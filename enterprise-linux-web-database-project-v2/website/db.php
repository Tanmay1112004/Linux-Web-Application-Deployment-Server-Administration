<?php
declare(strict_types=1);

$configPath = '/etc/student-portal/config.php';

if (!is_readable($configPath)) {
    http_response_code(500);
    exit('Application configuration is unavailable.');
}

$config = require $configPath;

$conn = new mysqli(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database'],
    (int)$config['port']
);

if ($conn->connect_errno) {
    error_log('Student Portal DB connection failed: ' . $conn->connect_error);
    http_response_code(503);
    exit('Database service is temporarily unavailable.');
}

$conn->set_charset('utf8mb4');
