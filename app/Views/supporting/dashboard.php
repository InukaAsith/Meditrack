<?php

declare(strict_types=1);

$title = 'Dashboard';
$active = 'dashboard';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e(first_name($staff['name'])) ?></h1>
    <div class="staff-head__sub"><?= e(date('l, F j, Y')) ?> · <span data-live-clock><?= e(date('h:i A')) ?></span> · pick a doctor to focus their queue</div>
  </div>
</div>

<?php
$selActive = 'AS';
$selDoctors = [
  ['id' => 'AS', 'name' => 'Dr. Sample Doctor 1', 'specialty' => 'General', 'tone' => 'blue'],
  ['id' => 'RF', 'name' => 'Dr. Sample Doctor 3', 'specialty' => 'Pediatrics', 'tone' => 'teal'],
  ['id' => 'MP', 'name' => 'Dr. Sample Doctor 2', 'specialty' => 'ENT', 'tone' => 'amber'],
];
?>
<div class="doc-selector" data-doc-selector>
  <span class="doc-selector__label">Serving:</span>
  <div class="doc-selector__pills" data-doc-pills>
    <?php foreach ($selDoctors as $doc): ?>
      <span data-doc-pill-wrap>
        <button type="button"
          class="doc-pill<?= $doc['id'] === $selActive ? ' is-active' : '' ?>"
          data-doc-pill="<?= e($doc['id']) ?>"
          data-doc-name="<?= e($doc['name']) ?>"
          data-doc-specialty="<?= e($doc['specialty']) ?>">
          <span class="doc-pill__avatar avatar--<?= e($doc['tone']) ?>"><?= e($doc['id']) ?></span>
          <span class="doc-pill__name"><?= e($doc['name']) ?></span>
        </button>
      </span>
    <?php endforeach; ?>
    <span data-doc-pill-wrap>
      <button type="button"
        class="doc-pill doc-pill--all<?= $selActive === 'all' ? ' is-active' : '' ?>"
        data-doc-pill="all" data-doc-name="All doctors" data-doc-specialty="All doctors">
        <span class="doc-pill__grid">⊞</span>
        <span class="doc-pill__name">All doctors</span>
      </button>
    </span>
  </div>
  <div class="doc-selector__search search-box">
    <?= icon('search', 15, 'search-box__icon') ?>
    <input class="search-box__input" type="search" data-doc-search
      placeholder="Search doctor…" aria-label="Search doctors">
  </div>
</div>

