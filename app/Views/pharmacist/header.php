<?php
declare(strict_types=1);

$title  = $title ?? 'Pharmacy';
$active = $active ?? 'dashboard';

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
  <link rel="stylesheet" href="/assets/css/pharmacist.css">
  <?php foreach (($extraCss ?? []) as $x): ?>
    <link rel="stylesheet" href="/assets/css/<?= e($x) ?>.css">
  <?php endforeach; ?>
  <script src="/assets/js/ui.js" defer></script>
</head>

<body>
  <div class="app staff-app">
    <?php
    $navItems = [
        ['key' => 'dashboard',       'label' => 'Dashboard',          'href' => '/staff/pharmacist/dashboard',       'icon' => 'home'],
        ['key' => 'prepare-queue',   'label' => 'Prepare queue',      'href' => '/staff/pharmacist/prepare-queue',   'icon' => 'queue'],
        ['key' => 'dispense',        'label' => 'Prepare & dispense', 'href' => '/staff/pharmacist/dispense',        'icon' => 'grid'],
        ['key' => 'inventory',       'label' => 'Inventory',          'href' => '/staff/pharmacist/inventory',       'icon' => 'records'],
        ['key' => 'suppliers',       'label' => 'Suppliers',          'href' => '/staff/pharmacist/suppliers',       'icon' => 'building'],
        ['key' => 'register-batch',  'label' => 'Register batch',     'href' => '/staff/pharmacist/register-batch',  'icon' => 'upload'],
        ['key' => 'billing-history', 'label' => 'Billing history',    'href' => '/staff/pharmacist/billing-history', 'icon' => 'billing'],
        ['key' => 'stock-alerts',    'label' => 'Stock alerts',       'href' => '/staff/pharmacist/stock-alerts',    'icon' => 'warning'],
        ['key' => 'notifications',   'label' => 'Notifications',      'href' => '/staff/pharmacist/notifications',   'icon' => 'bell'],
        ['key' => 'profile',         'label' => 'Profile',            'href' => '/staff/pharmacist/profile',         'icon' => 'profile'],
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
            <?php if ($item['key'] === 'notifications' && $hasUnread): ?><span class="sidebar__dot"></span><?php endif; ?>
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
          <span><?= e(strtoupper(date('D d M Y'))) ?></span>
          <span data-clock><?= e(date('H:i')) ?></span>
        </div>
        <div class="staff-topbar__actions">
          <div class="staff-topbar__quick">
            <a class="btn btn--secondary btn--sm" href="/staff/pharmacist/dispense?mode=manual"><?= icon('grid', 14) ?>Manual dispense</a>
            <a class="btn btn--primary btn--sm" href="/staff/pharmacist/dispense"><?= icon('search', 14) ?>Scan QR or look up</a>
          </div>
          <a href="/staff/pharmacist/notifications" class="staff-topbar__bell" aria-label="Notifications">
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
                  <div class="avatar-menu__meta inline-parts"><span><?= e($staff['role']) ?></span><span><?= e($clinicName) ?></span></div>
                </div>
              </div>
              <div class="avatar-menu__links">
                <a class="avatar-menu__link" href="/staff/pharmacist/profile"><?= icon('profile', 15) ?>My profile</a>
                <a class="avatar-menu__link" href="/staff/pharmacist/notifications"><?= icon('bell', 15) ?>Notifications</a>
                <a class="avatar-menu__link avatar-menu__link--danger" href="/staff/logout"><?= icon('arrowRight', 15) ?>Log out</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      <main class="app-content">
