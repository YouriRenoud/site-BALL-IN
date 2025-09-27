<?php
require_once __DIR__ . '/../models/User.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

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

        // ---- PHPMailer ----
        $mail = new PHPMailer(true);

        try {
            // Config SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';      // serveur SMTP (ici Gmail)
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yourirenoudgrappin@gmail.com';  // ton adresse Gmail
            $mail->Password   = 'ton-mot-de-passe-app'; // mot de passe d'application Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Expéditeur et destinataire
            $mail->setFrom('no-reply@ballin.com', "Ball'In");
            $mail->addAddress($email);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = "Password Reset - Ball'In";
            $mail->Body    = "Hello,<br><br>Click here to reset your password:<br>
                            <a href='$resetLink'>$resetLink</a><br><br>
                            If you did not request a reset, ignore this email.";

            $mail->send();
            $message = "A reset link has been sent to $email.";

        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "This email does not exist in our database.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {

    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
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

            $message = "Password changed successfully. <a href='login.php' class='btn'>Log in</a>";
        } else {
            $message = "Invalid or expired link.";
        }
    }
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $users = User::findByUsername($searchTerm);
} else {
    $users = User::all();
}