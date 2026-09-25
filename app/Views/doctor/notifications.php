<?php

declare(strict_types=1);

$title  = 'Notifications';
$active = 'notifications';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Notifications</h1>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary btn--sm" type="button">Mark all as read</button>
  </div>
</div>

<div class="card" style="max-width:720px">
  <div class="card__body">
    <div class="notif-centre">
      <div class="notif-centre__item notif-centre__item--unread">
        <span class="notif-centre__dot notif-centre__dot--info"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Queue update</div>
          <div class="notif-centre__text">PT-0967 moved to slot 6 - reception rescheduled.</div>
          <div class="notif-centre__time">09:35</div>
        </div>
      </div>
      <div class="notif-centre__item notif-centre__item--unread">
        <span class="notif-centre__dot notif-centre__dot--warning"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Lab result ready</div>
          <div class="notif-centre__text">PT-1088 HbA1c result flagged - view in patient workspace.</div>
          <div class="notif-centre__time">09:28</div>
        </div>
      </div>
      <div class="notif-centre__item">
        <span class="notif-centre__dot notif-centre__dot--success"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Rx dispensed</div>
          <div class="notif-centre__text">RX-0815 for PT-1121 dispensed by pharmacy.</div>
          <div class="notif-centre__time">09:12</div>
        </div>
      </div>
      <div class="notif-centre__item">
        <span class="notif-centre__dot notif-centre__dot--info"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Schedule change</div>
          <div class="notif-centre__text">Thu 16 Jul marked as leave - 4 appointments reassigned.</div>
          <div class="notif-centre__time">08:55</div>
        </div>
      </div>
      <div class="notif-centre__item">
        <span class="notif-centre__dot notif-centre__dot--warning"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Allergy alert</div>
          <div class="notif-centre__text">PT-1088 has Penicillin allergy - flagged during Rx entry.</div>
          <div class="notif-centre__time">08:40</div>
        </div>
      </div>
    </div>
    <div style="text-align:center;padding:var(--sp-5);color:var(--text-muted);font-size:var(--fs-xs)">
      All notifications up to date
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>