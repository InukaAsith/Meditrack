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
