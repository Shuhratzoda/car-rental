<?php
// public/api/cars/index.php — API-и МУВАҚҚАТӢ барои рӯзи 11.
// Дар оянда масири /api/cars дар front controller пайдо мешавад.
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Барои демо: агар PDO дастрас бошад, аз пойгоҳ мегирем, вагарна массив
$cars = [];

try {
    $configFile = dirname(__DIR__, 3) . '/config/config.php';
    if (!is_file($configFile)) {
        $configFile = dirname(__DIR__, 3) . '/config/config.example.php';
    }
    $config = require $configFile;
    $c = $config['db'];
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $c['host'], $c['port'], $c['name']);
    $pdo = new PDO($dsn, $c['user'], $c['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $q = trim((string) ($_GET['q'] ?? ''));
    $status = trim((string) ($_GET['status'] ?? ''));
    $tariffId = (int) ($_GET['tariff_id'] ?? 0);

    $sql = '
        SELECT c.id, c.brand, c.model, c.year, c.price_day, c.status, c.image,
               t.name AS tariff_name, t.km_per_day
        FROM cars c
        JOIN tariffs t ON t.id = c.tariff_id
        WHERE 1=1
    ';
    $params = [];

    if ($q !== '') {
        $sql .= " AND CONCAT_WS(' ', c.brand, c.model) LIKE :q";
        $params[':q'] = '%' . addcslashes($q, '%_\\') . '%';
    }
    if ($status !== '') {
        $sql .= ' AND c.status = :status';
        $params[':status'] = $status;
    }
    if ($tariffId > 0) {
        $sql .= ' AND c.tariff_id = :tid';
        $params[':tid'] = $tariffId;
    }

    $sql .= ' ORDER BY c.brand, c.model LIMIT 30';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cars = $stmt->fetchAll();
} catch (Throwable $e) {
    // Агар DB дастрас набошад — массиви демо
    $cars = [
        ['id' => 1, 'brand' => 'Toyota', 'model' => 'Camry', 'year' => 2022, 'price_day' => 60, 'status' => 'active', 'tariff_name' => 'Стандарт', 'km_per_day' => 250, 'image' => null],
        ['id' => 2, 'brand' => 'Chevrolet', 'model' => 'Cobalt', 'year' => 2021, 'price_day' => 35, 'status' => 'active', 'tariff_name' => 'Иқтисодӣ', 'km_per_day' => 200, 'image' => null],
        ['id' => 3, 'brand' => 'Hyundai', 'model' => 'Sonata', 'year' => 2023, 'price_day' => 55, 'status' => 'active', 'tariff_name' => 'Стандарт', 'km_per_day' => 250, 'image' => null],
        // Сабт барои санҷиши XSS — набояд ҳамчун HTML иҷро шавад
        ['id' => 99, 'brand' => '<img src=x onerror="alert(\'XSS\')">', 'model' => 'Test', 'year' => 2024, 'price_day' => 10, 'status' => 'active', 'tariff_name' => 'Тест', 'km_per_day' => 100, 'image' => null],
    ];

    $q = mb_strtolower(trim((string) ($_GET['q'] ?? '')));
    if ($q !== '') {
        $cars = array_values(array_filter($cars, function ($c) use ($q) {
            return str_contains(mb_strtolower($c['brand'] . ' ' . $c['model']), $q);
        }));
    }
}

echo json_encode(['items' => $cars, 'total' => count($cars)], JSON_UNESCAPED_UNICODE);
