<?php
require_once __DIR__ . '/../models/Product.php';
global $conn;

header('Content-Type: application/json');

$q = isset($_GET['term']) ? trim($_GET['term']) : '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name FROM products WHERE name LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $q);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        "id" => $row['id'],
        "text" => $row['name']
    ];
}

echo json_encode($products);
