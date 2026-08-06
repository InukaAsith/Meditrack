<?php

declare(strict_types=1);

$mode = $_GET['mode'] ?? 'scan';
if (!in_array($mode, ['clinic', 'photo', 'otc', 'manual', 'collection'], true)) {
  $mode = 'scan';
}
$title = 'Prepare & dispense';
$active = 'dispense';

$formulary = [
  ['name' => 'Amoxicillin 500 mg', 'dose' => '1 capsule 3 times a day', 'price' => 18, 'stock' => '320 left', 'stock_tone' => 'success', 'default_qty' => 15],
  ['name' => 'Azithromycin 500 mg', 'dose' => '1 a day for 3 days', 'price' => 180, 'stock' => '214 left', 'stock_tone' => 'success', 'default_qty' => 3],
  ['name' => 'Paracetamol 500 mg', 'dose' => '2 every 6 hours if fever', 'price' => 8, 'stock' => '1,240 left', 'stock_tone' => 'success', 'default_qty' => 20],
  ['name' => 'Cetirizine 10 mg', 'dose' => '1 at night', 'price' => 15, 'stock' => 'Expires in 20 days', 'stock_tone' => 'warning', 'default_qty' => 10],
  ['name' => 'Omeprazole 20 mg', 'dose' => '1 before breakfast', 'price' => 35, 'stock' => '450 left', 'stock_tone' => 'success', 'default_qty' => 14],
  ['name' => 'Metformin 500 mg', 'dose' => '2 a day after meals', 'price' => 8, 'stock' => '540 left', 'stock_tone' => 'success', 'default_qty' => 60],
  ['name' => 'Glimepiride 2 mg', 'dose' => '1 in the morning', 'price' => 10, 'stock' => '210 left', 'stock_tone' => 'success', 'default_qty' => 30],
  ['name' => 'Losartan 50 mg', 'dose' => '1 a day at night', 'price' => 22, 'stock' => '860 left', 'stock_tone' => 'success', 'default_qty' => 30],
  ['name' => 'Vitamin C 100 mg', 'dose' => '1 a day', 'price' => 15, 'stock' => '8 left', 'stock_tone' => 'danger', 'default_qty' => 8],
  ['name' => 'ORS sachets', 'dose' => '1 in a glass of water', 'price' => 45, 'stock' => '320 left', 'stock_tone' => 'success', 'default_qty' => 4],
];

if ($mode === 'clinic') {
  $order = [
    'type' => 'Clinic prescription', 'code' => 'RX-1042',
    'patient' => 'Nimsith Wickrama', 'initials' => 'NW', 'patient_code' => 'PT-0912',
    'details' => ['Issued today at 09:47', 'Dr. Sample Doctor 1'],
    'allergy' => 'Penicillin, Ibuprofen',
    'items' => [
      'Azithromycin 500 mg' => ['qty' => 3, 'dose' => '1 a day for 3 days, after meals'],
      'Paracetamol 500 mg' => ['qty' => 20, 'dose' => '2 every 6 hours if fever'],
      'Cetirizine 10 mg' => ['qty' => 5, 'dose' => '1 at night for 5 days'],
    ],
    'note' => 'Take losartan at night. Finish the full antibiotic course.',
    'payment' => 'Pay at counter',
  ];
} elseif ($mode === 'photo') {
  $order = [
    'type' => 'Photo prescription', 'code' => 'ORD-3391',
    'patient' => 'K.A. Inuka Asith', 'initials' => 'KI', 'patient_code' => 'PT-1088',
    'details' => ['Uploaded 8 min ago'],
    'allergy' => '',
    'items' => [
      'Metformin 500 mg' => ['qty' => 60, 'dose' => '2 a day after meals'],
      'Glimepiride 2 mg' => ['qty' => 30, 'dose' => '1 in the morning'],
    ],
    'note' => 'Please prepare the diabetes medicines for one month.',
    'payment' => 'Pay at collection',
  ];
} elseif ($mode === 'otc') {
  $order = [
    'type' => 'Over the counter', 'code' => 'OTC-2207',
    'patient' => 'Sandanu Dulmeth', 'initials' => 'SD', 'patient_code' => 'Walk-in',
    'details' => ['Came in 5 min ago'],
    'allergy' => '',
    'items' => [
      'Paracetamol 500 mg' => ['qty' => 10, 'dose' => 'When needed'],
      'ORS sachets' => ['qty' => 4, 'dose' => '1 in a glass of water'],
    ],
    'note' => 'Paracetamol and ORS for the family.',
    'payment' => 'Pay at counter',
  ];
} elseif ($mode === 'manual') {
  $order = [
    'type' => 'Manual dispense', 'code' => '',
    'patient' => 'Walk-in customer', 'initials' => 'W', 'patient_code' => 'No record',
    'details' => ['Paper prescription or counter sale'],
    'allergy' => '',
    'items' => [],
    'note' => '',
    'payment' => 'Pay at counter',
  ];
}

