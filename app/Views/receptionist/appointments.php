<?php

declare(strict_types=1);

$title = 'Appointments';
$active = 'appointments';

require __DIR__ . '/header.php';
?>
<div class="staff-head">
  <div>
    <h1 class="staff-head__title">Appointments</h1>
  </div>
  <div class="staff-head__actions">

    <a class="btn btn--primary" href="/staff/receptionist/book"><?= icon('plus', 14) ?>New booking</a>
  </div>
</div>

<div id="queue-paused" hidden>
  <div class="queue-paused" role="status">
    <span class="queue-paused__icon">⏸</span>
    <div class="queue-paused__body">
      <strong>Live queue paused.</strong> Emergency patient <span id="qp-name"></span> is with <span id="qp-doc"></span>.
      Waiting patients keep their place; ETAs resume when you release the queue.
    </div>
    <div class="queue-paused__actions">
      <a class="btn btn--secondary btn--sm" href="/staff/receptionist/check-in?mode=emergency">Add details</a>
      <button class="btn btn--primary btn--sm" type="button" data-emergency-resume>Resume queue</button>
    </div>
  </div>
</div>

<div class="cal-controls">
  <div class="cal-viewtabs" data-view-tabs>
    <button class="cal-viewtabs__item is-active" type="button" data-view="day">Day</button>
    <button class="cal-viewtabs__item" type="button" data-view="week">Week</button>
    <button class="cal-viewtabs__item" type="button" data-view="month">Month</button>
  </div>
  <div data-when="day week">
    <div class="cal-datenav">
      <button type="button" aria-label="Previous">‹</button>
      <span data-date-label>Friday 24 Jul 2026</span>
      <button type="button" aria-label="Next">›</button>
    </div>
  </div>
  <span class="cal-spacer"></span>
  <div data-when="day week">
    <div class="search-box" style="max-width:320px">
      <?= icon('search', 16, 'search-box__icon') ?>
      <input class="search-box__input" type="search" placeholder="APT-1042 or patient name / NIC…" aria-label="Find appointment">
    </div>
  </div>
</div>

