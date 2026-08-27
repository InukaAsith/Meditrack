<?php

declare(strict_types=1);

final class DoctorCalendar
{
    public static function days(int $doctorId, bool $usesWeekly, DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        $weekly = [];
        if ($usesWeekly) {
            foreach (DoctorSchedule::blocks($doctorId, true) as $block) {
                $weekly[(int) $block['day_of_week']][] = $block;
            }
        }

        $changedDays = DoctorAvailability::changedDaysBetween($doctorId, $from->format('Y-m-d'), $to->format('Y-m-d'));
        $leaveMap = DoctorLeave::leaveDaysMap($doctorId);

        $days = [];
        for ($day = $from; $day <= $to; $day = $day->modify('+1 day')) {
            $key = $day->format('Y-m-d');

            if (isset($changedDays[$key])) {
                $sessions = $changedDays[$key]['sessions'];
                $breaks = $changedDays[$key]['breaks'];
            } else {
                $sessions = $weekly[(int) $day->format('N')] ?? [];
                $breaks = [];
            }

            $days[$key] = [
                'date'         => $day,
                'sessions'     => $sessions,
                'breaks'       => $breaks,
                'changed'      => isset($changedDays[$key]),
                'on_leave'     => isset($leaveMap[$key]),
                'leave_reason' => $leaveMap[$key]['reason'] ?? null,
            ];
        }

        return $days;
    }

    public static function sessionIndexFor(array $day, ?string $slotTime): ?int
    {
        if ($day['on_leave'] || $day['sessions'] === []) {
            return null;
        }
        if ($slotTime === null) {
            return 0;
        }

        $time = substr($slotTime, 0, 5);
        foreach ($day['sessions'] as $index => $session) {
            if ($time >= substr($session['start_time'], 0, 5) && $time < substr($session['end_time'], 0, 5)) {
                return $index;
            }
        }

        return null;
    }
}
