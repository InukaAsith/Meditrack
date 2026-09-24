<?php

declare(strict_types=1);

$title = 'Alerts & Queue Events';
$active = 'alerts';

require __DIR__ . '/header.php';
?>

<div class="supporting-alerts-header">
  <h1 class="supporting-alerts-header__title">Alerts &amp; queue events</h1>
  <span class="supporting-alerts-header__sub">every event below re-runs the ETA engine &amp; diffs notifications (§5.8)</span>
</div>

<div class="supporting-alerts-card">
  <div class="supporting-alerts-list">
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #FDF3DF; color: #8A5B06;">
        Long wait
      </div>
      <div class="supporting-alert-item__desc">
        Sandanu Dulmeth waited 34 min (limit 30) - Dr. Sample Doctor 2 queue
      </div>
      <div class="supporting-alert-item__time">
        09:35
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="Rebalance">
          Rebalance
        </button>
      </div>
    </div>
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #FCE9EA; color: #C03036;">
        Emergency
      </div>
      <div class="supporting-alert-item__desc">
        K. Ashan Charuka inserted at front of Dr. Sample Doctor 3 - queue paused
      </div>
      <div class="supporting-alert-item__time">
        09:33
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="View">
          View
        </button>
      </div>
    </div>
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #FDF3DF; color: #8A5B06;">
        No-show risk
      </div>
      <div class="supporting-alert-item__desc">
        G. G. Mithun Majika (slot 09:45) not arrived - grace ends 09:55
      </div>
      <div class="supporting-alert-item__time">
        09:32
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="Notify">
          Notify
        </button>
      </div>
    </div>
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #E6F4FB; color: #155E82;">
        Push sent
      </div>
      <div class="supporting-alert-item__desc">
        &quot;Arrive by 10:13&quot; re-sent to Sandanu D. - ETA moved 12 min
      </div>
      <div class="supporting-alert-item__time">
        09:30
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="Log">
          Log
        </button>
      </div>
    </div>
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #FCE9EA; color: #C03036;">
        No-show
      </div>
      <div class="supporting-alert-item__desc">
        M. L. Omindu Gunathilaka finalized as no-show (30 min limit) - refund rule applied
      </div>
      <div class="supporting-alert-item__time">
        09:30
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="Undo">
          Undo
        </button>
      </div>
    </div>
    <div class="supporting-alert-item">
      <div class="supporting-alert-item__badge" style="background: #FDF3DF; color: #8A5B06;">
        Doctor late
      </div>
      <div class="supporting-alert-item__desc">
        Dr. Sample Doctor 2 arrived 09:12 (+12) - 4 downstream ETAs recomputed &amp; pushed
      </div>
      <div class="supporting-alert-item__time">
        09:12
      </div>
      <div class="supporting-alert-item__action">
        <button type="button" class="btn btn--secondary btn--sm supporting-alert-btn" data-action="Log">
          Log
        </button>
      </div>
    </div>
  </div>

</div>

<script src="/assets/js/supporting/alerts.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>