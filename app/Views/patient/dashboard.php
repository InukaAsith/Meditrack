<?php

declare(strict_types=1);

$title = 'Home';
$active = 'home';

$queueLive = true;

require __DIR__ . '/header.php';
?>
<div class="clinic-hello">
  <div class="clinic-hello__copy">
    <span class="clinic-hello__eyebrow"><?= e(date('l, j F')) ?></span>
    <h1 class="clinic-hello__title"><?= e(greeting()) ?>, <?= e(first_name($patientName)) ?>.</h1>
    <p class="clinic-hello__sub">You have one appointment today.</p>
  </div>
  <div class="clinic-hello__actions">
    <a class="page-hero__btn page-hero__btn--ghost" href="/app/profile">
      <img class="icon" src="/assets/img/icons/shield.svg" alt="" width="15" height="15">Patient card
    </a>
    <a class="page-hero__btn page-hero__btn--ghost" href="/app/book">
      <img class="icon" src="/assets/img/icons/plus.svg" alt="" width="15" height="15">Book a visit
    </a>
  </div>
</div>

<div class="today-row">
  <section class="today-appt">
    <div class="today-appt__body">
      <div class="today-appt__eyebrowrow">
        <span class="today-appt__eyebrow">Your appointment today</span>
        <span class="today-appt__pill">in 45 min</span>
      </div>
      <div class="today-appt__time">10:30</div>
      <div class="today-appt__doctor">Dr. Sample Doctor 1</div>
      <div class="today-appt__meta inline-parts"><span>Follow-up review</span><span>General Physician</span></div>
      <div class="today-appt__arrival">
        <img class="icon" src="/assets/img/icons/clock.svg" alt="" width="15" height="15">
        <span>Please arrive by <b>09:55</b>.</span>
      </div>
      <div class="today-appt__actions">
        <?php if ($queueLive): ?>
          <a class="today-appt__btn today-appt__btn--solid" href="/app/live-queue">Follow the live queue</a>
          <a class="today-appt__btn today-appt__btn--ghost" href="/app/appointments">Appointment details</a>
        <?php else: ?>
          <a class="today-appt__btn today-appt__btn--solid" href="/app/appointments">Appointment details</a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <aside class="today-queue">
    <?php if ($queueLive): ?>
      <div class="today-queue__head">
        <span class="today-queue__eyebrow">Queue running now</span>
        <span class="today-queue__live"><span class="today-queue__livedot"></span>Live</span>
      </div>
      <div class="today-queue__number">#1</div>
      <div class="today-queue__caption">now with Dr. Sample Doctor 1</div>
      <dl class="today-queue__facts">
        <div class="today-queue__fact">
          <dt>Your number</dt>
          <dd>#4 <span>3 ahead of you</span></dd>
        </div>
        <div class="today-queue__fact">
          <dt>Your estimated time</dt>
          <dd>~10:10</dd>
        </div>
      </dl>
      <a class="today-queue__link" href="/app/live-queue">Open the live queue <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></a>
    <?php else: ?>
      <div class="today-queue__head">
        <span class="today-queue__eyebrow">Live queue</span>
        <span class="today-queue__live today-queue__live--idle">Not started</span>
      </div>
      <div class="today-queue__idle">
        <span class="today-queue__idleicon"><img class="icon" src="/assets/img/icons/clock.svg" alt="" width="20" height="20"></span>
        <p class="today-queue__idletitle">No queue is running yet</p>
        <p class="today-queue__idletext">Dr. Sample Doctor 1's session starts at 10:00. We'll text you when it does.</p>
      </div>
      <a class="today-queue__link" href="/app/appointments">See your appointment <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="14" height="14"></a>
    <?php endif; ?>
  </aside>
</div>

