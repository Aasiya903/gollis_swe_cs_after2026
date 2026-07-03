<?php
/**
 * Database connection for the Madrasa Management System.
 * Include this file with: require_once __DIR__ . '/../db/connection.php';
 */

$db_host = 'localhost';
$db_user = 'root';       // change to your MySQL username
$db_pass = '';           // change to your MySQL password
$db_name = 'madrasa_system';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
