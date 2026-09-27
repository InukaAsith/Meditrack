<?php

declare(strict_types=1);

$title = 'Staff Accounts';
$active = 'staff';

$staffList = $staffList ?? [];
$roleFilters = ['All', 'Doctor', 'Receptionist', 'Supporting Staff', 'Pharmacist', 'Manager', 'Admin'];
$roleBadge = [
  'Admin' => 'danger',
  'Manager' => 'purple',
  'Doctor' => 'primary',
  'Receptionist' => 'info',
  'Supporting Staff' => 'warning',
  'Pharmacist' => 'success',
];
$statusBadge = ['active' => ['success', 'Active'], 'deactivated' => ['muted', 'Deactivated']];

require __DIR__ . '/header.php';
?>
<?php if (!empty($success)): ?>
  <p class="form-flash form-flash--success mb-4"><?= icon('check', 14) ?> <?= e($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--error mb-4"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Staff accounts</h1>
    <div class="staff-head__sub">Everyone who can sign in to the staff portal</div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--primary" href="/staff/admin/staff-create"><?= icon('plus', 14) ?>New staff account</a>
  </div>
</div>

<div class="admin-toolbar" data-staff-toolbar>
  <div class="admin-toolbar__left">
    <div class="admin-search">
      <span class="admin-search__icon"><?= icon('search', 16) ?></span>
      <input class="admin-search__input" type="search" placeholder="Search name, employee ID or email" data-staff-search aria-label="Search staff">
    </div>
  </div>
  <div class="admin-toolbar__right">
    <div class="seg" data-staff-rolefilter>
      <?php foreach ($roleFilters as $i => $r): ?>
        <button type="button" class="seg__opt<?= $i === 0 ? ' is-active' : '' ?>" data-role="<?= e($r) ?>"><?= e($r === 'Supporting Staff' ? 'Support' : $r) ?></button>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Staff member</th>
            <th>Role</th>
            <th>Status</th>
            <th>Added</th>
            <th class="data-table__actions"></th>
          </tr>
        </thead>
        <tbody data-staff-body>
          <?php foreach ($staffList as $s):
            [$badgeTone, $badgeLabel] = $statusBadge[$s['status']] ?? ['muted', ucfirst((string) $s['status'])]; ?>
            <tr data-staff-row data-role="<?= e($s['role']) ?>" data-search="<?= e(strtolower($s['name'] . ' ' . $s['code'] . ' ' . $s['email'])) ?>">
              <td><span class="table-code"><?= e($s['code']) ?></span></td>
              <td>
                <div class="table-lead">
                  <?php if (!empty($s['photo_uri'])): ?>
                    <img class="table-lead__photo" src="<?= e($s['photo_uri']) ?>" alt="<?= e($s['name']) ?>">
                  <?php else: ?>
                    <span class="table-lead__avatar avatar--<?= e($s['tone']) ?>"><?= e($s['initials']) ?></span>
                  <?php endif; ?>
                  <div class="table-lead__text">
                    <strong><?= e($s['name']) ?></strong>
                    <span><?= e($s['email']) ?></span>
                  </div>
                </div>
              </td>
              <td><span class="badge badge--<?= e($roleBadge[$s['role']] ?? 'muted') ?>"><?= e($s['role']) ?></span></td>
              <td><span class="badge badge--<?= e($badgeTone) ?>"><?= e($badgeLabel) ?></span></td>
              <td class="text-muted"><?= e($s['created']) ?></td>
              <td class="data-table__actions">
                <a class="btn btn--secondary btn--sm" href="/staff/admin/staff-edit/<?= e($s['staff_id']) ?>"><?= icon('edit', 14) ?>Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="table-empty" data-staff-empty hidden>No staff match your search.</div>
    </div>
  </div>
</div>

<p class="foot-note">
  <?= icon('shield', 14) ?>
  <span>Accounts are never deleted. Open one with Edit to reset the password or turn the account off.</span>
</p>
<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>