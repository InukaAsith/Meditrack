<?php

declare(strict_types=1);

final class StaffTrustedDevice
{
    public const DAYS = 30;

    public static function create(int $staffId): string
    {
        $token = bin2hex(random_bytes(32));

        db()->prepare(
            'INSERT INTO staff_trusted_device (staff_id, token_hash, expires_at)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))',
        )->execute([$staffId, hash('sha256', $token), self::DAYS]);

        return $token;
    }

    public static function isTrusted(int $staffId, string $token): bool
    {
        if ($token === '') {
            return false;
        }

        $stmt = db()->prepare(
            'SELECT 1 FROM staff_trusted_device
             WHERE staff_id = ? AND token_hash = ? AND expires_at > NOW()',
        );
        $stmt->execute([$staffId, hash('sha256', $token)]);

        return $stmt->fetchColumn() !== false;
    }

    public static function delete(int $staffId, string $token): void
    {
        db()->prepare('DELETE FROM staff_trusted_device WHERE staff_id = ? AND token_hash = ?')
            ->execute([$staffId, hash('sha256', $token)]);
    }
}
