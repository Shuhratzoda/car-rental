<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Validator;
use App\Repositories\CarRepository;
use App\Repositories\TariffRepository;
use PDOException;

final class CarController extends BaseController
{
    private const PER_PAGE = 10;

    private CarRepository $cars;
    private TariffRepository $tariffs;

    public function __construct()
    {
        parent::__construct();
        $this->cars    = new CarRepository();
        $this->tariffs = new TariffRepository();
    }

    /** GET /cars — рӯйхат бо ҷустуҷӯ, филтр, саҳифабандӣ */
    public function index(): void
    {
        require_role(['admin', 'manager']);

        $filters = [
            'q'         => trim((string) ($_GET['q'] ?? '')),
            'tariff_id' => $_GET['tariff_id'] ?? '',
            'status'    => $_GET['status'] ?? '',
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $result = $this->cars->paginate($filters, $page, self::PER_PAGE);

        $this->render('cars/index', [
            'title'   => 'Мошинҳо',
            'items'   => $result['items'],
            'total'   => $result['total'],
            'page'    => $result['page'],
            'pages'   => $result['pages'],
            'filters' => $filters,
            'tariffs' => $this->tariffs->all(),
        ]);
    }

    /** GET /cars/create */
    public function create(): void
    {
        require_role('admin');
        $this->renderForm([], [], null);
    }

    /** POST /cars/create */
    public function store(): void
    {
        require_role('admin');
        [$data, $errors] = $this->validate($_POST);

        if ($errors) {
            $this->renderForm($_POST, $errors, null, 422);
            return;
        }

        $id = $this->cars->create($data);
        flash('Мошин бомуваффақият илова шуд.');
        $this->redirect('/cars/' . $id);
    }

    /** GET /cars/{id} */
    public function show(string $id): void
    {
        require_role(['admin', 'manager']);
        $car = $this->cars->find((int) $id);
        if (!$car) {
            http_response_code(404);
            $this->render('errors/error', ['title' => 'Ёфт нашуд', 'code' => 404, 'message' => 'Мошин ёфт нашуд.']);
            return;
        }
        $this->render('cars/show', ['title' => $car['brand'] . ' ' . $car['model'], 'car' => $car]);
    }

    /** GET /cars/{id}/edit */
    public function edit(string $id): void
    {
        require_role('admin');
        $car = $this->cars->find((int) $id);
        if (!$car) {
            $this->redirect('/cars');
        }
        $this->renderForm($car, [], (int) $id);
    }

    /** POST /cars/{id}/edit */
    public function update(string $id): void
    {
        require_role('admin');
        $id = (int) $id;
        $car = $this->cars->find($id);
        if (!$car) {
            $this->redirect('/cars');
        }

        [$data, $errors] = $this->validate($_POST, $id);
        if ($errors) {
            $this->renderForm($_POST + ['id' => $id], $errors, $id, 422);
            return;
        }

        $this->cars->update($id, $data);
        flash('Маълумоти мошин нав карда шуд.');
        $this->redirect('/cars/' . $id);
    }

    /** POST /cars/{id}/delete */
    public function delete(string $id): void
    {
        require_role('admin');
        $id = (int) $id;
        try {
            $this->cars->delete($id);
            flash('Мошин нест карда шуд.');
        } catch (PDOException $e) {
            // 1451 — калиди берунӣ (бандҳо вуҷуд доранд)
            if (($e->errorInfo[1] ?? null) === 1451) {
                flash('Нест кардан мумкин нест: бандҳо ба ин мошин вобастаанд.');
                $this->redirect('/cars/' . $id);
            }
            throw $e;
        }
        $this->redirect('/cars');
    }

    private function validate(array $input, ?int $id = null): array
    {
        $v = (new Validator($input))
            ->required('brand', 'Бренд ҳатмист')
            ->maxLength('brand', 50)
            ->required('model', 'Модел ҳатмист')
            ->maxLength('model', 50)
            ->required('year')
            ->numeric('year')
            ->min('year', 1990)
            ->required('price_day')
            ->numeric('price_day')
            ->min('price_day', 1)
            ->required('tariff_id')
            ->in('tariff_id', array_map('strval', $this->tariffs->ids()), 'Тарифи нодуруст')
            ->required('status')
            ->in('status', ['active', 'inactive'], 'Статуси нодуруст')
            ->numeric('odometer')
            ->min('odometer', 0);

        if ($v->fails()) {
            return [$input, $v->errors()];
        }

        $data = [
            'brand'      => trim($input['brand']),
            'model'      => trim($input['model']),
            'year'       => (int) $input['year'],
            'image'      => trim($input['image'] ?? '') ?: null,
            'price_day'  => (float) $input['price_day'],
            'tariff_id'  => (int) $input['tariff_id'],
            'odometer'   => (int) ($input['odometer'] ?? 0),
            'status'     => $input['status'],
        ];

        return [$data, []];
    }

    private function renderForm(array $data, array $errors, ?int $id, int $status = 200): void
    {
        $this->render('cars/form', [
            'title'   => $id ? 'Таҳрири мошин' : 'Мошини нав',
            'data'    => $data,
            'errors'  => $errors,
            'id'      => $id,
            'tariffs' => $this->tariffs->all(),
        ], $status);
    }
}
