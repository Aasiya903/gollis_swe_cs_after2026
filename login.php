<?php
session_start();
include 'config/db.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: admin/dashboard.php");
        }
    }
}
?>

<form method="POST">
    Username:
    <input type="text" name="username">

    Password:
    <input type="password" name="password">

    <button type="submit" name="login">
        Login
    </button>
</form>