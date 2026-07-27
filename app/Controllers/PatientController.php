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
}
