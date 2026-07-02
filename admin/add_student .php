<?php
include '../config/db.php';
<link rel="stylesheet" href="style.css">

if(isset($_POST['save'])){
    $name = $_POST['student_name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    mysqli_query($conn, "
        INSERT INTO students(student_name, gender, age)
        VALUES('$name','$gender','$age')
    ");

    header("Location: students.php");
}
?>

<form method="POST">
    <input name="student_name" placeholder="Name">
    
    <select name="gender">
        <option>Male</option>
        <option>Female</option>
    </select>

    <input name="age" type="number">

    <button name="save">Save</button>
</form>