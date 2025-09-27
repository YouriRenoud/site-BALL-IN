<?php require_once __DIR__ . '/../app/controllers/AuthController.php'; ?>

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
                <h1>Welcome back !</h1>

                <?php if (!empty($error)) : ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="btn">Login</button>
                </form>

                <div class="links">
                    <p>New to Ball'In ? <a href="signup.php">Sign up now !</a></p>
                    <p><a href="forgot-password.php">Forgot your password ?</a></p>
                </div>
        
            </div>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>

    </body>
</html>