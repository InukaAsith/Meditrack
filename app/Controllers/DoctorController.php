<?php

declare(strict_types=1);

class DoctorController extends Controller
{
    public function __construct()
    {
        require_staff_login('doctor');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $doctorId = $this->getDoctorId();
        $doctor = Doctor::findByStaffId($doctorId);

        $view = 'month';

        $date = (string) ($_GET['date'] ?? '');
        if (!$this->isDate($date)) {
            $date = date('Y-m-d');
        }
        $selected = new DateTimeImmutable($date);

        $monthStart = $selected->modify('first day of this month');
        $monthEnd = $selected->modify('last day of this month');

        $from = $monthStart;
        $to = $monthEnd;
        $usesWeekly = (int) ($doctor['uses_regular_schedule'] ?? 0) === 1;
        $days = $this->scheduleDays($doctorId, $from, $to, $usesWeekly);

        $previous = $monthStart->modify('-1 month');
        $next = $monthStart->modify('+1 month');
        $dateLabel = $selected->format('F Y');

        $todayStr = date('Y-m-d');
        if (isset($days[$todayStr])) {
            $todayDay = $days[$todayStr];
        } else {
            $todayDays = $this->scheduleDays($doctorId, new DateTimeImmutable($todayStr), new DateTimeImmutable($todayStr), $usesWeekly);
            $todayDay = $todayDays[$todayStr] ?? null;
        }

        $this->view('doctor/dashboard', [
            'view'              => $view,
            'date'              => $date,
            'today'             => $todayStr,
            'days'              => $days,
            'selectedDay'       => $days[$date] ?? $todayDay,
            'todayDay'          => $todayDay,
            'monthStart'        => $monthStart,
            'dateLabel'         => $dateLabel,
            'previousDate'      => $previous->format('Y-m-d'),
            'nextDate'          => $next->format('Y-m-d'),
            'defaultCapacity'   => (int) ($doctor['default_capacity'] ?? 20),
            'usesWeekly'        => $usesWeekly,
            'leaves'            => DoctorLeave::forDoctor($doctorId),
            'doctor'            => $doctor,
            'success'           => get_flash_success(),
            'error'             => get_flash_error(),
        ]);
    }

    public function currentPatient(): void
    {
        $this->view('doctor/current-patient');
    }

    public function labResults(): void
    {
        $this->view('doctor/lab-results');
    }

    public function queue(): void
    {
        $this->view('doctor/queue');
    }

    public function schedule(): void
    {
        $doctorId = $this->getDoctorId();
        $doctor = Doctor::findByStaffId($doctorId);

        $view = (string) ($_GET['view'] ?? 'month');
        if (!in_array($view, ['day', 'week', 'month'], true)) {
            $view = 'month';
        }

        $date = (string) ($_GET['date'] ?? '');
        if (!$this->isDate($date)) {
            $date = date('Y-m-d');
        }
        $selected = new DateTimeImmutable($date);

        $monthStart = $selected->modify('first day of this month');
        $monthEnd = $selected->modify('last day of this month');
        $weekStart = $selected->modify('monday this week');
        $weekEnd = $weekStart->modify('+6 days');

        $from = min($monthStart, $weekStart);
        $to = max($monthEnd, $weekEnd);
        $usesWeekly = (int) $doctor['uses_regular_schedule'] === 1;
        $days = $this->scheduleDays($doctorId, $from, $to, $usesWeekly);

        if ($view === 'day') {
            $previous = $selected->modify('-1 day');
            $next = $selected->modify('+1 day');
            $dateLabel = $selected->format('l j M Y');
        } elseif ($view === 'week') {
            $previous = $selected->modify('-7 days');
            $next = $selected->modify('+7 days');
            $dateLabel = $weekStart->format('j M') . ' – ' . $weekEnd->format('j M Y');
        } else {
            $previous = $monthStart->modify('-1 month');
            $next = $monthStart->modify('+1 month');
            $dateLabel = $selected->format('F Y');
        }

        [$sessions, $otherAppointments] = $this->sessionRows($days[$date], (int) $doctor['slot_length_min']);

        $this->view('doctor/schedule', [
            'view'              => $view,
            'date'              => $date,
            'today'             => date('Y-m-d'),
            'minEditableDate'   => (new DateTimeImmutable('today'))->modify('+7 days')->format('Y-m-d'),
            'days'              => $days,
            'selectedDay'       => $days[$date],
            'sessions'          => $sessions,
            'otherAppointments' => $otherAppointments,
            'monthStart'        => $monthStart,
            'weekStart'         => $weekStart,
            'dateLabel'         => $dateLabel,
            'previousDate'      => $previous->format('Y-m-d'),
            'nextDate'          => $next->format('Y-m-d'),
            'defaultCapacity'   => (int) $doctor['default_capacity'],
            'slotLength'        => (int) $doctor['slot_length_min'],
            'usesWeekly'        => $usesWeekly,
            'leaves'            => DoctorLeave::forDoctor($doctorId),
            'success'           => get_flash_success(),
            'error'             => get_flash_error(),
        ]);
    }

