<?php

declare(strict_types=1);

$title = 'Approvals';
$active = 'approvals';

$pendingCount = count($requests) + 1;

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Approvals</h1>
    <div class="staff-head__sub"><?= $pendingCount ?> waiting for you</div>
  </div>
  <div class="staff-head__actions">
    <span class="badge badge--warning"><?= $pendingCount ?> pending</span>
  </div>
</div>

<?php if (!empty($success)): ?>
  <p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="stack">
  <?php foreach ($requests as $request): ?>
    <div class="approval">
      <?php if ($request['request_type'] === 'fee_revision'): ?>
        <div class="approval__top">
          <span class="approval__icon"><?= icon('billing', 18) ?></span>
          <div class="approval__body">
            <div class="approval__title">Dr. <?= e($request['doctor_name']) ?> - consultation fees</div>
            <div class="approval__meta">Fee change, asked on <?= e(date('j M, H:i', strtotime($request['requested_at']))) ?></div>
          </div>
          <span class="badge badge--muted">Doctor fees</span>
        </div>
        <div class="approval__change">
          <span class="approval__day">Consultation</span>
          <span class="approval__from"><?= e(money($request['consultation_fee'])) ?></span>
          <span class="approval__arrow"><?= icon('arrowRight', 16) ?></span>
          <span class="approval__to"><?= e(money($request['proposed_consultation_fee'])) ?></span>
        </div>
        <div class="approval__change">
          <span class="approval__day">Follow-up</span>
          <span class="approval__from"><?= $request['followup_fee'] !== null ? e(money($request['followup_fee'])) : 'not set' ?></span>
          <span class="approval__arrow"><?= icon('arrowRight', 16) ?></span>
          <span class="approval__to"><?= $request['proposed_followup_fee'] !== null ? e(money($request['proposed_followup_fee'])) : 'not set' ?></span>
        </div>
      <?php else: ?>
        <div class="approval__top">
          <span class="approval__icon"><?= icon('calendar', 18) ?></span>
          <div class="approval__body">
            <div class="approval__title">Dr. <?= e($request['doctor_name']) ?> - weekly schedule</div>
            <div class="approval__meta">Doctor schedule, asked on <?= e(date('j M, H:i', strtotime($request['requested_at']))) ?></div>
          </div>
          <span class="badge badge--muted">Doctor schedule</span>
        </div>
        <?php if ($request['day_changes'] === []): ?>
          <div class="approval__desc">Same hours as now.</div>
        <?php endif; ?>
        <?php foreach ($request['day_changes'] as $change): ?>
          <div class="approval__change">
            <span class="approval__day"><?= e($change['day']) ?></span>
            <span class="approval__from"><?= e($change['from']) ?></span>
            <span class="approval__arrow"><?= icon('arrowRight', 16) ?></span>
            <span class="approval__to"><?= e($change['to']) ?></span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <form class="approval__actions" method="post" action="/staff/manager/approval-decide/<?= (int) $request['approval_request_id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button class="btn btn--success btn--sm" type="submit" name="decision" value="approve"><?= icon('check', 14) ?> Approve</button>
        <button class="btn btn--secondary btn--sm" type="submit" name="decision" value="reject"><?= icon('close', 14) ?> Reject</button>
      </form>
    </div>
  <?php endforeach; ?>

  <div class="approval">
    <div class="approval__top">
      <span class="approval__icon"><?= icon('pill', 18) ?></span>
      <div class="approval__body">
        <div class="approval__title">Amoxicillin 500mg - unit price</div>
        <div class="approval__meta">Drug pricing, asked by Mithun (Pharmacist) 2 hours ago</div>
      </div>
      <span class="badge badge--muted">Drug pricing</span>
    </div>
    <div class="approval__change">
      <span class="approval__from">Rs. 12.00</span>
      <span class="approval__arrow"><?= icon('arrowRight', 16) ?></span>
      <span class="approval__to">Rs. 14.50</span>
    </div>
    <div class="approval__actions">
      <button class="btn btn--success btn--sm" type="button"><?= icon('check', 14) ?> Approve</button>
      <button class="btn btn--secondary btn--sm" type="button"><?= icon('close', 14) ?> Reject</button>
    </div>
  </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>