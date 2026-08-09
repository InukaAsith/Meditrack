<?php

declare(strict_types=1);

class PatientController extends Controller
{
    public function __construct()
    {
        require_patient_login();
    }

    public function index(): void
    {
        $this->view('patient/dashboard');
    }

    public function home(): void
    {
        $this->view('patient/dashboard');
    }

    public function yourHealth(): void
    {
        $this->view('patient/your-health');
    }

    public function book(): void
    {
        $this->view('patient/book', ['linkedDay' => trim((string) ($_GET['day'] ?? ''))] + BookingCatalogue::build());
    }

    public function appointments(): void
    {
        $this->view('patient/appointments');
    }

    public function liveQueue(): void
    {
        $this->view('patient/live-queue');
    }

    public function records(): void
    {
        $this->view('patient/records');
    }

    public function prescriptions(): void
    {
        $this->view('patient/prescriptions');
    }

    public function pharmacyChoose(): void
    {
        $this->view('patient/pharmacy-choose');
    }

    public function pharmacyOrder(): void
    {
        $this->view('patient/pharmacy-order');
    }

    public function pharmacyOtc(): void
    {
        $this->view('patient/pharmacy-otc');
    }

    public function pharmacyPhoto(): void
    {
        $this->view('patient/pharmacy-photo');
    }
}
