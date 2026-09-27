<?php

declare(strict_types=1);

$title = 'Profile';
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
    <div class="staff-head__sub">Your account details and sign-in security</div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/logout"><?= icon('arrowRight', 15) ?> Log out</a>
  </div>
</div>

<div class="profile-head">
  <span class="profile-head__avatar"><?= e($staff['initials']) ?></span>
  <div>
    <div class="profile-head__name"><?= e($signedIn['full_name']) ?></div>
    <div class="profile-head__meta">
      <span><span class="badge badge--primary-strong"><?= e($signedIn['role_name']) ?></span></span>
      <span><?= icon('records', 13) ?> <?= e($signedIn['employee_code']) ?></span>
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
          <dd><?= e($signedIn['full_name']) ?></dd>
          <dt>Employee ID</dt>
          <dd class="mono"><?= e($signedIn['employee_code']) ?></dd>
          <dt>Role</dt>
          <dd><?= e($signedIn['role_name']) ?></dd>
          <dt>Department</dt>
          <dd>Outpatient · Triage &amp; Queue</dd>
          <dt>Work email</dt>
          <dd><?= e($signedIn['work_email']) ?></dd>
          <dt>Phone</dt>
          <dd><?= e($signedIn['phone'] ?? '-') ?></dd>
          <dt>Joined</dt>
          <dd><?= e(date('d M Y', strtotime($signedIn['created_at']))) ?></dd>
          <dt>Last login</dt>
          <dd><?= e($lastLoginText) ?></dd>
        </dl>
      </div>
    </div>

    <div class="card mb-7">
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
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Session</div>
        <p class="text-sm text-muted" style="margin:0 0 var(--sp-5)">Signed in as <strong><?= e($signedIn['full_name']) ?></strong> · <?= e($lastLoginText) ?></p>
        <a class="btn btn--secondary btn--block" href="/staff/logout"><?= icon('arrowRight', 15) ?> Log out of MediTrack</a>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>