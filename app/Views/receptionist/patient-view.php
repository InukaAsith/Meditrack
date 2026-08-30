<?php
declare(strict_types=1);

$title = $patient['full_name'];
$active = 'patients';
$extraCss = ['patient'];

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/receptionist/patients"><?= icon('chevronLeft', 15) ?><?= e($patient['full_name']) ?></a>
  <span class="patient-find-subhead__hint">Patient record</span>
</div>

<div class="patient-find-card" style="max-width:820px">
  <?php if ($success): ?><p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($success) ?></p><?php endif; ?>

  <div class="card">
    <div class="card__body">
      <div class="edit-head">
        <?php if ($patient['photo_uri']): ?>
          <img class="edit-head__avatar edit-head__avatar--photo" src="<?= e($patient['photo_uri']) ?>" alt="Photo of <?= e($patient['full_name']) ?>">
        <?php else: ?>
          <span class="edit-head__avatar"><?= e(initials($patient['full_name'])) ?></span>
        <?php endif; ?>
        <div class="edit-head__body">
          <div class="edit-head__name"><?= e($patient['full_name']) ?></div>
          <div class="id-tags">
            <span class="id-tag id-tag--code"><?= e($patient['patient_code']) ?></span>
            <span class="id-tag"><span class="id-tag__label">NIC</span><?= e($patient['nic']) ?></span>
            <?php if ($patient['age'] !== null): ?>
              <span class="id-tag"><?= e($patient['age']) ?> years</span>
            <?php endif; ?>
            <?php if ($patient['gender']): ?>
              <span class="id-tag"><?= $patient['gender'] === 'female' ? 'Female' : 'Male' ?></span>
            <?php endif; ?>
            <span class="id-tags__note">Registered <?= e(date('d M Y', strtotime($patient['created_at']))) ?></span>
          </div>
        </div>
        <a class="btn btn--secondary btn--sm" href="/staff/receptionist/patient-edit/<?= e($patient['patient_id']) ?>"><?= icon('edit', 13) ?> Edit</a>
        <a class="btn btn--primary btn--sm" href="/staff/receptionist/check-in">Check in</a>
      </div>

      <div class="rec-facts">
        <div class="rec-fact"><span>Mobile</span><b><?= e($patient['mobile']) ?></b></div>
        <div class="rec-fact"><span>Email</span><b><?= e($patient['email'] ?? 'Not given') ?></b></div>
        <div class="rec-fact"><span>Date of birth</span><b><?= e(date('d M Y', strtotime($patient['date_of_birth']))) ?></b></div>
        <div class="rec-fact"><span>Blood type</span><b><?= e($patient['blood_type'] ?? 'Not known') ?></b></div>
        <div class="rec-fact"><span>Address</span><b><?= e($patient['address'] ?? 'Not given') ?></b></div>
        <div class="rec-fact">
          <span>Allergies</span>
          <?php if (!$allergies): ?>
            <b>None recorded</b>
          <?php else: ?>
            <div class="allergy-tags">
              <?php foreach ($allergies as $allergy): ?>
                <span class="allergy-tag"><?= e($allergy['allergen_name']) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-7">
    <div class="card__body">
      <div class="staff-eyebrow">Appointments</div>
      <p class="field__desc">No appointments yet.</p>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
