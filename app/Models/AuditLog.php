<?php

declare(strict_types=1);

final class AuditLog
{
    public static function record(
        string $actorType,
        int $actorId,
        string $action,
        ?string $entityName = null,
        ?string $entityPk = null,
        ?string $changedFields = null,
    ): void {
        db()->prepare(
            'INSERT INTO audit_log (actor_type, actor_id, action, entity_name, entity_pk, changed_fields)
             VALUES (?, ?, ?, ?, ?, ?)',
        )->execute([$actorType, $actorId, $action, $entityName, $entityPk, $changedFields]);
    }

    public static function seedBaselineIfEmpty(): void
    {
        $hasBaseline = (int) db()->query("SELECT COUNT(*) FROM audit_log WHERE entity_name = 'clinic_settings'")->fetchColumn();
        if ($hasBaseline > 0) {
            return;
        }

        $demos = [
            ['staff', 6, 'update', 'clinic_settings', '1', 'grace_window_min', '2026-09-26 06:20:11'],
            ['staff', 6, 'update', 'staff', '5', 'role_id', '2026-09-26 06:01:44'],
            ['staff', 4, 'update', 'medicine', '1', 'unit_price', '2026-09-26 05:52:03'],
            ['staff', 2, 'update', 'invoice', 'INV-0228', 'status, void_reason', '2026-09-26 05:35:22'],
            ['staff', 6, 'create', 'staff', '3', null, '2026-09-26 05:20:05'],
            ['staff', 1, 'update', 'consultation', 'CN-8841', 'notes, diagnosis', '2026-09-26 05:14:07'],
            ['staff', 1, 'view', 'patient', '1', null, '2026-09-26 05:12:55'],
            ['staff', 6, 'update', 'staff', '2', 'status', '2026-09-25 16:40:00'],
            ['staff', 6, 'update', 'message_template', 'Booking confirmed', 'message_text', '2026-09-25 14:11:00'],
        ];

        $stmt = db()->prepare(
            'INSERT INTO audit_log (actor_type, actor_id, action, entity_name, entity_pk, changed_fields, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        foreach ($demos as $row) {
            $stmt->execute($row);
        }
    }

