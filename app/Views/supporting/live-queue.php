<?php

declare(strict_types=1);

$title = 'Live queue';
$active = 'live-queue';

require __DIR__ . '/header.php';
?>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Live queue</h1>
    <div class="staff-head__sub">Call patients, record vitals and keep each doctor's queue moving.</div>
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

<div data-lq-mode="single">
  <div class="supporting-workspace-grid">
    <div class="supporting-queue-col">
      <section class="supporting-queue-panel" data-queue-panel="AS">
        <div class="supporting-serving-card">
          <div class="supporting-serving-card__avatar"><span>NJ</span></div>
          <div class="supporting-serving-card__info">
            <div class="supporting-serving-card__name-row">
              <strong class="supporting-serving-card__name">Nimsith Wickrama</strong>
              <span class="supporting-serving-card__doctor">· with Dr. Sample Doctor 1 now</span>
            </div>
            <div class="supporting-serving-card__timer">
              Started 09:28 · <span class="supporting-serving-card__clock-icon">⏱</span> <strong>07:12</strong>
            </div>
          </div>
          <button type="button" class="btn btn--success supporting-serving-card__btn" data-call-next>✓ Done - call next</button>
        </div>

        <div class="supporting-queue-subhead">
          <span class="supporting-queue-subhead__title">UP NEXT · DR. SILVA · ACD 12 min</span>
          <span class="supporting-queue-subhead__hint">row actions are always available · drag ⋮⋮ to reorder</span>
        </div>

        <div class="supporting-queue-list" data-queue-list>
          <div class="supporting-queue-row"
            data-patient-id="PT-1088" data-patient-name="K.A. Inuka Asith" data-inqueue="1"
            data-height="157" data-weight="58" data-bp="118 / 76" data-temp="36.6" data-pulse="71" data-spo2="99">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">1</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">K.A. Inuka Asith</strong>
              <span class="supporting-queue-row__meta">PT-1088 · slot 09:15</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--next">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Next</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~09:40</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1121" data-patient-name="M. L. Omindu Gunathilaka" data-inqueue="1"
            data-height="168" data-weight="71.2" data-bp="128 / 84" data-temp="36.8" data-pulse="76" data-spo2="98">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">2</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">M. L. Omindu Gunathilaka</strong>
              <span class="supporting-queue-row__meta">PT-1121 · slot 09:30</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--ready">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Ready</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~09:50</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-0967" data-patient-name="G. G. Mithun Majika" data-inqueue="1"
            data-height="160" data-weight="62" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">3</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">G. G. Mithun Majika</strong>
              <span class="supporting-queue-row__meta">PT-0967 · slot 09:45</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--not-arrived">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Not arrived yet</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:05</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1204" data-patient-name="K. Ashan Charuka" data-inqueue="1"
            data-height="172" data-weight="74.5" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">4</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">K. Ashan Charuka</strong>
              <span class="supporting-queue-row__meta">PT-1204 · slot 09:50</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Checked in</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:15</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1315" data-patient-name="Sandanu Dulmeth" data-inqueue="1"
            data-height="175" data-weight="80" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">5</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Sandanu Dulmeth</strong>
              <span class="supporting-queue-row__meta">PT-1315 · slot 10:00</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--not-arrived">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Not arrived yet</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:30</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
        </div>

        <div data-skip-board>
          <div class="skip-board supporting-skip-board">
            <div class="skip-board__head">
              <span class="staff-eyebrow m-0">Skipped patients</span>
              <span class="skip-board__hint">reinsert defaults after #3 in the queue</span>
            </div>
            <div class="skip-board__list">
              <div data-skip-chip="PT-1088" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>K.A. Inuka Asith</strong>
                    <span>PT-1088 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1121" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>M. L. Omindu Gunathilaka</strong>
                    <span>PT-1121 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-0967" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>G. G. Mithun Majika</strong>
                    <span>PT-0967 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1204" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>K. Ashan Charuka</strong>
                    <span>PT-1204 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1315" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Sandanu Dulmeth</strong>
                    <span>PT-1315 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-0644">
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>M. L. Omindu Gunathilaka</strong>
                    <span>PT-0644 · Stepped out to pharmacy · 09:24</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div data-parked-rows hidden>
          <div class="supporting-queue-row" data-patient-id="PT-0644" data-patient-name="M. L. Omindu Gunathilaka">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num"></div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">M. L. Omindu Gunathilaka</strong>
              <span class="supporting-queue-row__meta">PT-0644 · reinserted</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Reinserted</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~ recalc</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag">⚑</button>
            </div>
          </div>
        </div>
      </section>
      <section class="supporting-queue-panel" data-queue-panel="RF" hidden>
        <div class="supporting-serving-card">
          <div class="supporting-serving-card__avatar"><span>SK</span></div>
          <div class="supporting-serving-card__info">
            <div class="supporting-serving-card__name-row">
              <strong class="supporting-serving-card__name">K. Ashan Charuka</strong>
              <span class="supporting-serving-card__doctor">· with Dr. Sample Doctor 3 now</span>
            </div>
            <div class="supporting-serving-card__timer">
              Started 09:33 · <span class="supporting-serving-card__clock-icon">⏱</span> <strong>02:41</strong>
            </div>
          </div>
          <button type="button" class="btn btn--success supporting-serving-card__btn" data-call-next>✓ Done - call next</button>
        </div>

        <div class="supporting-queue-subhead">
          <span class="supporting-queue-subhead__title">UP NEXT · DR. FERNANDO · ACD 15 min</span>
          <span class="supporting-queue-subhead__hint">row actions are always available · drag ⋮⋮ to reorder</span>
        </div>

        <div class="supporting-queue-list" data-queue-list>
          <div class="supporting-queue-row"
            data-patient-id="PT-0844" data-patient-name="Nimsith Wickrama" data-inqueue="1"
            data-height="152" data-weight="54.1" data-bp="121 / 79" data-temp="36.5" data-pulse="69" data-spo2="99">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">1</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Nimsith Wickrama</strong>
              <span class="supporting-queue-row__meta">PT-0844 · slot 09:40</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--next">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Next</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~09:45</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1402" data-patient-name="K.A. Inuka Asith" data-inqueue="1"
            data-height="120" data-weight="24" data-bp="104 / 68" data-temp="37.1" data-pulse="92" data-spo2="98">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">2</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">K.A. Inuka Asith</strong>
              <span class="supporting-queue-row__meta">PT-1402 · slot 10:00</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--ready">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Ready</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:05</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1455" data-patient-name="G. G. Mithun Majika" data-inqueue="1"
            data-height="110" data-weight="19.5" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">3</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">G. G. Mithun Majika</strong>
              <span class="supporting-queue-row__meta">PT-1455 · slot 10:20</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Checked in</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:30</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1470" data-patient-name="Nimsith Wickrama" data-inqueue="1"
            data-height="132" data-weight="30.2" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">4</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Nimsith Wickrama</strong>
              <span class="supporting-queue-row__meta">PT-1470 · slot 10:40</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--not-arrived">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Not arrived yet</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:55</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
        </div>

        <div data-skip-board hidden>
          <div class="skip-board supporting-skip-board">
            <div class="skip-board__head">
              <span class="staff-eyebrow m-0">Skipped patients</span>
              <span class="skip-board__hint">reinsert defaults after #3 in the queue</span>
            </div>
            <div class="skip-board__list">
              <div data-skip-chip="PT-0844" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Nimsith Wickrama</strong>
                    <span>PT-0844 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1402" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>K.A. Inuka Asith</strong>
                    <span>PT-1402 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1455" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>G. G. Mithun Majika</strong>
                    <span>PT-1455 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1470" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Nimsith Wickrama</strong>
                    <span>PT-1470 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div data-parked-rows hidden>
        </div>
      </section>
      <section class="supporting-queue-panel" data-queue-panel="MP" hidden>
        <div class="supporting-serving-card">
          <div class="supporting-serving-card__avatar"><span>PS</span></div>
          <div class="supporting-serving-card__info">
            <div class="supporting-serving-card__name-row">
              <strong class="supporting-serving-card__name">K. Ashan Charuka</strong>
              <span class="supporting-serving-card__doctor">· with Dr. Sample Doctor 2 now</span>
            </div>
            <div class="supporting-serving-card__timer">
              Started 09:26 · <span class="supporting-serving-card__clock-icon">⏱</span> <strong>09:48</strong>
            </div>
          </div>
          <button type="button" class="btn btn--success supporting-serving-card__btn" data-call-next>✓ Done - call next</button>
        </div>

        <div class="supporting-queue-subhead">
          <span class="supporting-queue-subhead__title">UP NEXT · DR. PERERA · ACD 18 min</span>
          <span class="supporting-queue-subhead__hint">row actions are always available · drag ⋮⋮ to reorder</span>
        </div>

        <div class="supporting-queue-list" data-queue-list>
          <div class="supporting-queue-row"
            data-patient-id="PT-0230" data-patient-name="Sandanu Dulmeth" data-inqueue="1"
            data-height="160" data-weight="66.3" data-bp="142 / 92" data-temp="36.7" data-pulse="88" data-spo2="97">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">1</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Sandanu Dulmeth</strong>
              <span class="supporting-queue-row__meta">PT-0230 · slot 09:20</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--next">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Next</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~09:42</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1355" data-patient-name="Sandanu Dulmeth" data-inqueue="1"
            data-height="163" data-weight="60.4" data-bp="124 / 80" data-temp="36.6" data-pulse="74" data-spo2="98">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">2</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Sandanu Dulmeth</strong>
              <span class="supporting-queue-row__meta">PT-1355 · slot 10:10</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--ready">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Ready</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:20</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1508" data-patient-name="K.A. Inuka Asith" data-inqueue="1"
            data-height="178" data-weight="82" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">3</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">K.A. Inuka Asith</strong>
              <span class="supporting-queue-row__meta">PT-1508 · slot 10:25</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Checked in</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~10:45</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1533" data-patient-name="Nimsith Wickrama" data-inqueue="1"
            data-height="166" data-weight="58.9" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">4</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">Nimsith Wickrama</strong>
              <span class="supporting-queue-row__meta">PT-1533 · slot 10:40</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--not-arrived">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Not arrived yet</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~11:05</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row"
            data-patient-id="PT-1560" data-patient-name="M. L. Omindu Gunathilaka" data-inqueue="1"
            data-height="170" data-weight="77.1" data-bp="" data-temp="" data-pulse="" data-spo2="">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num">5</div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">M. L. Omindu Gunathilaka</strong>
              <span class="supporting-queue-row__meta">PT-1560 · slot 11:00</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--not-arrived">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Not arrived yet</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~11:25</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call" title="Call patient">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals" title="Record vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip" title="Skip patient">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" title="Move up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" title="Move down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag" title="Flag triage priority">⚑</button>
            </div>
          </div>
        </div>

        <div data-skip-board>
          <div class="skip-board supporting-skip-board">
            <div class="skip-board__head">
              <span class="staff-eyebrow m-0">Skipped patients</span>
              <span class="skip-board__hint">reinsert defaults after #3 in the queue</span>
            </div>
            <div class="skip-board__list">
              <div data-skip-chip="PT-0230" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Sandanu Dulmeth</strong>
                    <span>PT-0230 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1355" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Sandanu Dulmeth</strong>
                    <span>PT-1355 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1508" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>K.A. Inuka Asith</strong>
                    <span>PT-1508 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1533" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>Nimsith Wickrama</strong>
                    <span>PT-1533 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-1560" hidden>
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>M. L. Omindu Gunathilaka</strong>
                    <span>PT-1560 · skipped just now</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-0781">
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>K. Ashan Charuka</strong>
                    <span>PT-0781 · No response at call · 09:15</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
              <div data-skip-chip="PT-0820">
                <div class="skip-chip">
                  <div class="skip-chip__who">
                    <strong>G. G. Mithun Majika</strong>
                    <span>PT-0820 · Vitals pending · 09:29</span>
                  </div>
                  <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div data-parked-rows hidden>
          <div class="supporting-queue-row" data-patient-id="PT-0781" data-patient-name="K. Ashan Charuka">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num"></div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">K. Ashan Charuka</strong>
              <span class="supporting-queue-row__meta">PT-0781 · reinserted</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Reinserted</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~ recalc</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag">⚑</button>
            </div>
          </div>
          <div class="supporting-queue-row" data-patient-id="PT-0820" data-patient-name="G. G. Mithun Majika">
            <div class="supporting-queue-row__handle" title="Drag to reorder">⋮⋮</div>
            <div class="supporting-queue-row__num"></div>
            <div class="supporting-queue-row__identity">
              <strong class="supporting-queue-row__name">G. G. Mithun Majika</strong>
              <span class="supporting-queue-row__meta">PT-0820 · reinserted</span>
            </div>
            <div class="supporting-queue-row__status">
              <span class="supporting-status-pill supporting-status-pill--checked-in">
                <span class="supporting-status-pill__dot"></span>
                <span data-pill-label>Reinserted</span>
              </span>
            </div>
            <div class="supporting-queue-row__eta">~ recalc</div>
            <div class="supporting-queue-row__actions">
              <button type="button" class="supporting-act-btn supporting-act-btn--call" data-act="call">▶ Call</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--vitals" data-act="vitals">Vitals</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--skip" data-act="skip">Skip</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="up" aria-label="Move up">↑</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--move" data-act="down" aria-label="Move down">↓</button>
              <button type="button" class="supporting-act-btn supporting-act-btn--flag" data-act="flag">⚑</button>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="supporting-vitals-col">
      <div class="supporting-vitals-card">
        <div class="supporting-vitals-card__header">
          <h2 class="supporting-vitals-card__title" id="vitals-form-title">Vitals - Omindu G.</h2>
          <span class="supporting-vitals-card__sub" id="vitals-form-sub">PT-1121 · next up</span>
        </div>
        <form id="vitals-form" class="supporting-vitals-form">
          <input type="hidden" id="v-patient-id" value="PT-1121">
          <div class="supporting-vitals-form__grid">
            <div class="supporting-form-group">
              <label for="v-bp" class="supporting-form-label">BP</label>
              <input type="text" id="v-bp" class="supporting-form-input" value="128 / 84" required>
            </div>
            <div class="supporting-form-group">
              <label for="v-temp" class="supporting-form-label">Temp °C</label>
              <input type="number" step="0.1" id="v-temp" class="supporting-form-input" value="36.8" required>
            </div>
            <div class="supporting-form-group">
              <label for="v-pulse" class="supporting-form-label">Pulse</label>
              <input type="number" id="v-pulse" class="supporting-form-input" value="76" required>
            </div>
            <div class="supporting-form-group">
              <label for="v-spo2" class="supporting-form-label">SpO₂ %</label>
              <input type="number" id="v-spo2" class="supporting-form-input" value="98" required>
            </div>
            <div class="supporting-form-group">
              <label for="v-weight" class="supporting-form-label">Weight kg</label>
              <input type="number" step="0.1" id="v-weight" class="supporting-form-input" value="71.2" required>
            </div>
            <input type="hidden" id="v-height" value="168">
            <div class="supporting-form-group">
              <label class="supporting-form-label">BMI auto</label>
              <div class="supporting-bmi-badge" id="v-bmi-display">25.2</div>
            </div>
          </div>
          <button type="submit" class="btn btn--primary supporting-vitals-submit-btn" id="save-vitals-btn">Save → mark Ready ✓</button>
          <p class="supporting-vitals-note">Doctor sees these instantly in the workspace ( <span class="supporting-vitals-note__sync">3s</span> )</p>
        </form>
      </div>
    </div>
  </div>
