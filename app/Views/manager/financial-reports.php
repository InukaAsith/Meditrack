<?php

declare(strict_types=1);

$title = 'Financial Reports';
$active = 'financial-reports';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Financial reports</h1>
    <div class="staff-head__sub">June 2026</div>
  </div>
  <div class="staff-head__actions">
    <label class="doctor-select" style="border:1px solid var(--border);border-radius:var(--r-sm);padding:var(--sp-3) var(--sp-5)">
      <?= icon('calendar', 14) ?>
      <select aria-label="Report period">
        <option>June 2026</option>
        <option>May 2026</option>
        <option>April 2026</option>
        <option>Q2 2026</option>
      </select>
    </label>
    <button class="btn btn--secondary" type="button"><?= icon('download', 15) ?> Export CSV</button>
    <button class="btn btn--primary" type="button"><?= icon('print', 15) ?> Print PDF</button>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Gross income</span>
    </div>
    <div class="manager-kpi__value">Rs. 2.51M</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Total deductions</span>
    </div>
    <div class="manager-kpi__value">Rs. 104,700</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Net revenue</span>
    </div>
    <div class="manager-kpi__value">Rs. 2,419,600</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Invoices issued</span>
    </div>
    <div class="manager-kpi__value">1,462</div>
  </div>
</div>

<div class="financial-grid">
  <div class="stack">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Income by category</div>
        <div class="financial-row">
          <span class="financial-row__label"><span class="financial-row__swatch" style="background:#1B54B8"></span>Consultation fees</span>
          <span class="financial-row__amount financial-row__amount--in">Rs. 1,280,000</span>
        </div>
        <div class="financial-row">
          <span class="financial-row__label"><span class="financial-row__swatch" style="background:#0E7C78"></span>Pharmacy sales</span>
          <span class="financial-row__amount financial-row__amount--in">Rs. 1,130,000</span>
        </div>
        <div class="financial-row">
          <span class="financial-row__label"><span class="financial-row__swatch" style="background:#E0A63B"></span>Procedures &amp; other</span>
          <span class="financial-row__amount financial-row__amount--in">Rs. 96,300</span>
        </div>
        <div class="staff-eyebrow mt-8">Deductions</div>
        <div class="financial-row">
          <span class="financial-row__label"><span class="financial-row__swatch" style="background:#C03036"></span>Pharmacy cost of goods</span>
          <span class="financial-row__amount financial-row__amount--out">− Rs. 74,300</span>
        </div>
        <div class="financial-row">
          <span class="financial-row__label"><span class="financial-row__swatch" style="background:#8A93A6"></span>No-show refunds</span>
          <span class="financial-row__amount financial-row__amount--out">− Rs. 12,400</span>
        </div>
        <div class="financial-net">
          <span>Net revenue</span>
          <span class="mono">Rs. 2,401,600</span>
        </div>
        <div class="financial-note">
          <?= icon('lock', 14) ?>
          <span><b>Immutable snapshot.</b> Manager sees aggregates only - no clinical record access (RBAC).</span>
        </div>
      </div>
    </div>

    <div class="chart-card">
      <div class="chart-card__head">
        <div class="chart-card__titles">
          <span class="chart-card__title">Income by category</span>
          <span class="chart-card__sub">Share of gross income</span>
        </div>
      </div>
      <div class="chart-card__body">
        <div class="chart-card__canvas"><canvas data-chart="financial-income-category"></canvas></div>
      </div>
    </div>
  </div>

  <div class="stack">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">By payment channel</div>
        <div class="financial-row">
          <span class="financial-row__label">Cash at counter</span>
          <span class="financial-row__amount">Rs. 212,500 <span class="text-muted">(44%)</span></span>
        </div>
        <div class="financial-row">
          <span class="financial-row__label">Online (PayHere)</span>
          <span class="financial-row__amount">Rs. 302,500 <span class="text-muted">(62%)</span></span>
        </div>
        <div class="financial-row">
          <span class="financial-row__label">Card</span>
          <span class="financial-row__amount">Rs. 61,700 <span class="text-muted">(13%)</span></span>
        </div>
      </div>
    </div>

    <div class="chart-card">
      <div class="chart-card__head">
        <div class="chart-card__titles">
          <span class="chart-card__title">Channel split</span>
          <span class="chart-card__sub">Cash / online / card</span>
        </div>
      </div>
      <div class="chart-card__body">
        <div class="chart-card__canvas chart-card__canvas--sm"><canvas data-chart="financial-channels"></canvas></div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/chart-scripts.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>