    public static function allRecordChanges(): array
    {
        self::seedBaselineIfEmpty();

        $stmt = db()->query(
            "SELECT
                a.audit_log_id,
                a.actor_type,
                a.actor_id,
                a.action,
                a.entity_name,
                a.entity_pk,
                a.changed_fields,
                a.created_at,
                s.full_name AS staff_name,
                s.employee_code AS staff_code,
                r.role_name AS staff_role,
                p.full_name AS patient_name,
                p.patient_code AS patient_code
             FROM audit_log a
             LEFT JOIN staff s ON a.actor_type = 'staff' AND a.actor_id = s.staff_id
             LEFT JOIN role r ON s.role_id = r.role_id
             LEFT JOIN patient p ON a.actor_type = 'patient' AND a.actor_id = p.patient_id
             WHERE a.action NOT IN ('login', 'logout')
             ORDER BY a.created_at DESC, a.audit_log_id DESC"
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $staffMap = [];
        $staffRows = db()->query('SELECT staff_id, employee_code, full_name FROM staff')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($staffRows as $st) {
            $staffMap[(int) $st['staff_id']] = $st;
            $staffMap[(string) $st['employee_code']] = $st;
        }

        $patientMap = [];
        $patientRows = db()->query('SELECT patient_id, patient_code, full_name FROM patient')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($patientRows as $pt) {
            $patientMap[(int) $pt['patient_id']] = $pt;
            $patientMap[(string) $pt['patient_code']] = $pt;
        }

        $records = [];
        foreach ($rows as $row) {
            $records[] = self::formatRecordRow($row, $staffMap, $patientMap);
        }

        return $records;
    }

    public static function allLoginHistory(): array
    {
        $stmt = db()->query(
            "SELECT
                a.audit_log_id,
                a.actor_type,
                a.actor_id,
                a.action,
                a.changed_fields,
                a.created_at,
                s.full_name AS staff_name,
                s.employee_code AS staff_code,
                r.role_name AS staff_role,
                p.full_name AS patient_name,
                p.patient_code AS patient_code
             FROM audit_log a
             LEFT JOIN staff s ON a.actor_type = 'staff' AND a.actor_id = s.staff_id
             LEFT JOIN role r ON s.role_id = r.role_id
             LEFT JOIN patient p ON a.actor_type = 'patient' AND a.actor_id = p.patient_id
             WHERE a.action = 'login' OR a.action = 'logout'
             ORDER BY a.created_at DESC, a.audit_log_id DESC"
        );

        $dbLogins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logins = [];
        foreach ($dbLogins as $row) {
            $user = $row['staff_name'] ?? $row['patient_name'] ?? ('Staff #' . $row['actor_id']);
            $role = $row['staff_role'] ?? ($row['actor_type'] === 'patient' ? 'Patient' : 'Staff');
            $rawTime = (string) $row['created_at'];

            $ip = '127.0.0.1';
            $session = 'Chrome on Windows';
            if (!empty($row['changed_fields']) && str_contains($row['changed_fields'], '|')) {
                [$parsedIp, $parsedSession] = explode('|', $row['changed_fields'], 2);
                $ip = trim($parsedIp);
                $session = trim($parsedSession);
            }

            $logins[] = [
                'time' => self::formatTime($rawTime),
                'date' => date('Y-m-d', strtotime($rawTime)),
                'user' => $user,
                'role' => $role,
                'ip' => $ip,
                'session' => $session,
                'otp' => true,
                'signed_out' => $row['action'] === 'logout' ? 'Signed out' : null,
            ];
        }

        $demoLogins = [
            ['time' => 'Today 09:12:55', 'date' => date('Y-m-d'), 'user' => 'K. Ashan Charuka',      'role' => 'Receptionist',     'ip' => '192.168.1.40', 'session' => 'Chrome on Windows', 'otp' => false, 'signed_out' => null],
            ['time' => 'Today 08:35:03', 'date' => date('Y-m-d'), 'user' => 'Nimsith Wickrama',     'role' => 'Manager',          'ip' => '192.168.1.28', 'session' => 'Edge on Windows',   'otp' => false, 'signed_out' => null],
            ['time' => 'Today 08:22:41', 'date' => date('Y-m-d'), 'user' => 'G. G. Mithun Majika',    'role' => 'Supporting Staff', 'ip' => '192.168.1.31', 'session' => 'Chrome on Windows', 'otp' => false, 'signed_out' => null],
            ['time' => 'Today 08:14:22', 'date' => date('Y-m-d'), 'user' => 'Dr. Sample Doctor 1',  'role' => 'Doctor',           'ip' => '192.168.1.24', 'session' => 'Chrome on macOS',   'otp' => true,  'signed_out' => null],
            ['time' => 'Today 08:02:10', 'date' => date('Y-m-d'), 'user' => 'Sandanu Dulmeth',     'role' => 'Receptionist',     'ip' => '192.168.1.28', 'session' => 'Chrome on Windows', 'otp' => false, 'signed_out' => null],
            ['time' => 'Today 07:58:33', 'date' => date('Y-m-d'), 'user' => 'G. G. Mithun Majika',    'role' => 'Pharmacist',       'ip' => '192.168.1.31', 'session' => 'Chrome on Windows', 'otp' => false, 'signed_out' => null],
            ['time' => 'Yesterday 17:20', 'date' => date('Y-m-d', strtotime('-1 day')), 'user' => 'Dr. Sample Doctor 2', 'role' => 'Doctor',           'ip' => '192.168.1.24', 'session' => 'Chrome on macOS',   'otp' => true,  'signed_out' => 'Yesterday 20:05'],
            ['time' => 'Yesterday 08:40', 'date' => date('Y-m-d', strtotime('-1 day')), 'user' => 'K.A. Inuka Asith',      'role' => 'Admin',            'ip' => '192.168.1.20', 'session' => 'Chrome on macOS',   'otp' => true,  'signed_out' => 'Yesterday 18:12'],
        ];

        foreach ($demoLogins as $demo) {
            $logins[] = $demo;
        }

        return $logins;
    }

    private static function formatRecordRow(array $row, array $staffMap, array $patientMap): array
    {
        $rawTime = (string) $row['created_at'];
        $user = $row['staff_name'] ?? $row['patient_name'] ?? ('User #' . $row['actor_id']);
        $role = $row['staff_role'] ?? ($row['actor_type'] === 'patient' ? 'Patient' : 'Admin');

        $entityNameRaw = strtolower(trim((string) ($row['entity_name'] ?? '')));
        $entityPkRaw = trim((string) ($row['entity_pk'] ?? ''));

        $entity = 'Record';
        $pk = $entityPkRaw !== '' ? '#' . $entityPkRaw : '-';

        switch ($entityNameRaw) {
            case 'staff':
                $entity = 'Staff';
                if (ctype_digit($entityPkRaw) && isset($staffMap[(int) $entityPkRaw])) {
                    $pk = $staffMap[(int) $entityPkRaw]['employee_code'];
                } elseif (isset($staffMap[$entityPkRaw])) {
                    $pk = $staffMap[$entityPkRaw]['employee_code'];
                } elseif (str_starts_with($entityPkRaw, 'EMP-')) {
                    $pk = $entityPkRaw;
                } elseif (ctype_digit($entityPkRaw)) {
                    $pk = 'EMP-' . str_pad($entityPkRaw, 3, '0', STR_PAD_LEFT);
                }
                break;

            case 'doctor':
                $entity = 'Doctor';
                if (ctype_digit($entityPkRaw) && isset($staffMap[(int) $entityPkRaw])) {
                    $pk = $staffMap[(int) $entityPkRaw]['employee_code'];
                } else {
                    $pk = $entityPkRaw !== '' ? $entityPkRaw : '#';
                }
                break;

            case 'doctor_leave':
                $entity = 'Doctor leave';
                $pk = '#' . $entityPkRaw;
                break;

            case 'patient':
                $entity = 'Patient';
                if (ctype_digit($entityPkRaw) && isset($patientMap[(int) $entityPkRaw])) {
                    $pk = $patientMap[(int) $entityPkRaw]['patient_code'];
                } elseif (str_starts_with($entityPkRaw, 'PT-')) {
                    $pk = $entityPkRaw;
                } else {
                    $pk = 'PT-' . str_pad($entityPkRaw, 4, '0', STR_PAD_LEFT);
                }
                break;

            case 'patient_allergy':
                $entity = 'Patient allergy';
                $pk = $patientMap[(int) $entityPkRaw]['patient_code'] ?? ('#' . $entityPkRaw);
                break;

            case 'patient_photo':
            case 'staff_photo':
                $entity = 'Staff photo';
                if (ctype_digit($entityPkRaw) && isset($staffMap[(int) $entityPkRaw])) {
                    $pk = $staffMap[(int) $entityPkRaw]['employee_code'];
                } else {
                    $pk = '#' . $entityPkRaw;
                }
                break;

            case 'medicine':
                $entity = 'Medicine';
                $pk = str_starts_with($entityPkRaw, '#') ? $entityPkRaw : '#' . $entityPkRaw;
                break;

            case 'clinic_settings':
            case 'clinic_config':
                $entity = 'Clinic settings';
                $pk = str_starts_with($entityPkRaw, '#') ? $entityPkRaw : '#' . ($entityPkRaw ?: '1');
                break;

            case 'invoice':
                $entity = 'Invoice';
                $pk = str_starts_with($entityPkRaw, 'INV-') ? $entityPkRaw : 'INV-' . str_pad($entityPkRaw, 4, '0', STR_PAD_LEFT);
                break;

            case 'consultation':
                $entity = 'Consultation';
                $pk = str_starts_with($entityPkRaw, 'CN-') ? $entityPkRaw : 'CN-' . str_pad($entityPkRaw, 4, '0', STR_PAD_LEFT);
                break;

            case 'message_template':
                $entity = 'Message template';
                $pk = $entityPkRaw ?: 'Booking confirmed';
                break;

            default:
                if ($row['action'] === 'password_change') {
                    $entity = 'Staff';
                    $pk = $row['staff_code'] ?? ('EMP-' . str_pad((string) $row['actor_id'], 3, '0', STR_PAD_LEFT));
                } elseif ($entityNameRaw !== '') {
                    $entity = ucwords(str_replace('_', ' ', $entityNameRaw));
                    $pk = $entityPkRaw !== '' ? (str_starts_with($entityPkRaw, '#') ? $entityPkRaw : '#' . $entityPkRaw) : '-';
                }
                break;
        }

        $fields = null;
        if (!empty($row['changed_fields'])) {
            $rawFieldList = explode(',', $row['changed_fields']);
            $fields = [];
            foreach ($rawFieldList as $f) {
                $f = trim($f);
                if ($f === '') continue;
                $fields[] = self::prettifyFieldName($f);
            }
        }

        return [
            'id' => (int) $row['audit_log_id'],
            'time' => self::formatTime($rawTime),
            'date' => date('Y-m-d', strtotime($rawTime)),
            'user' => $user,
            'role' => $role,
            'entity' => $entity,
            'pk' => $pk,
            'action' => (string) $row['action'],
            'fields' => $fields,
        ];
    }

    private static function formatTime(string $datetime): string
    {
        $ts = strtotime($datetime);
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $d = date('Y-m-d', $ts);

        if ($d === $today) {
            return 'Today ' . date('H:i:s', $ts);
        }
        if ($d === $yesterday) {
            return 'Yesterday ' . date('H:i', $ts);
        }
        return date('d M Y H:i', $ts);
    }

    private static function prettifyFieldName(string $col): string
    {
        $map = [
            'full_name' => 'Full name',
            'work_email' => 'Work email',
            'phone' => 'Phone',
            'photo_uri' => 'Photo',
            'role_id' => 'Role',
            'status' => 'Status',
            'unit_price' => 'Unit price',
            'reorder_threshold' => 'Threshold',
            'requires_prescription' => 'Requires Rx',
            'is_available' => 'Availability',
            'slmc_number' => 'SLMC number',
            'specialty_id' => 'Specialty',
            'consultation_fee' => 'Consultation fee',
            'followup_fee' => 'Follow-up fee',
            'grace_window_min' => 'Grace window',
            'arrival_buffer_min' => 'Arrival buffer',
            'no_show_limit_min' => 'No-show limit',
            'notes' => 'Notes',
            'diagnosis' => 'Diagnosis',
            'void_reason' => 'Void reason',
            'message_text' => 'Message text',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'reason' => 'Reason',
        ];

        return $map[$col] ?? ucwords(str_replace('_', ' ', $col));
    }

    public static function lastLogin(string $actorType, int $actorId): ?string
    {
        $stmt = db()->prepare(
            "SELECT MAX(created_at) FROM audit_log
             WHERE actor_type = ? AND actor_id = ? AND action = 'login'",
        );
        $stmt->execute([$actorType, $actorId]);
        $when = $stmt->fetchColumn();

        return $when !== false && $when !== null ? (string) $when : null;
    }
}
