<?php

declare(strict_types=1);

$title  = 'View prescriptions';
$active = 'prescriptions';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">View prescriptions</h1>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <div style="display:flex;align-items:center;gap:var(--sp-5);margin-bottom:var(--sp-6);flex-wrap:wrap">
      <div class="search-box" style="flex:1;min-width:240px">
        <?= icon('search', 16, 'search-box__icon') ?>
        <input class="search-box__input" type="search" id="rx-search" placeholder="Search by Rx ID (RX-1042), patient name…" aria-label="Search prescriptions">
      </div>
      <div class="staff-filters" data-tab-group>
        <button class="staff-pill is-active" data-tab="all" type="button">All</button>
        <button class="staff-pill" data-tab="pending" type="button">Pending pickup</button>
        <button class="staff-pill" data-tab="partial" type="button">Partial</button>
        <button class="staff-pill" data-tab="dispensed" type="button">Dispensed</button>
      </div>
    </div>

    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Rx ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Issued</th>
            <th>Items</th>
            <th>Status</th>
            <th class="data-table__actions">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr class="data-table__row is-clickable" data-status="pending" onclick="location.href='/staff/doctor/prescription-view'">
            <td><code class="text-semibold">RX-1042</code></td>
            <td>
              <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-0912</span></div>
            </td>
            <td>Dr. Sample Doctor 1</td>
            <td>11 Jul 2026</td>
            <td style="text-align:center;font-weight:700">4</td>
            <td><span class="badge badge--warning">Pending pickup</span></td>
            <td class="data-table__actions">
              <a class="link-act" href="/staff/doctor/prescription-view">View</a>
              · <a class="link-act" href="/staff/doctor/prescription-edit">Edit</a>
            </td>
          </tr>
          <tr class="data-table__row is-clickable" data-status="dispensed" onclick="location.href='/staff/doctor/prescription-view'">
            <td><code class="text-semibold">RX-1035</code></td>
            <td>
              <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-0912</span></div>
            </td>
            <td>Dr. Sample Doctor 1</td>
            <td>02 Jul 2026</td>
            <td style="text-align:center;font-weight:700">2</td>
            <td><span class="badge badge--success">Fully dispensed</span></td>
            <td class="data-table__actions">
              <a class="link-act" href="/staff/doctor/prescription-view">View</a>
            </td>
          </tr>
          <tr class="data-table__row is-clickable" data-status="partial" onclick="location.href='/staff/doctor/prescription-view'">
            <td><code class="text-semibold">RX-1021</code></td>
            <td>
              <div class="table-patient"><strong>K.A. Inuka Asith</strong><span>PT-0788</span></div>
            </td>
            <td>Dr. Sample Doctor 2</td>
            <td>20 Jun 2026</td>
            <td style="text-align:center;font-weight:700">3</td>
            <td><span class="badge badge--info">Partially dispensed</span></td>
            <td class="data-table__actions">
              <a class="link-act" href="/staff/doctor/prescription-view">View</a>
            </td>
          </tr>
          <tr class="data-table__row is-clickable" data-status="dispensed" onclick="location.href='/staff/doctor/prescription-view'">
            <td><code class="text-semibold">RX-0871</code></td>
            <td>
              <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-0912</span></div>
            </td>
            <td>Dr. Sample Doctor 1</td>
            <td>12 May 2026</td>
            <td style="text-align:center;font-weight:700">1</td>
            <td><span class="badge badge--success">Fully dispensed</span></td>
            <td class="data-table__actions">
              <a class="link-act" href="/staff/doctor/prescription-view">View</a>
            </td>
          </tr>
          <tr class="data-table__row is-clickable" data-status="dispensed" onclick="location.href='/staff/doctor/prescription-view'">
            <td><code class="text-semibold">RX-0765</code></td>
            <td>
              <div class="table-patient"><strong>G. G. Mithun Majika</strong><span>PT-0654</span></div>
            </td>
            <td>Dr. Sample Doctor 1</td>
            <td>03 Feb 2026</td>
            <td style="text-align:center;font-weight:700">2</td>
            <td><span class="badge badge--success">Fully dispensed</span></td>
            <td class="data-table__actions">
              <a class="link-act" href="/staff/doctor/prescription-view">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/prescriptions.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>