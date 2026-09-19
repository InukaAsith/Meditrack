<?php

declare(strict_types=1);

$title = 'Drug Pricing';
$active = 'drug-pricing';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Drug pricing</h1>
    <div class="staff-head__sub">Unit prices for every medicine</div>
  </div>
</div>

<div class="pricing" data-pricing data-today="11 Jul 2026" data-actor="Nimsith W.">
  <div class="search-box" style="margin-bottom:var(--sp-6);max-width:520px">
    <?= icon('search', 16, 'search-box__icon') ?>
    <input class="search-box__input" type="search" placeholder="Search medicine" data-price-search aria-label="Search medicine">
  </div>

  <div class="card">
    <div class="card__body">
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Medicine</th>
              <th>Generic</th>
              <th>Form</th>
              <th class="table-num">Price</th>
              <th>Last changed</th>
              <th>Changed by</th>
              <th class="data-table__actions"></th>
            </tr>
          </thead>
          <tbody>
            <tr data-drug-row data-name="Amoxicillin 500 mg" data-generic="Amoxicillin">
              <td><strong>Amoxicillin 500 mg</strong></td>
              <td class="text-muted">Amoxicillin</td>
              <td class="text-muted">Capsule</td>
              <td class="table-num" data-cell="price">Rs. 22</td>
              <td data-cell="changed" class="text-muted">28 Jun 2026</td>
              <td data-cell="by" class="text-muted">Mithun M.</td>
              <td class="data-table__actions"><button class="link-btn" type="button" data-price-open>Adjust <?= icon('arrowRight', 13) ?></button></td>
            </tr>
            <tr data-drug-row data-name="Paracetamol 500 mg" data-generic="Paracetamol">
              <td><strong>Paracetamol 500 mg</strong></td>
              <td class="text-muted">Paracetamol</td>
              <td class="text-muted">Tablet</td>
              <td class="table-num" data-cell="price">Rs. 8</td>
              <td data-cell="changed" class="text-muted">02 Jan 2026</td>
              <td data-cell="by" class="text-muted">Mithun M.</td>
              <td class="data-table__actions"><button class="link-btn" type="button" data-price-open>Adjust <?= icon('arrowRight', 13) ?></button></td>
            </tr>
            <tr data-drug-row data-name="Cetirizine 10 mg" data-generic="Cetirizine HCl">
              <td><strong>Cetirizine 10 mg</strong></td>
              <td class="text-muted">Cetirizine HCl</td>
              <td class="text-muted">Tablet</td>
              <td class="table-num" data-cell="price">Rs. 15</td>
              <td data-cell="changed" class="text-muted">14 May 2026</td>
              <td data-cell="by" class="text-muted">Mithun M.</td>
              <td class="data-table__actions"><button class="link-btn" type="button" data-price-open>Adjust <?= icon('arrowRight', 13) ?></button></td>
            </tr>
            <tr data-drug-row data-name="Losartan 50 mg" data-generic="Losartan K">
              <td><strong>Losartan 50 mg</strong></td>
              <td class="text-muted">Losartan K</td>
              <td class="text-muted">Tablet</td>
              <td class="table-num" data-cell="price">Rs. 34</td>
              <td data-cell="changed" class="text-muted">19 Jun 2026</td>
              <td data-cell="by" class="text-muted">Mithun M.</td>
              <td class="data-table__actions"><button class="link-btn" type="button" data-price-open>Adjust <?= icon('arrowRight', 13) ?></button></td>
            </tr>
            <tr data-drug-row data-name="Amox-Clav 625 mg" data-generic="Co-amoxiclav">
              <td><strong>Amox-Clav 625 mg</strong></td>
              <td class="text-muted">Co-amoxiclav</td>
              <td class="text-muted">Tablet</td>
              <td class="table-num" data-cell="price">Rs. 96</td>
              <td data-cell="changed" class="text-muted">03 Jul 2026</td>
              <td data-cell="by" class="text-muted">Mithun M.</td>
              <td class="data-table__actions"><button class="link-btn" type="button" data-price-open>Adjust <?= icon('arrowRight', 13) ?></button></td>
            </tr>
          </tbody>
        </table>
        <p class="table-empty" data-price-empty hidden>No medicines match your search.</p>
      </div>
    </div>
  </div>

  <p class="financial-note mt-6">
    <?= icon('lock', 14) ?>
    <span>Pharmacists change prices and stock day to day from their Inventory page.</span>
  </p>
</div>

<div class="modal-backdrop" data-price-modal hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="price-modal-title">
    <div class="modal__head">
      <h2 class="modal__title" id="price-modal-title">Adjust price</h2>
      <button class="modal__close" type="button" data-price-close aria-label="Close"><?= icon('close', 18) ?></button>
    </div>
    <div class="modal__body">
      <p class="modal__lead inline-parts"><strong data-price-name>-</strong><span data-price-generic class="text-muted"></span></p>
      <div class="field">
        <label class="field__label">Current price</label>
        <input class="field__input" data-price-current readonly>
      </div>
      <div class="field">
        <label class="field__label" for="price-new">New unit price</label>
        <div class="money-input">
          <span class="money-input__prefix">Rs.&nbsp;</span>
          <input class="field__input" id="price-new" type="number" min="0" step="0.5" data-price-new>
        </div>
        <span class="field__desc">Applies to new pharmacy invoices from today. The change is written to the audit trail.</span>
        <p class="form-error" data-price-error hidden>Enter a valid price (0 or more).</p>
      </div>
    </div>
    <div class="modal__actions">
      <button class="btn btn--secondary" type="button" data-price-close>Cancel</button>
      <button class="btn btn--primary" type="button" data-price-save>Save new price</button>
    </div>
  </div>
</div>
<script src="/assets/js/manager/manager-pricing.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>