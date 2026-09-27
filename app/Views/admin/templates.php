<?php

declare(strict_types=1);

$title = 'Templates & Archive';
$active = 'templates';

$templates = [
  ['key' => 'booking_confirmed', 'name' => 'Booking confirmed',      'channel' => 'sms',    'edited' => 'Yesterday 14:11', 'by' => 'Inuka A.', 'body' => 'Your appointment {{appt_id}} on {{date}} {{time}} with {{doctor_name}} is confirmed. - HealthGate Medical'],
  ['key' => 'you_are_next',      'name' => 'Queue - you are next',   'channel' => 'sms',    'edited' => '02 Jul 2026',     'by' => 'Inuka A.', 'body' => 'Hi {{patient_name}}, you are next for {{doctor_name}}. Please be near room {{room}}.'],
  ['key' => 'appt_today',        'name' => 'Appointment today',      'channel' => 'sms',    'edited' => '28 Jun 2026',     'by' => 'Inuka A.', 'body' => '{{doctor_name}} update: please arrive by {{arrive_by}}. Current ETA {{eta}} (±{{band}} min).'],
  ['key' => 'refund_status',     'name' => 'No-show + refund',       'channel' => 'sms',    'edited' => '19 Jun 2026',     'by' => 'Inuka A.', 'body' => 'You were marked absent for {{appt_id}}. Refund {{refund_amount}}: {{refund_status}}.'],
  ['key' => 'order_ready',       'name' => 'Pharmacy order ready',   'channel' => 'sms',    'edited' => '11 Jun 2026',     'by' => 'Inuka A.', 'body' => 'Order {{order_id}} is ready to collect at HealthGate pharmacy. Please collect within {{deadline_hrs}} h.'],
  ['key' => 'doctor_arrived',    'name' => 'Doctor arrived',         'channel' => 'in_app', 'edited' => '20 Apr 2026',     'by' => 'Inuka A.', 'body' => '{{doctor_name}} has arrived and is now seeing patients. Your ETA is {{eta}}.'],
  ['key' => 'monthly_report',    'name' => 'Month-end report ready', 'channel' => 'email',  'edited' => '01 Jul 2026',     'by' => 'Inuka A.', 'body' => 'The {{month}} financial report snapshot for {{clinic_name}} is available to export.'],
  ['key' => 'invoice_receipt',   'name' => 'Invoice receipt',        'channel' => 'email',  'edited' => '22 May 2026',     'by' => 'Inuka A.', 'body' => 'Receipt for {{invoice_id}} - {{amount}} paid via {{method}} on {{date}}. Thank you.'],
  ['key' => 'followup_reminder', 'name' => 'Follow-up reminder',     'channel' => 'in_app', 'edited' => '02 May 2026',     'by' => 'Inuka A.', 'body' => 'A follow-up with {{doctor_name}} is due on {{due_date}}. Book from your dashboard.'],
];

$exports = [
  ['label' => 'Appointments',           'meta' => '1,262 in the last 30 days', 'formats' => ['CSV', 'JSON'], 'icon' => 'calendar'],
  ['label' => 'Patient registry',       'meta' => '3,942 patients, names removed', 'formats' => ['CSV'], 'icon' => 'users'],
  ['label' => 'Financial ledger',       'meta' => 'Clinic and pharmacy bills for June 2026', 'formats' => ['CSV'], 'icon' => 'billing'],
  ['label' => 'Notification templates',  'meta' => '10 templates', 'formats' => ['JSON'], 'icon' => 'message'],
  ['label' => 'Audit trail',            'meta' => 'Pick a date range', 'formats' => ['CSV', 'JSON'], 'icon' => 'records'],
];

$diagnostics = [
  ['label' => 'Queue updates', 'value' => '2.1 s',  'target' => 'Target 5 s or less',  'state' => 'ok',   'bars' => [34, 31, 26, 29, 24, 22, 23]],
  ['label' => 'Page load', 'value' => '1.8 s',  'target' => 'Target 3 s or less',  'state' => 'ok',   'bars' => [34, 29, 27, 32, 24, 22, 24]],
  ['label' => 'Uptime (30 days)', 'value' => '99.7%', 'target' => 'Target 99.5%', 'state' => 'ok',   'bars' => [34, 31, 25, 34, 28, 22, 28]],
  ['label' => 'People online', 'value' => '11',     'target' => 'Busiest today: 23', 'state' => 'ok',   'bars' => [22, 24, 28, 30, 34, 28, 26]],
];

$diagEvents = [
  ['text' => 'Wait times updated after a settings change', 'meta' => 'Today 09:20', 'tone' => 'info'],
  ['text' => 'SMS working', 'meta' => '412 sent today, none failed', 'tone' => 'success'],
  ['text' => 'Online payments working', 'meta' => 'Last checked 09:38', 'tone' => 'success'],
  ['text' => 'Stock updates on time', 'meta' => 'Today 09:30', 'tone' => 'success'],
];

$channelLabel = ['sms' => 'SMS', 'email' => 'Email', 'in_app' => 'In-App'];
$channelIcon = ['sms' => 'phone', 'email' => 'mail', 'in_app' => 'message'];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Templates &amp; archive</h1>
    <div class="staff-head__sub">Messages, exports and system health</div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button"><?= icon('download', 14) ?>Export templates</button>
  </div>
</div>

<div class="sec-head">
  <span class="sec-head__title">Message templates</span>
</div>

