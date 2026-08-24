<?php

declare(strict_types=1);

$title = 'Dashboard';
$active = 'dashboard';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title"><?= e(greeting()) ?>, <?= e(first_name($staff['name'])) ?></h1>
    <div class="staff-head__sub"><?= e(date('l j M Y')) ?></div>
  </div>
  <div class="staff-head__actions">
    <a class="btn btn--secondary" href="/staff/receptionist/patient-register"><?= icon('plus', 14) ?>Register patient</a>
    <a class="btn btn--primary" href="/staff/receptionist/book"><?= icon('plus', 14) ?>New booking</a>
  </div>
</div>

<div class="staff-toolbar">
  <form class="search-box" method="get" action="/staff/receptionist/patients">
    <?= icon('search', 16, 'search-box__icon') ?>
    <input class="search-box__input" type="search" name="q" placeholder="Search name, NIC, phone or Patient ID…" autocomplete="off" aria-label="Search patients">
  </form>
</div>

<div class="staff-kpis">
  <div class="staff-kpi">
    <div class="staff-kpi__label">Appointments today</div>
    <div class="staff-kpi__value">42</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Arrived &amp; checked in</div>
    <div class="staff-kpi__value staff-kpi__value--success">18</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Awaiting check-in</div>
    <div class="staff-kpi__value staff-kpi__value--warning">7</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Cash collected today</div>
    <div class="staff-kpi__value">Rs. 45,500</div>
  </div>
  <div class="staff-kpi">
    <div class="staff-kpi__label">Walk-in tokens issued</div>
    <div class="staff-kpi__value">3</div>
  </div>
</div>

