<?php

declare(strict_types=1);

if (!isset($title)) {
  $title = 'Patient portal';
}
if (!isset($active)) {
  $active = 'home';
}

$signedIn = Patient::find((int) current_patient_id());
$patientName = $signedIn['full_name'];
$patientCode = $signedIn['patient_code'];
$patientMemberSince = date('Y', strtotime($signedIn['created_at']));
$patientNic = $signedIn['nic'] ?? '-';
$patientMobile = $signedIn['mobile'] ?? '-';
$patientBloodType = $signedIn['blood_type'] ?? '-';
$patientPhoto = $signedIn['photo_uri'] ?? '/assets/img/photos/patient-portrait.jpg';

$unreadCount = 2;

$patientQrUrl = quickchart_qr_url($patientCode, 132);

if ($active === 'notifications') {
  $bellActiveClass = ' is-active';
} else {
  $bellActiveClass = '';
}

if ($active === 'profile') {
  $profileLinkClass = ' is-active';
} else {
  $profileLinkClass = '';
}

if ($active === 'records') {
  $recordsLinkClass = ' is-active';
} else {
  $recordsLinkClass = '';
}

if (in_array($active, ['more', 'book', 'your-health', 'records', 'billing', 'notifications', 'profile'], true)) {
  $moreTabClass = ' is-active';
} else {
  $moreTabClass = '';
}

if ($active === 'billing') {
  $billingLinkClass = ' is-active';
} else {
  $billingLinkClass = '';
}
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
  <link rel="stylesheet" href="/assets/css/patient.css">
  <script src="/assets/js/ui.js" defer></script>
</head>

