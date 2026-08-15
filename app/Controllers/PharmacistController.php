<?php

declare(strict_types=1);

class PharmacistController extends Controller
{
    public function __construct()
    {
        require_staff_login('pharmacist');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('pharmacist/dashboard');
    }

    public function dispense(): void
    {
        $this->view('pharmacist/dispense');
    }

    public function prepareQueue(): void
    {
        $this->view('pharmacist/prepare-queue');
    }

    public function inventory(): void
    {
        $search = isset($_GET['q']) ? trim((string)$_GET['q']) : null;
        $statusFilter = isset($_GET['status']) ? trim((string)$_GET['status']) : null;

        $items = Medicine::getAllWithBatches($search, $statusFilter);
        $counts = Medicine::getCounts();

        $this->view('pharmacist/inventory', [
            'items' => $items,
            'counts' => $counts,
            'search' => $search ?? '',
            'statusFilter' => $statusFilter ?? 'all',
            'flashSuccess' => get_flash_success(),
            'flashError' => get_flash_error(),
        ]);
    }

    public function inventoryConfig(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/pharmacist/inventory');
        }

        if (!csrf_check()) {
            $this->jsonResponse(['ok' => false, 'error' => 'This page expired. Refresh it and try again.'], 403);
            return;
        }

        $input = $this->readRequestData();

        $medicineId = (int)($input['medicine_id'] ?? 0);
        $threshold = max(0, (int)($input['threshold'] ?? 0));
        $price = max(0.0, (float)($input['price'] ?? 0.0));
        $requiresRx = !empty($input['requires_rx']) && ($input['requires_rx'] === true || $input['requires_rx'] === '1' || $input['requires_rx'] === 'on');
        $damagedOverride = !empty($input['damaged_override']) && ($input['damaged_override'] === true || $input['damaged_override'] === '1' || $input['damaged_override'] === 'on');
        $isAvailable = !$damagedOverride;

        if ($medicineId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'That medicine was not found.'], 400);
            return;
        }

        $success = Medicine::updateConfig($medicineId, $threshold, $price, $requiresRx, $isAvailable);

        if ($success) {
            $staffId = current_staff_id() ?? 0;
            AuditLog::record(
                'staff',
                $staffId,
                'update_config',
                'medicine',
                (string)$medicineId,
                'reorder_threshold, unit_price, requires_prescription, is_available'
            );

            $this->jsonResponse([
                'ok' => true,
                'message' => 'Settings saved.',
                'data' => [
                    'medicine_id' => $medicineId,
                    'threshold' => $threshold,
                    'price' => $price,
                    'requires_rx' => $requiresRx,
                    'is_available' => $isAvailable,
                ],
            ]);
        } else {
            $this->jsonResponse(['ok' => false, 'error' => 'The settings could not be saved. Try again.'], 500);
        }
    }

    public function inventoryAdjust(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/pharmacist/inventory');
        }

        if (!csrf_check()) {
            $this->jsonResponse(['ok' => false, 'error' => 'This page expired. Refresh it and try again.'], 403);
            return;
        }

        $input = $this->readRequestData();

        $medicineId = (int)($input['medicine_id'] ?? 0);
        $batchId = isset($input['batch_id']) && (int)$input['batch_id'] > 0 ? (int)$input['batch_id'] : null;
        $delta = (int)($input['quantity'] ?? 0);

        $rawReason = (string)($input['reason'] ?? '');
        $reason = $this->normalizeReason($rawReason);
        $note = trim((string)($input['note'] ?? $rawReason));

        if ($medicineId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'That medicine was not found.'], 400);
            return;
        }

        if ($delta === 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'Enter how many units to add or remove.'], 400);
            return;
        }

        $staffId = current_staff_id() ?? 0;
        $result = Medicine::adjustStock($medicineId, $batchId, $delta, $reason, $note, $staffId);

        if ($result['success']) {
            AuditLog::record(
                'staff',
                $staffId,
                'stock_adjustment',
                'medicine_batch',
                (string)($result['batch_id'] ?? $batchId),
                "delta: {$delta}, reason: {$reason}"
            );

            $this->jsonResponse([
                'ok' => true,
                'message' => "Stock changed by {$delta}.",
                'data' => $result,
            ]);
        } else {
            $this->jsonResponse(['ok' => false, 'error' => $result['message'] ?? 'The stock could not be changed. Try again.'], 500);
        }
    }

    public function inventoryBatchRemove(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/pharmacist/inventory');
        }

        if (!csrf_check()) {
            $this->jsonResponse(['ok' => false, 'error' => 'This page expired. Refresh it and try again.'], 403);
            return;
        }

        $input = $this->readRequestData();
        $batchId = (int)($input['batch_id'] ?? 0);

        if ($batchId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'That batch was not found.'], 400);
            return;
        }

        $staffId = current_staff_id() ?? 0;
        $result = Medicine::removeBatch($batchId, $staffId);

        if ($result['success']) {
            AuditLog::record(
                'staff',
                $staffId,
                'remove_batch',
                'medicine_batch',
                (string)$batchId,
                'status: damaged, quantity: 0'
            );

            $this->jsonResponse([
                'ok' => true,
                'message' => 'Batch removed.',
                'data' => $result,
            ]);
        } else {
            $this->jsonResponse(['ok' => false, 'error' => 'The batch could not be removed. Try again.'], 500);
        }
    }

    private function readRequestData(): array
    {
        $input = $_POST;
        if (empty($input)) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $input = $json;
                }
            }
        }
        return $input;
    }

    private function normalizeReason(string $raw): string
    {
        $lower = strtolower($raw);
        if (str_contains($lower, 'damage')) {
            return 'damaged';
        }
        if (str_contains($lower, 'baseline') || str_contains($lower, 'intake')) {
            return 'baseline_intake';
        }
        if (str_contains($lower, 'return')) {
            return 'order_return';
        }
        return 'audit_correction';
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($accept, 'application/json');

        if (!$isAjax && isset($_POST['redirect_back'])) {
            if ($payload['ok'] ?? false) {
                flash_success($payload['message'] ?? 'Action completed');
            } else {
                flash_error($payload['error'] ?? 'An error occurred');
            }
            $this->redirect('/staff/pharmacist/inventory');
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }
}
