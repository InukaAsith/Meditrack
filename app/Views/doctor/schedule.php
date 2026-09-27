<?php

declare(strict_types=1);

$title  = 'My schedule';
$active = 'schedule';

$visitStatusText = ['completed' => 'Seen', 'no_show' => 'No-show', 'pending' => 'Pending', 'rescheduled' => 'Rescheduled'];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">My schedule</h1>
  </div>
</div>

<?php if (!empty($success)): ?>
  <p class="form-flash form-flash--success mb-6"><?= icon('check', 14) ?> <?= e($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--danger mb-6"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="consultation-sched-controls">
  <div class="consultation-view-toggle">
    <a class="consultation-view-toggle__btn<?= $view === 'month' ? ' is-active' : '' ?>" href="/staff/doctor/schedule?view=month&date=<?= e($date) ?>">Month</a>
    <a class="consultation-view-toggle__btn<?= $view === 'week' ? ' is-active' : '' ?>" href="/staff/doctor/schedule?view=week&date=<?= e($date) ?>">Week</a>
    <a class="consultation-view-toggle__btn<?= $view === 'day' ? ' is-active' : '' ?>" href="/staff/doctor/schedule?view=day&date=<?= e($date) ?>">Day</a>
  </div>
  <div class="consultation-sched-datenav">
    <a href="/staff/doctor/schedule?view=<?= e($view) ?>&date=<?= e($previousDate) ?>" aria-label="Previous">‹</a>
    <span><?= e($dateLabel) ?></span>
    <a href="/staff/doctor/schedule?view=<?= e($view) ?>&date=<?= e($nextDate) ?>" aria-label="Next">›</a>
  </div>
  <?php if ($date !== $today): ?>
    <a class="btn btn--ghost btn--sm" href="/staff/doctor/schedule?view=<?= e($view) ?>&date=<?= e($today) ?>">Today</a>
  <?php endif; ?>
</div>

<div class="consultation-schedule-grid">
  <div class="consultation-sched-main">
    <?php if ($view === 'month'): ?>
      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow"><?= e($monthStart->format('F Y')) ?></div>
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
                 href="/staff/doctor/schedule?view=month&date=<?= e($key) ?>">
                <span class="consultation-month-cell__date"><?= $day['date']->format('j') ?></span>
                <span class="consultation-month-cell__label"><?= e($label) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
          <div class="consultation-month-legend">
            <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--today"></i>Today</span>
            <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--working"></i>Working day</span>
            <?php if ($usesWeekly): ?>
              <span><i class="consultation-month-legend__swatch consultation-month-legend__swatch--leave"></i>Leave</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($view === 'week'): ?>
      <div class="card">
        <div class="card__body">
          <div class="staff-eyebrow">Week of <?= e($weekStart->format('j M')) ?></div>
          <div class="consultation-week-grid">
            <?php for ($i = 0; $i < 7; $i++):
              $key = $weekStart->modify('+' . $i . ' days')->format('Y-m-d');
              $day = $days[$key];

              $hours = [];
              foreach ($day['sessions'] as $session) {
                  $hours[] = substr($session['start_time'], 0, 5) . '–' . substr($session['end_time'], 0, 5);
              }

              $cardClass = 'consultation-week-card';
              if ($day['on_leave']) {
                  $cardClass .= ' consultation-week-card--leave';
              }
              if ($key === $date) {
                  $cardClass .= ' consultation-week-card--selected';
              }
            ?>
              <a class="<?= $cardClass ?>" href="/staff/doctor/schedule?view=week&date=<?= e($key) ?>">
                <div class="consultation-week-card__day"><?= e(strtoupper($day['date']->format('D j'))) ?></div>
                <?php if ($day['on_leave']): ?>
                  <div class="consultation-week-card__hours">On leave</div>
                  <div class="consultation-week-card__cap">-</div>
                <?php elseif ($hours === []): ?>
                  <div class="consultation-week-card__hours">Closed</div>
                  <div class="consultation-week-card__cap">-</div>
                <?php else: ?>
                  <div class="consultation-week-card__hours"><?= e(implode(' + ', $hours)) ?></div>
                  <div class="consultation-week-card__cap"><?= count($day['appointments']) ?> / <?= (int) $day['capacity'] ?></div>
                <?php endif; ?>
              </a>
            <?php endfor; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Sessions &amp; appointments · <?= e($selectedDay['date']->format('l j M Y')) ?></div>

        <?php if ($selectedDay['on_leave']): ?>
          <div class="consultation-day-session">
            <div>
              <div class="consultation-day-session__title">On leave</div>
              <div class="consultation-day-session__hours">No sessions this day.</div>
            </div>
            <span class="badge badge--warning">Leave</span>
          </div>
        <?php elseif ($sessions === []): ?>
          <div class="consultation-day-session">
            <div>
              <div class="consultation-day-session__title">Closed</div>
              <div class="consultation-day-session__hours">No sessions this day.</div>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!$selectedDay['on_leave']): ?>
          <?php foreach ($sessions as $session): ?>
            <div class="consultation-day-session">
              <div>
                <div class="consultation-day-session__title"><?= e($session['title']) ?></div>
                <div class="consultation-day-session__hours"><?= (int) $session['booked'] ?>/<?= (int) $session['capacity'] ?> booked</div>
              </div>
              <?php if ($selectedDay['changed']): ?>
                <span class="badge badge--info">Changed for this day</span>
              <?php endif; ?>
            </div>
            <div class="consultation-slot-list mb-6">
              <?php foreach ($session['rows'] as $row): ?>
                <?php if ($row['kind'] === 'booked'): ?>
                  <div class="consultation-slot consultation-slot--booked">
                    <span class="consultation-slot__time"><?= e($row['time']) ?></span>
                    <div class="consultation-slot__body">
                      <div class="consultation-slot__label"><?= e($row['label']) ?></div>
                      <div class="consultation-slot__sub"><?= e($row['sub']) ?></div>
                    </div>
                    <?php if (isset($visitStatusText[$row['status']])): ?>
                      <span class="badge badge--<?= e(status_tone($row['status'])) ?>"><?= e($visitStatusText[$row['status']]) ?></span>
                    <?php endif; ?>
                  </div>
                <?php elseif ($row['kind'] === 'break'): ?>
                  <div class="consultation-slot consultation-slot--blocked">
                    <span class="consultation-slot__time"><?= e($row['time']) ?></span>
                    <div class="consultation-slot__body">
                      <div class="consultation-slot__label"><?= e($row['label']) ?></div>
                      <div class="consultation-slot__sub"><?= e($row['sub']) ?></div>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="consultation-slot consultation-slot--available">
                    <span class="consultation-slot__time"><?= e($row['time']) ?></span>
                    <div class="consultation-slot__body">
                      <div class="consultation-slot__label">Open slot</div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($otherAppointments !== []): ?>
          <div class="staff-eyebrow">Other appointments this day</div>
          <div class="consultation-slot-list">
            <?php foreach ($otherAppointments as $row): ?>
              <div class="consultation-slot consultation-slot--booked">
                <span class="consultation-slot__time"><?= e($row['time']) ?></span>
                <div class="consultation-slot__body">
                  <div class="consultation-slot__label"><?= e($row['label']) ?></div>
                  <div class="consultation-slot__sub"><?= e($row['sub']) ?></div>
                </div>
                <?php if (isset($visitStatusText[$row['status']])): ?>
                  <span class="badge badge--<?= e(status_tone($row['status'])) ?>"><?= e($visitStatusText[$row['status']]) ?></span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <aside class="consultation-avail">
    <div class="consultation-avail__info">
      Editing hours for <strong><?= e($selectedDay['date']->format('D j M')) ?></strong>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Hours · <?= e($selectedDay['date']->format('D j M')) ?></div>

        <?php if ($date < $today): ?>
          <p class="consultation-avail__note">Past days can't be changed.</p>

        <?php elseif ($date === $today): ?>
          <div class="consultation-avail__today">
            <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);padding-bottom:var(--sp-2);margin-bottom:var(--sp-3)">
              <span style="font-weight:700;font-size:var(--fs-xs);color:var(--text-strong);text-transform:uppercase;letter-spacing:0.04em;">Today's Working Hours</span>
              <span class="badge badge--primary">Today</span>
            </div>

            <?php if ($selectedDay['sessions'] !== []): ?>
              <div style="font-size:var(--fs-xs);color:var(--text);display:flex;flex-direction:column;gap:var(--sp-2);margin-bottom:var(--sp-4);">
                <?php foreach ($selectedDay['sessions'] as $s): ?>
                  <div style="padding:var(--sp-2) var(--sp-3);background:var(--surface, #ffffff);border:1px solid var(--border);border-radius:var(--r-sm);display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:600;"><?= e(substr($s['start_time'], 0, 5)) ?> – <?= e(substr($s['end_time'], 0, 5)) ?></span>
                    <span style="color:var(--text-muted);">(Capacity: <?= (int) $s['capacity'] ?>)</span>
                  </div>
                <?php endforeach; ?>
              </div>

              <div style="border-top:1px solid var(--border);padding-top:var(--sp-4);margin-top:var(--sp-2);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--sp-2);">
                  <span style="font-weight:700;font-size:var(--fs-xs);color:var(--text-strong);text-transform:uppercase;letter-spacing:0.04em;">Today's Breaks</span>
                  <span class="badge badge--neutral"><?= count($selectedDay['breaks']) ?> scheduled</span>
                </div>

                <form method="post" action="/staff/doctor/availability-save" id="today-break-form">
                  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                  <input type="hidden" name="date" value="<?= e($date) ?>">
                  <input type="hidden" name="view" value="<?= e($view) ?>">
                  <input type="hidden" name="action_type" value="add_break">

                  <?php foreach ($selectedDay['sessions'] as $idx => $s): ?>
                    <input type="hidden" name="start_<?= $idx + 1 ?>" value="<?= e(substr($s['start_time'], 0, 5)) ?>">
                    <input type="hidden" name="end_<?= $idx + 1 ?>" value="<?= e(substr($s['end_time'], 0, 5)) ?>">
                    <input type="hidden" name="capacity_<?= $idx + 1 ?>" value="<?= (int) $s['capacity'] ?>">
                  <?php endforeach; ?>

                  <?php if (!empty($selectedDay['breaks'])): ?>
                    <div style="margin-bottom:var(--sp-3);">
                      <?php foreach ($selectedDay['breaks'] as $index => $break):
                        $from = substr($break['from_time'], 0, 5);
                        $to = substr($break['to_time'], 0, 5);
                      ?>
                        <div class="consultation-break-row" style="margin-bottom:var(--sp-2);">
                          <input type="hidden" name="breaks[<?= $index ?>][from]" value="<?= e($from) ?>">
                          <input type="hidden" name="breaks[<?= $index ?>][to]" value="<?= e($to) ?>">
                          <input type="hidden" name="breaks[<?= $index ?>][label]" value="<?= e($break['label']) ?>">
                          <span class="consultation-break-row__time"><?= e($from) ?> – <?= e($to) ?></span>
                          <span class="consultation-break-row__label"><?= e($break['label']) ?></span>
                          <label class="consultation-break-row__remove-label">
                            <input type="checkbox" name="breaks[<?= $index ?>][remove]" value="1"> Remove
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <p style="font-size:var(--fs-xs);color:var(--text-muted);margin:0 0 var(--sp-3);">No breaks scheduled for today.</p>
                  <?php endif; ?>

                  <div class="consultation-avail__group mt-3">
                    <div class="consultation-avail__label">Add a break during session hours</div>
                    <div class="consultation-avail__time-inputs">
                      <input type="time" name="new_break_from" id="new_break_from" aria-label="Break start">
                      <span class="consultation-avail__time-sep">to</span>
                      <input type="time" name="new_break_to" id="new_break_to" aria-label="Break end">
                    </div>
                    <input class="consultation-avail__text mt-2" type="text" name="new_break_label" id="new_break_label" maxlength="60" placeholder="Name, e.g. Lunch">

                    <button class="btn btn--secondary btn--block mt-3" type="submit">
                      + Add break
                    </button>
                  </div>
                </form>
              </div>
            <?php else: ?>
              <p class="consultation-avail__note">Clinic is closed today. Breaks cannot be added without active sessions.</p>
            <?php endif; ?>
          </div>

        <?php elseif ($date < $minEditableDate): ?>
          <div class="consultation-avail__locked" style="padding:var(--sp-3);background:var(--surface-muted, #f8fafc);border:1px solid var(--border);border-radius:var(--r-sm);margin-bottom:var(--sp-4);">
            <div style="font-weight:600;color:var(--text);display:flex;align-items:center;gap:var(--sp-2);font-size:var(--fs-sm);">
              <span>🔒 Schedule locked</span>
            </div>
            <p style="margin:var(--sp-1) 0 0;font-size:var(--fs-xs);color:var(--text-muted);">
              Schedules cannot be edited or changed less than 1 week in advance.
            </p>
          </div>
          <?php if ($selectedDay['sessions'] !== []): ?>
            <div class="consultation-avail__summary" style="font-size:var(--fs-xs);color:var(--text);display:flex;flex-direction:column;gap:var(--sp-2)">
              <div style="font-weight:600;color:var(--text-strong)">Scheduled sessions:</div>
              <?php foreach ($selectedDay['sessions'] as $s): ?>
                <div style="padding:var(--sp-2) var(--sp-3);background:var(--surface, #ffffff);border:1px solid var(--border);border-radius:var(--r-sm);display:flex;justify-content:space-between;align-items:center;">
                  <span><?= e(substr($s['start_time'], 0, 5)) ?> – <?= e(substr($s['end_time'], 0, 5)) ?></span>
                  <span style="color:var(--text-muted);">(Capacity: <?= (int) $s['capacity'] ?>)</span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="consultation-avail__note">Closed this day.</p>
          <?php endif; ?>

        <?php else: ?>
          <?php if ($selectedDay['changed'] && $selectedDay['sessions'] !== []): ?>
            <p class="consultation-avail__note">Changed for this day only.</p>
          <?php elseif ($selectedDay['sessions'] === []): ?>
            <p class="consultation-avail__note">Closed. Add a session and save to mark this day available.</p>
          <?php else: ?>
            <p class="consultation-avail__note">Uses your weekly schedule.</p>
          <?php endif; ?>

          <form method="post" action="/staff/doctor/availability-save" id="availability-form">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="date" value="<?= e($date) ?>">
            <input type="hidden" name="view" value="<?= e($view) ?>">
            <input type="hidden" name="action_type" value="save_hours">

            <div class="consultation-avail__section">
              <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);padding-bottom:var(--sp-2);margin-bottom:var(--sp-3)">
                <span style="font-weight:700;font-size:var(--fs-xs);color:var(--text-strong);text-transform:uppercase;letter-spacing:0.04em;">Schedule Settings</span>
                <span class="badge badge--neutral">Working Hours</span>
              </div>

              <?php foreach ([1, 2] as $number):
                $session = $selectedDay['sessions'][$number - 1] ?? null;
                $sStart = $session ? substr($session['start_time'], 0, 5) : '';
                $sEnd = $session ? substr($session['end_time'], 0, 5) : '';
                $sMaxSlots = 0;
                if ($sStart !== '' && $sEnd !== '' && $sStart < $sEnd) {
                    $sDur = ((int) substr($sEnd, 0, 2) * 60 + (int) substr($sEnd, 3, 2)) -
                            ((int) substr($sStart, 0, 2) * 60 + (int) substr($sStart, 3, 2));
                    $sMaxSlots = intdiv($sDur, (int) $slotLength);
                }
                $sVal = $session
                    ? ($sMaxSlots > 0 ? min((int) $session['capacity'], $sMaxSlots) : (int) $session['capacity'])
                    : ($sMaxSlots > 0 ? $sMaxSlots : '');
              ?>
                <div class="consultation-avail__group mt-4">
                  <div class="consultation-avail__label"><?= $number === 1 ? 'First session' : 'Second session (optional)' ?></div>
                  <div class="consultation-avail__time-inputs">
                    <input type="time" name="start_<?= $number ?>" value="<?= e($sStart) ?>" aria-label="Start time"<?= $number === 1 ? ' required' : '' ?>>
                    <span class="consultation-avail__time-sep">to</span>
                    <input type="time" name="end_<?= $number ?>" value="<?= e($sEnd) ?>" aria-label="End time"<?= $number === 1 ? ' required' : '' ?>>
                  </div>
                  <?php if ($number === 2): ?>
                    <div id="session-overlap-alert" style="display:none;font-size:var(--fs-xs);color:var(--danger, #dc2626);margin-top:var(--sp-1);font-weight:500;">
                      ⚠️ Second session cannot overlap with the first session.
                    </div>
                  <?php endif; ?>
                  <div class="consultation-avail__row">
                    <span class="consultation-avail__row-label">Patient capacity</span>
                    <input class="consultation-avail__number" type="number" name="capacity_<?= $number ?>" min="1" max="<?= $sMaxSlots > 0 ? $sMaxSlots : 200 ?>"
                           value="<?= $sVal ?>" placeholder="<?= $sMaxSlots > 0 ? $sMaxSlots : '' ?>">
                  </div>
                  <div id="capacity-hint-<?= $number ?>" style="font-size:var(--fs-xs);color:var(--text-muted);margin-top:var(--sp-1);">
                    <?= $sMaxSlots > 0 ? "Max {$sMaxSlots} patients ({$slotLength} min slots)" : "Enter session hours to calculate slots ({$slotLength} min each)" ?>
                  </div>
                </div>
              <?php endforeach; ?>

              <button class="btn btn--primary btn--block mt-4" type="submit">
                <?= $selectedDay['sessions'] === [] ? 'Mark available' : 'Save schedule' ?>
              </button>
            </div>
          </form>

          <?php if ($selectedDay['changed'] && !$usesWeekly): ?>
            <form method="post" action="/staff/doctor/availability-reset" class="mt-4">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
              <input type="hidden" name="date" value="<?= e($date) ?>">
              <input type="hidden" name="view" value="<?= e($view) ?>">
              <button class="btn btn--ghost btn--block" type="submit">Close this day</button>
            </form>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($usesWeekly): ?>
    <div class="consultation-leave-card">
      <div class="consultation-leave-card__title">Mark leave / unavailable</div>
      <form method="post" action="/staff/doctor/leave-create">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <div class="consultation-leave-dates">
          <input type="date" name="start_date" value="<?= e(max($date, $today)) ?>" required title="Start date">
          <input type="date" name="end_date" value="<?= e(max($date, $today)) ?>" required title="End date">
        </div>
        <input type="text" name="reason" placeholder="Reason (optional)…" style="width:100%;padding:var(--sp-3) var(--sp-4);border:1px solid var(--border);border-radius:var(--r-sm);font-size:var(--fs-sm);margin-bottom:var(--sp-5)">
        <button class="btn btn--dark btn--block" type="submit">Mark leave</button>
      </form>
    </div>

    <div class="card mt-6">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>My Leaves</span>
          <span class="badge badge--neutral"><?= count($leaves ?? []) ?></span>
        </div>

        <?php if (empty($leaves)): ?>
          <div style="font-size:var(--fs-xs);color:var(--text-muted);text-align:center;padding:var(--sp-4) 0">
            No leave records marked yet.
          </div>
        <?php else: ?>
          <div class="consultation-leave-list" style="display:flex;flex-direction:column;gap:var(--sp-4);margin-top:var(--sp-3)">
            <?php
            $today = date('Y-m-d');
            foreach ($leaves as $l):
              $isPast = $l['end_date'] < $today;
              $isActive = ($l['start_date'] <= $today && $l['end_date'] >= $today);
              $days = (int) ($l['days_count'] ?? 1);
            ?>
              <div style="padding:var(--sp-3);background:var(--surface-muted, #f8fafc);border:1px solid var(--border);border-radius:var(--r-sm);font-size:var(--fs-xs)">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--sp-2)">
                  <strong style="color:var(--text-strong)">
                    <?= date('d M Y', strtotime($l['start_date'])) ?>
                    <?php if ($l['start_date'] !== $l['end_date']): ?>
                      – <?= date('d M Y', strtotime($l['end_date'])) ?>
                    <?php endif; ?>
                  </strong>
                  <?php if ($isActive): ?>
                    <span class="badge badge--warning">Active today</span>
                  <?php elseif ($isPast): ?>
                    <span class="badge badge--muted">Past</span>
                  <?php else: ?>
                    <span class="badge badge--primary">Upcoming</span>
                  <?php endif; ?>
                </div>

                <div style="color:var(--text-subtle);margin-bottom:var(--sp-2)">
                  <?= $days ?> day<?= $days === 1 ? '' : 's' ?>
                  <?php if (!empty($l['reason'])): ?>
                    · <?= e($l['reason']) ?>
                  <?php endif; ?>
                </div>

                <div style="display:flex;gap:var(--sp-3);align-items:center;margin-top:var(--sp-2);border-top:1px dashed var(--border);padding-top:var(--sp-2)">
                  <a class="link-act" href="/staff/doctor/leave-edit/<?= (int) $l['doctor_leave_id'] ?>">Edit</a>
                  <span style="color:var(--text-subtle)">·</span>
                  <form method="post" action="/staff/doctor/leave-delete/<?= (int) $l['doctor_leave_id'] ?>" style="display:inline" data-confirm="Are you sure you want to cancel this leave period? Your schedule for this day will be restored." data-confirm-title="Cancel Leave Period" data-confirm-ok="Cancel Leave" data-confirm-danger="true">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="link-act link-act--danger" style="background:none;border:none;padding:0;cursor:pointer;font-size:inherit;font-family:inherit;">Cancel</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </aside>
