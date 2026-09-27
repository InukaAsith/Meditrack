<?php

declare(strict_types=1);

$title = 'New booking';
$active = 'appointments';
$extraCss = ['patient'];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">New booking</h1>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/receptionist/appointments"><?= icon('chevronLeft', 15) ?>Back to calendar</a>
  </div>
</div>

<section data-book-panel="doctor">
  <div class="book-toolbar">
    <div class="search-box">
      <?= icon('search', 16, 'search-box__icon') ?>
      <input class="search-box__input" type="search" id="doc-search" placeholder="Search doctor or specialty…" aria-label="Search doctors">
    </div>
    <div class="book-filters">
      <button class="book-filters__pill is-active" type="button" data-filter="All">All</button>
      <button class="book-filters__pill" type="button" data-filter="General">General</button>
      <button class="book-filters__pill" type="button" data-filter="Pediatrics">Pediatrics</button>
      <button class="book-filters__pill" type="button" data-filter="ENT">ENT</button>
    </div>
  </div>

  <div class="doc-grid" id="doc-grid">
    <div data-doctor-id="AS" data-search="dr. a. silva general" data-specialty="General">
      <div class="doc-card">
        <div class="doc-card__top">
          <span class="doc-card__avatar">D1</span>
          <div>
            <div class="doc-card__name">Dr. Sample Doctor 1</div>
            <div class="doc-card__spec inline-parts"><span>General</span><span>Rs. 2,500</span></div>
          </div>
        </div>
        <div class="doc-card__meta">
          <span class="doc-card__slots">4 slots left</span>
          <span class="doc-card__next">next: 10:30</span>
        </div>
        <button class="doc-card__btn" type="button" data-see-availability>See availability →</button>
      </div>
    </div>
    <div data-doctor-id="RF" data-search="dr. r. fernando pediatrics" data-specialty="Pediatrics">
      <div class="doc-card">
        <div class="doc-card__top">
          <span class="doc-card__avatar">D3</span>
          <div>
            <div class="doc-card__name">Dr. Sample Doctor 3</div>
            <div class="doc-card__spec inline-parts"><span>Pediatrics</span><span>Rs. 3,000</span></div>
          </div>
        </div>
        <div class="doc-card__meta">
          <span class="doc-card__slots">4 slots left</span>
          <span class="doc-card__next">next: 11:00</span>
        </div>
        <button class="doc-card__btn" type="button" data-see-availability>See availability →</button>
      </div>
    </div>
    <div data-doctor-id="MP" data-search="dr. m. perera ent" data-specialty="ENT">
      <div class="doc-card">
        <div class="doc-card__top">
          <span class="doc-card__avatar">D2</span>
          <div>
            <div class="doc-card__name">Dr. Sample Doctor 2</div>
            <div class="doc-card__spec inline-parts"><span>ENT</span><span>Rs. 2,000</span></div>
          </div>
        </div>
        <div class="doc-card__meta">
          <span class="doc-card__slots">6 slots left</span>
          <span class="doc-card__next">next: 10:30</span>
        </div>
        <button class="doc-card__btn" type="button" data-see-availability>See availability →</button>
      </div>
    </div>
  </div>
</section>

<section data-book-panel="date" hidden>
  <button class="book-back" type="button" data-book-back="doctor"><?= icon('chevronLeft', 15) ?>Back</button>
  <div data-for-doctor="AS">
    <div class="book-doc-head">
      <span class="book-doc-head__avatar">D1</span>
      <div>
        <div class="book-doc-head__name">Dr. Sample Doctor 1</div>
        <div class="book-doc-head__meta inline-parts"><span>General</span><span>HealthGate Medical</span><span>Rs. 2,500</span></div>
      </div>
    </div>
  </div>
  <div data-for-doctor="RF" hidden>
    <div class="book-doc-head">
      <span class="book-doc-head__avatar">D3</span>
      <div>
        <div class="book-doc-head__name">Dr. Sample Doctor 3</div>
        <div class="book-doc-head__meta inline-parts"><span>Pediatrics</span><span>HealthGate Medical</span><span>Rs. 3,000</span></div>
      </div>
    </div>
  </div>
  <div data-for-doctor="MP" hidden>
    <div class="book-doc-head">
      <span class="book-doc-head__avatar">D2</span>
      <div>
        <div class="book-doc-head__name">Dr. Sample Doctor 2</div>
        <div class="book-doc-head__meta inline-parts"><span>ENT</span><span>HealthGate Medical</span><span>Rs. 2,000</span></div>
      </div>
    </div>
  </div>

  <div class="book-cal-grid">
    <div class="card">
      <div class="card__body">
        <?php
        $calYear = 2026;
        $calMonth = 7;
        $calMonths = 3;
        $calSelected = null;
        $calDots = null;
        $calInteractive = true;
        $calDayLabels = [
          '2026-07-21' => '6 open',
          '2026-07-22' => '9 open',
          '2026-07-23' => '4 open',
          '2026-07-24' => '3 open',
          '2026-07-26' => '7 open',
          '2026-07-28' => '8 open',
          '2026-07-29' => '5 open',
          '2026-07-30' => '2 open',
          '2026-07-31' => '6 open',
          '2026-08-03' => '8 open',
          '2026-08-04' => '6 open',
          '2026-08-05' => '9 open',
          '2026-08-06' => '4 open',
          '2026-08-07' => '7 open',
          '2026-08-11' => '5 open',
          '2026-08-12' => '9 open',
          '2026-08-13' => '3 open',
          '2026-08-18' => '8 open',
          '2026-09-01' => '9 open',
          '2026-09-02' => '7 open',
          '2026-09-03' => '6 open',
          '2026-09-08' => '8 open',
          '2026-09-09' => '5 open',
          '2026-09-15' => '9 open',
        ];
        require __DIR__ . '/../partials/month-calendar.php';
        ?>
        <div class="cal-legend2">
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--selected"></span>Selected</div>
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--available"></span>Has open sessions</div>
        </div>
      </div>
    </div>

    <aside class="slot-panel" id="slot-panel" aria-live="polite">
      <div class="slot-panel__eyebrow">Sessions</div>
      <div class="slot-panel__day" id="slot-day">Pick a date</div>
      <div id="session-list" hidden>
        <div class="reschedule-slots mt-6">
          <button class="reschedule-slot" type="button" data-slot="09:30">09:30</button>
          <button class="reschedule-slot" type="button" data-slot="10:00">10:00</button>
          <button class="reschedule-slot" type="button" data-slot="10:30">10:30</button>
          <button class="reschedule-slot" type="button" data-slot="11:00">11:00</button>
          <button class="reschedule-slot" type="button" data-slot="11:30">11:30</button>
          <button class="reschedule-slot" type="button" data-slot="12:00">12:00</button>
        </div>
      </div>
    </aside>
  </div>
