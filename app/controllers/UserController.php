<?php
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_users'])) {
    if (!empty($_POST['user_ids'])) {
        foreach ($_POST['user_ids'] as $userId) {
            if ($userId == $_SESSION['user_id']) continue;
            User::delete((int)$userId);
        }
        $success = "Selected users have been deleted.";
    } else {
        $error = "No users selected.";
    }
}

$searchUsername = isset($_GET['search_username']) ? $_GET['search_username'] : '';

if ($searchUsername !== '') {
    $users = User::searchByUsername($searchUsername);
} else {
    $users = User::all();
}