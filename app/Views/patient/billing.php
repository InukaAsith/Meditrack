<?php

declare(strict_types=1);

$title = 'Bills and payments';
$active = 'billing';

require __DIR__ . '/header.php';
?>
<h1 class="home-greeting">Bills and payments</h1>

<div class="due-banner">
  <span class="due-banner__icon"><img class="icon" src="/assets/img/icons/dollar.svg" alt="" width="24" height="24"></span>
  <div class="due-banner__body">
    <div class="due-banner__title">Rs. 2,500 to pay for APT001</div>
  </div>
  <div class="due-banner__actions">
    <button class="due-banner__btn" type="button">Pay now</button>
    <button class="due-banner__btn due-banner__btn--ghost" type="button">Pay at the counter</button>
  </div>
</div>

<div class="section-lead">
  <h2 class="section-lead__title">Your receipts</h2>
</div>

<div class="receipt-cards">
  <article class="receipt-card">
    <div class="receipt-card__top">
      <span class="receipt-card__code">INV-0227</span>
      <span class="badge badge--<?= e(status_tone('refund_complete')) ?>">Refunded</span>
    </div>
    <h3 class="receipt-card__item">Consultation with Dr. Sample Doctor 2</h3>
    <p class="receipt-card__when">Paid online on 02 Jun 2026</p>
    <div class="receipt-card__foot">
      <span class="receipt-card__amount">Rs. 2,000</span>
      <button class="receipt-card__download" type="button">
        <img class="icon" src="/assets/img/icons/download.svg" alt="" width="15" height="15">Download
      </button>
    </div>
  </article>
  <article class="receipt-card">
    <div class="receipt-card__top">
      <span class="receipt-card__code">INV-0102</span>
      <span class="badge badge--<?= e(status_tone('paid')) ?>">Paid</span>
    </div>
    <h3 class="receipt-card__item">Visit and medicines with Dr. Sample Doctor 1</h3>
    <p class="receipt-card__when">Paid in cash on 12 May 2026</p>
    <div class="receipt-card__foot">
      <span class="receipt-card__amount">Rs. 4,180</span>
      <button class="receipt-card__download" type="button">
        <img class="icon" src="/assets/img/icons/download.svg" alt="" width="15" height="15">Download
      </button>
    </div>
  </article>
  <article class="receipt-card">
    <div class="receipt-card__top">
      <span class="receipt-card__code">INV-0034</span>
      <span class="badge badge--<?= e(status_tone('paid')) ?>">Paid</span>
    </div>
    <h3 class="receipt-card__item">Consultation with Dr. Sample Doctor 2</h3>
    <p class="receipt-card__when">Paid by card on 03 Feb 2026</p>
    <div class="receipt-card__foot">
      <span class="receipt-card__amount">Rs. 2,000</span>
      <button class="receipt-card__download" type="button">
        <img class="icon" src="/assets/img/icons/download.svg" alt="" width="15" height="15">Download
      </button>
    </div>
  </article>
  <article class="receipt-card">
    <div class="receipt-card__top">
      <span class="receipt-card__code">INV-0912</span>
      <span class="badge badge--<?= e(status_tone('paid')) ?>">Paid</span>
    </div>
    <h3 class="receipt-card__item">Consultation with Dr. Sample Doctor 3</h3>
    <p class="receipt-card__when">Paid in cash on 18 Nov 2025</p>
    <div class="receipt-card__foot">
      <span class="receipt-card__amount">Rs. 3,000</span>
      <button class="receipt-card__download" type="button">
        <img class="icon" src="/assets/img/icons/download.svg" alt="" width="15" height="15">Download
      </button>
    </div>
  </article>
</div>
<?php require __DIR__ . '/footer.php'; ?>