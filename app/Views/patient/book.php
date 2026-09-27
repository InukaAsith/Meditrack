<?php

declare(strict_types=1);

$title = 'Book';
$active = 'book';

$firstDoctorId = array_key_first($doctors);

$dayDoctorLabels = [];
foreach ($sessions as $key => $byDoctor) {
  $available = 0;
  foreach ($byDoctor as $rows) {
    foreach ($rows as $s) {
      if ($s['booked'] < $s['capacity']) {
        $available++;
        break;
      }
    }
  }
  if ($available > 0) {
    $dayDoctorLabels[$key] = $available . ($available === 1 ? ' doctor' : ' doctors');
  }
}

$doctorDayLabels = [];
foreach ($doctors as $doctorId => $doctor) {
  $doctorDayLabels[$doctorId] = [];
}
foreach ($sessions as $key => $byDoctor) {
  foreach ($byDoctor as $doctorId => $rows) {
    $open = 0;
    foreach ($rows as $s) {
      if ($s['booked'] < $s['capacity']) {
        $open++;
      }
    }
    if ($open > 0) {
      $doctorDayLabels[$doctorId][$key] = $open . ' open';
    }
  }
}

$openDay = null;
if (isset($dayDoctorLabels[$linkedDay ?? ''])) {
  $openDay = $linkedDay;
}

require __DIR__ . '/header.php';
?>
<div class="row-between mb-8">
  <h1 class="rx-title">Book an appointment</h1>
  <div class="tabs tabs--pill">
    <button class="tabs__item<?= $openDay === null ? ' is-active' : '' ?>" type="button" data-book-tab="doctor">By doctor</button>
    <button class="tabs__item<?= $openDay !== null ? ' is-active' : '' ?>" type="button" data-book-tab="date">By date</button>
  </div>
</div>

<section data-book-panel="doctor" <?= $openDay !== null ? ' hidden' : '' ?>>
  <div class="book-picker">
    <span class="book-picker__label">Specialty</span>
    <div class="search-select" data-picker>
      <button class="search-select__trigger" type="button" data-picker-trigger aria-haspopup="listbox" aria-expanded="false">
        <span data-picker-chosen>Any specialty</span>
      </button>
      <div data-picker-menu hidden>
        <div class="search-select__menu">
          <div class="search-select__search">
            <input class="search-select__input" type="search" data-picker-search placeholder="Type to search, e.g. ear, fever, child" autocomplete="off" aria-label="Search specialties">
          </div>
          <div class="search-select__options" role="listbox">
            <div data-option-search="any specialty all every doctor">
              <button class="search-select__option is-active" type="button" role="option" data-filter="All">Any specialty</button>
            </div>
            <?php foreach ($specialties as $specialty): ?>
              <div data-option-search="<?= e(strtolower($specialty)) ?>">
                <button class="search-select__option" type="button" role="option" data-filter="<?= e($specialty) ?>"><?= e($specialty) ?></button>
              </div>
            <?php endforeach; ?>
            <p class="search-select__none" data-picker-none hidden>No specialty matches that.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <h2 class="book-doctors-title">Doctor</h2>
  <div class="search-box book-search">
    <img class="icon search-box__icon" src="/assets/img/icons/search.svg" alt="" width="16" height="16">
    <input class="search-box__input" type="search" id="doc-search" placeholder="Search doctor or specialty…" aria-label="Search doctors">
  </div>

  <div class="doc-grid" id="doc-grid">
    <?php
    $tones = ['brand', 'mint', 'violet'];
    $cardNumber = 0;
    foreach ($doctors as $doctorId => $doctor):
      $tone = $tones[$cardNumber++ % count($tones)];
    ?>
      <div data-doctor-id="<?= $doctorId ?>" data-search="<?= e(strtolower($doctor['name'] . ' ' . $doctor['specialty'])) ?>">
        <div class="doc-card doc-card--<?= $tone ?>">
          <div class="doc-card__banner">
            <span class="doc-card__portrait"><?= e($doctor['initials']) ?></span>
          </div>
          <div class="doc-card__body">
            <h3 class="doc-card__name"><?= e($doctor['name']) ?></h3>
            <span class="doc-card__spec-tag"><?= e($doctor['specialty']) ?></span>
            <button class="doc-card__btn" type="button" data-see-availability>
              See availability <img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="15" height="15">
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <p class="book-none" id="doc-none" hidden>No doctor matches that. Change the specialty or clear the search.</p>
</section>

