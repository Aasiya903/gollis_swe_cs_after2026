<?php
require_once __DIR__ . '/../db/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: view_students.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name       = trim($_POST['full_name']);
    $gender          = $_POST['gender'];
    $date_of_birth   = $_POST['date_of_birth'];
    $class_level     = trim($_POST['class_level']);
    $guardian_name   = trim($_POST['guardian_name']);
    $guardian_phone  = trim($_POST['guardian_phone']);
    $address         = trim($_POST['address']);
    $enrollment_date = $_POST['enrollment_date'];

    $stmt = mysqli_prepare($conn,
        "UPDATE students SET full_name=?, gender=?, date_of_birth=?, class_level=?, guardian_name=?, guardian_phone=?, address=?, enrollment_date=?
         WHERE id=?"
    );
    mysqli_stmt_bind_param(
        $stmt, 'ssssssssi',
        $full_name, $gender, $date_of_birth, $class_level,
        $guardian_name, $guardian_phone, $address, $enrollment_date, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header('Location: view_students.php?updated=1');
        exit;
    } else {
        $message = 'Error: ' . mysqli_error($conn);
        $messageType = 'error';
    }
    mysqli_stmt_close($stmt);
}

// Load current student data
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header('Location: view_students.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student - Madrasa Management System</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <div class="brand">Madrasa Management System</div>
    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="view_students.php">Students</a>
        <a href="../teachers/view_teachers.php">Teachers</a>
        <a href="../attendance/view_attendance.php">Attendance</a>
    </nav>
</div>

<div class="container">
    <div class="card">
        <h1>Edit Student</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_student.php?id=<?= $student['id'] ?>">
            <input type="hidden" name="id" value="<?= $student['id'] ?>">

            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>

            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>

            <label for="date_of_birth">Date of Birth *</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= $student['date_of_birth'] ?>" required>

            <label for="class_level">Class / Level *</label>
            <input type="text" id="class_level" name="class_level" value="<?= htmlspecialchars($student['class_level']) ?>" required>

            <label for="guardian_name">Guardian Name *</label>
            <input type="text" id="guardian_name" name="guardian_name" value="<?= htmlspecialchars($student['guardian_name']) ?>" required>

            <label for="guardian_phone">Guardian Phone *</label>
            <input type="tel" id="guardian_phone" name="guardian_phone" value="<?= htmlspecialchars($student['guardian_phone']) ?>" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"><?= htmlspecialchars($student['address']) ?></textarea>

            <label for="enrollment_date">Enrollment Date *</label>
            <input type="date" id="enrollment_date" name="enrollment_date" value="<?= $student['enrollment_date'] ?>" required>

            <button type="submit" class="btn">Update Student</button>
            <a href="view_students.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
        </form>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
