<?php

declare(strict_types=1);

class AdminController extends Controller
{
    public function __construct()
    {
        require_staff_login('admin');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('admin/dashboard');
    }

    private const PHOTO_FOLDER = '/uploads/staff/';

    public function staffAccounts(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $roleFilter = trim((string) ($_GET['role'] ?? 'All'));

        $staffRows = Staff::search($search, $roleFilter);
        $roles = Staff::allRoles();

        $staffList = [];
        foreach ($staffRows as $row) {
            $staffList[] = [
                'staff_id' => (int) $row['staff_id'],
                'code' => (string) $row['employee_code'],
                'name' => (string) $row['full_name'],
                'initials' => initials((string) $row['full_name']),
                'tone' => $this->roleTone((string) $row['role_name']),
                'role' => (string) $row['role_name'],
                'email' => (string) $row['work_email'],
                'phone' => (string) ($row['phone'] ?? ''),
                'photo_uri' => $row['photo_uri'],
                'status' => (string) $row['status'],
                'created' => date('d M Y', strtotime((string) $row['created_at'])),
            ];
        }

        $this->view('admin/staff-accounts', [
            'staffList' => $staffList,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'roles' => $roles,
            'success' => get_flash_success(),
            'error' => get_flash_error(),
        ]);
    }

    public function staffCreate(): void
    {
        $nextCode = Staff::nextEmployeeCode();
        $roles = Staff::allRoles();
        $values = [
            'full_name' => '',
            'work_email' => '',
            'phone' => '',
            'role_name' => 'Receptionist',
            'temp_password' => 'Passw0rd!',
            'slmc_number' => '',
            'specialty_id' => '',
            'consultation_fee' => '',
            'followup_fee' => '',
        ];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf('/staff/admin/staff-create');

            $values['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
            $values['work_email'] = trim((string) ($_POST['work_email'] ?? ''));
            $values['phone'] = $this->localPhone((string) ($_POST['phone'] ?? ''));
            $values['role_name'] = trim((string) ($_POST['role_name'] ?? 'Receptionist'));
            $values['temp_password'] = (string) ($_POST['temp_password'] ?? 'Passw0rd!');
            $values = $this->readDoctorFields($values);

            $errors = $this->validateStaff($values, true);
            if ($values['role_name'] === 'Doctor') {
                $errors += $this->validateDoctor($values, 0);
            }

            if (!$errors && Staff::emailTaken($values['work_email'])) {
                $errors['work_email'] = 'Another staff member already uses this email.';
            }

            $roleId = Staff::findRoleId($values['role_name']);
            if (!$errors && $roleId === null) {
                $errors['role_name'] = 'Pick one of the staff roles.';
            }

            $photoUri = null;
            $photoError = $_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE;
            if ($photoError !== UPLOAD_ERR_OK && $photoError !== UPLOAD_ERR_NO_FILE) {
                $errors['photo'] = 'That photo could not be uploaded. Try a smaller one.';
            }
            if (!$errors && $photoError === UPLOAD_ERR_OK) {
                $uploadRes = $this->saveUploadedPhoto($_FILES['photo']);
                if (isset($uploadRes['error'])) {
                    $errors['photo'] = $uploadRes['error'];
                } else {
                    $photoUri = $uploadRes['path'];
                }
            }

            if (!$errors && $roleId !== null) {
                $passwordHash = password_hash($values['temp_password'], PASSWORD_BCRYPT);

                db()->beginTransaction();
                $newId = Staff::create([
                    'employee_code' => $nextCode,
                    'full_name' => $values['full_name'],
                    'work_email' => $values['work_email'],
                    'role_id' => $roleId,
                    'password_hash' => $passwordHash,
                    'phone' => $values['phone'] === '' ? null : $values['phone'],
                    'photo_uri' => $photoUri,
                ]);
                if ($values['role_name'] === 'Doctor') {
                    Doctor::saveProfile($newId, $this->doctorData($values));
                }
                db()->commit();

                AuditLog::record('staff', current_staff_id() ?? $newId, 'create', 'staff', (string) $newId);
                if ($values['role_name'] === 'Doctor') {
                    AuditLog::record('staff', current_staff_id() ?? $newId, 'create', 'doctor', (string) $newId);
                }
                flash_success("{$values['full_name']} ({$nextCode}) added. Temporary password: {$values['temp_password']}");
                $this->redirect('/staff/admin/staff-accounts');
            }
        }

        $this->view('admin/staff-create', [
            'nextCode' => $nextCode,
            'roles' => $roles,
            'specialties' => Doctor::allSpecialties(),
            'values' => $values,
            'errors' => $errors,
            'error' => get_flash_error(),
        ]);
    }

