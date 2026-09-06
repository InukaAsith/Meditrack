<?php

declare(strict_types=1);

$title  = 'Patient history';
$active = 'patient-history';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Patient history</h1>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div class="consultation-subtabs" data-search-mode>
      <button class="consultation-subtab is-active" type="button" data-mode="patient">Search patient</button>
      <button class="consultation-subtab" type="button" data-mode="appointment">Search past appointment</button>
    </div>

    <div class="search-box" style="max-width:560px;margin-bottom:var(--sp-6)">
      <?= icon('search', 16, 'search-box__icon') ?>
      <input class="search-box__input" type="search" id="ph-search" placeholder="Search by patient ID (PT-0912) or name…" aria-label="Search patient">
    </div>

    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Patient</th>
            <th>Last visit</th>
            <th>Doctor</th>
            <th>Diagnosis (ongoing)</th>
            <th class="data-table__actions"></th>
          </tr>
        </thead>
        <tbody>
          <tr class="data-table__row is-clickable" data-ph-row onclick="location.href='/staff/doctor/patient-detail'">
            <td>
              <div style="display:flex;align-items:center;gap:var(--sp-4)">
                <span class="consultation-patient-card__avatar" style="width:32px;height:32px;font-size:var(--fs-xs)">NJ</span>
                <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-0912</span></div>
              </div>
            </td>
            <td>11 Jul 2026</td>
            <td>Dr. Sample Doctor 1</td>
            <td><span class="badge badge--muted">Hypertension</span></td>
            <td class="data-table__actions"><a class="link-act" href="/staff/doctor/patient-detail">Open record</a></td>
          </tr>
          <tr class="data-table__row is-clickable" data-ph-row onclick="location.href='/staff/doctor/patient-detail'">
            <td>
              <div style="display:flex;align-items:center;gap:var(--sp-4)">
                <span class="consultation-patient-card__avatar" style="width:32px;height:32px;font-size:var(--fs-xs)">KP</span>
                <div class="table-patient"><strong>K.A. Inuka Asith</strong><span>PT-0788</span></div>
              </div>
            </td>
            <td>20 Jun 2026</td>
            <td>Dr. Sample Doctor 2</td>
            <td><span class="badge badge--muted">Gastritis</span></td>
            <td class="data-table__actions"><a class="link-act" href="/staff/doctor/patient-detail">Open record</a></td>
          </tr>
          <tr class="data-table__row is-clickable" data-ph-row onclick="location.href='/staff/doctor/patient-detail'">
            <td>
              <div style="display:flex;align-items:center;gap:var(--sp-4)">
                <span class="consultation-patient-card__avatar" style="width:32px;height:32px;font-size:var(--fs-xs)">MF</span>
                <div class="table-patient"><strong>G. G. Mithun Majika</strong><span>PT-0654</span></div>
              </div>
            </td>
            <td>03 Feb 2026</td>
            <td>Dr. Sample Doctor 1</td>
            <td><span class="badge badge--muted">Asthma</span></td>
            <td class="data-table__actions"><a class="link-act" href="/staff/doctor/patient-detail">Open record</a></td>
          </tr>
          <tr class="data-table__row is-clickable" data-ph-row onclick="location.href='/staff/doctor/patient-detail'">
            <td>
              <div style="display:flex;align-items:center;gap:var(--sp-4)">
                <span class="consultation-patient-card__avatar" style="width:32px;height:32px;font-size:var(--fs-xs)">RD</span>
                <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-0533</span></div>
              </div>
            </td>
            <td>14 Jan 2026</td>
            <td>Dr. Sample Doctor 1</td>
            <td><span class="text-muted">None ongoing</span></td>
            <td class="data-table__actions"><a class="link-act" href="/staff/doctor/patient-detail">Open record</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/patient-history.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>