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

    public function financialReports(): void
    {
        $this->view('manager/financial-reports', ['charts' => ManagerCharts::financial()]);
    }

    public function approvals(): void
    {
        $requests = ApprovalRequest::allWaiting();

        foreach ($requests as $index => $request) {
            $requests[$index]['day_changes'] = [];
            if ($request['request_type'] !== 'regular_schedule') {
                continue;
            }

            $doctorId = (int) $request['doctor_id'];
            $now = $this->weekText(DoctorSchedule::blocks($doctorId, true));
            $new = $this->weekText(DoctorSchedule::blocks($doctorId, false));
            foreach ($now as $dayName => $text) {
                if ($text !== $new[$dayName]) {
                    $requests[$index]['day_changes'][] = ['day' => $dayName, 'from' => $text, 'to' => $new[$dayName]];
                }
            }
        }

        $this->view('manager/approvals', [
            'requests' => $requests,
            'success'  => get_flash_success(),
            'error'    => get_flash_error(),
        ]);
    }

    private function weekText(array $blocks): array
    {
        $dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];

        $hours = [];
        foreach ($blocks as $block) {
            $hours[(int) $block['day_of_week']][] = substr($block['start_time'], 0, 5) . '–' . substr($block['end_time'], 0, 5)
                . ' (' . (int) $block['capacity'] . ')';
        }

        $text = [];
        foreach ($dayNames as $dayNumber => $dayName) {
            $text[$dayName] = isset($hours[$dayNumber]) ? implode(' + ', $hours[$dayNumber]) : 'Day off';
        }

        return $text;
    }

    public function approvalDecide(string $id = ''): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/manager/approvals');
        }
        if (!csrf_check()) {
            flash_error('That form expired. Please try again.');
            $this->redirect('/staff/manager/approvals');
        }

        $request = ApprovalRequest::find((int) $id);
        $decision = (string) ($_POST['decision'] ?? '');
        if ($request === null || $request['status'] !== 'pending' || !in_array($decision, ['approve', 'reject'], true)) {
            flash_error('That request was already decided.');
            $this->redirect('/staff/manager/approvals');
        }

        $doctorId = (int) $request['doctor_id'];
        $approved = $decision === 'approve';

        $pdo = db();
        $pdo->beginTransaction();
        try {
            if ($request['request_type'] === 'fee_revision') {
                if ($approved) {
                    Doctor::updateFees($doctorId, $request['proposed_consultation_fee'], $request['proposed_followup_fee']);
                }
            } elseif ($approved) {
                DoctorSchedule::approveWaiting($doctorId);
            } else {
                DoctorSchedule::discardWaiting($doctorId);
            }

            ApprovalRequest::decide((int) $request['approval_request_id'], $approved ? 'approved' : 'rejected', current_staff_id());
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
        AuditLog::record('staff', current_staff_id(), $approved ? 'approve' : 'reject', 'approval_request', (string) $request['approval_request_id'], 'status');

        flash_success($approved ? 'Approved. The change is live.' : 'Rejected.');
        $this->redirect('/staff/manager/approvals');
    }
}
