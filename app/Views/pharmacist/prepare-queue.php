<?php

declare(strict_types=1);

$title = 'Prepare queue';
$active = 'prepare-queue';

$toPrepare = [
  ['type' => 'clinic', 'type_label' => 'Clinic prescription', 'patient' => 'Nimsith Wickrama', 'code' => 'PT-0912', 'order' => 'RX-1042', 'from' => 'Dr. Sample Doctor 1', 'requested' => '2 min ago', 'medicines' => '4 medicines', 'payment' => 'Pay at counter', 'allergy' => 'Penicillin'],
  ['type' => 'photo', 'type_label' => 'Photo prescription', 'patient' => 'K.A. Inuka Asith', 'code' => 'PT-1088', 'order' => 'ORD-3391', 'from' => 'Photo upload', 'requested' => '8 min ago', 'medicines' => 'Not added yet', 'payment' => 'Pay at counter', 'allergy' => ''],
  ['type' => 'otc', 'type_label' => 'Over the counter', 'patient' => 'Sandanu Dulmeth', 'code' => 'Walk-in', 'order' => 'OTC-2207', 'from' => 'Counter', 'requested' => '5 min ago', 'medicines' => '2 medicines', 'payment' => 'Pay at counter', 'allergy' => ''],
];

$prepared = [
  ['type' => 'clinic', 'type_label' => 'Clinic prescription', 'patient' => 'K. Ashan Charuka', 'code' => 'PT-0834', 'order' => 'RX-1039', 'from' => 'Dr. Sample Doctor 1', 'prepared' => '09:52', 'medicines' => '2 medicines', 'payment' => 'Paid online', 'badge' => 'success'],
  ['type' => 'photo', 'type_label' => 'Photo prescription', 'patient' => 'Nimsith Wickrama', 'code' => 'PT-0844', 'order' => 'ORD-3388', 'from' => 'Photo upload', 'prepared' => '08:40', 'medicines' => '5 medicines', 'payment' => 'Pay at counter', 'badge' => 'muted'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Prepare queue</h1>
  </div>
</div>

<div data-tab-group>
  <div class="cal-viewtabs checkin-seg">
    <button class="cal-viewtabs__item is-active" type="button" data-tab="to-prepare">To prepare (<span id="to-prepare-count"><?= count($toPrepare) ?></span>)</button>
    <button class="cal-viewtabs__item" type="button" data-tab="prepared">Prepared (<span id="prepared-count"><?= count($prepared) ?></span>)</button>
  </div>

  <div class="staff-toolbar">
    <div class="search-box">
      <?= icon('search', 16, 'search-box__icon') ?>
      <input class="search-box__input" type="search" id="queue-search" placeholder="Search patient, order number or doctor…" autocomplete="off" aria-label="Search the queue">
    </div>
    <div class="staff-filters">
      <button class="staff-pill is-active" type="button" data-type-filter="all">All</button>
      <button class="staff-pill" type="button" data-type-filter="clinic">Clinic prescription</button>
      <button class="staff-pill" type="button" data-type-filter="photo">Photo prescription</button>
      <button class="staff-pill" type="button" data-type-filter="otc">Over the counter</button>
    </div>
  </div>

  <section data-tab-panel="to-prepare">
    <div class="card">
      <div class="card__body">
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Patient</th>
                <th>Order</th>
                <th>Type</th>
                <th>Requested</th>
                <th>Medicines</th>
                <th>Payment</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($toPrepare as $order): ?>
                <tr class="data-table__row" data-queue-row data-order="<?= e($order['order']) ?>" data-type="<?= e($order['type']) ?>"
                  data-search="<?= e(strtolower($order['patient'] . ' ' . $order['code'] . ' ' . $order['order'] . ' ' . $order['from'])) ?>">
                  <td>
                    <div class="table-patient"><strong><?= e($order['patient']) ?></strong><span><?= e($order['code']) ?></span></div>
                    <?php if ($order['allergy'] !== ''): ?>
                      <div class="mt-2"><span class="badge badge--danger">Allergy: <?= e($order['allergy']) ?></span></div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="table-patient"><strong><?= e($order['order']) ?></strong><span class="table-patient__plain"><?= e($order['from']) ?></span></div>
                  </td>
                  <td><?= e($order['type_label']) ?></td>
                  <td><?= e($order['requested']) ?></td>
                  <td><?= e($order['medicines']) ?></td>
                  <td><span class="badge badge--muted"><?= e($order['payment']) ?></span></td>
                  <td class="data-table__actions">
                    <a class="btn btn--primary btn--xs" href="/staff/pharmacist/dispense?mode=<?= e($order['type']) ?>&rx=<?= e($order['order']) ?>">Open</a>
                    <button class="link-act link-act--muted" type="button" data-dismiss>Dismiss</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="field__desc" data-queue-empty hidden>No orders to prepare.</p>
      </div>
    </div>
  </section>

  <section data-tab-panel="prepared" hidden>
    <div class="card">
      <div class="card__body">
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Patient</th>
                <th>Order</th>
                <th>Type</th>
                <th>Prepared</th>
                <th>Medicines</th>
                <th>Payment</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($toPrepare as $order): ?>
                <tr class="data-table__row" data-prepared-copy="<?= e($order['order']) ?>" data-type="<?= e($order['type']) ?>"
                  data-search="<?= e(strtolower($order['patient'] . ' ' . $order['code'] . ' ' . $order['order'] . ' ' . $order['from'])) ?>" hidden>
                  <td>
                    <div class="table-patient"><strong><?= e($order['patient']) ?></strong><span><?= e($order['code']) ?></span></div>
                  </td>
                  <td>
                    <div class="table-patient"><strong><?= e($order['order']) ?></strong><span class="table-patient__plain"><?= e($order['from']) ?></span></div>
                  </td>
                  <td><?= e($order['type_label']) ?></td>
                  <td>Just now</td>
                  <td><?= e($order['medicines']) ?></td>
                  <td><span class="badge badge--muted"><?= e($order['payment']) ?></span></td>
                  <td class="data-table__actions">
                    <a class="btn btn--success btn--xs" href="/staff/pharmacist/dispense?mode=collection&rx=<?= e($order['order']) ?>">Hand over</a>
                  </td>
                </tr>
              <?php endforeach; ?>

              <?php foreach ($prepared as $order): ?>
                <tr class="data-table__row" data-queue-row data-order="<?= e($order['order']) ?>" data-type="<?= e($order['type']) ?>"
                  data-search="<?= e(strtolower($order['patient'] . ' ' . $order['code'] . ' ' . $order['order'] . ' ' . $order['from'])) ?>">
                  <td>
                    <div class="table-patient"><strong><?= e($order['patient']) ?></strong><span><?= e($order['code']) ?></span></div>
                  </td>
                  <td>
                    <div class="table-patient"><strong><?= e($order['order']) ?></strong><span class="table-patient__plain"><?= e($order['from']) ?></span></div>
                  </td>
                  <td><?= e($order['type_label']) ?></td>
                  <td class="table-time"><?= e($order['prepared']) ?></td>
                  <td><?= e($order['medicines']) ?></td>
                  <td><span class="badge badge--<?= e($order['badge']) ?>"><?= e($order['payment']) ?></span></td>
                  <td class="data-table__actions">
                    <a class="btn btn--success btn--xs" href="/staff/pharmacist/dispense?mode=collection&rx=<?= e($order['order']) ?>">Hand over</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="field__desc" data-queue-empty hidden>No orders waiting for collection.</p>
      </div>
    </div>
  </section>
</div>

<script src="/assets/js/pharmacist/prepare-queue.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
