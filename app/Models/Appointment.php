<?php

declare(strict_types=1);

final class Appointment
{
    public static function forDoctorBetween(int $doctorId, string $fromDate, string $toDate): array
    {
        $stmt = db()->prepare(
            "SELECT a.appointment_id, a.appointment_code, a.appointment_date, a.slot_time,
                    a.visit_type, a.status,
                    COALESCE(p.full_name, a.subject_full_name) AS patient_name,
                    p.patient_code
             FROM appointment a
             LEFT JOIN patient p ON p.patient_id = a.patient_id
             WHERE a.doctor_id = ?
               AND a.appointment_date BETWEEN ? AND ?
               AND a.status <> 'cancelled'
             ORDER BY a.appointment_date, a.slot_time",
        );
        $stmt->execute([$doctorId, $fromDate, $toDate]);

        return $stmt->fetchAll();
    }

    public static function upcomingForDoctor(int $doctorId): array
    {
        $stmt = db()->prepare(
            "SELECT appointment_id, appointment_date, slot_time, payment_timing
             FROM appointment
             WHERE doctor_id = ?
               AND status IN ('pending', 'confirmed', 'rescheduled')
               AND (appointment_date > CURDATE()
                    OR (appointment_date = CURDATE() AND (slot_time IS NULL OR slot_time >= CURTIME())))
             ORDER BY appointment_date, slot_time",
        );
        $stmt->execute([$doctorId]);

        return $stmt->fetchAll();
    }

    public static function bookedBetween(string $fromDate, string $toDate): array
    {
        $stmt = db()->prepare(
            "SELECT doctor_id, appointment_date, slot_time
             FROM appointment
             WHERE appointment_date BETWEEN ? AND ?
               AND status <> 'cancelled'",
        );
        $stmt->execute([$fromDate, $toDate]);

        return $stmt->fetchAll();
    }

    public static function cancelByDoctor(int $appointmentId, bool $refund): void
    {
        db()->prepare(
            "UPDATE appointment
             SET status = 'cancelled', cancel_reason = 'Doctor not available', refund_status = ?
             WHERE appointment_id = ?",
        )->execute([$refund ? 'queued' : 'none', $appointmentId]);
    }
}
