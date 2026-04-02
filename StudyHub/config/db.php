<?php
// Database configuration for XAMPP MySQL (college mini project)
// Using mysqli instead of PDO to be more reliable with XAMPP on Windows.

$db = 'membership_engine';
$rootUser = 'root';
$rootPass = ''; // change if your MySQL root has a password
$userCandidates = [
    ['user' => $rootUser, 'pass' => $rootPass],
    ['user' => 'pma', 'pass' => ''], // phpMyAdmin control user on XAMPP
];
$charset = 'utf8mb4';

$isCli = (php_sapi_name() === 'cli');
if ($isCli) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

$mysqli = null;
$lastError = '';

$candidates = [
    ['host' => 'localhost', 'port' => null],
    ['host' => '127.0.0.1', 'port' => 3307],
];

foreach ($candidates as $c) {
    try {
        if ($isCli) {
            echo 'Trying MySQL connect: host=' . $c['host'] . ($c['port'] ? (':port=' . $c['port']) : ' (no port)') . '...' . PHP_EOL;
            if (function_exists('flush')) @flush();
        }

        $conn = mysqli_init();
        if ($conn) {
            // Prevent long blocking during connection attempts.
            @$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);
        }

        foreach ($userCandidates as $u) {
            if ($c['port']) {
                $connected = $conn ? @$conn->real_connect($c['host'], $u['user'], $u['pass'], '', (int) $c['port']) : false;
            } else {
                $connected = $conn ? @$conn->real_connect($c['host'], $u['user'], $u['pass'], '') : false;
            }

            if ($connected && $conn->connect_errno === 0) {
                $mysqli = $conn;
                if ($isCli) {
                    echo 'MySQL connected successfully as user: ' . $u['user'] . PHP_EOL;
                    if (function_exists('flush')) @flush();
                }
                break 2; // break both loops
            }

            $lastError = $conn ? $conn->connect_error : 'Unknown connection error';
        }

        if ($isCli) {
            echo 'MySQL connect failed: ' . ($lastError ?: 'Unknown') . PHP_EOL;
            if (function_exists('flush')) @flush();
        }
    } catch (Throwable $e) {
        $lastError = $e->getMessage();
    }
}

if (!$mysqli || $mysqli->connect_errno !== 0) {
    die('Database connection failed: ' . htmlspecialchars($lastError ?: 'Unknown error'));
}

$mysqli->set_charset($charset);

// Ensure DB exists
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($db);

// Create required tables
$mysqli->query("
  CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    plan ENUM('Free', 'Basic', 'Premium') NOT NULL DEFAULT 'Free',
    expiry_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB
");

$mysqli->query("
  CREATE TABLE IF NOT EXISTS plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    plan_name ENUM('Free', 'Basic', 'Premium') NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    features TEXT NOT NULL
  ) ENGINE=InnoDB
");

// Seed plans
$mysqli->query("
  INSERT INTO plans (plan_name, price, features) VALUES
    ('Free', 0.00, 'Limited access to sample videos,Basic practice material'),
    ('Basic', 199.00, 'All Free features,More course videos,Topic-wise tests'),
    ('Premium', 399.00, 'All Basic features,Premium-only courses,Full mock tests')
  ON DUPLICATE KEY UPDATE
    price = VALUES(price),
    features = VALUES(features)
");