<section data-book-panel="date" <?= $openDay === null ? ' hidden' : '' ?>>
  <div class="book-cal-grid">
    <div class="card">
      <div class="card__body">
        <?php
        $calSelected = $openDay;
        $calDots = null;
        $calDayLabels = $dayDoctorLabels;
        $calInteractive = true;
        require __DIR__ . '/../partials/month-calendar.php';
        ?>
        <div class="cal-legend2">
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--selected"></span>Selected</div>
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--available"></span>Doctors available</div>
        </div>
      </div>
    </div>

    <aside class="slot-panel" aria-live="polite">
      <div class="slot-panel__eyebrow">Available doctors</div>

      <div data-date-day="none" <?= $openDay !== null ? ' hidden' : '' ?>>
        <div class="slot-panel__day">Pick a date</div>
        <div class="slot-panel__hint">Choose a day on the calendar to see who is consulting.</div>
        <div class="slot-panel__empty">No date selected yet.</div>
      </div>

      <?php foreach ($sessions as $day => $byDoctor): ?>
        <div data-date-day="<?= e($day) ?>" <?= $day === $openDay ? '' : ' hidden' ?>>
          <div class="slot-panel__day"><?= e(date('D, d M', strtotime($day))) ?></div>
          <div class="slot-panel__hint">
            <?= count($byDoctor) === 1 ? '1 doctor available' : count($byDoctor) . ' doctors available' ?>. Pick one to see their times.
          </div>
          <?php foreach ($byDoctor as $doctorId => $rows):
            $doctor = $doctors[$doctorId];
            $open = 0;
            foreach ($rows as $s) {
              if ($s['booked'] < $s['capacity']) {
                $open++;
              }
            }
          ?>
            <?php if ($open === 0): ?>
              <button class="slot-doctor slot-doctor--full" type="button" disabled>
                <span class="slot-doctor__avatar"><?= e($doctor['initials']) ?></span>
                <span class="slot-doctor__text">
                  <span class="slot-doctor__name"><?= e($doctor['name']) ?></span>
                  <span class="slot-doctor__meta inline-parts"><span><?= e($doctor['specialty']) ?></span><span><?= e(money($doctor['fee'])) ?></span></span>
                  <span class="slot-doctor__open">Fully booked that day</span>
                </span>
              </button>
            <?php else: ?>
              <button class="slot-doctor" type="button" data-pick-doctor="<?= $doctorId ?>" data-pick-day="<?= e($day) ?>">
                <span class="slot-doctor__avatar"><?= e($doctor['initials']) ?></span>
                <span class="slot-doctor__text">
                  <span class="slot-doctor__name"><?= e($doctor['name']) ?></span>
                  <span class="slot-doctor__meta inline-parts"><span><?= e($doctor['specialty']) ?></span><span><?= e(money($doctor['fee'])) ?></span></span>
                  <span class="slot-doctor__open"></span>
                </span>
              </button>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </aside>
  </div>
</section>

