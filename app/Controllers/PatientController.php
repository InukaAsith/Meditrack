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

    public function book(): void
    {
        $this->view('patient/book', ['linkedDay' => trim((string) ($_GET['day'] ?? ''))] + BookingCatalogue::build());
    }
}
