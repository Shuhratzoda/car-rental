<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class BookingRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    public function find(int $id): ?array
    {
        $sql = '
            SELECT b.*,
                   u.full_name, u.phone, u.email,
                   c.brand, c.model, c.odometer, c.price_day,
                   t.name AS tariff_name, t.km_per_day, t.extra_km_price
            FROM bookings b
            JOIN users u ON u.id = b.user_id
            JOIN cars c ON c.id = b.car_id
            JOIN tariffs t ON t.id = c.tariff_id
            WHERE b.id = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO bookings (user_id, car_id, date_start, date_end, total_est, status)
             VALUES (:user_id, :car_id, :date_start, :date_end, :total_est, :status)'
        );
        $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':car_id'     => $data['car_id'],
            ':date_start' => $data['date_start'],
            ':date_end'   => $data['date_end'],
            ':total_est'  => $data['total_est'],
            ':status'     => $data['status'] ?? 'pending',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE bookings SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function isCarFree(int $carId, string $start, string $end, int $ignoreId = 0): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM bookings
             WHERE car_id = :car_id
               AND status IN ('pending','approved','issued')
               AND date_start <= :end AND date_end >= :start
               AND id <> :ignore"
        );
        $stmt->execute([
            ':car_id'  => $carId,
            ':end'     => $end,
            ':start'   => $start,
            ':ignore'  => $ignoreId,
        ]);
        return (int) $stmt->fetchColumn() === 0;
    }

    public function forUser(int $userId): array
    {
        $sql = '
            SELECT b.*, c.brand, c.model, r.total, r.km_driven
            FROM bookings b
            JOIN cars c ON c.id = b.car_id
            LEFT JOIN rentals r ON r.booking_id = b.id
            WHERE b.user_id = :uid
            ORDER BY b.id DESC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function byStatus(string $status): array
    {
        $sql = '
            SELECT b.*, u.full_name, u.phone, c.brand, c.model, c.odometer
            FROM bookings b
            JOIN users u ON u.id = b.user_id
            JOIN cars c ON c.id = b.car_id
            WHERE b.status = :status
            ORDER BY b.created_at DESC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll();
    }

    public function countByStatus(): array
    {
        $stmt = $this->db->query('SELECT status, COUNT(*) AS cnt FROM bookings GROUP BY status');
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $r) {
            $result[$r['status']] = (int) $r['cnt'];
        }
        return $result;
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT b.id, b.status, b.date_start, b.date_end, c.brand, c.model, u.full_name
             FROM bookings b
             JOIN cars c ON c.id = b.car_id
             JOIN users u ON u.id = b.user_id
             ORDER BY b.id DESC LIMIT :lim'
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Ҳисобот: даромад аз рӯи мошинҳо */
    public function incomeReport(?string $from = null, ?string $to = null): array
    {
        $params = [];
        $dateCond = '';
        if ($from && $to) {
            $dateCond = ' AND DATE(r.returned_at) BETWEEN :from AND :to';
            $params[':from'] = $from;
            $params[':to']   = $to;
        }

        $sql = "
            SELECT c.id, c.brand, c.model,
                   COUNT(r.id) AS n,
                   COALESCE(SUM(r.extra_cost), 0) AS ex,
                   COALESCE(SUM(r.total), 0) AS sum_total
            FROM cars c
            LEFT JOIN bookings b ON b.car_id = c.id
            LEFT JOIN rentals r ON r.booking_id = b.id AND r.returned_at IS NOT NULL {$dateCond}
            GROUP BY c.id, c.brand, c.model
            HAVING sum_total > 0
            ORDER BY sum_total DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
