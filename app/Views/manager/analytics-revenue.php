<?php

declare(strict_types=1);

$title = 'Revenue analytics';
$active = 'analytics';
$analyticsActive = 'revenue';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">Clinic and pharmacy income</div>
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
    <span class="an-filterbar__hint">After refunds</span>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Revenue (30 days)</span>
    </div>
    <div class="manager-kpi__value">Rs. 2.41M</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Consultation fees</span>
    </div>
    <div class="manager-kpi__value">Rs. 1.28M</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Pharmacy sales</span>
    </div>
    <div class="manager-kpi__value">Rs. 1.13M</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Avg revenue / patient</span>
    </div>
    <div class="manager-kpi__value">Rs. 2,035</div>
  </div>
</div>

<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Revenue trend</span>
        <span class="chart-card__sub">Clinic and pharmacy, Rs. '000 a day</span>
      </div>
      <span class="chart-card__tag"><?= e($periodText) ?></span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="rev-trend"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Revenue breakdown</span>
        <span class="chart-card__sub">Share by category</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas chart-card__canvas--lg"><canvas data-chart="rev-breakdown"></canvas></div>
      <div class="chart-legend">
        <span class="chart-legend__item">
          <span class="chart-legend__swatch" style="background:#1B54B8"></span>
          Consultation fees (53%)
        </span>
        <span class="chart-legend__item">
          <span class="chart-legend__swatch" style="background:#0E7C78"></span>
          Pharmacy sales (47%)
        </span>
        <span class="chart-legend__item">
          <span class="chart-legend__swatch" style="background:#E0A63B"></span>
          Procedures and other (4%)
        </span>
      </div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Monthly revenue</span>
        <span class="chart-card__sub">Rs. million, last 12 months</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="rev-monthly"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Avg revenue per patient</span>
        <span class="chart-card__sub">Rs. per visit, last 8 weeks</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="rev-per-patient"></canvas></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>