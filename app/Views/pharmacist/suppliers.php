<?php

declare(strict_types=1);

$title = 'Suppliers';
$active = 'suppliers';

$suppliers = $suppliers ?? Supplier::allWithBatchCount();

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Suppliers</h1>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--primary" href="/staff/pharmacist/register-batch"><?= icon('plus', 14) ?>Register batch</a>
  </div>
</div>

<?php if (!empty($flashSuccess)): ?><p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($flashSuccess) ?></p><?php endif; ?>
<?php if (!empty($flashError)): ?><p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($flashError) ?></p><?php endif; ?>

<div class="card mb-7">
  <div class="card__body">
    <div class="staff-eyebrow">Add a supplier</div>
    <form class="supplier-form" method="post" action="/staff/pharmacist/suppliers">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label class="field">
        <span class="field__label">Supplier name <span class="field__req" aria-hidden="true">*</span></span>
        <input class="field__input" name="name" maxlength="160" required>
      </label>
      <label class="field">
        <span class="field__label">Phone <span class="field__req" aria-hidden="true">*</span></span>
        <input class="field__input" type="tel" name="contact" pattern="0[0-9]{9}" maxlength="10" title="Phone number must start with 0 and contain exactly 10 digits" required>
      </label>
      <button class="btn btn--primary" type="submit"><?= icon('plus', 14) ?>Add supplier</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div class="staff-eyebrow">Suppliers (<?= count($suppliers) ?>)</div>
    <?php if (!$suppliers): ?>
      <p class="field__desc">No suppliers added yet.</p>
    <?php else: ?>
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Supplier</th>
              <th>Phone</th>
              <th>Batches delivered</th>
              <th>Added on</th>
              <th class="data-table__actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($suppliers as $supplier): ?>
              <tr class="data-table__row">
                <td class="text-bold"><?= e($supplier['name']) ?></td>
                <td><?= e($supplier['contact'] ?? '') ?></td>
                <td class="mono"><?= e((string) $supplier['batch_count']) ?></td>
                <td><?= e(date('d M Y', strtotime($supplier['created_at']))) ?></td>
                <td class="data-table__actions">
                  <a class="link-act" href="/staff/pharmacist/register-batch?supplier=<?= e(urlencode($supplier['name'])) ?>">Add batch</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
