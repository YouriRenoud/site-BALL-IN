<?php
require_once __DIR__ . '/../../config/config.php';

class Product {
    public static function create($name, $description, $price, $image_url, $category_id) {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO products (name, description, price, image_url, category_id) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $category_id);
        return $stmt->execute();
    }

    public static function getCategories() {
        global $conn;
        $result = $conn->query("SELECT id, name FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getPaginated($page = 1, $perPage = 6, $sort = 'name', $order = 'ASC', $category_id = null) {
        global $conn;

        $validSort = ['name','price'];
        $validOrder = ['ASC','DESC'];

        if (!in_array($sort, $validSort)) $sort = 'name';
        if (!in_array($order, $validOrder)) $order = 'ASC';

        $offset = ($page - 1) * $perPage;

        if ($category_id) {
            $sql = "SELECT p.*, c.name AS category_name 
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.category_id = ?
                    ORDER BY $sort $order
                    LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $category_id, $perPage, $offset);
        } else {
            $sql = "SELECT p.*, c.name AS category_name 
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    ORDER BY $sort $order
                    LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $perPage, $offset);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function countAll($category_id = null) {
        global $conn;
        if ($category_id) {
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc()['total'];
        } else {
            $result = $conn->query("SELECT COUNT(*) as total FROM products");
            return $result->fetch_assoc()['total'];
        }
    }

    public static function searchByName($term) {
        global $conn;
        $like = "%".$term."%";
        $stmt = $conn->prepare("SELECT id, name
                                FROM products
                                WHERE name LIKE ? LIMIT 10");
        $stmt->bind_param("s", $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
