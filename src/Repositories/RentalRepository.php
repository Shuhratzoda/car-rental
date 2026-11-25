<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class RentalRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO rentals (booking_id, manager_id, odometer_start, issued_at)
             VALUES (:booking_id, :manager_id, :odometer_start, NOW())'
        );
        $stmt->execute([
            ':booking_id'     => $data['booking_id'],
            ':manager_id'     => $data['manager_id'],
            ':odometer_start' => $data['odometer_start'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findByBooking(int $bookingId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM rentals WHERE booking_id = :bid');
        $stmt->execute([':bid' => $bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function complete(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE rentals SET
                odometer_end = :odometer_end,
                returned_at  = NOW(),
                days         = :days,
                km_driven    = :km_driven,
                base_cost    = :base_cost,
                extra_cost   = :extra_cost,
                total        = :total
             WHERE id = :id'
        );
        $stmt->execute([
            ':odometer_end' => $data['odometer_end'],
            ':days'         => $data['days'],
            ':km_driven'    => $data['km_driven'],
            ':base_cost'    => $data['base_cost'],
            ':extra_cost'   => $data['extra_cost'],
            ':total'        => $data['total'],
            ':id'           => $id,
        ]);
    }
}
