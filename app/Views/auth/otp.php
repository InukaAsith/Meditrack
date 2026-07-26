<?php

declare(strict_types=1);

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - Verify your mobile</title>
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
          <span class="auth-hero__brand-sub">HealthGate Medical · Athurugiriya</span>
        </span>
      </a>
      <h1 class="auth-hero__headline">One more step to verify it's you.</h1>
    </aside>

    <main class="auth-form-col">
      <div class="auth-card">
        <a class="auth-card__logo-link" href="/"><img class="auth-card__logo" src="/assets/img/logotext.png" alt="MediTrack - Clinic Management System" width="190"></a>
        <h2 class="auth-card__title">Verify your mobile</h2>
        <p class="auth-card__subtitle">We've sent a code to <?= e($_SESSION['register_draft']['mobile']) ?>.</p>
        <?php if ($error = get_flash_error()): ?>
          <div class="auth-demo"><?= icon('alert', 15) ?><span><?= e($error) ?></span></div>
        <?php else: ?>
          <div class="auth-demo"><?= icon('alert', 15) ?><span><strong>Demo</strong> - demo code <strong>123456</strong> </span></div>
        <?php endif; ?>
        <form class="auth-card__form" action="/otp" method="post" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <label class="field"><span class="field__label">6-digit code</span><input class="field__input" type="text" name="otp" inputmode="numeric" maxlength="6" required autofocus></label>
          <button class="btn btn--primary btn--block" type="submit">Verify &amp; create account</button>
        </form>
        <p class="auth-card__foot"><a class="link-btn" href="/register">← Back</a></p>
      </div>
    </main>
  </div>
</body>

</html>