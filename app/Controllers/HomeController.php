<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('landing', ['dashboardUrl' => $this->dashboardUrl()]);
    }

    private function dashboardUrl(): ?string
    {
        if (signed_in_patient_id() !== null) {
            return '/app';
        }
        $role = signed_in_staff_role();
        if ($role !== null && $role !== '') {
            return '/staff/' . $role . '/dashboard';
        }

        return null;
    }
}
