<?php
declare(strict_types=1);

final class DoctorLeave
{
    public static function forDoctor(int $doctorId): array
    {
        $stmt = db()->prepare(
            'SELECT doctor_leave_id, doctor_id, start_date, end_date, reason, created_by, created_at,
                    DATEDIFF(end_date, start_date) + 1 AS days_count
             FROM doctor_leave
             WHERE doctor_id = ?
             ORDER BY start_date DESC',
        );
        $stmt->execute([$doctorId]);

        return $stmt->fetchAll();
    }

    public static function find(int $leaveId): ?array
    {
        $stmt = db()->prepare(
            'SELECT doctor_leave_id, doctor_id, start_date, end_date, reason, created_by, created_at,
                    DATEDIFF(end_date, start_date) + 1 AS days_count
             FROM doctor_leave
             WHERE doctor_leave_id = ?
             LIMIT 1',
        );
        $stmt->execute([$leaveId]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public static function belongsToDoctor(int $leaveId, int $doctorId): bool
    {
        $stmt = db()->prepare(
            'SELECT 1 FROM doctor_leave WHERE doctor_leave_id = ? AND doctor_id = ? LIMIT 1',
        );
        $stmt->execute([$leaveId, $doctorId]);

        return $stmt->fetchColumn() !== false;
    }

    public static function create(
        int $doctorId,
        string $startDate,
        string $endDate,
        ?string $reason,
        int $staffId,
    ): int {
        $stmt = db()->prepare(
            'INSERT INTO doctor_leave (doctor_id, start_date, end_date, reason, created_by)
             VALUES (?, ?, ?, ?, ?)',
        );
        $stmt->execute([$doctorId, $startDate, $endDate, $reason ?: null, $staffId]);

        return (int) db()->lastInsertId();
    }

    public static function update(
        int $leaveId,
        int $doctorId,
        string $startDate,
        string $endDate,
        ?string $reason,
    ): bool {
        $stmt = db()->prepare(
            'UPDATE doctor_leave
             SET start_date = ?, end_date = ?, reason = ?
             WHERE doctor_leave_id = ? AND doctor_id = ?',
        );
        $stmt->execute([$startDate, $endDate, $reason ?: null, $leaveId, $doctorId]);

        return $stmt->rowCount() >= 0;
    }

    public static function delete(int $leaveId, int $doctorId): bool
    {
        $stmt = db()->prepare(
            'DELETE FROM doctor_leave WHERE doctor_leave_id = ? AND doctor_id = ?',
        );
        $stmt->execute([$leaveId, $doctorId]);

        return $stmt->rowCount() > 0;
    }

    public static function leaveDaysMap(int $doctorId): array
    {
        $stmt = db()->prepare(
            'SELECT start_date, end_date, reason FROM doctor_leave WHERE doctor_id = ?',
        );
        $stmt->execute([$doctorId]);
        $rows = $stmt->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $start = new DateTimeImmutable((string) $row['start_date']);
            $end = (new DateTimeImmutable((string) $row['end_date']))->modify('+1 day');
            $period = new DatePeriod($start, new DateInterval('P1D'), $end);
            foreach ($period as $dt) {
                $map[$dt->format('Y-m-d')] = [
                    'reason' => $row['reason'] ?? null,
                ];
            }
        }

        return $map;
    }

    public static function leaveDateStrings(int $doctorId): array
    {
        return array_keys(self::leaveDaysMap($doctorId));
    }
}
