<?php

declare(strict_types=1);

$title  = 'Patient record';
$active = 'patient-history';

require __DIR__ . '/header.php';
?>
<div class="mb-5">
  <a class="link-act" href="/staff/doctor/patient-history"><?= icon('chevronLeft', 12) ?> Back to patient history</a>
</div>

<div class="consultation-ph-layout">
  <div>
    <div class="consultation-patient-card">
      <div class="consultation-patient-card__head">
        <span class="consultation-patient-card__avatar">NW</span>
        <div>
          <div class="consultation-patient-card__name">Nimsith Wickrama</div>
          <div class="consultation-patient-card__code">PT-0912 · 45y · M · A+</div>
        </div>
      </div>
      <div style="display:flex;gap:var(--sp-3);flex-wrap:wrap">
        <span class="consultation-allergy-chip">Aspirin</span>
        <span class="badge badge--muted">Atorvastatin 20 mg · 1×daily</span>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div data-subtabs>
          <div class="consultation-subtabs">
            <button class="consultation-subtab is-active" type="button" data-subtab="all">All visits</button>
            <button class="consultation-subtab" type="button" data-subtab="dx">Diagnoses</button>
            <button class="consultation-subtab" type="button" data-subtab="rx">Prescriptions</button>
            <button class="consultation-subtab" type="button" data-subtab="labs">Labs</button>
          </div>
          <div data-subpanel="all">
            <div class="timeline">
              <div class="timeline__entry">
                <span class="timeline__dot"></span>
                <div class="timeline__content">
                  <div class="timeline__row">
                    <span class="timeline__title">11 Jul 2026 - Follow-up</span>
                    <span class="badge badge--primary-strong">in progress</span>
                  </div>
                  <div class="timeline__body">BP review - adjusted Losartan dose.</div>
                  <div style="margin-top:var(--sp-2);font-size:var(--fs-xs);color:var(--text-muted)">Dr. Sample Doctor 1</div>
                </div>
              </div>
              <div class="timeline__entry">
                <span class="timeline__dot"></span>
                <div class="timeline__content">
                  <div class="timeline__row">
                    <span class="timeline__title">27 Jun 2026 - Follow-up</span>
                    <span class="badge badge--muted">Completed</span>
                  </div>
                  <div class="timeline__body">Lab review: HbA1c improved to 6.4%.</div>
                  <div style="margin-top:var(--sp-2);font-size:var(--fs-xs);color:var(--text-muted)">Dr. Sample Doctor 1</div>
                  <a href="/staff/doctor/patient-record" class="link-act" style="margin-top:var(--sp-2);display:inline-block">Open record</a>
                </div>
              </div>
              <div class="timeline__entry">
                <span class="timeline__dot"></span>
                <div class="timeline__content">
                  <div class="timeline__row">
                    <span class="timeline__title">13 Jun 2026 - Referral</span>
                    <span class="badge badge--muted">Completed</span>
                  </div>
                  <div class="timeline__body">Pediatric consult for child (linked PT-1422).</div>
                  <div style="margin-top:var(--sp-2);font-size:var(--fs-xs);color:var(--text-muted)">Dr. Sample Doctor 3</div>
                  <a href="/staff/doctor/patient-record" class="link-act" style="margin-top:var(--sp-2);display:inline-block">Open record</a>
                </div>
              </div>
              <div class="timeline__entry">
                <span class="timeline__dot"></span>
                <div class="timeline__content">
                  <div class="timeline__row">
                    <span class="timeline__title">01 Jun 2026 - New</span>
                    <span class="badge badge--muted">Completed</span>
                  </div>
                  <div class="timeline__body">Initial workup - FBS, lipids ordered. Started Metformin.</div>
                  <div style="margin-top:var(--sp-2);font-size:var(--fs-xs);color:var(--text-muted)">Dr. Sample Doctor 1</div>
                  <a href="/staff/doctor/patient-record" class="link-act" style="margin-top:var(--sp-2);display:inline-block">Open record</a>
                </div>
              </div>
              <div class="timeline__entry">
                <span class="timeline__dot"></span>
                <div class="timeline__content">
                  <div class="timeline__row">
                    <span class="timeline__title">15 May 2026 - Follow-up</span>
                    <span class="badge badge--muted">Completed</span>
                  </div>
                  <div class="timeline__body">Routine check-up, all clear.</div>
                  <div style="margin-top:var(--sp-2);font-size:var(--fs-xs);color:var(--text-muted)">Dr. Sample Doctor 1</div>
                  <a href="/staff/doctor/patient-record" class="link-act" style="margin-top:var(--sp-2);display:inline-block">Open record</a>
                </div>
              </div>
            </div>
          </div>
          <div data-subpanel="dx" hidden>
            <div class="data-table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>ICD-10</th>
                    <th>Diagnosis</th>
                    <th>Doctor</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>11 Jul 2026</td>
                    <td><code>I10</code></td>
                    <td>Essential hypertension - ongoing</td>
                    <td>Dr. Sample Doctor 1</td>
                  </tr>
                  <tr>
                    <td>01 Jun 2026</td>
                    <td><code>E11.9</code></td>
                    <td>Type 2 diabetes mellitus</td>
                    <td>Dr. Sample Doctor 1</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div data-subpanel="rx" hidden>
            <div class="data-table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Rx</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th class="data-table__actions"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="data-table__row">
                    <td><code>RX-0871</code></td>
                    <td>12 May 2026</td>
                    <td>1</td>
                    <td><span class="badge badge--success">Fully dispensed</span></td>
                    <td class="data-table__actions"><a class="link-act" href="/staff/doctor/prescription-view">View</a></td>
                  </tr>
                  <tr class="data-table__row">
                    <td><code>RX-0765</code></td>
                    <td>03 Feb 2026</td>
                    <td>2</td>
                    <td><span class="badge badge--success">Fully dispensed</span></td>
                    <td class="data-table__actions"><a class="link-act" href="/staff/doctor/prescription-view">View</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div data-subpanel="labs" hidden>
            <div class="consultation-lab-list">
              <div class="consultation-lab">
                <div class="consultation-lab__name">Lipid panel · 12 May</div><a class="link-act" href="/staff/doctor/lab-results">View</a>
              </div>
              <div class="consultation-lab">
                <div class="consultation-lab__name">FBC · 12 May</div><a class="link-act" href="/staff/doctor/lab-results">View</a>
              </div>
              <div class="consultation-lab">
                <div class="consultation-lab__name">ECG · 14 Jan</div><a class="link-act" href="/staff/doctor/lab-results">View</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Ongoing treatments</div>
        <div class="consultation-treatment-list">
          <div class="consultation-treatment">
            <div class="consultation-treatment__drug">Losartan 50 mg · daily <span class="badge badge--success ml-2">Adherent</span></div>
            <div class="consultation-treatment__dose">Since Jan 2026 · review monthly · next 02 Aug</div>
          </div>
          <div class="consultation-treatment">
            <div class="consultation-treatment__drug">Azithromycin course · 3 d <span class="badge badge--muted ml-2">Day 1 of 3</span></div>
            <div class="consultation-treatment__dose">Started today · follow-up 24 Jul if not resolved</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Lab results on file</div>
        <div class="consultation-lab-list">
          <div class="consultation-lab">
            <div class="consultation-lab__name">FBS</div>
            <div class="consultation-lab__date">08 Jul</div>
            <a class="link-act" href="/staff/doctor/lab-results">View</a>
          </div>
          <div class="consultation-lab">
            <div class="consultation-lab__name">HbA1c</div>
            <div class="consultation-lab__date">08 Jul</div>
            <a class="link-act" href="/staff/doctor/lab-results">View</a>
          </div>
          <div class="consultation-lab">
            <div class="consultation-lab__name">Lipid Panel</div>
            <div class="consultation-lab__date">15 Jun</div>
            <a class="link-act" href="/staff/doctor/lab-results">View</a>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Quick actions</div>
        <div style="display:flex;flex-direction:column;gap:var(--sp-3)">
          <a class="btn btn--secondary btn--sm btn--block" href="/staff/doctor/prescriptions">View prescriptions</a>
          <button class="btn btn--ghost btn--sm btn--block" type="button"><?= icon('print', 14) ?> Print summary</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/patient-history.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>