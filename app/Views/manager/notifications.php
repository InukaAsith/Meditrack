<?php

declare(strict_types=1);

$title = 'Notifications';
$active = 'notifications';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Notifications</h1>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button"><?= icon('check', 14) ?>Mark all read</button>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div class="notif-page-list notif-feed__list">
      <div class="notif-feed__item is-unread">
        <span class="notif-feed__dot"></span>
        <span class="manager-list__icon manager-list__icon--info" style="width:34px;height:34px"><?= icon('bell', 16) ?></span>
        <div class="notif-feed__body" style="flex:1">
          <p class="text-bold">Month-end report ready</p>
          <p class="text-muted">The June 2026 financial report is ready to export.</p>
          <span class="notif-feed__time">Today 08:00</span>
        </div>
      </div>
      <div class="notif-feed__item is-unread">
        <span class="notif-feed__dot"></span>
        <span class="manager-list__icon manager-list__icon--warning" style="width:34px;height:34px"><?= icon('bell', 16) ?></span>
        <div class="notif-feed__body" style="flex:1">
          <p class="text-bold">Pricing approval pending</p>
          <p class="text-muted">4 approvals are waiting for your review.</p>
          <span class="notif-feed__time">Yesterday</span>
        </div>
      </div>
      <div class="notif-feed__item">
        <span class="notif-feed__dot"></span>
        <span class="manager-list__icon manager-list__icon--success" style="width:34px;height:34px"><?= icon('bell', 16) ?></span>
        <div class="notif-feed__body" style="flex:1">
          <p class="text-bold">Utilization milestone</p>
          <p class="text-muted">Slot utilization crossed 70% for the first time this quarter.</p>
          <span class="notif-feed__time">2 days ago</span>
        </div>
      </div>
      <div class="notif-feed__item">
        <span class="notif-feed__dot"></span>
        <span class="manager-list__icon manager-list__icon--danger" style="width:34px;height:34px"><?= icon('bell', 16) ?></span>
        <div class="notif-feed__body" style="flex:1">
          <p class="text-bold">Pharmacy stock alert</p>
          <p class="text-muted">3 batches will expire within 30 days.</p>
          <span class="notif-feed__time">2 days ago</span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>