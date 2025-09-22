<?php
require_once __DIR__ . '/../app/models/Product.php';
global $conn;

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo '';
    exit;
}

$stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.name LIKE CONCAT('%', ?, '%')
                        LIMIT 10");
$stmt->bind_param("s", $q);
$stmt->execute();
$result = $stmt->get_result();

$products = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['mode']) && $_GET['mode'] === 'full') {
    foreach ($products as $p) {
        echo "<div class='product-card'>
                <img src='" . htmlspecialchars($p['image_url']) . "' alt='" . htmlspecialchars($p['name']) . "'>
                <div class='product-info'>
                    <h3>" . htmlspecialchars($p['name']) . "</h3>
                    <p>" . htmlspecialchars($p['description']) . "</p>
                    <p><strong>" . number_format($p['price'], 2) . " €</strong></p>
                </div>
                <a href='cart.php?add=" . $p['id'] . "' class='btn buy-btn'>Buy</a>
                </div>";
    }
} else {
    foreach ($products as $p) {
        echo "<div class='suggestion-item' data-id='{$p['id']}'>" . htmlspecialchars($p['name']) . "</div>";
    }
}
