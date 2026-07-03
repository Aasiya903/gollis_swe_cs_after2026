<?php
require_once __DIR__ . '/db/connection.php';

// Counts for the stat cards
$studentCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM students"))[0];
$teacherCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM teachers"))[0];

$today = date('Y-m-d');
$presentToday = mysqli_fetch_row(mysqli_query(
    $conn,
    "SELECT COUNT(*) FROM attendance WHERE attendance_date = '$today' AND status = 'Present'"
))[0];
$absentToday = mysqli_fetch_row(mysqli_query(
    $conn,
    "SELECT COUNT(*) FROM attendance WHERE attendance_date = '$today' AND status = 'Absent'"
))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - Madrasa Management System</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <div class="brand">Madrasa Management System</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="students/view_students.php">Students</a>
        <a href="teachers/view_teachers.php">Teachers</a>
        <a href="attendance/view_attendance.php">Attendance</a>
    </nav>
</div>

<div class="container">
    <h1>Welcome back</h1>
    <p>Here is a quick overview of the madrasa today.</p>

    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-number"><?= (int)$studentCount ?></div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= (int)$teacherCount ?></div>
            <div class="stat-label">Total Teachers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= (int)$presentToday ?></div>
            <div class="stat-label">Present Today</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= (int)$absentToday ?></div>
            <div class="stat-label">Absent Today</div>
        </div>
    </div>

    <div class="card">
        <h2>Quick Links</h2>
        <div class="menu-links">
            <a href="students/add_student.php">+ Add Student</a>
            <a href="students/view_students.php">View Students</a>
            <a href="teachers/add_teacher.php">+ Add Teacher</a>
            <a href="teachers/view_teachers.php">View Teachers</a>
            <a href="attendance/add_attendance.php">+ Take Attendance</a>
            <a href="attendance/view_attendance.php">View Attendance</a>
        </div>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
