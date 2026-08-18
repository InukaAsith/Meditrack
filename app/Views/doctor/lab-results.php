<?php

declare(strict_types=1);

$title  = 'Lab results';
$active = 'current-patient';

require __DIR__ . '/header.php';
?>
<div class="mb-5">
  <a class="link-act" href="/staff/doctor/current-patient"><?= icon('chevronLeft', 12) ?> Back to consultation</a>
</div>

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Lab results - K.A. Inuka Asith</h1>
    <div class="staff-head__sub">PT-1088</div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary btn--sm" type="button">Attach file</button>
  </div>
</div>

<div class="consultation-lab-viewer">
  <div class="card">
    <div class="card__body">
      <div class="staff-eyebrow">Report files · 3</div>
      <div class="consultation-file-list" data-lab-files>
        <button class="consultation-file consultation-file--btn is-active" type="button"
          data-lab-file="FBC report - Asiri Labs.pdf" data-lab-meta="Uploaded by patient · 09 Jul 2026" data-lab-size="284 KB" data-lab-pages="2">
          <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
          <div class="consultation-file__body">
            <div class="consultation-file__name">FBC report - Asiri Labs.pdf</div>
            <div class="consultation-file__meta">Uploaded by patient · 09 Jul 2026</div>
          </div>
          <span class="consultation-file__tag consultation-file__tag--new">New</span>
        </button>
        <button class="consultation-file consultation-file--btn" type="button"
          data-lab-file="Lipid panel.pdf" data-lab-meta="Attached by Dr. Sample Doctor 1 · 12 May 2026" data-lab-size="96 KB" data-lab-pages="1">
          <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
          <div class="consultation-file__body">
            <div class="consultation-file__name">Lipid panel.pdf</div>
            <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 12 May 2026</div>
          </div>
          <span class="consultation-file__tag">Reviewed</span>
        </button>
        <button class="consultation-file consultation-file--btn" type="button"
          data-lab-file="ECG - 12-lead.pdf" data-lab-meta="Attached by Dr. Sample Doctor 1 · 14 Jan 2026" data-lab-size="512 KB" data-lab-pages="1">
          <span class="consultation-file__icon"><?= icon('file', 18) ?></span>
          <div class="consultation-file__body">
            <div class="consultation-file__name">ECG - 12-lead.pdf</div>
            <div class="consultation-file__meta">Attached by Dr. Sample Doctor 1 · 14 Jan 2026</div>
          </div>
          <span class="consultation-file__tag">Reviewed</span>
        </button>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card__body">
      <div class="consultation-lab-preview__bar">
        <div>
          <div class="consultation-lab-preview__name" data-lab-name>FBC report - Asiri Labs.pdf</div>
          <div class="consultation-lab-preview__meta"><span data-lab-metaline>Uploaded by patient · 09 Jul 2026</span> · <span data-lab-sizeline>284 KB</span></div>
        </div>
        <div style="display:flex;gap:var(--sp-4);flex-wrap:wrap">
          <button class="btn btn--ghost btn--sm" type="button">Open</button>
          <button class="btn btn--secondary btn--sm" type="button">Download</button>
        </div>
      </div>
      <div class="consultation-lab-preview__doc">
        <span class="consultation-lab-preview__doc-icon" style="display:inline-flex;align-items:center;justify-content:center;color:var(--primary)"><?= icon('file', 36) ?></span>
        <div class="consultation-lab-preview__doc-name" data-lab-docname>FBC report - Asiri Labs.pdf</div>
        <div class="consultation-lab-preview__doc-hint">PDF preview · <span data-lab-pagesline>2</span> page(s)</div>
        <div class="consultation-lab-preview__doc-hint">Document secured via record proxy</div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/doctor/lab-results.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>