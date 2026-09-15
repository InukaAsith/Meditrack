<?php

declare(strict_types=1);

$title = 'Doctor schedule';
$active = 'doctor-schedule';

require __DIR__ . '/header.php';
?>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Doctor schedule</h1>
    <div class="staff-head__sub">Read-only view of each doctor's day, week and month - booking is handled by reception.</div>
  </div>
  <div class="staff-head__actions">
    <span class="badge badge--muted"><?= icon('eye', 13) ?> View only</span>
  </div>
</div>

<?php
$selActive = 'AS';
$selDoctors = [
  ['id' => 'AS', 'name' => 'Dr. Sample Doctor 1', 'specialty' => 'General', 'tone' => 'blue'],
  ['id' => 'RF', 'name' => 'Dr. Sample Doctor 3', 'specialty' => 'Pediatrics', 'tone' => 'teal'],
  ['id' => 'MP', 'name' => 'Dr. Sample Doctor 2', 'specialty' => 'ENT', 'tone' => 'amber'],
];
?>
<div class="doc-selector" data-doc-selector>
  <span class="doc-selector__label">Serving:</span>
  <div class="doc-selector__pills" data-doc-pills>
    <?php foreach ($selDoctors as $doc): ?>
      <span data-doc-pill-wrap>
        <button type="button"
          class="doc-pill<?= $doc['id'] === $selActive ? ' is-active' : '' ?>"
          data-doc-pill="<?= e($doc['id']) ?>"
          data-doc-name="<?= e($doc['name']) ?>"
          data-doc-specialty="<?= e($doc['specialty']) ?>">
          <span class="doc-pill__avatar avatar--<?= e($doc['tone']) ?>"><?= e($doc['id']) ?></span>
          <span class="doc-pill__name"><?= e($doc['name']) ?></span>
        </button>
      </span>
    <?php endforeach; ?>
    <span data-doc-pill-wrap>
      <button type="button"
        class="doc-pill doc-pill--all<?= $selActive === 'all' ? ' is-active' : '' ?>"
        data-doc-pill="all" data-doc-name="All doctors" data-doc-specialty="All doctors">
        <span class="doc-pill__grid">⊞</span>
        <span class="doc-pill__name">All doctors</span>
      </button>
    </span>
  </div>
  <div class="doc-selector__search search-box">
    <?= icon('search', 15, 'search-box__icon') ?>
    <input class="search-box__input" type="search" data-doc-search
      placeholder="Search doctor…" aria-label="Search doctors">
  </div>
</div>

<div class="cal-controls">
  <div class="cal-viewtabs" data-view-tabs>
    <button class="cal-viewtabs__item is-active" type="button" data-view="day">Day</button>
    <button class="cal-viewtabs__item" type="button" data-view="week">Week</button>
    <button class="cal-viewtabs__item" type="button" data-view="month">Month</button>
  </div>
  <div data-when="day week">
    <div class="cal-datenav">
      <button type="button" aria-label="Previous">‹</button>
      <span data-date-label>Wednesday 22 Jul 2026</span>
      <button type="button" aria-label="Next">›</button>
    </div>
  </div>
  <span class="cal-spacer"></span>
</div>

