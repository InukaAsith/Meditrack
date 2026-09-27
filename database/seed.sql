-- MediTrack - seed data: roles, one working login per role, two patients,
-- three doctors with their schedules, demo appointments, and the pharmacy
-- inventory (suppliers, medicines, batches).
--
-- Run once, after schema.sql:
--   mysql -u root -p < database/schema.sql
--   mysql -u root -p < database/seed.sql
--
-- Every account below already has its own password set and
-- must_change_password = 0, so each one signs straight in - no forced
-- password change. The passwords are listed in database/demo_credentials.md;
-- each hash here is password_hash('<that password>', PASSWORD_BCRYPT).
USE meditrack;
INSERT INTO role (role_name, description)
VALUES ('Patient', 'App-facing patient/guest account'),
    ('Doctor', 'Consults patients, writes prescriptions'),
    ('Receptionist', 'Front-desk booking, check-in, billing'),
    ('Supporting Staff', 'Live queue and vitals, no billing/prescriptions'),
    ('Pharmacist', 'Dispensing and pharmacy inventory'),
    ('Manager', 'Analytics, approvals, financial reports'),
    ('Admin', 'System administration and audit');
-- Staff. EMP-001 to EMP-006 are one sign-in per role; EMP-007 and EMP-008
-- are two more doctors so booking has more than one doctor to pick from.
INSERT INTO staff (
        employee_code,
        full_name,
        work_email,
        role_id,
        password_hash,
        must_change_password,
        phone
    )
VALUES (
        'EMP-001',
        'Omindu Gunathilaka',
        'omindu@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Doctor'),
        '$2y$12$RDDfJMLRDeQEyBj.mShgdeHkvcARMCFf3vPb5Ggi5Xt6IJLY25fRG', -- @Omindu2004
        0,
        '+94771000001'
    ),
    (
        'EMP-002',
        'Inuka Asith',
        'inuka@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Receptionist'),
        '$2y$12$61WFoQ1QSsM8AH9j.gxGKeefHP6vKnTtp1nOzh8L1KumTi/Y92gHm', -- @Inuka2004
        0,
        '+94771000002'
    ),
    (
        'EMP-003',
        'Gavishka Sandamal',
        'gavishka@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Supporting Staff'),
        '$2y$12$1KWtJBv03MrcoUaQPV6MseSGwKlxvWyVnnndjK9xj796y3fN301PG', -- @Gavishka2003
        0,
        '+94771000003'
    ),
    (
        'EMP-004',
        'Sandanu Dulmeth',
        'sandhanu@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Pharmacist'),
        '$2y$12$bQs8r3rnKXyl.weAJFjxbebfMA3uu9zvgDEyaShwjkzIWGxWykMFu', -- @Sandhanu2004
        0,
        '+94771000004'
    ),
    (
        'EMP-005',
        'Sadeesha Savindya',
        'sadeesha@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Manager'),
        '$2y$12$vNL2Z5x6caaHTJw/V8aV3u1EKcdAFeIIYN0cyJ81gXAJ0./565j9G', -- @Sadeesha2004
        0,
        '+94771000005'
    ),
    (
        'EMP-006',
        'Nimsith Athuraliya',
        'nimsith@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Admin'),
        '$2y$12$q5agJTYA3da3aMvrzMUtDebMVrbPcLZ/SJyzf2WsJmX.OMqtLAmAe', -- @Nimsith2004
        0,
        '+94771000006'
    ),
    (
        'EMP-007',
        'Chandima Jayamanna',
        'chandima@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Doctor'),
        '$2y$12$o2TjlUtvHHCiOXaEdx.cEeIY2dNEtuySQhAzc2bNFtR8Fn5XHI7jy', -- @Chandima2004
        0,
        '+94771000007'
    ),
    (
        'EMP-008',
        'Anindu Pathirana',
        'anindu@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Doctor'),
        '$2y$12$HUIuP3knL5NSOf6FgsAiseMxZHEpldGodZVy71KWCcCOrjbvafCUW', -- @Anindu2004
        0,
        '+94771000008'
    );
-- Two patients, registered at the front desk by the receptionist (EMP-002)
-- but already past their first sign-in, so they sign in with the password
-- below using their email, NIC or mobile.
-- patient_code is normally built from patient_id after the insert
-- (PT-0001 for id 1…); written out here because this runs on a fresh,
-- empty database.
INSERT INTO patient (
        patient_code,
        nic,
        full_name,
        date_of_birth,
        gender,
        blood_type,
        mobile,
        email,
        address,
        password_hash,
        must_change_password,
        pdpa_consent,
        registered_by
    )
