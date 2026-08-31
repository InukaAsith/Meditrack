<?php

declare(strict_types=1);

$title = 'Clinic Configuration';
$active = 'configuration';

$hours = [
  ['day' => 'Monday',    'open' => '08:30', 'close' => '20:00', 'closed' => false],
  ['day' => 'Tuesday',   'open' => '08:30', 'close' => '20:00', 'closed' => false],
  ['day' => 'Wednesday', 'open' => '08:30', 'close' => '20:00', 'closed' => false],
  ['day' => 'Thursday',  'open' => '08:30', 'close' => '20:00', 'closed' => false],
  ['day' => 'Friday',    'open' => '08:30', 'close' => '20:00', 'closed' => false],
  ['day' => 'Saturday',  'open' => '08:30', 'close' => '13:00', 'closed' => false],
  ['day' => 'Sunday and Poya days', 'open' => '', 'close' => '', 'closed' => true],
];

$queueFields = [
  ['field' => 'grace_window_min',        'label' => 'Grace window',            'value' => '12', 'unit' => 'min', 'help' => 'How late a patient can be before they count as a no-show.'],
  ['field' => 'arrival_buffer_min',      'label' => 'Arrival buffer',          'value' => '15', 'unit' => 'min', 'help' => 'How early patients are asked to arrive before their turn.'],
  ['field' => 'no_show_limit_min',       'label' => 'No-show limit',  'value' => '30', 'unit' => 'min', 'help' => 'How long after their slot a no-show is closed.'],
  ['field' => 'long_wait_alert_min',     'label' => 'Long wait alert', 'value' => '30', 'unit' => 'min', 'help' => 'Waiting time that alerts reception and supporting staff.'],
];

$etaFields = [
  ['field' => 'renotify_threshold_min', 'label' => 'Update patients after', 'value' => '10',   'unit' => 'min', 'help' => 'Patients get a new wait time message when it moves by this much.'],
  ['field' => 'acd_ema_alpha',          'label' => 'Consult time smoothing', 'value' => '0.30', 'unit' => '',   'help' => 'Higher follows the latest consults faster. Lower is steadier.'],
  ['field' => 'skip_reinsert_after',    'label' => 'Skipped patient returns after',  'value' => '3',    'unit' => 'calls', 'help' => 'A skipped patient goes back in line after this many calls.'],
];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Clinic configuration</h1>
    <div class="staff-head__sub">Settings for the whole clinic</div>
  </div>
</div>

