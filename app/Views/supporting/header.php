<?php

declare(strict_types=1);

$title = $title ?? 'Supporting Staff Portal';
$active = $active ?? 'live-queue';

$signedIn = Staff::find((int) current_staff_id());
$staff = [
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
  <link rel="icon" href="/assets/img/MediTrackLogo.png">
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/staff-common.css">
  <link rel="stylesheet" href="/assets/css/supporting.css">
  <?php foreach (($extraCss ?? []) as $x): ?>
    <link rel="stylesheet" href="/assets/css/<?= e($x) ?>.css">
  <?php endforeach; ?>
  <script src="/assets/js/ui.js" defer></script>
</head>

<body>
  <div class="app staff-app">
    <?php
    $navItems = [
      ['key' => 'dashboard',       'label' => 'Dashboard',       'href' => '/staff/supporting/dashboard',       'icon' => 'home'],
      ['key' => 'live-queue',      'label' => 'Live queue',      'href' => '/staff/supporting/live-queue',      'icon' => 'queue'],
      ['key' => 'doctor-schedule', 'label' => 'Doctor schedule', 'href' => '/staff/supporting/doctor-schedule', 'icon' => 'calendar'],
      ['key' => 'vitals-history',  'label' => 'Vitals history',  'href' => '/staff/supporting/vitals-history',  'icon' => 'records'],
      ['key' => 'alerts',          'label' => 'Alerts',          'href' => '/staff/supporting/alerts',          'icon' => 'alert'],
      ['key' => 'profile',         'label' => 'Profile',         'href' => '/staff/supporting/profile',         'icon' => 'profile'],
    ];
    ?>
    <nav class="sidebar staff-nav" aria-label="Primary">
      <a class="staff-nav__brand" href="/">
        <img class="brand__mark" src="/assets/img/MediTrackLogo.png" alt="MediTrack" width="34" height="34">
        <div class="staff-nav__brand-text">
          <span class="staff-nav__brand-name">MediTrack</span>
          <span class="staff-nav__brand-sub">HealthGate Medical</span>
        </div>
      </a>

      <div class="sidebar__links">
        <?php foreach ($navItems as $item): ?>
          <a class="sidebar__item<?= $item['key'] === $active ? ' is-active' : '' ?>"
            href="<?= e($item['href']) ?>"
            <?= $item['key'] === $active ? 'aria-current="page"' : '' ?>>
            <?= icon($item['icon'], 16, 'sidebar__icon') ?>
            <span class="sidebar__label"><?= e($item['label']) ?></span>
            <?php if ($item['key'] === 'alerts' && $hasUnread): ?><span class="sidebar__dot"></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="sidebar__footer staff-nav__footer">
        <div class="staff-id">
          <span class="staff-id__avatar"><?= e($staff['initials']) ?></span>
          <div class="staff-id__text">
            <strong><?= e($staff['name']) ?></strong>
            <span><?= e($staff['role']) ?></span>
          </div>
        </div>
        <a class="staff-id__logout" href="/staff/logout"><?= icon('arrowRight', 14) ?>Log out</a>
      </div>
    </nav>
    <div class="app-main">
      <header class="app-topbar staff-topbar">
        <div class="staff-topbar__clock">
          <?= icon('clock', 15) ?>
          <span><?= e(strtoupper(date('D d M Y'))) ?> · <span data-clock><?= e(date('H:i')) ?></span></span>
        </div>
        <div class="staff-topbar__actions">
          <div class="staff-topbar__quick">
            <button type="button" class="btn btn--danger btn--sm btn-emergency-trigger" id="emergency-btn">
              <?= icon('plus', 14) ?> Emergency
            </button>
          </div>
          <a href="/staff/supporting/alerts" class="staff-topbar__bell" aria-label="Alerts & Notifications">
            <?= icon('bell', 18) ?>
            <?php if ($hasUnread): ?><span class="staff-topbar__bell-dot"></span><?php endif; ?>
          </a>
          <div class="avatar-menu" data-avatar-menu>
            <button class="staff-topbar__avatar avatar-menu__trigger" type="button" data-avatar-toggle aria-haspopup="true" aria-expanded="false"><?= e($staff['initials']) ?></button>
            <div class="avatar-menu__pop" data-avatar-pop hidden>
              <div class="avatar-menu__id">
                <span class="avatar-menu__avatar"><?= e($staff['initials']) ?></span>
                <div>
                  <div class="avatar-menu__name"><?= e($staff['name']) ?></div>
                  <div class="avatar-menu__meta"><?= e($staff['role']) ?> · <?= e($clinicName) ?></div>
                </div>
              </div>
              <div class="avatar-menu__links">
                <a class="avatar-menu__link" href="/staff/supporting/alerts"><?= icon('bell', 15) ?>Alerts</a>
                <a class="avatar-menu__link avatar-menu__link--danger" href="/staff/logout"><?= icon('arrowRight', 15) ?>Log out</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      <main class="app-content">