<section data-book-panel="sessions" hidden>
  <button class="book-back" type="button" data-book-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="15" height="15">Back</button>

  <?php foreach ($doctors as $doctorId => $doctor): ?>
    <div data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>>
      <div class="book-doc-head">
        <span class="book-doc-head__avatar"><?= e($doctor['initials']) ?></span>
        <div>
          <div class="book-doc-head__name"><?= e($doctor['name']) ?></div>
          <div class="book-doc-head__meta inline-parts"><span><?= e($doctor['specialty']) ?></span><span><?= e(money($doctor['fee'])) ?></span></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="book-cal-grid">
    <div class="card">
      <div class="card__body">
        <?php foreach ($doctors as $doctorId => $doctor): ?>
          <div data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>>
            <?php
            $calSelected = null;
            $calDots = null;
            $calDayLabels = $doctorDayLabels[$doctorId];
            $calInteractive = true;
            require __DIR__ . '/../partials/month-calendar.php';
            ?>
          </div>
        <?php endforeach; ?>
        <div class="cal-legend2">
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--selected"></span>Selected</div>
          <div class="cal-legend2__item"><span class="cal-legend2__box cal-legend2__box--available"></span>Has open sessions</div>
        </div>
      </div>
    </div>

    <aside class="slot-panel" id="slot-panel" aria-live="polite">
      <div class="slot-panel__eyebrow">Sessions</div>

      <div data-sessions="none">
        <div class="slot-panel__day">Pick a date</div>
        <div class="slot-panel__empty">No date selected yet.</div>
      </div>

      <?php foreach ($sessions as $day => $byDoctor): ?>
        <?php foreach ($byDoctor as $doctorId => $rows): ?>
          <div data-sessions="<?= e($day . '|' . $doctorId) ?>" hidden>
            <div class="slot-panel__day"><?= e(date('D, d M', strtotime($day))) ?></div>
            <?php foreach ($rows as $s):
              $percentBooked = (int) round($s['booked'] / $s['capacity'] * 100);
            ?>
              <?php if ($s['booked'] >= $s['capacity']): ?>
                <div class="slot-session slot-session--full">
                  <div class="slot-session__head">
                    <div>
                      <div class="slot-session__name"><?= e($s['label']) ?></div>
                      <div class="slot-session__range"><?= e($s['range']) ?></div>
                    </div>
                    <div class="slot-session__count"><?= $s['booked'] ?>/<?= $s['capacity'] ?> booked</div>
                  </div>
                  <div class="slot-session__bar">
                    <div class="slot-session__fill" style="width: <?= $percentBooked ?>%"></div>
                  </div>
                  <div class="slot-session__full-tag">Fully booked</div>
                </div>
              <?php else:
                $yourNumber = $s['booked'] + 1;
                $eta = date('H:i', strtotime($s['start']) + $s['booked'] * $s['minutes'] * 60);
                $dayText = date('D, d M', strtotime($day));
              ?>
                <div class="slot-session">
                  <div class="slot-session__head">
                    <div>
                      <div class="slot-session__name"><?= e($s['label']) ?></div>
                      <div class="slot-session__range"><?= e($s['range']) ?></div>
                    </div>
                    <div class="slot-session__count"><?= $s['booked'] ?>/<?= $s['capacity'] ?> booked</div>
                  </div>
                  <div class="slot-session__bar">
                    <div class="slot-session__fill" style="width: <?= $percentBooked ?>%"></div>
                  </div>
                  <div class="slot-session__meta">
                    <span>Number <b>#<?= $yourNumber ?></b></span>
                    <span>Est. time <b>~<?= e($eta) ?></b> <span class="slot-session__ahead">(<?= $s['booked'] ?> ahead of you)</span></span>
                  </div>
                  <button class="btn btn--primary btn--sm btn--block" type="button" data-book-session
                    data-session="<?= e($dayText . ', ' . $s['label'] . ' (' . $s['range'] . ')') ?>"
                    data-step="<?= e('Session: ' . $dayText . ', ' . $s['label']) ?>"
                    data-number="<?= $yourNumber ?>"
                    data-eta="~<?= e($eta) ?>">Book this session</button>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </aside>
  </div>
</section>

