<?php

declare(strict_types=1);

$title = 'New invoice';
$active = 'billing';
$extraCss = ['patient'];

require __DIR__ . '/header.php';
?>
<div class="patient-find-subhead">
  <a class="book-back" href="/staff/receptionist/billing"><?= icon('chevronLeft', 15) ?>New invoice</a>
</div>

<div class="new-invoice-grid" data-new-invoice>
  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Patient</div>
      <div class="search-box mb-4">
        <?= icon('search', 16, 'search-box__icon') ?>
        <input class="search-box__input" type="search" id="new-invoice-search" placeholder="Search name, NIC, Patient ID or phone…" autocomplete="off" aria-label="Search patient">
      </div>
      <div class="new-invoice-results" id="new-invoice-results">
        <div data-invoice-result>
          <button class="patient-find-res new-invoice-res" type="button"
            data-hay="k.a. inuka asith pt-0967 786512340v +94 77 651 2340"
            data-name="K.A. Inuka Asith" data-code="PT-0967">
            <span class="patient-find-res__avatar">KI</span>
            <div class="patient-find-res__body">
              <div class="patient-find-res__name">K.A. Inuka Asith</div>
              <div class="patient-find-res__meta inline-parts"><span>PT-0967</span><span>NIC 786512340V</span><span>+94 77 651 2340</span></div>
            </div>
            <span class="new-invoice-res__pick">Select</span>
          </button>
        </div>
        <div data-invoice-result>
          <button class="patient-find-res new-invoice-res" type="button"
            data-hay="sample patient 14 pt-0411 831092811v +94 71 903 8221"
            data-name="M. L. Omindu Gunathilaka" data-code="PT-0411">
            <span class="patient-find-res__avatar">KI</span>
            <div class="patient-find-res__body">
              <div class="patient-find-res__name">M. L. Omindu Gunathilaka</div>
              <div class="patient-find-res__meta inline-parts"><span>PT-0411</span><span>NIC 831092811V</span><span>+94 71 903 8221</span></div>
            </div>
            <span class="new-invoice-res__pick">Select</span>
          </button>
        </div>
        <div data-invoice-result>
          <button class="patient-find-res new-invoice-res" type="button"
            data-hay="sample patient 15 pt-1290 926781002v +94 76 118 4470"
            data-name="G. G. Mithun Majika" data-code="PT-1290">
            <span class="patient-find-res__avatar">KI</span>
            <div class="patient-find-res__body">
              <div class="patient-find-res__name">G. G. Mithun Majika</div>
              <div class="patient-find-res__meta inline-parts"><span>PT-1290</span><span>NIC 926781002V</span><span>+94 76 118 4470</span></div>
            </div>
            <span class="new-invoice-res__pick">Select</span>
          </button>
        </div>
        <div id="new-invoice-empty" hidden>
          <div class="patient-find__foot"><span>No matching patient.</span></div>
        </div>
      </div>

      <label class="new-invoice-guest-toggle">
        <input type="checkbox" id="new-invoice-guest-toggle">
        <span>Walk-in patient with no record: type their name and phone</span>
      </label>
      <div class="new-invoice-guest" id="new-invoice-guest" hidden>
        <div class="form-2col">
          <label class="field"><span class="field__label">Patient name <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" id="new-invoice-guest-name" placeholder="Full name as given"></label>
          <label class="field"><span class="field__label">Phone <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" id="new-invoice-guest-phone" placeholder="+94 7X XXX XXXX" inputmode="tel"></label>
        </div>
        <div class="emergency-note">We save a temporary guest record for this invoice. Register them fully later.</div>
      </div>

      <div class="staff-eyebrow mt-8">Doctor</div>
      <label class="field"><span class="field__label">Consulting doctor <span class="field__req" aria-hidden="true">*</span></span>
        <select class="field__input" id="new-invoice-doc">
          <option value="Dr. Sample Doctor 1" data-fee="2500">Dr. Sample Doctor 1 (General, Rs. 2,500)</option>
          <option value="Dr. Sample Doctor 3" data-fee="3000">Dr. Sample Doctor 3 (Pediatrics, Rs. 3,000)</option>
          <option value="Dr. Sample Doctor 2" data-fee="2000">Dr. Sample Doctor 2 (ENT, Rs. 2,000)</option>
        </select>
      </label>

      <div class="staff-eyebrow mt-8">Details</div>
      <div class="form-2col">
        <label class="field"><span class="field__label">Appointment ID <span class="field__lock">(optional)</span></span><input class="field__input" id="new-invoice-appt" placeholder="APT-… (leave empty if there is none)"></label>
        <label class="field"><span class="field__label">Amount paid (cash) <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" id="new-invoice-amount" inputmode="decimal" value="2500.00"></label>
      </div>
      <div class="new-invoice-cashnote"><?= icon('billing', 14) ?>Cash only</div>
    </div>
  </div>

  <aside class="card new-invoice-summary">
    <div class="card__body">
      <div class="book-summary">
        <div class="book-summary__eyebrow">Invoice preview</div>
        <div class="book-summary__row"><span>Invoice</span><span class="val mono">INV-0232 <span class="text-subtle">(next)</span></span></div>
        <div class="book-summary__row"><span>Patient</span><span class="val" data-sum-patient>Not picked yet</span></div>
        <div class="book-summary__row"><span>Doctor</span><span class="val" data-sum-doctor>Dr. Sample Doctor 1</span></div>
        <div class="book-summary__row"><span>Appointment</span><span class="val" data-sum-appt>Manual check-in</span></div>
        <div class="book-summary__row"><span>Method</span><span class="val">Cash</span></div>
        <hr class="book-summary__hr">
        <div class="book-summary__row book-summary__row--total"><span>Amount paid</span><span class="val mono" data-sum-amount>Rs. 2,500</span></div>
        <p class="form-error" id="new-invoice-error" hidden>Pick a patient, or enter an unregistered guest's name.</p>
        <button class="btn btn--primary btn--block mt-6" type="button" id="new-invoice-create">Create invoice &amp; check in</button>
        <a class="btn btn--secondary btn--block mt-4" href="/staff/receptionist/billing">Cancel</a>
      </div>
    </div>
  </aside>
</div>
<script src="/assets/js/receptionist/new-invoice.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>