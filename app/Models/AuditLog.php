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

    public static function allRecordChanges(): array
    {
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

        $staffCodes = [];
        foreach (db()->query('SELECT staff_id, employee_code FROM staff')->fetchAll(PDO::FETCH_ASSOC) as $st) {
            $staffCodes[(int) $st['staff_id']] = (string) $st['employee_code'];
        }
        $patientCodes = [];
        foreach (db()->query('SELECT patient_id, patient_code FROM patient')->fetchAll(PDO::FETCH_ASSOC) as $pt) {
            $patientCodes[(int) $pt['patient_id']] = (string) $pt['patient_code'];
        }

        $records = [];
        foreach ($rows as $row) {
            $records[] = self::formatRecordRow($row, $staffCodes, $patientCodes);
        }

        return $records;
    }

    public static function recordLogin(string $actorType, int $actorId, bool $usedOtp): void
    {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $details = $ip . ' | ' . self::browserName() . ' | ' . ($usedOtp ? 'otp' : 'no otp');
        self::record($actorType, $actorId, 'login', null, null, $details);
    }

    private static function browserName(): string
    {
        $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');

        $browser = 'Unknown browser';
        if (str_contains($ua, 'Edg/')) {
            $browser = 'Edge';
        } elseif (str_contains($ua, 'OPR/')) {
            $browser = 'Opera';
        } elseif (str_contains($ua, 'Firefox/')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'Chrome/')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'Safari/')) {
            $browser = 'Safari';
        }

        $system = 'unknown system';
        if (str_contains($ua, 'Android')) {
            $system = 'Android';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $system = 'iOS';
        } elseif (str_contains($ua, 'Windows')) {
            $system = 'Windows';
        } elseif (str_contains($ua, 'Mac OS')) {
            $system = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $system = 'Linux';
        }

        return $browser . ' on ' . $system;
    }

    public static function allLoginHistory(): array
    {
        $stmt = db()->query(
            "SELECT
                a.actor_type,
                a.actor_id,
                a.action,
                a.changed_fields,
                a.created_at,
                s.full_name AS staff_name,
                r.role_name AS staff_role,
                p.full_name AS patient_name,
                p.patient_code AS patient_code
             FROM audit_log a
             LEFT JOIN staff s ON a.actor_type = 'staff' AND a.actor_id = s.staff_id
             LEFT JOIN role r ON s.role_id = r.role_id
             LEFT JOIN patient p ON a.actor_type = 'patient' AND a.actor_id = p.patient_id
             WHERE a.action IN ('login', 'logout')
             ORDER BY a.created_at ASC, a.audit_log_id ASC"
        );

        $logins = [];
        $openLogin = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $who = $row['actor_type'] . ':' . $row['actor_id'];
            $rawTime = (string) $row['created_at'];

            if ($row['action'] === 'logout') {
                if (isset($openLogin[$who])) {
                    $logins[$openLogin[$who]]['signed_out'] = 'Signed out ' . self::formatTime($rawTime);
                    unset($openLogin[$who]);
                }
                continue;
            }

            if (isset($openLogin[$who])) {
                $logins[$openLogin[$who]]['signed_out'] = 'No sign-out';
            }

            $parts = array_map('trim', explode('|', (string) $row['changed_fields']));

            if ($row['actor_type'] === 'patient') {
                $user = $row['patient_name'] ?? $row['patient_code'] ?? ('Patient #' . $row['actor_id']);
                $role = 'Patient';
            } else {
                $user = $row['staff_name'] ?? ('Staff #' . $row['actor_id']);
                $role = $row['staff_role'] ?? 'Staff';
            }

            $logins[] = [
                'time' => self::formatTime($rawTime),
                'date' => date('Y-m-d', strtotime($rawTime)),
                'user' => $user,
                'role' => $role,
                'ip' => ($parts[0] ?? '') !== '' ? $parts[0] : '-',
                'session' => $parts[1] ?? '-',
                'otp' => ($parts[2] ?? '') === 'otp',
                'signed_out' => null,
            ];
            $openLogin[$who] = count($logins) - 1;
        }

        return array_reverse($logins);
    }

    private static function formatRecordRow(array $row, array $staffCodes, array $patientCodes): array
    {
        $rawTime = (string) $row['created_at'];

        if ($row['actor_type'] === 'patient') {
            $user = $row['patient_name'] ?? $row['patient_code'] ?? ('Patient #' . $row['actor_id']);
            $role = 'Patient';
        } else {
            $user = $row['staff_name'] ?? ('Staff #' . $row['actor_id']);
            $role = $row['staff_role'] ?? 'Staff';
        }

        $entityName = (string) ($row['entity_name'] ?? '');
        $entityPk = (string) ($row['entity_pk'] ?? '');

        $entity = $entityName !== '' ? ucfirst(str_replace('_', ' ', $entityName)) : 'Account';
        $pk = $entityPk !== '' ? '#' . $entityPk : '-';

        if (in_array($entityName, ['staff', 'staff_photo', 'doctor', 'doctor_regular_schedule'], true)) {
            $pk = $staffCodes[(int) $entityPk] ?? $pk;
        } elseif (in_array($entityName, ['patient', 'patient_photo', 'patient_allergy'], true)) {
            $pk = $patientCodes[(int) $entityPk] ?? $pk;
        } elseif ($entityName === 'doctor_availability' && !ctype_digit($entityPk)) {
            $pk = $entityPk;
        } elseif ($entityName === '') {
            $pk = $row['actor_type'] === 'patient'
                ? ($row['patient_code'] ?? '-')
                : ($row['staff_code'] ?? '-');
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
            'availability_slot' => 'Sessions',
            'schedule_break' => 'Breaks',
            'day_of_week' => 'Day',
            'start_time' => 'Start time',
            'end_time' => 'End time',
            'capacity' => 'Capacity',
            'slot_length_min' => 'Slot length',
            'overtime_warn_min' => 'Overtime warning',
            'default_capacity' => 'Default capacity',
            'uses_regular_schedule' => 'Weekly schedule',
            'roster_api_enabled' => 'Roster',
            'refund_status' => 'Refund',
            'proposed_consultation_fee' => 'Proposed consultation fee',
            'proposed_followup_fee' => 'Proposed follow-up fee',
            'doctor_regular_schedule' => 'Weekly schedule',
            'quantity_on_hand' => 'Quantity',
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
