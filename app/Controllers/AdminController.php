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

    public function staffAccounts(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $roleFilter = trim((string) ($_GET['role'] ?? 'All'));

        $staffRows = Staff::search($search, $roleFilter);
        $roles = Staff::allRoles();

        $staffList = [];
        foreach ($staffRows as $row) {
            $staffList[] = [
                'staff_id' => (int) $row['staff_id'],
                'code' => (string) $row['employee_code'],
                'name' => (string) $row['full_name'],
                'initials' => initials((string) $row['full_name']),
                'tone' => $this->roleTone((string) $row['role_name']),
                'role' => (string) $row['role_name'],
                'email' => (string) $row['work_email'],
                'phone' => (string) ($row['phone'] ?? ''),
                'photo_uri' => $row['photo_uri'],
                'status' => (string) $row['status'],
                'created' => date('d M Y', strtotime((string) $row['created_at'])),
            ];
        }

        $this->view('admin/staff-accounts', [
            'staffList' => $staffList,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'roles' => $roles,
            'success' => get_flash_success(),
            'error' => get_flash_error(),
        ]);
    }

    private function checkCsrf(string $backTo): void
    {
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect($backTo);
        }
    }

    private function roleTone(string $role): string
    {
        return match ($role) {
            'Doctor' => 'blue',
            'Receptionist' => 'teal',
            'Supporting Staff' => 'red',
            'Pharmacist' => 'green',
            'Manager' => 'amber',
            'Admin' => 'purple',
            default => 'blue',
        };
    }
}
