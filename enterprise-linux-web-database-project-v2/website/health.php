<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/db.php';

$result = $conn->query("SELECT 1 AS ok");
$dbOk = $result && (int)$result->fetch_assoc()['ok'] === 1;

http_response_code($dbOk ? 200 : 503);

echo json_encode([
    'status' => $dbOk ? 'healthy' : 'unhealthy',
    'service' => 'student-portal',
    'database' => $dbOk ? 'reachable' : 'unreachable',
    'timestamp' => gmdate('c')
], JSON_PRETTY_PRINT);