    public function staffEdit(string $id = ''): void
    {
        $staff = $this->findStaffOr404($id);
        $staffId = (int) $staff['staff_id'];

        $values = [
            'full_name' => $staff['full_name'],
            'work_email' => $staff['work_email'],
            'phone' => $staff['phone'] ?? '',
            'role_name' => $staff['role_name'],
        ];
        $doctor = Doctor::findByStaffId($staffId);
        $values['slmc_number'] = $doctor['slmc_number'] ?? '';
        $values['specialty_id'] = (string) ($doctor['specialty_id'] ?? '');
        $values['consultation_fee'] = (string) ($doctor['consultation_fee'] ?? '');
        $values['followup_fee'] = (string) ($doctor['followup_fee'] ?? '');
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf('/staff/admin/staff-edit/' . $staffId);

            $values['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
            $values['work_email'] = trim((string) ($_POST['work_email'] ?? ''));
            $values['phone'] = $this->localPhone((string) ($_POST['phone'] ?? ''));
            $values = $this->readDoctorFields($values);

            $errors = $this->validateStaff($values, false);
            if ($values['role_name'] === 'Doctor') {
                $errors += $this->validateDoctor($values, $staffId);
            }

            if (!$errors && Staff::emailTaken($values['work_email'], $staffId)) {
                $errors['work_email'] = 'Another staff member already uses this email.';
            }

            if (!$errors) {
                $changed = [];
                if ($values['full_name'] !== $staff['full_name']) $changed[] = 'full_name';
                if ($values['work_email'] !== $staff['work_email']) $changed[] = 'work_email';
                if ($values['phone'] !== ($staff['phone'] ?? '')) $changed[] = 'phone';

                db()->beginTransaction();
                Staff::updateDetails($staffId, [
                    'full_name' => $values['full_name'],
                    'work_email' => $values['work_email'],
                    'phone' => $values['phone'] === '' ? null : $values['phone'],
                ]);
                if ($values['role_name'] === 'Doctor') {
                    Doctor::saveProfile($staffId, $this->doctorData($values));
                }
                db()->commit();

                if ($changed) {
                    AuditLog::record('staff', current_staff_id() ?? $staffId, 'update', 'staff', (string) $staffId, implode(', ', $changed));
                }
                $doctorChanged = $this->changedDoctorFields($doctor, $values);
                if ($doctorChanged) {
                    AuditLog::record('staff', current_staff_id() ?? $staffId, 'update', 'doctor', (string) $staffId, implode(', ', $doctorChanged));
                }

                flash_success('Changes saved.');
                $this->redirect('/staff/admin/staff-edit/' . $staffId);
            }
        }

        $this->view('admin/staff-edit', [
            'staff' => $staff,
            'specialties' => Doctor::allSpecialties(),
            'values' => $values,
            'errors' => $errors,
            'success' => get_flash_success(),
            'error' => get_flash_error(),
        ]);
    }

