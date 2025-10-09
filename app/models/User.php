<?php
require_once __DIR__ . '/../../config/config.php';

class User {
    public static function findByUsername($username) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findByEmail($email) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function allUsers() {
        global $conn;
        $result = $conn->query("SELECT id, username, email, birth_date, address FROM users WHERE role = 'user'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function allStores() {
        global $conn;
        $result = $conn->query("SELECT id, username, email, birth_date, address FROM users WHERE role = 'shop'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($username, $email, $password, $birth_date, $address, $role) {
        global $conn;
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, birth_date, address, role) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssss", $username, $email, $hash, $birth_date, $address, $role);

        if ($stmt->execute()) {
            return $conn->insert_id;
        } else {
            return false;
        }
    }

    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
?>