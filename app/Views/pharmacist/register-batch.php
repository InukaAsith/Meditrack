<?php

declare(strict_types=1);

$title = 'Register batch';
$active = 'register-batch';

$suppliers = $suppliers ?? Supplier::all();
$defaultSupplier = trim((string) ($_GET['supplier'] ?? 'MedLanka Pvt Ltd'));

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/pharmacist/inventory"><?= icon('chevronLeft', 15) ?>Register new batch</a>
</div>

<div class="card patient-find-card">
  <div class="card__body">
    <form method="post" action="/staff/pharmacist/register-batch" id="batch-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="staff-eyebrow">Medicine</div>
      <div class="form-2col">
        <label class="field">
          <span class="field__label">Brand name </span>
          <input class="field__input" name="commercial_name" placeholder="Amoxil 500">
        </label>
        <label class="field">
          <span class="field__label">Generic name </span>
          <input class="field__input" name="generic" placeholder="Amoxicillin">
        </label>
        <label class="field">
          <span class="field__label">Form</span>
          <select class="field__input" name="unit_form">
            <option selected>Capsule</option>
            <option>Tablet</option>
            <option>Inhaler</option>
            <option>Injection</option>
            <option>Syrup</option>
          </select>
        </label>
        <label class="field">
          <span class="field__label">Manufacturer</span>
          <input class="field__input" name="manufacturer" placeholder="GSK Lanka">
        </label>
      </div>
      <label class="field mt-6">
        <span class="field__label">Storage</span>
        <input class="field__input" name="storage_limits" placeholder="Below 25 °C, keep dry">
      </label>

      <div class="staff-eyebrow mt-8">Delivery</div>
      <div class="form-2col">
        <label class="field">
          <span class="field__label">Supplier </span>
          <input class="field__input" name="supplier" value="<?= e($defaultSupplier) ?>" list="supplier-options" autocomplete="off">
          <datalist id="supplier-options">
            <?php foreach ($suppliers as $supplier): ?>
              <option value="<?= e($supplier['name']) ?>"></option>
            <?php endforeach; ?>
          </datalist>
        </label>
        <label class="field">
          <span class="field__label">Supplier invoice number </span>
          <input class="field__input" name="invoice_ref" placeholder="ML-88213">
        </label>
        <label class="field">
          <span class="field__label">Batch number </span>
          <input class="field__input" name="batch_id" placeholder="BT-2231">
        </label>
        <label class="field">
          <span class="field__label">Expiry date </span>
          <input class="field__input" type="date" name="expiry_date" min="<?= e(date('Y-m-d')) ?>">
        </label>
        <label class="field">
          <span class="field__label">Quantity received </span>
          <input class="field__input" type="number" name="qty_received" min="1" placeholder="120">
        </label>
        <label class="field">
          <span class="field__label">Total cost (Rs.)</span>
          <input class="field__input" type="number" name="total_cost" min="0" step="0.01" placeholder="4500.00">
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

<script src="/assets/js/pharmacist/register-batch.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
