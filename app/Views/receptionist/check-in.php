<?php

declare(strict_types=1);

$title = 'Check-in';
$active = 'check-in';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Check-in</h1>
  </div>
</div>

<div class="checkin-switch" data-tab-group>
  <div class="cal-viewtabs checkin-seg">
    <button class="cal-viewtabs__item is-active" type="button" data-tab="registered">Registered patient</button>
    <button class="cal-viewtabs__item" type="button" data-tab="walkin">Walk-in</button>
    <button class="cal-viewtabs__item" type="button" data-tab="emergency">Emergency patient</button>
  </div>

  <div data-tab-panel="registered">
    <div class="checkin-grid">
      <div>
        <div class="scan-box">
          <span class="scan-box__icon"><?= icon('search', 26) ?></span>
          <div class="scan-box__title">Scan patient QR card</div>
          <div class="scan-box__sub">or type Patient ID / NIC below</div>
          <div class="scan-box__input">
            <div class="search-box">
              <?= icon('search', 16, 'search-box__icon') ?>
              <input class="search-box__input" type="search" id="checkin-lookup" placeholder="PT-0001 or NIC…" aria-label="Patient lookup">
            </div>
          </div>
        </div>

        <div class="lookup-card mt-8" id="lookup-card">
          <div class="lookup-card__top">
            <span class="lookup-card__avatar">KI</span>
            <div style="flex:1;min-width:0">
              <div class="lookup-card__name">K.A. Inuka Asith <span class="lookup-card__verified"><?= icon('check', 12) ?>identity verified</span></div>
              <div class="lookup-card__meta inline-parts"><span>PT-0967</span><span>38 years</span><span>Female</span></div>
            </div>
          </div>

          <div class="lookup-card__appt">
            <span class="inline-parts"><b>Today 09:45</b><span>Dr. Sample Doctor 1</span><span>New patient</span></span>
            <span class="checkin-late"><?= icon('clock', 12) ?>18 min late</span>
          </div>

          <div class="fee-row">
            <span class="fee-row__label">Consultation fee</span>
            <span class="fee-row__amount">Rs. 2,500</span>
          </div>
          <div class="pay-methods" data-pay-methods>
            <button class="pay-method is-active" type="button" data-pm="cash">Cash</button>
            <button class="pay-method" type="button" data-pm="card">Card</button>
            <button class="pay-method" type="button" data-pm="online" disabled>Paid online</button>
          </div>

          <button class="btn btn--primary btn--block" type="button" id="checkin-collect" data-checkin-late><?= icon('check', 14) ?>Check in</button>
          <button class="btn btn--secondary btn--block mt-4" type="button"><?= icon('print', 14) ?>Print invoice</button>

        </div>
      </div>

      <aside class="card">
        <div class="card__body">
          <div class="staff-eyebrow">This morning </div>
          <div class="morning-list">
            <div class="morning-row">
              <div>
                <div class="morning-row__name">Nimsith Wickrama</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>Paid online</span></div>
              </div>
              <span class="badge badge--success">Checked in</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name">M. L. Omindu Gunathilaka</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>Paid in cash</span></div>
              </div>
              <span class="badge badge--primary">Ready</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name">G. G. Mithun Majika</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 3</span><span>Paid online</span></div>
              </div>
              <span class="badge badge--success">Checked in</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name">K.A. Inuka Asith</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>Pay at counter</span></div>
              </div>
              <span class="badge badge--muted">Not arrived yet</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name">Sandanu Dulmeth</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>Pay at counter</span></div>
              </div>
              <span class="badge badge--muted">Not arrived yet</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name">K. Ashan Charuka</div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 2</span><span>Paid online</span></div>
              </div>
              <span class="badge badge--muted">Not arrived yet</span>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <div data-tab-panel="walkin" hidden>
    <div class="checkin-grid">
      <div class="card">
        <div class="card__body">
          <div class="walkin">
            <div class="walkin__head">
              <span class="walkin__icon"><?= icon('profile', 20) ?></span>
              <div>
                <div class="walkin__title">Walk-in patient</div>
                <div class="walkin__sub">No appointment</div>
              </div>
            </div>

            <label class="field"><span class="field__label">Patient name <span class="field__req" aria-hidden="true">*</span></span>
              <input class="field__input" id="ci-wk-name" placeholder="Full name as given" autocomplete="off">
            </label>
            <label class="field mt-6"><span class="field__label">Phone number <span class="field__req" aria-hidden="true">*</span></span>
              <input class="field__input" id="ci-wk-phone" placeholder="+94 7X XXX XXXX" inputmode="tel" autocomplete="off">
            </label>
            <label class="field mt-6"><span class="field__label">See doctor <span class="field__req" aria-hidden="true">*</span></span>
              <select class="field__input" id="ci-wk-doc" data-walkin-doc>
                <option value="AS" data-fee="2500">Dr. Sample Doctor 1 (General, Rs. 2,500)</option>
                <option value="RF" data-fee="3000">Dr. Sample Doctor 3 (Pediatrics, Rs. 3,000)</option>
                <option value="MP" data-fee="2000">Dr. Sample Doctor 2 (ENT, Rs. 2,000)</option>
              </select>
            </label>

            <div class="walkin-fee">
              <span class="walkin-fee__label">Consultation fee</span>
              <span class="walkin-fee__amount" data-walkin-fee>Rs. 2,500</span>
            </div>
            <div class="walkin-pay">
              <span class="walkin-pay__chip"><?= icon('check', 12) ?>Cash</span>
            </div>

            <button class="btn btn--primary btn--block walkin__submit mt-7" type="button"
              data-walkin-issue>
              <?= icon('check', 14) ?>Issue token
            </button>
          </div>
        </div>
      </div>
      <aside class="card">
        <div class="card__body">
          <div class="staff-eyebrow">Walk-ins today <span class="label-note">3 issued</span></div>
          <div class="morning-list">
            <div class="morning-row">
              <div>
                <div class="morning-row__name inline-parts"><span>W-04</span><span>Nimsith Wickrama</span></div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>issued 09:41</span></div>
              </div><span class="badge badge--warning">In queue</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name inline-parts"><span>W-05</span><span>guest</span></div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 2</span><span>issued 09:12</span></div>
              </div><span class="badge badge--success">Seen</span>
            </div>
            <div class="morning-row">
              <div>
                <div class="morning-row__name inline-parts"><span>W-07</span><span>Ashan C.</span></div>
                <div class="morning-row__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>issued 08:50</span></div>
              </div><span class="badge badge--success">Seen</span>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <div data-tab-panel="emergency" hidden>
    <div class="checkin-grid">
      <div class="card">
        <div class="card__body">
          <div class="emergency-form__head">
            <span class="emergency-form__badge"><?= icon('alert', 18) ?></span>
            <div>
              <div class="emergency-form__title">Emergency patient: send straight in</div>
            </div>
          </div>
          <div class="emergency-form" data-emg-form>
            <p class="emergency-form__lead">The live queue <strong>pauses</strong> while this patient is seen</p>

            <div class="emergency-tabs" data-emg-tabs>
              <button class="emergency-tab is-active" type="button" data-emg-tab="scan">Scan QR</button>
              <button class="emergency-tab" type="button" data-emg-tab="id">Type NIC / ID</button>
              <button class="emergency-tab" type="button" data-emg-tab="guest">Name only</button>
            </div>

            <div class="emergency-pane" data-emg-pane="scan">
              <div class="emergency-scan">
                <div class="emergency-scan__icon"><?= icon('grid', 28) ?></div>
                <div class="emergency-scan__text">Scan the patient's QR card</div>
                <button class="btn btn--secondary btn--sm" type="button">Open scanner</button>
              </div>
            </div>
            <div class="emergency-pane" data-emg-pane="id" hidden>
              <label class="field"><span class="field__label">NIC or Patient ID <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" id="ci-emg-id"></label>
            </div>
            <div class="emergency-pane" data-emg-pane="guest" hidden>
              <label class="field"><span class="field__label">Patient name <span class="field__req" aria-hidden="true">*</span></span><input class="field__input" id="ci-emg-name" placeholder="Full name as given"></label>
            </div>

            <label class="field emergency-doc"><span class="field__label">Send in to <span class="field__req" aria-hidden="true">*</span></span>
              <select class="field__input" id="ci-emg-doc">
                <option value="Dr. Sample Doctor 1">Dr. Sample Doctor 1 (General)</option>
                <option value="Dr. Sample Doctor 3">Dr. Sample Doctor 3 (Pediatrics)</option>
                <option value="Dr. Sample Doctor 2">Dr. Sample Doctor 2 (ENT)</option>
              </select>
            </label>

            <div class="emergency-form__actions">
              <button class="btn btn--emergency btn--block" type="button" data-emg-send>Send patient in</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="checkin-late-pop" hidden>
  <div class="reinsert-pop" role="dialog" aria-label="Late arrival , reinsert into queue">
    <div class="reinsert-pop__title" data-late-title>Late arrival, insert into queue</div>
    <label class="reinsert-pop__field">
      <span>Insert after position</span>
      <input type="number" min="1" value="3" data-late-pos aria-label="Insert after position">
    </label>
    <div class="reinsert-pop__hint">This patient came after their time. They go in after #3 unless you pick another place.</div>
    <div class="reinsert-pop__actions">
      <button class="btn btn--secondary btn--xs" type="button" data-late-cancel>Cancel</button>
      <button class="btn btn--success btn--xs" type="button" data-late-confirm>Check in here</button>
    </div>
  </div>
</div>
<script src="/assets/js/receptionist/check-in.js" defer></script>
<script src="/assets/js/receptionist/emergency.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>