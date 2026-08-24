<?php

declare(strict_types=1);

class ReceptionistController extends Controller
{
    public function __construct()
    {
        require_staff_login('receptionist');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('receptionist/dashboard');
    }

    private const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    private const PHOTO_FOLDER = '/uploads/patients/';

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }
}
