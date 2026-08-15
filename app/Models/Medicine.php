<?php

declare(strict_types=1);

final class Medicine
{
    public static function getAllWithBatches(?string $search = null, ?string $statusFilter = null): array
    {
        $pdo = db();

        $sql = 'SELECT m.* FROM medicine m';
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= ' WHERE m.commercial_name LIKE ? OR m.generic_name LIKE ?';
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
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

        $allowedReasons = ['damaged', 'baseline_intake', 'audit_correction', 'order_return'];
        if (!in_array($reason, $allowedReasons, true)) {
            $reason = 'audit_correction';
        }

        $pdo->beginTransaction();

        try {
            if ($batchId === null || $batchId <= 0) {
                $bStmt = $pdo->prepare(
                    'SELECT batch_id, quantity_on_hand FROM medicine_batch
                     WHERE medicine_id = ? AND status = "active"
                     ORDER BY expiry_date ASC LIMIT 1'
                );
                $bStmt->execute([$medicineId]);
                $bRow = $bStmt->fetch(PDO::FETCH_ASSOC);

                if (!$bRow) {
                    $bStmt = $pdo->prepare(
                        'SELECT batch_id, quantity_on_hand FROM medicine_batch
                         WHERE medicine_id = ?
                         ORDER BY batch_id DESC LIMIT 1'
                    );
                    $bStmt->execute([$medicineId]);
                    $bRow = $bStmt->fetch(PDO::FETCH_ASSOC);
                }

                if (!$bRow) {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'new_batch_stock' => 0,
                        'total_stock' => 0,
                        'status' => 'Out of stock',
                        'status_tone' => 'muted',
                        'message' => 'This medicine has no batch yet.',
                    ];
                }

                $batchId = (int)$bRow['batch_id'];
                $currentBatchQty = (int)$bRow['quantity_on_hand'];
            } else {
                $bStmt = $pdo->prepare('SELECT quantity_on_hand FROM medicine_batch WHERE batch_id = ? LIMIT 1');
                $bStmt->execute([$batchId]);
                $currentBatchQty = (int)$bStmt->fetchColumn();
            }

            $newBatchQty = max(0, $currentBatchQty + $delta);

            $uStmt = $pdo->prepare('UPDATE medicine_batch SET quantity_on_hand = ? WHERE batch_id = ?');
            $uStmt->execute([$newBatchQty, $batchId]);

            $adjStmt = $pdo->prepare(
                'INSERT INTO stock_adjustment (batch_id, quantity_delta, reason, note, adjusted_by)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $adjStmt->execute([$batchId, $delta, $reason, $note, $staffId]);

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
                'batch_id' => $batchId,
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
