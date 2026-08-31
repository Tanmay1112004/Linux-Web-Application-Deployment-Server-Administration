<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$pageTitle = 'Students';
$search = trim($_GET['search'] ?? '');
$course = trim($_GET['course'] ?? '');

$courses = [];
$courseResult = $conn->query("SELECT DISTINCT course FROM students WHERE course <> '' ORDER BY course");
while ($row = $courseResult->fetch_assoc()) {
    $courses[] = $row['course'];
}

$sql = "SELECT id, name, email, course, city, created_at FROM students WHERE 1=1";
$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR city LIKE ?)";
    $term = "%{$search}%";
    $params = [$term, $term, $term];
    $types = 'sss';
}
if ($course !== '') {
    $sql .= " AND course = ?";
    $params[] = $course;
    $types .= 's';
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

require __DIR__ . '/partials/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">CRUD MANAGEMENT</p>
        <h1>Students</h1>
        <p>Search, filter and manage records stored in MariaDB.</p>
    </div>
    <a class="btn primary" href="/add.php">+ Add Student</a>
</section>

<form class="filter-bar" method="get">
    <input type="search" name="search" placeholder="Search name, email or city..." value="<?= htmlspecialchars($search) ?>">
    <select name="course">
        <option value="">All courses</option>
        <?php foreach ($courses as $item): ?>
            <option value="<?= htmlspecialchars($item) ?>" <?= $course === $item ? 'selected' : '' ?>>
                <?= htmlspecialchars($item) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button class="btn secondary" type="submit">Filter</button>
    <a class="btn ghost" href="/students.php">Reset</a>
</form>

<section class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>ID</th><th>Student</th><th>Course</th><th>City</th><th>Added</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="6" class="empty">No students found.</td></tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= (int)$row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['name']) ?></strong><br><span class="muted"><?= htmlspecialchars($row['email']) ?></span></td>
                        <td><span class="pill"><?= htmlspecialchars($row['course']) ?></span></td>
                        <td><?= htmlspecialchars($row['city'] ?: '—') ?></td>
                        <td><?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?></td>
                        <td class="actions-cell">
                            <a class="icon-link" href="/edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
                            <form method="post" action="/delete.php" class="inline-form" data-confirm="Delete this student?">
                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                <button class="danger-link" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
