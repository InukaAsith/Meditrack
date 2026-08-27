<?php

declare(strict_types=1);

final class DoctorSchedule
{
    public static function blocks(int $doctorId, bool $approved): array
    {
        $stmt = db()->prepare(
            'SELECT day_of_week, start_time, end_time, capacity
             FROM doctor_regular_schedule
             WHERE doctor_id = ? AND is_active = ?
             ORDER BY day_of_week, start_time',
        );
        $stmt->execute([$doctorId, $approved ? 1 : 0]);

        return $stmt->fetchAll();
    }

    public static function saveWaiting(int $doctorId, array $blocks): void
    {
        db()->prepare('DELETE FROM doctor_regular_schedule WHERE doctor_id = ? AND is_active = 0')
            ->execute([$doctorId]);

        $insert = db()->prepare(
            'INSERT INTO doctor_regular_schedule (doctor_id, day_of_week, start_time, end_time, capacity, is_active)
             VALUES (?, ?, ?, ?, ?, 0)',
        );
        foreach ($blocks as $block) {
            $insert->execute([
                $doctorId,
                $block['day_of_week'],
                $block['start_time'],
                $block['end_time'],
                $block['capacity'],
            ]);
        }
    }

    public static function approveWaiting(int $doctorId): void
    {
        db()->prepare('DELETE FROM doctor_regular_schedule WHERE doctor_id = ? AND is_active = 1')
            ->execute([$doctorId]);
        db()->prepare('UPDATE doctor_regular_schedule SET is_active = 1 WHERE doctor_id = ? AND is_active = 0')
            ->execute([$doctorId]);
    }

    public static function discardWaiting(int $doctorId): void
    {
        db()->prepare('DELETE FROM doctor_regular_schedule WHERE doctor_id = ? AND is_active = 0')
            ->execute([$doctorId]);
    }
}
