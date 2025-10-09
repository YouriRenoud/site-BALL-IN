<?php
require_once __DIR__ . '/../models/Product.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $image_url   = null;
    $category_id = intval($_POST['category_id']);

    if (!empty($_FILES['image']['name'])) {
        $targetDir = __DIR__ . '/../../public/images/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image_url = 'images/' . $fileName;
            } else {
                $error = "Error uploading image.";
            }
        } else {
            $error = "Invalid file type. Allowed: JPG, PNG, GIF, WEBP.";
        }
    }

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

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$sort = $_GET['sort'] ?? 'name';
$order = $_GET['order'] ?? 'ASC';
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

$perPage = 6;
$totalProducts = Product::countAll($category_id);
$totalPages = ceil($totalProducts / $perPage);

$products = Product::getPaginated($page, $perPage, $sort, $order, $category_id);
