<?php

declare(strict_types=1);

$title = 'Profile';
$active = 'profile';

require __DIR__ . '/header.php';

$profileQrUrl = quickchart_qr_url($patientCode, 160);
?>
<div class="profile-layout">
  <aside class="profile-side">
    <div class="card">
      <div class="profile-id">
        <div class="profile-photo">
          <img class="profile-photo__img" src="<?= e($patientPhoto) ?>" alt="Photo of <?= e($patientName) ?>" width="160" height="160">
          <div class="profile-photo__actions">
            <button class="profile-photo__btn" type="button" aria-label="Change photo" title="Change photo">
              <img class="icon" src="/assets/img/icons/edit.svg" alt="" width="15" height="15">
            </button>
            <button class="profile-photo__btn profile-photo__btn--danger" type="button" aria-label="Remove photo" title="Remove photo">
              <img class="icon" src="/assets/img/icons/trash.svg" alt="" width="15" height="15">
            </button>
          </div>
        </div>
        <h1 class="profile-id__name"><?= e($patientName) ?></h1>
        <span class="profile-id__code"><?= e($patientCode) ?></span>
        <span class="profile-id__since">Patient since <?= e($patientMemberSince) ?></span>
      </div>
    </div>

    <div class="card">
      <div class="patient-card-qr">
        <div class="patient-card-qr__qr">
          <img src="<?= e($profileQrUrl) ?>" alt="QR code for <?= e($patientCode) ?>" loading="lazy" decoding="async">
        </div>
        <div class="patient-card-qr__text">
          <strong>My patient card</strong>
          <div class="patient-card-qr__actions">
            <button class="link-btn" type="button">Show full screen</button>
            <button class="link-btn" type="button">Print</button>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <div class="profile-main">
    <section class="card">
      <div class="card__head">
        <h3 class="card__title">Personal details</h3>
        <button class="btn btn--secondary btn--sm" type="button">Edit</button>
      </div>
      <div class="card__body">
        <dl class="profile-details">
          <div class="profile-details__row">
            <dt>Full name</dt>
            <dd><?= e($patientName) ?></dd>
          </div>
          <div class="profile-details__row">
            <dt>NIC</dt>
            <dd class="mono"><?= e($patientNic) ?></dd>
          </div>
          <div class="profile-details__row">
            <dt>Date of birth</dt>
            <dd><?= e($signedIn['date_of_birth'] ? date('d M Y', strtotime($signedIn['date_of_birth'])) : '-') ?></dd>
          </div>
          <div class="profile-details__row">
            <dt>Blood type</dt>
            <dd><?= e($patientBloodType) ?></dd>
          </div>
          <div class="profile-details__row">
            <dt>Mobile</dt>
            <dd class="mono"><?= e($patientMobile) ?></dd>
          </div>
          <div class="profile-details__row">
            <dt>Email</dt>
            <dd><?= e($signedIn['email'] ?? '-') ?></dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="card">
      <div class="card__head">
        <h3 class="card__title">Emergency contact</h3>
        <button class="btn btn--secondary btn--sm" type="button">Edit</button>
      </div>
      <div class="card__body">
        <dl class="profile-details">
          <div class="profile-details__row">
            <dt>Name</dt>
            <dd>Nimsith Wickrama</dd>
          </div>
          <div class="profile-details__row">
            <dt>Mobile</dt>
            <dd class="mono">+94 71 555 2201</dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="card">
      <div class="card__head">
        <h3 class="card__title">Allergies</h3>
        <button class="btn btn--secondary btn--sm" type="button">Add</button>
      </div>
      <div class="card__body">
        <div class="rec-tags mb-5">
          <span class="allergy-tag">Penicillin
            <button type="button" aria-label="Remove Penicillin"><img class="icon" src="/assets/img/icons/close.svg" alt="" width="12" height="12"></button>
          </span>
          <span class="allergy-tag">Ibuprofen
            <button type="button" aria-label="Remove Ibuprofen"><img class="icon" src="/assets/img/icons/close.svg" alt="" width="12" height="12"></button>
          </span>
        </div>
      </div>
    </section>

    <section class="card">
      <div class="card__head">
        <h3 class="card__title">Account</h3>
      </div>
      <div class="card__body">
        <div class="profile-action">
          <div class="profile-action__text">
            <strong>Change Password</strong>
          </div>
          <button class="btn btn--secondary btn--sm" type="button">Change password</button>
        </div>
        <div class="profile-action">
          <div class="profile-action__text">
            <strong>Sign out</strong>
          </div>
          <a class="btn btn--secondary btn--sm" href="/logout">Sign out</a>
        </div>
        <div class="profile-action">
          <div class="profile-action__text">
            <strong>Delete account</strong>
          </div>
          <button class="btn btn--soft-danger btn--sm" type="button">Delete account</button>
        </div>
      </div>
    </section>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>