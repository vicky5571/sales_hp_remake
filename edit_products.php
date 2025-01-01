<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check user role
$userRole = $_SESSION['user_role'] ?? '';
define('ROLE_OWNER', 'OWNER');
define('ROLE_ADMIN', 'ADMIN');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $categoryId = $_POST['category_id'];
    $productName = $_POST['product_name'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'];

    // Role-based permission check
    if ($userRole == ROLE_OWNER || $userRole == ROLE_ADMIN) {
        $updateQuery = "UPDATE PRODUCTS 
                        SET CATEGORY_ID = '$categoryId', PRODUCT_NAME = '$productName', COLOR = '$color', QUANTITY = '$quantity' 
                        WHERE PRODUCT_ID = '$productId'";
    } else {
        $updateQuery = "UPDATE PRODUCTS 
                        SET CATEGORY_ID = '$categoryId', PRODUCT_NAME = '$productName', COLOR = '$color' 
                        WHERE PRODUCT_ID = '$productId'";
    }

    if ($conn->query($updateQuery)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
