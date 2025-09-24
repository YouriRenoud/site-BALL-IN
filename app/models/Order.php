<?php
require_once __DIR__ . '/Cart.php';

class Order {
    public static function create($userId, $chosenStores) {
        global $conn;

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, created_at) VALUES (?, NOW())");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $orderId = $conn->insert_id;

            $stmtCart = $conn->prepare("
                SELECT ci.product_id, ci.quantity 
                FROM cart_items ci
                INNER JOIN carts c ON ci.cart_id = c.id
                WHERE c.user_id = ?
            ");
            $stmtCart->bind_param("i", $userId);
            $stmtCart->execute();
            $cartItems = $stmtCart->get_result()->fetch_all(MYSQLI_ASSOC);

            foreach ($cartItems as $item) {
                $productId = $item['product_id'];
                $qty       = $item['quantity'];
                $storeId   = $chosenStores[$productId] ?? null;

                if (!$storeId) {
                    $conn->rollback();
                    return false;
                }

                $stmt = $conn->prepare("SELECT stock_quantity FROM product_store WHERE product_id = ? AND store_id = ?");
                $stmt->bind_param("ii", $productId, $storeId);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                $stock = $res['stock_quantity'] ?? 0;

                if ($stock < $qty) {
                    $conn->rollback();
                    return false;
                }

                $stmtItem = $conn->prepare("
                    INSERT INTO order_items (order_id, product_id, store_id, quantity) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmtItem->bind_param("iiii", $orderId, $productId, $storeId, $qty);
                $stmtItem->execute();

                $stmtStock = $conn->prepare("
                    UPDATE product_store
                    SET stock_quantity = stock_quantity - ?
                    WHERE product_id = ? AND store_id = ?
                ");
                $stmtStock->bind_param("iii", $qty, $productId, $storeId);
                $stmtStock->execute();
            }

            Cart::clearUserCart($userId);
            $conn->commit();
            return $orderId;

        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
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
        $stmt = $conn->prepare("SELECT oi.quantity, p.name AS product_name, p.price, s.name AS store_name, s.google_maps_link AS store_address
                                FROM order_items oi
                                JOIN products p ON oi.product_id = p.id
                                JOIN stores s ON oi.store_id = s.id
                                WHERE oi.order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
