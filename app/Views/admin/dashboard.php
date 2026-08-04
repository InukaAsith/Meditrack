<?php

declare(strict_types=1);

$title = 'Dashboard';
$active = 'dashboard';

$statusTiles = [
  ['label' => 'Queue updates', 'value' => '2.1 s', 'target' => 'Target 5 s or less', 'state' => 'ok', 'icon' => 'clock'],
  ['label' => 'Page load', 'value' => '1.8 s', 'target' => 'Target 3 s or less', 'state' => 'ok', 'icon' => 'activity'],
  ['label' => 'People online', 'value' => '11', 'target' => 'Busiest today: 23', 'state' => 'ok', 'icon' => 'users'],
  ['label' => 'Uptime (30 days)', 'value' => '99.7%', 'target' => 'Target 99.5%', 'state' => 'ok', 'icon' => 'server'],
];

$activity = [
  ['text' => '<b>You</b> changed the grace window from 10 to 12 minutes', 'time' => 'Today 09:20', 'dot' => 'primary'],
  ['text' => '<b>EMP-012</b> account created as a Receptionist', 'time' => 'Today 08:20', 'dot' => 'info'],
  ['text' => '<b>G. G. Mithun Majika</b> (Pharmacist) signed in', 'time' => 'Today 07:58', 'dot' => 'success'],
  ['text' => '<b>Sandanu Dulmeth</b> account reactivated', 'time' => 'Yesterday 16:40', 'dot' => 'info'],
  ['text' => 'Booking confirmed SMS template edited', 'time' => 'Yesterday 14:11', 'dot' => 'primary'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e(first_name($admin['name'])) ?></h1>
    <div class="staff-head__sub"><?= e(date('l j M Y')) ?></div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/admin/audit-trail"><?= icon('records', 14) ?>Audit trail</a>
    <a class="btn btn--primary" href="/staff/admin/staff-create"><?= icon('plus', 14) ?>New staff account</a>
  </div>
</div>

<div class="staff-kpis">
  <div class="staff-kpi">
    <div class="staff-kpi__label">Active staff accounts</div>
    <div class="staff-kpi__value">18</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Sign-ins today</div>
    <div class="staff-kpi__value">14</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Audit entries today</div>
    <div class="staff-kpi__value">1,208</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Password reset requests</div>
    <div class="staff-kpi__value staff-kpi__value--warning">1</div>
  </div>
</div>

<div class="sec-head">
  <span class="sec-head__title">System status</span>
  <a class="sec-head__hint" href="/staff/admin/templates">Open diagnostics →</a>
</div>
<div class="status-grid">
  <?php foreach ($statusTiles as $s): ?>
    <div class="status-tile">
      <div class="status-tile__top">
        <span class="status-tile__label"><?= e($s['label']) ?></span>
        <span class="status-tile__dot status-tile__dot--<?= e($s['state']) ?>"><?= $s['state'] === 'ok' ? 'Healthy' : 'Check' ?></span>
      </div>
      <div class="status-tile__value"><?= e($s['value']) ?></div>
      <div class="status-tile__meta"><?= icon($s['icon'], 13) ?> <?= e($s['target']) ?></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="sec-head">
  <span class="sec-head__title">Recent admin activity</span>
  <a class="sec-head__hint" href="/staff/admin/audit-trail">Open audit trail →</a>
</div>
<div class="card">
  <div class="card__body">
    <div class="m-activity">
      <?php foreach ($activity as $a): ?>
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--<?= e($a['dot']) ?>"></span>
          <div class="m-activity__body">
            <div class="m-activity__text"><?= $a['text'] ?></div>
            <div class="m-activity__time"><?= e($a['time']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>