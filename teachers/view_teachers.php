<?php
require_once __DIR__ . '/../db/connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $like = '%' . mysqli_real_escape_string($conn, $search) . '%';
    $sql = "SELECT * FROM teachers WHERE full_name LIKE '$like' OR subject LIKE '$like' ORDER BY full_name";
} else {
    $sql = "SELECT * FROM teachers ORDER BY full_name";
}

$result = mysqli_query($conn, $sql);

// Simple delete handling (kept inline since delete_teacher.php is not part of the requested file list)
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM teachers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $delId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: view_teachers.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teachers - Madrasa Management System</title>
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
        <h1>Teachers</h1>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Teacher deleted successfully.</div>
        <?php endif; ?>

        <form method="GET" action="view_teachers.php" style="margin-bottom:18px;">
            <input type="text" name="search" placeholder="Search by name or subject..."
                   value="<?= htmlspecialchars($search) ?>" style="max-width:300px;display:inline-block;">
            <button type="submit" class="btn" style="margin-top:0;">Search</button>
            <a href="add_teacher.php" class="btn" style="margin-top:0;float:right;">+ Add Teacher</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Subject</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Hired</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="8">No teachers found.</td></tr>
                <?php else: ?>
                    <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['subject']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['hire_date']) ?></td>
                            <td class="actions">
                                <a class="delete" href="view_teachers.php?delete_id=<?= $row['id'] ?>"
                                   onclick="return confirm('Delete this teacher?');">Delete</a>
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