VALUES (
        'PT-0001',
        '831092811V',
        'K. Ashan Charuka',
        '1983-04-19',
        'male',
        'A+',
        '0712345601',
        'ashan@meditrack.lk',
        '8 Lake View, Malabe',
        '$2y$12$M3AT3dGmRAVPdjLs9/BfVu22UWgfKsxZQgI9BkVS7X.q4Kgy0uzfy', -- @Ashan2004
        0,
        1,
        (SELECT staff_id FROM staff WHERE employee_code = 'EMP-002')
    ),
    (
        'PT-0002',
        '926781002V',
        'G.G. Mithun Majika',
        '1992-06-26',
        'male',
        NULL,
        '0763345120',
        'mithun@meditrack.lk',
        NULL,
        '$2y$12$hd.gTY7ZV0eBh/AT3VEvz.wnHiDS3mUCVAs0pQ1ina9t8TWRsvQdi', -- @mithun2004
        0,
        1,
        (SELECT staff_id FROM staff WHERE employee_code = 'EMP-002')
    );
INSERT INTO patient_allergy (
        patient_id,
        allergen_name,
        added_by_role,
        added_by_staff_id
    )
VALUES (
        (SELECT patient_id FROM patient WHERE patient_code = 'PT-0001'),
        'Penicillin',
        'receptionist',
        (SELECT staff_id FROM staff WHERE employee_code = 'EMP-002')
    ),
    (
        (SELECT patient_id FROM patient WHERE patient_code = 'PT-0001'),
        'Ibuprofen',
        'receptionist',
        (SELECT staff_id FROM staff WHERE employee_code = 'EMP-002')
    );
-- Specialties lookup seed
INSERT INTO specialty (name)
VALUES ('General Medicine'),
    ('Cardiology'),
    ('Pediatrics'),
    ('ENT'),
    ('Dermatology');
SET @omindu = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-001');
SET @chandima = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-007');
SET @anindu = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-008');
-- The three doctors' profiles.
INSERT INTO doctor (staff_id, slmc_number, specialty_id, consultation_fee)
VALUES (
        @omindu,
        '45231',
        (SELECT specialty_id FROM specialty WHERE name = 'General Medicine'),
        2500.00
    ),
    (
        @chandima,
        '38117',
        (SELECT specialty_id FROM specialty WHERE name = 'Cardiology'),
        3500.00
    ),
    (
        @anindu,
        '51904',
        (SELECT specialty_id FROM specialty WHERE name = 'Pediatrics'),
        3000.00
    );
-- Demo leave for Dr. Omindu Gunathilaka
INSERT INTO doctor_leave (doctor_id, start_date, end_date, reason, created_by)
VALUES (@omindu, '2026-07-16', '2026-07-16', 'Medical conference in Kandy', @omindu);
-- Each doctor's approved weekly schedule (1 = Monday ... 7 = Sunday).
-- A day with no rows is a day off.
-- Dr. Omindu: weekday mornings, Wednesday evening too, short Saturday.
-- Dr. Chandima: Monday / Wednesday / Friday evenings.
-- Dr. Anindu: Tuesday / Thursday mornings and Saturday.
INSERT INTO doctor_regular_schedule (doctor_id, day_of_week, start_time, end_time, capacity)
VALUES (@omindu, 1, '09:00', '13:00', 16),
    (@omindu, 2, '09:00', '13:00', 16),
    (@omindu, 3, '09:00', '13:00', 16),
    (@omindu, 3, '17:00', '19:00', 8),
    (@omindu, 4, '09:00', '13:00', 16),
    (@omindu, 5, '09:00', '13:00', 16),
    (@omindu, 6, '09:00', '12:00', 12),
    (@chandima, 1, '16:00', '20:00', 16),
    (@chandima, 3, '16:00', '20:00', 16),
    (@chandima, 5, '16:00', '20:00', 16),
    (@anindu, 2, '08:30', '12:30', 16),
    (@anindu, 4, '08:30', '12:30', 16),
    (@anindu, 6, '08:30', '11:30', 12);
-- One day changed on Dr. Omindu's Schedule page: 25 Sep 2026 keeps its hours
-- but gets a tea break and a lunch break.
INSERT INTO doctor_availability (doctor_id, session_date, source)
VALUES (@omindu, '2026-09-25', 'manual');
SET @changedDay = LAST_INSERT_ID();
INSERT INTO availability_slot (availability_id, start_time, end_time, capacity)
VALUES (@changedDay, '09:00', '13:00', 16);
INSERT INTO schedule_break (availability_id, from_time, to_time, label)
VALUES (@changedDay, '11:00', '11:15', 'Tea break'),
    (@changedDay, '12:00', '12:30', 'Lunch');
