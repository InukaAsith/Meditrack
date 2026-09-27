<?php

declare(strict_types=1);

$title = 'Edit Patient';
$active = 'patients';
$id = $patient['patient_id'];

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/receptionist/patient/<?= e($id) ?>"><?= icon('chevronLeft', 15) ?>Edit Patient</a>
</div>

<div class="patient-find-card">
  <?php if ($success): ?><p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($success) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($error) ?></p><?php endif; ?>

  <div class="card">
    <div class="card__body">
      <div class="edit-head">
        <div class="patient-photo">
          <?php if ($patient['photo_uri']): ?>
            <img class="patient-photo__image" src="<?= e($patient['photo_uri']) ?>" alt="Photo of <?= e($patient['full_name']) ?>">
          <?php else: ?>
            <span class="patient-photo__initials"><?= e(initials($patient['full_name'])) ?></span>
          <?php endif; ?>

          <div class="patient-photo__actions">
            <form method="post" action="/staff/receptionist/patient-photo/<?= e($id) ?>" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
              <label class="patient-photo__button" title="<?= $patient['photo_uri'] ? 'Change photo' : 'Upload photo' ?>">
                <?= icon('edit', 14) ?>
                <input class="patient-photo__file" type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-auto-submit>
              </label>
              <noscript><button class="btn btn--secondary btn--xs" type="submit">Upload</button></noscript>
            </form>
            <?php if ($patient['photo_uri']): ?>
              <form method="post" action="/staff/receptionist/patient-photo-delete/<?= e($id) ?>">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button class="patient-photo__button patient-photo__button--danger" type="submit" title="Remove photo" aria-label="Remove photo"><?= icon('trash', 14) ?></button>
              </form>
            <?php endif; ?>
          </div>
        </div>

        <div class="edit-head__body">
          <div class="edit-head__name"><?= e($patient['full_name']) ?></div>
          <div class="edit-head__meta"><?= e($patient['patient_code']) ?> </div>
          <div class="edit-head__meta"> registered <?= e(date('d M Y', strtotime($patient['created_at']))) ?> </div>
        </div>
      </div>

      <form method="post" action="/staff/receptionist/patient-edit/<?= e($id) ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

        <?php if (isset($errors['clash'])): ?><p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($errors['clash']) ?></p><?php endif; ?>

        <div class="form-2col">
          <label class="field">
            <span class="field__label">NIC <span class="field__lock">(locked)</span></span>
            <input class="field__input" value="<?= e($patient['nic']) ?>" readonly>
          </label>
          <label class="field<?= isset($errors['full_name']) ? ' field--error' : '' ?>">
            <span class="field__label">Full name *</span>
            <input class="field__input" name="full_name" value="<?= e($values['full_name']) ?>" maxlength="120" required>
            <?php if (isset($errors['full_name'])): ?><span class="field__desc"><?= e($errors['full_name']) ?></span><?php endif; ?>
          </label>
          <label class="field<?= isset($errors['date_of_birth']) ? ' field--error' : '' ?>">
            <span class="field__label">Date of birth *</span>
            <input class="field__input" type="date" name="date_of_birth" value="<?= e($values['date_of_birth']) ?>" required>
            <?php if (isset($errors['date_of_birth'])): ?><span class="field__desc"><?= e($errors['date_of_birth']) ?></span><?php endif; ?>
          </label>
          <label class="field<?= isset($errors['gender']) ? ' field--error' : '' ?>">
            <span class="field__label">Gender *</span>
            <select class="field__input" name="gender" required>
              <option value="">Pick one</option>
              <option value="female" <?= $values['gender'] === 'female' ? ' selected' : '' ?>>Female</option>
              <option value="male" <?= $values['gender'] === 'male' ? ' selected' : '' ?>>Male</option>
            </select>
            <?php if (isset($errors['gender'])): ?><span class="field__desc"><?= e($errors['gender']) ?></span><?php endif; ?>
          </label>
          <label class="field<?= isset($errors['mobile']) ? ' field--error' : '' ?>">
            <span class="field__label">Mobile *</span>
            <input class="field__input" name="mobile" value="<?= e($values['mobile']) ?>" required>
            <?php if (isset($errors['mobile'])): ?><span class="field__desc"><?= e($errors['mobile']) ?></span><?php endif; ?>
          </label>
          <label class="field<?= isset($errors['email']) ? ' field--error' : '' ?>">
            <span class="field__label">Email</span>
            <input class="field__input" type="email" name="email" value="<?= e($values['email']) ?>">
            <?php if (isset($errors['email'])): ?><span class="field__desc"><?= e($errors['email']) ?></span><?php endif; ?>
          </label>
          <label class="field<?= isset($errors['blood_type']) ? ' field--error' : '' ?>">
            <span class="field__label">Blood type</span>
            <select class="field__input" name="blood_type">
              <option value="">Not known</option>
              <?php foreach ($bloodTypes as $type): ?>
                <option value="<?= e($type) ?>" <?= $values['blood_type'] === $type ? ' selected' : '' ?>><?= e($type) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['blood_type'])): ?><span class="field__desc"><?= e($errors['blood_type']) ?></span><?php endif; ?>
          </label>
        </div>

        <label class="field mt-6<?= isset($errors['address']) ? ' field--error' : '' ?>">
          <span class="field__label">Address</span>
          <input class="field__input" name="address" value="<?= e($values['address']) ?>" maxlength="255">
          <?php if (isset($errors['address'])): ?><span class="field__desc"><?= e($errors['address']) ?></span><?php endif; ?>
        </label>

        <div class="form-actions">
          <button class="btn btn--primary" type="submit">Save changes</button>
          <a class="btn btn--secondary" href="/staff/receptionist/patient/<?= e($id) ?>">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card mt-7">
    <div class="card__body">
      <div class="staff-eyebrow">Known allergies</div>
      <div class="allergy-tags mt-4">
        <?php if (!$allergies): ?><span class="field__desc">None recorded.</span><?php endif; ?>
        <?php foreach ($allergies as $allergy): ?>
          <form class="allergy-chip" method="post" action="/staff/receptionist/patient-allergy-remove/<?= e($id) ?>" data-confirm="Remove allergy &lt;b&gt;<?= e($allergy['allergen_name']) ?>&lt;/b&gt;?" data-confirm-title="Remove Allergy" data-confirm-ok="Remove" data-confirm-danger="true">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="allergen_name" value="<?= e($allergy['allergen_name']) ?>">
            <?= e($allergy['allergen_name']) ?>
            <button type="submit" aria-label="Remove <?= e($allergy['allergen_name']) ?>">✕</button>
          </form>
        <?php endforeach; ?>
      </div>
      <form class="allergy-add-form mt-6" method="post" action="/staff/receptionist/patient-allergy-add/<?= e($id) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input class="field__input" name="allergen_name" maxlength="100" required aria-label="Allergy to add">
        <button class="btn btn--secondary btn--sm" type="submit"><?= icon('plus', 13) ?> Add allergy</button>
      </form>
    </div>
  </div>

  <details class="danger-zone mt-7">
    <summary class="danger-zone__summary" data-modal-confirm="patient-delete-form"><?= icon('trash', 14) ?> Delete patient</summary>
    <div class="danger-zone__body">
      <p>Delete <b><?= e($patient['full_name']) ?></b>? This can't be undone.</p>
      <form id="patient-delete-form" method="post" action="/staff/receptionist/patient-delete/<?= e($id) ?>" data-confirm="Delete &lt;b&gt;<?= e($patient['full_name']) ?>&lt;/b&gt;? Their upcoming appointments are removed too, with no refund. This can't be undone." data-confirm-title="Delete Patient" data-confirm-ok="Yes, delete patient" data-confirm-danger="true">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button class="btn btn--danger" type="submit">Yes, delete patient</button>
      </form>
    </div>
  </details>
</div>
<script src="/assets/js/receptionist/patient-edit.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>