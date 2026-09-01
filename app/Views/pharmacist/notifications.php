<?php

declare(strict_types=1);

$title = 'Notifications';
$active = 'notifications';

$notifications = [
  ['tone' => 'danger', 'label' => 'Low stock', 'text' => 'Amoxicillin 500 mg has 14 left. The reorder level is 40.', 'time' => '09:55', 'unread' => true],
  ['tone' => 'warning', 'label' => 'Expiring soon', 'text' => 'Cetirizine 10 mg batch BT-2201 expires on 29 Jul 2026 (18 days).', 'time' => '09:15', 'unread' => true],
  ['tone' => 'danger', 'label' => 'Out of stock', 'text' => 'Salbutamol inhaler is out of stock. Order RX-1035 is waiting for it.', 'time' => '08:45', 'unread' => false],
  ['tone' => 'info', 'label' => 'New online order', 'text' => 'Nimsith Wickrama sent order RX-1042 for pickup.', 'time' => '08:30', 'unread' => false],
  ['tone' => 'warning', 'label' => 'Photo prescription', 'text' => 'K.A. Inuka Asith uploaded a photo prescription (ORD-3391). Add its medicines.', 'time' => '08:12', 'unread' => false],
  ['tone' => 'success', 'label' => 'Batch registered', 'text' => 'Batch BT-2231 (500 Amoxil 500) from MedLanka Pvt Ltd was added.', 'time' => 'Yesterday 17:30', 'unread' => false],
  ['tone' => 'info', 'label' => 'Repeat prescription due', 'text' => 'M. L. Omindu Gunathilaka\'s monthly prescription RX-0871 is due.', 'time' => 'Yesterday 14:15', 'unread' => false],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Notification centre</h1>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button" id="notifications-mark-all">Mark all read</button>
  </div>
</div>

<div class="card notification-list-card">
  <div class="card__body">
    <div class="notif-page-list">
      <?php foreach ($notifications as $n): ?>
        <div class="notif-centre__item<?= $n['unread'] ? ' notif-centre__item--unread' : '' ?>">
          <span class="notif-centre__dot notif-centre__dot--<?= e($n['tone']) ?>"></span>
          <div class="notif-centre__body">
            <div class="notif-centre__label"><?= e($n['label']) ?></div>
            <div class="notif-centre__text"><?= e($n['text']) ?></div>
          </div>
          <span class="notif-centre__time"><?= e($n['time']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script src="/assets/js/pharmacist/notifications.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
