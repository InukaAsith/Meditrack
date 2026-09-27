<?php

declare(strict_types=1);

$apptCode = 'APT-1052';
$token = trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (str_starts_with($token, 'guest/')) {
  $seg = substr($token, strlen('guest/'));
  if ($seg !== '' && preg_match('/^[A-Za-z0-9\-]+$/', $seg)) {
    $apptCode = strtoupper($seg);
  }
}
$subject = 'Sandanu Dulmeth';
$phone = '+94 71 882 4401';

$seed = strlen($subject);
$qrCells = [];
for ($i = 0; $i < 36; $i++) {
  $qrCells[] = (((int) floor(abs(sin(($i + $seed) * 12.9898) * 10000)) % 100) < 48);
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - Your appointment</title>
  <link rel="icon" href="/assets/img/MediTrackLogo.png">
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/patient.css">
  <link rel="stylesheet" href="/assets/css/guest.css">
</head>

<body>
  <div class="guest-shell patient-app">
    <div class="guest-topbar">
      <div class="guest-topbar__left">
        <a class="clinic-brand" href="/">
          <img class="clinic-brand__mark" src="/assets/img/MediTrackLogo.png" alt="" width="32" height="32">
          <span class="clinic-brand__text">
            <span class="clinic-brand__name">MediTrack</span>
            <span class="clinic-brand__clinic">HealthGate Medical</span>
          </span>
        </a>
        <span class="guest-pill">Guest link · <?= e($apptCode) ?></span>
      </div>
      <a class="guest-topbar__button" href="/register">Create an account</a>
    </div>

    <div class="guest-content guest-content--narrow">
      <p class="gtrack-lead">Opened from the link sent to <b><?= e($phone) ?></b> - bookmark this page, it doesn't need a password.</p>

      <div class="gtrack-hero">
        <span class="gtrack-hero__avatar">SD</span>
        <div class="gtrack-hero__body">
          <div class="gtrack-hero__name"><?= e($subject) ?></div>
          <div class="gtrack-hero__meta">Dr. Sample Doctor 1 · General · Fri 24 Jul · 10:30 · <?= e(money(2650)) ?> paid ✓</div>
        </div>
        <span class="gtrack-hero__badge">Confirmed</span>
      </div>

      <div class="guest-tracking-qr">
        <span class="guest-tracking-qr__icon"><?= icon('records', 22) ?></span>
        <div class="guest-tracking-qr__body">
          <div class="guest-tracking-qr__title">Your Booking QR</div>
          <div class="guest-tracking-qr__sub">Show this at check-in - a permanent record is created under this NIC on your first visit</div>
        </div>
        <div class="guest-tracking-qr__img">
          <?php foreach ($qrCells as $on): ?><span class="<?= $on ? 'is-on' : '' ?>"></span><?php endforeach; ?>
        </div>
      </div>

      <div class="gtrack-actions">
        <button class="btn btn--primary btn--block" type="button">Download E-Bill</button>
        <button class="btn btn--soft-danger btn--block" type="button">Cancel Appointment</button>
      </div>

      <div class="gtrack-outro">
        Want records, family bookings and this all in one place next time? With these same details, <a href="/register">Create a free account</a>.
      </div>
    </div>
  </div>
  <script src="/assets/js/ui.js" defer></script>
</body>

</html>