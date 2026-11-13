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
  km_per_day INT NOT NULL DEFAULT 200,
  extra_km_price DECIMAL(8,2) NOT NULL DEFAULT 1.00
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
  FOREIGN KEY (tariff_id) REFERENCES tariffs(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_cars_brand (brand)
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
  CONSTRAINT chk_dates CHECK (date_end >= date_start),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
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
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
  FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
