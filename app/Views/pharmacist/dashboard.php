<?php

declare(strict_types=1);

$title = 'Dashboard';
$active = 'dashboard';

$recentDispense = [
  ['invoice' => 'PH-INV-0311', 'time' => '09:52', 'patient' => 'K. Ashan Charuka', 'order' => 'RX-1039', 'doctor' => 'Dr. Sample Doctor 1', 'amount' => 'Rs. 1,240', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0310', 'time' => '09:31', 'patient' => 'Sandanu Dulmeth', 'order' => 'RX-1041', 'doctor' => 'Dr. Sample Doctor 2', 'amount' => 'Rs. 640', 'status' => 'Balance due', 'badge' => 'warning'],
  ['invoice' => 'PH-INV-0309', 'time' => '09:12', 'patient' => 'Walk-in', 'order' => 'Manual', 'doctor' => 'Outside doctor', 'amount' => 'Rs. 380', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0308', 'time' => '08:58', 'patient' => 'Nimsith Wickrama', 'order' => 'RX-1040', 'doctor' => 'Dr. Sample Doctor 3', 'amount' => 'Rs. 815', 'status' => 'Paid', 'badge' => 'success'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e(first_name($staff['name'])) ?></h1>
    <div class="staff-head__sub"><?= e(date('l j M Y')) ?></div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/pharmacist/register-batch"><?= icon('plus', 14) ?>Register batch</a>
    <a class="btn btn--primary" href="/staff/pharmacist/dispense"><?= icon('plus', 14) ?>New dispense</a>
  </div>
</div>

<div class="staff-kpis">
  <div class="staff-kpi">
    <div class="staff-kpi__label">Walk-in patients today</div>
    <div class="staff-kpi__value">26</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Prescriptions dispensed</div>
    <div class="staff-kpi__value staff-kpi__value--success">23</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Low on stock</div>
    <div class="staff-kpi__value staff-kpi__value--danger">1</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Expiring in 30 days</div>
    <div class="staff-kpi__value staff-kpi__value--warning">2</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Pharmacy sales today</div>
    <div class="staff-kpi__value">Rs. 38,240</div>
  </div>
</div>

<div class="staff-grid">
  <div>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Recent dispensing</span>
          <a href="/staff/pharmacist/billing-history">Billing history →</a>
        </div>
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Amount</th>
                <th>Status</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentDispense as $row): ?>
                <tr class="data-table__row">
                  <td class="mono text-bold"><?= e($row['invoice']) ?></td>
                  <td class="table-time"><?= e($row['time']) ?></td>
                  <td>
                    <div class="table-patient"><strong><?= e($row['patient']) ?></strong><span><?= e($row['order']) ?></span></div>
                  </td>
                  <td><?= e($row['doctor']) ?></td>
                  <td class="mono text-bold"><?= e($row['amount']) ?></td>
                  <td><span class="badge badge--<?= e($row['badge']) ?>"><?= e($row['status']) ?></span></td>
                  <td class="data-table__actions"><a class="link-act" href="/staff/pharmacist/dispense">Open</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Low on stock</span>
          <a href="/staff/pharmacist/stock-alerts">Stock alerts →</a>
        </div>
        <div class="morning-list">
          <div class="morning-row">
            <div>
              <div class="morning-row__name">Amoxicillin 500 mg</div>
              <div class="morning-row__sub">Batch BT-2214</div>
            </div>
            <span class="badge badge--danger">14 left</span>
          </div>
        </div>
        <a class="btn btn--secondary btn--block mt-6" href="/staff/pharmacist/register-batch"><?= icon('plus', 14) ?>Register new batch</a>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Expiring in 30 days</div>
        <div class="morning-list">
          <div class="morning-row">
            <div>
              <div class="morning-row__name">Cetirizine 10 mg</div>
              <div class="morning-row__sub">Batch BT-2201, expires 29 Jul</div>
            </div>
            <span class="badge badge--warning">18 days</span>
          </div>
          <div class="morning-row">
            <div>
              <div class="morning-row__name">Amoxicillin 500 mg</div>
              <div class="morning-row__sub">Batch BT-2214, expires 02 Aug</div>
            </div>
            <span class="badge badge--warning">22 days</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
