<?php
require_once __DIR__ . '/../models/Shop.php';

session_start();

$userId = $_SESSION['user_id'];

$store = Shop::findStoreByUserId($userId);

if (!$store) {
} elseif (!$store['is_validated']) {
    $pending = true;
} else {
    $pending = false;
}
$storeId = $store['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_store'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    Shop::addToStore($storeId, $productId, $quantity);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    Shop::updateStock($storeId, $productId, $quantity);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = intval($_POST['product_id']);
    Shop::deleteFromStore($storeId, $productId);
}

$products = Shop::getByStore($storeId);
