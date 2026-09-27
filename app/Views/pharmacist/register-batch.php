<?php

declare(strict_types=1);

$title = 'Register batch';
$active = 'register-batch';

$suppliers = $suppliers ?? Supplier::all();
$medicines = $medicines ?? db()->query('SELECT medicine_id, commercial_name, generic_name, unit_form, manufacturer, storage_limits, unit_price, reorder_threshold FROM medicine ORDER BY commercial_name ASC')->fetchAll(PDO::FETCH_ASSOC);
$existingBatches = $existingBatches ?? db()->query('SELECT batch_code FROM medicine_batch')->fetchAll(PDO::FETCH_COLUMN);
$defaultSupplier = trim((string) ($_GET['supplier'] ?? ''));
$old = $old ?? [];
$selectedSupplier = trim((string) ($old['supplier'] ?? $defaultSupplier));
$selectedForm = strtolower((string) ($old['unit_form'] ?? 'capsule'));

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/pharmacist/inventory"><?= icon('chevronLeft', 15) ?>Register new batch</a>
</div>

<?php if (!empty($flashSuccess)): ?><p class="form-flash form-flash--success mb-6"><?= icon('check', 14) ?> <?= e($flashSuccess) ?></p><?php endif; ?>
<?php if (!empty($flashError)): ?><p class="form-flash form-flash--error mb-6"><?= icon('alert', 14) ?> <?= e($flashError) ?></p><?php endif; ?>

<div class="card patient-find-card">
  <div class="card__body">
    <form method="post" action="/staff/pharmacist/register-batch" id="batch-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="staff-eyebrow">Medicine</div>
      <div class="form-2col">
        <label class="field">
          <span class="field__label">Brand name </span>
          <input class="field__input" name="commercial_name" value="<?= e($old['commercial_name'] ?? '') ?>" placeholder="Amoxil 500" list="medicine-options" autocomplete="off">
          <datalist id="medicine-options">
            <?php foreach ($medicines as $med): ?>
              <option value="<?= e($med['commercial_name']) ?>"><?= e($med['generic_name']) ?></option>
            <?php endforeach; ?>
          </datalist>
        </label>
        <label class="field">
          <span class="field__label">Generic name </span>
          <input class="field__input" name="generic" value="<?= e($old['generic'] ?? '') ?>" placeholder="Amoxicillin">
        </label>
        <label class="field">
          <span class="field__label">Form</span>
          <select class="field__input" name="unit_form">
            <option value="capsule" <?= $selectedForm === 'capsule' ? 'selected' : '' ?>>Capsule</option>
            <option value="tablet" <?= $selectedForm === 'tablet' ? 'selected' : '' ?>>Tablet</option>
            <option value="syrup" <?= $selectedForm === 'syrup' ? 'selected' : '' ?>>Syrup</option>
            <option value="inhaler" <?= $selectedForm === 'inhaler' ? 'selected' : '' ?>>Inhaler</option>
            <option value="injection" <?= $selectedForm === 'injection' ? 'selected' : '' ?>>Injection</option>
            <option value="drops" <?= $selectedForm === 'drops' ? 'selected' : '' ?>>Drops</option>
            <option value="other" <?= $selectedForm === 'other' ? 'selected' : '' ?>>Other</option>
          </select>
        </label>
        <label class="field">
          <span class="field__label">Manufacturer</span>
          <input class="field__input" name="manufacturer" value="<?= e($old['manufacturer'] ?? '') ?>" placeholder="GSK Lanka">
        </label>
        <label class="field">
          <span class="field__label">Unit selling price (Rs.)</span>
          <input class="field__input" type="number" step="0.01" min="0.01" name="unit_price" value="<?= e($old['unit_price'] ?? '') ?>" placeholder="e.g. 35.00">
        </label>
        <label class="field">
          <span class="field__label">Reorder threshold</span>
          <input class="field__input" type="number" min="0" max="10000" name="reorder_threshold" value="<?= e($old['reorder_threshold'] ?? '') ?>" placeholder="e.g. 40">
        </label>
      </div>
      <label class="field mt-6">
        <span class="field__label">Storage</span>
        <input class="field__input" name="storage_limits" value="<?= e($old['storage_limits'] ?? '') ?>" placeholder="Below 25 C, keep dry">
      </label>

      <div class="staff-eyebrow mt-8">Delivery</div>
      <div class="form-2col">
        <label class="field">
          <span class="field__label">Supplier </span>
          <select class="field__input" name="supplier" required>
            <option value="" disabled <?= $selectedSupplier === '' ? 'selected' : '' ?>>Select a supplier</option>
            <?php foreach ($suppliers as $supplier): ?>
              <option value="<?= e($supplier['name']) ?>" <?= strcasecmp($selectedSupplier, (string)$supplier['name']) === 0 ? 'selected' : '' ?>>
                <?= e($supplier['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="field">
          <span class="field__label">Supplier invoice number </span>
          <input class="field__input" name="invoice_ref" value="<?= e($old['invoice_ref'] ?? '') ?>" placeholder="ML-88213">
        </label>
        <label class="field">
          <span class="field__label">Batch number </span>
          <input class="field__input" name="batch_id" value="<?= e($old['batch_id'] ?? '') ?>" placeholder="BT-2231">
        </label>
        <label class="field">
          <span class="field__label">Expiry date </span>
          <input class="field__input" type="date" name="expiry_date" value="<?= e($old['expiry_date'] ?? '') ?>" min="<?= e(date('Y-m-d', strtotime('+1 day'))) ?>">
        </label>
        <label class="field">
          <span class="field__label">Quantity received </span>
          <input class="field__input" type="number" name="qty_received" value="<?= e($old['qty_received'] ?? '') ?>" min="1" placeholder="120">
        </label>
        <label class="field">
          <span class="field__label">Total cost (Rs.)</span>
          <input class="field__input" type="number" name="total_cost" value="<?= e($old['total_cost'] ?? '') ?>" min="0" step="0.01" placeholder="4500.00">
        </label>
      </div>

      <p class="form-error" id="batch-form-error" hidden>Please fill in: <span id="batch-form-missing"></span></p>

      <div class="form-actions">
        <button class="btn btn--primary" type="submit">Save batch</button>
        <a class="btn btn--secondary" href="/staff/pharmacist/inventory">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script id="medicines-data" type="application/json"><?= json_encode($medicines, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>
<script id="batches-data" type="application/json"><?= json_encode(array_map('strtoupper', $existingBatches), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>
<script src="/assets/js/pharmacist/register-batch.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
