<?php

declare(strict_types=1);

$title  = 'Configure';
$active = 'configure';
$doctor = $doctor ?? [];
$feeRequest = $feeRequest ?? null;
$week = $week ?? [1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => [], 7 => []];

$usesWeekly = (int) $doctor['uses_regular_schedule'] === 1;

$dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];

$consultationFee = $feeRequest ? $feeRequest['proposed_consultation_fee'] : $doctor['consultation_fee'];
$followupFee = $feeRequest ? $feeRequest['proposed_followup_fee'] : $doctor['followup_fee'];

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Configure</h1>
  </div>
</div>

<?php if (!empty($success)): ?>
  <p class="form-flash form-flash--success mb-6"><?= icon('check', 14) ?> <?= e($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <p class="form-flash form-flash--danger mb-6"><?= icon('alert', 14) ?> <?= e($error) ?></p>
<?php endif; ?>

<div class="consultation-config-grid">
  <form class="consultation-config-card" method="post" action="/staff/doctor/configure-defaults">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="consultation-config-card__head">
      <div class="consultation-config-card__title">Consultation &amp; scheduling defaults</div>
    </div>
    <div class="consultation-config-card__body">
      <div class="consultation-config-row">
        <label class="consultation-config-row__label" for="slot-length">Slot length</label>
        <select class="consultation-config-select" id="slot-length" name="slot_length_min">
          <?php foreach ([10, 15, 20, 30] as $minutes): ?>
            <option value="<?= $minutes ?>"<?= (int) $doctor['slot_length_min'] === $minutes ? ' selected' : '' ?>><?= $minutes ?> min</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="consultation-config-row">
        <label class="consultation-config-row__label" for="overtime-warn">Overtime warning</label>
        <select class="consultation-config-select" id="overtime-warn" name="overtime_warn_min">
          <?php foreach ([20, 25, 30] as $minutes): ?>
            <option value="<?= $minutes ?>"<?= (int) $doctor['overtime_warn_min'] === $minutes ? ' selected' : '' ?>><?= $minutes ?> min</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="consultation-config-row">
        <div>
          <label class="consultation-config-row__label" for="default-capacity">Default patient capacity / day</label>
          <div class="consultation-config-row__hint" id="default-cap-hint">e.g. for an 8h day at <?= (int) $doctor['slot_length_min'] ?> min/slot: max <?= intdiv(480, (int) $doctor['slot_length_min']) ?> patients</div>
        </div>
        <input class="consultation-config-input" id="default-capacity" type="number" name="default_capacity" min="1" max="200" value="<?= (int) $doctor['default_capacity'] ?>">
      </div>
      <button class="btn btn--primary btn--block mt-6" type="submit">Save defaults</button>
    </div>
  </form>

  <form class="consultation-config-card" method="post" action="/staff/doctor/configure-fees">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="consultation-config-card__head">
      <div class="consultation-config-card__title">Consultation charges</div>
    </div>
    <div class="consultation-config-card__body">
      <div class="consultation-config-row">
        <div>
          <label class="consultation-config-row__label" for="consultation-fee">Consultation fee</label>
          <div class="consultation-config-row__hint">Now <?= e(money($doctor['consultation_fee'])) ?></div>
        </div>
        <div class="consultation-config-fee">
          Rs.
          <input class="consultation-config-input" id="consultation-fee" type="number" name="consultation_fee" min="1" step="0.01" value="<?= e($consultationFee) ?>" required>
        </div>
      </div>
      <div class="consultation-config-row">
        <div>
          <label class="consultation-config-row__label" for="followup-fee">Follow-up fee</label>
          <div class="consultation-config-row__hint">
            <?= $doctor['followup_fee'] !== null ? 'Now ' . e(money($doctor['followup_fee'])) : 'Not set' ?>
          </div>
        </div>
        <div class="consultation-config-fee">
          Rs.
          <input class="consultation-config-input" id="followup-fee" type="number" name="followup_fee" min="1" step="0.01" value="<?= e($followupFee) ?>">
        </div>
      </div>
      <button class="btn btn--secondary btn--block mt-6" type="submit">Submit fee update</button>
    </div>
  </form>

  <form class="consultation-config-card consultation-config-card--wide" method="post" action="/staff/doctor/configure-schedule">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="consultation-config-card__head consultation-config-card__head--row">
      <div class="consultation-config-card__title">Regular weekly schedule</div>
      <?php if ($usesWeekly): ?>
        <button class="consultation-toggle is-on" type="submit" form="regular-schedule-off" aria-pressed="true" aria-label="Turn weekly schedule off"></button>
      <?php else: ?>
        <button class="consultation-toggle" type="submit" form="regular-schedule-switch" name="enabled" value="1" aria-pressed="false" aria-label="Turn weekly schedule on"></button>
      <?php endif; ?>
    </div>
    <div class="consultation-config-card__body">
      <?php if (!$usesWeekly): ?>
        <p class="consultation-config-row__hint">
          You don't work to a weekly schedule, so every day is closed until you open it.
          Pick a day on <a class="link-act" href="/staff/doctor/schedule">My schedule</a> and add a session to mark it available.
        </p>
      <?php else: ?>
      <div class="data-table-wrap">
        <table class="consultation-sched-table">
          <thead>
            <tr>
              <th>Day</th>
              <th>Active</th>
              <th>Hours</th>
              <th>Capacity</th>
              <th>Second session</th>
              <th>Capacity</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $slotLength = (int) ($doctor['slot_length_min'] ?? 15);
            foreach ($dayNames as $dayNumber => $dayName):
              $first = $week[$dayNumber][0] ?? null;
              $second = $week[$dayNumber][1] ?? null;
              $field = 'days[' . $dayNumber . ']';

              $start1 = $first ? substr($first['start_time'], 0, 5) : '';
              $end1 = $first ? substr($first['end_time'], 0, 5) : '';
              $max1 = 0;
              if ($start1 !== '' && $end1 !== '' && $start1 < $end1) {
                  $dur1 = ((int) substr($end1, 0, 2) * 60 + (int) substr($end1, 3, 2)) -
                          ((int) substr($start1, 0, 2) * 60 + (int) substr($start1, 3, 2));
                  $max1 = intdiv($dur1, $slotLength);
              }
              $cap1 = $first ? ($max1 > 0 ? min((int) $first['capacity'], $max1) : (int) $first['capacity']) : ($max1 > 0 ? $max1 : '');

              $start2 = $second ? substr($second['start_time'], 0, 5) : '';
              $end2 = $second ? substr($second['end_time'], 0, 5) : '';
              $max2 = 0;
              if ($start2 !== '' && $end2 !== '' && $start2 < $end2) {
                  $dur2 = ((int) substr($end2, 0, 2) * 60 + (int) substr($end2, 3, 2)) -
                          ((int) substr($start2, 0, 2) * 60 + (int) substr($start2, 3, 2));
                  $max2 = intdiv($dur2, $slotLength);
              }
              $cap2 = $second ? ($max2 > 0 ? min((int) $second['capacity'], $max2) : (int) $second['capacity']) : ($max2 > 0 ? $max2 : '');
            ?>
              <tr data-day="<?= $dayNumber ?>">
                <td class="text-semibold"><?= e($dayName) ?></td>
                <td>
                  <input class="consultation-toggle" type="checkbox" name="<?= $field ?>[active]" value="1"<?= $first ? ' checked' : '' ?> aria-label="<?= e($dayName) ?> active">
                </td>
                <td>
                  <input type="time" name="<?= $field ?>[start_1]" value="<?= e($start1) ?>" aria-label="<?= e($dayName) ?> start" class="sched-time-input">
                  <span class="consultation-sched-table__sep">to</span>
                  <input type="time" name="<?= $field ?>[end_1]" value="<?= e($end1) ?>" aria-label="<?= e($dayName) ?> end" class="sched-time-input">
                </td>
                <td>
                  <div style="display:flex;flex-direction:column;align-items:flex-start;">
                    <input type="number" name="<?= $field ?>[capacity_1]" min="1" max="<?= $max1 > 0 ? $max1 : 200 ?>" value="<?= $cap1 ?>" placeholder="<?= $max1 > 0 ? $max1 : '' ?>" aria-label="<?= e($dayName) ?> capacity" class="sched-cap-input" data-day="<?= $dayNumber ?>" data-session="1">
                    <span class="sched-cap-hint" id="cap-hint-<?= $dayNumber ?>-1" style="font-size:11px;color:var(--text-muted);display:block;margin-top:2px;">
                      <?= $max1 > 0 ? "max {$max1}" : '' ?>
                    </span>
                  </div>
                </td>
                <td>
                  <input type="time" name="<?= $field ?>[start_2]" value="<?= e($start2) ?>" aria-label="<?= e($dayName) ?> second session start" class="sched-time-input">
                  <span class="consultation-sched-table__sep">to</span>
                  <input type="time" name="<?= $field ?>[end_2]" value="<?= e($end2) ?>" aria-label="<?= e($dayName) ?> second session end" class="sched-time-input">
                </td>
                <td>
                  <div style="display:flex;flex-direction:column;align-items:flex-start;">
                    <input type="number" name="<?= $field ?>[capacity_2]" min="1" max="<?= $max2 > 0 ? $max2 : 200 ?>" value="<?= $cap2 ?>" placeholder="<?= $max2 > 0 ? $max2 : '' ?>" aria-label="<?= e($dayName) ?> second session capacity" class="sched-cap-input" data-day="<?= $dayNumber ?>" data-session="2">
                    <span class="sched-cap-hint" id="cap-hint-<?= $dayNumber ?>-2" style="font-size:11px;color:var(--text-muted);display:block;margin-top:2px;">
                      <?= $max2 > 0 ? "max {$max2}" : '' ?>
                    </span>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <button class="btn btn--secondary btn--block mt-6" type="submit">Submit schedule update</button>
      <?php endif; ?>
    </div>
  </form>

  <form class="consultation-config-card" method="post" action="/staff/doctor/configure-roster">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="consultation-config-card__head">
      <div class="consultation-config-card__title">Automation</div>
    </div>
    <div class="consultation-config-card__body">
      <div class="consultation-config-row">
        <div class="consultation-config-row__label">Auto-fill schedule from roster API</div>
        <?php if ((int) $doctor['roster_api_enabled'] === 1): ?>
          <button class="consultation-toggle is-on" type="submit" name="enabled" value="0" aria-pressed="true" aria-label="Turn off"></button>
        <?php else: ?>
          <button class="consultation-toggle" type="submit" name="enabled" value="1" aria-pressed="false" aria-label="Turn on"></button>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <div class="consultation-config-card">
    <div class="consultation-config-card__head">
      <div class="consultation-config-card__title">Data &amp; cache</div>
    </div>
    <div class="consultation-config-card__body">
      <div class="consultation-config-row">
        <div>
          <div class="consultation-config-row__label">Saved medicine cache</div>
        </div>
        <button class="btn btn--ghost btn--sm" type="button">Clear cache</button>
      </div>
    </div>
  </div>
</div>

<form id="regular-schedule-switch" method="post" action="/staff/doctor/configure-regular-schedule">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
</form>

<form id="regular-schedule-off" method="post" action="/staff/doctor/configure-regular-schedule" data-confirm="Are you sure you want to turn off your weekly schedule? All upcoming booked appointments will be cancelled." data-confirm-title="Turn Off Weekly Schedule" data-confirm-ok="Turn off">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
  <input type="hidden" name="enabled" value="0">
</form>

<script>
(function() {
  const slotLengthSelect = document.getElementById('slot-length');
  const defaultCapHint = document.getElementById('default-cap-hint');
  let slotLength = slotLengthSelect ? parseInt(slotLengthSelect.value, 10) : <?= (int) ($doctor['slot_length_min'] ?? 15) ?>;

  function toMinutes(t) {
    if (!t || !t.includes(':')) return null;
    const parts = t.split(':').map(Number);
    return parts[0] * 60 + parts[1];
  }

  function updateDefaultCapHint() {
    if (defaultCapHint) {
      const max8h = Math.floor(480 / slotLength);
      defaultCapHint.textContent = `e.g. for an 8h day at ${slotLength} min/slot: max ${max8h} patients`;
    }
  }

  if (slotLengthSelect) {
    slotLengthSelect.addEventListener('change', function() {
      slotLength = parseInt(this.value, 10);
      updateDefaultCapHint();
      recalcAllRows();
    });
  }

  function updateRowSession(row, sessionNum) {
    const sInput = row.querySelector(`input[name*="[start_${sessionNum}]"]`);
    const eInput = row.querySelector(`input[name*="[end_${sessionNum}]"]`);
    const cInput = row.querySelector(`input[name*="[capacity_${sessionNum}]"]`);
    const day = row.dataset.day;
    const hint = document.getElementById(`cap-hint-${day}-${sessionNum}`);
    if (!sInput || !eInput || !cInput) return;

    const s = toMinutes(sInput.value.trim());
    const e = toMinutes(eInput.value.trim());

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
          hint.textContent = `max ${maxSlots}`;
          hint.style.color = 'var(--text-muted)';
        }
      } else {
        cInput.max = 0;
        cInput.value = '';
        if (hint) {
          hint.textContent = `< ${slotLength}m`;
          hint.style.color = 'var(--danger, #dc2626)';
        }
      }
    } else {
      if (hint) {
        hint.textContent = '';
      }
    }
  }

  function checkRowOverlap(row) {
    const s1Input = row.querySelector('input[name*="[start_1]"]');
    const e1Input = row.querySelector('input[name*="[end_1]"]');
    const s2Input = row.querySelector('input[name*="[start_2]"]');
    const e2Input = row.querySelector('input[name*="[end_2]"]');
    if (!s1Input || !e1Input || !s2Input || !e2Input) return false;

    const s1 = toMinutes(s1Input.value.trim());
    const e1 = toMinutes(e1Input.value.trim());
    const s2 = toMinutes(s2Input.value.trim());
    const e2 = toMinutes(e2Input.value.trim());

    if (s1 !== null && e1 !== null && s2 !== null && e2 !== null) {
      if (s1 < e2 && s2 < e1) {
        row.style.background = 'rgba(239, 68, 68, 0.08)';
        return true;
      }
    }
    row.style.background = '';
    return false;
  }

  function recalcAllRows() {
    document.querySelectorAll('.consultation-sched-table tbody tr').forEach(row => {
      updateRowSession(row, 1);
      updateRowSession(row, 2);
      checkRowOverlap(row);
    });
  }

  document.querySelectorAll('.consultation-sched-table tbody tr').forEach(row => {
    [1, 2].forEach(sessionNum => {
      const sInput = row.querySelector(`input[name*="[start_${sessionNum}]"]`);
      const eInput = row.querySelector(`input[name*="[end_${sessionNum}]"]`);
      const cInput = row.querySelector(`input[name*="[capacity_${sessionNum}]"]`);

      function onTimeChange() {
        updateRowSession(row, sessionNum);
        checkRowOverlap(row);
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
  });

  const schedForm = document.querySelector('form[action="/staff/doctor/configure-schedule"]');
  const timeFields = ['start_1', 'end_1', 'start_2', 'end_2'];

  function rowState(row) {
    const active = row.querySelector('input[name*="[active]"]');
    return {
      active: !!(active && active.checked),
      times: timeFields.map(name => (row.querySelector(`input[name*="[${name}]"]`)?.value || '').trim()).join('|'),
    };
  }

  const savedRows = new Map();
  document.querySelectorAll('.consultation-sched-table tbody tr').forEach(row => savedRows.set(row, rowState(row)));

  function mayCancelBookings() {
    for (const [row, saved] of savedRows) {
      if (!saved.active) continue;
      const now = rowState(row);
      if (!now.active || now.times !== saved.times) return true;
    }
    return false;
  }

  if (schedForm) {
    schedForm.addEventListener('submit', function(e) {
      let hasError = false;
      document.querySelectorAll('.consultation-sched-table tbody tr').forEach(row => {
        const active = row.querySelector('input[name*="[active]"]');
        if (!active || !active.checked) return;

        const dayName = row.querySelector('td')?.textContent?.trim() || 'Day';

        if (checkRowOverlap(row)) {
          window.showAlertDialog({ title: 'Schedule Conflict', message: `${dayName}: The two sessions overlap.` });
          hasError = true;
          return;
        }

        [1, 2].forEach(sessionNum => {
          const sInput = row.querySelector(`input[name*="[start_${sessionNum}]"]`);
          const eInput = row.querySelector(`input[name*="[end_${sessionNum}]"]`);
          const cInput = row.querySelector(`input[name*="[capacity_${sessionNum}]"]`);
          if (sInput && eInput && cInput && sInput.value.trim() && eInput.value.trim()) {
            const s = toMinutes(sInput.value.trim());
            const endMin = toMinutes(eInput.value.trim());
            if (s !== null && endMin !== null) {
              if (s >= endMin) {
                window.showAlertDialog({ title: 'Invalid Time', message: `${dayName} session ${sessionNum}: Start time must be before end time.` });
                hasError = true;
                return;
              }
              const maxSlots = Math.floor((endMin - s) / slotLength);
              if (maxSlots <= 0) {
                window.showAlertDialog({ title: 'Invalid Duration', message: `${dayName} session ${sessionNum}: Duration must be at least ${slotLength} minutes.` });
                hasError = true;
                return;
              }
              const cap = parseInt(cInput.value, 10);
              if (!cap || cap < 1 || cap > maxSlots) {
                window.showAlertDialog({ title: 'Invalid Capacity', message: `${dayName} session ${sessionNum}: Capacity cannot exceed ${maxSlots} patients.` });
                hasError = true;
                return;
              }
            }
          }
        });
      });

      if (hasError) {
        e.preventDefault();
        schedForm.removeAttribute('data-confirm');
        return;
      }

      if (mayCancelBookings()) {
        schedForm.setAttribute('data-confirm', 'Turning a day off cancels its upcoming booked appointments, and changing hours cancels bookings outside the new hours.');
        schedForm.setAttribute('data-confirm-title', 'Save Weekly Schedule');
        schedForm.setAttribute('data-confirm-ok', 'Save schedule');
      } else {
        schedForm.removeAttribute('data-confirm');
      }
    });
  }
})();
</script>

<?php require __DIR__ . '/footer.php'; ?>
