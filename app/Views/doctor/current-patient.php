<?php

declare(strict_types=1);

$title  = 'Current patient';
$active = 'current-patient';

require __DIR__ . '/header.php';
?>
<div class="consultation-current-card mb-6">
  <div class="consultation-current-card__head mb-0">
    <div class="consultation-current-card__patient">
      <span class="consultation-current-card__avatar">KI</span>
      <div>
        <div class="consultation-current-card__name">K.A. Inuka Asith</div>
        <div class="consultation-current-card__code">58y · F · PT-1088 · NIC 687301245V · O+ · +94 77 234 5612</div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:var(--sp-5);flex-wrap:wrap">
      <span class="consultation-allergy-chip">Penicillin</span>
      <span class="consultation-allergy-chip consultation-allergy-chip--moderate">Ibuprofen</span>
      <div class="consultation-current-card__timer" id="consult-timer">
        <?= icon('clock', 16) ?><span id="timer-display">07:12</span>
      </div>
      <button class="btn btn--success btn--sm" type="button">Complete consultation</button>
    </div>
  </div>
</div>

<div data-tab-group>
  <div class="consultation-ws-tabs">
    <button class="consultation-ws-tab is-active" type="button" data-tab="current">Current consultation</button>
    <button class="consultation-ws-tab" type="button" data-tab="records">Past records <span class="consultation-ws-tab__count">3</span></button>
    <button class="consultation-ws-tab" type="button" data-tab="labs">Lab reports <span class="consultation-ws-tab__count consultation-ws-tab__count--new">1 new</span></button>
  </div>

  <div data-tab-panel="current">
    <div class="consultation-workspace">
      <div class="consultation-ws-panel">
        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow">Vitals <span class="label-note">· Recorded 09:26</span></div>
            <div class="consultation-vitals">
              <div class="consultation-vital consultation-vital--warning">
                <div class="consultation-vital__label">BP</div>
                <div class="consultation-vital__value">130/85 mmHg</div>
              </div>
              <div class="consultation-vital">
                <div class="consultation-vital__label">Pulse</div>
                <div class="consultation-vital__value">78 bpm</div>
              </div>
              <div class="consultation-vital">
                <div class="consultation-vital__label">Temp</div>
                <div class="consultation-vital__value">37.1 °C</div>
              </div>
              <div class="consultation-vital">
                <div class="consultation-vital__label">SpO₂</div>
                <div class="consultation-vital__value">97%</div>
              </div>
              <div class="consultation-vital">
                <div class="consultation-vital__label">Weight</div>
                <div class="consultation-vital__value">62 kg</div>
              </div>
              <div class="consultation-vital">
                <div class="consultation-vital__label">BMI</div>
                <div class="consultation-vital__value">24.8</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow staff-eyebrow--row">
              <span>Queue</span>
              <a href="/staff/doctor/queue">View all</a>
            </div>
            <div class="consultation-upnext">
              <div class="consultation-upnext__row">
                <span class="consultation-upnext__pos">1</span>
                <span class="consultation-upnext__name">K. Ashan Charuka</span>
                <span class="consultation-upnext__eta">~10:00</span>
              </div>
              <div class="consultation-upnext__row">
                <span class="consultation-upnext__pos">2</span>
                <span class="consultation-upnext__name">Sandanu Dulmeth</span>
                <span class="consultation-upnext__eta">~10:15</span>
              </div>
              <div class="consultation-upnext__row">
                <span class="consultation-upnext__pos">3</span>
                <span class="consultation-upnext__name">G. G. Mithun Majika</span>
                <span class="consultation-upnext__eta">~10:30</span>
              </div>
              <div class="consultation-upnext__row">
                <span class="consultation-upnext__pos">4</span>
                <span class="consultation-upnext__name">Nimsith Wickrama</span>
                <span class="consultation-upnext__eta">~10:45</span>
              </div>
              <div class="consultation-upnext__row">
                <span class="consultation-upnext__pos">5</span>
                <span class="consultation-upnext__name">M. L. Omindu Gunathilaka</span>
                <span class="consultation-upnext__eta">~11:00</span>
              </div>
            </div>
          </div>
        </div>

        <div class="consultation-avail__info">
          New lab report available: <strong>FBC report</strong> (09 Jul).
        </div>
      </div>

      <div class="consultation-ws-panel">
        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow">Notes &amp; diagnosis</div>
            <textarea class="consultation-notes-area" rows="5" placeholder="Type consultation notes here…" aria-label="Consultation notes">Fever 2 days, sore throat, mild headache. Chest clear. Throat erythematous, no exudate.</textarea>
            <div style="display:flex;gap:var(--sp-6);flex-wrap:wrap;margin-top:var(--sp-5)">
              <div style="flex:1;min-width:220px">
                <div class="consultation-avail__label mb-3">Diagnosis (ICD-10)</div>
                <div class="consultation-diagnosis" id="diagnosis-list">
                  <div class="consultation-diagnosis__row">
                    <input class="consultation-diagnosis__code" type="text" placeholder="Code" value="J06.9">
                    <input class="consultation-diagnosis__desc" type="text" placeholder="Description" value="Acute upper respiratory infection">
                  </div>
                </div>
                <button class="btn btn--ghost btn--sm mt-4" type="button" id="add-diagnosis">Add diagnosis</button>
              </div>
              <div style="flex:1;min-width:180px">
                <div class="consultation-avail__label mb-3">Follow-up</div>
                <div style="display:flex;gap:var(--sp-4);align-items:center;flex-wrap:wrap">
                  <input type="date" value="2026-07-24" style="padding:var(--sp-3) var(--sp-4);border:1px solid var(--border);border-radius:var(--r-sm);font-size:var(--fs-sm)">
                  <span class="badge badge--success">Scheduled</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__body">
            <div class="consultation-rx-section">
              <div class="consultation-rx-header">
                <span class="consultation-rx-header__title"><?= icon('pill', 16) ?> Prescription - RX-1042</span>
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
                    <option>2× daily</option>
                    <option>3× daily</option>
                    <option>PRN</option>
                  </select>
                  <input type="number" value="3" class="input-qty">
                  <input type="number" value="3" class="input-qty">
                  <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
                </div>
                <div class="consultation-rx-line">
                  <input type="text" value="Ibuprofen 400 mg" placeholder="Medicine name">
                  <input type="text" value="1 tab" placeholder="Dose">
                  <select>
                    <option>2× daily</option>
                    <option>1× daily</option>
                    <option>3× daily</option>
                    <option>PRN</option>
                  </select>
                  <input type="number" value="5" class="input-qty">
                  <input type="number" value="10" class="input-qty">
                  <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
                </div>
                <div class="consultation-rx-line">
                  <input type="text" value="Paracetamol 500 mg" placeholder="Medicine name">
                  <input type="text" value="1 tab" placeholder="Dose">
                  <select>
                    <option>6-hourly</option>
                    <option>1× daily</option>
                    <option>2× daily</option>
                    <option>PRN</option>
                  </select>
                  <input type="number" value="5" class="input-qty">
                  <input type="number" value="20" class="input-qty">
                  <button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>
                </div>
              </div>
              <button class="consultation-rx-add" type="button" id="add-rx-line">Add medicine</button>
            </div>
            <div class="consultation-config-notice consultation-config-notice--amber" style="border-radius:var(--r-sm);margin-top:var(--sp-4)">
              <?= icon('alert', 14) ?>
              <span><strong>Ibuprofen</strong> is on this patient's allergy list.
                <button class="btn btn--danger btn--xs ml-3" type="button">Remove</button>
                <button class="btn btn--ghost btn--xs" type="button">Keep anyway</button>
              </span>
            </div>
            <div style="display:flex;gap:var(--sp-4);margin-top:var(--sp-5);flex-wrap:wrap;align-items:center">
              <button class="btn btn--primary" type="button">Finalize prescription</button>
            </div>
          </div>
        </div>
      </div>

      <div class="consultation-ws-panel">
        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow staff-eyebrow--row"><span>Allergies</span>
              <button class="btn btn--ghost btn--sm" type="button">Add</button>
            </div>
            <div class="consultation-allergy-list">
              <span class="consultation-allergy-chip">Penicillin <span style="font-weight:400;margin-left:var(--sp-2)">High</span></span>
              <span class="consultation-allergy-chip consultation-allergy-chip--moderate">Ibuprofen <span style="font-weight:400;margin-left:var(--sp-2)">Moderate</span></span>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow staff-eyebrow--row"><span>Ongoing treatments</span>
              <button class="btn btn--ghost btn--sm" type="button">Add</button>
            </div>
            <div class="consultation-treatment-list">
              <div class="consultation-treatment">
                <div class="consultation-treatment__drug">Metformin 500 mg</div>
                <div class="consultation-treatment__dose">1×daily · morning · since Jan 2025</div>
              </div>
              <div class="consultation-treatment">
                <div class="consultation-treatment__drug">Losartan 50 mg</div>
                <div class="consultation-treatment__dose">1×daily · evening · since Mar 2025</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__body">
            <div class="staff-eyebrow staff-eyebrow--row"><span>Lab reports</span>
              <button class="btn btn--ghost btn--sm" type="button">Attach</button>
            </div>
            <div class="consultation-lab-list">
              <div class="consultation-lab">
                <div class="consultation-lab__name">FBS <span class="consultation-lab__new">NEW</span></div>
                <div class="consultation-lab__result">112 mg/dL</div>
                <div class="consultation-lab__date">08 Jul</div>
              </div>
              <div class="consultation-lab">
                <div class="consultation-lab__name">HbA1c <span class="consultation-lab__new">NEW</span></div>
                <div class="consultation-lab__result consultation-lab__result--warning">6.8%</div>
                <div class="consultation-lab__date">08 Jul</div>
              </div>
              <div class="consultation-lab">
                <div class="consultation-lab__name">Lipid Panel</div>
                <div class="consultation-lab__result">Normal</div>
                <div class="consultation-lab__date">15 Jun</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div data-tab-panel="records" hidden>
    <div class="card">
      <div class="card__body">
        <div data-subtabs>
          <div class="consultation-subtabs">
            <button class="consultation-subtab is-active" type="button" data-subtab="visits">Past records</button>
            <button class="consultation-subtab" type="button" data-subtab="rx">Prescriptions</button>
          </div>
          <div data-subpanel="visits">
            <div class="consultation-rec-entry">
              <div class="consultation-rec-entry__head">
                <span class="consultation-rec-entry__date">Today · 11 Jul 2026 · Follow-up</span>
                <span class="badge badge--primary-strong">in progress</span>
              </div>
              <div class="consultation-rec-entry__summary">Fever 2 days, sore throat. Dx J06.9 Acute URI. Rx RX-1042 pending at pharmacy.</div>
              <div class="consultation-rec-entry__meta">
                <span>Dr. Sample Doctor 1</span>
                <span>Dx <code>J06.9</code></span>
                <span>Rx <code>RX-1042</code></span>
              </div>
            </div>
            <div class="consultation-rec-entry">
              <div class="consultation-rec-entry__head">
                <span class="consultation-rec-entry__date">12 May 2026 · Follow-up</span>
                <a class="link-act" href="/staff/doctor/patient-record">Open record</a>
              </div>
              <div class="consultation-rec-entry__summary">Hypertension review - BP controlled on Losartan 50 mg. Continue, review monthly.</div>
              <div class="consultation-rec-entry__meta">
                <span>Dr. Sample Doctor 1</span>
                <span>Dx <code>I10</code></span>
                <span>Rx <code>RX-0871</code></span>
              </div>
            </div>
            <div class="consultation-rec-entry">
              <div class="consultation-rec-entry__head">
                <span class="consultation-rec-entry__date">03 Feb 2026 · New</span>
                <a class="link-act" href="/staff/doctor/patient-record">Open record</a>
              </div>
              <div class="consultation-rec-entry__summary">Epigastric pain - Dx gastritis. Omeprazole 20 mg × 14 d. Resolved at follow-up.</div>
              <div class="consultation-rec-entry__meta">
                <span>Dr. Sample Doctor 2</span>
                <span>Dx <code>K29.7</code></span>
                <span>Rx <code>RX-0765</code></span>
              </div>
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
                    <td><code class="text-semibold">RX-1042</code></td>
                    <td>Today · 11 Jul 2026</td>
                    <td style="text-align:center;font-weight:700">3</td>
                    <td><span class="badge badge--warning">Pending pickup</span></td>
                    <td class="data-table__actions"><a class="link-act" href="/staff/doctor/prescription-view">View</a></td>
                  </tr>
                  <tr class="data-table__row">
                    <td><code class="text-semibold">RX-0871</code></td>
                    <td>12 May 2026</td>
                    <td style="text-align:center;font-weight:700">1</td>
                    <td><span class="badge badge--success">Fully dispensed</span></td>
                    <td class="data-table__actions"><a class="link-act" href="/staff/doctor/prescription-view">View</a></td>
                  </tr>
                  <tr class="data-table__row">
                    <td><code class="text-semibold">RX-0765</code></td>
                    <td>03 Feb 2026</td>
                    <td style="text-align:center;font-weight:700">2</td>
                    <td><span class="badge badge--success">Fully dispensed</span></td>
                    <td class="data-table__actions"><a class="link-act" href="/staff/doctor/prescription-view">View</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div data-tab-panel="labs" hidden>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Files &amp; lab reports</span>
          <button class="btn btn--secondary btn--sm" type="button">Attach file</button>
        </div>
        <div class="consultation-file-list">
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">FBC report - Asiri Labs.pdf</div>
              <div class="consultation-file__meta">Uploaded by patient · 09 Jul 2026</div>
            </div>
            <span class="consultation-file__tag consultation-file__tag--new">New</span>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View results</a>
          </div>
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">Lipid panel.pdf</div>
              <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 12 May 2026</div>
            </div>
            <span class="consultation-file__tag">Reviewed</span>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View results</a>
          </div>
          <div class="consultation-file">
            <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
            <div class="consultation-file__body">
              <div class="consultation-file__name">ECG - 12-lead.pdf</div>
              <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 14 Jan 2026</div>
            </div>
            <span class="consultation-file__tag">Reviewed</span>
            <a class="btn btn--secondary btn--sm" href="/staff/doctor/lab-results">View results</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/current-patient.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>