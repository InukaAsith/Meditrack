<?php
declare(strict_types=1);

final class Doctor
{
    public static function findByStaffId(int $staffId): ?array
    {
        $stmt = db()->prepare(
            'SELECT d.*, s.full_name, s.employee_code, s.work_email, s.phone, sp.name AS specialty_name
             FROM doctor d
             JOIN staff s ON s.staff_id = d.staff_id
             LEFT JOIN specialty sp ON sp.specialty_id = d.specialty_id
             WHERE d.staff_id = ?
             LIMIT 1',
        );
        $stmt->execute([$staffId]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public static function ensureProfile(int $staffId): int
    {
        $existing = self::findByStaffId($staffId);
        if ($existing !== null) {
            return (int) $existing['staff_id'];
        }

        $specStmt = db()->query('SELECT specialty_id FROM specialty LIMIT 1');
        $specId = $specStmt->fetchColumn();
        if (!$specId) {
            db()->exec("INSERT INTO specialty (name) VALUES ('General Medicine')");
            $specId = (int) db()->lastInsertId();
        }

        $slmc = 'SLMC-' . $staffId . '-' . rand(1000, 9999);
        $insert = db()->prepare(
            'INSERT IGNORE INTO doctor (staff_id, slmc_number, specialty_id, consultation_fee)
             VALUES (?, ?, ?, 2500.00)',
        );
        $insert->execute([$staffId, $slmc, (int) $specId]);

        return $staffId;
    }

    public static function slmcTaken(string $slmcNumber, int $exceptStaffId = 0): bool
    {
        $stmt = db()->prepare('SELECT staff_id FROM doctor WHERE slmc_number = ? AND staff_id <> ? LIMIT 1');
        $stmt->execute([$slmcNumber, $exceptStaffId]);

        return $stmt->fetch() !== false;
    }

    public static function saveProfile(int $staffId, array $data): void
    {
        db()->prepare(
            'INSERT INTO doctor (staff_id, slmc_number, specialty_id, consultation_fee, followup_fee)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE slmc_number = VALUES(slmc_number), specialty_id = VALUES(specialty_id),
                                     consultation_fee = VALUES(consultation_fee), followup_fee = VALUES(followup_fee)',
        )->execute([
            $staffId,
            $data['slmc_number'],
            $data['specialty_id'],
            $data['consultation_fee'],
            $data['followup_fee'],
        ]);
    }

    public static function updateDefaults(int $staffId, int $slotLength, int $overtimeWarn, int $defaultCapacity): void
    {
        db()->prepare(
            'UPDATE doctor SET slot_length_min = ?, overtime_warn_min = ?, default_capacity = ? WHERE staff_id = ?',
        )->execute([$slotLength, $overtimeWarn, $defaultCapacity, $staffId]);
    }

    public static function setUsesRegularSchedule(int $staffId, bool $enabled): void
    {
        db()->prepare('UPDATE doctor SET uses_regular_schedule = ? WHERE staff_id = ?')
            ->execute([$enabled ? 1 : 0, $staffId]);
    }

    public static function setRosterEnabled(int $staffId, bool $enabled): void
    {
        db()->prepare('UPDATE doctor SET roster_api_enabled = ? WHERE staff_id = ?')
            ->execute([$enabled ? 1 : 0, $staffId]);
    }

    public static function updateFees(int $staffId, string $consultationFee, ?string $followupFee): void
    {
        db()->prepare('UPDATE doctor SET consultation_fee = ?, followup_fee = ? WHERE staff_id = ?')
            ->execute([$consultationFee, $followupFee, $staffId]);
    }

    public static function allBookable(): array
    {
        return db()->query(
            "SELECT d.staff_id, d.consultation_fee, d.slot_length_min, d.uses_regular_schedule,
                    s.full_name, sp.name AS specialty_name
             FROM doctor d
             JOIN staff s ON s.staff_id = d.staff_id
             LEFT JOIN specialty sp ON sp.specialty_id = d.specialty_id
             WHERE s.status = 'active'
             ORDER BY s.full_name",
        )->fetchAll();
    }

    public static function allSpecialties(): array
    {
        return db()->query('SELECT * FROM specialty ORDER BY name')->fetchAll();
    }
}
