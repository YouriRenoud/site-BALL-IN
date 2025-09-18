<?php
require_once __DIR__ . '/../app/functions/redirection.php';
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <title>Your Cart</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>

    </head>

    <body>
        <?php include __DIR__ . '/../resources/views/header.php'; ?>

        <main>
            <h1>Your Shopping Cart</h1>
            <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?>, here are your items:</p>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>

</html>
