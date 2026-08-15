<?php

declare(strict_types=1);

$id = (int) $staff['staff_id'];
$isDoctor = $values['role_name'] === 'Doctor';
$title = 'Edit ' . $staff['full_name'];
$active = 'staff';

require __DIR__ . '/header.php';
?>
<div class="staff-form-page">
  <a class="staff-back" href="/staff/admin/staff-accounts"><?= icon('chevronLeft', 15) ?>Back to staff accounts</a>

  <div class="staff-head">
    <div>
      <h1 class="staff-head__title">Edit staff account</h1>
      <div class="staff-head__sub inline-parts">
        <span><?= e($staff['full_name']) ?></span>
        <span class="mono"><?= e($staff['employee_code']) ?></span>
        <span>Added <?= e(date('d M Y', strtotime($staff['created_at']))) ?></span>
      </div>
    </div>
    <?php if ($staff['status'] !== 'active'): ?>
      <div class="staff-head__actions">
        <form method="post" action="/staff/admin/staff-reactivate/<?= e($id) ?>">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button class="btn btn--primary" type="submit"><?= icon('refresh', 14) ?>Turn account back on</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <?php if (!empty($success)): ?>
    <p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($success) ?></p>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($error) ?></p>
  <?php endif; ?>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Photo</span>
        <?php if ($staff['status'] === 'active'): ?>
          <span class="badge badge--success">Active</span>
        <?php else: ?>
          <span class="badge badge--muted">Turned off</span>
        <?php endif; ?>
      </div>
      <div class="photo-drop">
        <?php if (!empty($staff['photo_uri'])): ?>
          <img class="photo-drop__image" src="<?= e($staff['photo_uri']) ?>" alt="Photo of <?= e($staff['full_name']) ?>">
        <?php else: ?>
          <span class="photo-drop__preview avatar--blue"><?= e(initials($staff['full_name'])) ?></span>
        <?php endif; ?>

        <div class="logo-drop__text">
          <div class="logo-drop__name"><?= !empty($staff['photo_uri']) ? 'Photo added' : 'No photo' ?></div>
          <div class="logo-drop__hint">JPG, PNG or WebP, up to 2&nbsp;MB</div>
        </div>

        <div class="photo-drop__actions">
          <form method="post" action="/staff/admin/staff-photo/<?= e($id) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label class="btn btn--secondary btn--sm">
              <?= icon('upload', 14) ?><?= !empty($staff['photo_uri']) ? 'Change photo' : 'Upload photo' ?>
              <input class="photo-drop__file" type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-auto-submit>
            </label>
            <noscript><button class="btn btn--secondary btn--sm" type="submit">Upload</button></noscript>
          </form>
          <?php if (!empty($staff['photo_uri'])): ?>
            <form method="post" action="/staff/admin/staff-photo-delete/<?= e($id) ?>">
              <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
              <button class="btn btn--secondary btn--sm" type="submit"><?= icon('trash', 14) ?>Remove photo</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="/staff/admin/staff-edit/<?= e($id) ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="card mt-7">
      <div class="card__body">
        <div class="staff-eyebrow">Staff details</div>
        <div class="form-grid">
          <label class="field<?= isset($errors['full_name']) ? ' field--error' : '' ?>">
            <span class="field__label">Full name *</span>
            <input class="field__input" name="full_name" value="<?= e($values['full_name']) ?>" maxlength="120" required>
            <?php if (isset($errors['full_name'])): ?><span class="field__desc"><?= e($errors['full_name']) ?></span><?php endif; ?>
          </label>

          <label class="field">
            <span class="field__label">Employee ID</span>
            <input class="field__input mono" value="<?= e($staff['employee_code']) ?>" readonly>
            <span class="field__desc">Can't be changed</span>
          </label>

          <label class="field<?= isset($errors['work_email']) ? ' field--error' : '' ?>">
            <span class="field__label">Work email *</span>
            <input class="field__input" type="email" name="work_email" value="<?= e($values['work_email']) ?>" required>
            <?php if (isset($errors['work_email'])): ?><span class="field__desc"><?= e($errors['work_email']) ?></span><?php endif; ?>
          </label>

          <label class="field<?= isset($errors['phone']) ? ' field--error' : '' ?>">
            <span class="field__label">Phone</span>
            <input class="field__input" name="phone" value="<?= e($values['phone']) ?>">
            <?php if (isset($errors['phone'])): ?><span class="field__desc"><?= e($errors['phone']) ?></span><?php endif; ?>
          </label>

          <label class="field">
            <span class="field__label">Role</span>
            <input class="field__input" value="<?= e($staff['role_name']) ?>" readonly>
            <span class="field__desc">Can't be changed</span>
          </label>
        </div>
      </div>
    </div>

    <?php if ($isDoctor): ?>
      <div class="card mt-7">
        <div class="card__body">
          <div class="staff-eyebrow">Doctor details</div>
          <div class="form-grid">
            <label class="field<?= isset($errors['slmc_number']) ? ' field--error' : '' ?>">
              <span class="field__label">SLMC number *</span>
              <input class="field__input mono" name="slmc_number" value="<?= e($values['slmc_number']) ?>" maxlength="20" placeholder="e.g. 45231">
              <?php if (isset($errors['slmc_number'])): ?><span class="field__desc"><?= e($errors['slmc_number']) ?></span><?php endif; ?>
            </label>

            <label class="field<?= isset($errors['specialty_id']) ? ' field--error' : '' ?>">
              <span class="field__label">Specialty *</span>
              <select class="field__input" name="specialty_id">
                <option value="">Pick a specialty</option>
                <?php foreach ($specialties as $specialty): ?>
                  <option value="<?= e($specialty['specialty_id']) ?>" <?= (string) $specialty['specialty_id'] === $values['specialty_id'] ? ' selected' : '' ?>><?= e($specialty['name']) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['specialty_id'])): ?><span class="field__desc"><?= e($errors['specialty_id']) ?></span><?php endif; ?>
            </label>

            <label class="field<?= isset($errors['consultation_fee']) ? ' field--error' : '' ?>">
              <span class="field__label">Consultation fee (Rs.) *</span>
              <input class="field__input" name="consultation_fee" value="<?= e($values['consultation_fee']) ?>" inputmode="decimal" placeholder="e.g. 2500">
              <?php if (isset($errors['consultation_fee'])): ?><span class="field__desc"><?= e($errors['consultation_fee']) ?></span><?php endif; ?>
            </label>

            <label class="field<?= isset($errors['followup_fee']) ? ' field--error' : '' ?>">
              <span class="field__label">Follow-up fee (Rs.)</span>
              <input class="field__input" name="followup_fee" value="<?= e($values['followup_fee']) ?>" inputmode="decimal" placeholder="e.g. 1500">
              <?php if (isset($errors['followup_fee'])): ?>
                <span class="field__desc"><?= e($errors['followup_fee']) ?></span>
              <?php else: ?>
                <span class="field__desc">Leave blank to charge the normal fee</span>
              <?php endif; ?>
            </label>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="form-actions">
      <button class="btn btn--primary" type="submit">Save changes</button>
      <a class="btn btn--secondary" href="/staff/admin/staff-accounts">Cancel</a>
    </div>
  </form>

  <details class="danger-zone mt-7">
    <summary class="danger-zone__summary"><?= icon('key', 14) ?> Reset password</summary>
    <div class="danger-zone__body">
      <p>Set <b><?= e($staff['full_name']) ?></b>'s password back to <span class="mono">Passw0rd!</span>? They must change it when they next sign in.</p>
      <form method="post" action="/staff/admin/staff-reset-password/<?= e($id) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button class="btn btn--danger" type="submit">Yes, reset password</button>
      </form>
    </div>
  </details>

  <?php if ($staff['status'] === 'active'): ?>
    <details class="danger-zone">
      <summary class="danger-zone__summary"><?= icon('lock', 14) ?> Turn off account</summary>
      <div class="danger-zone__body">
        <p>Stop <b><?= e($staff['full_name']) ?></b> from signing in? Their history is kept and you can turn the account back on later.</p>
        <form method="post" action="/staff/admin/staff-deactivate/<?= e($id) ?>">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button class="btn btn--danger" type="submit">Yes, turn off account</button>
        </form>
      </div>
    </details>
  <?php endif; ?>
</div>

<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
