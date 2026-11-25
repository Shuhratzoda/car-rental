CREATE DATABASE IF NOT EXISTS car_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_rental;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS rentals, bookings, cars, tariffs, users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(30),
  password VARCHAR(255) NOT NULL,
  role ENUM('client','manager','admin') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tariffs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  km_per_day INT NOT NULL DEFAULT 200,            -- меъёри масофа дар як рӯз
  extra_km_price DECIMAL(8,2) NOT NULL DEFAULT 1  -- нарх барои 1 км зиёд
) ENGINE=InnoDB;

CREATE TABLE cars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(50) NOT NULL,
  model VARCHAR(50) NOT NULL,
  year YEAR NOT NULL,
  image VARCHAR(255),
  price_day DECIMAL(10,2) NOT NULL,
  tariff_id INT NOT NULL,
  odometer INT NOT NULL DEFAULT 0,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  FOREIGN KEY (tariff_id) REFERENCES tariffs(id)
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  date_start DATE NOT NULL,
  date_end DATE NOT NULL,
  total_est DECIMAL(10,2) NOT NULL,
  status ENUM('pending','approved','rejected','issued','returned','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (car_id) REFERENCES cars(id),
  INDEX idx_car_dates (car_id, date_start, date_end)
) ENGINE=InnoDB;

CREATE TABLE rentals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL UNIQUE,
  manager_id INT NOT NULL,
  odometer_start INT NOT NULL,
  odometer_end INT NULL,
  issued_at DATETIME NOT NULL,
  returned_at DATETIME NULL,
  days INT NULL,
  km_driven INT NULL,
  base_cost DECIMAL(10,2) NULL,
  extra_cost DECIMAL(10,2) NULL,
  total DECIMAL(10,2) NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(id),
  FOREIGN KEY (manager_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- Рамзи ҳамаи корбарони намунавӣ: password
INSERT INTO users (full_name,email,phone,password,role) VALUES
('Администратор','admin@rent.tj','+992900000001','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('Менеҷер Аҳмадов','manager@rent.tj','+992900000002','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','manager'),
('Мизоҷ Каримов','client@rent.tj','+992900000003','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','client');

INSERT INTO tariffs (name,km_per_day,extra_km_price) VALUES
('Иқтисодӣ',200,1.00),('Стандарт',250,1.50),('Премиум',300,2.50);

INSERT INTO cars (brand,model,year,image,price_day,tariff_id,odometer) VALUES
('Toyota','Camry',2022,'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800',60,2,35000),
('Chevrolet','Cobalt',2021,'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800',35,1,52000),
('Hyundai','Sonata',2023,'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800',55,2,18000),
('Mercedes-Benz','E-Class',2024,'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800',130,3,9000),
('BMW','X5',2023,'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800',150,3,14000),
('Kia','Rio',2020,'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800',32,1,70000);
