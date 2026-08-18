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
