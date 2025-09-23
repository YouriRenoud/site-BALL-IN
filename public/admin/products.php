<?php
require_once __DIR__ . '/../../app/controllers/ProductController.php';
require_once __DIR__ . '/../../app/models/Product.php';
$categories = Product::getCategories();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage products</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>
    </head>
    <body>

    <?php include __DIR__ . '/../../resources/views/header.php'; ?>

    <main>
        <div class="container">
            <h1>Add a Product</h1>

            <?php if (!empty($error)) : ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <p style="color:green;"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="name" placeholder="Product name" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="number" step="0.01" name="price" placeholder="Price (€)" required>
                <input type="text" name="image_url" placeholder="Image URL (optional)">
                
                <select name="category_id" required>
                    <option value="">-- Select category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="add_product" class="btn">Add Product</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../resources/views/footer.php'; ?>

    </body>
</html>
