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

                <?php if (!empty($success)) : ?>
                    <p style="color:green; font-weight:bold;"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="date" name="birth_date" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                    <label style="display: block; margin-top: 10px;">
                        <input type="checkbox" id="is_shop" name="is_shop"> I am a store owner
                    </label>

                    <div id="shop_fields" style="display:none; margin-top:15px;">
                        <input type="text" name="shop_name" placeholder="Store name">
                        <input type="text" name="shop_address" placeholder="Store address">
                        <input type="text" name="shop_maps_link" placeholder="Store maps link">
                    </div>

                    <button type="submit" class="btn">Sign up</button>
                </form>

                <div class="links">
                    <p>Already a member ? <a href="login.php">Login now !</a></p>
                </div>
            </div>

        </main>

        <script src="js/products.js"></script>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>

    </body>
</html>