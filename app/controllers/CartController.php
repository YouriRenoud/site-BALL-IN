<?php
require_once __DIR__ . '/../models/Cart.php';

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