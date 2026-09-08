<?php

declare(strict_types=1);

$title = 'Queue analytics';
$active = 'analytics';
$analyticsActive = 'queue';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">Waiting times, consultation duration and doctor punctuality</div>
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
      </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Average wait</span>
    </div>
    <div class="manager-kpi__value">18 min</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Avg consult duration</span>
    </div>
    <div class="manager-kpi__value">12 min</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Peak queue length</span>
    </div>
    <div class="manager-kpi__value">14</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Avg doctor delay</span>
    </div>
    <div class="manager-kpi__value">6 min</div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Average waiting time</span>
        <span class="chart-card__sub">Minutes a day</span>
      </div>
      <span class="chart-card__tag"><?= e($periodText) ?></span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="q-avg-wait"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Queue length through the day</span>
        <span class="chart-card__sub">Patients waiting by hour</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="q-length"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Average consultation duration</span>
        <span class="chart-card__sub">Minutes per patient by doctor</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="q-consult-duration"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Doctor delay statistics</span>
        <span class="chart-card__sub">Average start delay vs schedule</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="q-doctor-delay"></canvas></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>