<?php
require_once __DIR__ . '/../app/controllers/InscriptionController.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Ball'In</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>

    </head>

    <body>

        <?php include __DIR__ . '/../resources/views/header.php'; ?>

        <main>

            <div class="container">
                <h1>We are glad to see you !</h1>

                <?php if (!empty($error)) : ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="date" name="birth_date" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                    <button type="submit" class="btn">Sign up</button>
                </form>

                <div class="links">
                    <p>Already a member ? <a href="login.php">Login now !</a></p>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>

    </body>
</html>