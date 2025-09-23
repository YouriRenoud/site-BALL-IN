<?php
require_once __DIR__ . '/../app/functions/redirection.php';

require_once __DIR__ . '/../app/models/Order.php';
require_once __DIR__ . '/../app/models/Product.php';

$userId = $_SESSION['user_id'];
$orders = Order::getOrdersById($userId);

$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
$orderItems = [];

if ($orderId) {
    $orderItems = Order::getItems($orderId);
}
?>

<?php if ($orderId && !empty($orderItems)): ?>
    <div class="square-container">
        <h3>Details for Order #<?= $orderId ?></h3>
        <ul>
            <?php foreach ($orderItems as $item): ?>
                <li>
                    <?= htmlspecialchars($item['product_name']) ?> — 
                    <?= number_format($item['price'], 2) ?> € 
                    (Store: <?= htmlspecialchars($item['store_name']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <title>Our Contact</title>
        <?php include __DIR__ . '/../resources/views/head.php'; ?>
        <link rel="stylesheet" href="css/users.css">

    </head>

    <body>
        <?php include __DIR__ . '/../resources/views/header.php'; ?>

        <main>
            <h1>My Orders</h1>

            <?php if (!empty($orders)): ?>
                <ul>
                    <?php foreach ($orders as $order): ?>
                        <li>
                            Order #<?= htmlspecialchars($order['id']) ?> —
                            Placed on <?= htmlspecialchars($order['created_at']) ?>
                            <a href="contact.php?order_id=<?= $order['id'] ?>">View details</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have no orders yet.</p>
            <?php endif; ?>
        </main>

        <?php include __DIR__ . '/../resources/views/footer.php'; ?>
    </body>

</html>
