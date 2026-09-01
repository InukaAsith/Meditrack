<?php

declare(strict_types=1);

$title = 'Billing history';
$active = 'billing-history';

$invoices = [
  ['invoice' => 'PH-INV-0311', 'time' => '09:52', 'patient' => 'K. Ashan Charuka', 'order' => 'RX-1039', 'items' => 'Azithromycin (3), Paracetamol (20)', 'method' => 'Cash', 'amount' => 'Rs. 1,240', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0310', 'time' => '09:31', 'patient' => 'Sandanu Dulmeth', 'order' => 'RX-1041', 'items' => 'Omeprazole (14), Vitamin C (10)', 'method' => 'Card', 'amount' => 'Rs. 640', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0309', 'time' => '09:12', 'patient' => 'Walk-in', 'order' => 'Paper prescription', 'items' => 'Cetirizine (10)', 'method' => 'Cash', 'amount' => 'Rs. 380', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0308', 'time' => '08:58', 'patient' => 'Nimsith Wickrama', 'order' => 'RX-1040', 'items' => 'Salbutamol inhaler (1), Paracetamol (10)', 'method' => 'Cash', 'amount' => 'Rs. 815', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0307', 'time' => '08:41', 'patient' => 'Walk-in', 'order' => 'Counter sale', 'items' => 'Paracetamol (10), ORS (4)', 'method' => 'Cash', 'amount' => 'Rs. 260', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0306', 'time' => '08:22', 'patient' => 'M. L. Omindu Gunathilaka', 'order' => 'RX-0871', 'items' => 'Losartan (30), Atorvastatin (30)', 'method' => 'Online', 'amount' => 'Rs. 1,680', 'status' => 'Paid', 'badge' => 'success'],
  ['invoice' => 'PH-INV-0305', 'time' => '08:05', 'patient' => 'G. G. Mithun Majika', 'order' => 'RX-1038', 'items' => 'Amoxicillin (15), returned', 'method' => 'Online', 'amount' => '− Rs. 270', 'status' => 'Refunded', 'badge' => 'danger'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Billing history</h1>
    <div class="staff-head__sub"><?= e(date('l j M Y')) ?></div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button"><?= icon('download', 14) ?>Export CSV</button>
    <button class="btn btn--secondary" type="button" data-print><?= icon('print', 14) ?>Print day report</button>
  </div>
</div>

<div class="staff-toolbar">
  <div class="search-box">
    <?= icon('search', 16, 'search-box__icon') ?>
    <input class="search-box__input" type="search" id="billing-search" placeholder="Search patient or invoice…" autocomplete="off" aria-label="Search invoices">
  </div>
  <label class="doctor-select">
    <?= icon('calendar', 14) ?>
    <select aria-label="Pick a day">
      <option selected>Today</option>
      <option>Yesterday</option>
      <option>This week</option>
    </select>
  </label>
</div>

<div class="staff-grid">
  <div>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Today's invoices</div>
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Items</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($invoices as $inv): ?>
                <tr class="data-table__row" data-search="<?= e(strtolower($inv['invoice'] . ' ' . $inv['patient'] . ' ' . $inv['order'])) ?>">
                  <td class="mono text-bold"><?= e($inv['invoice']) ?></td>
                  <td class="table-time"><?= e($inv['time']) ?></td>
                  <td>
                    <div class="table-patient"><strong><?= e($inv['patient']) ?></strong><span><?= e($inv['order']) ?></span></div>
                  </td>
                  <td><?= e($inv['items']) ?></td>
                  <td><?= e($inv['method']) ?></td>
                  <td class="mono text-bold"><?= e($inv['amount']) ?></td>
                  <td><span class="badge badge--<?= e($inv['badge']) ?>"><?= e($inv['status']) ?></span></td>
                  <td class="data-table__actions">
                    <button class="link-act" type="button" data-print><?= icon('print', 13) ?> Print</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="field__desc" id="billing-empty" hidden>No invoices match your search.</p>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Day summary</div>
        <div class="bill-row">
          <span class="bill-row__label">Cash</span>
          <span class="bill-row__amount">Rs. 24,860</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Card</span>
          <span class="bill-row__amount">Rs. 8,120</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Online</span>
          <span class="bill-row__amount">Rs. 5,260</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Refunded</span>
          <span class="bill-row__amount bill-row__amount--danger">− Rs. 270</span>
        </div>
        <div class="bill-net"><span>Pharmacy sales today</span><span class="mono">Rs. 38,240</span></div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/pharmacist/billing-history.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
