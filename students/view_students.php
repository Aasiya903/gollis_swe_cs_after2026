<?php
require_once __DIR__ . '/../db/connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $like = '%' . mysqli_real_escape_string($conn, $search) . '%';
    $sql = "SELECT * FROM students WHERE full_name LIKE '$like' OR class_level LIKE '$like' ORDER BY full_name";
} else {
    $sql = "SELECT * FROM students ORDER BY full_name";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students - Madrasa Management System</title>
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
        <h1>Students</h1>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Student deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Student updated successfully.</div>
        <?php endif; ?>

        <form method="GET" action="view_students.php" style="margin-bottom:18px;">
            <input type="text" name="search" placeholder="Search by name or class..."
                   value="<?= htmlspecialchars($search) ?>" style="max-width:300px;display:inline-block;">
            <button type="submit" class="btn" style="margin-top:0;">Search</button>
            <a href="add_student.php" class="btn" style="margin-top:0;float:right;">+ Add Student</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Class</th>
                    <th>Guardian</th>
                    <th>Phone</th>
                    <th>Enrolled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="8">No students found.</td></tr>
                <?php else: ?>
                    <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['class_level']) ?></td>
                            <td><?= htmlspecialchars($row['guardian_name']) ?></td>
                            <td><?= htmlspecialchars($row['guardian_phone']) ?></td>
                            <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                            <td class="actions">
                                <a class="edit" href="edit_student.php?id=<?= $row['id'] ?>">Edit</a>
                                <a class="delete" href="delete_student.php?id=<?= $row['id'] ?>"
                                   onclick="return confirm('Delete this student?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="footer">Madrasa Management System</div>

</body>
</html>
