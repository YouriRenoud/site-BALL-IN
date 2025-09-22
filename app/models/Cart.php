<?php
require_once __DIR__ . '/Product.php';

class Cart {
    public static function getUserCart($userId) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $cart = $stmt->get_result()->fetch_assoc();

        if (!$cart) {
            $stmt = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $cartId = $stmt->insert_id;
        } else {
            $cartId = $cart['id'];
        }

        return $cartId;
    }

    public static function addProduct($userId, $productId, $quantity = 1) {
        global $conn;
        $cartId = self::getUserCart($userId);

        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        if ($item) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $item['id']);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $cartId, $productId, $quantity);
            $stmt->execute();
        }
    }

    public static function getProducts($userId) {
        global $conn;
        $cartId = self::getUserCart($userId);

        $stmt = $conn->prepare("SELECT p.id, p.name, p.price, p.image_url, ci.quantity 
                                FROM cart_items ci 
                                JOIN products p ON ci.product_id = p.id
                                WHERE ci.cart_id = ?");
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function removeProduct($userId, $productId) {
        global $conn;
        $cartId = self::getUserCart($userId);

        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
    }

    public static function checkAvailability($productId) {
        global $conn;

        $stmt = $conn->prepare("SELECT s.name, s.address, sp.stock_quantity 
                                FROM product_store sp
                                JOIN stores s ON sp.store_id = s.id
                                WHERE sp.product_id = ? AND sp.stock_quantity > 0");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
