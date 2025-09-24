<?php
session_start(); // always on the top

$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Ball'In</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>

    </head>

    <body>

        <?php include __DIR__ . '/../../resources/views/header.php'; ?>

        <main>
            <h1>Welcome again <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
            <div class="btn-group">
                <a href="products.php" class="btn">Manage products</a>
                <a href="../logout.php" class="btn">Logout</a>
            </div>
        </main>

        <?php include __DIR__ . '/../../resources/views/footer.php'; ?>
    
    </body>
</html>