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
}
