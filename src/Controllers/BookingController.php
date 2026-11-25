<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Validator;
use App\Repositories\BookingRepository;
use App\Repositories\CarRepository;

final class BookingController extends BaseController
{
    private BookingRepository $bookings;
    private CarRepository $cars;

    public function __construct()
    {
        parent::__construct();
        $this->bookings = new BookingRepository();
        $this->cars     = new CarRepository();
    }

    /** GET /book/{carId} */
    public function createForm(string $carId): void
    {
        require_role('client');
        $car = $this->cars->find((int) $carId);
        if (!$car || $car['status'] !== 'active') {
            flash('Мошин дастрас нест.');
            $this->redirect('/');
        }

        $s = $_GET['s'] ?? date('Y-m-d');
        $e = $_GET['e'] ?? date('Y-m-d', strtotime('+2 days'));

        $this->render('bookings/form', [
            'title' => 'Банд кардан',
            'car'   => $car,
            's'     => $s,
            'e'     => $e,
        ]);
    }

    /** POST /book/{carId} */
    public function store(string $carId): void
    {
        require_role('client');
        $car = $this->cars->find((int) $carId);
        if (!$car || $car['status'] !== 'active') {
            $this->redirect('/');
        }

        $v = (new Validator($_POST))
            ->required('date_start')
            ->date('date_start')
            ->required('date_end')
            ->date('date_end');

        if ($v->fails()) {
            $this->render('bookings/form', [
                'title'  => 'Банд кардан',
                'car'    => $car,
                's'      => $_POST['date_start'] ?? '',
                'e'      => $_POST['date_end'] ?? '',
                'errors' => $v->errors(),
            ], 422);
            return;
        }

        $s = $_POST['date_start'];
        $e = $_POST['date_end'];

        if ($s < date('Y-m-d') || $e < $s) {
            flash('Санаҳо нодурустанд.');
            $this->redirect('/book/' . $carId . '?s=' . urlencode($s) . '&e=' . urlencode($e));
        }

        if (!$this->bookings->isCarFree((int) $carId, $s, $e)) {
            flash('Мошин дар ин санаҳо банд аст! Санаҳои дигарро интихоб кунед.');
            $this->redirect('/book/' . $carId . '?s=' . urlencode($s) . '&e=' . urlencode($e));
        }

        $d = days($s, $e);
        $this->bookings->create([
            'user_id'    => current_user()['id'],
            'car_id'     => (int) $carId,
            'date_start' => $s,
            'date_end'   => $e,
            'total_est'  => $d * (float) $car['price_day'],
            'status'     => 'pending',
        ]);

        flash('Банд қабул шуд ва ба тасдиқи менеҷер фиристода шуд ✔');
        $this->redirect('/my');
    }

    /** GET /my */
    public function my(): void
    {
        require_role('client');
        $rows = $this->bookings->forUser((int) current_user()['id']);
        $this->render('bookings/my', [
            'title' => 'Бандҳои ман',
            'rows'  => $rows,
        ]);
    }

    /** POST /my/cancel */
    public function cancel(): void
    {
        require_role('client');
        $id = (int) ($_POST['id'] ?? 0);
        $booking = $this->bookings->find($id);
        if ($booking && (int) $booking['user_id'] === (int) current_user()['id']
            && in_array($booking['status'], ['pending', 'approved'], true)) {
            $this->bookings->updateStatus($id, 'cancelled');
            flash('Банд бекор шуд.');
        }
        $this->redirect('/my');
    }
}
