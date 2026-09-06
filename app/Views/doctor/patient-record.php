<?php

declare(strict_types=1);

$title  = 'Patient record';
$active = 'patient-history';

require __DIR__ . '/header.php';
?>
<div class="consultation-record-head">
  <div class="consultation-record-head__left">
    <a class="link-act" href="/staff/doctor/patient-detail" style="flex-shrink:0" title="Back to patient"><?= icon('chevronLeft', 14) ?></a>
    <span class="consultation-record__avatar" style="width:38px;height:38px;font-size:var(--fs-sm)">NJ</span>
    <div>
      <div class="consultation-record__name">Nimsith Wickrama - record 12 May 2026
        <span class="consultation-allergy-chip ml-3">Aspirin</span>
      </div>
      <div class="consultation-record__code">PT-0912 · 45 y · M · A+ · visit 10:40–10:56</div>
    </div>
  </div>
  <div class="consultation-record__actions">
    <button class="btn btn--ghost btn--sm" type="button"><?= icon('print', 14) ?> Print</button>
    <button class="btn btn--secondary btn--sm" type="button"><?= icon('file', 14) ?> Copy to today's note</button>
  </div>
</div>

<div class="consultation-record-grid">
  <div class="consultation-record-col">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Consultation note &amp; diagnosis</span>
          <span style="text-transform:none;letter-spacing:0;font-weight:400;font-size:var(--fs-xs);color:var(--text-subtle)">Dr. Sample Doctor 1 · Finalized</span>
        </div>
        <p style="font-size:var(--fs-sm);line-height:1.55;margin:0 0 var(--sp-4)">Hypertension review. BP well controlled on Losartan 50 mg - no dizziness, no headaches. Discussed salt intake and exercise. Continue current dose, review monthly.</p>
        <div style="font-size:var(--fs-sm);margin-bottom:var(--sp-3)"><span class="badge badge--muted"><code style="font-family:var(--font-mono)">I10</code></span> Essential hypertension - ongoing</div>
        <div style="font-size:var(--fs-sm);color:var(--text-muted);margin-top:var(--sp-3)">Vitals: BP 118/76 · 58.0 kg · BMI 23.5</div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Prescription - RX-0871</span>
          <span class="label-note">
            <span class="badge badge--success">Fully dispensed · 12 May 11:20</span>
            <span class="badge badge--info">Recurring</span>
          </span>
        </div>
        <div class="consultation-rx-med-list">
          <div class="consultation-rx-med">
            <span class="consultation-rx-med__dot"></span>
            <div>
              <div class="consultation-rx-med__drug">Losartan 50 mg</div>
              <div class="consultation-rx-med__detail">1 at night · 30 days · qty 30 · batch LSN-2205</div>
            </div>
          </div>
          <div class="consultation-rx-med">
            <span class="consultation-rx-med__dot"></span>
            <div>
              <div class="consultation-rx-med__drug">Atorvastatin 10 mg</div>
              <div class="consultation-rx-med__detail">1 daily · 30 days · qty 30 · batch ATV-1104</div>
            </div>
          </div>
        </div>
        <div style="font-size:var(--fs-xs);color:var(--text-muted);margin-top:var(--sp-4)">Pharmacist note: Advised to take losartan at night.</div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Follow-up</div>
        <div style="display:flex;align-items:center;justify-content:space-between;gap:var(--sp-5);flex-wrap:wrap">
          <div>
            <div style="font-size:var(--fs-md);font-weight:600">BP recheck - 02 Aug 2026 (Booked)</div>
            <div style="font-size:var(--fs-xs);color:var(--text-muted);margin-top:var(--sp-1)">Reminder scheduled · APT-1120</div>
          </div>
          <a class="btn btn--secondary btn--sm" href="/staff/doctor/schedule">Open booking</a>
        </div>
      </div>
    </div>
  </div>

  <div class="consultation-record-col">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Invoice - INV-0102</span>
          <span class="badge badge--success label-note">Paid · cash</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:var(--fs-sm);margin-bottom:var(--sp-4)">
          <span class="text-muted">Consultation</span><span>Rs. 2,500</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:var(--fs-sm);margin-bottom:var(--sp-4)">
          <span class="text-muted">Pharmacy · RX-0871</span><span>Rs. 1,680</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:var(--fs-lg);font-weight:800;padding-top:var(--sp-4);border-top:1px solid var(--border)">
          <span>Total</span><span>Rs. 4,180</span>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Files &amp; lab reports</span>
          <button class="btn btn--ghost btn--sm label-note" type="button">Attach</button>
        </div>
        <div class="consultation-file-list">
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">FBC report - Asiri Labs.pdf</div>
              <div class="consultation-file__meta">Uploaded by patient · 09 Jul 2026</div>
            </div>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View</a>
          </div>
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">Lipid panel.pdf</div>
              <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 12 May 2026</div>
            </div>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View</a>
          </div>
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">ECG - 12-lead.pdf</div>
              <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 14 Jan 2026</div>
            </div>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View</a>
          </div>
        </div>
      </div>
    </div>

    <div class="audit-note">
      <?= icon('records', 16) ?>
      <span>Official consultation record. Modifications and access are recorded in the audit log.</span>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>