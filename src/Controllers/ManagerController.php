<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\CarRepository;
use App\Repositories\RentalRepository;

final class ManagerController extends BaseController
{
    private BookingRepository $bookings;
    private RentalRepository $rentals;
    private CarRepository $cars;

    public function __construct()
    {
        parent::__construct();
        $this->bookings = new BookingRepository();
        $this->rentals  = new RentalRepository();
        $this->cars     = new CarRepository();
    }

    public function index(): void
    {
        require_role(['manager', 'admin']);
        $tab = $_GET['t'] ?? 'pending';
        $allowed = ['pending', 'approved', 'issued', 'returned', 'rejected', 'cancelled'];
        if (!in_array($tab, $allowed, true)) {
            $tab = 'pending';
        }

        $rows = $this->bookings->byStatus($tab);
        $counts = $this->bookings->countByStatus();

        $this->render('manager/index', [
            'title'  => 'Менеҷер',
            'tab'    => $tab,
            'rows'   => $rows,
            'counts' => $counts,
        ]);
    }

    public function action(): void
    {
        require_role(['manager', 'admin']);
        $id = (int) ($_POST['id'] ?? 0);
        $a  = $_POST['a'] ?? '';
        $b  = $this->bookings->find($id);
        if (!$b) {
            $this->redirect('/manager');
        }

        if ($a === 'approve' && $b['status'] === 'pending') {
            if ($this->bookings->isCarFree((int) $b['car_id'], $b['date_start'], $b['date_end'], $id)) {
                $this->bookings->updateStatus($id, 'approved');
                flash('Банд тасдиқ шуд ✔');
            } else {
                flash('Нест мешавад: санаҳо бо банди дигар мегузаранд.');
            }
        } elseif ($a === 'reject' && $b['status'] === 'pending') {
            $this->bookings->updateStatus($id, 'rejected');
            flash('Банд рад шуд.');
        } elseif ($a === 'issue' && $b['status'] === 'approved') {
            $odo = (int) ($_POST['odo'] ?? 0);
            $this->rentals->create([
                'booking_id'     => $id,
                'manager_id'     => current_user()['id'],
                'odometer_start' => $odo,
            ]);
            $this->bookings->updateStatus($id, 'issued');
            flash('Мошин ба мизоҷ дода шуд.');
        } elseif ($a === 'receive' && $b['status'] === 'issued') {
            $r = $this->rentals->findByBooking($id);
            $end = (int) ($_POST['odo'] ?? 0);
            if ($end < (int) $r['odometer_start']) {
                flash('Нишондоди нав аз аввал хурдтар буда наметавонад!');
            } else {
                $car = $this->cars->find((int) $b['car_id']);
                $d   = days($b['date_start'], $b['date_end']);
                $km  = $end - (int) $r['odometer_start'];
                $c   = calc($car, $d, $km);

                $this->rentals->complete((int) $r['id'], [
                    'odometer_end' => $end,
                    'days'         => $d,
                    'km_driven'    => $km,
                    'base_cost'    => $c['base'],
                    'extra_cost'   => $c['extra'],
                    'total'        => $c['total'],
                ]);
                $this->bookings->updateStatus($id, 'returned');
                $this->cars->updateOdometer((int) $car['id'], $end);
                flash("Қабул шуд. Ҳисоби ниҳоӣ: {$c['total']} $ (асосӣ {$c['base']} + иловагӣ {$c['extra']} барои {$c['extra_km']} км)");
            }
        }

        $this->redirect('/manager?t=' . ($b['status'] === 'pending' ? 'pending' : $a === 'approve' ? 'approved' : 'issued'));
    }
}