<div class="clinic-shortcuts">
  <a class="clinic-shortcut clinic-shortcut--brand" href="/app/book">
    <span class="clinic-shortcut__icon"><img class="icon" src="/assets/img/icons/calendarPlus.svg" alt="" width="20" height="20"></span>
    <span class="clinic-shortcut__label">Book a visit</span>
    <span class="clinic-shortcut__hint">Pick a doctor and a time</span>
  </a>

  <a class="clinic-shortcut clinic-shortcut--mint" href="/app/live-queue">
    <span class="clinic-shortcut__icon"><img class="icon" src="/assets/img/icons/queue.svg" alt="" width="20" height="20"></span>
    <span class="clinic-shortcut__label">Live queue</span>
    <span class="clinic-shortcut__hint"><?= e($queueLive ? 'Running now' : 'Nothing running right now') ?></span>
  </a>

  <a class="clinic-shortcut clinic-shortcut--sun" href="/app/prescriptions">
    <span class="clinic-shortcut__icon"><img class="icon" src="/assets/img/icons/pill.svg" alt="" width="20" height="20"></span>
    <span class="clinic-shortcut__label">Pharmacy</span>
    <span class="clinic-shortcut__hint">Prescriptions and refills</span>
  </a>

  <a class="clinic-shortcut clinic-shortcut--slate" href="/app/records">
    <span class="clinic-shortcut__icon"><img class="icon" src="/assets/img/icons/records.svg" alt="" width="20" height="20"></span>
    <span class="clinic-shortcut__label">My records</span>
    <span class="clinic-shortcut__hint">Visits, tests and reports</span>
  </a>
</div>

