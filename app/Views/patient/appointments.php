<?php

declare(strict_types=1);

$title = 'My appointments';
$active = 'appointments';

$queueLive = true;

require __DIR__ . '/header.php';
?>
<section id="appts-list">
  <div class="row-between mb-8">
    <h1 class="rx-title">My appointments</h1>
    <a class="btn btn--primary" href="/app/book">Book a new visit</a>
  </div>

  <div class="section-lead">
    <h2 class="section-lead__title">Today</h2>
  </div>

  <article class="visit-today" data-appt-open="APT-1043">
    <div class="visit-today__ring">
      <?php if ($queueLive): ?>
        <span class="visit-today__ringnum">#4</span>
        <span class="visit-today__ringlabel">your number</span>
      <?php else: ?>
        <span class="visit-today__ringnum visit-today__ringnum--time">10:30</span>
        <span class="visit-today__ringlabel">your slot</span>
      <?php endif; ?>
    </div>

    <div class="visit-today__body">
      <div class="visit-today__toprow">
        <h3 class="visit-today__doctor">Dr. Sample Doctor 1</h3>
        <span class="visit-today__badge"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="12" height="12">Checked in</span>
      </div>
      <p class="visit-today__meta inline-parts"><span>Follow-up review</span><span>General Physician</span></p>

      <div class="visit-today__stats">
        <?php if ($queueLive): ?>
          <div class="visit-today__stat">
            <strong>~10:10</strong>
            <span>expected</span>
          </div>
          <div class="visit-today__stat">
            <strong>3</strong>
            <span>people ahead</span>
          </div>
        <?php else: ?>
          <div class="visit-today__stat">
            <strong>10:00</strong>
            <span>queue opens at</span>
          </div>
          <div class="visit-today__stat">
            <strong>-</strong>
            <span>no queue running yet</span>
          </div>
        <?php endif; ?>
        <div class="visit-today__stat">
          <strong>09:55</strong>
          <span>arrive at clinic</span>
        </div>
      </div>
    </div>

    <div class="visit-today__actions" data-appt-action>
      <?php if ($queueLive): ?>
        <a class="visit-today__btn visit-today__btn--solid" href="/app/live-queue">Follow the live queue</a>
      <?php else: ?>
        <button class="visit-today__btn visit-today__btn--solid" type="button" data-appt-open-detail="APT-1043">Appointment details</button>
      <?php endif; ?>
      <button class="visit-today__btn visit-today__btn--ghost" type="button" data-appt-reschedule>Reschedule</button>
    </div>
  </article>

  <div class="section-lead">
    <h2 class="section-lead__title">Coming up</h2>
  </div>

  <div class="visit-cards">
    <article class="visit-card visit-card--upcoming" data-appt-open="APT-1043">
      <div class="visit-card__date">
        <span class="visit-card__day">24</span>
        <span class="visit-card__month">Jul</span>
      </div>
      <div class="visit-card__body">
        <h3 class="visit-card__title">Dr. Sample Doctor 1</h3>
        <p class="visit-card__sub">Follow-up review </p>
        <p class="visit-card__note">10:30</p>
      </div>
      <div class="visit-card__actions" data-appt-action>
        <button class="visit-card__btn" type="button" data-appt-reschedule>Reschedule</button>
        <button class="visit-card__btn visit-card__btn--quiet" type="button">Cancel</button>
      </div>
    </article>
  </div>

  <div class="section-lead">
    <h2 class="section-lead__title">Visits you've had</h2>
  </div>

  <div class="visit-cards visit-cards--past">
    <article class="visit-card" data-appt-open="APT-0955">
      <div class="visit-card__date visit-card__date--past">
        <span class="visit-card__day">12</span>
        <span class="visit-card__month">May 26</span>
      </div>
      <div class="visit-card__body">
        <h3 class="visit-card__title">Hypertension review</h3>
        <p class="visit-card__sub inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></p>
      </div>
      <span class="visit-card__chev"><img class="icon" src="/assets/img/icons/chevronRight.svg" alt="" width="18" height="18"></span>
    </article>

    <article class="visit-card" data-appt-open="APT-0810">
      <div class="visit-card__date visit-card__date--past">
        <span class="visit-card__day">03</span>
        <span class="visit-card__month">Feb 26</span>
      </div>
      <div class="visit-card__body">
        <h3 class="visit-card__title">Gastritis</h3>
        <p class="visit-card__sub inline-parts"><span>Dr. Sample Doctor 2</span><span>ENT Surgeon</span></p>
      </div>
      <span class="visit-card__chev"><img class="icon" src="/assets/img/icons/chevronRight.svg" alt="" width="18" height="18"></span>
    </article>

    <article class="visit-card" data-appt-open="APT-0699">
      <div class="visit-card__date visit-card__date--past">
        <span class="visit-card__day">18</span>
        <span class="visit-card__month">Nov 25</span>
      </div>
      <div class="visit-card__body">
        <h3 class="visit-card__title">Viral fever</h3>
        <p class="visit-card__sub inline-parts"><span>Dr. Sample Doctor 3</span><span>Pediatrics</span></p>
      </div>
      <span class="visit-card__chev"><img class="icon" src="/assets/img/icons/chevronRight.svg" alt="" width="18" height="18"></span>
    </article>
  </div>
