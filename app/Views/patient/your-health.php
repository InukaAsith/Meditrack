<?php

declare(strict_types=1);

$title = 'Your health';
$active = 'your-health';

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--rose">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">Your health</span>
    <h1 class="page-hero__title">How you have been doing</h1>

    <div class="page-hero__facts">
      <div class="page-hero__fact">
        <strong>1 of 4</strong>
        <span>medicines taken today</span>
      </div>
      <div class="page-hero__fact">
        <strong>2</strong>
        <span>courses running</span>
      </div>
      <div class="page-hero__fact">
        <strong>B+</strong>
        <span>blood type</span>
      </div>
    </div>

    <div class="page-hero__extra">
      <div class="hero-vitals">
        <span class="hero-vitals__label">Last checked 26 Jun</span>
        <div class="hero-vitals__row">
          <div class="hero-vital">
            <span class="hero-vital__name"><span class="vital-tile__dot vital-tile__dot--amber"></span>BP</span>
            <span class="hero-vital__value">128/84<small>mmHg</small></span>
          </div>
          <div class="hero-vital">
            <span class="hero-vital__name"><span class="vital-tile__dot vital-tile__dot--green"></span>Pulse</span>
            <span class="hero-vital__value">82<small>bpm</small></span>
          </div>
          <div class="hero-vital">
            <span class="hero-vital__name"><span class="vital-tile__dot vital-tile__dot--amber"></span>Temp</span>
            <span class="hero-vital__value">37.9<small>°C</small></span>
          </div>
          <div class="hero-vital">
            <span class="hero-vital__name"><span class="vital-tile__dot vital-tile__dot--green"></span>SpO₂</span>
            <span class="hero-vital__value">98<small>%</small></span>
          </div>
        </div>
      </div>
    </div>
  </div>

</header>
<div class="stack">
  <div class="home-grid">
    <div class="stack">
      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Today's medicines</h3>
        </div>
        <div class="card__body">
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
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="subhead mt-0">Taken every day</div>
          <div>
            <div class="recur-row">
              <span class="recur-row__icon"><img class="icon" src="/assets/img/icons/refresh.svg" alt="" width="16" height="16"></span>
              <div class="recur-row__text">
                <div class="recur-row__name">Losartan 50 mg</div>
                <div class="recur-row__detail inline-parts"><span>Every morning</span><span>Prescribed by Dr. Sample Doctor 1</span></div>
              </div>
              <span class="recur-row__tag">BP</span>
            </div>
            <div class="recur-row">
              <span class="recur-row__icon"><img class="icon" src="/assets/img/icons/refresh.svg" alt="" width="16" height="16"></span>
              <div class="recur-row__text">
                <div class="recur-row__name">Atorvastatin 10 mg</div>
                <div class="recur-row__detail inline-parts"><span>Every night</span><span>Prescribed by Dr. Sample Doctor 1</span></div>
              </div>
              <span class="recur-row__tag">Cholesterol</span>
            </div>
          </div>
          <div class="subhead">Short courses</div>
          <div>
            <div class="course">
              <div class="course__top">
                <div>
                  <span class="course__name">Azithromycin 500 mg</span>
                  <span class="course__note">Once a day</span>
                </div>
                <span class="course__day">Day 2 of 3</span>
              </div>
              <div class="course__bar">
                <div class="course__fill" style="width:67%"></div>
              </div>
            </div>
            <div class="course">
              <div class="course__top">
                <div>
                  <span class="course__name">Cetirizine 10 mg</span>
                  <span class="course__note">One at night</span>
                </div>
                <span class="course__day">Day 2 of 5</span>
              </div>
              <div class="course__bar">
                <div class="course__fill" style="width:40%"></div>
              </div>
            </div>
          </div>
          <a class="btn btn--secondary btn--block mt-6" href="/app/prescriptions">Order a refill</a>
        </div>
      </div>
    </div>

    <div class="stack">
      <div class="card">
        <div class="card__head">
          <h3 class="card__title">This week &amp; upcoming</h3>
        </div>
        <div class="card__body">
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
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/patient/meds-today.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>