<div class="staff-grid">
  <div>
    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Today's appointments</span>
          <a href="/staff/receptionist/appointments">Open appointments →</a>
        </div>
        <div class="dash-appts-toolbar">
          <div class="staff-filters" data-filter-group>
            <button class="staff-pill is-active" type="button" data-filter="all">All statuses</button>
            <button class="staff-pill" type="button" data-filter="confirmed">Confirmed</button>
            <button class="staff-pill" type="button" data-filter="pending">Pending</button>
            <button class="staff-pill" type="button" data-filter="cancelled">Cancelled</button>
          </div>
          <label class="doctor-select">
            <?= icon('profile', 14) ?>
            <select id="dash-doc-select" aria-label="Filter by doctor">
              <option value="all">All doctors</option>
              <option value="AS">Dr. Sample Doctor 1</option>
              <option value="RF">Dr. Sample Doctor 3</option>
              <option value="MP">Dr. Sample Doctor 2</option>
            </select>
          </label>
        </div>
        <div class="data-table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Type</th>
                <th>Payment</th>
                <th>Status</th>
                <th class="data-table__actions">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="data-table__row" data-doc="AS" data-group="confirmed">
                <td class="table-time">09:15</td>
                <td>
                  <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-1088</span></div>
                </td>
                <td>Dr. Sample Doctor 1</td>
                <td>Follow-up</td>
                <td>Paid online</td>
                <td><span class="badge badge--success">Checked in</span></td>
                <td class="data-table__actions"></td>
              </tr>
              <tr class="data-table__row" data-doc="AS" data-group="confirmed">
                <td class="table-time">09:30</td>
                <td>
                  <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-1121</span></div>
                </td>
                <td>Dr. Sample Doctor 1</td>
                <td>New</td>
                <td>Paid in cash</td>
                <td><span class="badge badge--primary">Ready</span></td>
                <td class="data-table__actions"></td>
              </tr>
              <tr class="data-table__row" data-doc="RF" data-group="confirmed">
                <td class="table-time">09:40</td>
                <td>
                  <div class="table-patient"><strong>G. G. Mithun Majika</strong><span>PT-0844</span></div>
                </td>
                <td>Dr. Sample Doctor 3</td>
                <td>Follow-up</td>
                <td>Paid online</td>
                <td><span class="badge badge--success">Checked in</span></td>
                <td class="data-table__actions"></td>
              </tr>
              <tr class="data-table__row" data-doc="AS" data-group="pending">
                <td class="table-time">09:45</td>
                <td>
                  <div class="table-patient"><strong>K.A. Inuka Asith</strong><span>PT-0967</span></div>
                </td>
                <td>Dr. Sample Doctor 1</td>
                <td>New</td>
                <td>Pay at counter</td>
                <td><span class="badge badge--muted">Not arrived yet</span></td>
                <td class="data-table__actions"><a class="link-act" href="/staff/receptionist/check-in">Check in</a></td>
              </tr>
              <tr class="data-table__row" data-doc="AS" data-group="pending">
                <td class="table-time">10:00</td>
                <td>
                  <div class="table-patient"><strong>Sandanu Dulmeth</strong><span>PT-1315</span></div>
                </td>
                <td>Dr. Sample Doctor 1</td>
                <td>New</td>
                <td>Pay at counter</td>
                <td><span class="badge badge--muted">Not arrived yet</span></td>
                <td class="data-table__actions"><a class="link-act" href="/staff/receptionist/check-in">Check in</a></td>
              </tr>
              <tr class="data-table__row" data-doc="MP" data-group="pending">
                <td class="table-time">10:10</td>
                <td>
                  <div class="table-patient"><strong>K. Ashan Charuka</strong><span>PT-1355</span></div>
                </td>
                <td>Dr. Sample Doctor 2</td>
                <td>New</td>
                <td>Paid online</td>
                <td><span class="badge badge--muted">Not arrived yet</span></td>
                <td class="data-table__actions"><a class="link-act" href="/staff/receptionist/check-in">Check in</a></td>
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
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Doctor status</span>
          <a href="/staff/receptionist/doctor-status">Doctor status →</a>
        </div>
        <div class="doctor-status-list">
          <div class="doctor-status-row">
            <span class="doctor-status-row__avatar avatar--blue">D1</span>
            <div class="doctor-status-row__body">
              <div class="doctor-status-row__name">Dr. Sample Doctor 1</div>
            </div>
            <span class="badge badge--warning">Late 5m</span>
          </div>
          <div class="doctor-status-row">
            <span class="doctor-status-row__avatar avatar--teal">D3</span>
            <div class="doctor-status-row__body">
              <div class="doctor-status-row__name">Dr. Sample Doctor 3</div>
            </div>
            <span class="badge badge--success">Arrived</span>
          </div>
          <div class="doctor-status-row">
            <span class="doctor-status-row__avatar avatar--amber">D2</span>
            <div class="doctor-status-row__body">
              <div class="doctor-status-row__name">Dr. Sample Doctor 2</div>
            </div>
            <span class="badge badge--danger">Leave</span>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span>Message board</span>
          <label class="doctor-select doctor-select--sm">
            <?= icon('profile', 13) ?>
            <select id="msg-doc-select" aria-label="Filter message board by doctor">
              <option value="all">All doctors</option>
              <option value="AS">Dr. Sample Doctor 1</option>
              <option value="RF">Dr. Sample Doctor 3</option>
              <option value="MP">Dr. Sample Doctor 2</option>
            </select>
          </label>
        </div>
        <div class="message-board" id="msg-board">
          <div data-doc="AS">
            <div class="message-board__post">
              <span class="message-board__avatar">D1</span>
              <div class="message-board__body">
                <strong>Dr. Sample Doctor 1 (Doctor):</strong>
                <p>Test message 1</p>
                <span class="message-board__time">08:40</span>
              </div>
            </div>
          </div>
          <div data-doc="all">
            <div class="message-board__post">
              <span class="message-board__avatar">SD</span>
              <div class="message-board__body">
                <strong>Sandanu (Reception):</strong>
                <p>Test message 2</p>
                <span class="message-board__time">09:22</span>
              </div>
            </div>
          </div>
          <div data-doc="MP">
            <div class="message-board__post">
              <span class="message-board__avatar">D2</span>
              <div class="message-board__body">
                <strong>Dr. Sample Doctor 2 (Doctor):</strong>
                <p>Test message 3</p>
                <span class="message-board__time">09:26</span>
              </div>
            </div>
          </div>
          <div class="message-board__empty" id="msg-empty" hidden>No posts for this doctor yet.</div>
        </div>
        <template id="msg-post-template">
          <div data-doc="">
            <div class="message-board__post">
              <span class="message-board__avatar">SD</span>
              <div class="message-board__body">
                <strong>Sandanu (reception):</strong>
                <p></p>
                <span class="message-board__time">just now</span>
              </div>
            </div>
          </div>
        </template>
        <div class="message-board__compose">
          <input type="text" id="msg-compose" placeholder="Post to message board…" aria-label="Post to message board">
          <button class="btn btn--primary btn--sm" type="button" id="msg-post">Post</button>
        </div>
      </div>
    </div>

  </div>
</div>
<script src="/assets/js/receptionist/dashboard.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>