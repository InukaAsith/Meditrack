    </main>
  </div>
</div>

<div class="modal-backdrop" id="emergency-modal" hidden>
  <div class="modal card card--modal">
    <div class="modal__header card__header">
      <h3 class="modal__title">＋ Emergency Queue Insertion</h3>
      <button class="modal__close" type="button" id="emergency-modal-close" aria-label="Close">✕</button>
    </div>
    <div class="modal__body card__body">
      <p class="text-sm text-muted">Insert a high-priority emergency patient directly to the front of a doctor's live queue.</p>
      <form id="emergency-form" class="form-grid mt-4">
        <div class="form-group">
          <label class="form-label" for="em-patient-name">Patient Name</label>
          <input type="text" id="em-patient-name" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="em-doctor-select">Assign Doctor</label>
          <select id="em-doctor-select" class="form-control" required>
            <option value="dr-silva">Dr. Sample Doctor 1 (Current ACD: 12 min)</option>
            <option value="dr-fernando">Dr. Sample Doctor 3 (Current ACD: 15 min)</option>
            <option value="dr-perera">Dr. Sample Doctor 2 (Current ACD: 18 min)</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label" for="em-triage-notes">Triage / Urgency Notes</label>
          <textarea id="em-triage-notes" class="form-control" rows="2"></textarea>
        </div>
        <div class="form-actions mt-4 flex gap-3 justify-end">
          <button type="button" class="btn btn--secondary" id="emergency-cancel-btn">Cancel</button>
          <button type="submit" class="btn btn--danger">Insert at Front &amp; Pause Normal Queue</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
