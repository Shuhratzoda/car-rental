USE car_rental;
SET NAMES utf8mb4;

-- 1. Рӯйхати автомобилҳо бо тариф (JOIN 3 таблиц: cars, tariffs) с фильтром по бренду (LIKE) (ФТ-02)
SELECT c.id, c.brand, c.model, c.price_day, t.name AS tariff_name, c.status
FROM cars c
JOIN tariffs t ON t.id = c.tariff_id
WHERE c.brand LIKE 'T%'
ORDER BY c.price_day DESC;

-- 2. Статистика автопарка по тарифам (GROUP BY + COUNT) (ФТ-08)
SELECT t.name AS tariff_name, COUNT(c.id) AS total_cars, AVG(c.price_day) AS avg_price
FROM tariffs t
LEFT JOIN cars c ON c.tariff_id = t.id
GROUP BY t.id, t.name;

-- 3. Список всех бронирований с ФИО клиентов и моделью авто (LEFT JOIN) (ФТ-03)
SELECT b.id, u.full_name AS client_name, CONCAT(c.brand, ' ', c.model) AS car_model, b.date_start, b.date_end, b.status
FROM bookings b
JOIN users u ON u.id = b.user_id
JOIN cars c ON c.id = b.car_id;

-- 4. Выборка бронирований по статусу (ФТ-05)
SELECT id, date_start, date_end, total_est, status
FROM bookings
WHERE status = 'approved';

-- 5. Пагинация списка автомобилей (LIMIT / OFFSET) (ФТ-02)
SELECT id, brand, model, price_day, odometer
FROM cars
ORDER BY id
LIMIT 3 OFFSET 0;

-- 6. Проверка ограничений (CHECK constraint)
-- Ожидается ошибка ERROR 4025, так как date_end раньше date_start:
-- INSERT INTO bookings (user_id, car_id, date_start, date_end, total_est, status) 
-- VALUES (3, 1, '2026-11-20', '2026-11-18', 100.00, 'pending');
