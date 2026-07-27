<?php

declare(strict_types=1);

final class Staff
{
    public static function findForSignIn(string $identifier): array|false
    {
        $stmt = db()->prepare(
            'SELECT staff.staff_id, staff.password_hash, staff.must_change_password, staff.status, role.role_name
             FROM staff
             JOIN role ON role.role_id = staff.role_id
             WHERE staff.work_email = ? OR staff.employee_code = ?
             LIMIT 1',
        );
        $stmt->execute([$identifier, $identifier]);

        return $stmt->fetch();
    }

    public static function isActive(int $staffId): bool
    {
        $stmt = db()->prepare('SELECT status FROM staff WHERE staff_id = ?');
        $stmt->execute([$staffId]);

        return $stmt->fetchColumn() === 'active';
    }

        public static function mustChangePassword(int $staffId): bool
    {
        $stmt = db()->prepare('SELECT must_change_password FROM staff WHERE staff_id = ?');
        $stmt->execute([$staffId]);
        $row = $stmt->fetch();

        return $row !== false && (int) $row['must_change_password'] === 1;
    }

    public static function passwordHash(int $staffId): string
    {
        $stmt = db()->prepare('SELECT password_hash FROM staff WHERE staff_id = ?');
        $stmt->execute([$staffId]);

        return (string) $stmt->fetchColumn();
    }

    public static function setNewPassword(int $staffId, string $passwordHash): void
    {
        db()->prepare('UPDATE staff SET password_hash = ?, must_change_password = 0 WHERE staff_id = ?')
            ->execute([$passwordHash, $staffId]);
    }

    public static function all(): array
    {
        $stmt = db()->prepare(
            'SELECT staff.staff_id, staff.employee_code, staff.full_name, staff.work_email,
                    staff.role_id, staff.status, staff.phone, staff.photo_uri, staff.created_at,
                    role.role_name
             FROM staff
             JOIN role ON role.role_id = staff.role_id
             ORDER BY staff.staff_id ASC',
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $staffId): array|false
    {
        $stmt = db()->prepare(
            'SELECT staff.staff_id, staff.employee_code, staff.full_name, staff.work_email,
                    staff.role_id, staff.status, staff.phone, staff.photo_uri, staff.created_at,
                    role.role_name
             FROM staff
             JOIN role ON role.role_id = staff.role_id
             WHERE staff.staff_id = ?
             LIMIT 1',
        );
        $stmt->execute([$staffId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function search(string $text, ?string $role = null): array
    {
        $sql = 'SELECT staff.staff_id, staff.employee_code, staff.full_name, staff.work_email,
                       staff.role_id, staff.status, staff.phone, staff.photo_uri, staff.created_at,
                       role.role_name
                FROM staff
                JOIN role ON role.role_id = staff.role_id
                WHERE 1=1';
        $params = [];

        if ($text !== '') {
            $sql .= ' AND (staff.full_name LIKE ? OR staff.employee_code LIKE ? OR staff.work_email LIKE ? OR staff.phone LIKE ?)';
            $like = '%' . $text . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($role !== null && $role !== '' && $role !== 'All') {
            $sql .= ' AND role.role_name = ?';
            $params[] = $role;
        }

        $sql .= ' ORDER BY staff.staff_id ASC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function nextEmployeeCode(): string
    {
        $stmt = db()->prepare("SELECT employee_code FROM staff WHERE employee_code LIKE 'EMP-%' ORDER BY staff_id DESC");
        $stmt->execute();
        $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $maxNum = 0;
        foreach ($codes as $code) {
            if (preg_match('/^EMP-([0-9]+)$/', (string) $code, $m)) {
                $num = (int) $m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        return sprintf('EMP-%03d', $maxNum + 1);
    }

    public static function emailTaken(string $email, int $exceptStaffId = 0): bool
    {
        $stmt = db()->prepare('SELECT staff_id FROM staff WHERE work_email = ? AND staff_id <> ? LIMIT 1');
        $stmt->execute([$email, $exceptStaffId]);

        return $stmt->fetch() !== false;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO staff (employee_code, full_name, work_email, role_id, password_hash, must_change_password, status, phone, photo_uri)
             VALUES (?, ?, ?, ?, ?, 1, "active", ?, ?)',
        );
        $stmt->execute([
            $data['employee_code'],
            $data['full_name'],
            $data['work_email'],
            $data['role_id'],
            $data['password_hash'],
            $data['phone'] ?? null,
            $data['photo_uri'] ?? null,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function updateDetails(int $staffId, array $data): void
    {
        $stmt = db()->prepare(
            'UPDATE staff
             SET full_name = ?, work_email = ?, phone = ?
             WHERE staff_id = ?',
        );
        $stmt->execute([
            $data['full_name'],
            $data['work_email'],
            $data['phone'] ?? null,
            $staffId,
        ]);
    }

    public static function setPhoto(int $staffId, ?string $photoUri): void
    {
        db()->prepare('UPDATE staff SET photo_uri = ? WHERE staff_id = ?')
            ->execute([$photoUri, $staffId]);
    }

    public static function setStatus(int $staffId, string $status): void
    {
        db()->prepare('UPDATE staff SET status = ? WHERE staff_id = ?')
            ->execute([$status, $staffId]);
    }

    public static function resetPassword(int $staffId, string $passwordHash): void
    {
        db()->prepare('UPDATE staff SET password_hash = ?, must_change_password = 1 WHERE staff_id = ?')
            ->execute([$passwordHash, $staffId]);
    }

    public static function allRoles(): array
    {
        $stmt = db()->prepare("SELECT * FROM role WHERE role_name <> 'Patient' ORDER BY role_id ASC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findRoleId(string $roleName): ?int
    {
        $stmt = db()->prepare('SELECT role_id FROM role WHERE role_name = ? LIMIT 1');
        $stmt->execute([$roleName]);
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
}
