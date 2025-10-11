<?php
require_once __DIR__ . '/../../config/config.php';

class Shop {
    public static function getByStore($storeId) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.description, p.price, p.image_url, ps.stock_quantity AS stock_quantity, c.name AS category_name
            FROM product_store ps
            JOIN products p ON ps.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE ps.store_id = ?
        ");
        $stmt->bind_param("i", $storeId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function updateStock($storeId, $productId, $quantity) {
        global $conn;
        $stmt = $conn->prepare("
            UPDATE product_store SET stock_quantity = ?
            WHERE store_id = ? AND product_id = ?
        ");
        $stmt->bind_param("iii", $quantity, $storeId, $productId);
        return $stmt->execute();
    }

    public static function deleteFromStore($storeId, $productId) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM product_store WHERE store_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $storeId, $productId);
        return $stmt->execute();
    }

    public static function addToStore($storeId, $productId, $quantity) {
        global $conn;
        $stmt = $conn->prepare("
            INSERT INTO product_store (store_id, product_id, stock_quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE stock_quantity = stock_quantity + VALUES(stock_quantity)
        ");
        $stmt->bind_param("iii", $storeId, $productId, $quantity);
        return $stmt->execute();
    }

    public static function findStoreByUserId($userId) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT s.id, s.name, s.address, s.google_maps_link, s.user_id, s.is_validated
            FROM stores s
            WHERE s.user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}