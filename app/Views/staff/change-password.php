<?php

declare(strict_types=1);

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - Set a new password</title>
  <link rel="icon" href="/assets/img/MediTrackLogo.png">
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/staff.css">
</head>

<body>
  <div class="staff-login">
    <aside class="staff-login__hero">
      <img class="staff-login__photo" src="/assets/img/login.webp" alt="" fetchpriority="high" decoding="async">
      <a class="staff-login__brand" href="/">
        <img class="staff-login__mark" src="/assets/img/MediTrackLogo.png" alt="" width="46" height="46">
        <div>
          <div class="staff-login__brand-name">MediTrack</div>
          <div class="staff-login__brand-sub">HealthGate Medical · Athurugiriya</div>
        </div>
      </a>
      <h1 class="staff-login__headline">Your account was created with a temporary password.</h1>
    </aside>

    <main class="staff-login__form-col">
      <form class="staff-login__card" method="post" autocomplete="off">
        <a href="/"><img class="staff-login__logo" src="/assets/img/logotext.png" alt="MediTrack - Clinic Management System" width="190"></a>
        <div class="staff-login__eyebrow">Staff portal</div>
        <div class="staff-login__title">Set a new password</div>

        <?php if ($error = get_flash_error()): ?>
          <div class="staff-login__demo"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="staff-login__form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <div class="field staff-login__pass">
            <span class="field__label">New password</span>
            <input class="field__input" type="password" name="password" required>
          </div>
          <div class="field staff-login__pass">
            <span class="field__label">Confirm new password</span>
            <input class="field__input" type="password" name="password_confirm" required>
          </div>
          <button class="btn btn--primary btn--block" type="submit">Save &amp; continue</button>
        </div>

        <div class="staff-login__demo">Admin set password has to be changed by staff</div>
      </form>
    </main>
  </div>
</body>

</html>