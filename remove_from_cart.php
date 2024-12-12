<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_item_id = $_POST['cart_item_id'];

    // Remove item from cart_items
    $delete_query = "DELETE FROM cart_items WHERE cart_item_id = $cart_item_id";
    if ($mysqli->query($delete_query)) {
        echo "<script>alert('Item removed from cart!'); window.location.href='carts.php';</script>";
    } else {
        echo "Error removing item: " . $mysqli->error;
    }
}
?>
