<?php

declare(strict_types=1);

class ManagerController extends Controller
{
    public function __construct()
    {
        require_staff_login('manager');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('manager/dashboard', ['charts' => ManagerCharts::dashboard()]);
    }
}
