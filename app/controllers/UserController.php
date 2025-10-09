<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Store.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_users'])) {
    if (!empty($_POST['user_ids'])) {
        foreach ($_POST['user_ids'] as $userId) {
            if ($userId == $_SESSION['user_id']) continue;
            User::delete((int)$userId);
        }
        $success = "Selected users have been deleted.";
    } else {
        $error = "No users selected.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_stores'])) {
    if (!empty($_POST['user_ids'])) {
        foreach ($_POST['user_ids'] as $userId) {
            if ($userId == $_SESSION['user_id']) continue;
            $stmt = $conn->prepare("SELECT id FROM stores WHERE user_id = $userId");
            $stmt->execute();
            $storeId = $stmt->get_result()->fetch_assoc();
            Store::delete((int)$storeId['id']);
            User::delete((int)$userId);
        }
        $success = "Selected stores have been deleted.";
    } else {
        $error = "No stores selected.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_request'])) {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $conn->query("DELETE FROM password_resets WHERE user_id = " . intval($user['id']));

        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user['id'], $token, $expires);
        $stmt->execute();

        $resetLink = "http://localhost/BallIn/public/reset-password.php?token=" . $token;

        $subject = "Password Reset - Ball'In";

        $messageBody = '
        <html>
        <head>
            <style>
                body {
                    font-family: "Poppins", sans-serif;
                    background-color: #f4f4f4;
                    padding: 30px;
                    color: #333;
                }
                .mail-container {
                    background: #fff;
                    border-radius: 10px;
                    padding: 30px;
                    max-width: 600px;
                    margin: auto;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                h2 {
                    color: #ff6600;
                    text-align: center;
                }
                .btn {
                    display: inline-block;
                    background-color: #ff6600;
                    color: #fff !important;
                    padding: 12px 25px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: bold;
                    margin: 20px 0;
                    transition: 0.3s;
                }
                .btn:hover {
                    background-color: #e65c00;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 14px;
                    color: #777;
                }
                .logo {
                    display: block;
                    margin: 0 auto 20px;
                    width: 100px;
                }
            </style>
        </head>
        <body>
            <div class="mail-container">
                <img src="https://i.ibb.co/4ZmT3yP/ballin-logo.png" alt="Ball\'In Logo" class="logo">
                <h2>Password Reset Request</h2>
                <p>Hello,</p>
                <p>We received a request to reset your password for your <strong>Ball\'In</strong> account.</p>
                <p>Click the button below to choose a new password:</p>
                <div style="text-align:center;">
                    <a href="' . $resetLink . '" class="btn">Reset My Password</a>
                </div>
                <p>If you didn’t request this, you can safely ignore this email — your password will remain unchanged.</p>
                <p class="footer">© ' . date("Y") . ' Ball\'In — All rights reserved.</p>
            </div>
        </body>
        </html>
        ';

        $headers = "From: Ball'In <no-reply@ballin.com>\r\n";
        $headers .= "Reply-To: support@ballin.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // send email
        if (mail($email, $subject, $messageBody, $headers)) {
            $message = "✅ A reset link has been sent to your email address.";
        } else {
            $message = "⚠️ Email could not be sent. Please check your mail configuration.";
        }
    } else {
        $message = "❌ This email does not exist in our database.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $reset = $stmt->get_result()->fetch_assoc();

        if ($reset && strtotime($reset['expires_at']) > time()) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $reset['user_id']);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $message = "✅ Password changed successfully. <a href='login.php' class='btn'>Log in</a>";
        } else {
            $message = "⚠️ Invalid or expired reset link.";
        }
    }
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $users = User::findByUsername($searchTerm);
} else {
    $users = User::allUsers();
}