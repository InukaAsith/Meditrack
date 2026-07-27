<?php

declare(strict_types=1);

class StaffAuthController extends Controller
{
    private const DEMO_OTP_CODE = '123456';

    public function index(): void
    {
        $this->redirect('/staff/login');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkPassword();
        }

        $this->view('staff/login');
    }

    public function changePassword(): void
    {
        if (empty($_SESSION['pending_staff_id'])) {
            $this->redirect('/staff/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveNewPassword();
        }

        $this->view('staff/change-password');
    }

    public function otp(): void
    {
        $staffId = $this->requireVerifiedPasswordStep();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkOtp($staffId);
        }

        $this->view('staff/otp');
    }

    public function forgetDevice(): void
    {
        $staffId = current_staff_id();
        if ($staffId === null) {
            $this->redirect('/staff/login');
        }

        $profileUrl = '/staff/' . current_staff_role() . '/profile';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
            $this->redirect($profileUrl);
        }

        StaffTrustedDevice::delete($staffId, trusted_device_token());
        $this->setTrustedDeviceCookie('', time() - 3600);
        AuditLog::record('staff', $staffId, 'trusted_device_removed');

        $this->redirect($profileUrl);
    }

    public function logout(): void
    {
        $staffId = current_staff_id();
        if ($staffId !== null) {
            AuditLog::record('staff', $staffId, 'logout');
        }
        $_SESSION = [];
        session_destroy();
        $this->redirect('/staff/login');
    }

    private function checkPassword(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/staff/login');
        }

        $identifier = trim((string) ($_POST['staff_id'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $row = Staff::findForSignIn($identifier);

        if ($row === false || !password_verify($password, $row['password_hash'])) {
            flash_error('Incorrect staff ID/email or password.');
            $this->redirect('/staff/login');
        }

        if ($row['status'] !== 'active') {
            flash_error('This account has been deactivated. Contact your administrator.');
            $this->redirect('/staff/login');
        }

        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['pending_staff_id'] = (int) $row['staff_id'];
        $_SESSION['pending_staff_role'] = staff_role_slug($row['role_name']);

        if ($row['must_change_password']) {
            $this->redirect('/staff/change-password');
        }

        if (StaffTrustedDevice::isTrusted((int) $row['staff_id'], trusted_device_token())) {
            $this->finishSignIn((int) $row['staff_id']);
        }
        $this->redirect('/staff/otp');
    }

    private function saveNewPassword(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/staff/change-password');
        }

        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        $passwordError = password_rule_error($password, $confirm);
        if ($passwordError !== null) {
            flash_error($passwordError);
            $this->redirect('/staff/change-password');
        }

        $staffId = (int) $_SESSION['pending_staff_id'];

        if (password_verify($password, Staff::passwordHash($staffId))) {
            flash_error('Your new password must be different from your temporary password.');
            $this->redirect('/staff/change-password');
        }

        Staff::setNewPassword($staffId, password_hash($password, PASSWORD_BCRYPT));
        AuditLog::record('staff', $staffId, 'password_change');

        $this->redirect('/staff/otp');
    }

    private function checkOtp(int $staffId): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/staff/otp');
        }

        $code = trim((string) ($_POST['otp'] ?? ''));
        if ($code !== self::DEMO_OTP_CODE) {
            flash_error('That code was incorrect. (Demo code: 123456.)');
            $this->redirect('/staff/otp');
        }

        if (isset($_POST['remember_device'])) {
            $token = StaffTrustedDevice::create($staffId);
            $this->setTrustedDeviceCookie($token, time() + StaffTrustedDevice::DAYS * 24 * 60 * 60);
        }

        $this->finishSignIn($staffId);
    }

    private function finishSignIn(int $staffId): never
    {
        $role = (string) $_SESSION['pending_staff_role'];

        unset($_SESSION['pending_staff_id'], $_SESSION['pending_staff_role']);
        session_regenerate_id(true);
        $_SESSION['staff_id'] = $staffId;
        $_SESSION['staff_role'] = $role;

        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $browser = 'Chrome on Windows';
        if (str_contains($ua, 'Firefox')) {
            $browser = 'Firefox on Windows';
        } elseif (str_contains($ua, 'Edg')) {
            $browser = 'Edge on Windows';
        } elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) {
            $browser = 'Chrome on macOS';
        }

        AuditLog::record('staff', $staffId, 'login', null, null, $ip . ' | ' . $browser);

        $this->redirect('/staff/' . $role . '/dashboard');
    }

    private function setTrustedDeviceCookie(string $token, int $expires): void
    {
        setcookie(TRUSTED_DEVICE_COOKIE, $token, [
            'expires' => $expires,
            'path' => '/staff',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function requireVerifiedPasswordStep(): int
    {
        if (empty($_SESSION['pending_staff_id'])) {
            $this->redirect('/staff/login');
        }

        $staffId = (int) $_SESSION['pending_staff_id'];
        if (Staff::mustChangePassword($staffId)) {
            $this->redirect('/staff/change-password');
        }

        return $staffId;
    }
}
