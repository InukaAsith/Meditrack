<?php

declare(strict_types=1);

$title = 'New staff account';
$active = 'staff';
$isDoctor = $values['role_name'] === 'Doctor';

require __DIR__ . '/header.php';
?>
<div class="staff-form-page">
  <a class="staff-back" href="/staff/admin/staff-accounts"><?= icon('chevronLeft', 15) ?>Back to staff accounts</a>

  <div class="staff-head">
    <div>
      <h1 class="staff-head__title">New staff account</h1>
      <div class="staff-head__sub">The role decides what they can see and do</div>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($error) ?></p>
  <?php endif; ?>

  <form method="post" action="/staff/admin/staff-create" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Staff details</div>

        <div class="field<?= isset($errors['photo']) ? ' field--error' : '' ?>">
          <span class="field__label">Photo (optional)</span>
          <div class="photo-drop">
            <div data-photo-placeholder><span class="photo-drop__preview avatar--blue"><?= icon('profile', 24) ?></span></div>
            <div hidden data-photo-picked><img class="photo-drop__image" alt="Chosen photo" data-photo-image></div>
            <div class="logo-drop__text">
              <div class="logo-drop__name" data-photo-name>No photo chosen</div>
              <div class="logo-drop__hint">JPG, PNG or WebP, up to 2&nbsp;MB</div>
            </div>
            <label class="btn btn--secondary btn--sm">
              <?= icon('upload', 14) ?>Choose photo
              <input class="photo-drop__file" type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-photo-input>
            </label>
          </div>
          <?php if (isset($errors['photo'])): ?><span class="field__desc"><?= e($errors['photo']) ?></span><?php endif; ?>
        </div>

        <div class="form-grid mt-6">
          <label class="field<?= isset($errors['full_name']) ? ' field--error' : '' ?>">
            <span class="field__label">Full name *</span>
            <input class="field__input" name="full_name" value="<?= e($values['full_name']) ?>" maxlength="120" required>
            <?php if (isset($errors['full_name'])): ?><span class="field__desc"><?= e($errors['full_name']) ?></span><?php endif; ?>
          </label>

          <label class="field">
            <span class="field__label">Employee ID</span>
            <input class="field__input mono" value="<?= e($nextCode) ?>" readonly>
            <span class="field__desc">Given automatically</span>
          </label>

          <label class="field<?= isset($errors['work_email']) ? ' field--error' : '' ?>">
            <span class="field__label">Work email *</span>
            <input class="field__input" type="email" name="work_email" value="<?= e($values['work_email']) ?>" required>
            <?php if (isset($errors['work_email'])): ?><span class="field__desc"><?= e($errors['work_email']) ?></span><?php endif; ?>
          </label>

          <label class="field<?= isset($errors['phone']) ? ' field--error' : '' ?>">
            <span class="field__label">Phone *</span>
            <input class="field__input" name="phone" value="<?= e($values['phone']) ?>" placeholder="07XXXXXXXX" maxlength="15" required>
            <?php if (isset($errors['phone'])): ?><span class="field__desc"><?= e($errors['phone']) ?></span><?php endif; ?>
          </label>

          <label class="field<?= isset($errors['temp_password']) ? ' field--error' : '' ?>">
            <span class="field__label">Temporary password *</span>
            <input class="field__input mono" name="temp_password" value="<?= e($values['temp_password']) ?>" required>
            <?php if (isset($errors['temp_password'])): ?>
              <span class="field__desc"><?= e($errors['temp_password']) ?></span>
            <?php else: ?>
              <span class="field__desc">They must change it the first time they sign in</span>
            <?php endif; ?>
          </label>

          <label class="field<?= isset($errors['role_name']) ? ' field--error' : '' ?>">
            <span class="field__label">Role *</span>
            <select class="field__input" name="role_name" data-role-select>
              <?php foreach ($roles as $role): ?>
                <option value="<?= e($role['role_name']) ?>" <?= $role['role_name'] === $values['role_name'] ? ' selected' : '' ?>><?= e($role['role_name']) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['role_name'])): ?><span class="field__desc"><?= e($errors['role_name']) ?></span><?php endif; ?>
          </label>
        </div>
      </div>
    </div>

    <div data-doctor-fields <?= $isDoctor ? '' : 'hidden' ?>>
      <div class="card mt-7">
        <div class="card__body">
          <div class="staff-eyebrow">Doctor details</div>
          <div class="form-grid">
            <label class="field<?= isset($errors['slmc_number']) ? ' field--error' : '' ?>">
              <span class="field__label">SLMC number *</span>
              <input class="field__input mono" name="slmc_number" value="<?= e($values['slmc_number']) ?>" maxlength="20">
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
              <input class="field__input" name="consultation_fee" value="<?= e($values['consultation_fee']) ?>" inputmode="decimal">
              <?php if (isset($errors['consultation_fee'])): ?><span class="field__desc"><?= e($errors['consultation_fee']) ?></span><?php endif; ?>
            </label>

            <label class="field<?= isset($errors['followup_fee']) ? ' field--error' : '' ?>">
              <span class="field__label">Follow-up fee (Rs.)</span>
              <input class="field__input" name="followup_fee" value="<?= e($values['followup_fee']) ?>" inputmode="decimal">
              <?php if (isset($errors['followup_fee'])): ?>
                <span class="field__desc"><?= e($errors['followup_fee']) ?></span>
              <?php else: ?>
                <span class="field__desc">Leave blank to charge the normal fee</span>
              <?php endif; ?>
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn btn--primary" type="submit"><?= icon('check', 14) ?>Create account</button>
      <a class="btn btn--secondary" href="/staff/admin/staff-accounts">Cancel</a>
    </div>
  </form>
</div>

<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
