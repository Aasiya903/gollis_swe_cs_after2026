<?php
require_once __DIR__ . '/../db/connection.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $gender    = $_POST['gender'];
    $subject   = trim($_POST['subject']);
    $phone     = trim($_POST['phone']);
    $email     = trim($_POST['email']);
    $hire_date = $_POST['hire_date'];

    if ($full_name === '' || $subject === '' || $phone === '') {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO teachers (full_name, gender, subject, phone, email, hire_date)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, 'ssssss', $full_name, $gender, $subject, $phone, $email, $hire_date);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Teacher added successfully.';
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
<title>Add Teacher - Madrasa Management System</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <div class="brand">Madrasa Management System</div>
    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../students/view_students.php">Students</a>
        <a href="view_teachers.php">Teachers</a>
        <a href="../attendance/view_attendance.php">Attendance</a>
    </nav>
</div>

<div class="container">
    <div class="card">
        <h1>Add New Teacher</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="add_teacher.php">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="subject">Subject *</label>
            <input type="text" id="subject" name="subject" placeholder="e.g. Quran, Arabic" required>

            <label for="phone">Phone *</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="hire_date">Hire Date *</label>
            <input type="date" id="hire_date" name="hire_date" value="<?= date('Y-m-d') ?>" required>

            <button type="submit" class="btn">Save Teacher</button>
            <a href="view_teachers.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
        </form>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
