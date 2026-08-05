<?php

declare(strict_types=1);

$title = 'Pharmacy';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--sun">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">HealthGate pharmacy</span>
    <h1 class="page-hero__title">Get your medicine without the queue</h1>
    <p class="page-hero__text">Order ahead and pick it up at the counter.</p>

    <div class="page-hero__facts">
      <div class="page-hero__fact">
        <strong>1</strong>
        <span>ready to collect</span>
      </div>
      <div class="page-hero__fact">
        <strong>2</strong>
        <span>not ordered yet</span>
      </div>
      <div class="page-hero__fact">
        <strong>4</strong>
        <span>prescriptions on file</span>
      </div>
    </div>
  </div>

</header>

<div class="ready-banner">
  <span class="ready-banner__icon"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="24" height="24"></span>
  <div class="ready-banner__body">
    <div class="ready-banner__title">Your medicine is packed and waiting</div>
    <div class="ready-banner__text">
      2 items, Rs. 1,680. Pay at counter
    </div>
  </div>
  <div class="ready-banner__actions">
    <button class="ready-banner__btn" type="button">Show collection QR</button>
    <button class="ready-banner__btn ready-banner__btn--ghost" type="button">See what's inside</button>
  </div>
</div>

<div class="section-lead">
  <h2 class="section-lead__title">What would you like to do?</h2>
</div>

<div class="choice-cards">
  <a class="choice-card choice-card--brand" href="/app/pharmacy-choose">
    <span class="choice-card__icon"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="22" height="22"></span>
    <span class="choice-card__title">Order from Digital prescription</span>
    <span class="choice-card__text">Choose one of the prescriptions on your account.</span>
    <span class="choice-card__go">Choose a prescription <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
  </a>
  <a class="choice-card choice-card--mint" href="/app/pharmacy-otc">
    <span class="choice-card__icon"><img class="icon" src="/assets/img/icons/search.svg" alt="" width="22" height="22"></span>
    <span class="choice-card__title">Buy without a prescription</span>
    <span class="choice-card__text">Only over-the-counter medicines.</span>
    <span class="choice-card__go">Search medicine <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
  </a>
  <a class="choice-card choice-card--violet" href="/app/pharmacy-photo">
    <span class="choice-card__icon"><img class="icon" src="/assets/img/icons/upload.svg" alt="" width="22" height="22"></span>
    <span class="choice-card__title">Send a photo of a prescription</span>
    <span class="choice-card__text">Upload a photo of your paper prescription.</span>
    <span class="choice-card__go">Upload a photo <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></span>
  </a>
</div>
<?php require __DIR__ . '/footer.php'; ?>