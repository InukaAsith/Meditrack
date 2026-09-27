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

        if (!isset($input['threshold']) || !is_numeric($input['threshold'])) {
            $this->jsonResponse(['ok' => false, 'error' => 'Please enter a valid reorder threshold.'], 400);
            return;
        }
        $threshold = (int)$input['threshold'];
        if ($threshold < 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'Reorder threshold cannot be negative.'], 400);
            return;
        }
        if ($threshold > 10000) {
            $this->jsonResponse(['ok' => false, 'error' => 'Reorder threshold cannot exceed 10,000 units.'], 400);
            return;
        }

        if (!isset($input['price']) || !is_numeric($input['price'])) {
            $this->jsonResponse(['ok' => false, 'error' => 'Please enter a valid unit price.'], 400);
            return;
        }
        $price = (float)$input['price'];
        if ($price <= 0.0) {
            $this->jsonResponse(['ok' => false, 'error' => 'Unit price must be greater than Rs. 0.00.'], 400);
            return;
        }
        if ($price > 1000000.0) {
            $this->jsonResponse(['ok' => false, 'error' => 'Unit price cannot exceed Rs. 1,000,000.00.'], 400);
            return;
        }

        $requiresRx = !empty($input['requires_rx']) && ($input['requires_rx'] === true || $input['requires_rx'] === '1' || $input['requires_rx'] === 'on');
        $damagedOverride = !empty($input['damaged_override']) && ($input['damaged_override'] === true || $input['damaged_override'] === '1' || $input['damaged_override'] === 'on');
        $isAvailable = !$damagedOverride;

        $currentMed = Medicine::findById($medicineId);
        if (!$currentMed) {
            $this->jsonResponse(['ok' => false, 'error' => 'That medicine was not found.'], 400);
            return;
        }

        $changed = [];
        if ((int)$currentMed['reorder_threshold'] !== $threshold) {
            $changed[] = 'reorder_threshold';
        }
        if (abs((float)$currentMed['unit_price'] - $price) > 0.001) {
            $changed[] = 'unit_price';
        }
        if ((bool)$currentMed['requires_prescription'] !== $requiresRx) {
            $changed[] = 'requires_prescription';
        }
        if ((bool)$currentMed['is_available'] !== $isAvailable) {
            $changed[] = 'is_available';
        }

        $success = Medicine::updateConfig($medicineId, $threshold, $price, $requiresRx, $isAvailable);

        if ($success) {
            if (!empty($changed)) {
                $staffId = current_staff_id() ?? 0;
                AuditLog::record(
                    'staff',
                    $staffId,
                    'update_config',
                    'medicine',
                    (string)$medicineId,
                    implode(', ', $changed)
                );
            }

            $allMeds = Medicine::getAllWithBatches();
            $counts = Medicine::getCounts();
            $medData = null;
            foreach ($allMeds as $m) {
                if ((int)$m['medicine_id'] === $medicineId) {
                    $medData = $m;
                    break;
                }
            }

            $this->jsonResponse([
                'ok' => true,
                'message' => 'Settings saved.',
                'data' => [
                    'medicine_id' => $medicineId,
                    'threshold' => $threshold,
                    'price' => $price,
                    'requires_rx' => $requiresRx,
                    'is_available' => $isAvailable,
                    'total_stock' => $medData['stock'] ?? 0,
                    'status' => $medData['status'] ?? 'In stock',
                    'status_tone' => $medData['status_tone'] ?? 'success',
                    'counts' => $counts,
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

        $qtyInput = $input['quantity'] ?? null;
        if ($qtyInput === null || !is_numeric($qtyInput)) {
            $this->jsonResponse(['ok' => false, 'error' => 'Please enter a valid numeric quantity to add or remove.'], 400);
            return;
        }
        $delta = (int)$qtyInput;

        $rawReason = (string)($input['reason'] ?? '');
        $reason = $this->validateAdjustmentReason($rawReason);
        if ($reason === null) {
            $this->jsonResponse(['ok' => false, 'error' => 'Invalid adjustment reason. Allowed reasons are: damaged, baseline_intake, audit_correction.'], 400);
            return;
        }
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
                'quantity_on_hand'
            );

            $result['counts'] = Medicine::getCounts();
            $actualDelta = $result['actual_delta'] ?? $delta;
            $this->jsonResponse([
                'ok' => true,
                'message' => "Stock changed by {$actualDelta}.",
                'data' => $result,
            ]);
        } else {
            $this->jsonResponse(['ok' => false, 'error' => $result['message'] ?? 'The stock could not be changed. Try again.'], 400);
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
                'status, quantity_on_hand'
            );

            $result['counts'] = Medicine::getCounts();
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
            $unitForm = strtolower(trim((string)($_POST['unit_form'] ?? '')));
            $manufacturer = trim((string)($_POST['manufacturer'] ?? ''));
            $storageLimits = trim((string)($_POST['storage_limits'] ?? ''));
            $supplierName = trim((string)($_POST['supplier'] ?? ''));
            $invoiceRef = trim((string)($_POST['invoice_ref'] ?? ''));
            $batchCode = strtoupper(trim((string)($_POST['batch_id'] ?? '')));

            $qtyRaw = trim((string)($_POST['qty_received'] ?? ''));
            if ($qtyRaw === '' || !preg_match('/^\d+$/', $qtyRaw) || (int)$qtyRaw <= 0) {
                flash_error('Please enter a valid positive quantity received (whole numbers only, e.g. 120).');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }
            $qty = (int)$qtyRaw;

            $costRaw = trim((string)($_POST['total_cost'] ?? ''));
            $totalCost = 0.0;
            if ($costRaw !== '') {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $costRaw) || (float)$costRaw < 0) {
                    flash_error('Please enter a valid total cost in rupees (e.g. 4500.00). Non-numeric text is not allowed.');
                    $_SESSION['old_batch_input'] = $_POST;
                    $this->redirect('/staff/pharmacist/register-batch');
                    return;
                }
                $totalCost = (float)$costRaw;
            }

            $expiryRaw = trim((string)($_POST['expiry_date'] ?? ''));
            if ($expiryRaw === '') {
                flash_error('Please enter the batch expiry date.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            $year = null;
            $month = null;
            $day = null;

            if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $expiryRaw, $m)) {
                $year = (int)$m[1];
                $month = (int)$m[2];
                $day = (int)$m[3];
            } elseif (preg_match('/^(\d{1,2})\s*[\/\-]\s*(\d{1,2})\s*[\/\-]\s*(\d{4})$/', $expiryRaw, $m)) {
                $day = (int)$m[1];
                $month = (int)$m[2];
                $year = (int)$m[3];
            }

            if ($year === null || $month === null || $day === null || !checkdate($month, $day, $year)) {
                flash_error('Please enter a valid calendar expiry date.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            $expiryDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $today = date('Y-m-d');
            if ($expiryDate <= $today) {
                flash_error('Expiry date must be in the future.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            if (!in_array($unitForm, Medicine::ALLOWED_FORMS, true)) {
                flash_error('Please select a valid unit form (' . implode(', ', Medicine::ALLOWED_FORMS) . ').');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            $unitPriceRaw = trim((string)($_POST['unit_price'] ?? ''));
            $unitPrice = null;
            if ($unitPriceRaw !== '') {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $unitPriceRaw) || (float)$unitPriceRaw <= 0.0) {
                    flash_error('Unit selling price must be greater than Rs. 0.00.');
                    $_SESSION['old_batch_input'] = $_POST;
                    $this->redirect('/staff/pharmacist/register-batch');
                    return;
                }
                $unitPrice = (float)$unitPriceRaw;
            }

            $reorderRaw = trim((string)($_POST['reorder_threshold'] ?? ''));
            $reorderThreshold = null;
            if ($reorderRaw !== '') {
                if (!preg_match('/^\d+$/', $reorderRaw) || (int)$reorderRaw < 0 || (int)$reorderRaw > 10000) {
                    flash_error('Reorder threshold must be between 0 and 10,000 units.');
                    $_SESSION['old_batch_input'] = $_POST;
                    $this->redirect('/staff/pharmacist/register-batch');
                    return;
                }
                $reorderThreshold = (int)$reorderRaw;
            }

            if ($commercialName === '') {
                flash_error('Please enter the medicine brand name.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            if ($supplierName === '') {
                flash_error('Please select a supplier.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            if ($batchCode === '') {
                flash_error('Please enter the batch number.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            $pdo = db();
            $staffId = current_staff_id() ?? 1;

            $sStmt = $pdo->prepare('SELECT supplier_id FROM supplier WHERE name = ? OR supplier_id = ? LIMIT 1');
            $sStmt->execute([$supplierName, is_numeric($supplierName) ? (int)$supplierName : 0]);
            $supplierId = $sStmt->fetchColumn();

            if (!$supplierId) {
                flash_error('Please select an existing supplier from the database.');
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            $bCheck = $pdo->prepare('SELECT b.batch_code, m.commercial_name FROM medicine_batch b JOIN medicine m ON b.medicine_id = m.medicine_id WHERE b.batch_code = ? LIMIT 1');
            $bCheck->execute([$batchCode]);
            $existingBatch = $bCheck->fetch(PDO::FETCH_ASSOC);
            if ($existingBatch) {
                flash_error("Batch code '{$batchCode}' is already registered for '{$existingBatch['commercial_name']}'. Reusing a batch code is not allowed.");
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }

            try {
                $registered = Medicine::registerBatch(
                    $commercialName,
                    $generic,
                    $unitForm,
                    $manufacturer,
                    $storageLimits,
                    $supplierId,
                    $invoiceRef,
                    $batchCode,
                    $qty,
                    $totalCost,
                    $expiryDate,
                    $staffId,
                    $unitPrice,
                    $reorderThreshold
                );

                AuditLog::record(
                    'staff',
                    $staffId,
                    'register_batch',
                    'medicine_batch',
                    $batchCode,
                    "qty: {$qty}, supplier: {$supplierName}"
                );

                unset($_SESSION['old_batch_input']);
                flash_success("Batch {$batchCode} added to the inventory.");
                $this->redirect('/staff/pharmacist/inventory');
                return;
            } catch (Throwable $e) {
                flash_error('Could not register batch: ' . $e->getMessage());
                $_SESSION['old_batch_input'] = $_POST;
                $this->redirect('/staff/pharmacist/register-batch');
                return;
            }
        }

        $oldInput = $_SESSION['old_batch_input'] ?? [];
        unset($_SESSION['old_batch_input']);

        $suppliers = Supplier::all();
        $medStmt = db()->query('SELECT medicine_id, commercial_name, generic_name, unit_form, manufacturer, storage_limits, unit_price, reorder_threshold FROM medicine ORDER BY commercial_name ASC');
        $medicines = $medStmt->fetchAll(PDO::FETCH_ASSOC);
        $existingBatches = db()->query('SELECT batch_code FROM medicine_batch')->fetchAll(PDO::FETCH_COLUMN);

        $this->view('pharmacist/register-batch', [
            'suppliers' => $suppliers,
            'medicines' => $medicines,
            'existingBatches' => $existingBatches,
            'old' => $oldInput,
            'flashError' => get_flash_error(),
            'flashSuccess' => get_flash_success(),
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
            $phone = preg_replace('/[\s\-]/', '', $contact);

            if ($name === '') {
                flash_error('Enter the supplier name.');
                $this->redirect('/staff/pharmacist/suppliers');
            }

            if ($phone === '') {
                flash_error('Enter the supplier phone number.');
                $this->redirect('/staff/pharmacist/suppliers');
            }

            if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                flash_error('Phone number must start with 0 and contain exactly 10 digits (e.g. 0114766666).');
                $this->redirect('/staff/pharmacist/suppliers');
            }

            if (Supplier::existsByName($name)) {
                flash_error("{$name} is already a supplier.");
                $this->redirect('/staff/pharmacist/suppliers');
            }

            $supplierId = Supplier::create($name, $phone);
            $staffId = current_staff_id() ?? 1;

            AuditLog::record(
                'staff',
                $staffId,
                'create',
                'supplier',
                (string)$supplierId
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

    public function billingHistory(): void
    {
        $this->view('pharmacist/billing-history');
    }

    public function pharmacyAlerts(): void
    {
        $this->view('pharmacist/pharmacy-alerts');
    }

    public function stockAlerts(): void
    {
        $this->view('pharmacist/pharmacy-alerts');
    }

    public function notifications(): void
    {
        $this->view('pharmacist/notifications');
    }

    public function profile(): void
    {
        $this->view('pharmacist/profile', ['deviceTrusted' => current_device_is_trusted()]);
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

    private function validateAdjustmentReason(string $raw): ?string
    {
        $clean = strtolower(trim($raw));
        $validReasons = [
            'damaged' => 'damaged',
            'baseline_intake' => 'baseline_intake',
            'opening stock' => 'baseline_intake',
            'audit_correction' => 'audit_correction',
            'stock count fix' => 'audit_correction',
        ];

        return $validReasons[$clean] ?? null;
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
