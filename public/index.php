<?php
session_start(); // always on the top

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['username']);
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
            <?php if ($isLoggedIn): ?>
                <h1>Welcome again <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
                <div class="btn-group">
                    <a href="logout.php" class="btn">Logout</a>
                </div>
            <?php else: ?>
                <h1>Welcome to Ball'In</h1>
                <h2>The Ultimate Basketball Experience !</h2>
                <div class="btn-group">
                    <a href="login.php" class="btn">Login</a>
                    <a href="signup.php" class="btn">Sign up</a>
                </div>
            <?php endif; ?>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    
    </body>
</html>