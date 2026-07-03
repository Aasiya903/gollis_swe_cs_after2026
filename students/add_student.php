<?php
require_once __DIR__ . '/../db/connection.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name']);
    $gender         = $_POST['gender'];
    $date_of_birth  = $_POST['date_of_birth'];
    $class_level    = trim($_POST['class_level']);
    $guardian_name  = trim($_POST['guardian_name']);
    $guardian_phone = trim($_POST['guardian_phone']);
    $address        = trim($_POST['address']);
    $enrollment_date = $_POST['enrollment_date'];

    if ($full_name === '' || $guardian_name === '' || $guardian_phone === '') {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO students (full_name, gender, date_of_birth, class_level, guardian_name, guardian_phone, address, enrollment_date)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt, 'ssssssss',
            $full_name, $gender, $date_of_birth, $class_level,
            $guardian_name, $guardian_phone, $address, $enrollment_date
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Student added successfully.';
            $messageType = 'success';
        } else {
            $message = 'Error: ' . mysqli_error($conn);
            $messageType = 'error';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student - Madrasa Management System</title>
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
        <h1>Add New Student</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="add_student.php">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="date_of_birth">Date of Birth *</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>

            <label for="class_level">Class / Level *</label>
            <input type="text" id="class_level" name="class_level" placeholder="e.g. Grade 5" required>

            <label for="guardian_name">Guardian Name *</label>
            <input type="text" id="guardian_name" name="guardian_name" required>

            <label for="guardian_phone">Guardian Phone *</label>
            <input type="tel" id="guardian_phone" name="guardian_phone" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"></textarea>

            <label for="enrollment_date">Enrollment Date *</label>
            <input type="date" id="enrollment_date" name="enrollment_date" value="<?= date('Y-m-d') ?>" required>

            <button type="submit" class="btn">Save Student</button>
            <a href="view_students.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
        </form>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
