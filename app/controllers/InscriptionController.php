<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Store.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $birth_date = $_POST['birth_date'];
    $address    = trim($_POST['address']);
    $confirm    = $_POST['confirm_password'];
    $is_shop = isset($_POST['is_shop']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (User::findByUsername($username)) {
        $error = "This username is already taken.";
    } elseif (User::findByEmail($email)) {
        $error = "This email is already registered.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        if ($is_shop) {
            $role = 'shop';
        } else {
            $role = 'user';
        }

        $user_id = User::create($username, $email, $password, $birth_date, $address, $role);

        if ($user_id) {
            if ($is_shop) {

                Store::createPending($user_id, $_POST['shop_name'], $_POST['shop_address'], $_POST['shop_maps_link']);

                $adminEmail = "yourirenoudgrappin@gmail.com";
                $subject = "🛍️ New store awaiting approval - Ball'In";
                $body = "A new store registration requires approval:<br><br>
                        <strong>Name:</strong> " . htmlspecialchars($_POST['shop_name']) . "<br>
                        <strong>Owner:</strong> " . htmlspecialchars($username) . "<br>
                        <strong>Email:</strong> " . htmlspecialchars($email) . "<br>
                        <strong>Description:</strong> " . htmlspecialchars($_POST['shop_address']) . "<br><br>
                        <a href='http://localhost/BallIn/public/admin/validate_store.php'>Validate now</a>";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Ball'In <no-reply@ballin.com>" . "\r\n";

                mail($adminEmail, $subject, $body, $headers);

                $success = "Your store registration request has been sent to the admin for validation. Account created successfully!";
            
            } else {
                $_SESSION['username'] = $username;
                header("Location: login.php");
            }
        } else {
            $error = "Error while creating the account.";
        }
    }
}
