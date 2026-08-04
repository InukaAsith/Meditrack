<?php

declare(strict_types=1);

class AdminController extends Controller
{
    public function __construct()
    {
        require_staff_login('admin');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('admin/dashboard');
    }

    private const PHOTO_FOLDER = '/uploads/staff/';

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }
}