<div class="admin-toolbar">
  <div class="admin-toolbar__left">
    <div class="seg" data-tpl-filter>
      <button type="button" class="seg__opt is-active" data-chan="all">All</button>
      <button type="button" class="seg__opt" data-chan="sms">SMS</button>
      <button type="button" class="seg__opt" data-chan="email">Email</button>
      <button type="button" class="seg__opt" data-chan="in_app">In-App</button>
    </div>
  </div>
</div>

<div class="template-grid" data-tpl-grid>
  <?php foreach ($templates as $t):
    $preview = preg_replace('/\{\{\s*([a-z_]+)\s*\}\}/i', '<span class="var">{{$1}}</span>', e($t['body']));
    preg_match_all('/\{\{\s*([a-z_]+)\s*\}\}/i', $t['body'], $matches);
    $vars = array_unique($matches[1]);
  ?>
    <div class="template-card" data-tpl-card data-chan="<?= e($t['channel']) ?>">
      <div class="template-card__head">
        <div class="template-card__titles">
          <div class="template-card__name"><?= e($t['name']) ?></div>
        </div>
        <span class="channel chan--<?= e($t['channel']) ?>"><?= icon($channelIcon[$t['channel']], 12) ?><?= e($channelLabel[$t['channel']]) ?></span>
      </div>
      <div class="template-card__preview"><?= $preview ?></div>
      <div class="template-card__vars">
        <?php foreach ($vars as $v): ?><span class="template-var">{{<?= e($v) ?>}}</span><?php endforeach; ?>
      </div>
      <div class="template-card__foot">
        <span class="template-card__edited">Updated <?= e($t['edited']) ?> by <?= e($t['by']) ?></span>
        <div class="template-card__actions">
          <button class="link-btn" type="button" data-tpl-edit data-name="<?= e($t['name']) ?>" data-body="<?= e($t['body']) ?>"><?= icon('edit', 14) ?>Edit</button>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="sec-head">
  <span class="sec-head__title">Export and archive</span>
</div>
<div class="admin-grid">
  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Export</div>
      <div class="export-list">
        <?php foreach ($exports as $x): ?>
          <div class="export-row">
            <span class="export-row__icon"><?= icon($x['icon'], 16) ?></span>
            <div class="export-row__body">
              <div class="export-row__label"><?= e($x['label']) ?></div>
              <div class="export-row__meta"><?= e($x['meta']) ?></div>
            </div>
            <div class="export-row__actions">
              <?php foreach ($x['formats'] as $fmt): ?>
                <button class="btn btn--secondary btn--sm" type="button"><?= e($fmt) ?></button>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="admin-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Archive old records</div>
        <div class="archive-panel">
          <div class="archive-stat"><span class="archive-stat__label">Eligible now</span><span class="archive-stat__value">4,120 records</span></div>
          <div class="archive-stat"><span class="archive-stat__label">Cutoff</span><span class="archive-stat__value">Before 21 Jul 2024</span></div>
          <div class="archive-stat"><span class="archive-stat__label">Kept for</span><span class="archive-stat__value">7 years</span></div>
          <div class="archive-stat"><span class="archive-stat__label">Last run</span><span class="archive-stat__value">01 Jul 2026</span></div>
          <button class="btn btn--primary btn--block" type="button"><?= icon('archive', 14) ?>Run archive</button>
          <p class="config-row__help">Moves records older than 2 years out of the live system. The audit trail is never archived.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="sec-head">
  <span class="sec-head__title">Live diagnostics</span>
  </div>
<div class="status-grid">
  <?php foreach ($diagnostics as $d): ?>
    <div class="status-tile">
      <div class="status-tile__top">
        <span class="status-tile__label"><?= e($d['label']) ?></span>
        <span class="status-tile__dot status-tile__dot--<?= e($d['state']) ?>"><?= $d['state'] === 'ok' ? 'Healthy' : 'Slow' ?></span>
      </div>
      <div class="status-tile__value"><?= e($d['value']) ?></div>
      <div class="sparkline">
        <?php foreach ($d['bars'] as $height): ?>
          <span class="sparkline__bar" style="height:<?= $height ?>px"></span>
        <?php endforeach; ?>
      </div>
      <div class="status-tile__meta"><?= icon('activity', 12) ?> <?= e($d['target']) ?></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="card mt-7">
  <div class="card__body">
    <div class="staff-eyebrow">System events</div>
    <div class="diagnostics-list">
      <?php foreach ($diagEvents as $ev): ?>
        <div class="diagnostics-row">
          <span class="diagnostics-row__dot diagnostics-row__dot--<?= e($ev['tone']) ?>"></span>
          <div class="diagnostics-row__body">
            <div class="diagnostics-row__text"><?= e($ev['text']) ?></div>
            <div class="diagnostics-row__meta"><?= e($ev['meta']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="modal-backdrop" data-tpl-modal hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="template-title">
    <div class="modal__head">
      <h2 class="modal__title" id="template-title" data-tpl-modal-title>Edit template</h2>
      <button class="modal__close" type="button" data-tpl-close aria-label="Close"><?= icon('close', 18) ?></button>
    </div>
    <div class="modal__body">
      <div class="field">
        <label class="field__label" for="template-body">Message <span class="field__req" aria-hidden="true">*</span></label>
        <textarea class="field__input" id="template-body" rows="4" data-tpl-body></textarea>
        <span class="field__desc">Words in {{double brackets}} are filled in for each patient.</span>
      </div>
      <div class="template-card__preview mt-4" data-tpl-live></div>
    </div>
    <div class="modal__actions">
      <button class="btn btn--secondary" type="button" data-tpl-close>Cancel</button>
      <button class="btn btn--primary" type="button" data-tpl-close>Save template</button>
    </div>
  </div>
</div>
<script src="/assets/js/admin/admin.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>