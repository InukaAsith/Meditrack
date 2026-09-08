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

    public function analytics(string $tab = 'overview'): void
    {
        $tabs = ['overview', 'appointments', 'revenue', 'queue', 'patients', 'staff'];
        if (!in_array($tab, $tabs, true)) {
            $this->notFound();
        }

        $period = (int) ($_GET['period'] ?? 30);
        if (!isset(ManagerCharts::PERIODS[$period])) {
            $period = 30;
        }

        $this->view('manager/analytics-' . $tab, [
            'charts'     => ManagerCharts::$tab($period),
            'period'     => $period,
            'periodText' => ManagerCharts::PERIODS[$period],
        ]);
    }
}
