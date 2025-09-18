<?php
require_once __DIR__ . '/../../config/config.php';

class Product {
    public static function create($name, $description, $price, $image_url, $category_id) {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO products (name, description, price, image_url, category_id) VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $category_id);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return true;
    }

    public static function getCategories() {
        global $conn;
        $result = $conn->query("SELECT id, name FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
