<?php

declare(strict_types=1);

$title = $title ?? 'Manager';
$active = $active ?? 'dashboard';

$signedIn = Staff::find((int) current_staff_id());
$manager = [
  'name' => $signedIn['full_name'],
  'initials' => initials($signedIn['full_name']),
  'role' => $signedIn['role_name'],
];
$clinicName = 'HealthGate Medical';

$hasUnread = true;
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - <?= e($title) ?></title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/manager.css">
  <script src="/assets/js/ui.js" defer></script>
</head>

<body>
  <div class="app staff-app">
    <?php
    $navItems = [
      ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => '/staff/manager/dashboard', 'icon' => 'home'],
      ['key' => 'analytics', 'label' => 'Analytics', 'href' => '/staff/manager/analytics', 'icon' => 'records'],
      ['key' => 'financial-reports', 'label' => 'Financial reports', 'href' => '/staff/manager/financial-reports', 'icon' => 'billing'],
      ['key' => 'approvals', 'label' => 'Approvals', 'href' => '/staff/manager/approvals', 'icon' => 'check'],
      ['key' => 'drug-pricing', 'label' => 'Drug pricing', 'href' => '/staff/manager/drug-pricing', 'icon' => 'pill'],
      ['key' => 'pharmacy-alerts', 'label' => 'Pharmacy alerts', 'href' => '/staff/manager/pharmacy-alerts', 'icon' => 'alert'],
      ['key' => 'notifications', 'label' => 'Notifications', 'href' => '/staff/manager/notifications', 'icon' => 'bell'],
      ['key' => 'profile', 'label' => 'Profile', 'href' => '/staff/manager/profile', 'icon' => 'profile'],
    ];
    ?>
    <nav class="sidebar staff-nav" aria-label="Primary">
      <div class="staff-nav__brand">
        <img class="brand__mark" src="/assets/img/MediTrackLogo.png" alt="MediTrack" width="34" height="34">
        <div class="staff-nav__brand-text">
          <span class="staff-nav__brand-name">MediTrack</span>
          <span class="staff-nav__brand-sub">HealthGate Medical</span>
        </div>
      </div>

      <div class="sidebar__links">
        <?php foreach ($navItems as $item): ?>
          <a class="sidebar__item<?= $item['key'] === $active ? ' is-active' : '' ?>"
            href="<?= e($item['href']) ?>"
            <?= $item['key'] === $active ? 'aria-current="page"' : '' ?>>
            <?= icon($item['icon'], 16, 'sidebar__icon') ?>
            <span class="sidebar__label"><?= e($item['label']) ?></span>
            <?php if ($item['key'] === 'notifications' && $hasUnread): ?><span class="sidebar__dot"></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="sidebar__footer staff-nav__footer">
        <div class="staff-id">
          <span class="staff-id__avatar"><?= e($manager['initials']) ?></span>
          <div class="staff-id__text">
            <strong><?= e($manager['name']) ?></strong>
            <span><?= e($manager['role']) ?></span>
          </div>
        </div>
        <a class="staff-id__logout" href="/staff/logout"><?= icon('arrowRight', 14) ?>Log out</a>
      </div>
    </nav>
    <div class="app-main">
      <header class="app-topbar staff-topbar">
        <div class="staff-topbar__clock">
          <?= icon('clock', 15) ?>
          <span><?= e(strtoupper(date('D d M Y'))) ?></span>
          <span data-clock><?= e(date('H:i')) ?></span>
        </div>
        <div class="staff-topbar__actions">
          <a href="/staff/manager/notifications" class="staff-topbar__bell" aria-label="Notifications">
            <?= icon('bell', 18) ?>
            <?php if ($hasUnread): ?><span class="staff-topbar__bell-dot"></span><?php endif; ?>
          </a>
          <div class="avatar-menu" data-avatar-menu>
            <button class="staff-topbar__avatar avatar-menu__trigger" type="button" data-avatar-toggle aria-haspopup="true" aria-expanded="false"><?= e($manager['initials']) ?></button>
            <div class="avatar-menu__pop" data-avatar-pop hidden>
              <div class="avatar-menu__id">
                <span class="avatar-menu__avatar"><?= e($manager['initials']) ?></span>
                <div>
                  <div class="avatar-menu__name"><?= e($manager['name']) ?></div>
                  <div class="avatar-menu__meta inline-parts"><span><?= e($manager['role']) ?></span><span><?= e($clinicName) ?></span></div>
                </div>
              </div>
              <div class="avatar-menu__links">
                <a class="avatar-menu__link" href="/staff/manager/profile"><?= icon('profile', 15) ?>My profile</a>
                <a class="avatar-menu__link" href="/staff/manager/notifications"><?= icon('bell', 15) ?>Notifications</a>
                <a class="avatar-menu__link avatar-menu__link--danger" href="/staff/logout"><?= icon('arrowRight', 15) ?>Log out</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      <main class="app-content">