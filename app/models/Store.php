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
        $stmt = $conn->prepare("SELECT id, name, address, google_maps_link
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
}