<section class="supporting-dash-panel" data-dash-panel="AS">

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Queue status · Dr. Sample Doctor 1</span>
    <span class="label-note">General · updates live</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Patients waiting</div>
      <div class="staff-kpi__value">6</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Checked in</div>
      <div class="staff-kpi__value">4</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Ready</div>
      <div class="staff-kpi__value staff-kpi__value--success">2</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">In consultation</div>
      <div class="staff-kpi__value">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Completed</div>
      <div class="staff-kpi__value staff-kpi__value--success">9</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Skipped</div>
      <div class="staff-kpi__value staff-kpi__value--warning">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Emergency</div>
      <div class="staff-kpi__value staff-kpi__value--warning">0</div>
    </div>
  </div>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Operational performance</span>
    <span class="label-note">from the live queue &amp; ETA engine</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Average wait</div>
      <div class="staff-kpi__value">14 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Queue length</div>
      <div class="staff-kpi__value">6</div>
      <div class="staff-kpi__delta">waiting now</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Avg consultation</div>
      <div class="staff-kpi__value">12 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Show-up rate</div>
      <div class="staff-kpi__value staff-kpi__value--success">88%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">No-show rate</div>
      <div class="staff-kpi__value staff-kpi__value--warning">12%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Capacity used</div>
      <div class="staff-kpi__value">24<span style="font-size:var(--fs-md);color:var(--text-muted)">/28</span></div>
      <div class="staff-kpi__delta">4 slots remaining</div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Today's session</div>
      <div class="supporting-session-card">
        <div class="supporting-session-card__id">
          <span class="supporting-session-card__avatar avatar--blue">AS</span>
          <div>
            <div class="supporting-session-card__name">Dr. Sample Doctor 1</div>
            <div class="supporting-session-card__meta">General · Room 3 · 09:00 – 13:00</div>
          </div>
        </div>
        <div class="supporting-session-card__status">
          <span class="supporting-session-card__status-label">Current status</span>
          <span class="badge badge--warning">Running late · +5 min</span>
        </div>
        <div class="supporting-session-card__cap">
          <span class="supporting-session-card__status-label">Capacity remaining</span>
          <strong>4 slots</strong>
        </div>
        <div class="supporting-session-card__progress">
          <div class="supporting-session-card__progress-head">
            <span class="supporting-session-card__status-label">Session progress</span>
            <span>64%</span>
          </div>
          <div class="supporting-progress"><span style="width:64%"></span></div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="supporting-dash-panel" data-dash-panel="RF" hidden>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Queue status · Dr. Sample Doctor 3</span>
    <span class="label-note">Pediatrics · updates live</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Patients waiting</div>
      <div class="staff-kpi__value">3</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Checked in</div>
      <div class="staff-kpi__value">2</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Ready</div>
      <div class="staff-kpi__value staff-kpi__value--success">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">In consultation</div>
      <div class="staff-kpi__value">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Completed</div>
      <div class="staff-kpi__value staff-kpi__value--success">6</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Skipped</div>
      <div class="staff-kpi__value staff-kpi__value--warning">0</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Emergency</div>
      <div class="staff-kpi__value staff-kpi__value--warning">1</div>
    </div>
  </div>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Operational performance</span>
    <span class="label-note">from the live queue &amp; ETA engine</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Average wait</div>
      <div class="staff-kpi__value">9 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Queue length</div>
      <div class="staff-kpi__value">3</div>
      <div class="staff-kpi__delta">waiting now</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Avg consultation</div>
      <div class="staff-kpi__value">15 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Show-up rate</div>
      <div class="staff-kpi__value staff-kpi__value--success">92%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">No-show rate</div>
      <div class="staff-kpi__value staff-kpi__value--warning">8%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Capacity used</div>
      <div class="staff-kpi__value">12<span style="font-size:var(--fs-md);color:var(--text-muted)">/16</span></div>
      <div class="staff-kpi__delta">4 slots remaining</div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Today's session</div>
      <div class="supporting-session-card">
        <div class="supporting-session-card__id">
          <span class="supporting-session-card__avatar avatar--teal">RF</span>
          <div>
            <div class="supporting-session-card__name">Dr. Sample Doctor 3</div>
            <div class="supporting-session-card__meta">Pediatrics · Room 5 · 09:00 – 12:00</div>
          </div>
        </div>
        <div class="supporting-session-card__status">
          <span class="supporting-session-card__status-label">Current status</span>
          <span class="badge badge--success">Arrived · on time</span>
        </div>
        <div class="supporting-session-card__cap">
          <span class="supporting-session-card__status-label">Capacity remaining</span>
          <strong>4 slots</strong>
        </div>
        <div class="supporting-session-card__progress">
          <div class="supporting-session-card__progress-head">
            <span class="supporting-session-card__status-label">Session progress</span>
            <span>55%</span>
          </div>
          <div class="supporting-progress"><span style="width:55%"></span></div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="supporting-dash-panel" data-dash-panel="MP" hidden>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Queue status · Dr. Sample Doctor 2</span>
    <span class="label-note">ENT · updates live</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Patients waiting</div>
      <div class="staff-kpi__value">5</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Checked in</div>
      <div class="staff-kpi__value">3</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Ready</div>
      <div class="staff-kpi__value staff-kpi__value--success">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">In consultation</div>
      <div class="staff-kpi__value">1</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Completed</div>
      <div class="staff-kpi__value staff-kpi__value--success">4</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Skipped</div>
      <div class="staff-kpi__value staff-kpi__value--warning">2</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Emergency</div>
      <div class="staff-kpi__value staff-kpi__value--warning">0</div>
    </div>
  </div>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Operational performance</span>
    <span class="label-note">from the live queue &amp; ETA engine</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Average wait</div>
      <div class="staff-kpi__value">22 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Queue length</div>
      <div class="staff-kpi__value">5</div>
      <div class="staff-kpi__delta">waiting now</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Avg consultation</div>
      <div class="staff-kpi__value">18 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Show-up rate</div>
      <div class="staff-kpi__value staff-kpi__value--success">80%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">No-show rate</div>
      <div class="staff-kpi__value staff-kpi__value--warning">20%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Capacity used</div>
      <div class="staff-kpi__value">10<span style="font-size:var(--fs-md);color:var(--text-muted)">/16</span></div>
      <div class="staff-kpi__delta">6 slots remaining</div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Today's session</div>
      <div class="supporting-session-card">
        <div class="supporting-session-card__id">
          <span class="supporting-session-card__avatar avatar--amber">MP</span>
          <div>
            <div class="supporting-session-card__name">Dr. Sample Doctor 2</div>
            <div class="supporting-session-card__meta">ENT · Room 2 · 09:00 – 13:00</div>
          </div>
        </div>
        <div class="supporting-session-card__status">
          <span class="supporting-session-card__status-label">Current status</span>
          <span class="badge badge--danger">Running late · +12 min</span>
        </div>
        <div class="supporting-session-card__cap">
          <span class="supporting-session-card__status-label">Capacity remaining</span>
          <strong>6 slots</strong>
        </div>
        <div class="supporting-session-card__progress">
          <div class="supporting-session-card__progress-head">
            <span class="supporting-session-card__status-label">Session progress</span>
            <span>40%</span>
          </div>
          <div class="supporting-progress"><span style="width:40%"></span></div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="supporting-dash-panel" data-dash-panel="all" hidden>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Queue status · All doctors</span>
    <span class="label-note">3 sessions today · updates live</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Patients waiting</div>
      <div class="staff-kpi__value">14</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Checked in</div>
      <div class="staff-kpi__value">9</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Ready</div>
      <div class="staff-kpi__value staff-kpi__value--success">4</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">In consultation</div>
      <div class="staff-kpi__value">3</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Completed</div>
      <div class="staff-kpi__value staff-kpi__value--success">19</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Skipped</div>
      <div class="staff-kpi__value staff-kpi__value--warning">3</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Emergency</div>
      <div class="staff-kpi__value staff-kpi__value--warning">1</div>
    </div>
  </div>

  <div class="staff-eyebrow staff-eyebrow--row">
    <span>Operational performance</span>
    <span class="label-note">from the live queue &amp; ETA engine</span>
  </div>
  <div class="staff-kpis supporting-dash-kpis">
    <div class="staff-kpi">
      <div class="staff-kpi__label">Average wait</div>
      <div class="staff-kpi__value">15 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Queue length</div>
      <div class="staff-kpi__value">14</div>
      <div class="staff-kpi__delta">waiting now</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Avg consultation</div>
      <div class="staff-kpi__value">15 min</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Show-up rate</div>
      <div class="staff-kpi__value staff-kpi__value--success">87%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">No-show rate</div>
      <div class="staff-kpi__value staff-kpi__value--warning">13%</div>
    </div>
    <div class="staff-kpi">
      <div class="staff-kpi__label">Capacity used</div>
      <div class="staff-kpi__value">46<span style="font-size:var(--fs-md);color:var(--text-muted)">/60</span></div>
      <div class="staff-kpi__delta">14 slots remaining</div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Today's session</div>
      <div class="supporting-session-card">
        <div class="supporting-session-card__id">
          <span class="supporting-session-card__avatar avatar--blue">⊞</span>
          <div>
            <div class="supporting-session-card__name">All doctors</div>
            <div class="supporting-session-card__meta">3 sessions today · Rooms 2 · 3 · 5 · 09:00 – 13:00</div>
          </div>
        </div>
        <div class="supporting-session-card__status">
          <span class="supporting-session-card__status-label">Current status</span>
          <span class="badge badge--success">Clinic open · 3 doctors on floor</span>
        </div>
        <div class="supporting-session-card__cap">
          <span class="supporting-session-card__status-label">Capacity remaining</span>
          <strong>14 slots</strong>
        </div>
        <div class="supporting-session-card__progress">
          <div class="supporting-session-card__progress-head">
            <span class="supporting-session-card__status-label">Session progress</span>
            <span>77%</span>
          </div>
          <div class="supporting-progress"><span style="width:77%"></span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="card">
  <div class="card__body">
    <div class="staff-eyebrow staff-eyebrow--row">
      <span>Recent queue activity</span>
      <span class="label-note">all doctors · last 30 min</span>
    </div>
    <ol class="supporting-timeline">
      <li class="supporting-timeline__item supporting-timeline__item--started">
        <span class="supporting-timeline__dot">▶</span>
        <span class="supporting-timeline__text">Dr. Sample Doctor 1 started consultation - Nimsith Wickrama</span>
        <span class="supporting-timeline__time">09:28</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--ready">
        <span class="supporting-timeline__dot">✓</span>
        <span class="supporting-timeline__text">M. L. Omindu Gunathilaka marked Ready - vitals recorded</span>
        <span class="supporting-timeline__time">09:26</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--vitals">
        <span class="supporting-timeline__dot">❤</span>
        <span class="supporting-timeline__text">Vitals recorded for K.A. Inuka Asith (118/76)</span>
        <span class="supporting-timeline__time">09:18</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--emergency">
        <span class="supporting-timeline__dot">＋</span>
        <span class="supporting-timeline__text">Emergency added - K. Ashan Charuka → Dr. Sample Doctor 3</span>
        <span class="supporting-timeline__time">09:33</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--reinserted">
        <span class="supporting-timeline__dot">↺</span>
        <span class="supporting-timeline__text">G. G. Mithun Majika reinserted after #3</span>
        <span class="supporting-timeline__time">09:12</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--checkin">
        <span class="supporting-timeline__dot">⤵</span>
        <span class="supporting-timeline__text">K. Ashan Charuka checked in at counter</span>
        <span class="supporting-timeline__time">09:10</span>
      </li>
      <li class="supporting-timeline__item supporting-timeline__item--skipped">
        <span class="supporting-timeline__dot">⤼</span>
        <span class="supporting-timeline__text">M. L. Omindu Gunathilaka skipped - stepped out to pharmacy</span>
        <span class="supporting-timeline__time">09:24</span>
      </li>
    </ol>
  </div>
</div>

<script src="/assets/js/supporting/doctor-selector.js" defer></script>
<script src="/assets/js/supporting/dashboard.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>