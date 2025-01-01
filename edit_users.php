<?php
session_start();
require_once 'conn.php';

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['OWNER', 'ADMIN', 'MANAJER'])) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $user_role = in_array($_SESSION['user_role'], ['OWNER', 'ADMIN']) ? $_POST['user_role'] : 'KARYAWAN';

    $query = "UPDATE users SET FIRST_NAME = '$first_name', LAST_NAME = '$last_name', EMAIL = '$email', PHONE = '$phone', USER_ROLE = '$user_role' WHERE USER_ID = $user_id";
    if ($conn->query($query)) {
        header("Location: users.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
