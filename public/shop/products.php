<?php
require_once __DIR__ . '/../../app/controllers/ShopController.php';
require_once __DIR__ . '/../../app/models/Product.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage products</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>
    </head>
    <body>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?php include __DIR__ . '/../../resources/views/header.php'; ?>
    <link rel="stylesheet" href="../css/users.css">

    <main>
        <div class="square-container">
            <h1>Manage My Store Products</h1>

            <h2>My Products</h2>
            <table border="1" cellpadding="10" style="width:100%; margin-bottom:20px;">
                <tr>
                    <th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th>
                </tr>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td><?= number_format($p['price'], 2) ?> €</td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($p['stock_quantity'] ?? 0) ?>">
                                <button type="submit" name="update_stock" class="btn">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2>Add Existing Product to Store</h2>
            <form method="POST">
                <select name="product_id" id="product-select" style="width: 300px;" required></select>
                <input type="number" name="quantity" placeholder="Stock" min="1" required>
                <button type="submit" name="add_to_store" class="btn">Add to Store</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../resources/views/footer.php'; ?>

    <script src="../js/storeProducts.js"></script>

    </body>
</html>