<section data-view-panel="day">
  <div class="appt-layout">
    <aside class="doc-rail">
      <div class="doc-rail__label">Doctor</div>
      <button class="doc-rail__item is-active" type="button" data-doc="all">
        <span class="doc-rail__grid">⊞</span>
        <span class="doc-rail__name">All doctors</span>
      </button>
      <button class="doc-rail__item" type="button" data-doc="AS">
        <span class="doc-rail__avatar avatar--blue">D1</span>
        <span class="doc-rail__body">
          <span class="doc-rail__name">Dr. Sample Doctor 1</span>
          <span class="doc-rail__meta inline-parts"><span>General</span><span>24 today</span></span>
        </span>
      </button>
      <button class="doc-rail__item" type="button" data-doc="RF">
        <span class="doc-rail__avatar avatar--teal">D3</span>
        <span class="doc-rail__body">
          <span class="doc-rail__name">Dr. Sample Doctor 3</span>
          <span class="doc-rail__meta inline-parts"><span>Pediatrics</span><span>12 today</span></span>
        </span>
      </button>
      <button class="doc-rail__item" type="button" data-doc="MP">
        <span class="doc-rail__avatar avatar--amber">D2</span>
        <span class="doc-rail__body">
          <span class="doc-rail__name">Dr. Sample Doctor 2</span>
          <span class="doc-rail__meta inline-parts"><span>ENT</span><span>10 today</span></span>
        </span>
      </button>

      <div data-day-mode="one" hidden>
        <div class="doc-rail__label mt-7">Filters</div>
        <div class="staff-filters staff-filters--col mt-3" data-appt-filters>
          <button class="staff-pill is-active" type="button">All</button>
          <button class="staff-pill" type="button">Upcoming</button>
          <button class="staff-pill" type="button">Cancelled</button>
          <button class="staff-pill" type="button">No-shows</button>
        </div>
      </div>
    </aside>

    <div class="appt-main">
      <div data-day-mode="all">
        <div class="day-cols">
          <div class="day-col" data-day-col="RF" role="button" tabindex="0" title="Open Dr. Sample Doctor 3's day queue">
            <div class="day-col__head">
              <span class="day-col__avatar avatar--teal">D3</span>
              <div>
                <div class="day-col__name">Dr. Sample Doctor 3</div>
                <div class="day-col__meta inline-parts"><span>Pediatrics</span><span>12/16 booked</span></div>
              </div>
            </div>
            <div class="day-col__slots">
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>G. G. Mithun Majika</span><span>FU</span></span>
                <span class="day-slot__time">09:00</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>K.A. Inuka Asith</span><span>New</span></span>
                <span class="day-slot__time">09:30</span>
              </div>
              <div class="day-slot day-slot--emergency">
                <span class="day-slot__label">Cancelled</span>
                <span class="day-slot__time">10:00</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>Nimsith Wickrama</span><span>FU</span></span>
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
                <span class="day-slot__label">Lunch break</span>
                <span class="day-slot__time">12:00</span>
              </div>
            </div>
          </div>
          <div class="day-col" data-day-col="MP" role="button" tabindex="0" title="Open Dr. Sample Doctor 2's day queue">
            <div class="day-col__head">
              <span class="day-col__avatar avatar--amber">D2</span>
              <div>
                <div class="day-col__name">Dr. Sample Doctor 2</div>
                <div class="day-col__meta inline-parts"><span>ENT</span><span>10/16 booked</span></div>
              </div>
            </div>
            <div class="day-col__slots">
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>G. G. Mithun Majika</span><span>FU</span></span>
                <span class="day-slot__time">09:00</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>Sandanu Dulmeth</span><span>New</span></span>
                <span class="day-slot__time">09:30</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>K. Ashan Charuka</span><span>New</span></span>
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
                <span class="day-slot__label">Lunch break</span>
                <span class="day-slot__time">12:00</span>
              </div>
            </div>
          </div>
          <div class="day-col" data-day-col="AS" role="button" tabindex="0" title="Open Dr. Sample Doctor 1's day queue">
            <div class="day-col__head">
              <span class="day-col__avatar avatar--blue">D1</span>
              <div>
                <div class="day-col__name">Dr. Sample Doctor 1</div>
                <div class="day-col__meta inline-parts"><span>General</span><span>24/28 booked</span></div>
              </div>
            </div>
            <div class="day-col__slots">
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>Nimsith Wickrama</span><span>FU</span></span>
                <span class="day-slot__time">09:00</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>M. L. Omindu Gunathilaka</span><span>New</span></span>
                <span class="day-slot__time">09:30</span>
              </div>
              <div class="day-slot day-slot--booked" draggable="true">
                <span class="day-slot__label inline-parts"><span>Sandanu D.</span><span>New</span></span>
                <span class="day-slot__time">10:00</span>
              </div>
              <div class="day-slot day-slot--available">
                <span class="day-slot__label">Available</span>
                <span class="day-slot__time">10:30</span>
              </div>
              <div class="day-slot day-slot--walkin">
                <span class="day-slot__label">Walk-in token W-07</span>
                <span class="day-slot__time">11:00</span>
              </div>
              <div class="day-slot day-slot--available">
                <span class="day-slot__label">Available</span>
                <span class="day-slot__time">11:30</span>
              </div>
              <div class="day-slot day-slot--blocked">
                <span class="day-slot__label">Lunch break</span>
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
        </div>
      </div>

      <div data-day-mode="one" hidden>
        <div class="appt-single">
          <div class="card">
            <div class="card__body">
              <div class="appt-single__head">
                <span class="appt-single__title" data-single-title>Dr. Sample Doctor 1 on Friday 24 Jul (24 appointments)</span>
              </div>
              <div class="data-table-wrap">
                <table class="data-table appt-table" data-queue id="day-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Time</th>
                      <th>Patient</th>
                      <th>Appointment</th>
                      <th>Status</th>
                      <th class="data-table__actions">Actions</th>
                      <th class="qcol-checkin">Check-in</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1088">
                      <td class="appt-qnum"><span class="appt-qnum__badge">#1</span></td>
                      <td class="table-time">09:15</td>
                      <td>
                        <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-1088</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1038</span><span>Follow-up</span></span></td>
                      <td><span class="badge badge--primary">In queue #1</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                          <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                          <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                        </div>
                      </td>
                      <td class="qcol-checkin"></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1121">
                      <td class="appt-qnum"><span class="appt-qnum__badge">#2</span></td>
                      <td class="table-time">09:30</td>
                      <td>
                        <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-1121</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1039</span><span>New</span></span></td>
                      <td><span class="badge badge--success">Checked in</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                          <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                          <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                        </div>
                      </td>
                      <td class="qcol-checkin"></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-0967">
                      <td class="appt-qnum"><span class="appt-qnum__badge">#3</span></td>
                      <td class="table-time">09:45</td>
                      <td>
                        <div class="table-patient"><strong>K.A. Inuka Asith</strong><span>PT-0967</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1040</span><span>Follow-up</span></span></td>
                      <td><span class="badge badge--warning">Rescheduled → 10:15</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                          <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                          <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                          <div class="row-menu" data-rowmenu>
                            <button class="row-menu__toggle" type="button" data-rowmenu-toggle aria-haspopup="true" aria-expanded="false" aria-label="More actions">⋯</button>
                            <div data-rowmenu-pop hidden>
                              <div class="row-menu__pop">
                                <a class="row-menu__item" href="/staff/receptionist/reschedule?appt=APT-1040&patient=K.A.%20Inuka%20Asith"><?= icon('calendar', 14) ?>Reschedule</a>
                                <button class="row-menu__item row-menu__item--danger" type="button" data-q-cancel data-patient="K.A. Inuka Asith"><?= icon('close', 14) ?>Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="qcol-checkin"><a class="btn btn--secondary btn--xs" href="/staff/receptionist/check-in">Check in</a></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1204">
                      <td class="appt-qnum"><span class="appt-qnum__badge">#4</span></td>
                      <td class="table-time">09:50</td>
                      <td>
                        <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-1204</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1041</span><span>New</span></span></td>
                      <td><span class="badge badge--success">Checked in</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                          <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                          <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                        </div>
                      </td>
                      <td class="qcol-checkin"></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1315">
                      <td class="appt-qnum"><span class="appt-qnum__badge">#5</span></td>
                      <td class="table-time">10:00</td>
                      <td>
                        <div class="table-patient"><strong>Sandanu Dulmeth</strong><span>PT-1315</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1042</span><span>New</span></span></td>
                      <td><span class="badge badge--muted">Not arrived</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                          <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                          <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                          <button class="icon-act icon-act--flag" type="button" aria-label="Flag">⚑</button>
                          <div class="row-menu" data-rowmenu>
                            <button class="row-menu__toggle" type="button" data-rowmenu-toggle aria-haspopup="true" aria-expanded="false" aria-label="More actions">⋯</button>
                            <div data-rowmenu-pop hidden>
                              <div class="row-menu__pop">
                                <a class="row-menu__item" href="/staff/receptionist/reschedule?appt=APT-1042&patient=Sandanu%20Dulmeth"><?= icon('calendar', 14) ?>Reschedule</a>
                                <button class="row-menu__item row-menu__item--danger" type="button" data-q-cancel data-patient="Sandanu Dulmeth"><?= icon('close', 14) ?>Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="qcol-checkin"><a class="btn btn--secondary btn--xs" href="/staff/receptionist/check-in">Check in</a></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="0">
                      <td class="appt-qnum"></td>
                      <td class="table-time">10:15</td>
                      <td>
                        <div class="table-patient"><strong>Walk-in token W-04</strong><span>Issued 09:41, fits after 10:00</span></div>
                      </td>
                      <td>Walk-in</td>
                      <td><span class="badge badge--warning">Walk-in</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                        </div>
                      </td>
                      <td class="qcol-checkin"></td>
                    </tr>
                    <tr class="data-table__row" data-queue-row data-inqueue="0">
                      <td class="appt-qnum"></td>
                      <td class="table-time">10:30</td>
                      <td>
                        <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-0871</span></div>
                      </td>
                      <td><span class="inline-parts"><span>APT-1043</span><span>Follow-up</span></span></td>
                      <td><span class="badge badge--danger">Cancelled, refund queued</span></td>
                      <td class="data-table__actions">
                        <div class="row-actions row-actions--on">
                          <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                        </div>
                      </td>
                      <td class="qcol-checkin"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div id="day-skipped" data-skip-board hidden>
                <div class="skip-board">
                  <div class="skip-board__head">
                    <span class="staff-eyebrow m-0">Skipped patients</span>
                    <span class="skip-board__hint">they go back in after #3</span>
                  </div>
                  <div class="skip-board__list" data-skip-list>
                    <div data-skip-chip="PT-1088" hidden>
                      <div class="skip-chip">
                        <div class="skip-chip__who"><strong>Nimsith Wickrama</strong><span>PT-1088</span></div>
                        <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                      </div>
                    </div>
                    <div data-skip-chip="PT-1121" hidden>
                      <div class="skip-chip">
                        <div class="skip-chip__who"><strong>M. L. Omindu Gunathilaka</strong><span>PT-1121</span></div>
                        <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                      </div>
                    </div>
                    <div data-skip-chip="PT-0967" hidden>
                      <div class="skip-chip">
                        <div class="skip-chip__who"><strong>K.A. Inuka Asith</strong><span>PT-0967</span></div>
                        <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                      </div>
                    </div>
                    <div data-skip-chip="PT-1204" hidden>
                      <div class="skip-chip">
                        <div class="skip-chip__who"><strong>Nimsith Wickrama</strong><span>PT-1204</span></div>
                        <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                      </div>
                    </div>
                    <div data-skip-chip="PT-1315" hidden>
                      <div class="skip-chip">
                        <div class="skip-chip__who"><strong>Sandanu Dulmeth</strong><span>PT-1315</span></div>
                        <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <aside class="card appt-msg">
            <div class="card__body">
              <div class="staff-eyebrow">Message board <span class="label-note"><span data-single-doc>Dr. Sample Doctor 1</span></span></div>
              <div class="message-board" id="appt-msgboard">
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
                <div class="message-board__empty" id="appt-msg-empty" hidden>No posts for this doctor yet.</div>
              </div>
              <template id="appt-msg-template">
                <div data-doc="">
                  <div class="message-board__post">
                    <span class="message-board__avatar">SD</span>
                    <div class="message-board__body">
                      <strong>Sandanu (reception):</strong>
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
        </div>
      </div>
    </div>
  </div>
