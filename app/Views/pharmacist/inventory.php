<?php

declare(strict_types=1);

$title = 'Inventory';
$active = 'inventory';

require __DIR__ . '/header.php';
?>

<meta name="csrf-token" content="<?= e(csrf_token()) ?>">

<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Inventory</h1>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/pharmacist/suppliers"><?= icon('building', 14) ?>Suppliers</a>
    <a class="btn btn--primary" href="/staff/pharmacist/register-batch"><?= icon('plus', 14) ?>Register batch</a>
  </div>
</div>

<?php if (!empty($flashSuccess)): ?><p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($flashSuccess) ?></p><?php endif; ?>
<?php if (!empty($flashError)): ?><p class="form-flash form-flash--error"><?= icon('alert', 14) ?> <?= e($flashError) ?></p><?php endif; ?>

<div class="staff-toolbar">
  <div class="search-box">
    <?= icon('search', 16, 'search-box__icon') ?>
    <input class="search-box__input" type="search" id="inventory-search" value="<?= e($search) ?>" placeholder="Search medicine, generic name, batch or supplier…" autocomplete="off" aria-label="Search medicines">
  </div>
  <div class="staff-filters">
    <button class="staff-pill<?= $statusFilter === 'all' ? ' is-active' : '' ?>" type="button" data-filter="all">All (<?= e((string) $counts['all']) ?>)</button>
    <button class="staff-pill<?= $statusFilter === 'low' ? ' is-active' : '' ?>" type="button" data-filter="low">Low stock (<?= e((string) $counts['low']) ?>)</button>
    <button class="staff-pill<?= $statusFilter === 'expiring' ? ' is-active' : '' ?>" type="button" data-filter="expiring">Expiring soon (<?= e((string) $counts['expiring']) ?>)</button>
    <button class="staff-pill<?= $statusFilter === 'out' ? ' is-active' : '' ?>" type="button" data-filter="out">Out of stock (<?= e((string) $counts['out']) ?>)</button>
  </div>
</div>

