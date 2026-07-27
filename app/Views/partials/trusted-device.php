<?php
if (!empty($deviceTrusted)): ?>
    <div class="card mb-7">
      <div class="card__body">
        <div class="staff-eyebrow">Trusted device</div>
        <p class="text-sm text-muted">This device is trusted. You sign in here without a code.</p>
        <form action="/staff/forget-device" method="post">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button class="btn btn--secondary btn--block" type="submit"><?= icon('key', 15) ?> Remove trusted device</button>
        </form>
      </div>
    </div>
<?php endif; ?>
