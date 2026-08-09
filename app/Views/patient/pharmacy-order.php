<?php

declare(strict_types=1);

$title = 'Your order';
$active = 'prescriptions';

$prescriptions = [
  'RX-1042' => ['doctor' => 'Dr. Sample Doctor 1', 'condition' => 'Chest infection', 'issued' => '11 Jul 2026'],
  'RX-1035' => ['doctor' => 'Dr. Sample Doctor 1', 'condition' => 'Hypertension review', 'issued' => '02 Jul 2026'],
  'RX-1021' => ['doctor' => 'Dr. Sample Doctor 2', 'condition' => 'Gastritis', 'issued' => '20 Jun 2026'],
  'RX-0998' => ['doctor' => 'Dr. Sample Doctor 1', 'condition' => 'Seasonal allergy', 'issued' => '01 Jun 2026'],
];

$pickedCode = isset($_GET['rx']) ? (string) $_GET['rx'] : 'RX-1042';
$picked = $prescriptions[$pickedCode] ?? null;
$pickedDoctor = $picked['doctor'] ?? 'Dr. Sample Doctor 1';
$pickedCondition = $picked['condition'] ?? 'Prescription';
$pickedIssued = $picked['issued'] ?? '11 Jul 2026';

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--brand">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">Step 2 of 2</span>
    <h1 class="page-hero__title"><?= e($pickedCondition) ?></h1>
    <p class="page-hero__text">Untick what you already have, then send it to the counter.</p>
  </div>

</header>

<a class="book-back" href="/app/pharmacy-choose"><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="15" height="15">Choose a different prescription</a>

<div class="rx-head">
  <div class="rx-head__left">
    <span class="rx-head__tag">Rx</span>
    <div>
      <div class="rx-head__title inline-parts"><span><?= e($pickedCode) ?></span><span><?= e($pickedDoctor) ?></span></div>
      <div class="rx-head__sub inline-parts"><span>Issued <?= e($pickedIssued) ?></span><span>4 items</span></div>
    </div>
  </div>
</div>

<div class="rx-builder">
  <div class="rx-table" id="rx-table">
    <div class="rx-table__head">
      <span></span>
      <span>Medicine</span>
      <span>Dosage</span>
      <span>Unit price</span>
      <span>Prescribed</span>
      <span>Days to buy</span>
      <span>Stock</span>
      <span>Amount</span>
    </div>
    <div id="rx-rows">
      <div class="rx-table__row" data-rx-row data-per-day="180" data-days="3">
        <input class="rx-table__check" type="checkbox" data-rx-check checked>
        <div class="rx-table__med">Azithromycin 500 mg</div>
        <span class="rx-table__cell rx-table__cell--left">Once a day, after meals</span>
        <span class="rx-table__cell">Rs. 180</span>
        <span class="rx-table__cell">3 days</span>
        <div class="text-center">
          <div class="stepper">
            <button class="stepper__btn" type="button" data-step="-1">−</button>
            <span class="stepper__val" data-days-val>3 days</span>
            <button class="stepper__btn" type="button" data-step="1">+</button>
          </div>
        </div>
        <span class="rx-table__stock rx-table__stock--success">In stock</span>
        <span class="rx-table__amount" data-amount>Rs. 540</span>
      </div>
      <div class="rx-table__row" data-rx-row data-per-day="32" data-days="5">
        <input class="rx-table__check" type="checkbox" data-rx-check checked>
        <div class="rx-table__med">Paracetamol 500 mg</div>
        <span class="rx-table__cell rx-table__cell--left">2 every 6 hours, if fever</span>
        <span class="rx-table__cell">Rs. 8</span>
        <span class="rx-table__cell">5 days</span>
        <div class="text-center">
          <div class="stepper">
            <button class="stepper__btn" type="button" data-step="-1">−</button>
            <span class="stepper__val" data-days-val>5 days</span>
            <button class="stepper__btn" type="button" data-step="1">+</button>
          </div>
        </div>
        <span class="rx-table__stock rx-table__stock--success">In stock</span>
        <span class="rx-table__amount" data-amount>Rs. 160</span>
      </div>
      <div class="rx-table__row" data-rx-row data-per-day="15" data-days="5">
        <input class="rx-table__check" type="checkbox" data-rx-check checked>
        <div class="rx-table__med">Cetirizine 10 mg</div>
        <span class="rx-table__cell rx-table__cell--left">1 at night</span>
        <span class="rx-table__cell">Rs. 15</span>
        <span class="rx-table__cell">5 days</span>
        <div class="text-center">
          <div class="stepper">
            <button class="stepper__btn" type="button" data-step="-1">−</button>
            <span class="stepper__val" data-days-val>5 days</span>
            <button class="stepper__btn" type="button" data-step="1">+</button>
          </div>
        </div>
        <span class="rx-table__stock rx-table__stock--warning">Only 4 left</span>
        <span class="rx-table__amount" data-amount>Rs. 75</span>
      </div>
      <div class="rx-table__row is-off" data-rx-row data-per-day="12" data-days="7">
        <input class="rx-table__check" type="checkbox" data-rx-check disabled>
        <div class="rx-table__med">Vitamin C 100 mg</div>
        <span class="rx-table__cell rx-table__cell--left">Once a day (optional)</span>
        <span class="rx-table__cell">Rs. 12</span>
        <span class="rx-table__cell">7 days</span>
        <div class="text-center">
          <div class="stepper">
            <button class="stepper__btn" type="button" data-step="-1">−</button>
            <span class="stepper__val" data-days-val>7 days</span>
            <button class="stepper__btn" type="button" data-step="1">+</button>
          </div>
        </div>
        <span class="rx-table__stock rx-table__stock--danger">Out of stock</span>
        <span class="rx-table__amount" data-amount>Rs. 84</span>
      </div>
    </div>
  </div>

  <div class="order-summary" id="rx-summary">
    <div class="order-summary__title">Order summary</div>
    <div class="order-summary__row"><span data-summary-count>Selected</span><span class="mono" data-summary-total>Rs. 0</span></div>
    <div class="order-summary__excluded">Vitamin C is out of stock, so it is not included.</div>
    <div class="order-summary__row order-summary__total"><span class="text-default">Total</span><span class="mono" data-summary-grand>Rs. 0</span></div>
    <div class="order-summary__pay-label">Payment</div>
    <div class="pay-toggle">
      <div class="pay-toggle__opt" data-pay="online">Pay online now</div>
      <div class="pay-toggle__opt is-active" data-pay="collection">At collection</div>
    </div>
    <button class="btn btn--success btn--block" type="button">Send order</button>
    <div class="order-summary__note">You can cancel any time before it's ready.</div>
  </div>
</div>
<script src="/assets/js/patient/prescriptions.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>