<div class="card">
  <div class="card__body">
    <?php if (!$items): ?>
      <p class="field__desc">No medicines found.</p>
    <?php else: ?>
      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th><button class="inventory-sort" type="button" data-sort="medicine">Medicine</button></th>
              <th>Form</th>
              <th>Batch</th>
              <th>Supplier</th>
              <th><button class="inventory-sort" type="button" data-sort="expiry">Expiry</button></th>
              <th><button class="inventory-sort" type="button" data-sort="stock">Stock</button></th>
              <th>Reorder level</th>
              <th>Status</th>
              <th class="data-table__actions">Action</th>
            </tr>
          </thead>
          <tbody id="inventory-body">
            <?php foreach ($items as $item): ?>
              <?php $settings = $item['config']; ?>
              
              <tr class="data-table__row" data-medicine-row="<?= e((string) $item['medicine_id']) ?>"
                data-search="<?= e(strtolower($item['medicine'] . ' ' . $item['generic'] . ' ' . $item['batch'] . ' ' . $item['supplier'] . ' ' . implode(' ', array_column($settings['batches'], 'supplier')))) ?>"
                data-stock="<?= e((string) $item['stock']) ?>"
                data-expiry="<?= e($item['expiry']) ?>">
                <td>
                  <div class="table-patient"><strong><?= e($item['medicine']) ?></strong><span class="table-patient__plain"><?= e($item['generic']) ?></span></div>
                </td>
                <td><?= e($item['form']) ?></td>
                <td class="mono"><?= e($item['batch']) ?></td>
                <td><?= e($item['supplier']) ?></td>
                <td<?= $item['expiry_tone'] === 'red' ? ' class="text-danger"' : '' ?>><?= e($item['expiry']) ?></td>
                <td class="mono text-bold" data-stock-number><?= e($item['stock_display']) ?></td>
                <td class="mono" data-reorder-number><?= e((string) $item['reorder']) ?></td>
                <td><span class="badge badge--<?= e($item['status_tone']) ?>" data-status-badge><?= e($item['status']) ?></span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button" data-settings-toggle="<?= e((string) $item['medicine_id']) ?>">Settings</button>
                </td>
              </tr>

              
              <tr data-settings-row="<?= e((string) $item['medicine_id']) ?>" hidden>
                <td class="inventory-settings" colspan="9">
                  <div class="inventory-settings__grid">
                    <div>
                      <div class="form-2col">
                        <label class="field">
                          <span class="field__label">Low stock alert at</span>
                          <input class="field__input" type="number" min="0" max="10000" step="1" value="<?= e((string) $settings['threshold']) ?>" data-setting="threshold">
                        </label>
                        <label class="field">
                          <span class="field__label">Unit price (Rs.)</span>
                          <input class="field__input" type="number" min="0.01" max="1000000" step="0.01" value="<?= e((string) $settings['price']) ?>" data-setting="price">
                        </label>
                      </div>

                      <div class="inventory-settings__switches">
                        <label class="toggle">
                          <input class="visually-hidden" type="checkbox" data-setting="requires-rx"<?= $settings['requires_rx'] ? ' checked' : '' ?>>
                          <span class="toggle__track"><span class="toggle__thumb"></span></span>
                          <span class="toggle__label">Needs a prescription</span>
                        </label>
                        <label class="toggle">
                          <input class="visually-hidden" type="checkbox" data-setting="out-of-stock"<?= $settings['damaged_override'] ? ' checked' : '' ?>>
                          <span class="toggle__track"><span class="toggle__thumb"></span></span>
                          <span class="toggle__label">Mark as out of stock</span>
                        </label>
                      </div>

                      <div class="field__label mt-6">Adjust stock</div>
                      <div class="inventory-settings__adjust">
                        <input class="field__input" type="number" value="0" aria-label="Units to add or remove" data-adjust="quantity">
                        <?php if (count($settings['batches']) > 1): ?>
                          <select class="field__input" aria-label="Batch" data-adjust="batch">
                            <?php foreach ($settings['batches'] as $batch): ?>
                              <option value="<?= e((string) $batch['id']) ?>"><?= e($batch['code']) ?> (<?= e((string) $batch['units']) ?> &middot; <?= e($batch['supplier'] ?? '-') ?>)</option>
                            <?php endforeach; ?>
                          </select>
                        <?php elseif ($settings['batches']): ?>
                          <input type="hidden" value="<?= e((string) $settings['batches'][0]['id']) ?>" data-adjust="batch">
                        <?php endif; ?>
                        <select class="field__input" aria-label="Reason" data-adjust="reason">
                          <option value="damaged">Damaged</option>
                          <option value="baseline_intake">Opening stock</option>
                          <option value="audit_correction" selected>Stock count fix</option>
                        </select>
                        <button class="btn btn--secondary btn--sm" type="button" data-adjust-apply="<?= e((string) $item['medicine_id']) ?>">Apply</button>
                      </div>

                      
                      <p class="form-error" data-settings-error hidden></p>

                      <div class="form-actions">
                        <button class="btn btn--primary" type="button" data-settings-save="<?= e((string) $item['medicine_id']) ?>">Save settings</button>
                      </div>
                    </div>

                    <div>
                      <div class="staff-eyebrow staff-eyebrow--row">
                        <span>Batches</span>
                        <a href="/staff/pharmacist/register-batch">Restock</a>
                      </div>
                      <?php if (!$settings['batches']): ?>
                        <p class="field__desc">No batches yet.</p>
                      <?php endif; ?>
                      <div class="morning-list">
                        <?php foreach ($settings['batches'] as $batch): ?>
                          <?php $removed = $batch['status'] === 'damaged'; ?>
                          <div class="morning-row" data-batch="<?= e((string) $batch['id']) ?>">
                            <div>
                              <div class="morning-row__name mono">
                                <?= e($batch['code']) ?>
                                <span class="badge badge--muted"><?= e($batch['supplier'] ?? '-') ?></span>
                              </div>
                              <div class="morning-row__sub"><span data-batch-units><?= e((string) $batch['units']) ?></span> units, expires <?= e($batch['exp']) ?></div>
                            </div>
                            
                            <div data-batch-removed<?= $removed ? '' : ' hidden' ?>>
                              <span class="badge badge--muted">Removed</span>
                            </div>
                            <div data-batch-actions<?= $removed ? ' hidden' : '' ?>>
                              <button class="link-act link-act--danger" type="button" data-batch-remove>Remove</button>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <p class="field__desc" id="inventory-empty" hidden>No medicines match.</p>
    <?php endif; ?>
  </div>
</div>

<script src="/assets/js/pharmacist/inventory.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>
