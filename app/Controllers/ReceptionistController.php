<?php

declare(strict_types=1);

class ReceptionistController extends Controller
{
    public function __construct()
    {
        require_staff_login('receptionist');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('receptionist/dashboard');
    }

    private const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    private const PHOTO_FOLDER = '/uploads/patients/';

    public function patients(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));

        $patients = [];
        foreach (Patient::search($search) as $patient) {
            $patient['age'] = $this->age($patient['date_of_birth']);
            $patients[] = $patient;
        }

        $this->view('receptionist/patients', [
            'search' => $search,
            'patients' => $patients,
            'success' => get_flash_success(),
        ]);
    }

    public function patientRegister(): void
    {
        $values = $this->emptyPatientForm();
        $errors = [];
        $clash = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf('/staff/receptionist/patient-register');
            $values = $this->readPatientForm();
            $values['nic'] = strtoupper(trim((string) ($_POST['nic'] ?? '')));
            $errors = $this->validatePatient($values, true);

            if (!$errors) {
                $clash = Patient::findClash($values['nic'], $values['mobile'], $values['email']);
                if ($clash) {
                    $errors['clash'] = 'This NIC, mobile or email is already registered.';
                }
            }

            if (!$errors) {
                $patientId = Patient::createAtReception($values, current_staff_id());
                $patientCode = sprintf('PT-%04d', $patientId);
                Patient::setPatientCode($patientId, $patientCode);
                AuditLog::record('staff', current_staff_id(), 'create', 'patient', (string) $patientId);

                flash_success($values['full_name'] . ' registered as ' . $patientCode . '. They sign in with their NIC as the password.');
                $this->redirect('/staff/receptionist/patient/' . $patientId);
            }
        }

        $this->view('receptionist/patient-register', [
            'values' => $values,
            'errors' => $errors,
            'clash' => $clash,
            'bloodTypes' => self::BLOOD_TYPES,
        ]);
    }

    public function patient(string $id = ''): void
    {
        $patient = $this->findPatientOr404($id);
        $patient['age'] = $this->age($patient['date_of_birth']);

        AuditLog::record('staff', current_staff_id(), 'view', 'patient', (string) $patient['patient_id']);

        $this->view('receptionist/patient-view', [
            'patient' => $patient,
            'allergies' => PatientAllergy::forPatient((int) $patient['patient_id']),
            'success' => get_flash_success(),
        ]);
    }

    public function patientEdit(string $id = ''): void
    {
        $patient = $this->findPatientOr404($id);
        $patientId = (int) $patient['patient_id'];
        $values = $patient;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf('/staff/receptionist/patient-edit/' . $patientId);
            $values = $this->readPatientForm();
            $values['nic'] = $patient['nic'];
            $errors = $this->validatePatient($values, false);

            if (!$errors && Patient::findClash($patient['nic'], $values['mobile'], $values['email'], $patientId)) {
                $errors['clash'] = 'Another patient already uses this mobile number or email.';
            }

            if (!$errors) {
                $changed = [];
                foreach (['full_name', 'date_of_birth', 'gender', 'blood_type', 'mobile', 'email', 'address'] as $column) {
                    if ((string) $values[$column] !== (string) $patient[$column]) {
                        $changed[] = $column;
                    }
                }

                if ($changed) {
                    Patient::updateDetails($patientId, $values);
                    AuditLog::record('staff', current_staff_id(), 'update', 'patient', (string) $patientId, implode(', ', $changed));
                    flash_success('Changes saved.');
                } else {
                    flash_success('Nothing was changed.');
                }
                $this->redirect('/staff/receptionist/patient/' . $patientId);
            }
        }

        $this->view('receptionist/patient-edit', [
            'patient' => $patient,
            'values' => $values,
            'errors' => $errors,
            'allergies' => PatientAllergy::forPatient($patientId),
            'bloodTypes' => self::BLOOD_TYPES,
            'success' => get_flash_success(),
            'error' => get_flash_error(),
        ]);
    }

    public function patientPhoto(string $id = ''): void
    {
        $patient = $this->findPatientForPost($id);
        $patientId = (int) $patient['patient_id'];
        $editPage = '/staff/receptionist/patient-edit/' . $patientId;

        $file = $_FILES['photo'] ?? null;
        if ($file === null || $file['error'] !== UPLOAD_ERR_OK) {
            flash_error('Choose a photo to upload.');
            $this->redirect($editPage);
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            flash_error('That photo is too big - the limit is 2 MB.');
            $this->redirect($editPage);
        }

        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $type = (string) mime_content_type($file['tmp_name']);
        if (!isset($extensions[$type])) {
            flash_error('The photo must be a JPG, PNG or WebP image.');
            $this->redirect($editPage);
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$type];
        $folder = dirname(__DIR__, 2) . '/public' . self::PHOTO_FOLDER;
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        move_uploaded_file($file['tmp_name'], $folder . $fileName);

        $this->deletePhotoFile($patient['photo_uri']);
        Patient::setPhoto($patientId, self::PHOTO_FOLDER . $fileName);
        AuditLog::record('staff', current_staff_id(), 'update', 'patient', (string) $patientId, 'photo_uri');

        flash_success('Photo updated.');
        $this->redirect($editPage);
    }

    public function patientPhotoDelete(string $id = ''): void
    {
        $patient = $this->findPatientForPost($id);
        $patientId = (int) $patient['patient_id'];

        $this->deletePhotoFile($patient['photo_uri']);
        Patient::setPhoto($patientId, null);
        AuditLog::record('staff', current_staff_id(), 'delete', 'patient_photo', (string) $patientId);

        flash_success('Photo removed.');
        $this->redirect('/staff/receptionist/patient-edit/' . $patientId);
    }

    public function patientAllergyAdd(string $id = ''): void
    {
        $patient = $this->findPatientForPost($id);
        $patientId = (int) $patient['patient_id'];
        $editPage = '/staff/receptionist/patient-edit/' . $patientId;

        $allergen = trim((string) ($_POST['allergen_name'] ?? ''));
        if ($allergen === '' || strlen($allergen) > 100) {
            flash_error('Type the allergy name (up to 100 characters).');
            $this->redirect($editPage);
        }

        if (PatientAllergy::add($patientId, $allergen, current_staff_id())) {
            AuditLog::record('staff', current_staff_id(), 'create', 'patient_allergy', (string) $patientId);
            flash_success($allergen . ' added to allergies.');
        } else {
            flash_error($allergen . ' is already on the list.');
        }
        $this->redirect($editPage);
    }

    public function patientAllergyRemove(string $id = ''): void
    {
        $patient = $this->findPatientForPost($id);
        $patientId = (int) $patient['patient_id'];

        $allergen = (string) ($_POST['allergen_name'] ?? '');
        if (!PatientAllergy::remove($patientId, $allergen)) {
            flash_error('Allergies added by a doctor cannot be removed.');
            $this->redirect('/staff/receptionist/patient-edit/' . $patientId);
        }
        AuditLog::record('staff', current_staff_id(), 'delete', 'patient_allergy', (string) $patientId);

        flash_success($allergen . ' removed from allergies.');
        $this->redirect('/staff/receptionist/patient-edit/' . $patientId);
    }

    public function patientDelete(string $id = ''): void
    {
        $patient = $this->findPatientForPost($id);
        $patientId = (int) $patient['patient_id'];

        $deletedAppointments = Patient::deletePersonalData($patientId);
        $this->deletePhotoFile($patient['photo_uri']);
        AuditLog::record('staff', current_staff_id(), 'delete', 'patient', (string) $patientId);
        foreach ($deletedAppointments as $appointmentId) {
            AuditLog::record('staff', current_staff_id(), 'delete', 'appointment', (string) $appointmentId);
        }

        $count = count($deletedAppointments);
        flash_success($patient['full_name'] . ' was deleted.'
            . ($count > 0 ? ' ' . $count . ($count === 1 ? ' upcoming appointment was' : ' upcoming appointments were') . ' removed, with no refund.' : ''));
        $this->redirect('/staff/receptionist/patients');
    }

    private function findPatientOr404(string $id): array
    {
        if (!ctype_digit($id)) {
            $this->notFound();
        }
        $patient = Patient::find((int) $id);
        if ($patient === false) {
            $this->notFound();
        }

        return $patient;
    }

    private function findPatientForPost(string $id): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->notFound();
        }
        $patient = $this->findPatientOr404($id);
        $this->checkCsrf('/staff/receptionist/patient-edit/' . $patient['patient_id']);

        return $patient;
    }

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }

    private function emptyPatientForm(): array
    {
        return [
            'full_name' => '',
            'nic' => '',
            'date_of_birth' => '',
            'gender' => '',
            'blood_type' => '',
            'mobile' => '',
            'email' => '',
            'address' => '',
            'pdpa_consent' => false,
        ];
    }

    private function readPatientForm(): array
    {
        $values = [];
        foreach (['full_name', 'date_of_birth', 'gender', 'blood_type', 'email', 'address'] as $field) {
            $value = trim((string) ($_POST[$field] ?? ''));
            $values[$field] = $value === '' ? null : $value;
        }
        $values['mobile'] = trim((string) ($_POST['mobile'] ?? ''));
        $values['pdpa_consent'] = isset($_POST['pdpa_consent']);

        return $values;
    }

    private function validatePatient(array $values, bool $isNewPatient): array
    {
        $errors = [];

        if ($values['full_name'] === null || mb_strlen($values['full_name']) > 120) {
            $errors['full_name'] = 'Enter the full name (up to 120 characters).';
        }

        if ($isNewPatient && !preg_match('/^([0-9]{9}[VX]|[0-9]{12})$/', $values['nic'])) {
            $errors['nic'] = 'Enter a valid NIC - 9 digits then V or X, or 12 digits.';
        }

        $birthday = $values['date_of_birth'] === null ? false : DateTime::createFromFormat('!Y-m-d', $values['date_of_birth']);
        if ($birthday === false || $birthday->format('Y-m-d') !== $values['date_of_birth']) {
            $errors['date_of_birth'] = 'Enter the date of birth.';
        } elseif ($birthday > new DateTime('today') || $birthday < new DateTime('-130 years')) {
            $errors['date_of_birth'] = 'That date of birth isn\'t possible.';
        }

        if (!in_array($values['gender'], ['male', 'female'], true)) {
            $errors['gender'] = 'Pick male or female.';
        }

        if ($values['blood_type'] !== null && !in_array($values['blood_type'], self::BLOOD_TYPES, true)) {
            $errors['blood_type'] = 'Pick a blood type from the list.';
        }

        if (!preg_match('/^0[0-9]{9}$/', $values['mobile'])) {
            $errors['mobile'] = 'Enter a 10-digit mobile number starting with 0, e.g. 0774521180.';
        }

        if ($values['email'] !== null && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'That email address doesn\'t look right.';
        } elseif ($values['email'] !== null && mb_strlen($values['email']) > 150) {
            $errors['email'] = 'Keep the email under 150 characters.';
        }

        if ($values['address'] !== null && mb_strlen($values['address']) > 255) {
            $errors['address'] = 'Keep the address under 255 characters.';
        }

        if ($isNewPatient && !$values['pdpa_consent']) {
            $errors['pdpa_consent'] = 'The patient must agree to the PDPA data policy first.';
        }

        return $errors;
    }

    private function age(?string $dateOfBirth): ?int
    {
        if ($dateOfBirth === null) {
            return null;
        }

        return (new DateTime($dateOfBirth))->diff(new DateTime('today'))->y;
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

    public function appointments(): void
    {
        $this->view('receptionist/appointments');
    }

    public function reschedule(): void
    {
        $this->view('receptionist/reschedule');
    }

    public function book(): void
    {
        $this->view('receptionist/book');
    }

    public function checkIn(): void
    {
        $this->view('receptionist/check-in');
    }

    public function doctorStatus(): void
    {
        $this->view('receptionist/doctor-status');
    }

    public function billing(): void
    {
        $this->view('receptionist/billing');
    }

    public function newInvoice(): void
    {
        $this->view('receptionist/new-invoice');
    }

    public function notifications(): void
    {
        $this->view('receptionist/notifications');
    }

    public function profile(): void
    {
        $this->view('receptionist/profile', ['deviceTrusted' => current_device_is_trusted()]);
    }
}
