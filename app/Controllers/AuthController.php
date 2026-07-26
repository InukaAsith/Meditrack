<?php

declare(strict_types=1);

class AuthController extends Controller
{
    private const DEMO_OTP_CODE = '123456';
    private const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->signIn();
        }

        $tab = 'signin';
        if (str_starts_with($_SERVER['REQUEST_URI'], '/forgot-password')) {
            $tab = 'forgot';
        }
        $this->showAuthPage($tab);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createDraftAccount();
        }

        $this->showAuthPage('register');
    }

    public function otp(): void
    {
        if (empty($_SESSION['register_draft'])) {
            $this->redirect('/register');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->finishRegistration();
        }

        $this->view('auth/otp');
    }

    public function changePassword(): void
    {
        if (empty($_SESSION['pending_patient_id'])) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveNewPassword();
        }

        $this->view('auth/change-password');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }

    private function showAuthPage(string $tab): void
    {
        $tabFromUrl = $_GET['tab'] ?? '';
        if (in_array($tabFromUrl, ['signin', 'register', 'forgot'], true)) {
            $tab = $tabFromUrl;
        }

        $this->view('auth/login', ['tab' => $tab, 'bloodTypes' => self::BLOOD_TYPES]);
    }

    private function signIn(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/login');
        }

        $identifier = trim((string) ($_POST['identifier'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (str_starts_with($identifier, '+')) {
            $identifier = '0' . substr($identifier, 3);
        } elseif (str_starts_with($identifier, '94') && strlen($identifier) === 11) {
            $identifier = '0' . substr($identifier, 2);
        }

        $row = Patient::findForSignIn($identifier);

        if (
            $row === false || $row['password_hash'] === null
            || !password_verify($password, $row['password_hash'])
            || $row['account_status'] !== 'active'
        ) {
            flash_error('Incorrect email/mobile or password.');
            $this->redirect('/login');
        }

        $patientId = (int) $row['patient_id'];

        $_SESSION = [];
        session_regenerate_id(true);

        if ((int) $row['must_change_password'] === 1) {
            $_SESSION['pending_patient_id'] = $patientId;
            $this->redirect('/change-password');
        }

        $_SESSION['patient_id'] = $patientId;
        AuditLog::record('patient', $patientId, 'login');

        if (isset($_POST['remember'])) {
            setcookie(session_name(), session_id(), [
                'expires' => time() + REMEMBER_ME_SECONDS,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        $this->redirect('/app');
    }

    private function saveNewPassword(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/change-password');
        }

        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        $passwordError = password_rule_error($password, $confirm);
        if ($passwordError !== null) {
            flash_error($passwordError);
            $this->redirect('/change-password');
        }

        $patientId = (int) $_SESSION['pending_patient_id'];
        Patient::setNewPassword($patientId, password_hash($password, PASSWORD_BCRYPT));
        AuditLog::record('patient', $patientId, 'password_change');

        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['patient_id'] = $patientId;
        AuditLog::record('patient', $patientId, 'login');

        $this->redirect('/app');
    }

    private function createDraftAccount(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/register');
        }

        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $nic = trim((string) ($_POST['nic'] ?? ''));
        $mobile = trim((string) ($_POST['mobile'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $dateOfBirth = trim((string) ($_POST['date_of_birth'] ?? ''));
        $gender = (string) ($_POST['gender'] ?? '');
        $bloodType = (string) ($_POST['blood_type'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');
        $consent = isset($_POST['pdpa_consent']);

        if ($fullName === '' || $nic === '' || $mobile === '' || $email === '' || $dateOfBirth === '') {
            flash_error('Please fill in every field.');
            $this->redirect('/register');
        }
        if (!preg_match('/^0[0-9]{9}$/', $mobile)) {
            flash_error('Enter a 10-digit mobile number starting with 0, e.g. 0771234567.');
            $this->redirect('/register');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_error('That email address does not look right.');
            $this->redirect('/register');
        }
        if (date('Y-m-d', strtotime($dateOfBirth)) !== $dateOfBirth || $dateOfBirth > date('Y-m-d')) {
            flash_error('Please enter a real date of birth.');
            $this->redirect('/register');
        }
        if (!in_array($gender, ['male', 'female'], true)) {
            flash_error('Please pick a gender.');
            $this->redirect('/register');
        }
        if ($bloodType === '') {
            $bloodType = null;
        } elseif (!in_array($bloodType, self::BLOOD_TYPES, true)) {
            flash_error('Please pick a blood type from the list.');
            $this->redirect('/register');
        }
        $passwordError = password_rule_error($password, $confirm);
        if ($passwordError !== null) {
            flash_error($passwordError);
            $this->redirect('/register');
        }
        if (!$consent) {
            flash_error('You need to agree to the PDPA data policy to create an account.');
            $this->redirect('/register');
        }

        if (Patient::identifiersTaken($nic, $mobile, $email)) {
            flash_error('An account already exists with that NIC, mobile number or email.');
            $this->redirect('/register');
        }

        $_SESSION['register_draft'] = [
            'full_name' => $fullName,
            'nic' => $nic,
            'mobile' => $mobile,
            'email' => $email,
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'blood_type' => $bloodType,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $this->redirect('/otp');
    }

    private function finishRegistration(): never
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/otp');
        }

        $code = trim((string) ($_POST['otp'] ?? ''));
        if ($code !== self::DEMO_OTP_CODE) {
            flash_error('That code was incorrect. (Demo code: 123456.)');
            $this->redirect('/otp');
        }

        $draft = $_SESSION['register_draft'];

        if (Patient::identifiersTaken($draft['nic'], $draft['mobile'], $draft['email'])) {
            unset($_SESSION['register_draft']);
            flash_error('That NIC, mobile number or email was registered while you were verifying. Please sign in instead.');
            $this->redirect('/login');
        }

        $patientId = Patient::create($draft);
        Patient::setPatientCode($patientId, sprintf('PT-%04d', $patientId));
        AuditLog::record('patient', $patientId, 'register');

        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['patient_id'] = $patientId;

        $this->redirect('/app');
    }
}
