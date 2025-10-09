<?php
require_once __DIR__ . '/../../app/models/Store.php';
require_once __DIR__ . '/../../app/models/User.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $storeId = (int) $_GET['id'];
    if ($_GET['action'] === 'approve') {
        Store::validateStore($storeId);
        $message = "✅ Store approved successfully.";
    } elseif ($_GET['action'] === 'reject') {
        Store::deleteStore($storeId);
        $message = "❌ Store rejected and removed.";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'approve') {
    $store = Store::findById($storeId);
    $owner = User::findById($store['user_id']);
    $to = $owner['email'];
    $subject = "Your store has been approved - Ball'In";
    $body = "Hello " . htmlspecialchars($owner['username']) . ",<br><br>
            Your store <strong>" . htmlspecialchars($store['name']) . "</strong> has been approved and is now live on Ball'In!<br><br>
            You can now log in to manage your products.";
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: Ball'In <no-reply@ballin.com>\r\n";
    mail($to, $subject, $body, $headers);
}


$pendingStores = Store::getPendingStores();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pending Stores - Ball'In</title>
        <?php include __DIR__ . '/../../resources/views/head.php'; ?>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>

    <?php include __DIR__ . '/../../resources/views/header.php'; ?>

    <main>
        <div class="square-container">
            <h1>🛍️ Pending Store Requests</h1>

            <?php if (!empty($message)) : ?>
                <p style="color:green; font-weight:bold;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if (empty($pendingStores)) : ?>
                <p>No stores awaiting validation.</p>
            <?php else : ?>
                <table border="1" cellpadding="10" cellspacing="0" style="margin:auto; background:white; color:black;">
                    <tr style="background:#ff6600; color:white;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Address</th>
                        <th>Maps link</th>
                        <th>Actions</th>
                    </tr>

                    <?php foreach ($pendingStores as $store) : 
                        $owner = User::findById($store['user_id']);
                    ?>
                        <tr>
                            <td><?= $store['id'] ?></td>
                            <td><?= htmlspecialchars($store['name']) ?></td>
                            <td><?= htmlspecialchars($owner['username']) ?> (<?= htmlspecialchars($owner['email']) ?>)</td>
                            <td><?= htmlspecialchars($store['address']) ?></td>
                            <td>
                                <?php if (!empty($store['google_maps_link'])): ?>
                                    <a href="<?= htmlspecialchars($store['google_maps_link']) ?>" target="_blank" style="color:#007bff; text-decoration:underline;">
                                        View on Maps
                                    </a>
                                <?php else: ?>
                                    <span style="color:gray;">No link</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="?action=approve&id=<?= $store['id'] ?>" class="btn">✅ Approve</a>
                                <a href="?action=reject&id=<?= $store['id'] ?>" class="btn" style="background:#cc0000;">❌ Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../../resources/views/footer.php'; ?>

    </body>
</html>