<section data-book-panel="details" hidden>
  <div class="book-stepper">
    <div class="book-step"><span class="book-step__num is-done"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="12" height="12"></span>
      <?php foreach ($doctors as $doctorId => $doctor): ?>
        <span data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>><span class="book-step__label is-active">Doctor - <?= e($doctor['name']) ?></span></span>
      <?php endforeach; ?>
    </div>
    <span class="book-step__sep">-</span>
    <div class="book-step"><span class="book-step__num is-done"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="12" height="12"></span><span class="book-step__label is-active" id="step-datetime">Session</span></div>
    <span class="book-step__sep">-</span>
    <div class="book-step"><span class="book-step__num is-current">3</span><span class="book-step__label is-active">Patient details</span></div>
  </div>

  <div class="book-details-grid">
    <div class="card">
      <div class="card__body">
        <div class="row-between">
          <div class="who-title m-0">Who is this appointment for?</div>
          <div class="who-toggle">
            <div class="who-toggle__opt is-active" data-who="self">For myself</div>
            <div class="who-toggle__opt" data-who="else">Someone else</div>
          </div>
        </div>

        <div class="mt-6" data-who-form="self">
          <div class="autofill-note"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="14" height="14">Filled in from your profile</div>
          <div class="form-2col">
            <label class="field"><span class="field__label">Full name <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" value="K.A. Inuka Asith"></label>
            <label class="field"><span class="field__label">NIC number <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" value="200034501234"></label>
            <label class="field"><span class="field__label">Date of birth <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" value="12 / 08 / 2000"></label>
            <label class="field"><span class="field__label">Mobile number <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" value="+94 77 123 4567"></label>
            <label class="field"><span class="field__label">Email</span><input class="field__input" value="inuka.a@gmail.com"></label>
          </div>
        </div>

        <div class="mt-6" data-who-form="else" hidden>
          <div class="register-note"><img class="icon" src="/assets/img/icons/alert.svg" alt="" width="15" height="15">write the alert (im thinking yet).</div>
          <div class="form-2col">
            <label class="field"><span class="field__label">Full name of patient <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="As on NIC"></label>
            <label class="field"><span class="field__label">NIC number <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="NIC"></label>
            <label class="field"><span class="field__label">Date of birth <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="DD / MM / YYYY"></label>
            <label class="field"><span class="field__label">Relationship to you <span class="field__req" aria-hidden="true">*</span></span>
              <select class="field__input">
                <option>Father</option>
                <option>Mother</option>
                <option>Spouse</option>
                <option>Child</option>
                <option>Other</option>
              </select>
            </label>
            <label class="field"><span class="field__label">Mobile number <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" placeholder="+94 ..."></label>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="book-summary">
          <div class="book-summary__eyebrow">Booking summary</div>
          <?php foreach ($doctors as $doctorId => $doctor): ?>
            <div data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>>
              <div class="summary-doc">
                <span class="summary-doc__avatar"><?= e($doctor['initials']) ?></span>
                <div>
                  <div class="summary-doc__name"><?= e($doctor['name']) ?></div>
                  <div class="summary-doc__meta"><?= e($doctor['specialty']) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <hr class="book-summary__hr">
          <div class="book-summary__row"><span>Patient</span><span class="val"><span data-who-label="self">You</span><span data-who-label="else" hidden>Someone else</span></span></div>
          <div class="book-summary__row"><span>Session</span><span class="val" id="sum-datetime">-</span></div>
          <div class="book-summary__row"><span>Your number</span><span class="val" id="sum-number">-</span></div>
          <div class="book-summary__row"><span>Estimated time</span><span class="val" id="sum-eta">-</span></div>
          <?php foreach ($doctors as $doctorId => $doctor): ?>
            <div data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>>
              <div class="book-summary__row"><span>Consultation fee</span><span class="val"><?= e(money($doctor['fee'])) ?></span></div>
            </div>
            <div data-for-doctor="<?= $doctorId ?>" <?= $doctorId === $firstDoctorId ? '' : ' hidden' ?>>
              <div class="book-summary__total"><span>Total</span><span class="mono"><?= e(money($doctor['fee'])) ?></span></div>
            </div>
          <?php endforeach; ?>
          <hr class="book-summary__hr">
          <div class="book-summary__row"><span style="color:var(--text);font-weight:600">Payment</span>
            <div class="pay-toggle">
              <div class="pay-toggle__opt is-active" data-pay="online">Pay online</div>
              <div class="pay-toggle__opt" data-pay="counter">At counter</div>
            </div>
          </div>
          <div class="noshow-row"><span>No-show refund</span>
            <button class="toggle is-on" type="button" data-toggle aria-pressed="true"><span class="toggle__track"><span class="toggle__thumb"></span></span></button>
          </div>
          <a class="btn btn--primary btn--block" href="/app/appointments">Confirm booking</a>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/assets/js/components/month-calendar.js" defer></script>
<script src="/assets/js/patient/book.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>