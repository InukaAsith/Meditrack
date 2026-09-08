<?php

declare(strict_types=1);

$title = 'Analytics';
$active = 'analytics';
$analyticsActive = 'overview';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">How the clinic is doing</div>
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
    <span class="an-filterbar__hint">Showing <?= e(strtolower($periodText)) ?></span>
  </div>
</div>

<div class="manager-kpis">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Revenue (30 days)</span>
    </div>
    <div class="manager-kpi__value">Rs. 2.41M</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Patients seen</span>
    </div>
    <div class="manager-kpi__value">1,184</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Average wait</span>
    </div>
    <div class="manager-kpi__value">18 min</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">No-show rate</span>
    </div>
    <div class="manager-kpi__value">6.2%</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Slot utilization</span>
    </div>
    <div class="manager-kpi__value">74%</div>
  </div>
</div>

<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Revenue trend</span>
        <span class="chart-card__sub">Rs. '000 a day, clinic and pharmacy</span>
      </div>
      <span class="chart-card__tag"><?= e($periodText) ?></span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="ov-revenue-trend"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Patients by weekday</span>
        <span class="chart-card__sub">Average visits per day</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="ov-patients-weekday"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--3">
  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Recent alerts</span>
        <a href="/staff/manager/pharmacy-alerts">All →</a>
      </div>
      <div class="manager-list">
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--danger"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Salbutamol inhaler below reorder</span>
            <span class="manager-list__meta">8 left in the pharmacy</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--warning"><?= icon('alert', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Insulin Glargine expires in 18 days</span>
            <span class="manager-list__meta">Pharmacy batch INS-2207</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--info"><?= icon('calendar', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">No-show rate up on Wednesdays</span>
            <span class="manager-list__meta">9.1% of Wednesday appointments</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Pending approvals</span>
        <a href="/staff/manager/approvals">Review →</a>
      </div>
      <div class="manager-list">
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--primary"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Amoxicillin 500mg - unit price</span>
            <span class="manager-list__meta">Drug pricing, 2 hours ago</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--primary"><?= icon('calendar', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Dr. Sample Doctor 1 - Wed evening session</span>
            <span class="manager-list__meta">Doctor schedule, yesterday</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Recent notifications</span>
        <a href="/staff/manager/notifications">All →</a>
      </div>
      <div class="manager-list">
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--info"><?= icon('bell', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Month-end report ready</span>
            <span class="manager-list__meta">Today 08:00</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--warning"><?= icon('bell', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Pricing approval pending</span>
            <span class="manager-list__meta">Yesterday</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--success"><?= icon('bell', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Utilization milestone</span>
            <span class="manager-list__meta">2 days ago</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>