</div>

<div data-lq-mode="all" hidden>
  <div class="day-cols">
    <div class="day-col" title="Dr. Sample Doctor 3's day">
      <div class="day-col__head">
        <span class="day-col__avatar avatar--teal">RF</span>
        <div>
          <div class="day-col__name">Dr. Sample Doctor 3</div>
          <div class="day-col__meta">Pediatrics · 12/16 booked</div>
        </div>
      </div>
      <div class="day-col__slots">
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">Nimsith Wickrama · FU</span>
          <span class="day-slot__time">09:00</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">K.A. Inuka Asith · New</span>
          <span class="day-slot__time">09:30</span>
        </div>
        <div class="day-slot day-slot--emergency">
          <span class="day-slot__label">Cancelled</span>
          <span class="day-slot__time">10:00</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">Nimsith Wickrama · FU</span>
          <span class="day-slot__time">10:30</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">11:00</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">11:30</span>
        </div>
        <div class="day-slot day-slot--blocked">
          <span class="day-slot__label">Blocked - lunch</span>
          <span class="day-slot__time">12:00</span>
        </div>
      </div>
    </div>
    <div class="day-col" title="Dr. Sample Doctor 2's day">
      <div class="day-col__head">
        <span class="day-col__avatar avatar--amber">MP</span>
        <div>
          <div class="day-col__name">Dr. Sample Doctor 2</div>
          <div class="day-col__meta">ENT · 10/16 booked</div>
        </div>
      </div>
      <div class="day-col__slots">
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">Sandanu Dulmeth · FU</span>
          <span class="day-slot__time">09:00</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          <span class="day-slot__time">09:30</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">Sandanu Dulmeth · New</span>
          <span class="day-slot__time">10:00</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">10:30</span>
        </div>
        <div class="day-slot day-slot--blocked-doctor">
          <span class="day-slot__label">Blocked by doctor</span>
          <span class="day-slot__time">11:00</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">11:30</span>
        </div>
        <div class="day-slot day-slot--blocked">
          <span class="day-slot__label">Blocked - lunch</span>
          <span class="day-slot__time">12:00</span>
        </div>
      </div>
    </div>
    <div class="day-col" title="Dr. Sample Doctor 1's day">
      <div class="day-col__head">
        <span class="day-col__avatar avatar--blue">AS</span>
        <div>
          <div class="day-col__name">Dr. Sample Doctor 1</div>
          <div class="day-col__meta">General · 24/28 booked</div>
        </div>
      </div>
      <div class="day-col__slots">
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">K.A. Inuka Asith · FU</span>
          <span class="day-slot__time">09:00</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">M. L. Omindu Gunathilaka · New</span>
          <span class="day-slot__time">09:30</span>
        </div>
        <div class="day-slot day-slot--booked">
          <span class="day-slot__label">Sandanu D. · New</span>
          <span class="day-slot__time">10:00</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">10:30</span>
        </div>
        <div class="day-slot day-slot--walkin">
          <span class="day-slot__label">W-07 · walk-in token</span>
          <span class="day-slot__time">11:00</span>
        </div>
        <div class="day-slot day-slot--available">
          <span class="day-slot__label">Available</span>
          <span class="day-slot__time">11:30</span>
        </div>
        <div class="day-slot day-slot--blocked">
          <span class="day-slot__label">Blocked - lunch</span>
          <span class="day-slot__time">12:00</span>
        </div>
      </div>
    </div>
  </div>
  <div class="day-legend">
    <span><i class="day-legend__box day-legend__box--booked"></i>Booked</span>
    <span><i class="day-legend__box day-legend__box--available"></i>Available</span>
    <span><i class="day-legend__box day-legend__box--walkin"></i>Walk-in token</span>
    <span><i class="day-legend__box day-legend__box--blocked"></i>Blocked</span>
    <span class="day-legend__hint">Read-only overview · pick a single doctor above to manage that queue.</span>
  </div>
