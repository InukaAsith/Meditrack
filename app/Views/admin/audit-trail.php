<?php

declare(strict_types=1);

$title = 'Audit Trail';
$active = 'audit';

$roleBadge = [
  'Admin' => 'danger',
  'Manager' => 'purple',
  'Doctor' => 'primary',
  'Receptionist' => 'info',
  'Supporting Staff' => 'warning',
  'Pharmacist' => 'success',
  'Patient' => 'muted',
];

$actionBadge = [
  'create' => 'success',
  'register' => 'success',
  'register_batch' => 'success',
  'reactivate' => 'success',
  'update' => 'primary',
  'update_config' => 'primary',
  'stock_adjustment' => 'primary',
  'delete' => 'danger',
  'deactivate' => 'danger',
  'remove_batch' => 'danger',
  'trusted_device_removed' => 'danger',
  'reset_password' => 'warning',
  'password_change' => 'warning',
  'view' => 'muted',
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Audit trail</h1>
    <div class="staff-head__sub">Every change, sign-in and queue event</div>
  </div>
  <div class="staff-head__actions">
    <a href="/staff/admin/audit-export" class="btn btn--secondary" download data-audit-export><?= icon('download', 14) ?>Export</a>
  </div>
</div>

<div class="immutable-bar">
  <span class="immutable-bar__icon"><?= icon('lock', 16) ?></span>
  <span>Entries can't be edited or deleted by anyone.</span>
</div>

<div class="admin-toolbar">
  <div class="admin-toolbar__left">
    <div class="admin-search">
      <span class="admin-search__icon"><?= icon('search', 16) ?></span>
      <input class="admin-search__input" type="search" placeholder="Search user, record or field…" data-audit-search aria-label="Search audit log">
    </div>
  </div>
  <div class="admin-toolbar__right">
    <div class="select">
      <div class="select__control">
        <select class="select__input" data-audit-date aria-label="Date range">
          <option value="all">All dates</option>
          <option value="today">Today</option>
          <option value="7days">Last 7 days</option>
          <option value="30days">Last 30 days</option>
          <option value="month">This month</option>
        </select>
      </div>
    </div>
    <div class="select">
      <div class="select__control">
        <select class="select__input" data-audit-user aria-label="User">
          <option value="all">All users</option>
          <?php foreach ($users as $u): ?>
            <option value="<?= e(strtolower($u)) ?>"><?= e($u) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="select">
      <div class="select__control">
        <select class="select__input" data-audit-role aria-label="Role">
          <option value="all">All roles</option>
          <?php foreach ($roles as $ro):
            $roleName = is_array($ro) ? ($ro['role_name'] ?? '') : (string) $ro;
            if ($roleName === '') continue;
          ?>
            <option value="<?= e(strtolower($roleName)) ?>"><?= e($roleName) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="select">
      <div class="select__control">
        <select class="select__input" data-audit-record aria-label="Record">
          <option value="all">All records</option>
          <?php foreach ($records as $rc): ?>
            <option value="<?= e(strtolower($rc)) ?>"><?= e($rc) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</div>

<div data-tab-group>
  <div class="audit-tabs">
    <button class="audit-tabs__item is-active" data-tab="changes"><?= icon('records', 15) ?> Record changes <span class="audit-tabs__count" data-count-changes><?= count($changes) ?></span></button>
    <button class="audit-tabs__item" data-tab="logins"><?= icon('key', 15) ?> Login history <span class="audit-tabs__count" data-count-logins><?= count($logins) ?></span></button>
    <button class="audit-tabs__item" data-tab="queue"><?= icon('queue', 15) ?> Queue events <span class="audit-tabs__count" data-count-queue><?= count($queueEvents) ?></span></button>
  </div>

  <div data-tab-panel="changes">
    <div class="card">
      <div class="card__body">
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Time</th>
                <th>User</th>
                <th>Record</th>
                <th>Action</th>
                <th>Changed fields</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($changes as $r): ?>
                <tr data-audit-row
                    data-search="<?= e(strtolower($r['user'] . ' ' . $r['role'] . ' ' . $r['entity'] . ' ' . $r['pk'] . ' ' . $r['action'] . ' ' . implode(' ', $r['fields'] ?? []))) ?>"
                    data-user="<?= e(strtolower($r['user'])) ?>"
                    data-role="<?= e(strtolower($r['role'])) ?>"
                    data-record="<?= e(strtolower($r['entity'])) ?>"
                    data-date="<?= e($r['date']) ?>">
                  <td class="audit-time"><?= e($r['time']) ?></td>
                  <td>
                    <div class="table-lead__text">
                      <strong><?= e($r['user']) ?></strong>
                      <span><span class="badge badge--<?= e($roleBadge[$r['role']] ?? 'muted') ?>"><?= e($r['role']) ?></span></span>
                    </div>
                  </td>
                  <td>
                    <div class="table-lead__text">
                      <strong><?= e($r['entity']) ?></strong>
                      <span class="mono"><?= e($r['pk']) ?></span>
                    </div>
                  </td>
                  <td><span class="badge badge--<?= e($actionBadge[$r['action']] ?? 'info') ?>"><?= e(ucfirst(str_replace('_', ' ', $r['action']))) ?></span></td>
                  <td>
                    <?php if (!empty($r['fields'])): ?>
                      <?php foreach ($r['fields'] as $f): ?><span class="field-chip"><?= e($f) ?></span> <?php endforeach; ?>
                    <?php elseif ($r['action'] === 'view'): ?>
                      <span class="delta__masked"><?= icon('eye', 13) ?> Opened</span>
                    <?php elseif ($r['action'] === 'create'): ?>
                      <span class="delta__masked"><?= icon('plus', 13) ?> Created</span>
                    <?php elseif ($r['action'] === 'delete'): ?>
                      <span class="delta__masked"><?= icon('trash', 13) ?> Removed</span>
                    <?php elseif ($r['action'] === 'deactivate'): ?>
                      <span class="delta__masked">Account turned off</span>
                    <?php elseif ($r['action'] === 'reactivate'): ?>
                      <span class="delta__masked">Account restored</span>
                    <?php elseif ($r['action'] === 'reset_password'): ?>
                      <span class="delta__masked">Password reset</span>
                    <?php elseif ($r['action'] === 'password_change'): ?>
                      <span class="delta__masked">Password changed</span>
                    <?php else: ?>
                      <span class="delta__masked"><?= e(ucfirst(str_replace('_', ' ', $r['action']))) ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr data-audit-empty<?= $changes !== [] ? ' hidden' : '' ?>>
                <td colspan="5" class="text-center text-muted" style="padding: 2.5rem 1rem;"><?= $changes === [] ? 'Nothing recorded yet.' : 'No record changes match your search or filter.' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div data-tab-panel="logins" hidden>
    <div class="card">
      <div class="card__body">
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Signed in</th>
                <th>User</th>
                <th>IP address</th>
                <th>Browser</th>
                <th>Records code</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logins as $r): ?>
                <tr data-audit-row
                    data-search="<?= e(strtolower($r['user'] . ' ' . $r['role'] . ' ' . $r['ip'] . ' ' . $r['session'])) ?>"
                    data-user="<?= e(strtolower($r['user'])) ?>"
                    data-role="<?= e(strtolower($r['role'])) ?>"
                    data-date="<?= e($r['date']) ?>">
                  <td class="audit-time"><?= e($r['time']) ?></td>
                  <td>
                    <div class="table-lead__text">
                      <strong><?= e($r['user']) ?></strong>
                      <span><span class="badge badge--<?= e($roleBadge[$r['role']] ?? 'muted') ?>"><?= e($r['role']) ?></span></span>
                    </div>
                  </td>
                  <td><span class="mono"><?= e($r['ip']) ?></span></td>
                  <td class="text-muted"><?= e($r['session']) ?></td>
                  <td>
                    <?php if (!empty($r['otp'])): ?>
                      <span class="badge badge--success"><?= icon('lock', 11) ?> Verified</span>
                    <?php else: ?>
                      <span class="badge badge--muted">Not used</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($r['signed_out'] === null): ?>
                      <span class="badge badge--info">Active</span>
                    <?php else: ?>
                      <span class="text-muted"><?= e($r['signed_out']) ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr data-audit-empty<?= $logins !== [] ? ' hidden' : '' ?>>
                <td colspan="6" class="text-center text-muted" style="padding: 2.5rem 1rem;"><?= $logins === [] ? 'Nothing recorded yet.' : 'No sign-in events match your search or filter.' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div data-tab-panel="queue" hidden>
    <div class="card">
      <div class="card__body">
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Event</th>
                <th>Doctor</th>
                <th>Queue entry</th>
                <th>Detail</th>
                <th>Triggered by</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($queueEvents as $r): ?>
                <tr data-audit-row
                    data-search="<?= e(strtolower($r['event'] . ' ' . $r['doctor'] . ' ' . $r['detail'] . ' ' . $r['by'])) ?>"
                    data-date="<?= e($r['date'] ?? '') ?>">
                  <td class="audit-time"><?= e($r['time']) ?></td>
                  <td><span class="badge badge--<?= e($r['tone']) ?>"><?= e($r['event']) ?></span></td>
                  <td><?= e($r['doctor']) ?></td>
                  <td><span class="mono"><?= e($r['entry']) ?></span></td>
                  <td class="text-muted"><?= e($r['detail']) ?></td>
                  <td>
                    <?php if ($r['by'] === 'system'): ?>
                      <span class="delta__masked"><?= icon('settings', 12) ?> System</span>
                    <?php elseif ($r['by'] === 'patient'): ?>
                      <span class="delta__masked"><?= icon('profile', 12) ?> Patient</span>
                    <?php else: ?>
                      <?= e($r['by']) ?>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr data-audit-empty<?= $queueEvents !== [] ? ' hidden' : '' ?>>
                <td colspan="6" class="text-center text-muted" style="padding: 2.5rem 1rem;">No queue events yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<p class="foot-note mt-7">
  <?= icon('archive', 14) ?>
  <span>Entries are kept for 7 years.</span>
</p>
<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>