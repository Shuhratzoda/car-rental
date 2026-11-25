<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class TariffRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT id, name, km_per_day, extra_km_price FROM tariffs ORDER BY id')->fetchAll();
    }

    public function ids(): array
    {
        return $this->db->query('SELECT id FROM tariffs')->fetchAll(PDO::FETCH_COLUMN);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tariffs WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
