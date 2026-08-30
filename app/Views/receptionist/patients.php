<?php

declare(strict_types=1);

$title = 'Patients';
$active = 'patients';
$extraCss = ['patient'];

require __DIR__ . '/header.php';
?>

<div class="patient-find-modeswitch cal-viewtabs">
  <button class="cal-viewtabs__item is-active" type="button" data-pt-mode="patients">Patients</button>
  <button class="cal-viewtabs__item" type="button" data-pt-mode="all-appts">All appointments</button>
</div>

<section data-pt-panel="search">
  <div class="patient-find-header">
    <a class="btn btn--primary" href="/staff/receptionist/patient-register"><?= icon('plus', 14) ?>Register new patient</a>
  </div>

  <div class="patient-find">
    <?php if ($success): ?><p class="form-flash form-flash--success"><?= icon('check', 14) ?> <?= e($success) ?></p><?php endif; ?>

    <h2 class="patient-find__title">Find a patient</h2>

    <div class="patient-find__box">
      <form class="search-box patient-find__searchbox" method="get" action="/staff/receptionist/patients">
        <?= icon('search', 16, 'search-box__icon') ?>
        <input class="search-box__input" type="search" name="q" id="patient-find-search" value="<?= e($search) ?>" placeholder="Search name, NIC, phone or Patient ID…" autocomplete="off" aria-label="Find a patient">
        <button class="btn btn--secondary btn--sm" type="submit">Search</button>
      </form>

      <div class="patient-find__results" id="patient-find-results">
        <?php if ($search !== ''): ?>
          <div class="staff-eyebrow"><?= count($patients) ?> match<?= count($patients) === 1 ? '' : 'es' ?> for “<?= e($search) ?>”</div>
        <?php endif; ?>
        <?php foreach ($patients as $patient): ?>
          <div data-name="<?= e(strtolower($patient['full_name'] . ' ' . $patient['patient_code'] . ' ' . $patient['nic'] . ' ' . $patient['mobile'])) ?>">
            <div class="patient-find-res">
              <?php if ($patient['photo_uri']): ?>
                <img class="patient-find-res__avatar patient-find-res__avatar--photo" src="<?= e($patient['photo_uri']) ?>" alt="">
              <?php else: ?>
                <span class="patient-find-res__avatar"><?= e(initials($patient['full_name'])) ?></span>
              <?php endif; ?>
              <div class="patient-find-res__body">
                <div class="patient-find-res__name"><?= e($patient['full_name']) ?></div>
                <div class="patient-find-res__meta inline-parts">
                  <span><?= e($patient['patient_code']) ?></span>
                  <span>NIC <?= e($patient['nic']) ?></span>
                  <?php if ($patient['age'] !== null): ?><span><?= e($patient['age']) ?> years</span><?php endif; ?>
                  <?php if ($patient['gender']): ?><span><?= $patient['gender'] === 'female' ? 'Female' : 'Male' ?></span><?php endif; ?>
                </div>
              </div>
              <div class="patient-find-res__actions">
                <a class="link-act" href="/staff/receptionist/patient/<?= e($patient['patient_id']) ?>">Open</a>
                <a class="link-act--muted" href="/staff/receptionist/patient-edit/<?= e($patient['patient_id']) ?>"><?= icon('edit', 13) ?> Edit</a>
                <a class="btn btn--primary btn--xs" href="/staff/receptionist/check-in">Check in</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (!$patients): ?>
          <p class="field__desc">No patients found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section data-pt-panel="all-appts" hidden>
  <?php
  $allAppointments = [
    ['id' => 'APT-1041', 'patient' => 'K.A. Inuka Asith', 'code' => 'PT-0967', 'doc' => 'AS', 'doctor' => 'Dr. Sample Doctor 1', 'date' => '2026-07-24', 'day' => '24 Jul 2026', 'time' => '09:45', 'type' => 'New', 'status' => 'Checked in', 'badge' => 'success'],
    ['id' => 'APT-0902', 'patient' => 'K.A. Inuka Asith', 'code' => 'PT-0967', 'doc' => 'AS', 'doctor' => 'Dr. Sample Doctor 1', 'date' => '2026-05-12', 'day' => '12 May 2026', 'time' => '10:30', 'type' => 'Follow-up', 'status' => 'Completed', 'badge' => 'muted'],
    ['id' => 'APT-0788', 'patient' => 'K.A. Inuka Asith', 'code' => 'PT-0967', 'doc' => 'MP', 'doctor' => 'Dr. Sample Doctor 2', 'date' => '2026-03-02', 'day' => '02 Mar 2026', 'time' => '11:00', 'type' => 'New', 'status' => 'Completed', 'badge' => 'muted'],
    ['id' => 'APT-1038', 'patient' => 'Nimsith Wickrama', 'code' => 'PT-1088', 'doc' => 'AS', 'doctor' => 'Dr. Sample Doctor 1', 'date' => '2026-07-24', 'day' => '24 Jul 2026', 'time' => '09:15', 'type' => 'Follow-up', 'status' => 'Checked in', 'badge' => 'success'],
    ['id' => 'APT-1043', 'patient' => 'K. Ashan Charuka', 'code' => 'PT-1355', 'doc' => 'MP', 'doctor' => 'Dr. Sample Doctor 2', 'date' => '2026-07-24', 'day' => '24 Jul 2026', 'time' => '10:10', 'type' => 'New', 'status' => 'Not arrived', 'badge' => 'muted'],
    ['id' => 'APT-1040', 'patient' => 'G. G. Mithun Majika', 'code' => 'PT-0844', 'doc' => 'RF', 'doctor' => 'Dr. Sample Doctor 3', 'date' => '2026-07-24', 'day' => '24 Jul 2026', 'time' => '09:40', 'type' => 'Follow-up', 'status' => 'Checked in', 'badge' => 'success'],
  ];
  ?>
  <div class="search-box all-appointments__search">
    <?= icon('search', 16, 'search-box__icon') ?>
    <input class="search-box__input" type="search" id="aa-search" placeholder="Search APT-1041, PT-0967 or patient name…" autocomplete="off" aria-label="Search appointments">
  </div>

  <div class="all-appointments-controls">
    <label class="doctor-select"><?= icon('calendar', 14) ?>
      <input type="date" id="aa-date" aria-label="Filter by date">
    </label>
    <label class="doctor-select"><?= icon('profile', 14) ?>
      <select id="aa-doc" aria-label="Filter by doctor">
        <option value="all">All doctors</option>
        <option value="AS">Dr. Sample Doctor 1</option>
        <option value="RF">Dr. Sample Doctor 3</option>
        <option value="MP">Dr. Sample Doctor 2</option>
      </select>
    </label>
    <label class="doctor-select">
      <select id="aa-sort" aria-label="Sort">
        <option value="appt">Sort by appointment ID</option>
        <option value="date">Sort by date</option>
        <option value="patient">Sort by patient</option>
      </select>
    </label>
  </div>

  <div class="card">
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Appointment</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Doctor</th>
            <th>Type</th>
            <th>Status</th>
            <th class="data-table__actions"></th>
          </tr>
        </thead>
        <tbody id="aa-results">
          <?php foreach ($allAppointments as $appointment): ?>
            <tr class="data-table__row" data-appt-row
              data-hay="<?= e(strtolower($appointment['id'] . ' ' . $appointment['patient'] . ' ' . $appointment['code'])) ?>"
              data-apptid="<?= e($appointment['id']) ?>" data-doc="<?= e($appointment['doc']) ?>"
              data-date="<?= e($appointment['date']) ?>" data-patient="<?= e($appointment['patient']) ?>">
              <td class="table-code"><?= e($appointment['id']) ?></td>
              <td>
                <div class="table-patient"><strong><?= e($appointment['patient']) ?></strong><span><?= e($appointment['code']) ?></span></div>
              </td>
              <td><?= e($appointment['day']) ?></td>
              <td class="table-time"><?= e($appointment['time']) ?></td>
              <td><?= e($appointment['doctor']) ?></td>
              <td><?= e($appointment['type']) ?></td>
              <td><span class="badge badge--<?= e($appointment['badge']) ?>"><?= e($appointment['status']) ?></span></td>
              <td class="data-table__actions"><button class="link-act" type="button" data-pt-tab="appt-<?= e($appointment['id']) ?>">Open</button></td>
            </tr>
          <?php endforeach; ?>
          <tr id="aa-empty" hidden>
            <td class="all-appointments__empty" colspan="8">No matching appointments.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section data-pt-panel="appt-APT-1041" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-1041</button>
    <span class="patient-find-subhead__hint">K.A. Inuka Asith (PT-0967)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-1041 on 24 Jul 2026 at 09:45</span>
            <span class="badge badge--success">Checked in</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D1</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General</span></div>
              <div class="med-row__sub inline-parts"><span>K.A. Inuka Asith</span><span>PT-0967</span><span>New</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Sore throat and mild fever for 3 days.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-1041</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">24 Jul 2026 at 09:45</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 1</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Pay at counter</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">None yet</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">None yet</span></div>
            <hr class="book-summary__hr">
            <div class="book-summary__note">Checked in. It can’t be moved or cancelled now.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section data-pt-panel="appt-APT-0902" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-0902</button>
    <span class="patient-find-subhead__hint">K.A. Inuka Asith (PT-0967)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-0902 on 12 May 2026 at 10:30</span>
            <span class="badge badge--muted">Completed</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D1</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General</span></div>
              <div class="med-row__sub inline-parts"><span>K.A. Inuka Asith</span><span>PT-0967</span><span>Follow-up</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>URI review, cough still there.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-0902</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">12 May 2026 at 10:30</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 1</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid online</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0181</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">RX-0902</span></div>
            <hr class="book-summary__hr">
            <div class="book-summary__note">Finished. It can’t be moved or cancelled now.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section data-pt-panel="appt-APT-0788" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-0788</button>
    <span class="patient-find-subhead__hint">K.A. Inuka Asith (PT-0967)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-0788 on 02 Mar 2026 at 11:00</span>
            <span class="badge badge--muted">Completed</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D2</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 2</span><span>ENT</span></div>
              <div class="med-row__sub inline-parts"><span>K.A. Inuka Asith</span><span>PT-0967</span><span>New</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Ear pain, right side.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-0788</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">02 Mar 2026 at 11:00</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 2</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,000</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid in cash</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0090</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">RX-0788</span></div>
            <hr class="book-summary__hr">
            <div class="book-summary__note">Finished. It can’t be moved or cancelled now.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section data-pt-panel="appt-APT-1038" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-1038</button>
    <span class="patient-find-subhead__hint">Nimsith Wickrama (PT-1088)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-1038 on 24 Jul 2026 at 09:15</span>
            <span class="badge badge--success">Checked in</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D1</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 1</span><span>General</span></div>
              <div class="med-row__sub inline-parts"><span>Nimsith Wickrama</span><span>PT-1088</span><span>Follow-up</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Blood-pressure review.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-1038</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">24 Jul 2026 at 09:15</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 1</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,500</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid online</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0231</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">None yet</span></div>
            <hr class="book-summary__hr">
            <div class="book-summary__note">Checked in. It can’t be moved or cancelled now.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section data-pt-panel="appt-APT-1043" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-1043</button>
    <span class="patient-find-subhead__hint">K. Ashan Charuka (PT-1355)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-1043 on 24 Jul 2026 at 10:10</span>
            <span class="badge badge--muted">Not arrived</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D2</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 2</span><span>ENT</span></div>
              <div class="med-row__sub inline-parts"><span>K. Ashan Charuka</span><span>PT-1355</span><span>New</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Sinus congestion.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-1043</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">24 Jul 2026 at 10:10</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 2</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 2,000</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid online</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">None yet</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">None yet</span></div>
            <hr class="book-summary__hr">
            <a class="btn btn--primary btn--block" href="/staff/receptionist/reschedule?appt=APT-1043&patient=K.%20Ashan%20Charuka&doc=MP">Reschedule</a>
            <button class="btn btn--soft-danger btn--block mt-4" type="button">Cancel appointment</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section data-pt-panel="appt-APT-1040" hidden>
  <div class="patient-find-subhead">
    <button class="book-back" type="button" data-pt-tab="all-appts"><?= icon('chevronLeft', 15) ?>APT-1040</button>
    <span class="patient-find-subhead__hint">G. G. Mithun Majika (PT-0844)</span>
  </div>

  <div class="record-detail-grid" style="max-width:860px">
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="record-detail-head mb-6">
            <span class="record-detail-title">APT-1040 on 24 Jul 2026 at 09:40</span>
            <span class="badge badge--success">Checked in</span>
          </div>
          <div class="record-detail-doctor">
            <span class="book-doc-head__avatar">D3</span>
            <div>
              <div class="text-title inline-parts"><span>Dr. Sample Doctor 3</span><span>Pediatrics</span></div>
              <div class="med-row__sub inline-parts"><span>G. G. Mithun Majika</span><span>PT-0844</span><span>Follow-up</span></div>
            </div>
          </div>
          <div class="record-detail-block__label mt-6">Reason for visit</div>
          <p>Child vaccination follow-up.</p>
        </div>
      </div>
    </div>
    <div class="stack">
      <div class="card">
        <div class="card__body">
          <div class="book-summary">
            <div class="book-summary__eyebrow">Appointment</div>
            <div class="book-summary__row"><span>Appointment ID</span><span class="val mono">APT-1040</span></div>
            <div class="book-summary__row"><span>Date &amp; time</span><span class="val">24 Jul 2026 at 09:40</span></div>
            <div class="book-summary__row"><span>Doctor</span><span class="val">Dr. Sample Doctor 3</span></div>
            <div class="book-summary__row"><span>Consultation fee</span><span class="val">Rs. 3,000</span></div>
            <div class="book-summary__row"><span>Payment</span><span class="val">Paid online</span></div>
            <div class="book-summary__row"><span>Invoice</span><span class="val">INV-0230</span></div>
            <div class="book-summary__row"><span>Prescription</span><span class="val">None yet</span></div>
            <hr class="book-summary__hr">
            <div class="book-summary__note">Checked in. It can’t be moved or cancelled now.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="/assets/js/receptionist/patients.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>