<div class="config-card">
  <div class="config-card__head">
    <span class="config-card__icon"><?= icon('building', 18) ?></span>
    <div class="config-card__titles">
      <div class="config-card__title">Clinic identity</div>
      <div class="config-card__sub">Printed on invoices and prescriptions</div>
    </div>
  </div>
  <div class="config-card__body">
    <div class="form-grid">
      <div class="field form-grid__full">
        <label class="field__label" for="config-name">Clinic name</label>
        <input class="field__input" id="config-name" value="HealthGate Medical (Pvt) Ltd">
      </div>
      <div class="field">
        <label class="field__label" for="config-phone">Phone</label>
        <input class="field__input" id="config-phone" value="+94 11 274 5500">
      </div>
      <div class="field">
        <label class="field__label" for="config-addr">Address</label>
        <input class="field__input" id="config-addr" value="No. 115 Main Street, Athurugiriya, Sri Lanka">
      </div>
      <div class="field form-grid__full">
        <label class="field__label">Logo</label>
        <div class="logo-drop">
          <span class="logo-drop__preview"><?= icon('health', 26) ?></span>
          <div class="logo-drop__text">
            <div class="logo-drop__name">logo.png</div>
            <div class="logo-drop__hint">PNG or SVG, up to 1&nbsp;MB</div>
          </div>
          <button class="btn btn--secondary btn--sm" type="button"><?= icon('upload', 14) ?>Replace</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="config-card">
  <div class="config-card__head">
    <span class="config-card__icon"><?= icon('clock', 18) ?></span>
    <div class="config-card__titles">
      <div class="config-card__title">Operating hours</div>
      <div class="config-card__sub">Bookings can only be made inside these hours</div>
    </div>
  </div>
  <div class="config-card__body">
    <div class="hours">
      <?php foreach ($hours as $h): ?>
        <div class="hours__row">
          <span class="hours__day"><?= e($h['day']) ?></span>
          <?php if ($h['closed']): ?>
            <span class="hours__closed">Closed</span>
            <label class="toggle" data-toggle role="button" tabindex="0" aria-pressed="false">
              <span class="toggle__track"><span class="toggle__thumb"></span></span>
              <span class="toggle__label">Open</span>
            </label>
          <?php else: ?>
            <span class="hours__times">
              <input class="time-input" type="text" value="<?= e($h['open']) ?>" aria-label="Open time">
              <span class="hours__sep">–</span>
              <input class="time-input" type="text" value="<?= e($h['close']) ?>" aria-label="Close time">
            </span>
            <label class="toggle is-on" data-toggle role="button" tabindex="0" aria-pressed="true">
              <span class="toggle__track"><span class="toggle__thumb"></span></span>
              <span class="toggle__label">Open</span>
            </label>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="config-card">
  <div class="config-card__head">
    <span class="config-card__icon"><?= icon('queue', 18) ?></span>
    <div class="config-card__titles">
      <div class="config-card__title">Queue settings</div>
      <div class="config-card__sub">Used by the live queue and wait times</div>
    </div>
  </div>
  <div class="config-card__body">
    <?php foreach ($queueFields as $f): ?>
      <div class="config-row">
        <div>
          <div class="config-row__label"><?= e($f['label']) ?></div>
          <div class="config-row__help"><?= e($f['help']) ?></div>
        </div>
        <div class="config-row__field">
          <label class="config-input">
            <input type="text" value="<?= e($f['value']) ?>" inputmode="decimal" aria-label="<?= e($f['label']) ?>">
            <span class="config-input__unit"><?= e($f['unit']) ?></span>
          </label>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="config-card">
  <div class="config-card__head">
    <span class="config-card__icon"><?= icon('activity', 18) ?></span>
    <div class="config-card__titles">
      <div class="config-card__title">Wait time settings</div>
      <div class="config-card__sub">How wait times are worked out and sent</div>
    </div>
  </div>
  <div class="config-card__body">
    <?php foreach ($etaFields as $f): ?>
      <div class="config-row">
        <div>
          <div class="config-row__label"><?= e($f['label']) ?></div>
          <div class="config-row__help"><?= e($f['help']) ?></div>
        </div>
        <div class="config-row__field">
          <label class="config-input">
            <input type="text" value="<?= e($f['value']) ?>" inputmode="decimal" aria-label="<?= e($f['label']) ?>">
            <span class="config-input__unit"><?= e($f['unit']) ?></span>
          </label>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="config-card">
  <div class="config-card__head">
    <span class="config-card__icon"><?= icon('pill', 18) ?></span>
    <div class="config-card__titles">
      <div class="config-card__title">Pharmacy &amp; reschedule policy</div>
      <div class="config-card__sub">Deadlines for pharmacy orders and rescheduling</div>
    </div>
  </div>
  <div class="config-card__body">
    <div class="config-row">
      <div>
        <div class="config-row__label">Pharmacy collection deadline</div>
        <div class="config-row__help">An order not collected by then is cancelled and refunded.</div>
      </div>
      <div class="config-row__field">
        <label class="config-input">
          <input type="text" value="24" inputmode="decimal" aria-label="Pharmacy collection deadline">
          <span class="config-input__unit">hours</span>
        </label>
      </div>
    </div>
    <div class="config-row">
      <div>
        <div class="config-row__label">Patient reschedule window</div>
        <div class="config-row__help">How long after booking a patient can reschedule by themselves.</div>
      </div>
      <div class="config-row__field">
        <label class="config-input">
          <input type="text" value="2" inputmode="decimal" aria-label="Patient reschedule window">
          <span class="config-input__unit">days</span>
        </label>
      </div>
    </div>
  </div>
</div>

<div class="save-bar">
  <span class="save-bar__note"><?= icon('records', 14) ?> Changes are saved to the audit trail.</span>
  <div class="save-bar__actions">
    <button class="btn btn--secondary" type="button">Discard</button>
    <button class="btn btn--primary" type="button"><?= icon('check', 14) ?>Save changes</button>
  </div>
</div>
<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>