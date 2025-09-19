<?php
require_once __DIR__ . '/../models/User.php';

session_start();

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

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $users = User::findByUsername($searchTerm);
} else {
    $users = User::all();
}