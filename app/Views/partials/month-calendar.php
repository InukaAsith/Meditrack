<?php

declare(strict_types=1);

$calSelected = $calSelected ?? null;
$calDots = $calDots ?? null;
$calDayLabels = $calDayLabels ?? null;
$calInteractive = $calInteractive ?? false;
$calMonths = max(1, (int) ($calMonths ?? 1));

$dotTone = ['appointment' => 'primary', 'followup' => 'info', 'refill' => 'warning', 'contact' => 'danger'];

$tall = $calDayLabels !== null;

$openMonth = 0;
for ($m = 0; $m < $calMonths; $m++) {
  if ($calSelected !== null && str_starts_with($calSelected, date('Y-m', mktime(0, 0, 0, $calMonth + $m, 1, $calYear)))) {
    $openMonth = $m;
  }
}
$openLabel = date('F Y', mktime(0, 0, 0, $calMonth + $openMonth, 1, $calYear));
?>
<div class="month-cal" data-month-cal>
  <div class="month-cal__head">
    <button class="month-cal__nav" type="button" data-month-prev aria-label="Previous month" <?= $openMonth > 0 ? '' : ' disabled' ?>><?= icon('chevronLeft') ?></button>
    <h3 data-month-label><?= e($openLabel) ?></h3>
    <button class="month-cal__nav" type="button" data-month-next aria-label="Next month" <?= $openMonth < $calMonths - 1 ? '' : ' disabled' ?>><?= icon('chevronRight') ?></button>
  </div>
  <div class="month-cal__weekdays">
    <?php foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $d): ?>
      <span><?= $d ?></span>
    <?php endforeach; ?>
  </div>
  <?php for ($m = 0; $m < $calMonths; $m++):
    $monthTs = mktime(0, 0, 0, $calMonth + $m, 1, $calYear);
    $year = (int) date('Y', $monthTs);
    $month = (int) date('n', $monthTs);
    $startWeekday = (int) date('w', $monthTs);
    $daysInMonth = (int) date('t', $monthTs);
  ?>
    <div data-month="<?= sprintf('%04d-%02d', $year, $month) ?>"
      data-month-label="<?= e(date('F Y', $monthTs)) ?>" <?= $m === $openMonth ? '' : ' hidden' ?>>
      <div class="month-cal__grid">
        <?php for ($i = 0; $i < $startWeekday; $i++): ?>
          <div class="month-cal__cell month-cal__cell--empty"></div>
        <?php endfor; ?>
        <?php for ($d = 1; $d <= $daysInMonth; $d++):
          $key = sprintf('%04d-%02d-%02d', $year, $month, $d);
          $sub = $tall ? ($calDayLabels[$key] ?? null) : null;
          $dots = (!$tall && $calDots !== null) ? ($calDots[$key] ?? []) : [];
          $disabled = $tall && $sub === null;
          $classes = 'month-cal__cell'
            . ($tall ? ' month-cal__cell--tall' : '')
            . ($key === $calSelected ? ' is-selected' : '')
            . ($disabled ? ' is-disabled' : '');
          $tag = ($calInteractive && (!$disabled || $tall)) ? 'button' : 'div';
        ?>
          <<?= $tag ?> class="<?= $classes ?>" <?php
                                                if ($tag === 'button') {
                                                  echo ' type="button" data-day="' . e($key) . '"';
                                                }
                                                if ($disabled) {
                                                  echo $tag === 'button' ? ' disabled' : ' aria-disabled="true"';
                                                }
                                                ?>>
            <span><?= $d ?></span>
            <?php if ($tall): ?><span class="month-cal__sublabel"><?= e($sub ?? '') ?></span><?php endif; ?>
            <?php if ($dots): ?>
              <span class="month-cal__dots">
                <?php foreach ($dots as $t): ?><span class="month-cal__dot month-cal__dot--<?= e($dotTone[$t] ?? 'muted') ?>"></span><?php endforeach; ?>
              </span>
            <?php endif; ?>
          </<?= $tag ?>>
        <?php endfor; ?>
      </div>
    </div>
  <?php endfor; ?>
</div>