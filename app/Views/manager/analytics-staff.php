<?php

declare(strict_types=1);

$title = 'Staff analytics';
$active = 'analytics';
$analyticsActive = 'staff';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Analytics</h1>
    <div class="staff-head__sub">Work done by each role in the last 30 days</div>
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

<div class="sec-head">
  <h2 class="sec-head__title">Doctors</h2>
  <span class="sec-head__hint">Patients seen and average consult time</span>
</div>
<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Patients seen by doctor</span>
        <span class="chart-card__sub">Last 30 days</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="st-doctor-seen"></canvas></div>
    </div>
  </div>
  <div class="card">
    <div class="card__body">
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Doctor</th>
              <th class="table-num">Seen</th>
              <th class="table-num">Avg dur.</th>
              <th class="table-num">Completed</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="table-lead">
                  <span class="table-lead__avatar avatar--blue">DS</span>
                  <div class="table-lead__text"><strong>Dr. Sample Doctor 1</strong><span>General</span></div>
                </div>
              </td>
              <td class="table-num">118</td>
              <td class="table-num">11 min</td>
              <td class="table-num">112</td>
            </tr>
            <tr>
              <td>
                <div class="table-lead">
                  <span class="table-lead__avatar avatar--teal">DF</span>
                  <div class="table-lead__text"><strong>Dr. Sample Doctor 3</strong><span>Pediatrics</span></div>
                </div>
              </td>
              <td class="table-num">64</td>
              <td class="table-num">13 min</td>
              <td class="table-num">60</td>
            </tr>
            <tr>
              <td>
                <div class="table-lead">
                  <span class="table-lead__avatar avatar--amber">DP</span>
                  <div class="table-lead__text"><strong>Dr. Sample Doctor 2</strong><span>ENT</span></div>
                </div>
              </td>
              <td class="table-num">52</td>
              <td class="table-num">14 min</td>
              <td class="table-num">48</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="sec-head">
  <h2 class="sec-head__title">Receptionists</h2>
  <span class="sec-head__hint">Registrations &amp; check-ins</span>
</div>
<div class="chart-grid chart-grid--wide">
  <div class="chart-card">
    <div class="chart-card__head">
      <div class="chart-card__titles">
        <span class="chart-card__title">Registrations &amp; check-ins</span>
        <span class="chart-card__sub">Each receptionist, last 30 days</span>
      </div>
    </div>
    <div class="chart-card__body">
      <div class="chart-card__canvas"><canvas data-chart="st-reception"></canvas></div>
    </div>
  </div>
  <div class="card">
    <div class="card__body">
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Receptionist</th>
              <th class="table-num">Registrations</th>
              <th class="table-num">Check-ins</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="table-lead">
                  <span class="table-lead__avatar avatar--blue">SD</span>
                  <div class="table-lead__text"><strong>Sandanu D.</strong><span>Receptionist</span></div>
                </div>
              </td>
              <td class="table-num">96</td>
              <td class="table-num">412</td>
            </tr>
            <tr>
              <td>
                <div class="table-lead">
                  <span class="table-lead__avatar avatar--teal">KA</span>
                  <div class="table-lead__text"><strong>Ashan C.</strong><span>Receptionist</span></div>
                </div>
              </td>
              <td class="table-num">74</td>
              <td class="table-num">356</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="sec-head">
  <h2 class="sec-head__title">Pharmacy &amp; supporting staff</h2>
  <span class="sec-head__hint">Prescriptions dispensed and vitals recorded</span>
</div>
<div class="chart-grid chart-grid--2">
  <div class="card">
    <div class="card__body">
      <div class="manager-list__row" style="border:none;padding:0">
        <span class="manager-list__icon manager-list__icon--success"><?= icon('pill', 18) ?></span>
        <div class="manager-list__body">
          <span class="manager-list__title">Mithun M. (Pharmacist)</span>
          <span class="manager-list__meta">Prescriptions dispensed</span>
        </div>
        <div class="manager-list__aside">
          <span class="stat-card__value" style="font-size:var(--fs-2xl)">486</span>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card__body">
      <div class="manager-list__row" style="border:none;padding:0">
        <span class="manager-list__icon manager-list__icon--info"><?= icon('health', 18) ?></span>
        <div class="manager-list__body">
          <span class="manager-list__title">Mithun M. (Supporting staff)</span>
          <span class="manager-list__meta">Vitals recorded</span>
        </div>
        <div class="manager-list__aside">
          <span class="stat-card__value" style="font-size:var(--fs-2xl)">358</span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>