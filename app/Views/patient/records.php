<?php

declare(strict_types=1);

$title = 'Records';
$active = 'records';

require __DIR__ . '/header.php';
?>
<section id="records-list">
  <div class="rec-topline">
    <h1 class="rec-topline__title">My records</h1>
  </div>

  <div class="rec-filters">
    <button class="rec-filters__pill is-active" type="button">All doctors ▾</button>
    <button class="rec-filters__pill" type="button">Visits</button>
    <button class="rec-filters__pill" type="button">Pharmacy</button>
    <button class="rec-filters__pill" type="button">Labs</button>
  </div>

  <div class="section-lead">
    <h2 class="section-lead__title">Your visits</h2>
  </div>

  <div class="rec-card rec-card--clickable" data-open-record>
    <div class="rec-card__body">
      <div class="rec-card__title-row">
        <span class="rec-card__title">Hypertension review</span>
        <span class="rec-card__date">12 May 2026</span>
      </div>
      <div class="rec-card__meta inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></div>
    </div>
    <span class="btn btn--secondary btn--sm" style="pointer-events:none">Open record</span>
  </div>

  <div class="rec-card rec-card--clickable">
    <div class="rec-card__body">
      <div class="rec-card__title-row">
        <span class="rec-card__title">Gastritis</span>
        <span class="rec-card__date">03 Feb 2026</span>
      </div>
      <div class="rec-card__meta inline-parts"><span>Dr. Sample Doctor 2</span><span>ENT Surgeon</span></div>
    </div>
    <span class="btn btn--secondary btn--sm" style="pointer-events:none">Open record</span>
  </div>

  <div class="rec-card rec-card--clickable">
    <div class="rec-card__body">
      <div class="rec-card__title-row">
        <span class="rec-card__title">Viral fever</span>
        <span class="rec-card__date">18 Nov 2025</span>
      </div>
      <div class="rec-card__meta inline-parts"><span>Dr. Sample Doctor 3</span><span>Pediatrics</span></div>
    </div>
    <span class="btn btn--secondary btn--sm" style="pointer-events:none">Open record</span>
  </div>
</section>

<section id="records-detail" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-close-record><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Record - 12 May 2026</span>
    <span class="rec-tag">Hypertension review</span>
    <div style="margin-left:auto;display:flex;gap:var(--sp-4)">
      <button class="btn btn--secondary btn--sm" type="button">Print</button>
      <button class="btn btn--secondary btn--sm" type="button">Download PDF</button>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="row-between">
        <div class="record-detail-doctor">
          <span class="book-doc-head__avatar">D1</span>
          <div>
            <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></div>
            <div class="med-row__sub">12 May 2026, 10:40</div>
          </div>
        </div>
        <div style="display:flex;gap:var(--sp-4)">
          <span class="record-detail-vpill">BP 118/76</span>
          <span class="record-detail-vpill">58 kg</span>
          <span class="record-detail-vpill">BMI 23.5</span>
        </div>
      </div>
    </div>
  </div>

  <div class="record-detail-grid mt-7">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-block__label">Doctor notes &amp; diagnosis</div>
          <p>BP well controlled on Losartan 50 mg. No dizziness or headaches reported. Continue current dose, review monthly.</p>
          <div class="record-detail-diag">High blood pressure (ongoing)</div>
        </div>
      </div>

      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Prescription - RX-0871</h3>
          <span class="badge badge--success">Fully dispensed</span>
        </div>
        <div class="card__body">
          <div style="padding:var(--sp-4) 0">
            <div style="font-weight:600;font-size:var(--fs-md)">• Losartan 50 mg</div>
            <div class="med-row__sub" style="padding-left:var(--sp-6)">1 every morning, for 30 days</div>
          </div>
          <div style="padding:var(--sp-4) 0">
            <div style="font-weight:600;font-size:var(--fs-md)">• Atorvastatin 10 mg</div>
            <div class="med-row__sub" style="padding-left:var(--sp-6)">1 at night, for 30 days</div>
            <div class="med-row__sub" style="padding-left:var(--sp-6);color:var(--info)">Repeats every month</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="record-detail-block__label">Follow-up</div>
          <div class="row-between">
            <span>Blood pressure check with Dr. Sample Doctor 1 on 02 Aug 2026</span>
            <button class="link-btn" type="button">View booking</button>
          </div>
        </div>
      </div>
    </div>

    <div class="stack">
      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Invoice - INV-0102</h3>
          <span class="badge badge--success">Paid</span>
        </div>
        <div class="card__body">
          <div class="record-detail-inv-row"><span>Consultation with Dr. Sample Doctor 1</span><span class="mono">Rs. 2,500</span></div>
          <div class="record-detail-inv-row"><span>Pharmacy order RX-0871</span><span class="mono">Rs. 1,680</span></div>
          <div class="record-detail-inv-total"><span>Total</span><span class="mono">Rs. 4,180</span></div>
          <button class="btn btn--secondary btn--sm mt-5" type="button">Download receipt</button>
        </div>
      </div>

      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Files &amp; lab reports</h3>
          <button class="link-btn" type="button"><img class="icon" src="/assets/img/icons/upload.svg" alt="" width="13" height="13"> Upload</button>
        </div>
        <div class="card__body">
          <div class="record-detail-file">
            <span class="record-detail-file__icon"><img class="icon" src="/assets/img/icons/file.svg" alt="" width="18" height="18"></span>
            <div class="record-detail-file__body">
              <div class="record-detail-file__name">FBC report - Asiri Labs.pdf</div>
              <div class="record-detail-file__meta">Uploaded by you on 09 May 2026</div>
            </div>
            <button class="record-detail-file__view" type="button">View</button>
          </div>
          <div class="record-detail-file">
            <span class="record-detail-file__icon"><img class="icon" src="/assets/img/icons/file.svg" alt="" width="18" height="18"></span>
            <div class="record-detail-file__body">
              <div class="record-detail-file__name">Lipid panel.pdf</div>
              <div class="record-detail-file__meta">Attached by Dr. Sample Doctor 1 on 12 May 2026</div>
            </div>
            <button class="record-detail-file__view" type="button">View</button>
          </div>
          <div class="record-detail-file">
            <span class="record-detail-file__icon"><img class="icon" src="/assets/img/icons/file.svg" alt="" width="18" height="18"></span>
            <div class="record-detail-file__body">
              <div class="record-detail-file__name">ECG report.pdf</div>
              <div class="record-detail-file__meta">Attached by Dr. Sample Doctor 1 on 14 Jan 2026</div>
            </div>
            <button class="record-detail-file__view" type="button">View</button>
          </div>
          <p class="med-row__sub mt-5">Upload lab reports before a visit so your doctor can see them.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/assets/js/patient/records.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>