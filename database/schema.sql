CREATE DATABASE IF NOT EXISTS meditrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meditrack;
CREATE TABLE role (
    role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(30) NOT NULL UNIQUE,
    description VARCHAR(255) NULL
) ENGINE = InnoDB;
CREATE TABLE staff (
    staff_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    work_email VARCHAR(150) NOT NULL UNIQUE,
    role_id INT UNSIGNED NOT NULL,
    password_hash CHAR(60) NOT NULL,
    must_change_password TINYINT(1) NOT NULL DEFAULT 1,
    status ENUM('active', 'deactivated') NOT NULL DEFAULT 'active',
    phone VARCHAR(20) NULL,
    photo_uri VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_staff_role FOREIGN KEY (role_id) REFERENCES role (role_id)
) ENGINE = InnoDB;
CREATE TABLE patient (
    patient_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_code VARCHAR(20) NOT NULL UNIQUE,
    nic VARCHAR(20) NULL UNIQUE,
    full_name VARCHAR(120) NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female') NULL,
    blood_type VARCHAR(3) NULL,
    mobile VARCHAR(20) NULL UNIQUE,
    email VARCHAR(150) NULL UNIQUE,
    address VARCHAR(255) NULL,
    photo_uri VARCHAR(255) NULL,
    password_hash CHAR(60) NULL,
    must_change_password TINYINT(1) NOT NULL DEFAULT 0,
    pdpa_consent TINYINT(1) NOT NULL DEFAULT 0,
    account_status ENUM('active', 'deactivated', 'deleted') NOT NULL DEFAULT 'active',
    registered_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_patient_registered_by FOREIGN KEY (registered_by) REFERENCES staff (staff_id)
) ENGINE = InnoDB;
CREATE TABLE patient_allergy (
    patient_id BIGINT UNSIGNED NOT NULL,
    allergen_name VARCHAR(100) NOT NULL,
    added_by_role ENUM('doctor', 'patient', 'receptionist') NOT NULL,
    added_by_staff_id BIGINT UNSIGNED NULL,
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (patient_id, allergen_name),
    CONSTRAINT fk_allergy_patient FOREIGN KEY (patient_id) REFERENCES patient (patient_id) ON DELETE CASCADE,
    CONSTRAINT fk_allergy_staff FOREIGN KEY (added_by_staff_id) REFERENCES staff (staff_id)
) ENGINE = InnoDB;
CREATE TABLE staff_trusted_device (
    trusted_device_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    staff_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL,
    CONSTRAINT fk_trusted_device_staff FOREIGN KEY (staff_id) REFERENCES staff (staff_id) ON DELETE CASCADE
) ENGINE = InnoDB;
CREATE TABLE audit_log (
    audit_log_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_type ENUM('staff', 'patient') NOT NULL,
    actor_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_name VARCHAR(50) NULL,
    entity_pk VARCHAR(30) NULL,
    changed_fields VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
CREATE TABLE specialty (
    specialty_id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE
) ENGINE = InnoDB;
CREATE TABLE doctor (
    staff_id BIGINT UNSIGNED PRIMARY KEY,
    slmc_number VARCHAR(20) NOT NULL UNIQUE,
    specialty_id SMALLINT UNSIGNED NOT NULL,
    consultation_fee DECIMAL(10, 2) NOT NULL DEFAULT 2500.00,
    followup_fee DECIMAL(10, 2) NULL,
    slot_length_min SMALLINT UNSIGNED NOT NULL DEFAULT 15,
    overtime_warn_min SMALLINT UNSIGNED NOT NULL DEFAULT 25,
    default_capacity SMALLINT UNSIGNED NOT NULL DEFAULT 28,
    roster_api_enabled TINYINT(1) NOT NULL DEFAULT 0,
    uses_regular_schedule TINYINT(1) NOT NULL DEFAULT 1,
    acd_new_min DECIMAL(5, 2) NULL,
    acd_followup_min DECIMAL(5, 2) NULL,
    acd_sigma_min DECIMAL(5, 2) NULL,
    CONSTRAINT fk_doctor_staff FOREIGN KEY (staff_id) REFERENCES staff (staff_id) ON DELETE CASCADE,
    CONSTRAINT fk_doctor_specialty FOREIGN KEY (specialty_id) REFERENCES specialty (specialty_id)
) ENGINE = InnoDB;
CREATE TABLE doctor_leave (
    doctor_leave_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason VARCHAR(160) NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_leave_doctor FOREIGN KEY (doctor_id) REFERENCES doctor (staff_id) ON DELETE CASCADE,
    CONSTRAINT fk_leave_creator FOREIGN KEY (created_by) REFERENCES staff (staff_id) ON DELETE
    SET NULL,
        INDEX ix_leave_doctor_dates (doctor_id, start_date, end_date)
) ENGINE = InnoDB;
CREATE TABLE doctor_regular_schedule (
    regular_schedule_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT UNSIGNED NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    capacity SMALLINT UNSIGNED NOT NULL,
    note VARCHAR(120) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_regsched_doctor FOREIGN KEY (doctor_id) REFERENCES doctor (staff_id) ON DELETE CASCADE,
    UNIQUE KEY uq_regsched (doctor_id, is_active, day_of_week, start_time)
) ENGINE = InnoDB;
CREATE TABLE doctor_availability (
    availability_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id BIGINT UNSIGNED NOT NULL,
    session_date DATE NOT NULL,
    source VARCHAR(40) NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_avail_doctor FOREIGN KEY (doctor_id) REFERENCES doctor (staff_id) ON DELETE CASCADE,
    UNIQUE KEY uq_avail (doctor_id, session_date),
    INDEX ix_avail_date (session_date)
) ENGINE = InnoDB;
CREATE TABLE availability_slot (
    availability_id BIGINT UNSIGNED NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    capacity SMALLINT UNSIGNED NOT NULL,
    label VARCHAR(60) NULL,
    PRIMARY KEY (availability_id, start_time),
    CONSTRAINT fk_slot_avail FOREIGN KEY (availability_id) REFERENCES doctor_availability (availability_id) ON DELETE CASCADE
) ENGINE = InnoDB;
CREATE TABLE schedule_break (
    availability_id BIGINT UNSIGNED NOT NULL,
    from_time TIME NOT NULL,
    to_time TIME NOT NULL,
    label VARCHAR(60) NULL,
    PRIMARY KEY (availability_id, from_time),
    CONSTRAINT fk_break_avail FOREIGN KEY (availability_id) REFERENCES doctor_availability (availability_id) ON DELETE CASCADE
) ENGINE = InnoDB;
CREATE TABLE approval_request (
    approval_request_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id BIGINT UNSIGNED NOT NULL,
    request_type ENUM('regular_schedule', 'fee_revision') NOT NULL,
    proposed_consultation_fee DECIMAL(10, 2) NULL,
    proposed_followup_fee DECIMAL(10, 2) NULL,
    details VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    decided_by BIGINT UNSIGNED NULL,
    decided_at DATETIME NULL,
    CONSTRAINT fk_approval_doctor FOREIGN KEY (doctor_id) REFERENCES doctor (staff_id) ON DELETE CASCADE,
    CONSTRAINT fk_approval_decider FOREIGN KEY (decided_by) REFERENCES staff (staff_id) ON DELETE
    SET NULL,
        INDEX ix_approval_status (status)
) ENGINE = InnoDB;
CREATE TABLE appointment (
    appointment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_code VARCHAR(20) NOT NULL UNIQUE,
    doctor_id BIGINT UNSIGNED NOT NULL,
    booking_for ENUM('self', 'someone_else', 'guest', 'walk_in') NOT NULL,
    patient_id BIGINT UNSIGNED NULL,
    booking_patient_id BIGINT UNSIGNED NULL,
    booked_by_staff_id BIGINT UNSIGNED NULL,
    subject_full_name VARCHAR(160) NULL,
    subject_nic VARCHAR(15) NULL,
    subject_dob DATE NULL,
    subject_gender ENUM('male', 'female') NULL,
    subject_mobile VARCHAR(20) NULL,
    subject_email VARCHAR(160) NULL,
    appointment_date DATE NOT NULL,
    slot_time TIME NULL,
    visit_type ENUM('new', 'follow_up') NOT NULL DEFAULT 'new',
    booking_channel ENUM('patient_app', 'patient_web', 'guest_web', 'reception', 'walk_in') NOT NULL,
    status ENUM('pending', 'confirmed', 'rescheduled', 'cancelled', 'completed', 'no_show') NOT NULL DEFAULT 'confirmed',
    reason_for_visit VARCHAR(255) NULL,
    fee_amount DECIMAL(10, 2) NOT NULL,
    payment_timing ENUM('online', 'at_counter') NOT NULL,
    no_show_refund_opted TINYINT(1) NOT NULL DEFAULT 0,
    guest_access_token CHAR(36) NULL UNIQUE,
    rescheduled_from_id BIGINT UNSIGNED NULL,
    follow_up_of_consultation_id BIGINT UNSIGNED NULL,
    cancel_reason VARCHAR(255) NULL,
    refund_status ENUM('none', 'queued', 'refunded') NOT NULL DEFAULT 'none',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_appt_doctor FOREIGN KEY (doctor_id) REFERENCES doctor (staff_id),
    CONSTRAINT fk_appt_patient FOREIGN KEY (patient_id) REFERENCES patient (patient_id),
    CONSTRAINT fk_appt_booker FOREIGN KEY (booking_patient_id) REFERENCES patient (patient_id),
    CONSTRAINT fk_appt_staff FOREIGN KEY (booked_by_staff_id) REFERENCES staff (staff_id) ON DELETE
    SET NULL,
        CONSTRAINT fk_appt_rescheduled FOREIGN KEY (rescheduled_from_id) REFERENCES appointment (appointment_id),
        CONSTRAINT ck_appt_subject CHECK (
            patient_id IS NOT NULL
            OR subject_full_name IS NOT NULL
        ),
        INDEX ix_appt_doctor_date (doctor_id, appointment_date, slot_time),
        INDEX ix_appt_patient (patient_id),
        INDEX ix_appt_booking (booking_patient_id),
        INDEX ix_appt_status (status)
) ENGINE = InnoDB;

CREATE TABLE supplier (
    supplier_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL UNIQUE,
    contact VARCHAR(120) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE medicine (
    medicine_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    commercial_name VARCHAR(120) NOT NULL,
    generic_name VARCHAR(120) NOT NULL,
    unit_form ENUM(
        'tablet',
        'capsule',
        'syrup',
        'inhaler',
        'injection',
        'drops',
        'other'
    ) NOT NULL,
    manufacturer VARCHAR(120) NULL,
    storage_limits VARCHAR(120) NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    reorder_threshold INT UNSIGNED NOT NULL DEFAULT 0,
    requires_prescription TINYINT(1) NOT NULL DEFAULT 1,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_medicine UNIQUE (commercial_name, unit_form),
    INDEX ix_medicine_generic (generic_name)
) ENGINE = InnoDB;

CREATE TABLE medicine_batch (
    batch_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    batch_code VARCHAR(30) NOT NULL UNIQUE,
    medicine_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    supplier_invoice_ref VARCHAR(60) NOT NULL,
    quantity_received INT UNSIGNED NOT NULL,
    quantity_on_hand INT UNSIGNED NOT NULL,
    cost_price_total DECIMAL(12, 2) NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('active', 'expired', 'blocked', 'damaged') NOT NULL DEFAULT 'active',
    registered_by BIGINT UNSIGNED NOT NULL,
    received_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_batch_medicine FOREIGN KEY (medicine_id) REFERENCES medicine (medicine_id) ON DELETE CASCADE,
    CONSTRAINT fk_batch_supplier FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id),
    CONSTRAINT fk_batch_staff FOREIGN KEY (registered_by) REFERENCES staff (staff_id),
    INDEX ix_batch_medicine (medicine_id),
    INDEX ix_batch_expiry (expiry_date)
) ENGINE = InnoDB;

CREATE TABLE stock_adjustment (
    stock_adjustment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    batch_id BIGINT UNSIGNED NOT NULL,
    pharmacy_order_id BIGINT UNSIGNED NULL,
    quantity_delta INT NOT NULL,
    reason ENUM(
        'damaged',
        'baseline_intake',
        'audit_correction',
        'order_return'
    ) NOT NULL,
    note VARCHAR(255) NULL,
    adjusted_by BIGINT UNSIGNED NULL,
    adjusted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stockadj_batch FOREIGN KEY (batch_id) REFERENCES medicine_batch (batch_id) ON DELETE CASCADE,
    CONSTRAINT fk_stockadj_staff FOREIGN KEY (adjusted_by) REFERENCES staff (staff_id),
    INDEX ix_stockadj_batch (batch_id)
) ENGINE = InnoDB;

