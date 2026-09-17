<?php

declare(strict_types=1);

$title = 'Notifications';
$active = 'notifications';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Notification centre</h1>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button">Mark all read</button>
  </div>
</div>

<div class="card" style="max-width:760px">
  <div class="card__body">
    <div class="notif-page-list">
      <div class="notif-centre__item" style="background:var(--primary-tint)">
        <span class="notif-centre__dot notif-centre__dot--warning"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Test notification 1</div>
        </div>
        <span class="notif-centre__time">09:38</span>
      </div>
      <div class="notif-centre__item" style="background:var(--primary-tint)">
        <span class="notif-centre__dot notif-centre__dot--info"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Test notification 2</div>
        </div>
        <span class="notif-centre__time">09:31</span>
      </div>
      <div class="notif-centre__item" style="background:var(--surface-inset)">
        <span class="notif-centre__dot notif-centre__dot--success"></span>
        <div class="notif-centre__body">
          <div class="notif-centre__label">Test notification 3</div>
        </div>
        <span class="notif-centre__time">09:05</span>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>