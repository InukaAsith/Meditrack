<?php

declare(strict_types=1);

final class Supplier
{
    public static function all(): array
    {
        $stmt = db()->query('SELECT supplier_id, name, contact FROM supplier ORDER BY name ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allWithBatchCount(): array
    {
        $sql = 'SELECT s.supplier_id, s.name, s.contact, s.created_at, COUNT(b.batch_id) AS batch_count
                FROM supplier s
                LEFT JOIN medicine_batch b ON s.supplier_id = b.supplier_id
                GROUP BY s.supplier_id
                ORDER BY s.name ASC';
        $stmt = db()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function existsByName(string $name): bool
    {
        $stmt = db()->prepare('SELECT supplier_id FROM supplier WHERE name = ? LIMIT 1');
        $stmt->execute([trim($name)]);
        return $stmt->fetchColumn() !== false;
    }

    public static function create(string $name, ?string $contact = null): int
    {
        $name = trim($name);
        $pdo = db();
        $ins = $pdo->prepare('INSERT INTO supplier (name, contact) VALUES (?, ?)');
        $ins->execute([$name, $contact !== null && trim($contact) !== '' ? trim($contact) : null]);
        return (int) $pdo->lastInsertId();
    }

    public static function findOrCreate(string $name, ?string $contact = null): int
    {
        $name = trim($name);
        $pdo = db();

        $stmt = $pdo->prepare('SELECT supplier_id FROM supplier WHERE name = ? LIMIT 1');
        $stmt->execute([$name]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int) $id;
        }

        return self::create($name, $contact);
    }
}