<div class="clinic-bento">
  <div class="clinic-bento__col">
    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">Your calendar</h2>
        <a class="clinic-box__link" href="/app/appointments">All appointments</a>
      </div>
      <?php
      $calYear = 2026;
      $calMonth = 7;
      $calSelected = '2026-07-24';
      $calDots = [
        '2026-07-24' => ['appointment'],
        '2026-07-26' => ['followup'],
        '2026-07-28' => ['refill'],
        '2026-08-02' => ['followup'],
      ];
      $calDayLabels = null;
      $calInteractive = true;
      require __DIR__ . '/../partials/month-calendar.php';
      ?>
      <div class="cal-legend">
        <div class="cal-legend__item">
          <span class="cal-legend__dot cal-legend__dot--appointment"></span>
          <span>Appointment</span>
        </div>
        <div class="cal-legend__item">
          <span class="cal-legend__dot cal-legend__dot--followup"></span>
          <span>Follow-up</span>
        </div>
        <div class="cal-legend__item">
          <span class="cal-legend__dot cal-legend__dot--contact"></span>
          <span>Contact doctor</span>
        </div>
        <div class="cal-legend__item">
          <span class="cal-legend__dot cal-legend__dot--refill"></span>
          <span>Refill due</span>
        </div>
      </div>
    </section>

    <div data-day-appts="2026-07-24">
      <section class="clinic-box">
        <div class="clinic-box__head">
          <h2 class="clinic-box__title">On Fri, 24 Jul</h2>
        </div>
        <div class="day-appts">
          <div class="day-appt">
            <span class="day-appt__time">10:30</span>
            <span class="day-appt__dot day-appt__dot--appointment"></span>
            <div class="day-appt__body">
              <div class="day-appt__title">Follow-up with Dr. Sample Doctor 1</div>
              <div class="day-appt__sub">Confirmed</div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div data-day-appts="2026-07-26" hidden>
      <section class="clinic-box">
        <div class="clinic-box__head">
          <h2 class="clinic-box__title">On Sun, 26 Jul</h2>
        </div>
        <div class="day-appts">
          <div class="day-appt">
            <span class="day-appt__time">All day</span>
            <span class="day-appt__dot day-appt__dot--followup"></span>
            <div class="day-appt__body">
          </div>
        </div>
      </section>
    </div>

    <div data-day-appts="2026-07-28" hidden>
      <section class="clinic-box">
        <div class="clinic-box__head">
          <h2 class="clinic-box__title">On Tue, 28 Jul</h2>
        </div>
        <div class="day-appts">
          <div class="day-appt">
            <span class="day-appt__time">All day</span>
            <span class="day-appt__dot day-appt__dot--refill"></span>
            <div class="day-appt__body">
              <div class="day-appt__title">Losartan 50 mg refill due</div>
              <div class="day-appt__sub">Order a refill before you run out</div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div data-day-appts="2026-08-02" hidden>
      <section class="clinic-box">
        <div class="clinic-box__head">
          <h2 class="clinic-box__title">On Sun, 02 Aug</h2>
        </div>
        <div class="day-appts">
          <div class="day-appt">
            <span class="day-appt__time">10:00</span>
            <span class="day-appt__dot day-appt__dot--followup"></span>
            <div class="day-appt__body">
              <div class="day-appt__title">BP recheck with Dr. Sample Doctor 1</div>
              <div class="day-appt__sub">Booked</div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">Doctors you see</h2>
        <a class="clinic-box__link" href="/app/book">Book with one</a>
      </div>
      <div class="reg-docs">
        <a class="reg-doc" href="/app/book">
          <span class="reg-doc__avatar reg-doc__avatar--blue">D1</span>
          <span class="reg-doc__name">Dr. Sample Doctor 1</span>
        </a>
        <a class="reg-doc" href="/app/book">
          <span class="reg-doc__avatar reg-doc__avatar--amber">D2</span>
          <span class="reg-doc__name">Dr. Sample Doctor 2</span>
        </a>
      </div>
    </section>
  </div>

  <div class="clinic-bento__col">
    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">Medicines today</h2>
      </div>
      <div class="med-list">
        <button type="button" class="med-row" data-med="m1" aria-pressed="true">
          <span class="med-row__check is-done"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
          <span class="med-cap med-cap--amber"><span></span><span></span></span>
          <span class="med-row__text">
            <span class="med-row__name">Losartan 50 mg</span>
            <span class="med-row__sub">Every morning for blood pressure</span>
          </span>
        </button>
        <button type="button" class="med-row" data-med="m2" aria-pressed="false">
          <span class="med-row__check"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
          <span class="med-cap med-cap--blue"><span></span><span></span></span>
          <span class="med-row__text">
            <span class="med-row__name">Azithromycin 500 mg</span>
            <span class="med-row__sub inline-parts"><span>After lunch</span><span>Day 2 of 3</span></span>
          </span>
        </button>
        <button type="button" class="med-row" data-med="m3" aria-pressed="false">
          <span class="med-row__check"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
          <span class="med-cap med-cap--violet"><span></span><span></span></span>
          <span class="med-row__text">
            <span class="med-row__name">Cetirizine 10 mg</span>
            <span class="med-row__sub inline-parts"><span>At night</span><span>Day 2 of 5</span></span>
          </span>
        </button>
        <button type="button" class="med-row" data-med="m4" aria-pressed="false">
          <span class="med-row__check"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
          <span class="med-cap med-cap--teal"><span></span><span></span></span>
          <span class="med-row__text">
            <span class="med-row__name">Atorvastatin 10 mg</span>
            <span class="med-row__sub">Every night for cholesterol</span>
          </span>
        </button>
      </div>
      <p class="clinic-box__foot">Tap a medicine once you have taken it.</p>
    </section>

    <section class="clinic-box">
      <div class="clinic-box__head">
        <h2 class="clinic-box__title">Coming up this week</h2>
      </div>
      <div class="week-feed">
        <div class="week-feed-item">
          <div class="week-feed-item__icon week-feed-item__icon--info"><img class="icon" src="/assets/img/icons/clock.svg" alt="" width="15" height="15"></div>
          <div class="week-feed-item__body">
            <div class="week-feed-item__eyebrow inline-parts"><span>FRI 24 JUL</span><span>10:30</span></div>
            <div class="week-feed-item__title">Follow-up with Dr. Sample Doctor 1</div>
            <div class="week-feed-item__sub">Confirmed</div>
          </div>
          <div class="week-feed-item__action">
            <button type="button" class="week-feed-btn week-feed-btn--primary">View appointment</button>
          </div>
        </div>
        <div class="week-feed-item">
          <div class="week-feed-item__icon week-feed-item__icon--info"><img class="icon" src="/assets/img/icons/calendar.svg" alt="" width="15" height="15"></div>
          <div class="week-feed-item__body">
            <div class="week-feed-item__eyebrow inline-parts"><span>WED 29 JUL</span><span>08:30</span></div>
            <div class="week-feed-item__title">Appointment with Dr. Sample Doctor 1</div>
            <div class="week-feed-item__sub">Blood pressure review</div>
          </div>
          <div class="week-feed-item__action">
            <button type="button" class="week-feed-btn week-feed-btn--primary">View appointment</button>
          </div>
        </div>
        <div class="week-feed-item">
          <div class="week-feed-item__icon week-feed-item__icon--success"><img class="icon" src="/assets/img/icons/refresh.svg" alt="" width="15" height="15"></div>
          <div class="week-feed-item__body">
            <div class="week-feed-item__eyebrow">02 AUG</div>
            <div class="week-feed-item__title">Blood pressure check</div>
            <div class="week-feed-item__sub">Your Losartan runs out soon</div>
          </div>
          <div class="week-feed-item__action">
            <button type="button" class="week-feed-btn week-feed-btn--success">Book review</button>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>
<script src="/assets/js/patient/meds-today.js" defer></script>
<script src="/assets/js/patient/home-calendar.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>