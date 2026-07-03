<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "gollissweafter2026";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "Connection successful";
?>