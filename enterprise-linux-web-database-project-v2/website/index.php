<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$pageTitle = 'Dashboard';

$stats = [
    'students' => 0,
    'courses' => 0,
    'cities' => 0,
];

$result = $conn->query("SELECT COUNT(*) AS total FROM students");
$stats['students'] = (int)$result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(DISTINCT course) AS total FROM students WHERE course <> ''");
$stats['courses'] = (int)$result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(DISTINCT city) AS total FROM students WHERE city <> ''");
$stats['cities'] = (int)$result->fetch_assoc()['total'];

$recent = $conn->query(
    "SELECT id, name, email, course, city, created_at
     FROM students ORDER BY created_at DESC, id DESC LIMIT 5"
);

require __DIR__ . '/partials/header.php';
?>
<section class="hero">
    <div>
        <p class="eyebrow">AWS EC2 • LINUX • LAMP</p>
        <h1>Student Management Portal</h1>
        <p class="hero-copy">A production-style practice environment for Linux administration, web services, databases, automation and troubleshooting.</p>
        <div class="actions">
            <a class="btn primary" href="/add.php">+ Add Student</a>
            <a class="btn ghost" href="/students.php">Manage Students</a>
        </div>
    </div>
    <div class="hero-terminal">
        <div class="terminal-head"><span></span><span></span><span></span><b>server-health</b></div>
        <pre><code>$ systemctl is-active httpd
active
$ systemctl is-active mariadb
active
$ ss -lnt | grep -E ':80|:3306'
LISTEN :80
LISTEN :3306</code></pre>
    </div>
</section>

<section class="stats">
    <article class="stat-card">
        <span class="stat-icon">👥</span>
        <div><small>Total Students</small><strong><?= $stats['students'] ?></strong></div>
    </article>
    <article class="stat-card">
        <span class="stat-icon">◈</span>
        <div><small>Courses</small><strong><?= $stats['courses'] ?></strong></div>
    </article>
    <article class="stat-card">
        <span class="stat-icon">⌖</span>
        <div><small>Cities</small><strong><?= $stats['cities'] ?></strong></div>
    </article>
</section>

<section class="panel">
    <div class="panel-head">
        <div>
            <p class="eyebrow">DATABASE</p>
            <h2>Recent Students</h2>
        </div>
        <a class="text-link" href="/students.php">View all →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Course</th><th>City</th><th>Added</th></tr></thead>
            <tbody>
            <?php while ($row = $recent->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><span class="pill"><?= htmlspecialchars($row['course']) ?></span></td>
                    <td><?= htmlspecialchars($row['city'] ?: '—') ?></td>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