-- Demo appointments for Dr. Omindu (the signed-in doctor). Registered
-- patients point at their patient row; everyone else is named on the
-- appointment itself.
SET @ashan = (SELECT patient_id FROM patient WHERE patient_code = 'PT-0001');
SET @mithun = (SELECT patient_id FROM patient WHERE patient_code = 'PT-0002');
INSERT INTO appointment (
        appointment_code,
        doctor_id,
        booking_for,
        patient_id,
        booking_patient_id,
        subject_full_name,
        subject_mobile,
        appointment_date,
        slot_time,
        visit_type,
        booking_channel,
        status,
        fee_amount,
        payment_timing
    )
VALUES ('APT-1001', @omindu, 'self', @ashan, @ashan, NULL, NULL, '2026-09-22', '09:00', 'follow_up', 'patient_web', 'completed', 2500.00, 'online'),
    ('APT-1002', @omindu, 'self', @mithun, @mithun, NULL, NULL, '2026-09-22', '09:15', 'new', 'patient_web', 'completed', 2500.00, 'at_counter'),
    ('APT-1003', @omindu, 'guest', NULL, NULL, 'Nimali Perera', '+94771234501', '2026-09-22', '09:30', 'new', 'guest_web', 'no_show', 2500.00, 'online'),
    ('APT-1004', @omindu, 'self', @mithun, @mithun, NULL, NULL, '2026-09-24', '09:00', 'new', 'patient_web', 'completed', 2500.00, 'at_counter'),
    ('APT-1005', @omindu, 'guest', NULL, NULL, 'Ruwan Silva', '+94771234502', '2026-09-24', '10:00', 'new', 'reception', 'completed', 2500.00, 'at_counter'),
    ('APT-1006', @omindu, 'self', @ashan, @ashan, NULL, NULL, '2026-09-25', '09:00', 'follow_up', 'patient_web', 'completed', 2500.00, 'online'),
    ('APT-1007', @omindu, 'someone_else', NULL, @ashan, 'Dilini Fernando', '+94771234503', '2026-09-25', '09:15', 'new', 'patient_web', 'completed', 2500.00, 'online'),
    ('APT-1008', @omindu, 'self', @mithun, @mithun, NULL, NULL, '2026-09-25', '09:30', 'follow_up', 'patient_web', 'completed', 2500.00, 'at_counter'),
    ('APT-1009', @omindu, 'guest', NULL, NULL, 'Chamath Dissanayake', '+94771234509', '2026-09-25', '10:30', 'new', 'reception', 'completed', 2500.00, 'at_counter'),
    ('APT-1010', @omindu, 'guest', NULL, NULL, 'Kasun Jayawardena', '+94771234504', '2026-09-25', '12:30', 'new', 'reception', 'completed', 2500.00, 'at_counter'),
    ('APT-1011', @omindu, 'guest', NULL, NULL, 'Amaya Senanayake', '+94771234505', '2026-09-25', '11:30', 'new', 'guest_web', 'cancelled', 2500.00, 'online'),
    ('APT-1012', @omindu, 'self', @ashan, @ashan, NULL, NULL, '2026-09-26', '09:00', 'follow_up', 'patient_web', 'completed', 2500.00, 'online'),
    ('APT-1013', @omindu, 'guest', NULL, NULL, 'Sachini Wijesinghe', '+94771234506', '2026-09-26', '09:30', 'new', 'guest_web', 'no_show', 2500.00, 'online'),
    ('APT-1014', @omindu, 'self', @mithun, @mithun, NULL, NULL, '2026-09-29', '09:15', 'new', 'patient_web', 'confirmed', 2500.00, 'at_counter'),
    ('APT-1015', @omindu, 'self', @ashan, @ashan, NULL, NULL, '2026-09-29', '11:00', 'follow_up', 'patient_web', 'confirmed', 2500.00, 'online'),
    ('APT-1016', @omindu, 'self', @mithun, @mithun, NULL, NULL, '2026-09-30', '17:00', 'new', 'patient_web', 'confirmed', 2500.00, 'at_counter'),
    ('APT-1017', @omindu, 'guest', NULL, NULL, 'Tharindu Bandara', '+94771234507', '2026-09-30', '17:30', 'new', 'reception', 'confirmed', 2500.00, 'at_counter'),
    ('APT-1018', @omindu, 'self', @ashan, @ashan, NULL, NULL, '2026-10-02', '09:00', 'new', 'patient_web', 'confirmed', 2500.00, 'online'),
    ('APT-1019', @omindu, 'guest', NULL, NULL, 'Hasini Rathnayake', '+94771234508', '2026-10-06', '10:15', 'new', 'guest_web', 'confirmed', 2500.00, 'online');