    public function staffPhoto(string $id = ''): void
    {
        $staff = $this->findStaffForPost($id);
        $staffId = (int) $staff['staff_id'];
        $editPage = '/staff/admin/staff-edit/' . $staffId;

        $file = $_FILES['photo'] ?? null;
        if ($file === null || $file['error'] !== UPLOAD_ERR_OK) {
            flash_error('Pick a photo to upload.');
            $this->redirect($editPage);
        }

        $res = $this->saveUploadedPhoto($file);
        if (isset($res['error'])) {
            flash_error($res['error']);
            $this->redirect($editPage);
        }

        $this->deletePhotoFile($staff['photo_uri']);
        Staff::setPhoto($staffId, $res['path']);
        AuditLog::record('staff', current_staff_id() ?? $staffId, 'update', 'staff', (string) $staffId, 'photo_uri');

        flash_success('Photo updated.');
        $this->redirect($editPage);
    }

    public function staffPhotoDelete(string $id = ''): void
    {
        $staff = $this->findStaffForPost($id);
        $staffId = (int) $staff['staff_id'];

        $this->deletePhotoFile($staff['photo_uri']);
        Staff::setPhoto($staffId, null);
        AuditLog::record('staff', current_staff_id() ?? $staffId, 'delete', 'staff_photo', (string) $staffId);

        flash_success('Photo removed.');
        $this->redirect('/staff/admin/staff-edit/' . $staffId);
    }

    public function staffDeactivate(string $id = ''): void
    {
        $staff = $this->findStaffForPost($id);
        $staffId = (int) $staff['staff_id'];

        if ($staffId === current_staff_id()) {
            flash_error("You can't turn off your own account.");
            $this->redirect('/staff/admin/staff-edit/' . $staffId);
        }

        Staff::setStatus($staffId, 'deactivated');
        AuditLog::record('staff', current_staff_id() ?? $staffId, 'deactivate', 'staff', (string) $staffId);

        $cancelled = 0;
        $refunds = 0;
        if (Doctor::findByStaffId($staffId) !== null) {
            foreach (Appointment::upcomingForDoctor($staffId) as $appointment) {
                $refund = $appointment['payment_timing'] === 'online';
                Appointment::cancelByDoctor((int) $appointment['appointment_id'], $refund);
                AuditLog::record('staff', current_staff_id() ?? $staffId, 'update', 'appointment', (string) $appointment['appointment_id'], 'status, refund_status');
                $cancelled++;
                if ($refund) {
                    $refunds++;
                }
            }
        }

        flash_success('Account turned off.'
            . ($cancelled > 0
                ? ' ' . $cancelled . ($cancelled === 1 ? ' booking was' : ' bookings were') . ' cancelled'
                  . ($refunds > 0 ? ' and ' . $refunds . ($refunds === 1 ? ' refund' : ' refunds') . ' queued' : '') . '.'
                : ''));
        $this->redirect('/staff/admin/staff-edit/' . $staffId);
    }

    public function staffReactivate(string $id = ''): void
    {
        $staff = $this->findStaffForPost($id);
        $staffId = (int) $staff['staff_id'];

        Staff::setStatus($staffId, 'active');
        AuditLog::record('staff', current_staff_id() ?? $staffId, 'reactivate', 'staff', (string) $staffId);

        flash_success('Account turned back on.');
        $this->redirect('/staff/admin/staff-edit/' . $staffId);
    }

    public function staffResetPassword(string $id = ''): void
    {
        $staff = $this->findStaffForPost($id);
        $staffId = (int) $staff['staff_id'];

        $tempPassword = 'Passw0rd!';
        $passwordHash = password_hash($tempPassword, PASSWORD_BCRYPT);
        Staff::resetPassword($staffId, $passwordHash);
        AuditLog::record('staff', current_staff_id() ?? $staffId, 'reset_password', 'staff', (string) $staffId);

        flash_success("Password reset to {$tempPassword}.");
        $this->redirect('/staff/admin/staff-edit/' . $staffId);
    }

    private function findStaffOr404(string $id): array
    {
        if (!ctype_digit($id)) {
            $this->notFound();
        }
        $staff = Staff::find((int) $id);
        if ($staff === false) {
            $this->notFound();
        }

        return $staff;
    }

