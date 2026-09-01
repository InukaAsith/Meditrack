<?php

declare(strict_types=1);

$title = 'Stock alerts';
$active = 'stock-alerts';

$lowStock = [
  ['name' => 'Salbutamol inhaler', 'form' => 'Inhaler', 'batch' => 'BT-2168', 'supplier' => 'GlobalPharm', 'stock' => '0', 'reorder' => '25', 'status' => 'Out of stock', 'badge' => 'danger'],
  ['name' => 'Amoxicillin 500 mg', 'form' => 'Capsule', 'batch' => 'BT-2214', 'supplier' => 'MedLanka Pvt Ltd', 'stock' => '14', 'reorder' => '40', 'status' => 'Low stock', 'badge' => 'danger'],
  ['name' => 'Amox-Clav 625 mg', 'form' => 'Tablet', 'batch' => 'BT-2216', 'supplier' => 'MedLanka Pvt Ltd', 'stock' => '96', 'reorder' => '80', 'status' => 'Near reorder level', 'badge' => 'warning'],
];

$expiringBatches = [
  ['name' => 'Cetirizine 10 mg', 'form' => 'Tablet', 'batch' => 'BT-2201', 'supplier' => 'MedLanka Pvt Ltd', 'expires' => '29 Jul 2026', 'days' => '18 days', 'badge' => 'danger', 'on_hand' => '320'],
  ['name' => 'Amoxicillin 500 mg', 'form' => 'Capsule', 'batch' => 'BT-2214', 'supplier' => 'MedLanka Pvt Ltd', 'expires' => '02 Aug 2026', 'days' => '22 days', 'badge' => 'warning', 'on_hand' => '14'],
  ['name' => 'Insulin Glargine', 'form' => 'Injection', 'batch' => 'BT-2233', 'supplier' => 'GlobalPharm', 'expires' => '11 Aug 2026', 'days' => '31 days', 'badge' => 'warning', 'on_hand' => '26'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Stock alerts</h1>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--primary" href="/staff/pharmacist/register-batch"><?= icon('plus', 14) ?>Register batch</a>
  </div>
</div>

<div class="staff-kpis stock-alerts-kpis">
  <div class="staff-kpi">
    <div class="staff-kpi__label">Out of stock</div>
    <div class="staff-kpi__value staff-kpi__value--danger">1</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Low on stock</div>
    <div class="staff-kpi__value staff-kpi__value--danger">1</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Expiring in 30 days</div>
    <div class="staff-kpi__value staff-kpi__value--warning">3</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Value of expiring stock</div>
    <div class="staff-kpi__value">Rs. 47,800</div>
  </div>
</div>

<div class="card mb-7">
  <div class="card__body">
    <div class="staff-eyebrow">Low on stock</div>
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Batch</th>
            <th>Supplier</th>
            <th>Stock</th>
            <th>Reorder level</th>
            <th>Status</th>
            <th class="data-table__actions">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lowStock as $item): ?>
            <tr class="data-table__row">
              <td>
                <div class="table-patient"><strong><?= e($item['name']) ?></strong><span class="table-patient__plain"><?= e($item['form']) ?></span></div>
              </td>
              <td class="mono"><?= e($item['batch']) ?></td>
              <td><?= e($item['supplier']) ?></td>
              <td class="mono text-bold"><?= e($item['stock']) ?></td>
              <td class="mono"><?= e($item['reorder']) ?></td>
              <td><span class="badge badge--<?= e($item['badge']) ?>"><?= e($item['status']) ?></span></td>
              <td class="data-table__actions"><a class="link-act" href="/staff/pharmacist/register-batch">Restock</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div class="staff-eyebrow">Expiring in 30 days</div>
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Batch</th>
            <th>Supplier</th>
            <th>Expires</th>
            <th>Time left</th>
            <th>On hand</th>
            <th class="data-table__actions">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($expiringBatches as $batch): ?>
            <tr class="data-table__row">
              <td>
                <div class="table-patient"><strong><?= e($batch['name']) ?></strong><span class="table-patient__plain"><?= e($batch['form']) ?></span></div>
              </td>
              <td class="mono"><?= e($batch['batch']) ?></td>
              <td><?= e($batch['supplier']) ?></td>
              <td><?= e($batch['expires']) ?></td>
              <td><span class="badge badge--<?= e($batch['badge']) ?>"><?= e($batch['days']) ?></span></td>
              <td class="mono text-bold"><?= e($batch['on_hand']) ?></td>
              <td class="data-table__actions"><a class="link-act" href="/staff/pharmacist/register-batch">Restock</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
