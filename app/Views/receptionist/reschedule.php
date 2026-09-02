<?php

declare(strict_types=1);

$title = 'Reschedule';
$active = 'appointments';
$extraCss = ['patient'];

$doctors = [
  'AS' => ['code' => 'AS', 'initials' => 'D1', 'name' => 'Dr. Sample Doctor 1', 'specialty' => 'General', 'session' => '09:00–13:00', 'tone' => 'blue', 'fee' => 2500],
  'RF' => ['code' => 'RF', 'initials' => 'D3', 'name' => 'Dr. Sample Doctor 3', 'specialty' => 'Pediatrics', 'session' => '09:00–12:00', 'tone' => 'teal', 'fee' => 3000],
  'MP' => ['code' => 'MP', 'initials' => 'D2', 'name' => 'Dr. Sample Doctor 2', 'specialty' => 'ENT', 'session' => '09:00–13:00', 'tone' => 'amber', 'fee' => 2000],
];

$apptCode = isset($_GET['appt']) ? (string) $_GET['appt'] : 'APT-1041';
$patient = isset($_GET['patient']) ? (string) $_GET['patient'] : 'The patient';
$docCode = isset($_GET['doc']) ? (string) $_GET['doc'] : 'AS';
$doctor = $doctors[$docCode] ?? $doctors['AS'];

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/receptionist/appointments"><?= icon('chevronLeft', 15) ?>Reschedule <?= e($apptCode) ?></a>
  <span class="patient-find-subhead__hint"><?= e($patient) ?></span>
</div>

<div class="record-detail-grid" style="max-width:900px" data-reschedule>
  <div class="stack">
    <div class="card">
      <div class="card__body">
        <div class="record-detail-doctor mb-6">
          <span class="book-doc-head__avatar avatar--<?= e($doctor['tone']) ?>"><?= e($doctor['initials']) ?></span>
          <div>
            <div class="text-title inline-parts"><span><?= e($doctor['name']) ?></span><span><?= e($doctor['specialty']) ?></span></div>
            <div class="med-row__sub">Session <?= e($doctor['session']) ?> with the same doctor</div>
          </div>
        </div>

        <div class="record-detail-block__label">Choose a later day</div>
        <div class="reschedule-days" data-presch-days>
          <button class="reschedule-day is-active" type="button" data-day="Mon 27 Jul">Mon 27 Jul</button>
          <button class="reschedule-day" type="button" data-day="Tue 28 Jul">Tue 28 Jul</button>
          <button class="reschedule-day" type="button" data-day="Wed 29 Jul">Wed 29 Jul</button>
          <button class="reschedule-day" type="button" data-day="Thu 30 Jul">Thu 30 Jul</button>
          <button class="reschedule-day" type="button" data-day="Fri 31 Jul">Fri 31 Jul</button>
        </div>

        <div class="record-detail-block__label mt-6">Available times with <?= e($doctor['name']) ?></div>
        <div class="reschedule-slots" data-presch-slots>
          <button class="reschedule-slot" type="button" data-slot="09:30">09:30</button>
          <button class="reschedule-slot" type="button" data-slot="10:30">10:30</button>
          <button class="reschedule-slot" type="button" data-slot="11:30">11:30</button>
          <button class="reschedule-slot" type="button" data-slot="12:00">12:00</button>
          <button class="reschedule-slot" type="button" data-slot="14:30">14:30</button>
          <button class="reschedule-slot" type="button" data-slot="15:00">15:00</button>
        </div>
      </div>
    </div>
  </div>

  <div class="stack">
    <div class="card">
      <div class="card__body">
        <div class="book-summary">
          <div class="book-summary__eyebrow">Reschedule</div>
          <div class="book-summary__row"><span>Appointment</span><span class="val mono"><?= e($apptCode) ?></span></div>
          <div class="book-summary__row"><span>Patient</span><span class="val"><?= e($patient) ?></span></div>
          <div class="book-summary__row"><span>Doctor</span><span class="val"><?= e($doctor['name']) ?></span></div>
          <div class="book-summary__row"><span>New time</span><span class="val" data-presch-new>Pick a day &amp; time</span></div>
          <div class="book-summary__row"><span>Consultation fee</span><span class="val"><?= e(money($doctor['fee'])) ?></span></div>
          <hr class="book-summary__hr">
          <button class="btn btn--primary btn--block" type="button" data-presch-confirm disabled>Confirm reschedule</button>
          <a class="btn btn--secondary btn--block mt-4" href="/staff/receptionist/appointments">Keep the current time</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/receptionist/reschedule.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>