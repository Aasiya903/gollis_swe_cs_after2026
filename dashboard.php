<?php

session_start();

if(!isset($_SESSION['admin'])){

header("Location:../login.php");

}

include("db/connection.php");

$total=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM students"));

?>

<!DOCTYPE html>

<html>

<head>

<title>Dashboard</title>

<link rel="stylesheet"
href="../assets/style.css">

</head>

<body>

<header>

<h1>Madrasa Management System</h1>

</header>

<nav>

<a href="dashboard.php">Dashboard</a>

<a href="students.php">Students</a>

<a href="../teachers/attendance.php">Attendance</a>

<a href="../logout.php">Logout</a>

</nav>

<div class="container">

<div class="card">

<h2>Welcome <?php echo $_SESSION['admin']; ?></h2>

<h3>Total Students</h3>

<h1><?php echo $total; ?></h1>

</div>

</div>

</body>

</html> 