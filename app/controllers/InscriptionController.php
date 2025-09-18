<?php
require_once __DIR__ . '/../models/User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $birth_date = $_POST['birth_date'];
    $address    = trim($_POST['address']);
    $confirm    = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (User::findByUsername($username)) {
        $error = "This username is already taken.";
    } elseif (User::findByEmail($email)) {
        $error = "This email is already registered.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        if (User::create($username, $email, $password, $birth_date, $address)) {
            $_SESSION['username'] = $username;
            header("Location: login.php");
            exit;
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}
