<?php
require_once __DIR__ . '/../app/functions/redirection.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';

$items = Cart::getProducts($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Checkout</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>
        <link rel="stylesheet" href="css/users.css">

    </head>
    <body>

    <?php include __DIR__ . '/../resources/views/header.php'; ?>

    <main>
        <div class="square-container">
            <h1>Choose store for each product</h1>

            <form method="POST">
                <?php foreach ($items as $item): ?>
                    <div class="product-card" style="margin-bottom:20px; text-align:left;">
                        <h3><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)</h3>
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" width="100">

                        <?php
                        $stores = Cart::checkAvailability($item['id']);
                        if (empty($stores)): ?>
                            <p style="color:red;">Not available in any store.</p>
                        <?php else: ?>
                            <p>Available in:</p>
                            <?php foreach ($stores as $store): ?>
                                <label>
                                    <input type="radio" name="stores[<?= $item['id'] ?>]" value="<?= $store['store_id'] ?>" required>
                                    <?= htmlspecialchars($store['name']) ?> - 
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($store['address']) ?>" target="_blank">
                                        <?= htmlspecialchars($store['address']) ?>
                                    </a> (Stock: <?= $store['stock_quantity'] ?>)
                                </label><br>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn">Confirm Order</button>

                <?php if (empty($stores)) : ?>
                    <p style="color:red;">One of the products is out of stock.</p>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../resources/views/footer.php'; ?>

    </body>
</html>
