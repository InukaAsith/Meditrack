<?php

declare(strict_types=1);

$title = 'Send a photo';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<header class="page-hero page-hero--violet">
  <div class="page-hero__copy">
    <span class="page-hero__eyebrow">photo prescription</span>
    <h1 class="page-hero__title">Upload a photo of your prescription</h1>
    <p class="page-hero__text">Pharmacist will see and prepare it</p>
  </div>

</header>

<a class="book-back" href="/app/prescriptions"><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="15" height="15">Back to pharmacy</a>

<div class="photo-flow">
  <div class="dropzone-wrap">
    <label class="dropzone">
      <span class="dropzone__icon"><img class="icon" src="/assets/img/icons/upload.svg" alt="" width="24" height="24"></span>
      <span class="dropzone__label">Upload photo</span>
      <input type="file" accept="image/*" hidden>
    </label>
  </div>

  <ol class="photo-steps">
    <li class="photo-step">
      <span class="photo-step__num">1</span>
      <span class="photo-step__text"><strong>Upload the photo</strong></span>
    </li>
    <li class="photo-step">
      <span class="photo-step__num">2</span>
      <span class="photo-step__text"><strong>Pharmacy will prepare and Alert you</strong></span>
    </li>
    <li class="photo-step">
      <span class="photo-step__num">3</span>
      <span class="photo-step__text"><strong>Pay and Collect</strong></span>
    </li>
  </ol>
</div>

<div class="help-note">
  <span class="help-note__icon"><img class="icon" src="/assets/img/icons/shield.svg" alt="" width="16" height="16"></span>
  <span>Only the pharmacist can see this photo.</span>
</div>
<?php require __DIR__ . '/footer.php'; ?>