<?php

declare(strict_types=1);

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HealthGate Medical - MediTrack</title>
  <link rel="icon" href="/assets/img/MediTrackLogo.png">
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/landing.css">
</head>

<body class="landing-page">

  <header class="landing-header">
    <div class="landing-container landing-header__inner">
      <a class="landing-brand" href="#top">
        <img class="landing-brand__logo" src="/assets/img/MediTrackLogo.png" alt="" width="40" height="40">
        <span class="landing-brand__text">
          <span class="landing-brand__name">MediTrack</span>
          <span class="landing-brand__clinic">HealthGate Medical</span>
        </span>
      </a>
      <nav class="landing-nav" aria-label="Main">
        <a class="landing-nav__link landing-nav__link--current" href="#top">Home</a>
        <a class="landing-nav__link" href="#services">Services</a>
        <a class="landing-nav__link" href="#how">How it works</a>
        <a class="landing-nav__link" href="#contact">Contact</a>
      </nav>
      <div class="landing-header__actions">
        <a class="landing-button landing-button--outline" href="/login">Log in</a>
        <a class="landing-button landing-button--primary" href="/book"><?= icon('plus', 16) ?> Book appointment</a>
      </div>
    </div>
  </header>

  <main>

    <section class="landing-container landing-hero" id="top">
      <div class="landing-hero__copy">
        <span class="landing-hero__address">No 115, Main Street, Athurugiriya</span>
        <h1 class="landing-hero__title">Book, track and manage your clinic visits online.</h1>
        <p class="landing-hero__text">Appointments, the live queue, your prescriptions and medical records. All in one patient account.</p>
        <div class="landing-hero__buttons">
          <a class="landing-button landing-button--primary landing-button--large" href="/book">Book appointment</a>
          <a class="landing-button landing-button--outline landing-button--large" href="/register">Create account</a>
        </div>
      </div>

      <div class="landing-hero__visual">
        <div class="landing-hero__logo-panel">
          <img class="landing-hero__logo" src="/assets/img/MediTrackLogo.png" alt="MediTrack logo" width="360" height="360">
        </div>
        <div class="landing-queue-card">
          <div class="landing-queue-card__top">
            <span class="landing-queue-card__label">Queue running now</span>
            <span class="landing-live-badge"><span class="landing-live-badge__dot"></span>Live</span>
          </div>
          <span class="landing-queue-card__number">#12</span>
          <div class="landing-queue-card__bottom">
            <span class="landing-queue-card__label">Your number</span>
            <span class="landing-queue-card__yours">#15 <span class="landing-queue-card__ahead">3 ahead</span></span>
          </div>
        </div>
      </div>
    </section>

    <?php
    $services = [
      ['image' => 'reception.jpg',   'alt' => 'Clinic reception',                     'icon' => 'calendar', 'tone' => 'blue',  'name' => 'Online booking',      'text' => 'Pick a doctor and a time'],
      ['image' => 'queue-phone.jpg', 'alt' => 'Checking the live queue on a phone',   'icon' => 'queue',    'tone' => 'green', 'name' => 'Live queue tracking', 'text' => 'See your number and arrive on time'],
      ['image' => 'medicines.jpg',   'alt' => 'Medicines',                            'icon' => 'pill',     'tone' => 'amber', 'name' => 'Online pharmacy',     'text' => 'Prescriptions and refills'],
      ['image' => 'records.jpg',     'alt' => 'Doctor reviewing records with a patient', 'icon' => 'file',  'tone' => 'grey',  'name' => 'Medical records',     'text' => 'Visits, tests and reports'],
    ];
    ?>
    <section class="landing-services" id="services">
      <div class="landing-container landing-services__inner">
        <div class="landing-section-head">
          <h2 class="landing-section-title">Our services</h2>
          <a class="landing-text-link" href="/login">Log in to your patient account <?= icon('arrowRight', 16) ?></a>
        </div>
        <div class="landing-services__grid">
          <?php foreach ($services as $service): ?>
            <div class="landing-service-card">
              <img class="landing-service-card__image" src="/assets/img/landing/<?= e($service['image']) ?>" alt="<?= e($service['alt']) ?>" width="360" height="220">
              <div class="landing-service-card__body">
                <span class="landing-service-card__icon landing-service-card__icon--<?= e($service['tone']) ?>"><?= icon($service['icon'], 22) ?></span>
                <span class="landing-service-card__words">
                  <span class="landing-service-card__name"><?= e($service['name']) ?></span>
                  <span class="landing-service-card__text"><?= e($service['text']) ?></span>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="landing-container landing-how" id="how">
      <h2 class="landing-section-title">How it works</h2>
      <div class="landing-steps">
        <div class="landing-step">
          <span class="landing-step__number">1</span>
          <span class="landing-step__name">Book a visit</span>
          <span class="landing-step__text">Choose your doctor and time slot.</span>
        </div>
        <div class="landing-step">
          <span class="landing-step__number">2</span>
          <span class="landing-step__name">Follow the live queue</span>
          <span class="landing-step__text">Come in when your number is close.</span>
        </div>
        <div class="landing-step">
          <span class="landing-step__number">3</span>
          <span class="landing-step__name">Consult and collect</span>
          <span class="landing-step__text">Get your medicines and records in your account.</span>
        </div>
      </div>
    </section>

    <section class="landing-container landing-contact" id="contact">
      <h2 class="landing-section-title">Contact us</h2>
      <div class="landing-contact__grid">
        <div class="landing-contact-card">
          <dl class="landing-contact-card__list">
            <div class="landing-contact-card__row">
              <dt class="landing-contact-card__label">Address</dt>
              <dd class="landing-contact-card__value">No 115, Main Street, Athurugiriya</dd>
            </div>
            <div class="landing-contact-card__row">
              <dt class="landing-contact-card__label">Telephone</dt>
              <dd class="landing-contact-card__value"><a href="tel:+94718274752">+94 71 827 4752</a></dd>
            </div>
            <div class="landing-contact-card__row">
              <dt class="landing-contact-card__label">Email</dt>
              <dd class="landing-contact-card__value"><a href="mailto:prasannagam@yahoo.com">prasannagam@yahoo.com</a></dd>
            </div>
            <div class="landing-contact-card__row">
              <dt class="landing-contact-card__label">Contact person</dt>
              <dd class="landing-contact-card__value">Dr. P.G.P.K. Gamage</dd>
            </div>
          </dl>
          <a class="landing-button landing-button--primary landing-button--large landing-button--full" href="/book">Book appointment</a>
        </div>

        <div class="landing-map">
          <img class="landing-map__image" src="/assets/img/landing/map.jpg" alt="Map showing HealthGate Medical on Athurugiriya Road" width="1510" height="1262">
          <a class="landing-map__directions" href="https://www.google.com/maps/search/?api=1&amp;query=No+115+Main+Street+Athurugiriya" target="_blank" rel="noopener">Get directions <?= icon('arrowRight', 15) ?></a>
          <span class="landing-map__credit">Map data © Google</span>
        </div>
      </div>
    </section>

  </main>

  <footer class="landing-container landing-footer">
    <div class="landing-footer__inner">
      <span>© 2026 HealthGate Medical</span>
    </div>
  </footer>

</body>

</html>