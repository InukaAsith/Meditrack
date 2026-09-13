<?php

declare(strict_types=1);

$title = 'Billing';
$active = 'billing';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Billing</h1>
    <div class="staff-head__sub">Friday 11 Jul 2026</div>
  </div>
  <div class="staff-head__actions">
    <button class="btn btn--secondary" type="button"><?= icon('print', 14) ?>Print day report</button>
    <a class="btn btn--primary" href="/staff/receptionist/new-invoice"><?= icon('plus', 14) ?>New invoice</a>
  </div>
</div>

<div class="staff-grid">
  <div>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Today's transactions</div>
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Item</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="data-table__row">
                <td class="mono text-bold">INV-0231</td>
                <td class="table-time">09:41</td>
                <td>Nimsith Wickrama</td>
                <td>Consultation with Dr. Sample Doctor 1</td>
                <td>Cash</td>
                <td class="mono text-bold">Rs. 2,500</td>
                <td><span class="badge badge--success">Paid</span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button"><?= icon('print', 13) ?> Print invoice</button>
                </td>
              </tr>
              <tr class="data-table__row">
                <td class="mono text-bold">INV-0230</td>
                <td class="table-time">09:28</td>
                <td>G. G. Mithun Majika</td>
                <td>Consultation with Dr. Sample Doctor 3</td>
                <td>Card</td>
                <td class="mono text-bold">Rs. 3,000</td>
                <td><span class="badge badge--success">Paid</span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button"><?= icon('print', 13) ?> Print invoice</button>
                </td>
              </tr>
              <tr class="data-table__row">
                <td class="mono text-bold">INV-0229</td>
                <td class="table-time">09:14</td>
                <td>K. Ashan Charuka</td>
                <td>Consultation + Pharmacy</td>
                <td>Cash</td>
                <td class="mono text-bold">Rs. 4,180</td>
                <td><span class="badge badge--success">Paid</span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button"><?= icon('print', 13) ?> Print invoice</button>
                </td>
              </tr>
              <tr class="data-table__row">
                <td class="mono text-bold">INV-0228</td>
                <td class="table-time">09:02</td>
                <td>G. G. Mithun Majika</td>
                <td>Consultation with Dr. Sample Doctor 2</td>
                <td>Cash</td>
                <td class="mono text-bold">Rs. 2,000</td>
                <td><span class="badge badge--success">Paid</span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button"><?= icon('print', 13) ?> Print invoice</button>
                </td>
              </tr>
              <tr class="data-table__row">
                <td class="mono text-bold">INV-0227</td>
                <td class="table-time">08:55</td>
                <td>M. L. Omindu Gunathilaka</td>
                <td>Consultation with Dr. Sample Doctor 2</td>
                <td>Online</td>
                <td class="mono text-bold">Rs. 2,000</td>
                <td><span class="badge badge--warning">Refund queued</span></td>
                <td class="data-table__actions">
                  <button class="link-act" type="button"><?= icon('print', 13) ?> Print invoice</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="staff-side">
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">End-of-day summary</div>
        <div class="bill-row">
          <span class="bill-row__label">Cash</span>
          <span class="bill-row__amount">Rs. 45,500</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Card</span>
          <span class="bill-row__amount">Rs. 18,000</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Online (PayHere)</span>
          <span class="bill-row__amount">Rs. 27,500</span>
        </div>
        <div class="bill-row">
          <span class="bill-row__label">Refunds (no-show)</span>
          <span class="bill-row__amount bill-row__amount--danger">− Rs. 2,000</span>
        </div>
        <div class="bill-net"><span>Net collected</span><span class="mono">Rs. 89,000</span></div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow">Cash drawer reconciliation</div>
        <div class="drawer-line"><span class="bill-row__label">Expected in drawer</span><span class="mono">Rs. 45,500</span></div>
        <div class="drawer-line"><span class="bill-row__label">Counted</span><input class="drawer-input" value="Rs. 45,500" aria-label="Counted cash"></div>
        <div class="drawer-balanced"><?= icon('check', 14) ?>Cash drawer matches</div>
        <button class="btn btn--primary btn--block mt-6" type="button"><?= icon('lock', 14) ?>Close day &amp; lock transactions</button>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>