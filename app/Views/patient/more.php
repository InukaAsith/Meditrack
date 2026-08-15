<?php

declare(strict_types=1);

$title = 'More';
$active = 'more';

require __DIR__ . '/header.php';
?>
<h1 class="rx-title mb-8">More</h1>

<div class="more-list">
  <a class="more-list__link" href="/app/book">
    <img class="icon" src="/assets/img/icons/calendarPlus.svg" alt="" width="18" height="18">
    <span>Book an appointment</span>
  </a>
  <a class="more-list__link" href="/app/your-health">
    <img class="icon" src="/assets/img/icons/heartPulse.svg" alt="" width="18" height="18">
    <span>Your health</span>
  </a>
  <a class="more-list__link" href="/app/records">
    <img class="icon" src="/assets/img/icons/records.svg" alt="" width="18" height="18">
    <span>Medical records</span>
  </a>
  <a class="more-list__link" href="/app/billing">
    <img class="icon" src="/assets/img/icons/billing.svg" alt="" width="18" height="18">
    <span>Bills and payments</span>
  </a>
  <a class="more-list__link" href="/app/notifications">
    <img class="icon" src="/assets/img/icons/bell.svg" alt="" width="18" height="18">
    <span>Notifications</span>
  </a>
</div>

<a class="more-profile" href="/app/profile">
  <img class="more-profile__photo" src="/assets/img/photos/patient-portrait.jpg" alt="" width="48" height="48">
  <span class="more-profile__who">
    <strong>K.A. Inuka Asith</strong>
    <span>View your profile</span>
  </span>
  <img class="icon" src="/assets/img/icons/chevronRight.svg" alt="" width="16" height="16">
</a>
<?php require __DIR__ . '/footer.php'; ?>