<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$isEdit = isset($student);
$errors = [];

$name = $student['name'] ?? '';
$email = $student['email'] ?? '';
$course = $student['course'] ?? '';
$city = $student['city'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $city = trim($_POST['city'] ?? '');

    if ($name === '' || strlen($name) < 2) $errors[] = 'Name must contain at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if ($course === '') $errors[] = 'Course is required.';

    if (!$errors) {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE students SET name=?, email=?, course=?, city=? WHERE id=?");
            $stmt->bind_param('ssssi', $name, $email, $course, $city, $student['id']);
        } else {
            $stmt = $conn->prepare("INSERT INTO students (name, email, course, city) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $name, $email, $course, $city);
        }

        if ($stmt->execute()) {
            header('Location: /students.php');
            exit;
        }
        $errors[] = 'Unable to save the record. Check the Apache error log.';
    }
}

$pageTitle = 'Add Student';
require __DIR__ . '/partials/header.php';
?>
<section class="form-page">
    <div class="form-card">
        <p class="eyebrow">CREATE RECORD</p>
        <h1>Add Student</h1>
        <p class="muted">Create a new record in the student_portal database.</p>
        <?php if ($errors): ?><div class="alert"><?= htmlspecialchars(implode(' ', $errors)) ?></div><?php endif; ?>
        <form method="post" class="student-form">
            <label>Name<input name="name" value="<?= htmlspecialchars($name) ?>" required></label>
            <label>Email<input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></label>
            <label>Course<input name="course" value="<?= htmlspecialchars($course) ?>" placeholder="Cloud Computing" required></label>
            <label>City<input name="city" value="<?= htmlspecialchars($city) ?>" placeholder="Pune"></label>
            <div class="form-actions"><a class="btn ghost" href="/students.php">Cancel</a><button class="btn primary" type="submit">Create Student</button></div>
        </form>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
