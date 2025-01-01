<?php
session_start();
require_once 'conn.php';

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['OWNER', 'ADMIN'])) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    $query = "DELETE FROM users WHERE USER_ID = $user_id";
    if ($conn->query($query)) {
        header("Location: users.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
