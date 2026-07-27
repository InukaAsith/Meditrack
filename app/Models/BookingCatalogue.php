<?php

declare(strict_types=1);

final class BookingCatalogue
{
    private const MONTHS = 3;

    public static function build(): array
    {
        $now = new DateTimeImmutable();
        $from = $now->setTime(0, 0);
        $to = $from->modify('first day of this month')->modify('+' . self::MONTHS . ' months -1 day');
        $today = $from->format('Y-m-d');
        $timeNow = $now->format('H:i');

        $booked = [];
        foreach (Appointment::bookedBetween($today, $to->format('Y-m-d')) as $row) {
            $booked[(int) $row['doctor_id']][$row['appointment_date']][] = $row['slot_time'];
        }

        $doctors = [];
        $sessions = [];
        $specialties = [];
        foreach (Doctor::allBookable() as $doctor) {
            $doctorId = (int) $doctor['staff_id'];
            $doctors[$doctorId] = [
                'name'      => preg_match('/^Dr\.?\s/i', $doctor['full_name']) ? $doctor['full_name'] : 'Dr. ' . $doctor['full_name'],
                'initials'  => self::initials($doctor['full_name']),
                'specialty' => (string) $doctor['specialty_name'],
                'fee'       => (float) $doctor['consultation_fee'],
            ];
            $specialties[(string) $doctor['specialty_name']] = true;

            $days = DoctorCalendar::days($doctorId, (int) $doctor['uses_regular_schedule'] === 1, $from, $to);
            foreach ($days as $key => $day) {
                if ($day['on_leave'] || $day['sessions'] === []) {
                    continue;
                }

                $counts = array_fill(0, count($day['sessions']), 0);
                foreach ($booked[$doctorId][$key] ?? [] as $slotTime) {
                    $index = DoctorCalendar::sessionIndexFor($day, $slotTime);
                    if ($index !== null) {
                        $counts[$index]++;
                    }
                }

                $rows = [];
                foreach ($day['sessions'] as $index => $session) {
                    $start = substr($session['start_time'], 0, 5);
                    $end = substr($session['end_time'], 0, 5);
                    if ($key === $today && $end <= $timeNow) {
                        continue;
                    }

                    $rows[] = [
                        'label'    => date('g.i A', strtotime($start)),
                        'range'    => $start . '–' . $end,
                        'start'    => $start,
                        'capacity' => (int) $session['capacity'],
                        'booked'   => $counts[$index],
                        'minutes'  => (int) $doctor['slot_length_min'],
                    ];
                }

                if ($rows !== []) {
                    $sessions[$key][$doctorId] = $rows;
                }
            }
        }
        ksort($sessions);

        return [
            'doctors'     => $doctors,
            'sessions'    => $sessions,
            'specialties' => array_keys($specialties),
            'calYear'     => (int) $from->format('Y'),
            'calMonth'    => (int) $from->format('n'),
            'calMonths'   => self::MONTHS,
        ];
    }

    private static function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim(preg_replace('/^Dr\.?\s*/i', '', $name)));
        $first = $words[0] ?? '';
        $last = count($words) > 1 ? $words[count($words) - 1] : '';

        return strtoupper(mb_substr($first, 0, 1) . mb_substr($last, 0, 1));
    }
}
