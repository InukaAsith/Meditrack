<?php

declare(strict_types=1);

$title = 'Pharmacy Alerts';
$active = 'pharmacy-alerts';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Pharmacy alerts</h1>
    <div class="staff-head__sub">4 items running low and 3 batches expiring soon</div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button"><?= icon('bell', 15) ?> Notify pharmacist</button>
  </div>
</div>

<div class="manager-kpis manager-kpis--4">
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Below reorder</span>
    </div>
    <div class="manager-kpi__value">2</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Low stock</span>
    </div>
    <div class="manager-kpi__value">2</div>
  </div>
  <div class="manager-kpi">
    <div class="manager-kpi__top">
      <span class="manager-kpi__label">Expiring ≤30d</span>
    </div>
    <div class="manager-kpi__value">3</div>
  </div>
</div>

<div class="sec-head">
  <h2 class="sec-head__title">Low stock</h2>
  <span class="sec-head__hint">Stock left and reorder level</span>
</div>
<div class="card">
  <div class="card__body">
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Medicine</th>
            <th class="table-barcell">On hand vs reorder</th>
            <th class="data-table__actions">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="table-lead">
                <span class="table-lead__avatar avatar--red"><?= icon('pill', 14) ?></span>
                <div class="table-lead__text"><strong>Amoxicillin 500mg</strong><span>Capsule, batch AMX-2231</span></div>
              </div>
            </td>
            <td class="table-barcell">
              <div class="table-barcell__row">
                <div class="meter" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="120" aria-label="Amoxicillin 500mg on hand vs reorder point">
                  <span class="meter__fill meter__fill--danger" style="width: 33%"></span>
                </div>
                <span class="table-barcell__cap"><strong>40</strong> / min 120</span>
              </div>
            </td>
            <td class="data-table__actions"><span class="badge badge--danger">Below reorder</span></td>
          </tr>
          <tr>
            <td>
              <div class="table-lead">
                <span class="table-lead__avatar avatar--amber"><?= icon('pill', 14) ?></span>
                <div class="table-lead__text"><strong>Metformin 850mg</strong><span>Tablet, batch MET-1180</span></div>
              </div>
            </td>
            <td class="table-barcell">
              <div class="table-barcell__row">
                <div class="meter" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" aria-label="Metformin 850mg on hand vs reorder point">
                  <span class="meter__fill meter__fill--warning" style="width: 65%"></span>
                </div>
                <span class="table-barcell__cap"><strong>65</strong> / min 100</span>
              </div>
            </td>
            <td class="data-table__actions"><span class="badge badge--warning">Low</span></td>
          </tr>
          <tr>
            <td>
              <div class="table-lead">
                <span class="table-lead__avatar avatar--red"><?= icon('pill', 14) ?></span>
                <div class="table-lead__text"><strong>Salbutamol inhaler</strong><span>Inhaler, batch SAL-0442</span></div>
              </div>
            </td>
            <td class="table-barcell">
              <div class="table-barcell__row">
                <div class="meter" role="progressbar" aria-valuenow="8" aria-valuemin="0" aria-valuemax="25" aria-label="Salbutamol inhaler on hand vs reorder point">
                  <span class="meter__fill meter__fill--danger" style="width: 32%"></span>
                </div>
                <span class="table-barcell__cap"><strong>8</strong> / min 25</span>
              </div>
            </td>
            <td class="data-table__actions"><span class="badge badge--danger">Below reorder</span></td>
          </tr>
          <tr>
            <td>
              <div class="table-lead">
                <span class="table-lead__avatar avatar--amber"><?= icon('pill', 14) ?></span>
                <div class="table-lead__text"><strong>Cetirizine 10mg</strong><span>Tablet, batch CET-3320</span></div>
              </div>
            </td>
            <td class="table-barcell">
              <div class="table-barcell__row">
                <div class="meter" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="120" aria-label="Cetirizine 10mg on hand vs reorder point">
                  <span class="meter__fill meter__fill--warning" style="width: 75%"></span>
                </div>
                <span class="table-barcell__cap"><strong>90</strong> / min 120</span>
              </div>
            </td>
            <td class="data-table__actions"><span class="badge badge--warning">Low</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="sec-head">
  <h2 class="sec-head__title">Expiring batches</h2>
  <span class="sec-head__hint">Within 30 days</span>
</div>
<div class="card">
  <div class="card__body">
    <div class="manager-list">
      <div class="manager-list__row">
        <span class="manager-list__icon manager-list__icon--warning"><?= icon('clock', 16) ?></span>
        <div class="manager-list__body">
          <span class="manager-list__title">Augmentin 625mg</span>
          <span class="manager-list__meta inline-parts"><span>Batch AUG-8841</span><span>60 units</span><span>Expires 04 Aug 2026</span></span>
        </div>
        <div class="manager-list__aside">
          <span class="manager-list__figure manager-list__figure--warning">24 days</span>
          <button class="link-btn" type="button">Raise reorder</button>
        </div>
      </div>
      <div class="manager-list__row">
        <span class="manager-list__icon manager-list__icon--danger"><?= icon('clock', 16) ?></span>
        <div class="manager-list__body">
          <span class="manager-list__title">Insulin Glargine</span>
          <span class="manager-list__meta inline-parts"><span>Batch INS-2207</span><span>18 units</span><span>Expires 29 Jul 2026</span></span>
        </div>
        <div class="manager-list__aside">
          <span class="manager-list__figure manager-list__figure--danger">18 days</span>
          <button class="link-btn" type="button">Raise reorder</button>
        </div>
      </div>
      <div class="manager-list__row">
        <span class="manager-list__icon manager-list__icon--warning"><?= icon('clock', 16) ?></span>
        <div class="manager-list__body">
          <span class="manager-list__title">Paracetamol syrup</span>
          <span class="manager-list__meta inline-parts"><span>Batch PCM-5540</span><span>32 units</span><span>Expires 09 Aug 2026</span></span>
        </div>
        <div class="manager-list__aside">
          <span class="manager-list__figure manager-list__figure--warning">29 days</span>
          <button class="link-btn" type="button">Raise reorder</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>