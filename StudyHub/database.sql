-- StudyHub Membership Engine - MySQL schema
-- Run this script in phpMyAdmin (XAMPP) inside a database named `membership_engine`

CREATE DATABASE IF NOT EXISTS membership_engine
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE membership_engine;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  plan ENUM('Free', 'Basic', 'Premium') NOT NULL DEFAULT 'Free',
  expiry_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Plans table
CREATE TABLE IF NOT EXISTS plans (
  plan_id INT AUTO_INCREMENT PRIMARY KEY,
  plan_name ENUM('Free', 'Basic', 'Premium') NOT NULL UNIQUE,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  features TEXT NOT NULL
) ENGINE=InnoDB;

-- Seed plans
INSERT INTO plans (plan_name, price, features) VALUES
  ('Free', 0.00, 'Limited access to sample videos,Basic practice material'),
  ('Basic', 199.00, 'All Free features,More course videos,Topic-wise tests'),
  ('Premium', 399.00, 'All Basic features,Premium-only courses,Full mock tests')
ON DUPLICATE KEY UPDATE
  price = VALUES(price),
  features = VALUES(features);

