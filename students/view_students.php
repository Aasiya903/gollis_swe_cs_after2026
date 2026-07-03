<?php
$login_path = "../auth/login.php";
require_once __DIR__ . '/../db/connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search !== "") {
    $like = "%" . $search . "%";
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM students WHERE full_name LIKE ? OR roll_no LIKE ? OR class_name LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students - Madrasa System</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <div>
        <h1>Madrasa Management System</h1>
        <div class="subtitle">Students</div>
    </div>
    <div>
        <a href="../auth/logout.php" class="logout-link">Logout</a>
    </div>
</div>

<div class="container">
    <div class="breadcrumb"><a href="../dashboard.php">Dashboard</a> / Students</div>

    <div class="card">
        <div class="top-bar">
            <h2 style="margin:0;">All Students</h2>
            <a href="add_student.php" class="btn btn-primary">+ Add Student</a>
        </div>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success">Student added successfully.</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Student updated successfully.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Student deleted successfully.</div>
        <?php endif; ?>

        <form method="GET" action="" style="margin-bottom:16px;">
            <input type="text" name="search" placeholder="Search by name, roll no, or class..."
                   value="<?= htmlspecialchars($search) ?>" style="max-width:300px; display:inline-block;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            <a href="view_students.php" class="btn btn-sm">Reset</a>
        </form>

        <table>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Gender</th>
                <th>Guardian</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr><td colspan="7" style="text-align:center; color:var(--muted);">No students found.</td></tr>
            <?php else: while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['class_name']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['guardian_name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td class="actions">
                        <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this student? This will also remove their attendance records.');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
        </table>
    </div>
</div>

</body>
</html>
