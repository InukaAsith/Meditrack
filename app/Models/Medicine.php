<?php

declare(strict_types=1);

final class Medicine
{
    public const ALLOWED_FORMS = ['tablet', 'capsule', 'syrup', 'inhaler', 'injection', 'drops', 'other'];

    public static function getAllWithBatches(?string $search = null, ?string $statusFilter = null): array
    {
        $pdo = db();

        $sql = 'SELECT DISTINCT m.* FROM medicine m
                LEFT JOIN medicine_batch b ON m.medicine_id = b.medicine_id
                LEFT JOIN supplier s ON b.supplier_id = s.supplier_id';
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $term = '%' . trim($search) . '%';
            $sql .= ' WHERE m.commercial_name LIKE ?
                         OR m.generic_name LIKE ?
                         OR m.unit_form LIKE ?
                         OR b.batch_code LIKE ?
                         OR s.name LIKE ?';
            $params = [$term, $term, $term, $term, $term];
        }

        $sql .= ' ORDER BY m.commercial_name ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $batchStmt = $pdo->query(
            'SELECT b.*, s.name AS supplier_name
             FROM medicine_batch b
             JOIN supplier s ON b.supplier_id = s.supplier_id
             ORDER BY b.expiry_date ASC'
        );
        $allBatches = $batchStmt->fetchAll(PDO::FETCH_ASSOC);

        $batchesByMedicine = [];
        foreach ($allBatches as $batch) {
            $batchesByMedicine[(int)$batch['medicine_id']][] = $batch;
        }

        $today = new DateTimeImmutable('today');
        $items = [];

        foreach ($medicines as $med) {
            $medId = (int)$med['medicine_id'];
            $batches = $batchesByMedicine[$medId] ?? [];

            $totalStock = 0;
            $nearestExpiry = null;
            $nearestExpiryDays = null;
            $primarySupplier = '-';
            $primaryBatchCode = '-';
            $batchList = [];

            foreach ($batches as $b) {
                $qty = (int)$b['quantity_on_hand'];
                $bStatus = $b['status'];

                if ($bStatus !== 'damaged') {
                    $totalStock += $qty;
                }

                $expDate = new DateTimeImmutable($b['expiry_date']);
                $diff = (int)$today->diff($expDate)->format('%r%a');

                if ($nearestExpiry === null || ($qty > 0 && ($nearestExpiryDays === null || $diff < $nearestExpiryDays))) {
                    $nearestExpiry = $expDate;
                    $nearestExpiryDays = $diff;
                    $primaryBatchCode = $b['batch_code'];
                    $primarySupplier = $b['supplier_name'];
                }

                $batchList[] = [
                    'id' => (int)$b['batch_id'],
                    'code' => $b['batch_code'],
                    'units' => $qty,
                    'exp' => $expDate->format('d M Y'),
                    'status' => $bStatus,
                    'supplier' => $b['supplier_name'] ?? '-',
                ];
            }

            if ($nearestExpiry === null) {
                $formattedExpiry = '-';
                $expiryTone = 'normal';
            } else {
                $formattedExpiry = $nearestExpiry->format('d M Y');
                $expiryTone = ($nearestExpiryDays !== null && $nearestExpiryDays <= 30) ? 'red' : 'normal';
            }

            $reorder = (int)$med['reorder_threshold'];
            $isAvailable = (bool)$med['is_available'];

            if (!$isAvailable || $totalStock === 0) {
                $status = !$isAvailable ? 'Marked out of stock' : 'Out of stock';
                $statusTone = 'muted';
            } elseif ($totalStock <= $reorder) {
                $status = 'Low stock';
                $statusTone = 'danger';
            } elseif ($nearestExpiryDays !== null && $nearestExpiryDays < 0) {
                $status = 'Expired';
                $statusTone = 'danger';
            } elseif ($nearestExpiryDays !== null && $nearestExpiryDays <= 30) {
                $status = 'Expires in ' . max(1, $nearestExpiryDays) . ' days';
                $statusTone = 'warning';
            } else {
                $status = 'In stock';
                $statusTone = 'success';
            }

            if ($statusFilter !== null && $statusFilter !== 'all' && $statusFilter !== '') {
                $matches = false;
                $filterLower = strtolower($statusFilter);
                $statusLower = strtolower($status);

                if ($filterLower === 'low' && str_contains($statusLower, 'low')) {
                    $matches = true;
                } elseif ($filterLower === 'expiring' && str_contains($statusLower, 'expir')) {
                    $matches = true;
                } elseif ($filterLower === 'out' && str_contains($statusLower, 'out')) {
                    $matches = true;
                }

                if (!$matches) {
                    continue;
                }
            }

            $items[] = [
                'id' => 'inventory-' . $medId,
                'medicine_id' => $medId,
                'medicine' => $med['commercial_name'],
                'generic' => $med['generic_name'],
                'form' => ucfirst((string)$med['unit_form']),
                'batch' => $primaryBatchCode,
                'supplier' => $primarySupplier,
                'expiry' => $formattedExpiry,
                'expiry_tone' => $expiryTone,
                'stock' => $totalStock,
                'stock_display' => number_format($totalStock),
                'reorder' => $reorder,
                'status' => $status,
                'status_tone' => $statusTone,
                'config' => [
                    'threshold' => $reorder,
                    'price' => (float)$med['unit_price'],
                    'requires_rx' => (bool)$med['requires_prescription'],
                    'damaged_override' => !$isAvailable,
                    'adjust_qty' => 0,
                    'adjust_reason' => 'Reason: Damaged items',
                    'batches' => $batchList,
                ],
            ];
        }

        return $items;
    }

    public static function getCounts(): array
    {
        $all = self::getAllWithBatches();

        $counts = [
            'all' => count($all),
            'low' => 0,
            'expiring' => 0,
            'out' => 0,
        ];

        foreach ($all as $item) {
            $st = strtolower((string)$item['status']);
            if (str_contains($st, 'low')) {
                $counts['low']++;
            } elseif (str_contains($st, 'expir')) {
                $counts['expiring']++;
            } elseif (str_contains($st, 'out')) {
                $counts['out']++;
            }
        }

        return $counts;
    }

    public static function findById(int $medicineId): ?array
    {
        $stmt = db()->prepare('SELECT * FROM medicine WHERE medicine_id = ? LIMIT 1');
        $stmt->execute([$medicineId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public static function updateConfig(
        int $medicineId,
        int $reorderThreshold,
        float $unitPrice,
        bool $requiresPrescription,
        bool $isAvailable
    ): bool {
        $stmt = db()->prepare(
            'UPDATE medicine
             SET reorder_threshold = ?,
                 unit_price = ?,
                 requires_prescription = ?,
                 is_available = ?
             WHERE medicine_id = ?'
        );

        return $stmt->execute([
            $reorderThreshold,
            $unitPrice,
            $requiresPrescription ? 1 : 0,
            $isAvailable ? 1 : 0,
            $medicineId,
        ]);
    }

    public static function adjustStock(
        int $medicineId,
        ?int $batchId,
        int $delta,
        string $reason,
        ?string $note,
        int $staffId
    ): array {
        $pdo = db();

        $allowedReasons = ['damaged', 'baseline_intake', 'audit_correction'];
        if (!in_array($reason, $allowedReasons, true)) {
            return [
                'success' => false,
                'new_batch_stock' => 0,
                'total_stock' => 0,
                'status' => '',
                'status_tone' => '',
                'message' => 'Invalid adjustment reason. Allowed reasons are: damaged, baseline_intake, audit_correction.',
            ];
        }

        $med = self::findById($medicineId);
        if (!$med) {
            return [
                'success' => false,
                'new_batch_stock' => 0,
                'total_stock' => 0,
                'status' => '',
                'status_tone' => '',
                'message' => 'Medicine not found.',
            ];
        }

        $pdo->beginTransaction();

        try {
            if ($batchId === null || $batchId <= 0) {
                $bStmt = $pdo->prepare(
                    'SELECT batch_id, quantity_on_hand FROM medicine_batch
                     WHERE medicine_id = ? AND status != "damaged"
                     ORDER BY expiry_date ASC LIMIT 1'
                );
                $bStmt->execute([$medicineId]);
                $bRow = $bStmt->fetch(PDO::FETCH_ASSOC);

                if (!$bRow) {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'new_batch_stock' => 0,
                        'total_stock' => 0,
                        'status' => 'Out of stock',
                        'status_tone' => 'muted',
                        'message' => 'This medicine has no active batches to adjust.',
                    ];
                }

                $batchId = (int)$bRow['batch_id'];
                $currentBatchQty = (int)$bRow['quantity_on_hand'];
            } else {
                $bStmt = $pdo->prepare('SELECT batch_id, medicine_id, quantity_on_hand, status FROM medicine_batch WHERE batch_id = ? LIMIT 1');
                $bStmt->execute([$batchId]);
                $bRow = $bStmt->fetch(PDO::FETCH_ASSOC);

                if (!$bRow) {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'new_batch_stock' => 0,
                        'total_stock' => 0,
                        'status' => '',
                        'status_tone' => '',
                        'message' => 'The specified batch was not found.',
                    ];
                }

                if ((int)$bRow['medicine_id'] !== $medicineId) {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'new_batch_stock' => 0,
                        'total_stock' => 0,
                        'status' => '',
                        'status_tone' => '',
                        'message' => 'The selected batch does not belong to this medicine.',
                    ];
                }

                if ($bRow['status'] === 'damaged') {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'new_batch_stock' => 0,
                        'total_stock' => 0,
                        'status' => '',
                        'status_tone' => '',
                        'message' => 'Cannot adjust stock on a removed or damaged batch.',
                    ];
                }

                $currentBatchQty = (int)$bRow['quantity_on_hand'];
            }

            if ($delta < 0 && abs($delta) > $currentBatchQty) {
                $pdo->rollBack();
                return [
                    'success' => false,
                    'new_batch_stock' => $currentBatchQty,
                    'total_stock' => 0,
                    'status' => '',
                    'status_tone' => '',
                    'message' => "Cannot remove " . abs($delta) . " units. Only {$currentBatchQty} units available on hand in this batch.",
                ];
            }

            $newBatchQty = max(0, $currentBatchQty + $delta);
            $actualDelta = $newBatchQty - $currentBatchQty;

            $uStmt = $pdo->prepare('UPDATE medicine_batch SET quantity_on_hand = ? WHERE batch_id = ?');
            $uStmt->execute([$newBatchQty, $batchId]);

            $adjStmt = $pdo->prepare(
                'INSERT INTO stock_adjustment (batch_id, quantity_delta, reason, note, adjusted_by)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $adjStmt->execute([$batchId, $actualDelta, $reason, $note, $staffId]);

            $pdo->commit();

            $totStmt = $pdo->prepare(
                'SELECT SUM(quantity_on_hand) FROM medicine_batch
                 WHERE medicine_id = ? AND status != "damaged"'
            );
            $totStmt->execute([$medicineId]);
            $totalStock = (int)$totStmt->fetchColumn();

            $reorder = (int)($med['reorder_threshold'] ?? 0);
            $isAvailable = (bool)($med['is_available'] ?? true);

            if (!$isAvailable || $totalStock === 0) {
                $status = !$isAvailable ? 'Marked out of stock' : 'Out of stock';
                $statusTone = 'muted';
            } elseif ($totalStock <= $reorder) {
                $status = 'Low stock';
                $statusTone = 'danger';
            } else {
                $status = 'In stock';
                $statusTone = 'success';
            }

            return [
                'success' => true,
                'batch_id' => $batchId,
                'actual_delta' => $actualDelta,
                'new_batch_stock' => $newBatchQty,
                'total_stock' => $totalStock,
                'status' => $status,
                'status_tone' => $statusTone,
                'message' => 'Stock adjusted successfully',
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function registerBatch(
        string $commercialName,
        string $generic,
        string $unitForm,
        string $manufacturer,
        string $storageLimits,
        int $supplierId,
        string $invoiceRef,
        string $batchCode,
        int $qty,
        float $totalCost,
        string $expiryDate,
        int $staffId,
        ?float $unitPrice = null,
        ?int $reorderThreshold = null
    ): array {
        $unitForm = strtolower(trim($unitForm));
        if (!in_array($unitForm, self::ALLOWED_FORMS, true)) {
            throw new InvalidArgumentException("Invalid unit form '{$unitForm}'. Allowed forms are: " . implode(', ', self::ALLOWED_FORMS));
        }

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $mStmt = $pdo->prepare('SELECT medicine_id, unit_price, reorder_threshold FROM medicine WHERE commercial_name = ? AND unit_form = ? LIMIT 1');
            $mStmt->execute([$commercialName, $unitForm]);
            $existingMed = $mStmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingMed) {
                if ($unitPrice === null || $unitPrice <= 0.0) {
                    if ($qty > 0 && $totalCost > 0) {
                        $unitPrice = round(($totalCost / $qty) * 1.25, 2);
                    } else {
                        throw new InvalidArgumentException('Please specify a unit selling price greater than Rs. 0.00 for the new medicine.');
                    }
                }

                if ($reorderThreshold === null || $reorderThreshold < 0) {
                    $reorderThreshold = 0;
                } elseif ($reorderThreshold > 10000) {
                    $reorderThreshold = 10000;
                }

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
                    $unitPrice,
                    $reorderThreshold,
                ]);
                $medicineId = (int)$pdo->lastInsertId();
            } else {
                $medicineId = (int)$existingMed['medicine_id'];
            }

            $insBatch = $pdo->prepare(
                'INSERT INTO medicine_batch (batch_code, medicine_id, supplier_id, supplier_invoice_ref, quantity_received, quantity_on_hand, cost_price_total, expiry_date, registered_by)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
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
            $batchId = (int)$pdo->lastInsertId();

            $pdo->commit();

            return [
                'medicine_id' => $medicineId,
                'batch_id' => $batchId,
                'batch_code' => $batchCode,
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function removeBatch(int $batchId, int $staffId): array
    {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare('SELECT medicine_id, quantity_on_hand FROM medicine_batch WHERE batch_id = ? LIMIT 1');
            $stmt->execute([$batchId]);
            $batch = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$batch) {
                $pdo->rollBack();
                return ['success' => false, 'total_stock' => 0, 'status' => '', 'status_tone' => ''];
            }

            $medicineId = (int)$batch['medicine_id'];
            $qty = (int)$batch['quantity_on_hand'];

            if ($qty > 0) {
                $adjStmt = $pdo->prepare(
                    'INSERT INTO stock_adjustment (batch_id, quantity_delta, reason, note, adjusted_by)
                     VALUES (?, ?, "damaged", "Batch removed / damaged by pharmacist", ?)'
                );
                $adjStmt->execute([$batchId, -$qty, $staffId]);
            }

            $uStmt = $pdo->prepare('UPDATE medicine_batch SET quantity_on_hand = 0, status = "damaged" WHERE batch_id = ?');
            $uStmt->execute([$batchId]);

            $pdo->commit();

            $totStmt = $pdo->prepare(
                'SELECT SUM(quantity_on_hand) FROM medicine_batch
                 WHERE medicine_id = ? AND status != "damaged"'
            );
            $totStmt->execute([$medicineId]);
            $totalStock = (int)$totStmt->fetchColumn();

            $med = self::findById($medicineId);
            $reorder = (int)($med['reorder_threshold'] ?? 0);
            $isAvailable = (bool)($med['is_available'] ?? true);

            if (!$isAvailable || $totalStock === 0) {
                $status = !$isAvailable ? 'Marked out of stock' : 'Out of stock';
                $statusTone = 'muted';
            } elseif ($totalStock <= $reorder) {
                $status = 'Low stock';
                $statusTone = 'danger';
            } else {
                $status = 'In stock';
                $statusTone = 'success';
            }

            return [
                'success' => true,
                'total_stock' => $totalStock,
                'status' => $status,
                'status_tone' => $statusTone,
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
