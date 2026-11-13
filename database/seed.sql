USE car_rental;
SET NAMES utf8mb4;

-- Пароль ҳамаи корбарон: password
INSERT INTO users (full_name,email,phone,password,role) VALUES
('Администратор Системный','admin@rent.tj','+992900000001','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('Менеҷер Аҳмадов Бобораҷаб','manager@rent.tj','+992900000002','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','manager'),
('Мизоҷ Каримов Далерҷон','client@rent.tj','+992900000003','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','client'),
('Мизоҷ Саидова Манижа','client2@rent.tj','+992900000004','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','client');

INSERT INTO tariffs (name,km_per_day,extra_km_price) VALUES
('Иқтисодӣ',200,1.00),('Стандарт',250,1.50),('Премиум',300,2.50);

INSERT INTO cars (brand,model,year,image,price_day,tariff_id,odometer) VALUES
('Toyota','Camry',2022,'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800',60.00,2,35000),
('Chevrolet','Cobalt',2021,'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800',35.00,1,52000),
('Hyundai','Sonata',2023,'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800',55.00,2,18000),
('Mercedes-Benz','E-Class',2024,'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800',130.00,3,9000),
('BMW','X5',2023,'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800',150.00,3,14000),
('Kia','Rio',2020,'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800',32.00,1,70000);

INSERT INTO bookings (user_id,car_id,date_start,date_end,total_est,status) VALUES
(3,1,'2026-11-15','2026-11-18',180.00,'approved'),
(4,2,'2026-11-16','2026-11-19',105.00,'pending');
