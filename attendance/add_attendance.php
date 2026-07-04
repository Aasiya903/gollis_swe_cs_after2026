<?php
require_once __DIR__ . '/../db/connection.php';

$message = '';
$messageType = '';

$selectedDate = $_GET['date'] ?? ($_POST['attendance_date'] ?? date('Y-m-d'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $statuses = $_POST['status'] ?? [];   // [student_id => status]
    $remarks  = $_POST['remarks'] ?? [];  // [student_id => remarks]

    $errors = 0;
    foreach ($statuses as $studentId => $status) {
        $studentId = (int)$studentId;
        $remark = trim($remarks[$studentId] ?? '');

        // INSERT ... ON DUPLICATE KEY UPDATE lets the form be re-submitted to correct a day's record
        $stmt = mysqli_prepare($conn,
            "INSERT INTO attendance (student_id, attendance_date, status, remarks)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE status = VALUES(status), remarks = VALUES(remarks)"
        );
        mysqli_stmt_bind_param($stmt, 'isss', $studentId, $attendance_date, $status, $remark);
        if (!mysqli_stmt_execute($stmt)) {
            $errors++;
        }
        mysqli_stmt_close($stmt);
    }

    if ($errors === 0) {
        $message = 'Attendance saved for ' . htmlspecialchars($attendance_date) . '.';
        $messageType = 'success';
    } else {
        $message = 'Some records could not be saved.';
        $messageType = 'error';
    }
    $selectedDate = $attendance_date;
}

// Load students, along with any existing attendance record for the selected date
$sql = "SELECT s.id, s.full_name, s.class_level, a.status, a.remarks
        FROM students s
        LEFT JOIN attendance a ON a.student_id = s.id AND a.attendance_date = '" . mysqli_real_escape_string($conn, $selectedDate) . "'
        ORDER BY s.full_name";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Take Attendance - Madrasa Management System</title>
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
        <h1>Take Attendance</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>"><?= $message ?></div>
        <?php endif; ?>

        <form method="GET" action="add_attendance.php" style="margin-bottom:10px;">
            <label for="date">Attendance Date</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>"
                   onchange="this.form.submit()">
        </form>

        <form method="POST" action="add_attendance.php">
            <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($selectedDate) ?>">

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
                    <?php if (mysqli_num_rows($result) === 0): ?>
                        <tr><td colspan="4">No students found. Add students first.</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php $current = $row['status'] ?? 'Present'; ?>
                            <tr>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['class_level']) ?></td>
                                <td>
                                    <select name="status[<?= $row['id'] ?>]">
                                        <?php foreach (['Present','Absent','Late','Excused'] as $opt): ?>
                                            <option value="<?= $opt ?>" <?= $current === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="remarks[<?= $row['id'] ?>]"
                                           value="<?= htmlspecialchars($row['remarks'] ?? '') ?>" placeholder="optional">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Save Attendance</button>
        </form>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>

