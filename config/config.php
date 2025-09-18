<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ballin_db";

// DataBase connection
$conn = new mysqli($host, $user, $pass, $db);

// Error check
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
