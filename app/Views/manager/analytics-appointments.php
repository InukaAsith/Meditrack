<?php

declare(strict_types=1);

$title = 'Appointment analytics';
$active = 'analytics';
$analyticsActive = 'appointments';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">Appointment volume, timing and outcomes across all doctors</div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/manager/financial-reports"><?= icon('download', 15) ?> Export</a>
  </div>
</div>

<?php
$tabs = [
  ['key' => 'overview', 'label' => 'Overview', 'href' => '/staff/manager/analytics/overview', 'icon' => 'home'],
  ['key' => 'appointments', 'label' => 'Appointments', 'href' => '/staff/manager/analytics/appointments', 'icon' => 'calendar'],
  ['key' => 'revenue', 'label' => 'Revenue', 'href' => '/staff/manager/analytics/revenue', 'icon' => 'billing'],
  ['key' => 'queue', 'label' => 'Queue', 'href' => '/staff/manager/analytics/queue', 'icon' => 'queue'],
  ['key' => 'patients', 'label' => 'Patients', 'href' => '/staff/manager/analytics/patients', 'icon' => 'profile'],
  ['key' => 'staff', 'label' => 'Staff', 'href' => '/staff/manager/analytics/staff', 'icon' => 'health'],
];
?>
<nav class="analytics-tabs" aria-label="Analytics views">
  <?php foreach ($tabs as $t): ?>
    <a class="analytics-tabs__item<?= $t['key'] === $analyticsActive ? ' is-active' : '' ?>"
      href="<?= e($t['href']) ?>"
      <?= $t['key'] === $analyticsActive ? 'aria-current="page"' : '' ?>>
      <?= icon($t['icon'], 15) ?><span><?= e($t['label']) ?></span>
    </a>
  <?php endforeach; ?>
</nav>

<div class="an-filterbar">
  <div class="an-filterbar__left">
    <?php require __DIR__ . '/period-picker.php'; ?>
  </div>
  <div class="an-filterbar__right">
    <span class="an-filterbar__hint">Booked, completed, cancelled &amp; no-show</span>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Booked (30 days)</span>
    </div>
    <div class="manager-kpi__value">1,262</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Completed</span>
    </div>
    <div class="manager-kpi__value">1,096</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Cancelled</span>
    </div>
    <div class="manager-kpi__value">88</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">No-shows</span>
    </div>
    <div class="manager-kpi__value">78</div>
  </div>
</div>

<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Appointments trend</span>
        <span class="chart-card__sub">Booked and completed</span>
      </div>
      <span class="chart-card__tag"><?= e($periodText) ?></span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="ap-trend"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Outcomes</span>
        <span class="chart-card__sub">Completed / cancelled / no-show</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="ap-status-breakdown"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Appointments by doctor</span>
        <span class="chart-card__sub">Last 30 days</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="ap-by-doctor"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Peak booking hours</span>
        <span class="chart-card__sub">Bookings by hour of day</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="ap-peak-hours"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Peak weekdays</span>
        <span class="chart-card__sub">Bookings by day of week</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="ap-peak-weekdays"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Cancellations &amp; no-shows</span>
        <span class="chart-card__sub">Last 8 weeks</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="ap-cancel-noshow"></canvas></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>