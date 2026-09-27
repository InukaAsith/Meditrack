USE meditrack;
INSERT INTO role (role_name, description)
VALUES ('Patient', 'App-facing patient/guest account'),
    ('Doctor', 'Consults patients, writes prescriptions'),
    ('Receptionist', 'Front-desk booking, check-in, billing'),
    ('Supporting Staff', 'Live queue and vitals, no billing/prescriptions'),
    ('Pharmacist', 'Dispensing and pharmacy inventory'),
    ('Manager', 'Analytics, approvals, financial reports'),
    ('Admin', 'System administration and audit');
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
        '$2y$12$RDDfJMLRDeQEyBj.mShgdeHkvcARMCFf3vPb5Ggi5Xt6IJLY25fRG',
        0,
        '0771000001'
    ),
    (
        'EMP-002',
        'Inuka Asith',
        'inuka@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Receptionist'),
        '$2y$12$61WFoQ1QSsM8AH9j.gxGKeefHP6vKnTtp1nOzh8L1KumTi/Y92gHm',
        0,
        '0771000002'
    ),
    (
        'EMP-003',
        'Gavishka Sandamal',
        'gavishka@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Supporting Staff'),
        '$2y$12$1KWtJBv03MrcoUaQPV6MseSGwKlxvWyVnnndjK9xj796y3fN301PG',
        0,
        '0771000003'
    ),
    (
        'EMP-004',
        'Sandanu Dulmeth',
        'sandhanu@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Pharmacist'),
        '$2y$12$bQs8r3rnKXyl.weAJFjxbebfMA3uu9zvgDEyaShwjkzIWGxWykMFu',
        0,
        '0771000004'
    ),
    (
        'EMP-005',
        'Sadeesha Savindya',
        'sadeesha@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Manager'),
        '$2y$12$vNL2Z5x6caaHTJw/V8aV3u1EKcdAFeIIYN0cyJ81gXAJ0./565j9G',
        0,
        '0771000005'
    ),
    (
        'EMP-006',
        'Nimsith Athuraliya',
        'nimsith@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Admin'),
        '$2y$12$q5agJTYA3da3aMvrzMUtDebMVrbPcLZ/SJyzf2WsJmX.OMqtLAmAe',
        0,
        '0771000006'
    ),
    (
        'EMP-007',
        'Chandima Jayamanna',
        'chandima@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Doctor'),
        '$2y$12$o2TjlUtvHHCiOXaEdx.cEeIY2dNEtuySQhAzc2bNFtR8Fn5XHI7jy',
        0,
        '0771000007'
    ),
    (
        'EMP-008',
        'Anindu Pathirana',
        'anindu@meditrack.lk',
        (SELECT role_id FROM role WHERE role_name = 'Doctor'),
        '$2y$12$HUIuP3knL5NSOf6FgsAiseMxZHEpldGodZVy71KWCcCOrjbvafCUW',
        0,
        '0771000008'
    );
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
        '$2y$12$M3AT3dGmRAVPdjLs9/BfVu22UWgfKsxZQgI9BkVS7X.q4Kgy0uzfy',
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
        '$2y$12$hd.gTY7ZV0eBh/AT3VEvz.wnHiDS3mUCVAs0pQ1ina9t8TWRsvQdi',
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
INSERT INTO specialty (name)
VALUES ('General Medicine'),
    ('Cardiology'),
    ('Pediatrics'),
    ('ENT'),
    ('Dermatology');
SET @omindu = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-001');
SET @chandima = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-007');
SET @anindu = (SELECT staff_id FROM staff WHERE employee_code = 'EMP-008');
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
INSERT INTO doctor_leave (doctor_id, start_date, end_date, reason, created_by)
VALUES (@omindu, '2026-07-16', '2026-07-16', 'Medical conference in Kandy', @omindu);
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
INSERT INTO doctor_availability (doctor_id, session_date, source)
VALUES (@omindu, '2026-09-25', 'manual');
SET @changedDay = LAST_INSERT_ID();
INSERT INTO availability_slot (availability_id, start_time, end_time, capacity)
VALUES (@changedDay, '09:00', '13:00', 16);
INSERT INTO schedule_break (availability_id, from_time, to_time, label)
VALUES (@changedDay, '11:00', '11:15', 'Tea break'),
    (@changedDay, '12:00', '12:30', 'Lunch');

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
