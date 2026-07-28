<?php

declare(strict_types=1);

final class DoctorAvailability
{
    public static function changedDaysBetween(int $doctorId, string $fromDate, string $toDate): array
    {
        $days = [];

        $stmt = db()->prepare(
            'SELECT session_date FROM doctor_availability
             WHERE doctor_id = ? AND session_date BETWEEN ? AND ?',
        );
        $stmt->execute([$doctorId, $fromDate, $toDate]);
        foreach ($stmt->fetchAll() as $row) {
            $days[$row['session_date']] = ['sessions' => [], 'breaks' => []];
        }

        $stmt = db()->prepare(
            'SELECT a.session_date, s.start_time, s.end_time, s.capacity
             FROM doctor_availability a
             JOIN availability_slot s ON s.availability_id = a.availability_id
             WHERE a.doctor_id = ? AND a.session_date BETWEEN ? AND ?
             ORDER BY s.start_time',
        );
        $stmt->execute([$doctorId, $fromDate, $toDate]);
        foreach ($stmt->fetchAll() as $row) {
            $days[$row['session_date']]['sessions'][] = $row;
        }

        $stmt = db()->prepare(
            'SELECT a.session_date, b.from_time, b.to_time, b.label
             FROM doctor_availability a
             JOIN schedule_break b ON b.availability_id = a.availability_id
             WHERE a.doctor_id = ? AND a.session_date BETWEEN ? AND ?
             ORDER BY b.from_time',
        );
        $stmt->execute([$doctorId, $fromDate, $toDate]);
        foreach ($stmt->fetchAll() as $row) {
            $days[$row['session_date']]['breaks'][] = $row;
        }

        return $days;
    }

    public static function saveDay(int $doctorId, string $date, array $sessions, array $breaks): int
    {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $pdo->prepare('DELETE FROM doctor_availability WHERE doctor_id = ? AND session_date = ?')
                ->execute([$doctorId, $date]);

            $pdo->prepare(
                "INSERT INTO doctor_availability (doctor_id, session_date, source) VALUES (?, ?, 'manual')",
            )->execute([$doctorId, $date]);
            $availabilityId = (int) $pdo->lastInsertId();

            $insertSlot = $pdo->prepare(
                'INSERT INTO availability_slot (availability_id, start_time, end_time, capacity) VALUES (?, ?, ?, ?)',
            );
            foreach ($sessions as $session) {
                $insertSlot->execute([$availabilityId, $session['start_time'], $session['end_time'], $session['capacity']]);
            }

            $insertBreak = $pdo->prepare(
                'INSERT INTO schedule_break (availability_id, from_time, to_time, label) VALUES (?, ?, ?, ?)',
            );
            foreach ($breaks as $break) {
                $insertBreak->execute([$availabilityId, $break['from_time'], $break['to_time'], $break['label']]);
            }

            $pdo->commit();

            return $availabilityId;
        } catch (Throwable $error) {
            $pdo->rollBack();
            throw $error;
        }
    }

    public static function resetDay(int $doctorId, string $date): void
    {
        db()->prepare('DELETE FROM doctor_availability WHERE doctor_id = ? AND session_date = ?')
            ->execute([$doctorId, $date]);
    }
}
