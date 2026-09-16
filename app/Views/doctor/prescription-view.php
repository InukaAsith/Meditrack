<?php

declare(strict_types=1);

$title  = 'Prescription';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<div class="mb-5">
  <a class="link-act" href="/staff/doctor/prescriptions"><?= icon('chevronLeft', 12) ?> Back to prescriptions</a>
</div>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Prescription RX-1042</h1>
    <div class="staff-head__sub">Issued 11 Jul 2026 · 09:41 · Dr. Sample Doctor 1</div>
  </div>
  <div class="staff-head__actions">
    <span class="badge badge--warning" style="align-self:center">Pending pickup</span>
    <a class="btn btn--primary btn--sm" href="/staff/doctor/prescription-edit"><?= icon('edit', 14) ?> Edit</a>
    <button class="btn btn--ghost btn--sm" type="button"><?= icon('print', 14) ?> Print</button>
    <button class="btn btn--secondary btn--sm" type="button">Reissue</button>
  </div>
</div>

<div class="consultation-rx-view">
  <div class="consultation-rx-meta">
    <div>
      <div class="consultation-rx-meta__label">Patient</div>
      <div class="consultation-rx-meta__value">Nimsith Wickrama</div>
    </div>
    <div>
      <div class="consultation-rx-meta__label">Patient ID</div>
      <div class="consultation-rx-meta__value">PT-0912</div>
    </div>
    <div>
      <div class="consultation-rx-meta__label">Age / Sex</div>
      <div class="consultation-rx-meta__value">46 · M · O+</div>
    </div>
    <div>
      <div class="consultation-rx-meta__label">Diagnosis</div>
      <div class="consultation-rx-meta__value"><code style="font-family:var(--font-mono);color:var(--primary)">J06.9</code> Acute upper respiratory infection</div>
    </div>
  </div>

  <div class="consultation-allergy-warn mb-6">
    <?= icon('alert', 16) ?>
    Allergy on file: Penicillin, Ibuprofen
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Medicines · 3</div>
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Medicine</th>
              <th>Dose</th>
              <th>Frequency</th>
              <th>Days</th>
              <th>Qty</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Azithromycin 500 mg</strong></td>
              <td>1 tab</td>
              <td>1× daily</td>
              <td>3</td>
              <td>3</td>
              <td class="text-muted">after meals</td>
            </tr>
            <tr>
              <td><strong>Ibuprofen 400 mg</strong>
                <br><span class="consultation-rx-item-flag">Known patient allergy</span>
              </td>
              <td>1 tab</td>
              <td>2× daily</td>
              <td>5</td>
              <td>10</td>
              <td class="text-muted">after food</td>
            </tr>
            <tr>
              <td><strong>Paracetamol 500 mg</strong></td>
              <td>1 tab</td>
              <td>6-hourly</td>
              <td>5</td>
              <td>20</td>
              <td class="text-muted">if fever</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="consultation-record-grid mt-7">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Follow-up &amp; pharmacy</div>
        <div style="font-size:var(--fs-sm);margin-bottom:var(--sp-4)">Follow-up: <strong>24 Jul 2026</strong></div>
        <div style="font-size:var(--fs-sm);color:var(--text-muted)">HealthGate Pharmacy · sent 09:41</div>
      </div>
    </div>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row"><span>Pharmacy invoice - PH-INV-1042</span></div>
        <div style="display:flex;justify-content:space-between;font-size:var(--fs-md);font-weight:800;padding:var(--sp-3) 0">
          <span>Total</span><span>Rs. 1,240</span>
        </div>
        <span class="badge badge--warning">Pending</span>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>