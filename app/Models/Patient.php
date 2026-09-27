<?php

declare(strict_types=1);

final class Patient
{
    public static function findForSignIn(string $identifier): array|false
    {
        $stmt = db()->prepare(
            'SELECT patient_id, password_hash, must_change_password, account_status
             FROM patient
             WHERE nic = ? OR mobile = ? OR email = ?
             LIMIT 1',
        );
        $stmt->execute([$identifier, $identifier, $identifier]);

        return $stmt->fetch();
    }

    public static function identifiersTaken(string $nic, string $mobile, ?string $email): bool
    {
        $stmt = db()->prepare(
            'SELECT patient_id FROM patient
             WHERE nic = ? OR mobile = ? OR (email = ? AND email IS NOT NULL)
             LIMIT 1',
        );
        $stmt->execute([$nic, $mobile, $email]);

        return $stmt->fetch() !== false;
    }

    public static function create(array $data): int
    {
        db()->prepare(
            'INSERT INTO patient
                (patient_code, nic, full_name, date_of_birth, gender, blood_type, mobile, email, password_hash, pdpa_consent)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)',
        )->execute([
            'T' . uniqid(),
            $data['nic'],
            $data['full_name'],
            $data['date_of_birth'],
            $data['gender'],
            $data['blood_type'],
            $data['mobile'],
            $data['email'],
            $data['password_hash'],
        ]);

        return (int) db()->lastInsertId();
    }

    public static function setPatientCode(int $patientId, string $code): void
    {
        db()->prepare('UPDATE patient SET patient_code = ? WHERE patient_id = ?')
            ->execute([$code, $patientId]);
    }

    public static function search(string $text): array
    {
        if ($text === '') {
            $stmt = db()->prepare(
                "SELECT * FROM patient WHERE account_status = 'active'
                 ORDER BY created_at DESC, patient_id DESC LIMIT 20",
            );
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $like = '%' . $text . '%';
        $mobileLike = '%' . ltrim(str_replace(' ', '', $text), '0') . '%';
        $stmt = db()->prepare(
            "SELECT * FROM patient
             WHERE account_status = 'active'
               AND (full_name LIKE ? OR patient_code LIKE ? OR nic LIKE ? OR mobile LIKE ?)
             ORDER BY full_name
             LIMIT 20",
        );
        $stmt->execute([$like, $like, $like, $mobileLike]);

        return $stmt->fetchAll();
    }

    public static function find(int $patientId): array|false
    {
        $stmt = db()->prepare("SELECT * FROM patient WHERE patient_id = ? AND account_status = 'active'");
        $stmt->execute([$patientId]);

        return $stmt->fetch();
    }

    public static function findClash(string $nic, string $mobile, ?string $email, int $exceptPatientId = 0): array|false
    {
        $stmt = db()->prepare(
            'SELECT patient_id, patient_code, full_name, nic, mobile, email FROM patient
             WHERE (nic = ? OR mobile = ? OR (email = ? AND email IS NOT NULL))
               AND patient_id <> ?
             LIMIT 1',
        );
        $stmt->execute([$nic, $mobile, $email, $exceptPatientId]);

        return $stmt->fetch();
    }

    public static function createAtReception(array $details, int $staffId): int
    {
        db()->prepare(
            'INSERT INTO patient
                (patient_code, nic, full_name, date_of_birth, gender, blood_type, mobile, email, address,
                 password_hash, must_change_password, pdpa_consent, registered_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, ?)',
        )->execute([
            'T' . uniqid(),
            $details['nic'],
            $details['full_name'],
            $details['date_of_birth'],
            $details['gender'],
            $details['blood_type'],
            $details['mobile'],
            $details['email'],
            $details['address'],
            password_hash($details['nic'], PASSWORD_BCRYPT),
            $staffId,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function setNewPassword(int $patientId, string $passwordHash): void
    {
        db()->prepare('UPDATE patient SET password_hash = ?, must_change_password = 0 WHERE patient_id = ?')
            ->execute([$passwordHash, $patientId]);
    }

    public static function updateDetails(int $patientId, array $details): void
    {
        db()->prepare(
            'UPDATE patient
             SET full_name = ?, date_of_birth = ?, gender = ?, blood_type = ?, mobile = ?, email = ?, address = ?
             WHERE patient_id = ?',
        )->execute([
            $details['full_name'],
            $details['date_of_birth'],
            $details['gender'],
            $details['blood_type'],
            $details['mobile'],
            $details['email'],
            $details['address'],
            $patientId,
        ]);
    }

    public static function setPhoto(int $patientId, ?string $photoUri): void
    {
        db()->prepare('UPDATE patient SET photo_uri = ? WHERE patient_id = ?')
            ->execute([$photoUri, $patientId]);
    }

    public static function deletePersonalData(int $patientId): array
    {
        $db = db();
        $db->beginTransaction();
        try {
            $find = $db->prepare(
                "SELECT appointment_id FROM appointment
                 WHERE patient_id = ? AND appointment_date >= CURDATE()
                   AND status IN ('pending', 'confirmed', 'rescheduled')",
            );
            $find->execute([$patientId]);
            $appointmentIds = array_map('intval', $find->fetchAll(PDO::FETCH_COLUMN));

            foreach ($appointmentIds as $appointmentId) {
                $db->prepare('UPDATE appointment SET rescheduled_from_id = NULL WHERE rescheduled_from_id = ?')
                    ->execute([$appointmentId]);
                $db->prepare('DELETE FROM appointment WHERE appointment_id = ?')->execute([$appointmentId]);
            }

            $db->prepare('DELETE FROM patient_allergy WHERE patient_id = ?')->execute([$patientId]);
            $db->prepare(
                "UPDATE patient
                 SET nic = NULL, full_name = NULL, date_of_birth = NULL, gender = NULL, blood_type = NULL,
                     mobile = NULL, email = NULL, address = NULL, photo_uri = NULL, password_hash = NULL,
                     pdpa_consent = 0, account_status = 'deleted'
                 WHERE patient_id = ?",
            )->execute([$patientId]);
            $db->commit();
        } catch (Throwable $error) {
            $db->rollBack();
            throw $error;
        }

        return $appointmentIds;
    }
}
