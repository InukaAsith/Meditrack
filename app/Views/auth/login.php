<?php

declare(strict_types=1);

$tab = $tab ?? 'signin';

$titles = ['signin' => 'Sign in', 'register' => 'Create account', 'forgot' => 'Reset password'];
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - <?= e($titles[$tab]) ?></title>
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
          <span class="auth-hero__brand-sub">HealthGate Medical · Athurugiriya</span>
        </span>
      </a>
      <h1 class="auth-hero__headline">Sign Into Access full features</h1>
      <div class="auth-hero__points">
      </div>
    </aside>

    <main class="auth-form-col">
      <div class="auth-card">
        <a class="auth-card__logo-link" href="/"><img class="auth-card__logo" src="/assets/img/logotext.png" alt="MediTrack - Clinic Management System" width="190"></a>
        <?php if ($tab !== 'forgot'): ?>
          <div class="tabs tabs--pill auth-card__tabs">
            <a class="tabs__item<?= $tab === 'signin' ? ' is-active' : '' ?>" href="/login">Sign in</a>
            <a class="tabs__item<?= $tab === 'register' ? ' is-active' : '' ?>" href="/register">Create account</a>
          </div>
        <?php endif; ?>

        <?php if ($tab === 'signin'): ?>
          <h2 class="auth-card__title">Welcome back</h2>
          <p class="auth-card__subtitle">Sign in to your patient portal.</p>
          <?php if ($error = get_flash_error()): ?>
            <div class="auth-demo"><?= icon('alert', 15) ?><span><?= e($error) ?></span></div>
          <?php endif; ?>
          <form class="auth-card__form" action="/login" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="form" value="signin">
            <label class="field"><span class="field__label">Email or mobile</span><input class="field__input" type="text" name="identifier" required></label>
            <div class="field auth-pass">
              <span class="field__label">Password</span>
              <input class="field__input" type="password" id="pw" name="password" required>
              <button class="auth-pass__toggle" type="button" data-pw-toggle="pw">Show</button>
            </div>
            <div class="auth-card__row">
              <label class="toggle">
                <input class="visually-hidden" type="checkbox" name="remember" value="1" checked>
                <span class="toggle__track"><span class="toggle__thumb"></span></span>
                <span class="toggle__label">Remember me</span>
              </label>
              <a class="link-btn" href="/forgot-password">Forgot password?</a>
            </div>
            <button class="btn btn--primary btn--block" type="submit">Sign in</button>
          </form>
          <p class="auth-card__foot"><a class="link-btn" href="/book">Continue As a Guest</a>.</p>
          <p class="auth-card__foot"><a class="link-btn" href="/staff/login">Staff Login</a>.</p>

        <?php elseif ($tab === 'register'): ?>
          <h2 class="auth-card__title">Create your account</h2>
          <p class="auth-card__subtitle">One profile for appointments, records and billing.</p>
          <?php if ($error = get_flash_error()): ?>
            <div class="auth-demo"><?= icon('alert', 15) ?><span><?= e($error) ?></span></div>
          <?php endif; ?>
          <form class="auth-card__form" action="/register" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="form" value="register">
            <label class="field"><span class="field__label">Full name (as on NIC)</span><input class="field__input" type="text" name="full_name" placeholder="e.g. Sandanu Dulmeth" required></label>
            <label class="field"><span class="field__label">NIC number</span><input class="field__input" type="text" name="nic" placeholder="NIC" required></label>
            <label class="field"><span class="field__label">Mobile - queue SMS goes here</span><input class="field__input" type="text" name="mobile" placeholder="07XXXXXXXX" required></label>
            <label class="field"><span class="field__label">Email</span><input class="field__input" type="email" name="email" placeholder="you@example.com" required></label>
            <label class="field"><span class="field__label">Date of birth</span><input class="field__input" type="date" name="date_of_birth" max="<?= e(date('Y-m-d')) ?>" required></label>
            <label class="field">
              <span class="field__label">Gender</span>
              <select class="field__input" name="gender" required>
                <option value="">Choose…</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
              </select>
            </label>
            <label class="field">
              <span class="field__label">Blood type (optional)</span>
              <select class="field__input" name="blood_type">
                <option value="">Not known</option>
                <?php foreach ($bloodTypes as $type): ?>
                  <option value="<?= e($type) ?>"><?= e($type) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <div class="field auth-pass">
              <span class="field__label">Password</span>
              <input class="field__input" type="password" id="pw" name="password" placeholder="8+ characters, upper & lower case, a number" required>
              <button class="auth-pass__toggle" type="button" data-pw-toggle="pw">Show</button>
            </div>
            <div class="field auth-pass">
              <span class="field__label">Confirm password</span>
              <input class="field__input" type="password" id="pw-confirm" name="password_confirm" required>
              <button class="auth-pass__toggle" type="button" data-pw-toggle="pw-confirm">Show</button>
            </div>
            <label class="auth-consent"><input type="checkbox" name="pdpa_consent" checked><span>I agree to the <a class="link-btn" href="/">PDPA data policy</a> - my records are stored securely.</span></label>
            <button class="btn btn--primary btn--block" type="submit">Create account</button>
          </form>
          <p class="auth-card__foot">Already registered? <a class="link-btn" href="/login">Sign in</a></p>

        <?php else: ?>
          <h2 class="auth-card__title">Reset your password</h2>
          <p class="auth-card__subtitle">We'll send a reset link to your email or mobile.</p>
          <form class="auth-card__form" action="/login" method="get" autocomplete="off">
            <label class="field"><span class="field__label">Email or mobile</span><input class="field__input" type="text" placeholder="you@example.com"></label>
            <button class="btn btn--primary btn--block" type="submit">Send reset link</button>
          </form>
          <p class="auth-card__foot"><a class="link-btn" href="/login">← Back to sign in</a></p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    (function() {
      document.querySelectorAll("[data-pw-toggle]").forEach(function(btn) {
        btn.addEventListener("click", function() {
          var input = document.getElementById(btn.getAttribute("data-pw-toggle"));
          if (!input) return;
          var show = input.type === "password";
          input.type = show ? "text" : "password";
          btn.textContent = show ? "Hide" : "Show";
        });
      });
      document.querySelectorAll("[data-toggle]").forEach(function(t) {
        t.addEventListener("click", function(e) {
          e.preventDefault();
          t.classList.toggle("is-on");
        });
      });
    })();
  </script>
</body>

</html>