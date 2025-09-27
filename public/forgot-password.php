<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Forgot Password</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>
    </head>

    <body>
        <?php include __DIR__ . '/../resources/views/header.php'; ?>
        <main>
            <div class="container">
                <h1>Forgot Password</h1>
                <?php if (!empty($message)): ?>
                    <div class="alert"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit" name="reset_request" class="btn">Send reset link</button>
                </form>
            </div>
        </main>
        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>
</html>
