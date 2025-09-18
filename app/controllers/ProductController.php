<?php
require_once __DIR__ . '/../models/Product.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $image_url   = trim($_POST['image_url']); 
    $category_id = intval($_POST['category_id']);

    if (empty($name) || empty($price) || empty($category_id)) {
        $error = "Name, price and category are required.";
    } else {
        if (Product::create($name, $description, $price, $image_url, $category_id)) {
            $success = "Product added successfully!";
        } else {
            $error = "An error occurred while adding the product.";
        }
    }
}