<body>
  <div class="app patient-app">
    <?php
    $railGroups = [
      'Your visits' => [
        ['key' => 'home', 'label' => 'Home', 'href' => '/app/home', 'icon' => 'home'],
        ['key' => 'book', 'label' => 'Book an appointment', 'href' => '/app/book', 'icon' => 'calendarPlus'],
        ['key' => 'appointments', 'label' => 'My appointments', 'href' => '/app/appointments', 'icon' => 'calendarCheck'],
        ['key' => 'live-queue', 'label' => 'Live queue', 'href' => '/app/live-queue', 'icon' => 'queue'],
      ],
      'Your health' => [
        ['key' => 'your-health', 'label' => 'Your health', 'href' => '/app/your-health', 'icon' => 'heartPulse'],
        ['key' => 'prescriptions', 'label' => 'Pharmacy', 'href' => '/app/prescriptions', 'icon' => 'pill'],
        ['key' => 'records', 'label' => 'Medical records', 'href' => '/app/records', 'icon' => 'records'],
      ],
      'Your account' => [
        ['key' => 'billing', 'label' => 'Bills and payments', 'href' => '/app/billing', 'icon' => 'billing'],
        ['key' => 'notifications', 'label' => 'Notifications', 'href' => '/app/notifications', 'icon' => 'bell'],
      ],
    ];

    $active = $active ?? 'home';
    ?>
    <aside class="clinic-rail" id="clinic-drawer" data-drawer aria-label="All pages">
      <div class="clinic-rail__head">
        <button class="clinic-menu-btn" type="button" data-drawer-toggle aria-controls="clinic-drawer" aria-expanded="false" aria-label="Expand menu">
          <span class="clinic-menu-btn__bars"><span></span><span></span><span></span></span>
        </button>
      </div>

      <div class="clinic-rail__scroll">
        <?php foreach ($railGroups as $groupName => $items): ?>
          <div class="clinic-rail__group">
            <div class="clinic-rail__grouphead"><?= e($groupName) ?></div>
            <?php foreach ($items as $item): ?>
              <a class="clinic-rail__link<?= $item['key'] === $active ? ' is-active' : '' ?>"
                href="<?= e($item['href']) ?>"
                title="<?= e($item['label']) ?>">
                <span class="clinic-rail__icon"><img class="icon" src="/assets/img/icons/<?= e($item['icon']) ?>.svg" alt="" width="17" height="17"></span>
                <span class="clinic-rail__label"><?= e($item['label']) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="clinic-rail__foot">
        <a class="clinic-rail__id<?= $profileLinkClass ?>" href="/app/profile" title="Your profile">
          <img class="clinic-rail__avatar" src="<?= e($patientPhoto) ?>" alt="" width="40" height="40">
          <span class="clinic-rail__idtext">
            <strong><?= e($patientName) ?></strong>
            <span><?= e($patientCode) ?></span>
          </span>
        </a>
      </div>
    </aside>
    <div class="app-main">
      <header class="app-topbar clinic-topbar">
        <div class="clinic-topbar__inner">
          <a class="clinic-brand" href="/">
            <img class="clinic-brand__mark" src="/assets/img/MediTrackLogo.png" alt="" width="32" height="32">
            <span class="clinic-brand__text">
              <span class="clinic-brand__name">MediTrack</span>
              <span class="clinic-brand__clinic">HealthGate Medical</span>
            </span>
          </a>

          <?php
          $barNav = [
            ['key' => 'home', 'label' => 'Home', 'href' => '/app/home', 'icon' => 'home'],
            ['key' => 'appointments', 'label' => 'Appointments', 'href' => '/app/appointments', 'icon' => 'calendarCheck'],
            ['key' => 'live-queue', 'label' => 'Live queue', 'href' => '/app/live-queue', 'icon' => 'queue'],
            ['key' => 'prescriptions', 'label' => 'Pharmacy', 'href' => '/app/prescriptions', 'icon' => 'pill'],
          ];

          $active = $active ?? 'home';
          ?>
          <nav class="clinic-nav" aria-label="Primary">
            <?php foreach ($barNav as $item): ?>
              <a class="clinic-nav__item<?= $item['key'] === $active ? ' is-active' : '' ?>"
                href="<?= e($item['href']) ?>"
                <?= $item['key'] === $active ? 'aria-current="page"' : '' ?>>
                <img class="icon clinic-nav__icon" src="/assets/img/icons/<?= e($item['icon']) ?>.svg" alt="" width="17" height="17">
                <span class="clinic-nav__label"><?= e($item['label']) ?></span>
              </a>
            <?php endforeach; ?>
          </nav>

          <div class="clinic-topbar__actions">
            <a class="clinic-book-btn" href="/app/book"><img class="icon" src="/assets/img/icons/plus.svg" alt="" width="14" height="14">Book appointment</a>
            <a href="/app/notifications" class="clinic-bell<?= $bellActiveClass ?>" aria-label="Notifications">
              <img class="icon" src="/assets/img/icons/bell.svg" alt="" width="18" height="18">
              <?php if ($unreadCount > 0): ?><span class="clinic-bell__dot"><?= e($unreadCount) ?></span><?php endif; ?>
            </a>
            <div class="avatar-menu" data-avatar-menu>
              <button class="clinic-avatar avatar-menu__trigger" type="button" data-avatar-toggle aria-haspopup="true" aria-expanded="false">
                <img class="clinic-avatar__photo" src="<?= e($patientPhoto) ?>" alt="Your account" width="38" height="38">
              </button>
              <div class="avatar-menu__pop clinic-account" data-avatar-pop hidden>
                <div class="avatar-menu__id">
                  <img class="avatar-menu__avatar" src="<?= e($patientPhoto) ?>" alt="" width="40" height="40">
                  <div>
                    <div class="avatar-menu__name"><?= e($patientName) ?></div>
                    <div class="avatar-menu__meta inline-parts"><span><?= e($patientCode) ?></span><span>Patient since <?= e($patientMemberSince) ?></span></div>
                  </div>
                </div>

                <div class="clinic-account__card">
                  <img class="clinic-account__qr" src="<?= e($patientQrUrl) ?>" alt="QR code for <?= e($patientCode) ?>" loading="lazy" decoding="async">
                  <div class="clinic-account__cardtext">
                    <strong>My patient card</strong>
                    <span>Show this at reception and at the pharmacy counter.</span>
                    <div class="clinic-account__cardactions">
                      <button class="link-btn" type="button">Full screen</button>
                      <button class="link-btn" type="button">Print</button>
                    </div>
                  </div>
                </div>

                <div class="avatar-menu__facts">
                  <div class="avatar-menu__fact"><span>NIC</span><b><?= e($patientNic) ?></b></div>
                  <div class="avatar-menu__fact"><span>Mobile</span><b><?= e($patientMobile) ?></b></div>
                  <div class="avatar-menu__fact"><span>Blood type</span><b><?= e($patientBloodType) ?></b></div>
                </div>

                <div class="avatar-menu__links">
                  <a class="avatar-menu__link<?= $profileLinkClass ?>" href="/app/profile"><img class="icon" src="/assets/img/icons/profile.svg" alt="" width="15" height="15">Profile &amp; settings</a>
                  <a class="avatar-menu__link<?= $recordsLinkClass ?>" href="/app/records"><img class="icon" src="/assets/img/icons/records.svg" alt="" width="15" height="15">Medical records</a>
                  <a class="avatar-menu__link<?= $billingLinkClass ?>" href="/app/billing"><img class="icon" src="/assets/img/icons/billing.svg" alt="" width="15" height="15">Bills &amp; payments</a>
                  <a class="avatar-menu__link avatar-menu__link--danger" href="/logout"><img class="icon" src="/assets/img/icons/arrowRight.svg" alt="" width="15" height="15">Sign out</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <nav class="clinic-tabbar" aria-label="Primary">
        <?php foreach ($barNav as $item): ?>
          <a class="clinic-tabbar__item<?= $item['key'] === $active ? ' is-active' : '' ?>" href="<?= e($item['href']) ?>">
            <img class="icon" src="/assets/img/icons/<?= e($item['icon']) ?>.svg" alt="" width="20" height="20">
            <span><?= e($item['label']) ?></span>
          </a>
        <?php endforeach; ?>

        <a class="clinic-tabbar__item<?= $moreTabClass ?>" href="/app/more">
          <img class="icon" src="/assets/img/icons/grid.svg" alt="" width="20" height="20">
          <span>More</span>
        </a>
      </nav>

      <main class="app-content">