</section>

<section data-book-panel="details" hidden>
  <button class="book-back" type="button" data-book-back="date"><?= icon('chevronLeft', 15) ?>Back</button>
  <div class="book-details-grid">
    <div class="card">
      <div class="card__body">
        <div class="who-title m-0">Who is this booking for?</div>
        <div class="who-toggle mt-5">
          <div class="who-toggle__opt is-active" data-who="existing">Existing patient</div>
          <div class="who-toggle__opt" data-who="new">New / walk-in</div>
        </div>

        <div class="mt-6" data-who-form="existing">
          <div class="search-box">
            <?= icon('search', 16, 'search-box__icon') ?>
            <input class="search-box__input" type="search" id="bk-pt-search" placeholder="Search name, Patient ID or NIC…" value="K.A. Inuka Asith" aria-label="Find patient">
          </div>
          <div class="autofill-note mt-5"><?= icon('check', 14) ?>K.A. Inuka Asith (PT-0967), queue SMS goes to +94 77 651 2340</div>
        </div>

        <div class="mt-6" data-who-form="new" hidden>
          <div class="form-2col">
            <label class="field"><span class="field__label">Full name of patient <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="As given"></label>
            <label class="field"><span class="field__label">Mobile (for queue SMS) <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="+94 ..."></label>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="book-summary">
          <div class="book-summary__eyebrow">Booking summary</div>
          <div data-for-doctor="AS">
            <div class="summary-doc">
              <span class="summary-doc__avatar">D1</span>
              <div>
                <div class="summary-doc__name">Dr. Sample Doctor 1</div>
                <div class="summary-doc__meta inline-parts"><span>General</span><span>HealthGate Medical</span></div>
              </div>
            </div>
          </div>
          <div data-for-doctor="RF" hidden>
            <div class="summary-doc">
              <span class="summary-doc__avatar">D3</span>
              <div>
                <div class="summary-doc__name">Dr. Sample Doctor 3</div>
                <div class="summary-doc__meta inline-parts"><span>Pediatrics</span><span>HealthGate Medical</span></div>
              </div>
            </div>
          </div>
          <div data-for-doctor="MP" hidden>
            <div class="summary-doc">
              <span class="summary-doc__avatar">D2</span>
              <div>
                <div class="summary-doc__name">Dr. Sample Doctor 2</div>
                <div class="summary-doc__meta inline-parts"><span>ENT</span><span>HealthGate Medical</span></div>
              </div>
            </div>
          </div>
          <hr class="book-summary__hr">
          <div class="book-summary__row"><span>Patient</span><span class="val"><span data-who-label="existing">K.A. Inuka Asith</span><span data-who-label="new" hidden>New patient</span></span></div>
          <div class="book-summary__row"><span>Session</span><span class="val" id="sum-datetime">-</span></div>
          <div data-for-doctor="AS">
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
          </div>
          <div data-for-doctor="RF" hidden>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 3,000</span></div>
          </div>
          <div data-for-doctor="MP" hidden>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,000</span></div>
          </div>
          <hr class="book-summary__hr">
          <div class="book-summary__row"><span style="color:var(--text);font-weight:600">Payment</span>
            <div class="pay-toggle">
              <div class="pay-toggle__opt is-active" data-pay="counter">At counter</div>
              <div class="pay-toggle__opt" data-pay="online">Online link</div>
            </div>
          </div>
          <button class="btn btn--primary btn--block mt-6" type="button" id="book-confirm">Confirm booking</button>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/assets/js/components/month-calendar.js" defer></script>
<script src="/assets/js/receptionist/book.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>