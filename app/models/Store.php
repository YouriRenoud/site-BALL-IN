<?php
require_once __DIR__ . '/../../config/config.php';

class Store {
    public static function all() {
        global $conn;
        $result = $conn->query("SELECT id, name, address, google_maps_link FROM stores");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function findById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT *
                                FROM stores
                                WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM stores WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function createPending($user_id, $name, $address, $google_maps_link) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO stores (user_id, name, address, google_maps_link, is_validated) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("isss", $user_id, $name, $address, $google_maps_link);
        return $stmt->execute();
    }

    public static function getPendingStores() {
        global $conn;
        $sql = "SELECT * FROM stores WHERE is_validated = 0";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function validateStore($id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE stores SET is_validated = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function deleteStore($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM stores WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