require __DIR__ . '/header.php';
?>
<?php if ($mode === 'scan'): ?>
  <?php $scanned = ($_GET['scanned'] ?? '') === '1'; ?>
  <div class="staff-head">
    <div>
      <h1 class="staff-head__title">Prepare &amp; dispense</h1>
    </div>
  </div>

  <div class="cal-viewtabs checkin-seg">
    <a class="cal-viewtabs__item is-active" href="/staff/pharmacist/dispense">Scan patient</a>
    <a class="cal-viewtabs__item" href="/staff/pharmacist/dispense?mode=manual">Manual dispense</a>
  </div>

  <div class="checkin-grid">
    <div>
      <div class="scan-box">
        <span class="scan-box__icon"><?= icon('search', 26) ?></span>
        <div class="scan-box__title">Scan the patient's QR card</div>
        <div class="scan-box__sub">or type their Patient ID or NIC</div>
        <form class="search-box scan-box__input" id="scan-form">
          <?= icon('search', 16, 'search-box__icon') ?>
          <input class="search-box__input" type="search" placeholder="PT-0912 or NIC…" aria-label="Patient ID or NIC">
        </form>
        <button class="btn btn--secondary btn--sm mt-6" type="button" id="scan-demo"><?= icon('search', 13) ?> Demo: scan PT-0912</button>
      </div>

      <div id="scan-result"<?= $scanned ? '' : ' hidden' ?>>
        <div class="lookup-card mt-8">
          <div class="lookup-card__top">
            <span class="lookup-card__avatar">NW</span>
            <div class="dispense-grow">
              <div class="lookup-card__name">Nimsith Wickrama <span class="lookup-card__verified"><?= icon('check', 12) ?>Identity checked</span></div>
              <div class="lookup-card__meta inline-parts"><span>PT-0912</span><span>NIC 911••••••V</span><span>42 years</span><span>Male</span></div>
            </div>
            <span class="badge badge--danger">Allergy: Penicillin, Ibuprofen</span>
          </div>
        </div>

        <div class="card mt-8">
          <div class="card__body">
            <div class="staff-eyebrow">Ready to hand over</div>
            <div class="morning-list">
              <div class="morning-row">
                <div>
                  <div class="morning-row__name">RX-1042 from Dr. Sample Doctor 1</div>
                  <div class="morning-row__sub">Prepared at 09:52. 4 medicines, Rs. 775, paid online.</div>
                </div>
                <a class="btn btn--success btn--xs" href="/staff/pharmacist/dispense?mode=collection&rx=RX-1042">Hand over</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Past prescriptions</div>
        <p class="field__desc" id="scan-side-hint"<?= $scanned ? ' hidden' : '' ?>>Scan a patient to see their prescriptions.</p>
        <?php
        $pastPrescriptions = [
          ['code' => 'RX-1042', 'from' => 'Dr. Sample Doctor 1', 'mode' => 'clinic', 'date' => 'Today at 09:47', 'items' => 'Azithromycin (3), Paracetamol (20), Cetirizine (5)', 'status' => 'Not collected', 'badge' => 'primary'],
          ['code' => 'ORD-0788', 'from' => 'Photo upload', 'mode' => 'photo', 'date' => '18 Jun 2026', 'items' => 'Salbutamol inhaler (1), Paracetamol (10)', 'status' => 'Dispensed', 'badge' => 'muted'],
          ['code' => 'RX-0871', 'from' => 'Dr. Sample Doctor 1', 'mode' => 'clinic', 'date' => '03 Jun 2026', 'items' => 'Losartan (30), Atorvastatin (30)', 'status' => 'Dispensed', 'badge' => 'muted'],
          ['code' => 'OTC-0446', 'from' => 'Counter sale', 'mode' => 'otc', 'date' => '21 Feb 2026', 'items' => 'ORS sachets (4), Vitamin C (10)', 'status' => 'Dispensed', 'badge' => 'muted'],
          ['code' => 'RX-0512', 'from' => 'Dr. Sample Doctor 2', 'mode' => 'clinic', 'date' => '03 Feb 2026', 'items' => 'Omeprazole 20 mg (14)', 'status' => 'Dispensed', 'badge' => 'muted'],
        ];
        ?>
        <div id="scan-side-list"<?= $scanned ? '' : ' hidden' ?>>
          <div class="morning-list">
            <?php foreach ($pastPrescriptions as $past): ?>
              <a class="morning-row" href="/staff/pharmacist/dispense?mode=<?= e($past['mode']) ?>&rx=<?= e($past['code']) ?>">
                <div>
                  <div class="morning-row__name"><?= e($past['code']) ?> from <?= e($past['from']) ?></div>
                  <div class="morning-row__sub"><?= e($past['date']) ?>: <?= e($past['items']) ?></div>
                </div>
                <span class="badge badge--<?= e($past['badge']) ?>"><?= e($past['status']) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php elseif ($mode === 'collection'): ?>
  <?php
  $collectionItems = [
    ['name' => 'Azithromycin 500 mg', 'dose' => '1 a day for 3 days, after meals', 'batch' => 'BT-2190', 'qty' => 3, 'amount' => 'Rs. 540'],
    ['name' => 'Paracetamol 500 mg', 'dose' => '2 every 6 hours if fever', 'batch' => 'BT-2175', 'qty' => 20, 'amount' => 'Rs. 160'],
    ['name' => 'Cetirizine 10 mg', 'dose' => '1 at night for 5 days', 'batch' => 'BT-2201', 'qty' => 5, 'amount' => 'Rs. 75'],
  ];
  ?>
  <div class="patient-find-subhead">
    <a class="book-back" href="/staff/pharmacist/dispense?scanned=1"><?= icon('chevronLeft', 15) ?>Hand over RX-1042</a>
  </div>

  <div class="staff-grid">
    <div>
      <div class="lookup-card mb-7">
        <div class="lookup-card__top">
          <span class="lookup-card__avatar">NW</span>
          <div class="dispense-grow">
            <div class="lookup-card__name">Nimsith Wickrama</div>
            <div class="lookup-card__meta inline-parts"><span>PT-0912</span><span>Check their ID before handing over</span></div>
          </div>
          <span class="badge badge--danger">Allergy: Penicillin, Ibuprofen</span>
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow staff-eyebrow--row">
            <span>Prepared medicines</span>
            <span class="badge badge--success">Prepared at 09:52</span>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Medicine</th>
                  <th>Batch</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($collectionItems as $item): ?>
                  <tr class="data-table__row">
                    <td>
                      <div class="table-patient"><strong><?= e($item['name']) ?></strong><span class="table-patient__plain"><?= e($item['dose']) ?></span></div>
                    </td>
                    <td class="mono"><?= e($item['batch']) ?></td>
                    <td class="mono text-bold"><?= e((string) $item['qty']) ?></td>
                    <td class="mono"><?= e($item['amount']) ?></td>
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
          <div class="staff-eyebrow">Payment</div>
          <div class="bill-row">
            <span class="bill-row__label">3 medicines</span>
            <span class="bill-row__amount">Rs. 775</span>
          </div>
          <div class="bill-row">
            <span class="bill-row__label">Paid online at 09:47</span>
            <span class="bill-row__amount">− Rs. 775</span>
          </div>
          <div class="bill-net"><span>To pay now</span><span class="mono">Rs. 0</span></div>
          <a class="btn btn--primary btn--block mt-6" href="/staff/pharmacist/prepare-queue?collected=RX-1042">Hand over</a>
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow">Cancel this order</div>
          <p class="field__desc">The medicines go back into stock and the online payment is refunded.</p>
          <button class="btn btn--danger btn--block mt-6" type="button">Cancel and refund</button>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>
  <?php
  $startTotal = 0;
  foreach ($formulary as $medicine) {
    if (isset($order['items'][$medicine['name']])) {
      $startTotal += $order['items'][$medicine['name']]['qty'] * $medicine['price'];
    }
  }
  $startCount = count($order['items']);
  ?>
  <div class="patient-find-subhead">
    <a class="book-back" href="/staff/pharmacist/dispense"><?= icon('chevronLeft', 15) ?><?= e($order['type']) ?><?= $order['code'] !== '' ? ' ' . e($order['code']) : '' ?></a>
    <a class="btn btn--secondary btn--sm" href="/staff/pharmacist/dispense?scanned=1"><?= icon('records', 13) ?> Patient's prescriptions</a>
  </div>

  <div class="staff-grid">
    <div>
      <div class="lookup-card mb-7">
        <div class="lookup-card__top">
          <span class="lookup-card__avatar"><?= e($order['initials']) ?></span>
          <div class="dispense-grow">
            <div class="lookup-card__name"><?= e($order['patient']) ?></div>
            <div class="lookup-card__meta inline-parts">
              <span><?= e($order['patient_code']) ?></span>
              <?php foreach ($order['details'] as $detail): ?><span><?= e($detail) ?></span><?php endforeach; ?>
            </div>
          </div>
          <?php if ($order['allergy'] !== ''): ?>
            <span class="badge badge--danger">Allergy: <?= e($order['allergy']) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow">Medicines</div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Medicine</th>
                  <th>Stock</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                  <th class="data-table__actions"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($formulary as $medicine): ?>
                  <?php
                  $onOrder = isset($order['items'][$medicine['name']]);
                  $qty = $onOrder ? $order['items'][$medicine['name']]['qty'] : $medicine['default_qty'];
                  $dose = $onOrder ? $order['items'][$medicine['name']]['dose'] : $medicine['dose'];
                  ?>
                  <tr class="data-table__row" data-medicine="<?= e(strtolower($medicine['name'])) ?>" data-unit-price="<?= e((string) $medicine['price']) ?>" data-default-qty="<?= e((string) $medicine['default_qty']) ?>"<?= $onOrder ? '' : ' hidden' ?>>
                    <td>
                      <div class="table-patient"><strong><?= e($medicine['name']) ?></strong><span class="table-patient__plain"><?= e($dose) ?></span></div>
                    </td>
                    <td><span class="badge badge--<?= e($medicine['stock_tone']) ?>"><?= e($medicine['stock']) ?></span></td>
                    <td>
                      <div class="qty-stepper">
                        <button class="qty-stepper__button" type="button" data-step="-1" aria-label="One less">−</button>
                        <span class="qty-stepper__value" data-qty><?= e((string) $qty) ?></span>
                        <button class="qty-stepper__button" type="button" data-step="1" aria-label="One more">+</button>
                      </div>
                    </td>
                    <td class="mono">Rs. <span data-amount><?= e(number_format($qty * $medicine['price'])) ?></span></td>
                    <td class="data-table__actions">
                      <button class="link-act link-act--danger" type="button" data-remove>Remove</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <p class="field__desc" id="dispense-empty"<?= $startCount === 0 ? '' : ' hidden' ?>>No medicines yet. Search below to add one.</p>

          <div class="dispense-add">
            <div class="search-box dispense-add__search">
              <?= icon('search', 16, 'search-box__icon') ?>
              <input class="search-box__input" type="search" id="dispense-search" placeholder="Type a medicine to add…" autocomplete="off" aria-label="Find a medicine">
              <div class="dispense-add__results" id="dispense-results" hidden>
                <?php foreach ($formulary as $medicine): ?>
                  <div data-result="<?= e(strtolower($medicine['name'])) ?>" hidden>
                    <button class="dispense-add__result" type="button">
                      <span><?= e($medicine['name']) ?></span>
                      <span class="text-muted">Rs. <?= e((string) $medicine['price']) ?> each</span>
                    </button>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <button class="btn btn--secondary" type="button" id="dispense-add"><?= icon('plus', 14) ?>Add medicine</button>
          </div>
          <p class="form-error" id="dispense-add-error" hidden></p>
        </div>
      </div>

      <?php if ($order['note'] !== ''): ?>
        <div class="card mt-7">
          <div class="card__body">
            <div class="staff-eyebrow">Note from the patient or doctor</div>
            <p><?= e($order['note']) ?></p>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <div class="staff-side">
      <?php if ($mode === 'photo'): ?>
        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow">Uploaded prescription</div>
            <div class="dispense-photo">
              <div class="dispense-photo__page" id="dispense-photo">
                <?= icon('records', 32) ?>
                <span>Prescription photo</span>
              </div>
            </div>
            <div class="staff-filters mt-6">
              <button class="staff-pill" type="button" data-photo="zoom-out" aria-label="Zoom out">−</button>
              <button class="staff-pill" type="button" data-photo="zoom-in" aria-label="Zoom in">+</button>
              <button class="staff-pill" type="button" data-photo="rotate">Rotate</button>
              <button class="staff-pill" type="button" data-photo="reset">Reset</button>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow">Payment</div>
          <div class="bill-row">
            <span class="bill-row__label" id="dispense-count"><?= $startCount === 1 ? '1 medicine' : e((string) $startCount) . ' medicines' ?></span>
            <span class="bill-row__amount"><?= e($order['payment']) ?></span>
          </div>
          <div class="bill-net"><span>Total</span><span class="mono" id="dispense-total">Rs. <?= e(number_format($startTotal)) ?></span></div>
          <div class="staff-filters mt-6" data-pay-buttons>
            <button class="staff-pill is-active" type="button">Cash</button>
            <button class="staff-pill" type="button">Card</button>
          </div>
          <?php if ($mode === 'manual'): ?>
            <button class="btn btn--primary btn--block mt-6" type="button">Dispense and bill</button>
          <?php else: ?>
            <a class="btn btn--primary btn--block mt-6" href="/staff/pharmacist/prepare-queue?prepared=<?= e($order['code']) ?>">Prepare and tell the patient</a>
            <button class="btn btn--secondary btn--block mt-4" type="button">Dispense and bill now</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="/assets/js/pharmacist/dispense.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
