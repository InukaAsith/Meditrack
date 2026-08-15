<?php

declare(strict_types=1);

$title = 'Notifications';
$active = 'notifications';

$unread = 2;

require __DIR__ . '/header.php';
?>
<div class="section-lead notif-lead">
  <h1 class="home-greeting m-0">You have 2 new messages</h1>
  <button class="page-hero__btn page-hero__btn--ghost" type="button" id="mark-all-read">Mark all as read</button>
</div>

<div class="notif-list">
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--blue"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 1</div>
      <div class="notif-card__time">09:48</div>
    </div>
    <span class="notif-card__unread"></span>
  </div>
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--blue"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 2</div>
      <div class="notif-card__time">09:35</div>
    </div>
    <span class="notif-card__unread"></span>
  </div>
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--amber"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 3</div>
      <div class="notif-card__time">09:20</div>
    </div>
  </div>
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--green"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 4</div>
      <div class="notif-card__time">09:05</div>
    </div>
  </div>
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--blue"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 5</div>
      <div class="notif-card__time">07:30</div>
    </div>
  </div>
  <div class="notif-card">
    <span class="notif-card__dot notif-card__dot--gray"></span>
    <div class="notif-card__body">
      <div class="notif-card__text">Test notification 6</div>
      <div class="notif-card__time">Yesterday</div>
    </div>
  </div>
</div>
<script src="/assets/js/patient/notifications.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>