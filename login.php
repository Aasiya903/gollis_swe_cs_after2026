<?php
session_start();
include "config/db.php";

// PHP code-kaaga halkan
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Digital Madrasa System</h1>

<form method="POST">
    <input type="text" name="username">
    <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=md5($_POST['password']);

$sql=mysqli_query($conn,"SELECT * FROM users
WHERE username='$username'
AND password='$password'");

if(mysqli_num_rows($sql)>0){

$_SESSION['admin']=$username;

header("Location:admin/dashboard.php");

}else{

$error="Invalid Username or Password";

}

}

?>
