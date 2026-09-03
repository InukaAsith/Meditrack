<?php

declare(strict_types=1);

$title = 'Dashboard';
$active = 'dashboard';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e(first_name($manager['name'])) ?></h1>
    <div class="staff-head__sub"><?= e(date('l j M Y')) ?></div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/manager/financial-reports"><?= icon('download', 14) ?>Financial report</a>
    <a class="btn btn--primary" href="/staff/manager/analytics"><?= icon('records', 14) ?>Open analytics</a>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Today's appointments</span>
    </div>
    <div class="manager-kpi__value">42</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Revenue today</span>
    </div>
    <div class="manager-kpi__value">Rs. 96,300</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Waiting now</span>
    </div>
    <div class="manager-kpi__value">7</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Low-stock items</span>
    </div>
    <div class="manager-kpi__value">9</div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Appointments trend</span>
        <span class="chart-card__sub">Booked and completed, last 7 days</span>
      </div>
      <span class="chart-card__tag">7 days</span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="dash-appointments-trend"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Revenue trend</span>
        <span class="chart-card__sub">Rs. '000 a day, clinic and pharmacy</span>
      </div>
      <span class="chart-card__tag">7 days</span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="dash-revenue-trend"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Doctor workload</span>
        <span class="chart-card__sub">Consultations today by doctor</span>
      </div>
      <span class="chart-card__tag">Today</span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="dash-doctor-workload"></canvas></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Queue performance</span>
        <span class="chart-card__sub">Average wait (min) through the day</span>
      </div>
      <span class="chart-card__tag">Today</span>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="dash-queue-performance"></canvas></div>
    </div>
  </div>
</div>

<div class="chart-grid chart-grid--2">
  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Low-stock alerts</span>
        <a href="/staff/manager/pharmacy-alerts">Pharmacy alerts →</a>
      </div>
      <div class="manager-list">
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--danger"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Amoxicillin 500mg</span>
            <span class="manager-list__meta inline-parts"><span>40 left</span><span>Reorder at 120</span></span>
          </div>
          <div class="manager-list__aside">
            <span class="badge badge--danger">Below reorder</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--warning"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Metformin 850mg</span>
            <span class="manager-list__meta inline-parts"><span>65 left</span><span>Reorder at 100</span></span>
          </div>
          <div class="manager-list__aside">
            <span class="badge badge--warning">Low</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--danger"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Salbutamol inhaler</span>
            <span class="manager-list__meta inline-parts"><span>8 left</span><span>Reorder at 25</span></span>
          </div>
          <div class="manager-list__aside">
            <span class="badge badge--danger">Below reorder</span>
          </div>
        </div>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--warning"><?= icon('pill', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title">Cetirizine 10mg</span>
            <span class="manager-list__meta inline-parts"><span>90 left</span><span>Reorder at 120</span></span>
          </div>
          <div class="manager-list__aside">
            <span class="badge badge--warning">Low</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow staff-eyebrow--row">
        <span>Recent activity</span>
        <a href="/staff/manager/notifications">Notifications →</a>
      </div>
      <div class="m-activity">
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--success"></span>
          <div class="m-activity__body">
            <div class="m-activity__text"><b>Dr. Sample Doctor 1</b> arrived</div>
            <div class="m-activity__time">09:38</div>
          </div>
        </div>
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--primary"></span>
          <div class="m-activity__body">
            <div class="m-activity__text"><b>PH-INV-2041</b> dispensed to K.A. Inuka Asith</div>
            <div class="m-activity__time">09:32</div>
          </div>
        </div>
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--danger"></span>
          <div class="m-activity__body">
            <div class="m-activity__text">Low stock: <b>Salbutamol inhaler</b> hit reorder point</div>
            <div class="m-activity__time">09:15</div>
          </div>
        </div>
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--info"></span>
          <div class="m-activity__body">
            <div class="m-activity__text">Reception opened the counter with Rs. 10,000</div>
            <div class="m-activity__time">08:55</div>
          </div>
        </div>
        <div class="m-activity__row">
          <span class="m-activity__dot m-activity__dot--warning"></span>
          <div class="m-activity__body">
            <div class="m-activity__text"><b>Dr. Sample Doctor 2</b> flagged running late (+12 min)</div>
            <div class="m-activity__time">08:50</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>