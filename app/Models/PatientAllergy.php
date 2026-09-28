<?php

declare(strict_types=1);

final class PatientAllergy
{
    public static function forPatient(int $patientId): array
    {
        $stmt = db()->prepare(
            'SELECT allergen_name, added_by_role, added_at FROM patient_allergy
             WHERE patient_id = ? ORDER BY allergen_name',
        );
        $stmt->execute([$patientId]);

        return $stmt->fetchAll();
    }

    public static function add(int $patientId, string $allergenName, int $staffId): bool
    {
        $stmt = db()->prepare(
            "INSERT IGNORE INTO patient_allergy (patient_id, allergen_name, added_by_role, added_by_staff_id)
             VALUES (?, ?, 'receptionist', ?)",
        );
        $stmt->execute([$patientId, $allergenName, $staffId]);

        return $stmt->rowCount() === 1;
    }

    public static function remove(int $patientId, string $allergenName): bool
    {
        $stmt = db()->prepare(
            "DELETE FROM patient_allergy WHERE patient_id = ? AND allergen_name = ? AND added_by_role <> 'doctor'",
        );
        $stmt->execute([$patientId, $allergenName]);

        return $stmt->rowCount() === 1;
    }
}
