<?php

declare(strict_types=1);

$title  = 'My queue';
$active = 'queue';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">My queue - Friday 11 Jul 2026</h1>
    <div class="staff-head__sub" style="display:flex;align-items:center;gap:var(--sp-4);flex-wrap:wrap">
      <span class="badge badge--success">Session 09:00–13:00</span>
      <span>5 patients waiting</span>
    </div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--primary btn--sm" type="button">Call next</button>
  </div>
</div>

<div class="staff-grid">
  <div>
    <div class="consultation-current-card mb-7">
      <div class="consultation-current-card__head mb-0">
        <div class="consultation-current-card__patient">
          <span class="consultation-current-card__avatar">KI</span>
          <div>
            <div class="consultation-current-card__name">K.A. Inuka Asith</div>
            <div class="consultation-current-card__code">Started 09:28 · Elapsed 07:12</div>
          </div>
        </div>
        <div style="display:flex;gap:var(--sp-4);align-items:center;flex-wrap:wrap">
          <a class="btn btn--ghost btn--sm" style="background:var(--primary-tint)" href="/staff/doctor/current-patient">Open workspace</a>
          <button class="btn btn--success btn--sm" type="button">Complete</button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="consultation-queue-subhead">
          <span class="consultation-queue-subhead__title">Up next · 5 waiting</span>
        </div>
        <div class="consultation-queue-list">
          <div class="consultation-queue-row">
            <span class="consultation-queue-row__handle" title="Drag to reorder">⋮⋮</span>
            <span class="consultation-queue-row__num">1</span>
            <div class="consultation-queue-row__identity">
              <strong class="consultation-queue-row__name">K. Ashan Charuka</strong>
              <span class="consultation-queue-row__meta">PT-1242 · slot ~10:00</span>
            </div>
            <span class="consultation-qpill consultation-qpill--next"><span class="consultation-qpill__dot"></span>Next</span>
            <span class="consultation-queue-row__eta">~10:00</span>
            <div class="consultation-queue-row__actions">
              <button class="consultation-qact consultation-qact--call" type="button">Call</button>
              <button class="consultation-qact consultation-qact--skip" type="button">Skip</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move up" aria-label="Move up">↑</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move down" aria-label="Move down">↓</button>
              <button class="consultation-qact consultation-qact--flag" type="button" title="Flag triage priority" aria-label="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="consultation-queue-row">
            <span class="consultation-queue-row__handle" title="Drag to reorder">⋮⋮</span>
            <span class="consultation-queue-row__num">2</span>
            <div class="consultation-queue-row__identity">
              <strong class="consultation-queue-row__name">Sandanu Dulmeth</strong>
              <span class="consultation-queue-row__meta">PT-1315 · slot ~10:15</span>
            </div>
            <span class="consultation-qpill consultation-qpill--not-arrived"><span class="consultation-qpill__dot"></span>Not arrived yet</span>
            <span class="consultation-queue-row__eta">~10:15</span>
            <div class="consultation-queue-row__actions">
              <button class="consultation-qact consultation-qact--call" type="button">Call</button>
              <button class="consultation-qact consultation-qact--skip" type="button">Skip</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move up" aria-label="Move up">↑</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move down" aria-label="Move down">↓</button>
              <button class="consultation-qact consultation-qact--flag" type="button" title="Flag triage priority" aria-label="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="consultation-queue-row">
            <span class="consultation-queue-row__handle" title="Drag to reorder">⋮⋮</span>
            <span class="consultation-queue-row__num">3</span>
            <div class="consultation-queue-row__identity">
              <strong class="consultation-queue-row__name">G. G. Mithun Majika</strong>
              <span class="consultation-queue-row__meta">PT-0967 · slot ~10:30</span>
            </div>
            <span class="consultation-qpill consultation-qpill--checked-in"><span class="consultation-qpill__dot"></span>Checked in</span>
            <span class="consultation-queue-row__eta">~10:30</span>
            <div class="consultation-queue-row__actions">
              <button class="consultation-qact consultation-qact--call" type="button">Call</button>
              <button class="consultation-qact consultation-qact--skip" type="button">Skip</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move up" aria-label="Move up">↑</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move down" aria-label="Move down">↓</button>
              <button class="consultation-qact consultation-qact--flag" type="button" title="Flag triage priority" aria-label="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="consultation-queue-row">
            <span class="consultation-queue-row__handle" title="Drag to reorder">⋮⋮</span>
            <span class="consultation-queue-row__num">4</span>
            <div class="consultation-queue-row__identity">
              <strong class="consultation-queue-row__name">Nimsith Wickrama</strong>
              <span class="consultation-queue-row__meta">PT-0844 · slot ~10:45</span>
            </div>
            <span class="consultation-qpill consultation-qpill--checked-in"><span class="consultation-qpill__dot"></span>Checked in</span>
            <span class="consultation-queue-row__eta">~10:45</span>
            <div class="consultation-queue-row__actions">
              <button class="consultation-qact consultation-qact--call" type="button">Call</button>
              <button class="consultation-qact consultation-qact--skip" type="button">Skip</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move up" aria-label="Move up">↑</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move down" aria-label="Move down">↓</button>
              <button class="consultation-qact consultation-qact--flag" type="button" title="Flag triage priority" aria-label="Flag triage priority">⚑</button>
            </div>
          </div>
          <div class="consultation-queue-row">
            <span class="consultation-queue-row__handle" title="Drag to reorder">⋮⋮</span>
            <span class="consultation-queue-row__num">5</span>
            <div class="consultation-queue-row__identity">
              <strong class="consultation-queue-row__name">M. L. Omindu Gunathilaka</strong>
              <span class="consultation-queue-row__meta">PT-1401 · slot ~11:00</span>
            </div>
            <span class="consultation-qpill consultation-qpill--not-arrived"><span class="consultation-qpill__dot"></span>Not arrived yet</span>
            <span class="consultation-queue-row__eta">~11:00</span>
            <div class="consultation-queue-row__actions">
              <button class="consultation-qact consultation-qact--call" type="button">Call</button>
              <button class="consultation-qact consultation-qact--skip" type="button">Skip</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move up" aria-label="Move up">↑</button>
              <button class="consultation-qact consultation-qact--move" type="button" title="Move down" aria-label="Move down">↓</button>
              <button class="consultation-qact consultation-qact--flag" type="button" title="Flag triage priority" aria-label="Flag triage priority">⚑</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Today at a glance</div>
        <div class="consultation-glance">
          <div class="consultation-glance__row"><span>Seen</span><strong>5 of 24</strong></div>
          <div class="consultation-glance__row"><span>No-shows</span><strong class="consultation-glance__danger">1</strong></div>
          <div class="consultation-glance__row"><span>Walk-ins added</span><strong class="consultation-glance__info">2</strong></div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row"><span>Message board</span></div>
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
          <input type="text" placeholder="Post a message…" aria-label="Post to message board">
          <button class="btn btn--primary btn--sm" type="button">Post</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>