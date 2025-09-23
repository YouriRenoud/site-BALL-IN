<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Store.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chosenStores = $_POST['stores'] ?? [];
    if (empty($chosenStores)) {
        $_SESSION['error'] = "Please select a store for each product.";
        header("Location: checkout.php");
        exit;
    }

    $orderId = Order::create($userId, $chosenStores);

    header("Location: contact.php?id=" . $orderId);
    exit;
}

$items  = Cart::getProducts($userId);
$stores = Store::all();