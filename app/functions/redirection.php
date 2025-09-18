<?php
session_start();

// No user connected → login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
