<?php
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/models/User.php';

$users = User::all();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage Users</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>
        <link rel="stylesheet" href="../css/users.css">
    </head>
    <body>

    <?php include __DIR__ . '/../../resources/views/header.php'; ?>

    <main>
        <div class="square-container">
            <h1>Manage Users</h1>

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
                        <?php foreach ($users as $user): ?>
                            <?php 
                                $isHighlighted = isset($_GET['search']) && stripos($user['username'], $_GET['search']) !== false;
                            ?>
                            <tr class="<?= $isHighlighted ? 'highlight' : '' ?>" id="<?= $isHighlighted ? 'user-' . $user['id'] : '' ?>">
                                <td><input type="checkbox" name="user_ids[]" value="<?= $user['id'] ?>"></td>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['birth_date']) ?></td>
                                <td><?= htmlspecialchars($user['address']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="delete_users" class="btn" style="margin-top: 20px;">Delete Selected Users</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../resources/views/footer.php'; ?>

    <script src="/path/to/your/js/search.js"></script>

    </body>
</html>

