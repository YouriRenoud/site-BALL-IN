<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

$token = $_GET['token'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reset Password</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>
    </head>

    <body>
        <?php include __DIR__ . '/../resources/views/header.php'; ?>
        <main>
            <div class="container">
                <h1>Reset Password</h1>
                <?php if ($message): ?>
                    <p><?= $message ?></p>
                <?php endif; ?>

                <?php if ($token && !$message): ?>
                    <form method="POST">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="password" name="password" placeholder="New password" required>
                        <input type="password" name="confirm" placeholder="Confirm password" required>
                        <button type="submit" name="reset_password" class="btn">Change Password</button>
                    </form>
                <?php endif; ?>
            </div>
        </main>
        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>
</html>
