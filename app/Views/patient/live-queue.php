<?php

declare(strict_types=1);

$title = 'Live queue';
$active = 'live-queue';

$queueLive = true;

if (!$queueLive) {
  require __DIR__ . '/header.php';
?>
  <header class="page-hero page-hero--indigo">
    <div class="page-hero__copy">
      <span class="page-hero__eyebrow">Live queue</span>
      <h1 class="page-hero__title">No live appointments right now</h1>
      <p class="page-hero__text">Your queue number will show here once your doctor starts seeing patients.</p>

      <div class="page-hero__actions">
        <a class="page-hero__btn page-hero__btn--solid" href="/app/appointments">See my appointments</a>
        <a class="page-hero__btn page-hero__btn--ghost" href="/app/book">Book a visit</a>
      </div>
    </div>

  </header>
  <div class="queue-none">
    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">What happens next</h2>
      </div>
      <div class="queue-advice">
        <div class="queue-advice__item">
          <span class="queue-advice__icon queue-advice__icon--brand"><img class="icon" src="/assets/img/icons/calendar.svg" alt="" width="17" height="17"></span>
          <div class="queue-advice__text">
            <strong>Dr. Sample Doctor 1, 24 Jul at 10:30</strong>
            <span>The queue opens at 10:00.</span>
          </div>
        </div>
        <div class="queue-advice__item">
          <span class="queue-advice__icon queue-advice__icon--mint"><img class="icon" src="/assets/img/icons/clock.svg" alt="" width="17" height="17"></span>
          <div class="queue-advice__text">
            <strong>Be at the clinic by 09:55</strong>
          </div>
        </div>
        <div class="queue-advice__item">
          <span class="queue-advice__icon queue-advice__icon--sun"><img class="icon" src="/assets/img/icons/bell.svg" alt="" width="17" height="17"></span>
          <div class="queue-advice__text">
            <strong>We will text you when the queue starts</strong>
          </div>
        </div>
      </div>
      <div class="queue-advice__actions">
        <a class="page-hero__btn page-hero__btn--solid" href="/app/appointments">Open the appointment</a>
      </div>
    </section>
  </div>
<?php
  require __DIR__ . '/footer.php';
  return;
}

$aheadOfMe = 3;
$queueTotal = 8;

$progress = $aheadOfMe / $queueTotal;
$ringCircumference = 2 * M_PI * 46;
$ringOffset = $ringCircumference * (1 - $progress);

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--mint">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">Dr. Sample Doctor 1</span>
    <h1 class="page-hero__title">You are number 4 in the queue</h1>

    <div class="page-hero__facts">
      <div class="page-hero__fact">
        <strong>3</strong>
        <span>people ahead</span>
      </div>
      <div class="page-hero__fact">
        <strong>N. J•••••</strong>
        <span>being seen now</span>
      </div>
      <div class="page-hero__fact">
        <strong>09:55</strong>
        <span>Arrive by</span>
      </div>
    </div>

    <div class="page-hero__extra">
      <div class="queue-clock">
        <div class="queue-ring">
          <svg viewBox="0 0 108 108" aria-hidden="true">
            <circle class="queue-ring__track" cx="54" cy="54" r="46" fill="none" stroke-width="9"></circle>
            <circle class="queue-ring__bar" cx="54" cy="54" r="46" fill="none" stroke-width="9" stroke-linecap="round" stroke-dasharray="<?= $ringCircumference ?>" stroke-dashoffset="<?= $ringOffset ?>"></circle>
          </svg>
          <span class="queue-ring__num">#4</span>
        </div>
        <div class="queue-clock__text">
          <span class="queue-clock__label">Expected Wait</span>
          <span class="queue-clock__time">10:10</span>
        </div>
      </div>
    </div>
  </div>

</header>
<div class="section-lead">
  <h2 class="section-lead__title">Where you are right now</h2>
</div>

<div class="queue-stages">
  <div class="queue-stage queue-stage--done">
    <span class="queue-stage__dot"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
    <span class="queue-stage__label">Checked in</span>
  </div>
  <div class="queue-stage queue-stage--current">
    <span class="queue-stage__dot"></span>
    <span class="queue-stage__label">Vitals</span>
  </div>
  <div class="queue-stage queue-stage--pending">
    <span class="queue-stage__dot"></span>
    <span class="queue-stage__label">You're next</span>
  </div>
  <div class="queue-stage queue-stage--pending">
    <span class="queue-stage__dot"></span>
    <span class="queue-stage__label">With the doctor</span>
  </div>
</div>

<div class="queue-grid">
  <div class="stack">
    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">What to do now</h2>
      </div>
      <div class="queue-advice">
        <div class="queue-advice__item">
          <span class="queue-advice__icon queue-advice__icon--mint"><img class="icon" src="/assets/img/icons/clock.svg" alt="" width="17" height="17"></span>
          <div class="queue-advice__text">
            <strong>Be at the clinic by 09:55</strong>
          </div>
        </div>
        <div class="queue-advice__item">
          <span class="queue-advice__icon queue-advice__icon--brand"><img class="icon" src="/assets/img/icons/bell.svg" alt="" width="17" height="17"></span>
          <div class="queue-advice__text">
            <strong>We will text you when you are next</strong>
          </div>
        </div>
      </div>
      <div class="queue-advice__actions">
        <button class="page-hero__btn page-hero__btn--solid" type="button">Get directions</button>
      </div>
    </section>

    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">Messages from the clinic</h2>
        <span class="clinic-box__count">today</span>
      </div>
      <ul class="notice-board__list">
        <li class="notice-board__post">
          <span class="notice-board__avatar">D1</span>
          <div class="notice-board__body">
            <p><strong>Dr. Sample Doctor 1 </strong>is running about 5 minutes late.</p>
            <span class="notice-board__time">09:02</span>
          </div>
        </li>
        <li class="notice-board__post">
          <span class="notice-board__avatar">HG</span>
          <div class="notice-board__body">
            <p><strong>Clinic </strong>pharmacy counter moves to Level 1 from Monday.</p>
            <span class="notice-board__time">08:30</span>
          </div>
        </li>
      </ul>
    </section>
  </div>

  <section class="clinic-box">
    <div class="clinic-box__head">
      <h2 class="clinic-box__title">Everyone in line</h2>
      <span class="live-badge"><span class="live-badge__dot"></span>LIVE</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">1</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">N. J•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('in_consultation')) ?>"><?= e(status_label('in_consultation')) ?></span>
      <span class="queue-row__eta">now</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">2</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">K. P•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('ready')) ?>"><?= e(status_label('ready')) ?></span>
      <span class="queue-row__eta">~09:50</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">3</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">R. D•••••</span>
        <span class="queue-row__note">vitals done</span>
      </div>
      <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
      <span class="queue-row__eta">~10:00</span>
    </div>
    <div class="queue-row queue-row--me">
      <span class="queue-row__position">4</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">You</span>
      </div>
      <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
      <span class="queue-row__eta">~10:10</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">5</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">S. B•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
      <span class="queue-row__eta">~10:22</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">6</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">M. F•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
      <span class="queue-row__eta">~10:34</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">7</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">T. W•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
      <span class="queue-row__eta">~10:46</span>
    </div>
    <div class="queue-row">
      <span class="queue-row__position">8</span>
      <div class="queue-row__identity">
        <span class="queue-row__name">D. R•••••</span>
      </div>
      <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
      <span class="queue-row__eta">~10:58</span>
    </div>
  </section>
</div>
<?php require __DIR__ . '/footer.php'; ?>