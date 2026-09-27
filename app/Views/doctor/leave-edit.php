<?php

declare(strict_types=1);

$title  = 'Edit Leave';
$active = 'schedule';

require __DIR__ . '/header.php';
?>
<a class="staff-back" href="/staff/doctor/schedule"><?= icon('chevronLeft', 15) ?> Back to schedule</a>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Edit leave record</h1>
  </div>
</div>

<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--danger mb-6"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="card" style="max-width: 600px;">
  <div class="card__body">
    <div class="staff-eyebrow">Leave details</div>

    <form method="post" action="/staff/doctor/leave-edit/<?= (int) $leave['doctor_leave_id'] ?>">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

      <?php if (!empty($errors['dates'])): ?>
        <p class="form-flash form-flash--danger mb-4"><?= icon('alert', 14) ?> <?= e($errors['dates']) ?></p>
      <?php endif; ?>

      <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: var(--sp-4); margin-bottom: var(--sp-5);">
        <div class="field">
          <label class="field__label" for="leave-start">Start date *</label>
          <input class="field__input" type="date" id="leave-start" name="start_date" value="<?= e($leave['start_date']) ?>" required>
        </div>
        <div class="field">
          <label class="field__label" for="leave-end">End date *</label>
          <input class="field__input" type="date" id="leave-end" name="end_date" value="<?= e($leave['end_date']) ?>" required>
        </div>
      </div>

      <div class="field mb-6">
        <label class="field__label" for="leave-reason">Reason (optional)</label>
        <input class="field__input" type="text" id="leave-reason" name="reason" value="<?= e($leave['reason'] ?? '') ?>" maxlength="160">
      </div>

      <div class="row-actions" style="display:flex; justify-content:space-between; align-items:center;">
        <a class="btn btn--ghost btn--sm" href="/staff/doctor/schedule">Cancel</a>
        <button class="btn btn--primary btn--sm" type="submit"><?= icon('check', 14) ?> Save changes</button>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>