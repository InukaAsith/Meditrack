<?php

declare(strict_types=1);

$title = 'Buy without a prescription';
$active = 'prescriptions';

$otcMedicines = [
  ['medicine_id' => 1, 'name' => 'Paracetamol 500 mg', 'form' => 'Tablet', 'price' => 8],
  ['medicine_id' => 2, 'name' => 'Vitamin C 500 mg', 'form' => 'Tablet', 'price' => 12],
  ['medicine_id' => 3, 'name' => 'Cetirizine 10 mg', 'form' => 'Tablet', 'price' => 15],
  ['medicine_id' => 4, 'name' => 'ORS sachet', 'form' => 'Sachet', 'price' => 25],
  ['medicine_id' => 5, 'name' => 'Antiseptic cream', 'form' => 'Tube', 'price' => 180],
];

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--mint">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">Over-the-Counter</span>
    <h1 class="page-hero__title">Buy everyday medicines</h1>
    <p class="page-hero__text">Add what you need and we'll have it ready to collect.</p>
  </div>

</header>

<a class="book-back" href="/app/prescriptions"><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="15" height="15">Back to pharmacy</a>

<div class="rx-builder">
  <div>
    <div class="otc-search">
      <div class="search-box">
        <img class="icon search-box__icon" src="/assets/img/icons/search.svg" alt="" width="16" height="16">
        <input class="search-box__input" type="search" id="otc-search-input" placeholder="Search for a medicine" autocomplete="off" aria-label="Search medicine to add">
      </div>
      <div class="otc-results" id="otc-results" hidden>
        <?php foreach ($otcMedicines as $m): ?>
          <div data-otc-result="<?= $m['medicine_id'] ?>" data-name="<?= e(strtolower($m['name'])) ?>" hidden>
            <button class="otc-result" type="button">
              <span class="otc-result__name"><?= e($m['name']) ?></span>
              <span class="otc-result__meta inline-parts"><span><?= e($m['form']) ?></span><span><?= e(money($m['price'])) ?></span></span>
              <span class="otc-result__add">＋ Add</span>
            </button>
          </div>
        <?php endforeach; ?>
        <div id="otc-no-match" hidden>
          <div class="otc-results__none">No medicine matches “<span id="otc-no-match-text"></span>”.</div>
        </div>
      </div>
    </div>

    <div class="otc-quick">
      <span class="otc-quick__label">Commonly bought:</span>
      <span class="otc-quick__chip">Paracetamol 500 mg</span>
      <span class="otc-quick__chip">Vitamin C 500 mg</span>
      <span class="otc-quick__chip">Cetirizine 10 mg</span>
      <span class="otc-quick__chip">ORS sachet</span>
      <span class="otc-quick__chip">Antiseptic cream</span>
    </div>

    <div class="otc-table" id="otc-table" hidden>
      <div class="otc-table__head">
        <span>Medicine</span>
        <span>Form</span>
        <span>Unit price</span>
        <span>Quantity</span>
        <span>Amount</span>
        <span></span>
      </div>
      <?php foreach ($otcMedicines as $m): ?>
        <div data-otc-row="<?= $m['medicine_id'] ?>" data-price="<?= $m['price'] ?>" hidden>
          <div class="otc-table__row">
            <span class="otc-table__name"><?= e($m['name']) ?></span>
            <span class="otc-table__cell"><?= e($m['form']) ?></span>
            <span class="otc-table__cell otc-table__price"><?= e(money($m['price'])) ?></span>
            <div class="text-center">
              <div class="stepper">
                <button class="stepper__btn" type="button" data-step="-1">−</button>
                <span class="stepper__val" data-otc-qty>0</span>
                <button class="stepper__btn" type="button" data-step="1">+</button>
              </div>
            </div>
            <span class="otc-table__amount" data-otc-amount>Rs. 0</span>
            <button class="otc-table__remove" type="button" title="Remove" data-otc-remove>✕</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="otc-empty" id="otc-empty">
      <span class="otc-empty__icon"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="26" height="26"></span>
      <div class="otc-empty__title">Nothing in your basket yet</div>
      <div class="otc-empty__sub">Search for a medicine to add it here.</div>
    </div>
  </div>

  <div class="order-summary" id="otc-summary">
    <div class="order-summary__title">Your basket</div>
    <div class="order-summary__row"><span data-otc-count>0 items</span><span class="mono" data-otc-total>Rs. 0</span></div>
    <div class="order-summary__row order-summary__total"><span class="text-default">Total to pay</span><span class="mono" data-otc-grand>Rs. 0</span></div>
    <div class="order-summary__pay-label">Payment</div>
    <div class="pay-toggle">
      <div class="pay-toggle__opt is-active" data-otcpay="online">Pay online now</div>
      <div class="pay-toggle__opt" data-otcpay="collection">At collection</div>
    </div>
    <button class="btn btn--success btn--block" type="button" id="otc-buy" disabled>Place order</button>
  </div>
</div>
<script src="/assets/js/patient/prescriptions.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>