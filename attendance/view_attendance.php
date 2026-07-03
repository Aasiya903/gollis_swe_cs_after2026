<?php
require_once __DIR__ . '/../db/connection.php';

$selectedDate = $_GET['date'] ?? date('Y-m-d');

$sql = "SELECT a.*, s.full_name, s.class_level
        FROM attendance a
        JOIN students s ON s.id = a.student_id
        WHERE a.attendance_date = '" . mysqli_real_escape_string($conn, $selectedDate) . "'
        ORDER BY s.full_name";
$result = mysqli_query($conn, $sql);

// Summary counts for the selected date
$summary = ['Present' => 0, 'Absent' => 0, 'Late' => 0, 'Excused' => 0];
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    if (isset($summary[$row['status']])) {
        $summary[$row['status']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance - Madrasa Management System</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <div class="brand">Madrasa Management System</div>
    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../students/view_students.php">Students</a>
        <a href="../teachers/view_teachers.php">Teachers</a>
        <a href="view_attendance.php">Attendance</a>
    </nav>
</div>

<div class="container">
    <div class="card">
        <h1>Attendance Records</h1>

        <form method="GET" action="view_attendance.php" style="margin-bottom:10px;">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>"
                   onchange="this.form.submit()">
            <a href="add_attendance.php?date=<?= htmlspecialchars($selectedDate) ?>" class="btn" style="margin-top:0;float:right;">
                Take / Edit Attendance
            </a>
        </form>

        <div class="dashboard-grid" style="margin-bottom:20px;">
            <div class="stat-card">
                <div class="stat-number"><?= $summary['Present'] ?></div>
                <div class="stat-label">Present</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $summary['Absent'] ?></div>
                <div class="stat-label">Absent</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $summary['Late'] ?></div>
                <div class="stat-label">Late</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $summary['Excused'] ?></div>
                <div class="stat-label">Excused</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="4">No attendance recorded for this date yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['class_level']) ?></td>
                            <td class="status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['remarks']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