</div>

<script>
(function() {
  const form = document.getElementById('availability-form');
  if (!form) return;

  const slotLength = <?= (int) ($slotLength ?? 15) ?>;
  const start1 = form.querySelector('input[name="start_1"]');
  const end1 = form.querySelector('input[name="end_1"]');
  const start2 = form.querySelector('input[name="start_2"]');
  const end2 = form.querySelector('input[name="end_2"]');
  const overlapAlert = document.getElementById('session-overlap-alert');
  const saveBtn = form.querySelector('button[type="submit"]');

  function parseMinutes(t) {
    if (!t || !t.includes(':')) return null;
    const parts = t.split(':').map(Number);
    return parts[0] * 60 + parts[1];
  }

  function updateCapacity(num) {
    const sInput = form.querySelector(`input[name="start_${num}"]`);
    const eInput = form.querySelector(`input[name="end_${num}"]`);
    const cInput = form.querySelector(`input[name="capacity_${num}"]`);
    const hint = document.getElementById(`capacity-hint-${num}`);
    if (!sInput || !eInput || !cInput) return 0;

    const s = parseMinutes(sInput.value.trim());
    const e = parseMinutes(eInput.value.trim());

    if (s !== null && e !== null && e > s) {
      const dur = e - s;
      const maxSlots = Math.floor(dur / slotLength);
      if (maxSlots > 0) {
        cInput.max = maxSlots;
        const cur = parseInt(cInput.value, 10);
        if (!cur || isNaN(cur) || cur > maxSlots || cInput.dataset.auto === '1') {
          cInput.value = maxSlots;
          cInput.dataset.auto = '1';
        }
        if (hint) {
          hint.textContent = `Max ${maxSlots} patients (${slotLength} min slots for ${dur}m session)`;
          hint.style.color = 'var(--text-muted)';
        }
        return maxSlots;
      } else {
        cInput.max = 0;
        cInput.value = '';
        if (hint) {
          hint.textContent = `Duration (${dur}m) must be at least ${slotLength}m (one slot)`;
          hint.style.color = 'var(--danger, #dc2626)';
        }
        return -1;
      }
    } else {
      if (hint) {
        hint.textContent = `Enter session hours to calculate slots (${slotLength} min each)`;
        hint.style.color = 'var(--text-muted)';
      }
      return 0;
    }
  }

  function checkSessionOverlap() {
    if (!start1 || !end1 || !start2 || !end2 || !overlapAlert) return false;
    const s1 = start1.value.trim();
    const e1 = end1.value.trim();
    const s2 = start2.value.trim();
    const e2 = end2.value.trim();

    if (s1 && e1 && s2 && e2) {
      if (s1 < e2 && s2 < e1) {
        overlapAlert.style.display = 'block';
        if (saveBtn) saveBtn.disabled = true;
        return true;
      }
    }
    overlapAlert.style.display = 'none';
    if (saveBtn) saveBtn.disabled = false;
    return false;
  }

  [1, 2].forEach(num => {
    const sInput = form.querySelector(`input[name="start_${num}"]`);
    const eInput = form.querySelector(`input[name="end_${num}"]`);
    const cInput = form.querySelector(`input[name="capacity_${num}"]`);

    function onTimeChange() {
      updateCapacity(num);
      checkSessionOverlap();
    }

    if (sInput) {
      sInput.addEventListener('input', onTimeChange);
      sInput.addEventListener('change', onTimeChange);
    }
    if (eInput) {
      eInput.addEventListener('input', onTimeChange);
      eInput.addEventListener('change', onTimeChange);
    }
    if (cInput) {
      cInput.addEventListener('input', () => {
        cInput.dataset.auto = '0';
        const max = parseInt(cInput.max, 10);
        const val = parseInt(cInput.value, 10);
        if (max > 0 && val > max) {
          cInput.value = max;
        }
      });
    }
  });

  form.addEventListener('submit', function(e) {
    if (checkSessionOverlap()) {
      e.preventDefault();
      window.showAlertDialog({ title: 'Schedule Conflict', message: 'The second session cannot overlap with the first session.' });
      return;
    }

    for (const num of [1, 2]) {
      const sInput = form.querySelector(`input[name="start_${num}"]`);
      const eInput = form.querySelector(`input[name="end_${num}"]`);
      const cInput = form.querySelector(`input[name="capacity_${num}"]`);
      if (sInput && eInput && cInput && sInput.value.trim() && eInput.value.trim()) {
        const s = parseMinutes(sInput.value.trim());
        const eMin = parseMinutes(eInput.value.trim());
        if (s !== null && eMin !== null && eMin > s) {
          const maxSlots = Math.floor((eMin - s) / slotLength);
          if (maxSlots <= 0) {
            e.preventDefault();
            window.showAlertDialog({ title: 'Invalid Session Duration', message: `Session ${num} duration must be at least ${slotLength} minutes.` });
            return;
          }
          const cap = parseInt(cInput.value, 10);
          if (!cap || cap < 1 || cap > maxSlots) {
            e.preventDefault();
            window.showAlertDialog({ title: 'Invalid Session Capacity', message: `Session ${num} capacity cannot exceed ${maxSlots} patients.` });
            return;
          }
        }
      }
    }
  });
})();

(function() {
  const breakForm = document.getElementById('today-break-form');
  if (!breakForm) return;

  breakForm.addEventListener('submit', function(e) {
    const from = document.getElementById('new_break_from')?.value.trim();
    const to = document.getElementById('new_break_to')?.value.trim();
    const hasRemove = Array.from(breakForm.querySelectorAll('input[type="checkbox"][name*="[remove]"]')).some(cb => cb.checked);

    if (!from && !to && hasRemove) {
      return;
    }

    if (!from || !to) {
      e.preventDefault();
      window.showAlertDialog({ title: 'Break Schedule', message: 'Please specify both a start time and an end time to add a break.' });
      return;
    }

    if (from >= to) {
      e.preventDefault();
      window.showAlertDialog({ title: 'Break Schedule', message: 'Break start time must be before end time.' });
      return;
    }
  });
})();
</script>

<?php require __DIR__ . '/footer.php'; ?>
