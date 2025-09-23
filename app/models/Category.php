<?php
require_once __DIR__ . '/../../config/config.php';

class Category {

    public static function getCategories() {
        global $conn;
        $result = $conn->query("SELECT id, name FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getCategoryById($name) {
        global $conn;
        $stmt = $conn->prepare("SELECT id, name FROM categories WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
