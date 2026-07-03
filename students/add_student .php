<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";

if(isset($_POST['save'])){

$name = $_POST['student_name'];
$gender = $_POST['gender'];
$age = $_POST['age'];

$sql = "INSERT INTO students(student_name,gender,age)
VALUES('$name','$gender','$age')";

if(mysqli_query($conn,$sql)){

$message="Student Added Successfully!";

}else{

$message="Error: ".mysqli_error($conn);

}

}
?>

<!DOCTYPE html>

<html>

<head>

<title>Add Student</title>

<link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<header>

<h1>Add Student</h1>

</header>

<nav>

<a href="dashboard.php">Dashboard</a>

<a href="students.php">Students</a>

<a href="../logout.php">Logout</a>

</nav>

<div class="container">

<div class="card">

<?php

if($message!=""){

echo "<h3 class='success'>$message</h3>";

}

?>

<form method="POST">

<label>Student Name</label>

<input
type="text"
name="student_name"
required>

<label>Gender</label>

<select name="gender">

<option>Male</option>

<option>Female</option>

</select>

<label>Age</label>

<input
type="number"
name="age"
required>

<button
name="save">

Save Student

</button>

</form>

</div>

</div>

</body>

</html>