</section>

<section class="appt-detail" data-appt-detail="APT-1043" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-appt-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Upcoming on 24 Jul 2026 at 10:30</span>
    <span class="badge badge--info">Confirmed</span>
  </div>
  <div class="record-detail-grid">
    <div class="stack">
      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Live queue</h3>
          <?php if ($queueLive): ?>
            <span class="live-badge"><span class="live-badge__dot"></span>LIVE</span>
          <?php else: ?>
            <span class="badge badge--muted">Not started</span>
          <?php endif; ?>
        </div>
        <div class="card__body">
          <?php if ($queueLive): ?>
            <div class="appt-queue-lead">
              <div class="appt-queue-lead__me">
                <span class="appt-queue-lead__num">#4</span>
                <span class="appt-queue-lead__label">your number</span>
              </div>
              <dl class="appt-queue-lead__facts">
                <div class="appt-queue-lead__fact">
                  <dt>Now being seen</dt>
                  <dd class="inline-parts"><span>#1</span><span>N. J•••••</span></dd>
                </div>
                <div class="appt-queue-lead__fact">
                  <dt>People ahead</dt>
                  <dd>3</dd>
                </div>
                <div class="appt-queue-lead__fact">
                  <dt>Estimated time</dt>
                  <dd>~10:10</dd>
                </div>
              </dl>
            </div>

            <div class="queue-stages">
              <div class="queue-stage queue-stage--done">
                <span class="queue-stage__dot"><img class="icon" src="/assets/img/icons/check.svg" alt="" width="13" height="13"></span>
                <span class="queue-stage__label">Checked in</span>
                <span class="queue-stage__note">at reception</span>
              </div>
              <div class="queue-stage queue-stage--current">
                <span class="queue-stage__dot"></span>
                <span class="queue-stage__label">Vitals</span>
                <span class="queue-stage__note">nurse takes BP</span>
              </div>
              <div class="queue-stage queue-stage--pending">
                <span class="queue-stage__dot"></span>
                <span class="queue-stage__label">You're next</span>
                <span class="queue-stage__note">wait by the door</span>
              </div>
              <div class="queue-stage queue-stage--pending">
                <span class="queue-stage__dot"></span>
                <span class="queue-stage__label">With the doctor</span>
                <span class="queue-stage__note">in the room</span>
              </div>
            </div>

            <div class="appt-queue-list">
              <div class="queue-row">
                <span class="queue-row__position">1</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">N. J•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('in_consultation')) ?>"><?= e(status_label('in_consultation')) ?></span>
                <span class="queue-row__eta">now</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">2</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">K. P•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('ready')) ?>"><?= e(status_label('ready')) ?></span>
                <span class="queue-row__eta">~09:50</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">3</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">R. D•••••</span>
                  <span class="queue-row__note">vitals done</span>
                </div>
                <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
                <span class="queue-row__eta">~10:00</span>
              </div>
              <div class="queue-row queue-row--me">
                <span class="queue-row__position">4</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">You</span>
                </div>
                <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
                <span class="queue-row__eta">~10:10</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">5</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">S. B•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('checked_in')) ?>"><?= e(status_label('checked_in')) ?></span>
                <span class="queue-row__eta">~10:22</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">6</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">M. F•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
                <span class="queue-row__eta">~10:34</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">7</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">T. W•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
                <span class="queue-row__eta">~10:46</span>
              </div>
              <div class="queue-row">
                <span class="queue-row__position">8</span>
                <div class="queue-row__identity">
                  <span class="queue-row__name">D. R•••••</span>
                </div>
                <span class="badge badge--<?= e(status_tone('not_arrived')) ?>"><?= e(status_label('not_arrived')) ?></span>
                <span class="queue-row__eta">~10:58</span>
              </div>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <span class="empty-state__icon"><img class="icon" src="/assets/img/icons/clock.svg" alt="" width="28" height="28"></span>
              <p class="empty-state__message">Dr. Sample Doctor 1's session starts at 10:00. Your queue number will show here then.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card__body">
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D1</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></div>
              <div class="med-row__sub">Follow-up review</div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Follow-up for a chest infection. The cough is mostly gone, but a mild sore throat remains.</p>
          <div class="record-detail-diag mt-5">
            <img class="icon" src="/assets/img/icons/clock.svg" alt="" width="14" height="14"> Please arrive by <b>09:55</b>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__head">
          <h3 class="card__title">Files for this visit</h3>
        </div>
        <div class="card__body">
          <div class="record-detail-file">
            <span class="record-detail-file__icon"><img class="icon" src="/assets/img/icons/file.svg" alt="" width="18" height="18"></span>
            <div class="record-detail-file__body">
              <div class="record-detail-file__name">FBC report.pdf</div>
              <div class="record-detail-file__meta">Added today</div>
            </div>
            <button class="record-detail-file__view" type="button">View</button>
          </div>
          <div class="record-detail-file">
            <span class="record-detail-file__icon"><img class="icon" src="/assets/img/icons/file.svg" alt="" width="18" height="18"></span>
            <div class="record-detail-file__body">
              <div class="record-detail-file__name">Chest X-ray.jpg</div>
              <div class="record-detail-file__meta">Added yesterday</div>
            </div>
            <button class="record-detail-file__view" type="button">View</button>
          </div>
        </div>
      </div>
    </div>

    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">24 Jul 2026 at 10:30</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid online</span></div>
            <div class="book-summary__row"><span>Session</span><span class="val"><?= $queueLive ? 'Running now' : 'Starts 10:00' ?></span></div>
            <hr class="book-summary__hr">
            <?php if ($queueLive): ?>
              <a class="btn btn--primary btn--block" href="/app/live-queue">Open the live queue</a>
              <button class="btn btn--secondary btn--block mt-4" type="button" data-appt-reschedule>Reschedule</button>
            <?php else: ?>
              <button class="btn btn--primary btn--block" type="button" data-appt-reschedule>Reschedule</button>
            <?php endif; ?>
            <button class="btn btn--soft-danger btn--block mt-4" type="button">Cancel appointment</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="appt-detail" data-appt-detail="APT-0955" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-appt-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Visit on 12 May 2026 at 10:40</span>
    <span class="badge badge--muted">Completed</span>
  </div>
  <div class="record-detail-grid">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">A.</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></div>
              <div class="med-row__sub">12 May 2026</div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Visit summary</div>
          <p>Blood pressure is under control. Keep taking Losartan 50 mg as before.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Visit details</div>
            <div class="book-summary__row"><span>Waited</span><span class="val">14 min</span></div>
            <div class="book-summary__row"><span>Consultation</span><span class="val">16 min</span></div>
            <div class="book-summary__row"><span>Amount paid</span><span class="val">Rs. 4,180</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">RX-0871</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0102</span></div>
            <hr class="book-summary__hr">
            <a class="btn btn--primary btn--block" href="/app/records">Open medical record</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="appt-detail" data-appt-detail="APT-0810" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-appt-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Visit on 03 Feb 2026 at 09:15</span>
    <span class="badge badge--muted">Completed</span>
  </div>
  <div class="record-detail-grid">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">PE</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 2</span><span>ENT Surgeon</span></div>
              <div class="med-row__sub">03 Feb 2026</div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Visit summary</div>
          <p>Advice on diet and a short course of tablets to reduce stomach acid.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Visit details</div>
            <div class="book-summary__row"><span>Waited</span><span class="val">9 min</span></div>
            <div class="book-summary__row"><span>Consultation</span><span class="val">12 min</span></div>
            <div class="book-summary__row"><span>Amount paid</span><span class="val">Rs. 2,000</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">RX-0512</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0034</span></div>
            <hr class="book-summary__hr">
            <a class="btn btn--primary btn--block" href="/app/records">Open medical record</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="appt-detail" data-appt-detail="APT-0699" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-appt-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Visit on 18 Nov 2025 at 14:00</span>
    <span class="badge badge--muted">Completed</span>
  </div>
  <div class="record-detail-grid">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">FE</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 3</span><span>Pediatrics</span></div>
              <div class="med-row__sub">18 Nov 2025</div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Visit summary</div>
          <p>Rest, fluids and paracetamol. Come back if the fever doesn't settle.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Visit details</div>
            <div class="book-summary__row"><span>Waited</span><span class="val">20 min</span></div>
            <div class="book-summary__row"><span>Consultation</span><span class="val">10 min</span></div>
            <div class="book-summary__row"><span>Amount paid</span><span class="val">Rs. 3,000</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">RX-0301</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0912</span></div>
            <hr class="book-summary__hr">
            <a class="btn btn--primary btn--block" href="/app/records">Open medical record</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="appt-detail" data-appt-detail="reschedule" hidden>
  <div class="record-detail-head">
    <button class="record-detail-back" type="button" aria-label="Back" data-appt-back><img class="icon" src="/assets/img/icons/chevronLeft.svg" alt="" width="18" height="18"></button>
    <span class="record-detail-title">Reschedule - Dr. Sample Doctor 1</span>
    <span class="badge badge--info">Pick a new time</span>
  </div>
  <div class="record-detail-grid">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D1</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General Physician</span></div>
              <div class="med-row__sub">Currently 24 Jul at 10:30</div>
            </div>
          </div>

          <div class="record-detail-block__label mt-6">Choose a day</div>
          <div class="reschedule-days" data-presch-days>
            <button class="reschedule-day is-active" type="button" data-day="Mon 27 Jul">Mon 27 Jul</button>
            <button class="reschedule-day" type="button" data-day="Tue 28 Jul">Tue 28 Jul</button>
            <button class="reschedule-day" type="button" data-day="Thu 30 Jul">Thu 30 Jul</button>
            <button class="reschedule-day" type="button" data-day="Fri 31 Jul">Fri 31 Jul</button>
          </div>

          <div class="record-detail-block__label mt-6">Available times</div>
          <div class="reschedule-slots" data-presch-slots>
            <button class="reschedule-slot" type="button" data-slot="09:30">09:30</button>
            <button class="reschedule-slot" type="button" data-slot="10:00">10:00</button>
            <button class="reschedule-slot" type="button" data-slot="10:30">10:30</button>
            <button class="reschedule-slot" type="button" data-slot="11:00">11:00</button>
            <button class="reschedule-slot" type="button" data-slot="11:30">11:30</button>
            <button class="reschedule-slot" type="button" data-slot="12:00">12:00</button>
          </div>
        </div>
      </div>
    </div>

    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">New booking</div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 1</span></div>
            <div class="book-summary__row"><span>Current</span><span class="val">24 Jul at 10:30</span></div>
            <div class="book-summary__row"><span>New time</span><span class="val" data-presch-new>Pick a day &amp; time</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
            <hr class="book-summary__hr">
            <button class="btn btn--primary btn--block" type="button" data-presch-confirm disabled>Confirm reschedule</button>
            <div class="book-summary__note">Your current time stays booked until you confirm. We'll text you the new time.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/assets/js/patient/appointments.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>