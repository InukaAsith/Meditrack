<?php
declare(strict_types=1);

$title = 'Register patient';
$active = 'patients';

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/receptionist/patients"><?= icon('chevronLeft', 15) ?>Register new patient</a>
</div>

<div class="card patient-find-card">
  <div class="card__body">
    <form method="post" action="/staff/receptionist/patient-register" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <?php if ($clash): ?>
        <div class="duplicate-warning">
          <span class="duplicate-warning__icon"><?= icon('alert', 18) ?></span>
          <div class="duplicate-warning__body">
            <strong>Already registered:</strong> this NIC, mobile or email belongs to another patient.
            <a class="duplicate-warning__link" href="/staff/receptionist/patient/<?= e($clash['patient_id']) ?>">Open their record instead?</a>
          </div>
        </div>
      <?php endif; ?>

      <label class="field<?= isset($errors['full_name']) ? ' field--error' : '' ?>">
        <span class="field__label">Full name of patient *</span>
        <input class="field__input" name="full_name" value="<?= e($values['full_name']) ?>" maxlength="120" required>
        <?php if (isset($errors['full_name'])): ?><span class="field__desc"><?= e($errors['full_name']) ?></span><?php endif; ?>
      </label>

      <div class="form-2col mt-6">
        <label class="field<?= isset($errors['nic']) ? ' field--error' : '' ?>">
          <span class="field__label">NIC * <span class="field__lock">(can't be changed after saving)</span></span>
          <input class="field__input" name="nic" value="<?= e($values['nic']) ?>" placeholder="786512340V or 198812340567" maxlength="12" required>
          <?php if (isset($errors['nic'])): ?><span class="field__desc"><?= e($errors['nic']) ?></span><?php endif; ?>
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
            <option value="female"<?= $values['gender'] === 'female' ? ' selected' : '' ?>>Female</option>
            <option value="male"<?= $values['gender'] === 'male' ? ' selected' : '' ?>>Male</option>
          </select>
          <?php if (isset($errors['gender'])): ?><span class="field__desc"><?= e($errors['gender']) ?></span><?php endif; ?>
        </label>
        <label class="field<?= isset($errors['mobile']) ? ' field--error' : '' ?>">
          <span class="field__label">Mobile *</span>
          <input class="field__input" name="mobile" value="<?= e($values['mobile']) ?>" placeholder="0774521180" required>
          <?php if (isset($errors['mobile'])): ?><span class="field__desc"><?= e($errors['mobile']) ?></span><?php endif; ?>
        </label>
        <label class="field<?= isset($errors['email']) ? ' field--error' : '' ?>">
          <span class="field__label">Email</span>
          <input class="field__input" type="email" name="email" value="<?= e($values['email']) ?>" placeholder="name@example.com">
          <?php if (isset($errors['email'])): ?><span class="field__desc"><?= e($errors['email']) ?></span><?php endif; ?>
        </label>
        <label class="field<?= isset($errors['blood_type']) ? ' field--error' : '' ?>">
          <span class="field__label">Blood type</span>
          <select class="field__input" name="blood_type">
            <option value="">Not known</option>
            <?php foreach ($bloodTypes as $type): ?>
              <option value="<?= e($type) ?>"<?= $values['blood_type'] === $type ? ' selected' : '' ?>><?= e($type) ?></option>
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

      <label class="consent-check">
        <input type="checkbox" name="pdpa_consent" value="1"<?= $values['pdpa_consent'] ? ' checked' : '' ?>>
        <span>The patient agreed to have their details stored digitally (PDPA).</span>
      </label>
      <?php if (isset($errors['pdpa_consent'])): ?><p class="form-flash form-flash--error"><?= e($errors['pdpa_consent']) ?></p><?php endif; ?>

      <div class="form-actions">
        <button class="btn btn--primary" type="submit">Register</button>
        <a class="btn btn--secondary" href="/staff/receptionist/patients">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
