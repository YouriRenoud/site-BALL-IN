<?php
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/models/User.php';

$stores = User::allStores();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage Stores</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>
        <link rel="stylesheet" href="../css/users.css">
    </head>
    <body>

    <?php include __DIR__ . '/../../resources/views/header.php'; ?>

    <main>
        <div class="square-container">
            <h1>Manage Stores</h1>

            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by username" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                <button type="submit" class="btn">Search</button>
            </form>

            <?php if (!empty($error)) : ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <p style="color:green;"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form method="POST">
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; text-align: center;">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Birth Date</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stores as $store): ?>
                            <?php
                                $isHighlighted = isset($_GET['search']) && stripos($store['username'], $_GET['search']) !== false;
                            ?>
                            <tr class="<?= $isHighlighted ? 'highlight' : '' ?>" id="<?= $isHighlighted ? 'user-' . $store['id'] : '' ?>">
                                <td><input type="checkbox" name="user_ids[]" value="<?= $store['id'] ?>"></td>
                                <td><?= htmlspecialchars($store['id']) ?></td>
                                <td><?= htmlspecialchars($store['username']) ?></td>
                                <td><?= htmlspecialchars($store['email']) ?></td>
                                <td><?= htmlspecialchars($store['birth_date']) ?></td>
                                <td><?= htmlspecialchars($store['address']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="delete_stores" class="btn" style="margin-top: 20px;">Delete Selected Stores</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../resources/views/footer.php'; ?>

    <script src="/path/to/your/js/search.js"></script>

    </body>
</html>

