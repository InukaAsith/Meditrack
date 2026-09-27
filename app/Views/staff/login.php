<?php

declare(strict_types=1);

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediTrack - Staff sign in</title>
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
      <h1 class="staff-login__headline">Staff Login</h1>

    </aside>

    <main class="staff-login__form-col">
      <form class="staff-login__card" id="staff-login-form" action="/staff/login" method="post" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <a href="/"><img class="staff-login__logo" src="/assets/img/logotext.png" alt="MediTrack - Clinic Management System" width="190"></a>
        <div class="staff-login__eyebrow">Staff portal</div>
        <div class="staff-login__title">Sign in to continue</div>

        <?php if ($error = get_flash_error()): ?>
          <div class="staff-login__demo"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="staff-login__form">
          <label class="field">
            <span class="field__label">Staff ID or email <span class="field__req" aria-hidden="true">*</span></span>
            <input class="field__input" type="text" name="staff_id" required>
          </label>

          <div class="field staff-login__pass">
            <span class="field__label">Password <span class="field__req" aria-hidden="true">*</span></span>
            <input class="field__input" type="password" id="staff-pass" name="password" required>
            <button class="staff-login__pass-toggle" type="button" id="staff-pass-toggle">Show</button>
          </div>

          <div class="staff-login__row">
            <a class="link-act" href="/staff/login">Forgot password?</a>
          </div>

          <button class="btn btn--primary btn--block" type="submit">Sign in</button>
        </div>

        <p class="staff-login__foot"><a class="link-btn" href="/login">Patient login</a></p>
      </form>
    </main>
  </div>

  <script>
    (function() {
      var pass = document.getElementById("staff-pass");
      var toggle = document.getElementById("staff-pass-toggle");
      if (toggle && pass) {
        toggle.addEventListener("click", function() {
          var show = pass.type === "password";
          pass.type = show ? "text" : "password";
          toggle.textContent = show ? "Hide" : "Show";
        });
      }
    })();
  </script>
</body>

</html>