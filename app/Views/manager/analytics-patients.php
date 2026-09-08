<?php

declare(strict_types=1);

$title = 'Patient analytics';
$active = 'analytics';
$analyticsActive = 'patients';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">Patient numbers for the whole clinic</div>
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
    <span class="an-filterbar__hint">Totals only, no names</span>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Total patients</span>
    </div>
    <div class="manager-kpi__value">3,942</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">New patients (30 days)</span>
    </div>
    <div class="manager-kpi__value">214</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Returning (30 days)</span>
    </div>
    <div class="manager-kpi__value">970</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Retention rate</span>
    </div>
    <div class="manager-kpi__value">82%</div>
  </div>
</div>

<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">New vs returning</span>
        <span class="chart-card__sub">Last 12 months</span>
      </div>
      <span class="chart-card__tag">12 months</span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="patient-find-new-returning"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Gender distribution</span>
        <span class="chart-card__sub">All registered patients</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="patient-find-gender"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Age distribution</span>
        <span class="chart-card__sub">Patients by age band</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="patient-find-age"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Patient growth</span>
        <span class="chart-card__sub">Cumulative registrations</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="patient-find-growth"></canvas></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>