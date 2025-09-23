<?php
require_once __DIR__ . '/Cart.php';

class Order {
    public static function create($userId, $chosenStores) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            die("Error creating order: " . $stmt->error);
        }
        $orderId = $stmt->insert_id;

        $items = Cart::getProducts($userId);

        foreach ($items as $item) {
            if (!isset($chosenStores[$item['id']])) {
                continue;
            }
            $storeId = (int)$chosenStores[$item['id']];
            $quantity = (int)$item['quantity'];

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, store_id, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $orderId, $item['id'], $storeId, $quantity);
            if (!$stmt->execute()) {
                die("Error adding product: " . $stmt->error);
            }
        }

        Cart::clearUserCart($userId);

        return $orderId;
    }

    public static function getOrdersById($userId) {
        global $conn;
        $stmt = $conn->prepare("SELECT id, created_at
                                FROM orders
                                WHERE user_id = ?
                                ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getItems($orderId) {
        global $conn;
        $stmt = $conn->prepare("SELECT oi.quantity, p.name AS product_name, p.price, s.name AS store_name
                                FROM order_items oi
                                JOIN products p ON oi.product_id = p.id
                                JOIN stores s ON oi.store_id = s.id
                                WHERE oi.order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