</section>

<section data-view-panel="week" hidden>
  <div class="week-grid">
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">MON</span>
        <span class="week-day__date">20 Jul</span>
      </div>
      <div class="week-day__count">52 <span>patients</span></div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">TUE</span>
        <span class="week-day__date">21 Jul</span>
      </div>
      <div class="week-day__count">38 <span>patients</span></div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">WED</span>
        <span class="week-day__date">22 Jul</span>
      </div>
      <div class="week-day__count">24 <span>patients</span></div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">THU</span>
        <span class="week-day__date">23 Jul</span>
      </div>
      <div class="week-day__count">52 <span>patients</span></div>
    </div>
    <div class="week-day is-today">
      <div class="week-day__head">
        <span class="week-day__dow">FRI</span>
        <span class="week-day__date">24 Jul</span>
      </div>
      <div class="week-day__count">52 <span>patients</span></div>
    </div>
    <div class="week-day is-closed">
      <div class="week-day__head">
        <span class="week-day__dow">SAT</span>
        <span class="week-day__date">25 Jul</span>
      </div>
      <div class="week-day__closed">Clinic closed</div>
    </div>
    <div class="week-day">
      <div class="week-day__head">
        <span class="week-day__dow">SUN</span>
        <span class="week-day__date">26 Jul</span>
      </div>
      <div class="week-day__count">24 <span>patients</span></div>
    </div>
  </div>

  <div class="card week-queue">
    <div class="card__body">
      <div class="appt-single__head">
        <span class="appt-single__title" data-week-queue-title>Dr. Sample Doctor 1 on Fri 24 Jul (7 patients)</span>
        <label class="doctor-select doctor-select--sm">
          <?= icon('profile', 13) ?>
          <select aria-label="Queue doctor">
            <option value="AS" selected>Dr. Sample Doctor 1</option>
            <option value="RF">Dr. Sample Doctor 3</option>
            <option value="MP">Dr. Sample Doctor 2</option>
          </select>
        </label>
      </div>
      <div class="data-table-wrap">
        <table class="data-table appt-table" data-queue id="week-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Time</th>
              <th>Patient</th>
              <th>Appointment</th>
              <th>Status</th>
              <th class="data-table__actions">Actions</th>
              <th class="qcol-checkin">Check-in</th>
            </tr>
          </thead>
          <tbody>
            <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1088">
              <td class="appt-qnum"><span class="appt-qnum__badge">#1</span></td>
              <td class="table-time">09:15</td>
              <td>
                <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-1088</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1038</span><span>Follow-up</span></span></td>
              <td><span class="badge badge--primary">In queue #1</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                  <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                  <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                </div>
              </td>
              <td class="qcol-checkin"></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1121">
              <td class="appt-qnum"><span class="appt-qnum__badge">#2</span></td>
              <td class="table-time">09:30</td>
              <td>
                <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-1121</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1039</span><span>New</span></span></td>
              <td><span class="badge badge--success">Checked in</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                  <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                  <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                </div>
              </td>
              <td class="qcol-checkin"></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-0967">
              <td class="appt-qnum"><span class="appt-qnum__badge">#3</span></td>
              <td class="table-time">09:45</td>
              <td>
                <div class="table-patient"><strong>K.A. Inuka Asith</strong><span>PT-0967</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1040</span><span>Follow-up</span></span></td>
              <td><span class="badge badge--warning">Rescheduled → 10:15</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                  <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                  <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                  <div class="row-menu" data-rowmenu>
                    <button class="row-menu__toggle" type="button" data-rowmenu-toggle aria-haspopup="true" aria-expanded="false" aria-label="More actions">⋯</button>
                    <div data-rowmenu-pop hidden>
                      <div class="row-menu__pop">
                        <a class="row-menu__item" href="/staff/receptionist/reschedule?appt=APT-1040&patient=K.A.%20Inuka%20Asith"><?= icon('calendar', 14) ?>Reschedule</a>
                        <button class="row-menu__item row-menu__item--danger" type="button" data-q-cancel data-patient="K.A. Inuka Asith"><?= icon('close', 14) ?>Cancel</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="qcol-checkin"><a class="btn btn--secondary btn--xs" href="/staff/receptionist/check-in">Check in</a></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1204">
              <td class="appt-qnum"><span class="appt-qnum__badge">#4</span></td>
              <td class="table-time">09:50</td>
              <td>
                <div class="table-patient"><strong>Nimsith Wickrama</strong><span>PT-1204</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1041</span><span>New</span></span></td>
              <td><span class="badge badge--success">Checked in</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                  <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                  <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                </div>
              </td>
              <td class="qcol-checkin"></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="1" data-patient="PT-1315">
              <td class="appt-qnum"><span class="appt-qnum__badge">#5</span></td>
              <td class="table-time">10:00</td>
              <td>
                <div class="table-patient"><strong>Sandanu Dulmeth</strong><span>PT-1315</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1042</span><span>New</span></span></td>
              <td><span class="badge badge--muted">Not arrived</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Move up" data-q-up>↑</button>
                  <button class="icon-act" type="button" aria-label="Move down" data-q-down>↓</button>
                  <button class="link-act--muted" type="button" data-q-skip>Skip</button>
                  <button class="icon-act icon-act--flag" type="button" aria-label="Flag">⚑</button>
                  <div class="row-menu" data-rowmenu>
                    <button class="row-menu__toggle" type="button" data-rowmenu-toggle aria-haspopup="true" aria-expanded="false" aria-label="More actions">⋯</button>
                    <div data-rowmenu-pop hidden>
                      <div class="row-menu__pop">
                        <a class="row-menu__item" href="/staff/receptionist/reschedule?appt=APT-1042&patient=Sandanu%20Dulmeth"><?= icon('calendar', 14) ?>Reschedule</a>
                        <button class="row-menu__item row-menu__item--danger" type="button" data-q-cancel data-patient="Sandanu Dulmeth"><?= icon('close', 14) ?>Cancel</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="qcol-checkin"><a class="btn btn--secondary btn--xs" href="/staff/receptionist/check-in">Check in</a></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="0">
              <td class="appt-qnum"></td>
              <td class="table-time">10:15</td>
              <td>
                <div class="table-patient"><strong>Walk-in token W-04</strong><span>Issued 09:41, fits after 10:00</span></div>
              </td>
              <td>Walk-in</td>
              <td><span class="badge badge--warning">Walk-in</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                </div>
              </td>
              <td class="qcol-checkin"></td>
            </tr>
            <tr class="data-table__row" data-queue-row data-inqueue="0">
              <td class="appt-qnum"></td>
              <td class="table-time">10:30</td>
              <td>
                <div class="table-patient"><strong>M. L. Omindu Gunathilaka</strong><span>PT-0871</span></div>
              </td>
              <td><span class="inline-parts"><span>APT-1043</span><span>Follow-up</span></span></td>
              <td><span class="badge badge--danger">Cancelled, refund queued</span></td>
              <td class="data-table__actions">
                <div class="row-actions row-actions--on">
                  <button class="icon-act" type="button" aria-label="Flag">⚑</button>
                </div>
              </td>
              <td class="qcol-checkin"></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div id="week-skipped" data-skip-board hidden>
        <div class="skip-board">
          <div class="skip-board__head">
            <span class="staff-eyebrow m-0">Skipped patients</span>
            <span class="skip-board__hint">they go back in after #3</span>
          </div>
          <div class="skip-board__list" data-skip-list>
            <div data-skip-chip="PT-1088" hidden>
              <div class="skip-chip">
                <div class="skip-chip__who"><strong>Nimsith Wickrama</strong><span>PT-1088</span></div>
                <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
              </div>
            </div>
            <div data-skip-chip="PT-1121" hidden>
              <div class="skip-chip">
                <div class="skip-chip__who"><strong>M. L. Omindu Gunathilaka</strong><span>PT-1121</span></div>
                <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
              </div>
            </div>
            <div data-skip-chip="PT-0967" hidden>
              <div class="skip-chip">
                <div class="skip-chip__who"><strong>K.A. Inuka Asith</strong><span>PT-0967</span></div>
                <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
              </div>
            </div>
            <div data-skip-chip="PT-1204" hidden>
              <div class="skip-chip">
                <div class="skip-chip__who"><strong>Nimsith Wickrama</strong><span>PT-1204</span></div>
                <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
              </div>
            </div>
            <div data-skip-chip="PT-1315" hidden>
              <div class="skip-chip">
                <div class="skip-chip__who"><strong>Sandanu Dulmeth</strong><span>PT-1315</span></div>
                <button class="btn btn--secondary btn--xs" type="button" data-q-reinsert>Reinsert…</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section data-view-panel="month" hidden>
  <div class="month-doctors">
    <label class="doctor-select"><?= icon('profile', 14) ?>
      <select aria-label="Show doctor">
        <option value="all">All doctors</option>
        <option value="AS">Dr. Sample Doctor 1</option>
        <option value="RF">Dr. Sample Doctor 3</option>
        <option value="MP">Dr. Sample Doctor 2</option>
      </select>
    </label>
  </div>

  <div class="month-view">
    <div class="card">
      <div class="card__body">
        <div class="manager-calendar__head">
          <div class="manager-calendar__nav"><button type="button">‹</button><span>July 2026</span><button type="button">›</button></div>
        </div>
        <div class="manager-calendar__weekdays">
          <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span>
        </div>
        <div class="manager-calendar__grid">
          <div class="manager-calendar__cell manager-calendar__cell--empty"></div>
          <div class="manager-calendar__cell manager-calendar__cell--empty"></div>
          <div class="manager-calendar__cell" data-mday="1"><span class="manager-calendar__daynum">1</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="2"><span class="manager-calendar__daynum">2</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="3"><span class="manager-calendar__daynum">3</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">4</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="5"><span class="manager-calendar__daynum">5</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="6"><span class="manager-calendar__daynum">6</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="7"><span class="manager-calendar__daynum">7</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="8"><span class="manager-calendar__daynum">8</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell" data-mday="9"><span class="manager-calendar__daynum">9</span><span class="manager-calendar__daynote">done</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--today" data-mday="10"><span class="manager-calendar__daynum">10</span><span class="manager-calendar__daynote">46 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">11</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="12"><span class="manager-calendar__daynum">12</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="13"><span class="manager-calendar__daynum">13</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="14"><span class="manager-calendar__daynum">14</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--leave" data-mday="15"><span class="manager-calendar__daynum">15</span><span class="manager-calendar__daynote">Doctor 1 on leave</span></div>
          <div class="manager-calendar__cell" data-mday="16"><span class="manager-calendar__daynum">16</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="17"><span class="manager-calendar__daynum">17</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">18</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="19"><span class="manager-calendar__daynum">19</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="20"><span class="manager-calendar__daynum">20</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="21"><span class="manager-calendar__daynum">21</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="22"><span class="manager-calendar__daynum">22</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="23"><span class="manager-calendar__daynum">23</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell is-selected" data-mday="24"><span class="manager-calendar__daynum">24</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell manager-calendar__cell--closed"><span class="manager-calendar__daynum">25</span><span class="manager-calendar__daynote">closed</span></div>
          <div class="manager-calendar__cell" data-mday="26"><span class="manager-calendar__daynum">26</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="27"><span class="manager-calendar__daynum">27</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="28"><span class="manager-calendar__daynum">28</span><span class="manager-calendar__daynote">38 booked</span></div>
          <div class="manager-calendar__cell" data-mday="29"><span class="manager-calendar__daynum">29</span><span class="manager-calendar__daynote">24 booked</span></div>
          <div class="manager-calendar__cell" data-mday="30"><span class="manager-calendar__daynum">30</span><span class="manager-calendar__daynote">52 booked</span></div>
          <div class="manager-calendar__cell" data-mday="31"><span class="manager-calendar__daynum">31</span><span class="manager-calendar__daynote">24 booked</span></div>
        </div>
        <div class="manager-calendar__legend">
          <span><i style="background:var(--primary)"></i>selected / today</span>
          <span><i style="background:var(--warning-tint);border:1px solid var(--warning)"></i>doctor on leave</span>
          <span><i style="background:var(--surface);border:1px solid var(--border)"></i>booked count per day</span>
        </div>
      </div>
    </div>

    <aside class="card">
      <div class="card__body">
        <div class="staff-eyebrow staff-eyebrow--row">
          <span data-mday-title>Fri 24 Jul (52 booked)</span>
          <span class="badge badge--primary">selected day</span>
        </div>
        <div class="month-day-eyebrow">Pick a doctor</div>
        <label class="doctor-select month-day-select"><?= icon('profile', 14) ?>
          <select data-mday-docs aria-label="Pick a doctor">
            <option value="AS" data-name="Dr. Sample Doctor 1" selected>Dr. Sample Doctor 1 (22/28 booked)</option>
            <option value="RF" data-name="Dr. Sample Doctor 3">Dr. Sample Doctor 3 (9/16 booked)</option>
            <option value="MP" data-name="Dr. Sample Doctor 2">Dr. Sample Doctor 2 (12/16 booked)</option>
          </select>
        </label>

        <div class="month-day-eyebrow staff-eyebrow--row mt-7">
          <span data-mday-doc-label>Dr. Sample Doctor 1 on Fri 24 Jul</span>
          <a href="/staff/receptionist/appointments?view=day&doc=AS" data-mday-fullday>full day view →</a>
        </div>
        <div class="month-day-slots">
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:00</span>
            <span class="day-slot__label inline-parts"><span>Nimsith Wickrama</span><span>FU</span></span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">09:30</span>
            <span class="day-slot__label inline-parts"><span>M. L. Omindu Gunathilaka</span><span>New</span></span>
          </div>
          <div class="day-slot day-slot--booked">
            <span class="day-slot__time">10:00</span>
            <span class="day-slot__label inline-parts"><span>Sandanu D.</span><span>New</span></span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">10:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--walkin">
            <span class="day-slot__time">11:00</span>
            <span class="day-slot__label">Walk-in token W-07</span>
          </div>
          <div class="day-slot day-slot--available">
            <span class="day-slot__time">11:30</span>
            <span class="day-slot__label">Available</span>
          </div>
          <div class="day-slot day-slot--blocked">
            <span class="day-slot__time">12:00</span>
            <span class="day-slot__label">Lunch break</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</section>
<div id="reinsert-pop" hidden>
  <div class="reinsert-pop" role="dialog" aria-label="Reinsert into queue">
    <div class="reinsert-pop__title" data-reinsert-title>Reinsert patient</div>
    <label class="reinsert-pop__field">
      <span>Insert after position</span>
      <input type="number" min="1" value="3" data-reinsert-pos aria-label="Insert after position">
    </label>
    <div class="reinsert-pop__hint">They go in after #3 unless you pick another place.</div>
    <div class="reinsert-pop__actions">
      <button class="btn btn--secondary btn--xs" type="button" data-reinsert-cancel>Cancel</button>
      <button class="btn btn--primary btn--xs" type="button" data-reinsert-confirm>Reinsert</button>
    </div>
  </div>
</div>
<script src="/assets/js/receptionist/appointments-calendar.js" defer></script>
<script src="/assets/js/receptionist/queue.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>