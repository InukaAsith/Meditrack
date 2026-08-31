<?php

declare(strict_types=1);

$title = 'Notifications';
$active = 'notifications';

$notifications = [
  ['label' => 'Password reset requested', 'text' => 'M. L. Omindu Gunathilaka (EMP-011) asked for a new password.', 'time' => 'Today 08:20', 'tone' => 'warning', 'unread' => true],
  ['label' => 'Records ready to archive', 'text' => '4,120 records are older than 2 years.', 'time' => 'Today', 'tone' => 'info', 'unread' => true],
  ['label' => 'New staff account created', 'text' => 'EMP-012 was added as a Receptionist.', 'time' => 'Today 08:20', 'tone' => 'success', 'unread' => false],
  ['label' => 'Data deletion request', 'text' => 'A patient asked for their data to be deleted.', 'time' => 'Yesterday', 'tone' => 'danger', 'unread' => false],
];

$icons = ['warning' => 'alert', 'info' => 'bell', 'success' => 'check', 'danger' => 'shield'];

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
    <div class="manager-list">
      <?php foreach ($notifications as $n): ?>
        <div class="manager-list__row">
          <span class="manager-list__icon manager-list__icon--<?= e($n['tone']) ?>"><?= icon($icons[$n['tone']] ?? 'bell', 16) ?></span>
          <div class="manager-list__body">
            <span class="manager-list__title"><?= e($n['label']) ?><?php if (!empty($n['unread'])): ?> <span class="badge badge--primary ml-3">New</span><?php endif; ?></span>
            <span class="manager-list__meta"><?= e($n['text']) ?></span>
          </div>
          <div class="manager-list__aside">
            <span class="manager-list__meta"><?= e($n['time']) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>