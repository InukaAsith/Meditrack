<?php
$periodOptions = [7 => '7D', 30 => '30D', 90 => '90D', 365 => '12M'];
?>
<div class="period-sel">
  <?php foreach ($periodOptions as $days => $label): ?>
    <a class="period-sel__opt<?= $days === $period ? ' is-active' : '' ?>" href="?period=<?= $days ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>
