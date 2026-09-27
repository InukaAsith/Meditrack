<?php

declare(strict_types=1);

$title  = 'Profile';
$active = 'profile';

$lastLogin = AuditLog::lastLogin('staff', (int) current_staff_id());
$lastLoginText = 'Never';
if ($lastLogin !== null) {
  $lastLoginText = date('d M Y · H:i', strtotime($lastLogin));
}

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">My profile</h1>
  </div>
</div>

<div class="profile-head">
  <span class="profile-head__avatar"><?= e($staff['initials']) ?></span>
  <div>
    <div class="profile-head__name"><?= e($staff['name']) ?></div>
    <div class="profile-head__meta">
      <span><span class="badge badge--primary-strong"><?= e($signedIn['role_name']) ?></span></span>
      <span><?= icon('records', 13) ?> <?= e($signedIn['employee_code']) ?></span>
      <span><?= icon('health', 13) ?> <?= e($doctorDetails['slmc_number'] ?? '-') ?></span>
      <span><?= icon('mail', 13) ?> <?= e($signedIn['work_email']) ?></span>
      <span><?= icon('clock', 13) ?> Last sign-in <?= e($lastLoginText) ?></span>
    </div>
  </div>
</div>

<div class="admin-grid">
  <div>
    <div class="card mb-7">
      <div class="card__body">
        <div class="staff-eyebrow">Account details</div>
        <dl class="definition-list">
          <dt>Full name</dt>
          <dd><?= e($staff['name']) ?></dd>
          <dt>Employee ID</dt>
          <dd class="mono"><?= e($signedIn['employee_code']) ?></dd>
          <dt>SLMC registration</dt>
          <dd class="mono"><?= e($doctorDetails['slmc_number'] ?? '-') ?></dd>
          <dt>Role</dt>
          <dd><?= e($signedIn['role_name']) ?> · <?= e($staff['specialty']) ?></dd>
          <dt>Work email</dt>
          <dd><?= e($signedIn['work_email']) ?></dd>
          <dt>Phone</dt>
          <dd><?= e($signedIn['phone'] ?? '-') ?></dd>
          <dt>Created</dt>
          <dd><?= e(date('d M Y', strtotime($signedIn['created_at']))) ?></dd>
        </dl>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Change password</div>
        <div class="form-grid">
          <div class="field form-grid__full">
            <label class="field__label" for="pw-current">Current password</label>
            <input class="field__input" id="pw-current" type="password">
          </div>
          <div class="field">
            <label class="field__label" for="pw-new">New password</label>
            <input class="field__input" id="pw-new" type="password" placeholder="At least 10 characters">
          </div>
          <div class="field">
            <label class="field__label" for="pw-confirm">Confirm new password</label>
            <input class="field__input" id="pw-confirm" type="password" placeholder="Re-enter new password">
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn--primary" type="button"><?= icon('key', 15) ?> Update password</button>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-side">
    <?php require __DIR__ . '/../partials/trusted-device.php'; ?>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>