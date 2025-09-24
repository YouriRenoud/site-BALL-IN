<?php
require_once __DIR__ . '/../models/Shop.php';

session_start();

$storeId = Shop::getStoreIdByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_store'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    Product::addToStore($storeId, $productId, $quantity);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    Product::updateStock($storeId, $productId, $quantity);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = intval($_POST['product_id']);
    Product::deleteFromStore($storeId, $productId);
}

$products = Product::getByStore($storeId);
