<?php

declare(strict_types=1);

$title = 'Clinic Configuration';
$active = 'clinic-configuration';

$clinicFee = 0.00;

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Clinic configuration</h1>
    <div class="staff-head__sub">Charges the clinic adds to every visit</div>
  </div>
</div>

<section class="card">
  <div class="card__head">
    <h3 class="card__title">Clinic fee</h3>
  </div>
  <div class="card__body">
    <div class="field" style="max-width:320px">
      <label class="field__label" for="clinic-fee">Clinic fee per visit <span class="field__req" aria-hidden="true">*</span></label>
      <div class="money-input">
        <span class="money-input__prefix">Rs.&nbsp;</span>
        <input class="field__input" id="clinic-fee" type="number" min="0" step="0.01" value="<?= e(number_format($clinicFee, 2, '.', '')) ?>">
      </div>
      <span class="field__desc">Added on top of the doctor's consultation fee for each appointment.</span>
    </div>
    <div class="form-actions">
      <button class="btn btn--primary" type="button" disabled>Save clinic fee</button>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