    private function scheduleDays(int $doctorId, DateTimeImmutable $from, DateTimeImmutable $to, bool $usesWeekly): array
    {
        $appointments = [];
        foreach (Appointment::forDoctorBetween($doctorId, $from->format('Y-m-d'), $to->format('Y-m-d')) as $appointment) {
            $appointments[$appointment['appointment_date']][] = $appointment;
        }

        $days = DoctorCalendar::days($doctorId, $usesWeekly, $from, $to);
        foreach ($days as $key => $day) {
            $capacity = 0;
            foreach ($day['sessions'] as $session) {
                $capacity += (int) $session['capacity'];
            }

            $days[$key]['appointments'] = $appointments[$key] ?? [];
            $days[$key]['capacity'] = $capacity;
        }

        return $days;
    }

    private function sessionRows(array $day, int $slotLength): array
    {
        $byTime = [];
        foreach ($day['appointments'] as $appointment) {
            $time = $appointment['slot_time'] === null ? '-' : substr($appointment['slot_time'], 0, 5);
            $byTime[$time][] = $appointment;
        }

        $sessions = [];
        foreach ($day['sessions'] as $session) {
            $start = substr($session['start_time'], 0, 5);
            $end = substr($session['end_time'], 0, 5);
            $rows = [];
            $booked = 0;
            $breaksShown = [];

            for ($minute = $this->minutes($start); $minute < $this->minutes($end); $minute += $slotLength) {
                $time = $this->clock($minute);

                $inBreak = null;
                foreach ($day['breaks'] as $break) {
                    if ($minute >= $this->minutes($break['from_time']) && $minute < $this->minutes($break['to_time'])) {
                        $inBreak = $break;
                    }
                }
                if ($inBreak !== null) {
                    if (!isset($breaksShown[$inBreak['from_time']])) {
                        $breaksShown[$inBreak['from_time']] = true;
                        $rows[] = [
                            'kind'  => 'break',
                            'time'  => substr($inBreak['from_time'], 0, 5),
                            'label' => $inBreak['label'] ?: 'Break',
                            'sub'   => substr($inBreak['from_time'], 0, 5) . ' – ' . substr($inBreak['to_time'], 0, 5),
                        ];
                    }
                    continue;
                }

                if (isset($byTime[$time])) {
                    foreach ($byTime[$time] as $appointment) {
                        $rows[] = $this->appointmentRow($appointment);
                        $booked++;
                    }
                    unset($byTime[$time]);
                } else {
                    $rows[] = ['kind' => 'open', 'time' => $time, 'label' => 'Open slot', 'sub' => ''];
                }
            }

            $sessions[] = [
                'title'    => $start . '–' . $end . ' session',
                'booked'   => $booked,
                'capacity' => (int) $session['capacity'],
                'rows'     => $rows,
            ];
        }

        $others = [];
        foreach ($byTime as $appointmentsAtTime) {
            foreach ($appointmentsAtTime as $appointment) {
                $others[] = $this->appointmentRow($appointment);
            }
        }

        return [$sessions, $others];
    }

    private function appointmentRow(array $appointment): array
    {
        $code = $appointment['patient_code'] ?? $appointment['appointment_code'];
        $visit = $appointment['visit_type'] === 'follow_up' ? 'Follow-up' : 'New';

        return [
            'kind'   => 'booked',
            'time'   => $appointment['slot_time'] === null ? '-' : substr($appointment['slot_time'], 0, 5),
            'label'  => $appointment['patient_name'],
            'sub'    => $code . ' · ' . $visit,
            'status' => $appointment['status'],
        ];
    }

    private function minutes(string $time): int
    {
        return (int) substr($time, 0, 2) * 60 + (int) substr($time, 3, 2);
    }

    private function clock(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }

    private function isDate(string $value): bool
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value;
    }

    private function isTime(string $value): bool
    {
        return preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $value) === 1;
    }

    private function scheduleUrl(string $view, string $date): string
    {
        if (!in_array($view, ['day', 'week', 'month'], true)) {
            $view = 'month';
        }
        if (!$this->isDate($date)) {
            $date = date('Y-m-d');
        }

        return '/staff/doctor/schedule?view=' . $view . '&date=' . $date;
    }

    private function getDoctorId(): int
    {
        $staffId = current_staff_id();
        if ($staffId === null) {
            $this->redirect('/staff/login');
        }

        return Doctor::ensureProfile($staffId);
    }

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }
}
