<?php
require_once __DIR__ . '/../app/controllers/ProductController.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Products</title>
        <link rel="stylesheet" href="css/searchMenu.css">
        <link rel="stylesheet" href="css/users.css">
        <?php include __DIR__ . '/../resources/views/head.php'; ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    </head>
    <body>

    <?php include __DIR__ . '/../resources/views/header.php'; ?>

    <main>
        <div class="square-container" style="margin-top:20px;">

            <p>
                <a href="index.php">Home</a> > 
                <a href="products.php">Products</a>
                <?php if ($category_id): ?>
                    > <span><?= htmlspecialchars($products[0]['category_name'] ?? '') ?></span>
                <?php endif; ?>
            </p>

            <input type="text" id="search" placeholder="Search products..." autocomplete="off">
            <div id="suggestions" class="dropdown"></div>

            <form method="GET" style="margin-top:20px;">
                <input type="hidden" name="category" value="<?= $category_id ?>">
                <select name="sort" class="sort-select">
                    <option value="name" <?= $sort==='name'?'selected':'' ?>>Name</option>
                    <option value="price" <?= $sort==='price'?'selected':'' ?>>Price</option>
                </select>
                <select name="order" class="sort-select">
                    <option value="ASC" <?= $order==='ASC'?'selected':'' ?>>Ascending</option>
                    <option value="DESC" <?= $order==='DESC'?'selected':'' ?>>Descending</option>
                </select>
                <button type="submit" class="btn">Sort</button>
            </form>

            <div id="product-grid" class="product-grid" style="margin-top:20px;">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">

                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong><?= number_format($product['price'], 2) ?> €</strong></p>
                        </div>

                        <a href="cart.php?add=<?= $product['id'] ?>" class="btn buy-btn">Buy</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:30px; text-align:center;">
                <?php for ($i=1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>&category=<?= $category_id ?>"
                    class="btn" style="margin:2px;"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../resources/views/footer.php'; ?>

    <script src="js/products.js"></script>

    </body>
</html>
