<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class CarRepository
{
    /** Майдонҳое, ки аз шакл захира кардан мумкин аст (рӯйхати сафед) */
    public const FIELDS = [
        'brand', 'model', 'year', 'image', 'price_day', 'tariff_id', 'odometer', 'status',
    ];

    private const SELECT = '
        SELECT c.*,
               t.name           AS tariff_name,
               t.km_per_day,
               t.extra_km_price
        FROM cars c
        JOIN tariffs t ON t.id = c.tariff_id
    ';

    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    private function buildWhere(array $filters): array
    {
        $conditions = [];
        $params = [];

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $conditions[] = "CONCAT_WS(' ', c.brand, c.model) LIKE :q";
            $params[':q'] = '%' . addcslashes($q, '%_\\') . '%';
        }
        if (!empty($filters['tariff_id'])) {
            $conditions[] = 'c.tariff_id = :tariff_id';
            $params[':tariff_id'] = (int) $filters['tariff_id'];
        }
        if (!empty($filters['status'])) {
            $conditions[] = 'c.status = :status';
            $params[':status'] = (string) $filters['status'];
        }
        if (isset($filters['active_only']) && $filters['active_only']) {
            $conditions[] = "c.status = 'active'";
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        return [$where, $params];
    }

    public function paginate(array $filters, int $page, int $perPage = 10): array
    {
        [$where, $params] = $this->buildWhere($filters);

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars c {$where}");
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, $page), $pages);

        $sql = self::SELECT . " {$where} ORDER BY c.brand, c.model, c.id LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $name => $value) {
            $stmt->bindValue($name, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'page'  => $page,
            'pages' => $pages,
        ];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE c.id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function allActive(): array
    {
        $stmt = $this->db->query(self::SELECT . " WHERE c.status = 'active' ORDER BY c.price_day");
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $columns = implode(', ', self::FIELDS);
        $placeholders = ':' . implode(', :', self::FIELDS);

        $stmt = $this->db->prepare("INSERT INTO cars ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($this->onlyFields($data));

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $set = implode(', ', array_map(fn ($f) => "{$f} = :{$f}", self::FIELDS));
        $stmt = $this->db->prepare("UPDATE cars SET {$set} WHERE id = :id");
        $stmt->execute($this->onlyFields($data) + ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM cars WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public function updateOdometer(int $id, int $odometer): void
    {
        $stmt = $this->db->prepare('UPDATE cars SET odometer = :odometer WHERE id = :id');
        $stmt->execute([':odometer' => $odometer, ':id' => $id]);
    }

    public function countByStatus(): array
    {
        $stmt = $this->db->query("SELECT status, COUNT(*) AS cnt FROM cars GROUP BY status");
        $rows = $stmt->fetchAll();
        $result = ['active' => 0, 'inactive' => 0];
        foreach ($rows as $r) {
            $result[$r['status']] = (int) $r['cnt'];
        }
        return $result;
    }

    private function onlyFields(array $data): array
    {
        $out = [];
        foreach (self::FIELDS as $f) {
            $out[$f] = $data[$f] ?? null;
        }
        return $out;
    }
}
