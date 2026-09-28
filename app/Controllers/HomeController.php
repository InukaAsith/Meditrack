<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('landing', ['patientSignedIn' => signed_in_patient_id() !== null]);
    }
}
