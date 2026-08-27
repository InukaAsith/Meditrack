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

    public function registerBatch(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_check()) {
                flash_error('This page expired. Try again.');
                $this->redirect('/staff/pharmacist/register-batch');
            }

            $commercialName = trim((string)($_POST['commercial_name'] ?? ''));
            $generic = trim((string)($_POST['generic'] ?? ''));
            $unitForm = strtolower(trim((string)($_POST['unit_form'] ?? 'tablet')));
            $manufacturer = trim((string)($_POST['manufacturer'] ?? ''));
            $storageLimits = trim((string)($_POST['storage_limits'] ?? ''));
            $supplierName = trim((string)($_POST['supplier'] ?? ''));
            $invoiceRef = trim((string)($_POST['invoice_ref'] ?? ''));
            $batchCode = strtoupper(trim((string)($_POST['batch_id'] ?? '')));

            $qtyRaw = (string)($_POST['qty_received'] ?? '');
            preg_match('/\d+/', str_replace(',', '', $qtyRaw), $qtyMatches);
            $qty = isset($qtyMatches[0]) ? (int)$qtyMatches[0] : 0;

            $costRaw = (string)($_POST['total_cost'] ?? '');
            preg_match('/[\d\.]+/', str_replace(',', '', $costRaw), $costMatches);
            $totalCost = isset($costMatches[0]) ? (float)$costMatches[0] : 0.0;

            $expiryRaw = trim((string)($_POST['expiry_date'] ?? ''));
            $expiryDate = null;
            if (preg_match('/(\d{1,2})\s*[\/\-]\s*(\d{1,2})\s*[\/\-]\s*(\d{4})/', $expiryRaw, $m)) {
                $expiryDate = sprintf('%04d-%02d-%02d', (int)$m[3], (int)$m[2], (int)$m[1]);
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiryRaw)) {
                $expiryDate = $expiryRaw;
            } else {
                $expiryDate = date('Y-m-d', strtotime('+1 year'));
            }

            if ($commercialName !== '' && $batchCode !== '' && $qty > 0) {
                $pdo = db();
                $staffId = current_staff_id() ?? 1;

                $supplierId = Supplier::findOrCreate($supplierName);

                $mStmt = $pdo->prepare('SELECT medicine_id FROM medicine WHERE commercial_name = ? LIMIT 1');
                $mStmt->execute([$commercialName]);
                $medicineId = $mStmt->fetchColumn();

                if (!$medicineId) {
                    $insMed = $pdo->prepare(
                        'INSERT INTO medicine (commercial_name, generic_name, unit_form, manufacturer, storage_limits, unit_price, reorder_threshold)
                         VALUES (?, ?, ?, ?, ?, ?, ?)'
                    );
                    $insMed->execute([
                        $commercialName,
                        $generic !== '' ? $generic : $commercialName,
                        $unitForm,
                        $manufacturer,
                        $storageLimits,
                        25.00,
                        40,
                    ]);
                    $medicineId = $pdo->lastInsertId();
                }

                $insBatch = $pdo->prepare(
                    'INSERT INTO medicine_batch (batch_code, medicine_id, supplier_id, supplier_invoice_ref, quantity_received, quantity_on_hand, cost_price_total, expiry_date, registered_by)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE quantity_on_hand = quantity_on_hand + VALUES(quantity_on_hand)'
                );
                $insBatch->execute([
                    $batchCode,
                    $medicineId,
                    $supplierId,
                    $invoiceRef,
                    $qty,
                    $qty,
                    $totalCost,
                    $expiryDate,
                    $staffId,
                ]);

                AuditLog::record(
                    'staff',
                    $staffId,
                    'register_batch',
                    'medicine_batch',
                    $batchCode,
                    "qty: {$qty}, supplier: {$supplierName}"
                );

                flash_success("Batch {$batchCode} added to the inventory.");
                $this->redirect('/staff/pharmacist/inventory');
            }
        }

        $suppliers = Supplier::all();
        $this->view('pharmacist/register-batch', [
            'suppliers' => $suppliers,
        ]);
    }

    public function suppliers(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_check()) {
                flash_error('This page expired. Try again.');
                $this->redirect('/staff/pharmacist/suppliers');
            }

            $name = trim((string)($_POST['name'] ?? ''));
            $contact = trim((string)($_POST['contact'] ?? ''));

            if ($name === '') {
                flash_error('Enter the supplier name.');
                $this->redirect('/staff/pharmacist/suppliers');
            }

            if (Supplier::existsByName($name)) {
                flash_error("{$name} is already a supplier.");
                $this->redirect('/staff/pharmacist/suppliers');
            }

            $supplierId = Supplier::create($name, $contact);
            $staffId = current_staff_id() ?? 1;

            AuditLog::record(
                'staff',
                $staffId,
                'create',
                'supplier',
                (string)$supplierId,
                "name: {$name}, contact: {$contact}"
            );

            flash_success("{$name} added to suppliers.");
            $this->redirect('/staff/pharmacist/suppliers');
        }

        $suppliers = Supplier::allWithBatchCount();

        $this->view('pharmacist/suppliers', [
            'suppliers' => $suppliers,
            'flashSuccess' => get_flash_success(),
            'flashError' => get_flash_error(),
        ]);
    }

    public function registerSupplier(): void
    {
        $this->suppliers();
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