</div>

<aside class="card appt-msg supporting-msg">
  <div class="card__body">
    <div class="staff-eyebrow">Message board <span class="label-note">· <span data-single-doc>Dr. Sample Doctor 1</span> · all staff see this</span></div>
    <div class="message-board" id="appt-msgboard">
      <div data-doc="AS">
        <div class="message-board__post">
          <span class="message-board__avatar">AS</span>
          <div class="message-board__body">
            <strong>Dr. Sample Doctor 1:</strong>
            <p>keep 12:30–13:00 free - hospital call expected.</p>
            <span class="message-board__time">08:40</span>
          </div>
        </div>
      </div>
      <div data-doc="all">
        <div class="message-board__post">
          <span class="message-board__avatar">SD</span>
          <div class="message-board__body">
            <strong>Sandanu (reception):</strong>
            <p>PT-0967 rescheduled to 10:15 - walk-in token issued.</p>
            <span class="message-board__time">09:22</span>
          </div>
        </div>
      </div>
      <div data-doc="MP">
        <div class="message-board__post">
          <span class="message-board__avatar">MP</span>
          <div class="message-board__body">
            <strong>Dr. Sample Doctor 2:</strong>
            <p>running ~12 min behind - please advise waiting ENT patients.</p>
            <span class="message-board__time">09:26</span>
          </div>
        </div>
      </div>
      <div class="message-board__empty" id="appt-msg-empty" hidden>No posts for this doctor yet.</div>
    </div>
    <template id="appt-msg-template">
      <div data-doc="">
        <div class="message-board__post">
          <span class="message-board__avatar">ML</span>
          <div class="message-board__body">
            <strong>Omindu (support):</strong>
            <p></p>
            <span class="message-board__time"></span>
          </div>
        </div>
      </div>
    </template>
    <div class="message-board__compose">
      <input type="text" id="appt-msg-input" placeholder="Post to message board…" aria-label="Post to message board">
      <button class="btn btn--primary btn--sm" type="button" id="appt-msg-post">Post</button>
    </div>
  </div>
</aside>

<div id="reinsert-pop" hidden>
  <div class="reinsert-pop" role="dialog" aria-label="Reinsert into queue">
    <div class="reinsert-pop__title" data-reinsert-title>Reinsert patient</div>
    <label class="reinsert-pop__field">
      <span>Insert after position</span>
      <input type="number" min="1" value="3" data-reinsert-pos aria-label="Insert after position">
    </label>
    <div class="reinsert-pop__hint">Default: after #3 in the queue · ETAs recompute on confirm.</div>
    <div class="reinsert-pop__actions">
      <button class="btn btn--secondary btn--xs" type="button" data-reinsert-cancel>Cancel</button>
      <button class="btn btn--primary btn--xs" type="button" data-reinsert-confirm>Reinsert</button>
    </div>
  </div>
</div>

<script src="/assets/js/supporting/doctor-selector.js" defer></script>
<script src="/assets/js/supporting/live-queue.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>