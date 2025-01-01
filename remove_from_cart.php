<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_item_id = $_POST['cart_item_id'];

    // Fetch IMEI before removing
    $fetchIMEIQuery = "SELECT IMEI FROM cart_items WHERE CART_ITEM_ID = ?";
    $stmt = $conn->prepare($fetchIMEIQuery);
    $stmt->bind_param("i", $cart_item_id);
    $stmt->execute();
    $stmt->bind_result($imei);
    $stmt->fetch();
    $stmt->close();

    if ($imei) {
        // Update ADDED_TO_CART to 0
        $updateProductQuery = "UPDATE product_unit SET ADDED_TO_CART = 0 WHERE IMEI = ?";
        $stmt = $conn->prepare($updateProductQuery);
        $stmt->bind_param("s", $imei);
        $stmt->execute();
        $stmt->close();

        // Remove item from cart_items
        $deleteCartItemQuery = "DELETE FROM cart_items WHERE CART_ITEM_ID = ?";
        $stmt = $conn->prepare($deleteCartItemQuery);
        $stmt->bind_param("i", $cart_item_id);
        if ($stmt->execute()) {
            echo "<script>alert('Item removed from cart successfully.'); window.location.href='carts.php';</script>";
        } else {
            echo "<script>alert('Error removing item.'); window.location.href='carts.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid cart item.'); window.location.href='carts.php';</script>";
    }
}
