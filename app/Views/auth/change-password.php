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
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>

<body>
  <div class="auth-shell">
    <aside class="auth-hero">
      <img class="auth-hero__photo" src="/assets/img/login.webp" alt="" fetchpriority="high" decoding="async">
      <a class="auth-hero__brand" href="/">
        <img class="auth-hero__mark" src="/assets/img/MediTrackLogo.png" alt="" width="46" height="46">
        <span class="auth-hero__brand-text">
          <span class="auth-hero__brand-name">MediTrack</span>
          <span class="auth-hero__brand-sub">HealthGate Medical, Athurugiriya</span>
        </span>
      </a>
      <h1 class="auth-hero__headline">Welcome. Choose your own password to continue.</h1>
    </aside>

    <main class="auth-form-col">
      <div class="auth-card">
        <a class="auth-card__logo-link" href="/"><img class="auth-card__logo" src="/assets/img/logotext.png" alt="MediTrack - Clinic Management System" width="190"></a>
        <h2 class="auth-card__title">Set a new password</h2>
        <p class="auth-card__subtitle">You signed in with your NIC. Pick a password only you know.</p>
        <?php if ($error = get_flash_error()): ?>
          <div class="auth-demo"><?= icon('alert', 15) ?><span><?= e($error) ?></span></div>
        <?php endif; ?>
        <form class="auth-card__form" action="/change-password" method="post" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <label class="field"><span class="field__label">New password <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" type="password" name="password" required autofocus></label>
          <label class="field"><span class="field__label">Confirm new password <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" type="password" name="password_confirm" required></label>
          <p class="field__desc">At least 8 characters, with an uppercase letter, a lowercase letter and a number.</p>
          <button class="btn btn--primary btn--block" type="submit">Save and continue</button>
        </form>
        <p class="auth-card__foot"><a class="link-btn" href="/logout">Cancel</a></p>
      </div>
    </main>
  </div>
</body>

</html>
