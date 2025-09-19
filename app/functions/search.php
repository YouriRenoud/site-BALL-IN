<?php
require_once __DIR__ . '/../models/Product.php';

$q = $_GET['q'] ?? '';
$results = Product::searchByName($q);

foreach($results as $r){
    echo "<div class='suggestion-item' data-id='{$r['id']}'>".htmlspecialchars($r['name'])."</div>";
}
