<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Student Portal';
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Student Management Portal running on Linux, Apache, PHP and MariaDB.">
    <meta name="referrer" content="same-origin">
    <title><?= htmlspecialchars($pageTitle) ?> · DevOps Lab</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand">
        <div class="brand-mark">LP</div>
        <div>
            <strong>Linux Portal</strong>
            <span>DevOps Administration Lab</span>
        </div>
    </div>
    <nav>
        <a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="/">Dashboard</a>
        <a class="<?= $currentPage === 'students.php' ? 'active' : '' ?>" href="/students.php">Students</a>
        <a href="/health.php">Health</a>
    </nav>
</header>
<main class="container">
