<?php
declare(strict_types=1);

class SupportingController extends Controller
{
    public function __construct()
    {
        require_staff_login('supporting');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('supporting/dashboard');
    }

    public function liveQueue(): void
    {
        $this->view('supporting/live-queue');
    }

    public function doctorSchedule(): void
    {
        $this->view('supporting/doctor-schedule');
    }

    public function vitalsHistory(): void
    {
        $this->view('supporting/vitals-history');
    }

    public function alerts(): void
    {
        $this->view('supporting/alerts');
    }

    public function profile(): void
    {
        $this->view('supporting/profile', ['deviceTrusted' => current_device_is_trusted()]);
    }
}
