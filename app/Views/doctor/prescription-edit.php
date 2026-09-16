<?php

declare(strict_types=1);

$title  = 'Edit prescription';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<div class="mb-5">
  <a class="link-act" href="/staff/doctor/prescription-view"><?= icon('chevronLeft', 12) ?> Back to prescription</a>
</div>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Edit prescription RX-1042</h1>
    <div class="staff-head__sub">Nimsith Wickrama · PT-0912</div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--ghost btn--sm" href="/staff/doctor/prescription-view">Cancel</a>
    <button class="btn btn--primary btn--sm" type="button">Save changes</button>
  </div>
</div>

<div class="consultation-rx-view">
  <div class="consultation-allergy-warn mb-6">
    <?= icon('alert', 16) ?>
    Allergy on file: Penicillin, Ibuprofen
  </div>

  <div class="card mb-7">
    <div class="card__body">
      <div class="staff-eyebrow">Diagnosis (ICD-10)</div>
      <div class="consultation-diagnosis" id="diagnosis-list">
        <div class="consultation-diagnosis__row">
          <input class="consultation-diagnosis__code" type="text" value="J06.9">
          <input class="consultation-diagnosis__desc" type="text" value="Acute upper respiratory infection">
        </div>
      </div>
      <button class="btn btn--ghost btn--sm mt-4" type="button" id="add-diagnosis">Add diagnosis</button>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="consultation-rx-section">
        <div class="consultation-rx-header">
          <span class="consultation-rx-header__title"><?= icon('pill', 16) ?> Medicines - RX-1042</span>
        </div>
        <div class="consultation-rx-thead">
          <span>Medicine</span><span>Dose</span><span>Frequency</span><span>Days</span><span>Qty</span><span></span>
        </div>
        <div class="consultation-rx-lines" id="rx-lines">
          <div class="consultation-rx-line">
            <input type="text" value="Azithromycin 500 mg" placeholder="Medicine name">
            <input type="text" value="1 tab" placeholder="Dose">
            <select>
              <option>1× daily</option>
              <option>1× daily</option>
              <option>2× daily</option>
              <option>3× daily</option>
              <option>PRN</option>
            </select>
            <input type="number" value="3" style="width:60px">
            <input type="number" value="3" style="width:60px">
            <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
          </div>
          <div class="consultation-rx-line">
            <input type="text" value="Ibuprofen 400 mg" placeholder="Medicine name">
            <input type="text" value="1 tab" placeholder="Dose">
            <select>
              <option>2× daily</option>
              <option>1× daily</option>
              <option>2× daily</option>
              <option>3× daily</option>
              <option>PRN</option>
            </select>
            <input type="number" value="5" style="width:60px">
            <input type="number" value="10" style="width:60px">
            <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
          </div>
          <div class="consultation-rx-line">
            <input type="text" value="Paracetamol 500 mg" placeholder="Medicine name">
            <input type="text" value="1 tab" placeholder="Dose">
            <select>
              <option>6-hourly</option>
              <option>1× daily</option>
              <option>2× daily</option>
              <option>3× daily</option>
              <option>PRN</option>
            </select>
            <input type="number" value="5" style="width:60px">
            <input type="number" value="20" style="width:60px">
            <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
          </div>
        </div>
        <button class="consultation-rx-add" type="button" id="add-rx-line">Add medication</button>
      </div>

      <div class="mt-6">
        <div class="consultation-avail__label mb-3">Follow-up</div>
        <input type="date" value="2026-07-24" style="padding:var(--sp-3) var(--sp-4);border:1px solid var(--border);border-radius:var(--r-sm);font-size:var(--fs-sm)">
      </div>

      <div style="display:flex;gap:var(--sp-4);margin-top:var(--sp-6);flex-wrap:wrap">
        <button class="btn btn--primary" type="button">Save changes</button>
        <a class="btn btn--ghost" href="/staff/doctor/prescription-view">Cancel</a>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/current-patient.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>