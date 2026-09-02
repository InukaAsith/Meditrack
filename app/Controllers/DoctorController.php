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

    private function cancelAppointmentsWithoutSession(int $doctorId): string
    {
        $upcoming = Appointment::upcomingForDoctor($doctorId);
        if ($upcoming === []) {
            return '';
        }

        $doctor = Doctor::findByStaffId($doctorId);
        $days = DoctorCalendar::days(
            $doctorId,
            (int) $doctor['uses_regular_schedule'] === 1,
            new DateTimeImmutable($upcoming[0]['appointment_date']),
            new DateTimeImmutable($upcoming[count($upcoming) - 1]['appointment_date']),
        );

        $cancelled = 0;
        $refunds = 0;
        foreach ($upcoming as $appointment) {
            $day = $days[$appointment['appointment_date']];
            if (DoctorCalendar::sessionIndexFor($day, $appointment['slot_time']) !== null) {
                continue;
            }

            $refund = $appointment['payment_timing'] === 'online';
            Appointment::cancelByDoctor((int) $appointment['appointment_id'], $refund);
            AuditLog::record('staff', current_staff_id(), 'update', 'appointment', (string) $appointment['appointment_id'], 'status, refund_status');

            $cancelled++;
            if ($refund) {
                $refunds++;
            }
        }

        if ($cancelled === 0) {
            return '';
        }

        return ' ' . $cancelled . ($cancelled === 1 ? ' appointment was' : ' appointments were') . ' cancelled'
            . ($refunds > 0 ? ' and ' . $refunds . ($refunds === 1 ? ' refund' : ' refunds') . ' queued' : '') . '.';
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

    public function availabilitySave(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/doctor/schedule');
        }

        $this->checkCsrf('/staff/doctor/schedule');
        $doctorId = $this->getDoctorId();

        $date = trim((string) ($_POST['date'] ?? ''));
        $back = $this->scheduleUrl((string) ($_POST['view'] ?? 'month'), $date);

        if (!$this->isDate($date)) {
            flash_error('Pick a day first.');
            $this->redirect('/staff/doctor/schedule');
        }

        $today = date('Y-m-d');
        $actionType = trim((string) ($_POST['action_type'] ?? ''));

        if ($actionType === 'add_break') {
            if ($date < $today) {
                flash_error('Breaks cannot be added to past dates.');
                $this->redirect($back);
            }
            if ($date > $today) {
                flash_error('Breaks can only be added for the current date.');
                $this->redirect($back);
            }
        } else {
            if ($date < $today) {
                flash_error('Past days can\'t be changed.');
                $this->redirect($back);
            }
            $minEditableDate = date('Y-m-d', strtotime('+7 days'));
            if ($date < $minEditableDate) {
                flash_error('Schedules cannot be changed less than 1 week in advance. This date is locked.');
                $this->redirect($back);
            }
        }

        $doctor = Doctor::findByStaffId($doctorId);
        $slotLength = (int) ($doctor['slot_length_min'] ?? 15);
        if ($slotLength < 5) {
            $slotLength = 15;
        }

        $sessions = [];
        foreach ([1, 2] as $number) {
            $start = trim((string) ($_POST['start_' . $number] ?? ''));
            $end = trim((string) ($_POST['end_' . $number] ?? ''));
            $capacityRaw = trim((string) ($_POST['capacity_' . $number] ?? ''));

            if ($start === '' && $end === '') {
                continue;
            }
            if (!$this->isTime($start) || !$this->isTime($end) || $start >= $end) {
                flash_error('Session ' . $number . ' needs a start time before its end time.');
                $this->redirect($back);
            }

            $duration = $this->minutes($end) - $this->minutes($start);
            if ($duration < $slotLength) {
                flash_error('Session ' . $number . ' duration (' . $duration . ' min) must be at least one slot length (' . $slotLength . ' min).');
                $this->redirect($back);
            }

            $maxSlots = intdiv($duration, $slotLength);
            $capacity = $capacityRaw !== '' ? (int) $capacityRaw : $maxSlots;

            if ($capacity < 1 || $capacity > $maxSlots) {
                flash_error('Session ' . $number . ' capacity cannot exceed ' . $maxSlots . ' (based on ' . $slotLength . '-minute slots for a ' . $duration . '-minute session).');
                $this->redirect($back);
            }

            $sessions[] = ['start_time' => $start, 'end_time' => $end, 'capacity' => $capacity];
        }

        if ($sessions === []) {
            flash_error('Enter a start and end time for the session.');
            $this->redirect($back);
        }

        if (count($sessions) === 2) {
            if (
                $sessions[0]['start_time'] < $sessions[1]['end_time']
                && $sessions[1]['start_time'] < $sessions[0]['end_time']
            ) {
                flash_error('The second session overlaps with the first session. Please adjust the hours so they do not overlap.');
                $this->redirect($back);
            }
            if ($sessions[0]['start_time'] === $sessions[1]['start_time']) {
                flash_error('Both sessions cannot start at the same time.');
                $this->redirect($back);
            }
            usort($sessions, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));
        }

        $breaks = [];
        if ($actionType === 'add_break') {
            foreach ((array) ($_POST['breaks'] ?? []) as $break) {
                if (!empty($break['remove'])) {
                    continue;
                }
                $breaks[] = [
                    'from_time' => trim((string) ($break['from'] ?? '')),
                    'to_time'   => trim((string) ($break['to'] ?? '')),
                    'label'     => trim((string) ($break['label'] ?? '')),
                ];
            }

            $newFrom = trim((string) ($_POST['new_break_from'] ?? ''));
            $newTo = trim((string) ($_POST['new_break_to'] ?? ''));
            $newLabel = trim((string) ($_POST['new_break_label'] ?? ''));

            $hasRemovedBreak = false;
            foreach ((array) ($_POST['breaks'] ?? []) as $b) {
                if (!empty($b['remove'])) {
                    $hasRemovedBreak = true;
                    break;
                }
            }

            if ($newFrom === '' && $newTo === '') {
                if (!$hasRemovedBreak) {
                    flash_error('Please specify both a start time and an end time to add a break.');
                    $this->redirect($back);
                }
            } else {
                $breaks[] = [
                    'from_time' => $newFrom,
                    'to_time'   => $newTo,
                    'label'     => $newLabel,
                ];
            }

            $startTimes = [];
            foreach ($breaks as $index => $break) {
                if (
                    !$this->isTime($break['from_time']) || !$this->isTime($break['to_time'])
                    || $break['from_time'] >= $break['to_time']
                ) {
                    flash_error('A break needs a start time before its end time.');
                    $this->redirect($back);
                }
                if (isset($startTimes[$break['from_time']])) {
                    flash_error('Two breaks can\'t start at the same time.');
                    $this->redirect($back);
                }
                $startTimes[$break['from_time']] = true;

                $inSession = false;
                foreach ($sessions as $session) {
                    if ($break['from_time'] >= $session['start_time'] && $break['to_time'] <= $session['end_time']) {
                        $inSession = true;
                        break;
                    }
                }
                if (!$inSession) {
                    flash_error('Break (' . $break['from_time'] . '–' . $break['to_time'] . ') must fall within today\'s session hours.');
                    $this->redirect($back);
                }

                $breaks[$index]['label'] = mb_substr($break['label'] !== '' ? $break['label'] : 'Break', 0, 60);
            }

            $breakCount = count($breaks);
            for ($i = 0; $i < $breakCount; $i++) {
                for ($j = $i + 1; $j < $breakCount; $j++) {
                    if ($breaks[$i]['from_time'] < $breaks[$j]['to_time'] && $breaks[$j]['from_time'] < $breaks[$i]['to_time']) {
                        flash_error('Breaks cannot overlap with each other.');
                        $this->redirect($back);
                    }
                }
            }
            usort($breaks, fn($a, $b) => strcmp($a['from_time'], $b['from_time']));
        }

        $availabilityId = DoctorAvailability::saveDay($doctorId, $date, $sessions, $breaks);
        AuditLog::record('staff', current_staff_id(), 'update', 'doctor_availability', (string) $availabilityId);

        if ($actionType === 'add_break') {
            flash_success('Updated breaks for today (' . date('j M Y', strtotime($date)) . ').');
        } else {
            flash_success('Saved schedule for ' . date('j M Y', strtotime($date)) . '.' . $this->cancelAppointmentsWithoutSession($doctorId));
        }
        $this->redirect($back);
    }

    public function availabilityReset(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/doctor/schedule');
        }

        $this->checkCsrf('/staff/doctor/schedule');
        $doctorId = $this->getDoctorId();

        $date = trim((string) ($_POST['date'] ?? ''));
        $back = $this->scheduleUrl((string) ($_POST['view'] ?? 'month'), $date);

        if (!$this->isDate($date) || $date < date('Y-m-d')) {
            flash_error('Past days can\'t be changed.');
            $this->redirect($back);
        }

        $minEditableDate = date('Y-m-d', strtotime('+7 days'));
        if ($date < $minEditableDate) {
            flash_error('Schedules cannot be changed less than 1 week in advance. This date is locked.');
            $this->redirect($back);
        }

        DoctorAvailability::resetDay($doctorId, $date);
        AuditLog::record('staff', current_staff_id(), 'delete', 'doctor_availability', $date);

        $doctor = Doctor::findByStaffId($doctorId);
        flash_success(date('j M Y', strtotime($date)) . ((int) $doctor['uses_regular_schedule'] === 1
            ? ' uses your weekly schedule again.'
            : ' is closed again.') . $this->cancelAppointmentsWithoutSession($doctorId));
        $this->redirect($back);
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

    public function leaveCreate(): void
    {
        $back = (string) ($_POST['back'] ?? '/staff/doctor/schedule');
        if (!str_starts_with($back, '/staff/doctor/')) {
            $back = '/staff/doctor/schedule';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($back);
        }

        $this->checkCsrf($back);
        $doctorId = $this->getDoctorId();

        if ((int) Doctor::findByStaffId($doctorId)['uses_regular_schedule'] !== 1) {
            flash_error('You don\'t have a weekly schedule, so there is no leave to mark. Days you don\'t open stay closed.');
            $this->redirect($back);
        }

        $startDate = trim((string) ($_POST['start_date'] ?? ''));
        $endDate = trim((string) ($_POST['end_date'] ?? ''));
        $reason = trim((string) ($_POST['reason'] ?? ''));

        if ($startDate === '' || $endDate === '') {
            flash_error('Both start date and end date are required.');
            $this->redirect($back);
        }

        if ($startDate > $endDate) {
            flash_error('End date cannot be earlier than start date.');
            $this->redirect($back);
        }

        $leaveId = DoctorLeave::create($doctorId, $startDate, $endDate, $reason, current_staff_id());
        AuditLog::record('staff', current_staff_id(), 'create', 'doctor_leave', (string) $leaveId);

        flash_success('Leave marked successfully for ' . $startDate . ($startDate !== $endDate ? ' to ' . $endDate : '') . '.'
            . $this->cancelAppointmentsWithoutSession($doctorId));
        $this->redirect($back);
    }

    public function leaveEdit(string $id = ''): void
    {
        $leaveId = (int) $id;
        $doctorId = $this->getDoctorId();
        $leave = DoctorLeave::find($leaveId);

        if (!$leave || (int) $leave['doctor_id'] !== $doctorId) {
            flash_error('Leave record not found.');
            $this->redirect('/staff/doctor/schedule');
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf('/staff/doctor/leave-edit/' . $leaveId);

            $startDate = trim((string) ($_POST['start_date'] ?? ''));
            $endDate = trim((string) ($_POST['end_date'] ?? ''));
            $reason = trim((string) ($_POST['reason'] ?? ''));

            if ($startDate === '' || $endDate === '') {
                $errors['dates'] = 'Both start date and end date are required.';
            } elseif ($startDate > $endDate) {
                $errors['dates'] = 'End date cannot be earlier than start date.';
            }

            if (!$errors) {
                DoctorLeave::update($leaveId, $doctorId, $startDate, $endDate, $reason);
                AuditLog::record('staff', current_staff_id(), 'update', 'doctor_leave', (string) $leaveId, 'start_date, end_date, reason');

                flash_success('Leave record updated successfully.' . $this->cancelAppointmentsWithoutSession($doctorId));
                $this->redirect('/staff/doctor/schedule');
            }

            $leave['start_date'] = $startDate;
            $leave['end_date'] = $endDate;
            $leave['reason'] = $reason;
        }

        $this->view('doctor/leave-edit', [
            'leave'   => $leave,
            'errors'  => $errors,
            'success' => get_flash_success(),
            'error'   => get_flash_error(),
        ]);
    }

    public function leaveDelete(string $id = ''): void
    {
        $back = (string) ($_POST['back'] ?? '/staff/doctor/schedule');
        if (!str_starts_with($back, '/staff/doctor/')) {
            $back = '/staff/doctor/schedule';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($back);
        }

        $this->checkCsrf($back);
        $leaveId = (int) $id;
        $doctorId = $this->getDoctorId();

        $leave = DoctorLeave::find($leaveId);
        if ($leave && (int) $leave['doctor_id'] === $doctorId) {
            DoctorLeave::delete($leaveId, $doctorId);
            AuditLog::record('staff', current_staff_id(), 'delete', 'doctor_leave', (string) $leaveId);
            flash_success('Leave record removed.');
        } else {
            flash_error('Leave record not found.');
        }

        $this->redirect($back);
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
