<?php

declare(strict_types=1);

$title  = 'Dashboard';
$active = 'dashboard';

$visitStatusText = ['completed' => 'Seen', 'no_show' => 'No-show', 'pending' => 'Pending', 'rescheduled' => 'Rescheduled'];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e($staff['name']) ?></h1>
    <?php
    $subText = 'No session scheduled today';
    if (!empty($todayDay['on_leave'])) {
        $subText = 'On leave today' . (!empty($todayDay['leave_reason']) ? ' (' . $todayDay['leave_reason'] . ')' : '');
    } elseif (!empty($todayDay['sessions'])) {
        $firstS = $todayDay['sessions'][0];
        $subText = 'Session ' . substr($firstS['start_time'], 0, 5) . '–' . substr($firstS['end_time'], 0, 5) . ' · ' . count($todayDay['appointments']) . ' patients booked';
    }
    ?>
    <div class="staff-head__sub"><?= e($subText) ?></div>
  </div>
</div>

<?php if (!empty($success)): ?>
  <p class="form-flash form-flash--success mb-6"><?= icon('check', 14) ?> <?= e($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--danger mb-6"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="staff-grid">
  <div>
    <div class="consultation-hero mb-7">
      <div class="consultation-hero__patient">
        <span class="consultation-hero__avatar">KI</span>
        <div class="consultation-hero__info">
          <div class="consultation-hero__name-group">
            <h2 class="consultation-hero__name">K.A. Inuka Asith</h2>
          </div>
          <div class="consultation-hero__meta">
            58y · Female · PT-1088 · NIC 687301245V · O+ · +94 77 234 5612
          </div>
        </div>
      </div>

      <div class="consultation-hero__chips">
        <div class="consultation-hero__allergies">
          <span class="consultation-allergy-chip"><?= icon('alert', 12) ?> Penicillin</span>
          <span class="consultation-allergy-chip consultation-allergy-chip--moderate"><?= icon('alert', 12) ?> Ibuprofen</span>
        </div>
        <div class="consultation-hero__vitals">
          <span class="consultation-hero__vital-pill">
            <span class="vital-label">BP</span>
            <span class="vital-val vital-val--warning">130/85 mmHg</span>
          </span>
          <span class="consultation-hero__vital-pill">
            <span class="vital-label">Pulse</span>
            <span class="vital-val">78 bpm</span>
          </span>
          <span class="consultation-hero__vital-pill">
            <span class="vital-label">Temp</span>
            <span class="vital-val">37.1 °C</span>
          </span>
          <span class="consultation-hero__vital-pill">
            <?= icon('file', 12) ?>
            <span class="vital-val">1 lab report</span>
          </span>
        </div>
      </div>

      <div class="consultation-hero__actions">
        <a class="btn btn--primary btn--sm" href="/staff/doctor/current-patient"><?= icon('arrowRight', 14) ?> Open consultation workspace</a>
        <button class="btn btn--secondary btn--sm" type="button"><?= icon('check', 14) ?> Complete consultation</button>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Message board</span>
        </div>
        <div class="message-board">
          <div class="message-board__post">
            <span class="message-board__avatar">SD</span>
            <div class="message-board__body">
              <strong>Sandanu (reception):</strong>
              <p>PT-0967 rescheduled to 10:15 - walk-in token issued.</p>
              <span class="message-board__time">09:22</span>
            </div>
          </div>
          <div class="message-board__post">
            <span class="message-board__avatar">AS</span>
            <div class="message-board__body">
              <strong>Dr. Sample Doctor 1:</strong>
              <p>Keep 12:30–13:00 free - hospital call expected.</p>
              <span class="message-board__time">08:40</span>
            </div>
          </div>
        </div>
        <div class="message-board__compose">
          <input type="text" id="msg-compose" placeholder="Message reception / staff…" aria-label="Post to message board">
          <button class="btn btn--primary btn--sm" type="button">Post</button>
        </div>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Up next</span>
          <button class="btn btn--primary btn--sm" type="button">Call next</button>
        </div>
        <div class="consultation-upnext">
          <div class="consultation-upnext__row">
            <span class="consultation-upnext__pos">1</span>
            <span class="consultation-upnext__name">K. Ashan Charuka</span>
            <span class="badge badge--success">Checked in</span>
            <span class="consultation-upnext__eta">~10:00</span>
          </div>
          <div class="consultation-upnext__row">
            <span class="consultation-upnext__pos">2</span>
            <span class="consultation-upnext__name">Sandanu Dulmeth</span>
            <span class="badge badge--muted">Not arrived yet</span>
            <span class="consultation-upnext__eta">~10:15</span>
          </div>
          <div class="consultation-upnext__row">
            <span class="consultation-upnext__pos">3</span>
            <span class="consultation-upnext__name">G. G. Mithun Majika</span>
            <span class="badge badge--success">Checked in</span>
            <span class="consultation-upnext__eta">~10:30</span>
          </div>
          <div class="consultation-upnext__row">
            <span class="consultation-upnext__pos">4</span>
            <span class="consultation-upnext__name">Nimsith Wickrama</span>
            <span class="badge badge--success">Checked in</span>
            <span class="consultation-upnext__eta">~10:45</span>
          </div>
          <div class="consultation-upnext__row">
            <span class="consultation-upnext__pos">5</span>
            <span class="consultation-upnext__name">M. L. Omindu Gunathilaka</span>
            <span class="badge badge--muted">Not arrived yet</span>
            <span class="consultation-upnext__eta">~11:00</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mt-8">
  <div class="card__body">
    <div class="staff-eyebrow staff-eyebrow--row mb-6">
      <span>My schedule - <?= e($dateLabel) ?></span>
      <div style="display:flex;gap:var(--sp-4);align-items:center;flex-wrap:wrap">
        <div class="consultation-sched-datenav">
          <a href="/staff/doctor/dashboard?date=<?= e($previousDate) ?>" aria-label="Previous">‹</a>
          <span><?= e($dateLabel) ?></span>
          <a href="/staff/doctor/dashboard?date=<?= e($nextDate) ?>" aria-label="Next">›</a>
        </div>
        <?php if ($date !== $today): ?>
          <a class="btn btn--ghost btn--sm" href="/staff/doctor/dashboard?date=<?= e($today) ?>">Today</a>
        <?php endif; ?>
        <a class="btn btn--secondary btn--sm" href="/staff/doctor/schedule?view=month&date=<?= e($date) ?>">Full schedule</a>
      </div>
    </div>

    <div>
      <div class="consultation-month-grid">
        <div class="consultation-month-head">MON</div>
        <div class="consultation-month-head">TUE</div>
        <div class="consultation-month-head">WED</div>
        <div class="consultation-month-head">THU</div>
        <div class="consultation-month-head">FRI</div>
        <div class="consultation-month-head">SAT</div>
        <div class="consultation-month-head">SUN</div>

        <?php for ($i = 1; $i < (int) $monthStart->format('N'); $i++): ?>
          <div class="consultation-month-cell consultation-month-cell--blank"></div>
        <?php endfor; ?>

        <?php
        $monthKey = $monthStart->format('Y-m');
        foreach ($days as $key => $day):
          if (substr($key, 0, 7) !== $monthKey) {
              continue;
          }

          $booked = count($day['appointments']);
          if ($day['on_leave']) {
              $kind = 'leave';
              $label = $key === $today ? 'today · leave' : 'leave';
          } elseif ($day['sessions'] === []) {
              $kind = $key === $today ? 'today' : 'closed';
              $label = $booked > 0 ? $booked . ' booked' : 'closed';
          } elseif ($key === $today) {
              $kind = 'today';
              $label = $booked . '/' . $day['capacity'] . ' booked';
          } elseif ($key < $today) {
              $kind = 'past';
              $label = $booked . ' booked';
          } else {
              $kind = 'future';
              $label = $booked . '/' . $day['capacity'] . ' booked';
          }
        ?>
          <a class="consultation-month-cell consultation-month-cell--<?= $kind ?><?= $key === $date ? ' is-selected' : '' ?><?= $key === $today ? ' is-today' : '' ?>"
             href="/staff/doctor/schedule?view=day&date=<?= e($key) ?>"
             title="<?= $day['date']->format('l, j F Y') ?><?= $day['on_leave'] && !empty($day['leave_reason']) ? ' (Leave: ' . e($day['leave_reason']) . ')' : '' ?>">
            <span class="consultation-month-cell__date"><?= $day['date']->format('j') ?></span>
            <span class="consultation-month-cell__label"><?= e($label) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="consultation-month-legend mt-4">
        <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--today"></i>Today</span>
        <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--working"></i>Working day</span>
        <?php if ($usesWeekly): ?>
          <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--leave"></i>Leave</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/dashboard.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>