    private function findStaffForPost(string $id): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->notFound();
        }
        $staff = $this->findStaffOr404($id);
        $this->checkCsrf('/staff/admin/staff-edit/' . $staff['staff_id']);

        return $staff;
    }

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }

    private function validateStaff(array $values, bool $isNew): array
    {
        $errors = [];

        if (empty($values['full_name']) || strlen($values['full_name']) > 120) {
            $errors['full_name'] = 'Enter their full name.';
        }

        if (empty($values['work_email']) || filter_var($values['work_email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['work_email'] = 'Enter a valid email.';
        } elseif (strlen($values['work_email']) > 150) {
            $errors['work_email'] = 'That email is too long.';
        }

        if ($values['phone'] === '') {
            $errors['phone'] = 'Enter their phone number.';
        } elseif (!preg_match('/^0[0-9]{9}$/', $values['phone'])) {
            $errors['phone'] = 'Enter a 10-digit number starting with 0, e.g. 0771234567.';
        }

        if ($isNew && (empty($values['temp_password']) || strlen($values['temp_password']) < 8)) {
            $errors['temp_password'] = 'Use at least 8 characters.';
        }

        return $errors;
    }

    private function localPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-]/', '', trim($phone));
        if (str_starts_with($phone, '+94')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '94') && strlen($phone) === 11) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }

    private function readDoctorFields(array $values): array
    {
        $values['slmc_number'] = trim((string) ($_POST['slmc_number'] ?? ''));
        $values['specialty_id'] = trim((string) ($_POST['specialty_id'] ?? ''));
        $values['consultation_fee'] = trim((string) ($_POST['consultation_fee'] ?? ''));
        $values['followup_fee'] = trim((string) ($_POST['followup_fee'] ?? ''));

        return $values;
    }

    private function validateDoctor(array $values, int $staffId): array
    {
        $errors = [];

        if ($values['slmc_number'] === '' || strlen($values['slmc_number']) > 20) {
            $errors['slmc_number'] = 'Enter their SLMC number.';
        } elseif (Doctor::slmcTaken($values['slmc_number'], $staffId)) {
            $errors['slmc_number'] = 'Another doctor already has this SLMC number.';
        }

        $specialtyIds = [];
        foreach (Doctor::allSpecialties() as $specialty) {
            $specialtyIds[] = (string) $specialty['specialty_id'];
        }
        if (!in_array($values['specialty_id'], $specialtyIds, true)) {
            $errors['specialty_id'] = 'Pick a specialty.';
        }

        if (!$this->isFee($values['consultation_fee'])) {
            $errors['consultation_fee'] = 'Enter a fee between Rs. 1 and Rs. 100,000, e.g. 2500.';
        }
        if ($values['followup_fee'] !== '' && !$this->isFee($values['followup_fee'])) {
            $errors['followup_fee'] = 'Enter a fee between Rs. 1 and Rs. 100,000, or leave it blank.';
        }

        return $errors;
    }

    private function isFee(string $amount): bool
    {
        return preg_match('/^[0-9]{1,6}(\.[0-9]{1,2})?$/', $amount) === 1
            && (float) $amount >= 1
            && (float) $amount <= 100000;
    }

    private function doctorData(array $values): array
    {
        return [
            'slmc_number' => $values['slmc_number'],
            'specialty_id' => (int) $values['specialty_id'],
            'consultation_fee' => $values['consultation_fee'],
            'followup_fee' => $values['followup_fee'] === '' ? null : $values['followup_fee'],
        ];
    }

    private function changedDoctorFields(?array $doctor, array $values): array
    {
        if ($values['role_name'] !== 'Doctor') {
            return [];
        }
        if ($doctor === null) {
            return ['slmc_number', 'specialty_id', 'consultation_fee', 'followup_fee'];
        }

        $changed = [];
        if ($values['slmc_number'] !== $doctor['slmc_number']) $changed[] = 'slmc_number';
        if ((int) $values['specialty_id'] !== (int) $doctor['specialty_id']) $changed[] = 'specialty_id';
        if ((float) $values['consultation_fee'] !== (float) $doctor['consultation_fee']) $changed[] = 'consultation_fee';
        $oldFollowup = $doctor['followup_fee'] === null ? '' : (string) (float) $doctor['followup_fee'];
        $newFollowup = $values['followup_fee'] === '' ? '' : (string) (float) $values['followup_fee'];
        if ($newFollowup !== $oldFollowup) $changed[] = 'followup_fee';

        return $changed;
    }

    private function saveUploadedPhoto(array $file): array
    {
        if ($file['size'] > 2 * 1024 * 1024) {
            return ['error' => 'That photo is bigger than 2 MB.'];
        }

        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $type = (string) mime_content_type($file['tmp_name']);
        if (!isset($extensions[$type])) {
            return ['error' => 'The photo must be a JPG, PNG or WebP image.'];
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$type];
        $folder = dirname(__DIR__, 2) . '/public' . self::PHOTO_FOLDER;
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        move_uploaded_file($file['tmp_name'], $folder . $fileName);

        return ['path' => self::PHOTO_FOLDER . $fileName];
    }

    private function deletePhotoFile(?string $photoUri): void
    {
        if ($photoUri === null || !str_starts_with($photoUri, self::PHOTO_FOLDER)) {
            return;
        }
        $file = dirname(__DIR__, 2) . '/public' . self::PHOTO_FOLDER . basename($photoUri);
        if (is_file($file)) {
            unlink($file);
        }
    }

    private function roleTone(string $role): string
    {
        return match ($role) {
            'Doctor' => 'blue',
            'Receptionist' => 'teal',
            'Supporting Staff' => 'red',
            'Pharmacist' => 'green',
            'Manager' => 'amber',
            'Admin' => 'purple',
            default => 'blue',
        };
    }

    public function auditTrail(): void
    {
        $changes = AuditLog::allRecordChanges();
        $logins = AuditLog::allLoginHistory();

        $allStaff = Staff::all();
        $users = [];
        foreach ($allStaff as $s) {
            $users[] = (string) $s['full_name'];
        }
        foreach ($changes as $c) {
            if (!empty($c['user'])) {
                $users[] = (string) $c['user'];
            }
        }
        $users = array_values(array_unique($users));
        sort($users);

        $roles = [];
        foreach (Staff::allRoles() as $r) {
            $roles[] = (string) $r['role_name'];
        }
        $roles[] = 'Patient';

        $records = [];
        foreach ($changes as $c) {
            $records[] = (string) $c['entity'];
        }
        $records = array_values(array_unique($records));
        sort($records);

        $queueEvents = [];

        $this->view('admin/audit-trail', [
            'changes' => $changes,
            'logins' => $logins,
            'queueEvents' => $queueEvents,
            'users' => $users,
            'roles' => $roles,
            'records' => $records,
        ]);
    }

    public function auditExport(): void
    {
        $changes = AuditLog::allRecordChanges();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="audit-trail-' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Time', 'Date', 'User', 'Role', 'Record', 'Record ID', 'Action', 'Changed Fields']);

        foreach ($changes as $r) {
            $fieldDesc = !empty($r['fields'])
                ? implode(', ', $r['fields'])
                : ($r['action'] === 'create' ? 'Created' : ($r['action'] === 'view' ? 'Opened' : ($r['action'] === 'delete' ? 'Removed' : '')));

            fputcsv($out, [
                $r['time'],
                $r['date'],
                $r['user'],
                $r['role'],
                $r['entity'],
                $r['pk'],
                ucfirst(str_replace('_', ' ', $r['action'])),
                $fieldDesc,
            ]);
        }

        fclose($out);
        exit;
    }

    public function configuration(): void
    {
        $this->view('admin/configuration');
    }

    public function templates(): void
    {
        $this->view('admin/templates');
    }

    public function profile(): void
    {
        $this->view('admin/profile', ['deviceTrusted' => current_device_is_trusted()]);
    }

    public function notifications(): void
    {
        $this->view('admin/notifications');
    }
}