<section data-view-panel="day">
  <div data-day-mode="all" hidden>
    <div class="day-cols">
      <div class="day-col" title="Dr. Sample Doctor 3's day">
        <div class="day-col__head">
          <span class="day-col__avatar avatar--teal">RF</span>
          <div>
            <div class="day-col__name">Dr. Sample Doctor 3</div>
            <div class="day-col__meta">Pediatrics · 12/16 booked</div>
          </div>
        </div>
        <div class="day-col__slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
            <span class="day-slot__time">09:00</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">K.A. Inuka Asith · New</span>
            <span class="day-slot__time">09:30</span>
          </div>
          <div class="day-slot day-slot--emergency">
            <span class="day-slot__label">Cancelled</span>
            <span class="day-slot__time">10:00</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
            <span class="day-slot__time">10:30</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">11:00</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">11:30</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__label">Blocked - lunch</span>
            <span class="day-slot__time">12:00</span>
          </div>
        </div>
      </div>
      <div class="day-col" title="Dr. Sample Doctor 2's day">
        <div class="day-col__head">
          <span class="day-col__avatar avatar--amber">MP</span>
          <div>
            <div class="day-col__name">Dr. Sample Doctor 2</div>
            <div class="day-col__meta">ENT · 10/16 booked</div>
          </div>
        </div>
        <div class="day-col__slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">Sandanu Dulmeth · FU</span>
            <span class="day-slot__time">09:00</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
            <span class="day-slot__time">09:30</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">Sandanu Dulmeth · New</span>
            <span class="day-slot__time">10:00</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">10:30</span>
          </div>
          <div class="day-slot day-slot--blocked-doctor">
            <span class="day-slot__label">Blocked by doctor</span>
            <span class="day-slot__time">11:00</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">11:30</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__label">Blocked - lunch</span>
            <span class="day-slot__time">12:00</span>
          </div>
        </div>
      </div>
      <div class="day-col" title="Dr. Sample Doctor 1's day">
        <div class="day-col__head">
          <span class="day-col__avatar avatar--blue">AS</span>
          <div>
            <div class="day-col__name">Dr. Sample Doctor 1</div>
            <div class="day-col__meta">General · 24/28 booked</div>
          </div>
        </div>
        <div class="day-col__slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">K.A. Inuka Asith · FU</span>
            <span class="day-slot__time">09:00</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
            <span class="day-slot__time">09:30</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__label">Sandanu D. · New</span>
            <span class="day-slot__time">10:00</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">10:30</span>
          </div>
          <div class="day-slot day-slot--walkin">
            <span class="day-slot__label">W-07 · walk-in token</span>
            <span class="day-slot__time">11:00</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__label">Available</span>
            <span class="day-slot__time">11:30</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__label">Blocked - lunch</span>
            <span class="day-slot__time">12:00</span>
          </div>
        </div>
      </div>
    </div>
    <div class="day-legend">
      <span><i class="day-legend__box day-legend__box--booked"></i>Booked</span>
      <span><i class="day-legend__box day-legend__box--available"></i>Available</span>
      <span><i class="day-legend__box day-legend__box--walkin"></i>Walk-in token</span>
      <span><i class="day-legend__box day-legend__box--blocked"></i>Blocked</span>
      <span class="day-legend__hint">Read-only - pick a doctor above to see just their day.</span>
    </div>
  </div>

  <div data-day-mode="one" data-day-doc="RF" hidden>
    <div class="card">
      <div class="card__body">
        <div class="appt-single__head">
          <span class="appt-single__title">Dr. Sample Doctor 3 - Wed 22 Jul · 12/16 booked</span>
          <span class="appt-single__hint">read-only · schedule changes are made by reception</span>
        </div>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">K.A. Inuka Asith · New</span>
          </div>
          <div class="day-slot day-slot--emergency">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Cancelled</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div data-day-mode="one" data-day-doc="MP" hidden>
    <div class="card">
      <div class="card__body">
        <div class="appt-single__head">
          <span class="appt-single__title">Dr. Sample Doctor 2 - Wed 22 Jul · 10/16 booked</span>
          <span class="appt-single__hint">read-only · schedule changes are made by reception</span>
        </div>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">Sandanu Dulmeth · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Sandanu Dulmeth · New</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked-doctor">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">Blocked by doctor</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div data-day-mode="one" data-day-doc="AS">
    <div class="card">
      <div class="card__body">
        <div class="appt-single__head">
          <span class="appt-single__title">Dr. Sample Doctor 1 - Wed 22 Jul · 24/28 booked</span>
          <span class="appt-single__hint">read-only · schedule changes are made by reception</span>
        </div>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">K.A. Inuka Asith · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Sandanu D. · New</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--walkin">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">W-07 · walk-in token</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section data-view-panel="week" hidden>
  <div class="week-grid">
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">MON</span>
        <span class="week-day__date">20 Jul</span>
      </div>
      <div class="week-day__count">52 <span>booked</span></div>
      <div class="week-day__open">28% capacity</div>
      <div class="week-day__bar"><span style="width:28%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 34 patients</span>
        <span class="week-day__pill">PM 18 patients</span>
      </div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">TUE</span>
        <span class="week-day__date">21 Jul</span>
      </div>
      <div class="week-day__count">38 <span>booked</span></div>
      <div class="week-day__open">41% capacity</div>
      <div class="week-day__bar"><span style="width:41%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 22 patients</span>
        <span class="week-day__pill">PM 16 patients</span>
      </div>
    </div>
    <div class="week-day is-today">
      <div class="week-day__head">
        <span class="week-day__dow">WED</span>
        <span class="week-day__date">22 Jul</span>
      </div>
      <div class="week-day__count">24 <span>booked</span></div>
      <div class="week-day__open">55% capacity</div>
      <div class="week-day__bar"><span style="width:55%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 15 patients</span>
        <span class="week-day__pill">PM 9 patients</span>
      </div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">THU</span>
        <span class="week-day__date">23 Jul</span>
      </div>
      <div class="week-day__count">52 <span>booked</span></div>
      <div class="week-day__open">27% capacity</div>
      <div class="week-day__bar"><span style="width:27%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 33 patients</span>
        <span class="week-day__pill">PM 19 patients</span>
      </div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">FRI</span>
        <span class="week-day__date">24 Jul</span>
      </div>
      <div class="week-day__count">52 <span>booked</span></div>
      <div class="week-day__open">26% capacity</div>
      <div class="week-day__bar"><span style="width:26%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 30 patients</span>
        <span class="week-day__pill">PM 22 patients</span>
      </div>
    </div>
    <div class="week-day is-closed">
      <div class="week-day__head">
        <span class="week-day__dow">SAT</span>
        <span class="week-day__date">25 Jul</span>
      </div>
      <div class="week-day__closed">Clinic closed</div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">SUN</span>
        <span class="week-day__date">26 Jul</span>
      </div>
      <div class="week-day__count">24 <span>booked</span></div>
      <div class="week-day__open">60% capacity</div>
      <div class="week-day__bar"><span style="width:60%"></span></div>
      <div class="week-day__sessions">
        <span class="week-day__pill">AM 18 patients</span>
        <span class="week-day__pill">PM 6 patients</span>
      </div>
    </div>
  </div>
  <div class="cal-hint">Each day shows total booked and capacity used · click a day to see its doctor sessions below.</div>

  <div class="card week-queue">
    <div class="card__body">
      <div class="appt-single__head">
        <span class="appt-single__title"><span data-week-title>Wed 22 Jul</span> - doctor sessions</span>
        <label class="doctor-select doctor-select--sm">
          <?= icon('profile', 13) ?>
          <select data-week-doc aria-label="Session doctor">
            <option value="RF">Dr. Sample Doctor 3</option>
            <option value="MP">Dr. Sample Doctor 2</option>
            <option value="AS" selected>Dr. Sample Doctor 1</option>
          </select>
        </label>
      </div>
      <div data-week-slots="RF" hidden>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">K.A. Inuka Asith · New</span>
          </div>
          <div class="day-slot day-slot--emergency">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Cancelled</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Nimsith Wickrama · FU</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
      <div data-week-slots="MP" hidden>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">Sandanu Dulmeth · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Sandanu Dulmeth · New</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked-doctor">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">Blocked by doctor</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
      <div data-week-slots="AS">
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label">K.A. Inuka Asith · FU</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label">Sandanu D. · New</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--walkin">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">W-07 · walk-in token</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Blocked - lunch</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section data-view-panel="month" hidden>
  <div class="month-view">
    <div class="card">
      <div class="card__body">
        <div class="manager-calendar__head">
          <div class="manager-calendar__nav"><button type="button">‹</button><span>July 2026</span><button type="button">›</button></div>
          <span class="cal-hint m-0">click a day to see its detail →</span>
        </div>
        <div class="manager-calendar__weekdays">
          <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span>
        </div>
        <div class="manager-calendar__grid">
          <div class="manager-calendar__cell manager-calendar__cell--empty"></div>
          <div class="manager-calendar__cell manager-calendar__cell--empty"></div>
          <div class="manager-calendar__cell" data-mday="1"><span class="manager-calendar__daynum">1</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="2"><span class="manager-calendar__daynum">2</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="3"><span class="manager-calendar__daynum">3</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">4</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="5"><span class="manager-calendar__daynum">5</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="6"><span class="manager-calendar__daynum">6</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="7"><span class="manager-calendar__daynum">7</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="8"><span class="manager-calendar__daynum">8</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="9"><span class="manager-calendar__daynum">9</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--today" data-mday="10"><span class="manager-calendar__daynum">10</span><span class="manager-calendar__daynote">today · 46 appts</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">11</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="12"><span class="manager-calendar__daynum">12</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="13"><span class="manager-calendar__daynum">13</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="14"><span class="manager-calendar__daynum">14</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--leave" data-mday="15"><span class="manager-calendar__daynum">15</span><span class="manager-calendar__daynote">Sample Doctor 1 on leave</span></div>
          <div class="manager-calendar__cell" data-mday="16"><span class="manager-calendar__daynum">16</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="17"><span class="manager-calendar__daynum">17</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">18</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="19"><span class="manager-calendar__daynum">19</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="20"><span class="manager-calendar__daynum">20</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="21"><span class="manager-calendar__daynum">21</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="22"><span class="manager-calendar__daynum">22</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="23"><span class="manager-calendar__daynum">23</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="24"><span class="manager-calendar__daynum">24</span><span class="manager-calendar__daynote">52 booked ✓</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">25</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="26"><span class="manager-calendar__daynum">26</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="27"><span class="manager-calendar__daynum">27</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="28"><span class="manager-calendar__daynum">28</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="29"><span class="manager-calendar__daynum">29</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="30"><span class="manager-calendar__daynum">30</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="31"><span class="manager-calendar__daynum">31</span><span class="manager-calendar__daynote">24 booked</span></div>
        </div>
        <div class="manager-calendar__legend">
          <span><i style="background:var(--primary)"></i>selected / today</span>
          <span><i style="background:var(--warning-tint);border:1px solid var(--warning)"></i>doctor on leave</span>
          <span><i style="background:var(--surface);border:1px solid var(--border)"></i>booked count per day</span>
        </div>
      </div>
    </div>

    <aside class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span data-mday-title>Wed 22 Jul - 24 booked</span>
          <span class="badge badge--muted">read-only</span>
        </div>
        <div class="month-day-eyebrow">Doctors on this day - select one</div>
        <div class="month-day-docs" data-mday-docs>
          <button class="month-day-doc is-active" type="button" data-doc="AS" data-name="Dr. Sample Doctor 1">
            <span class="month-day-doc__avatar avatar--blue">AS</span>
            <span class="month-day-doc__body">
              <span class="month-day-doc__name">Dr. Sample Doctor 1</span>
              <span class="month-day-doc__meta">General · 09:00–13:00</span>
              <span class="month-day-doc__bar"><span style="width:79%"></span></span>
            </span>
            <span class="month-day-doc__count">22/28</span>
          </button>
          <button class="month-day-doc" type="button" data-doc="RF" data-name="Dr. Sample Doctor 3">
            <span class="month-day-doc__avatar avatar--teal">RF</span>
            <span class="month-day-doc__body">
              <span class="month-day-doc__name">Dr. Sample Doctor 3</span>
              <span class="month-day-doc__meta">Pediatrics · 09:00–12:00</span>
              <span class="month-day-doc__bar"><span style="width:75%"></span></span>
            </span>
            <span class="month-day-doc__count">12/16</span>
          </button>
          <button class="month-day-doc" type="button" data-doc="MP" data-name="Dr. Sample Doctor 2">
            <span class="month-day-doc__avatar avatar--amber">MP</span>
            <span class="month-day-doc__body">
              <span class="month-day-doc__name">Dr. Sample Doctor 2</span>
              <span class="month-day-doc__meta">ENT · 09:00–13:00</span>
              <span class="month-day-doc__bar"><span style="width:63%"></span></span>
            </span>
            <span class="month-day-doc__count">10/16</span>
          </button>
        </div>

        <div class="month-day-eyebrow staff-eyebrow--row mt-7">
          <span data-mday-doc-label>Dr. Sample Doctor 1 - sessions</span>
        </div>
        <div data-month-slots="RF" hidden>
          <div class="month-day-slots">
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:00</span>
              <span class="day-slot__label">Nimsith Wickrama · FU</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:30</span>
              <span class="day-slot__label">K.A. Inuka Asith · New</span>
            </div>
            <div class="day-slot day-slot--emergency">
              <span class="day-slot__time">10:00</span>
              <span class="day-slot__label">Cancelled</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">10:30</span>
              <span class="day-slot__label">Nimsith Wickrama · FU</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">11:00</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">11:30</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--blocked">
              <span class="day-slot__time">12:00</span>
              <span class="day-slot__label">Blocked - lunch</span>
            </div>
          </div>
        </div>
        <div data-month-slots="MP" hidden>
          <div class="month-day-slots">
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:00</span>
              <span class="day-slot__label">Sandanu Dulmeth · FU</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:30</span>
              <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">10:00</span>
              <span class="day-slot__label">Sandanu Dulmeth · New</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">10:30</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--blocked-doctor">
              <span class="day-slot__time">11:00</span>
              <span class="day-slot__label">Blocked by doctor</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">11:30</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--blocked">
              <span class="day-slot__time">12:00</span>
              <span class="day-slot__label">Blocked - lunch</span>
            </div>
          </div>
        </div>
        <div data-month-slots="AS">
          <div class="month-day-slots">
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:00</span>
              <span class="day-slot__label">K.A. Inuka Asith · FU</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">09:30</span>
              <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
            </div>
            <div class="day-slot day-slot--booked">
              <span class="day-slot__time">10:00</span>
              <span class="day-slot__label">Sandanu D. · New</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">10:30</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--walkin">
              <span class="day-slot__time">11:00</span>
              <span class="day-slot__label">W-07 · walk-in token</span>
            </div>
            <div class="day-slot day-slot--available">
              <span class="day-slot__time">11:30</span>
              <span class="day-slot__label">Available</span>
            </div>
            <div class="day-slot day-slot--blocked">
              <span class="day-slot__time">12:00</span>
              <span class="day-slot__label">Blocked - lunch</span>
            </div>
          </div>
        </div>
      </div>
    </aside>
  </div>
</section>

<script src="/assets/js/supporting/doctor-selector.js" defer></script>
<script src="/assets/js/supporting/doctor-schedule.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>