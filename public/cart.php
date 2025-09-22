<?php
require_once __DIR__ . '/../app/functions/redirection.php';

require_once __DIR__ . '/../app/models/Cart.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Add product to cart if requested
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    Cart::addProduct($_SESSION['user_id'], $productId);
    header("Location: cart.php");
    exit;
}

// Load cart items
$items = Cart::getProducts($_SESSION['user_id']);

if (isset($_GET['remove'])) {
    Cart::removeProduct($_SESSION['user_id'], (int)$_GET['remove']);
    header("Location: cart.php");
    exit;
}

// Check availability
$availability = [];
if (isset($_GET['check'])) {
    $availability = Cart::checkAvailability((int)$_GET['check']);
}

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
            </div>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>

</html>
