<?php

declare(strict_types=1);

$title = 'Choose a prescription';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--brand">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">Step 1 of 2</span>
    <h1 class="page-hero__title">Choose a prescription</h1>
  </div>

</header>

<a class="book-back" href="/app/prescriptions"><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="15" height="15">Back to pharmacy</a>

<div class="rx-picker">
  <a class="rx-pick-card" href="/app/pharmacy-order?rx=RX-1042">
    <span class="rx-pick-card__top">
      <span class="rx-pick-card__code">RX-1042</span>
      <span class="badge badge--info">Pending pickup</span>
    </span>
    <span class="rx-pick-card__condition">Chest infection</span>
    <span class="rx-pick-card__doctor inline-parts"><span>Dr. Sample Doctor 1</span><span>11 Jul 2026</span></span>
    <span class="rx-pick-card__foot">
      <span class="rx-pick-card__items"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="14" height="14">4 medicines</span>
      <span class="rx-pick-card__use">Use this one <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
    </span>
  </a>
  <a class="rx-pick-card" href="/app/pharmacy-order?rx=RX-1035">
    <span class="rx-pick-card__top">
      <span class="rx-pick-card__code">RX-1035</span>
      <span class="badge badge--success">Fully dispensed</span>
    </span>
    <span class="rx-pick-card__condition">Hypertension review</span>
    <span class="rx-pick-card__doctor inline-parts"><span>Dr. Sample Doctor 1</span><span>02 Jul 2026</span></span>
    <span class="rx-pick-card__foot">
      <span class="rx-pick-card__items"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="14" height="14">2 medicines</span>
      <span class="rx-pick-card__use">Use this one <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
    </span>
  </a>
  <a class="rx-pick-card" href="/app/pharmacy-order?rx=RX-1021">
    <span class="rx-pick-card__top">
      <span class="rx-pick-card__code">RX-1021</span>
      <span class="badge badge--warning">Partially dispensed</span>
    </span>
    <span class="rx-pick-card__condition">Gastritis</span>
    <span class="rx-pick-card__doctor inline-parts"><span>Dr. Sample Doctor 2</span><span>20 Jun 2026</span></span>
    <span class="rx-pick-card__foot">
      <span class="rx-pick-card__items"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="14" height="14">3 medicines</span>
      <span class="rx-pick-card__use">Use this one <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
    </span>
  </a>
  <a class="rx-pick-card" href="/app/pharmacy-order?rx=RX-0998">
    <span class="rx-pick-card__top">
      <span class="rx-pick-card__code">RX-0998</span>
      <span class="badge badge--muted">Not dispensed</span>
    </span>
    <span class="rx-pick-card__condition">Seasonal allergy</span>
    <span class="rx-pick-card__doctor inline-parts"><span>Dr. Sample Doctor 1</span><span>01 Jun 2026</span></span>
    <span class="rx-pick-card__foot">
      <span class="rx-pick-card__items"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="14" height="14">1 medicine</span>
      <span class="rx-pick-card__use">Use this one <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
    </span>
  </a>
</div>

<?php require __DIR__ . '/footer.php'; ?>