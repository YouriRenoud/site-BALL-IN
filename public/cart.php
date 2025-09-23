<?php
require_once __DIR__ . '/../app/functions/redirection.php';

require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/models/Cart.php';

$items = Cart::getProducts($_SESSION['user_id']);
$total = array_reduce($items, fn($sum, $i) => $sum + $i['price'] * $i['quantity'], 0);
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
            <p>Welcome <?= htmlspecialchars($_SESSION['username']) ?>, here are your items :</p>

            <div class="container">

                <?php if (empty($items)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($items as $item): ?>
                            <li>
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" width="50">
                                <?= htmlspecialchars($item['name']) ?> -
                                <?= $item['quantity'] ?> × <?= number_format($item['price'], 2) ?> €
                                <a href="cart.php?remove=<?= $item['id'] ?>" class="btn">Remove</a>
                                <a href="cart.php?check=<?= $item['id'] ?>" class="btn">Check Availability</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <h3>Total: <?= number_format($total, 2) ?> €</h3>
                <?php endif; ?>

                <?php if (!empty($availability)): ?>
                    <div class="container" style="margin-top:20px;">
                        <h2>Available in stores:</h2>
                        <ul>
                            <?php foreach ($availability as $store): ?>
                                <li>
                                    <?= htmlspecialchars($store['name']) ?> (<?= htmlspecialchars($store['address']) ?>) - 
                                    Stock: <?= $store['stock_quantity'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($items)): ?>
                    <h3>Total: <?= number_format($total, 2) ?> €</h3>
                    <a href="checkout.php" class="btn">Validate Cart</a>
                <?php endif; ?>

            </div>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>

</html>
