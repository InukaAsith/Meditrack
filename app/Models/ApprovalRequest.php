<?php

declare(strict_types=1);

final class ApprovalRequest
{
    public static function waitingFor(int $doctorId, string $type): ?array
    {
        $stmt = db()->prepare(
            "SELECT * FROM approval_request
             WHERE doctor_id = ? AND request_type = ? AND status = 'pending'
             LIMIT 1",
        );
        $stmt->execute([$doctorId, $type]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public static function submit(int $doctorId, string $type, ?string $consultationFee, ?string $followupFee, string $details): int
    {
        $waiting = self::waitingFor($doctorId, $type);

        if ($waiting !== null) {
            db()->prepare(
                'UPDATE approval_request
                 SET proposed_consultation_fee = ?, proposed_followup_fee = ?, details = ?, requested_at = NOW()
                 WHERE approval_request_id = ?',
            )->execute([$consultationFee, $followupFee, $details, $waiting['approval_request_id']]);

            return (int) $waiting['approval_request_id'];
        }

        db()->prepare(
            'INSERT INTO approval_request (doctor_id, request_type, proposed_consultation_fee, proposed_followup_fee, details)
             VALUES (?, ?, ?, ?, ?)',
        )->execute([$doctorId, $type, $consultationFee, $followupFee, $details]);

        return (int) db()->lastInsertId();
    }

    public static function allWaiting(): array
    {
        return db()->query(
            "SELECT r.*, s.full_name AS doctor_name, d.consultation_fee, d.followup_fee
             FROM approval_request r
             JOIN doctor d ON d.staff_id = r.doctor_id
             JOIN staff s ON s.staff_id = r.doctor_id
             WHERE r.status = 'pending'
             ORDER BY r.requested_at",
        )->fetchAll();
    }

    public static function find(int $requestId): ?array
    {
        $stmt = db()->prepare('SELECT * FROM approval_request WHERE approval_request_id = ? LIMIT 1');
        $stmt->execute([$requestId]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public static function decide(int $requestId, string $status, ?int $managerId): void
    {
        db()->prepare(
            'UPDATE approval_request SET status = ?, decided_by = ?, decided_at = NOW() WHERE approval_request_id = ?',
        )->execute([$status, $managerId, $requestId]);
    }
}
