<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\CarRepository;

final class HomeController extends BaseController
{
    public function index(): void
    {
        $carsRepo = new CarRepository();
        $bookRepo = new BookingRepository();

        $s = $_GET['s'] ?? date('Y-m-d');
        $e = $_GET['e'] ?? date('Y-m-d', strtotime('+2 days'));
        $search = isset($_GET['s'], $_GET['e']);

        $cars = $carsRepo->allActive();

        if ($search) {
            $cars = array_filter($cars, function ($c) use ($bookRepo, $s, $e) {
                return $bookRepo->isCarFree((int) $c['id'], $s, $e);
            });
        }

        $stats = $carsRepo->countByStatus();
        $bookingStats = $bookRepo->countByStatus();

        $this->render('home/index', [
            'title'         => 'Автопарк',
            'cars'          => $cars,
            's'             => $s,
            'e'             => $e,
            'search'        => $search,
            'stats'         => $stats,
            'bookingStats'  => $bookingStats,
            'latest'        => $bookRepo->latest(5),
        ]);
    }
}