-- APT-1011 was cancelled after paying online: a cancellation needs a reason,
-- and the online payment is waiting to be refunded.
UPDATE appointment
SET cancel_reason = 'Cancelled by the patient',
    refund_status = 'queued'
WHERE appointment_code = 'APT-1011';

-- ==============================================================================
-- Pharmacy & Inventory Seed Data
-- ==============================================================================

-- Seed Suppliers
INSERT INTO supplier (name, contact)
VALUES (
        'MedLanka Pvt Ltd',
        '0112345678'
    ),
    (
        'Ceyoka Health',
        '0114712000'
    ),
    (
        'GlobalPharm Logistics',
        '0115889100'
    ),
    (
        'State Pharmaceuticals Corporation',
        '0112320356'
    ) ON DUPLICATE KEY
UPDATE contact =
VALUES(contact);

-- Seed Medicines
INSERT INTO medicine (
        commercial_name,
        generic_name,
        unit_form,
        manufacturer,
        storage_limits,
        unit_price,
        reorder_threshold,
        requires_prescription,
        is_available
    )
VALUES (
        'Paracetamol 500 mg',
        'Paracetamol',
        'tablet',
        'GlaxoSmithKline LK',
        'Store in cool dry place',
        8.00,
        300,
        0,
        1
    ),
    (
        'Amoxicillin 500 mg',
        'Amoxicillin',
        'capsule',
        'MedLanka Pharma',
        'Below 25 C dry',
        22.00,
        40,
        1,
        1
    ),
    (
        'Cetirizine 10 mg',
        'Cetirizine HCl',
        'tablet',
        'Ceyoka Labs',
        'Below 30 C',
        15.00,
        150,
        0,
        1
    ) ON DUPLICATE KEY
UPDATE generic_name =
VALUES(generic_name),
    unit_price =
VALUES(unit_price),
    reorder_threshold =
VALUES(reorder_threshold),
    requires_prescription =
VALUES(requires_prescription),
    is_available =
VALUES(is_available);

-- Seed Batches (using EMP-004 Pharmacist as registered_by). Nothing has been
-- dispensed or adjusted yet, so each batch still holds everything received.
SET @pharmacist_id = (
        SELECT staff_id
        FROM staff
        WHERE employee_code = 'EMP-004'
        LIMIT 1
    );
SET @sup_medlanka = (
        SELECT supplier_id
        FROM supplier
        WHERE name = 'MedLanka Pvt Ltd'
        LIMIT 1
    );
SET @sup_ceyoka = (
        SELECT supplier_id
        FROM supplier
        WHERE name = 'Ceyoka Health'
        LIMIT 1
    );
SET @sup_global = (
        SELECT supplier_id
        FROM supplier
        WHERE name = 'GlobalPharm Logistics'
        LIMIT 1
    );
SET @sup_spc = (
        SELECT supplier_id
        FROM supplier
        WHERE name = 'State Pharmaceuticals Corporation'
        LIMIT 1
    );

INSERT INTO medicine_batch (
        batch_code,
        medicine_id,
        supplier_id,
        supplier_invoice_ref,
        quantity_received,
        quantity_on_hand,
        cost_price_total,
        expiry_date,
        status,
        registered_by
    )
VALUES (
        'BT-2190', (
            SELECT medicine_id
            FROM medicine
            WHERE commercial_name = 'Paracetamol 500 mg'
            LIMIT 1
        ), @sup_ceyoka, 'CY-2026-3401', 800, 800, 3840.00, '2027-03-11', 'active', @pharmacist_id
    ), (
        'BT-2205', (
            SELECT medicine_id
            FROM medicine
            WHERE commercial_name = 'Paracetamol 500 mg'
            LIMIT 1
        ), @sup_ceyoka, 'CY-2026-3890', 600, 600, 3600.00, '2027-06-08', 'active', @pharmacist_id
    ), (
        'BT-2214', (
            SELECT medicine_id
            FROM medicine
            WHERE commercial_name = 'Amoxicillin 500 mg'
            LIMIT 1
        ), @sup_medlanka, 'ML-2026-1102', 500, 500, 7700.00, '2027-08-15', 'active', @pharmacist_id
    ), (
        'BT-2201', (
            SELECT medicine_id
            FROM medicine
            WHERE commercial_name = 'Cetirizine 10 mg'
            LIMIT 1
        ), @sup_medlanka, 'ML-2026-2210', 400, 400, 4200.00, '2027-10-20', 'active', @pharmacist_id
    ) ON DUPLICATE KEY
UPDATE quantity_on_hand =
VALUES(quantity_on_hand),
    status =
VALUES(status);
