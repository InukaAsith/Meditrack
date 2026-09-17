<?php

declare(strict_types=1);

$title = 'Doctor status';
$active = 'doctor-status';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Doctor arrival &amp; status</h1>
  </div>
</div>

<div class="doctor-status-card" data-docstat>
  <div class="doctor-status-card__top">
    <span class="doctor-status-card__avatar avatar--blue">D1</span>
    <div>
      <div class="doctor-status-card__name">Dr. Sample Doctor 1</div>
      <div class="doctor-status-card__meta inline-parts"><span>General</span><span>session 09:00–13:00</span></div>
    </div>
  </div>

  <div class="status-segment" data-statseg>
    <button class="status-segment__opt" type="button" data-state="not_arrived">Not arrived</button>
    <button class="status-segment__opt is-active" type="button" data-state="arrived">Arrived</button>
    <button class="status-segment__opt" type="button" data-state="running_late">Running late</button>
    <button class="status-segment__opt" type="button" data-state="on_leave">On leave</button>
  </div>

  <div class="doctor-status-card__row">
    <label class="doctor-status-card__delay">Delay <input type="number" value="5" min="0" step="5"> min</label>
    <input class="doctor-status-card__announce" type="text" value="Dr. Sample Doctor 1 is running about 5 minutes late. Thank you for waiting." placeholder="Add announcement for patients…" aria-label="Announcement">
    <button class="btn btn--primary btn--sm" type="button">Push update</button>
  </div>
</div>
<div class="doctor-status-card" data-docstat>
  <div class="doctor-status-card__top">
    <span class="doctor-status-card__avatar avatar--teal">D3</span>
    <div>
      <div class="doctor-status-card__name">Dr. Sample Doctor 3</div>
      <div class="doctor-status-card__meta inline-parts"><span>Pediatrics</span><span>session 09:00–12:00</span></div>
    </div>
  </div>

  <div class="status-segment" data-statseg>
    <button class="status-segment__opt" type="button" data-state="not_arrived">Not arrived</button>
    <button class="status-segment__opt is-active" type="button" data-state="arrived">Arrived</button>
    <button class="status-segment__opt" type="button" data-state="running_late">Running late</button>
    <button class="status-segment__opt" type="button" data-state="on_leave">On leave</button>
  </div>

  <div class="doctor-status-card__row">
    <label class="doctor-status-card__delay">Delay <input type="number" value="0" min="0" step="5"> min</label>
    <input class="doctor-status-card__announce" type="text" value="" placeholder="Add announcement for patients…" aria-label="Announcement">
    <button class="btn btn--primary btn--sm" type="button">Push update</button>
  </div>
</div>
<div class="doctor-status-card" data-docstat>
  <div class="doctor-status-card__top">
    <span class="doctor-status-card__avatar avatar--amber">D2</span>
    <div>
      <div class="doctor-status-card__name">Dr. Sample Doctor 2</div>
      <div class="doctor-status-card__meta inline-parts"><span>ENT</span><span>session 09:00–13:00</span></div>
    </div>
  </div>

  <div class="status-segment" data-statseg>
    <button class="status-segment__opt" type="button" data-state="not_arrived">Not arrived</button>
    <button class="status-segment__opt" type="button" data-state="arrived">Arrived</button>
    <button class="status-segment__opt is-active" type="button" data-state="running_late">Running late</button>
    <button class="status-segment__opt" type="button" data-state="on_leave">On leave</button>
  </div>

  <div class="doctor-status-card__row">
    <label class="doctor-status-card__delay">Delay <input type="number" value="12" min="0" step="5"> min</label>
    <input class="doctor-status-card__announce" type="text" value="Dr. Sample Doctor 2 arrived at 09:12." placeholder="Add announcement for patients…" aria-label="Announcement">
    <button class="btn btn--primary btn--sm" type="button">Push update</button>
  </div>
</div>

<script src="/assets/js/receptionist/doctor-status.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>