<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the user's cart
$cart_query = "SELECT cart_id, quantity FROM carts WHERE user_id = $user_id";
$cart_result = $conn->query($cart_query);
$cart = $cart_result->fetch_assoc();

if ($cart) {
    $cart_id = $cart['cart_id'];
    $quantity = $cart['quantity'];

    // Calculate total price
    $total_query = "SELECT SUM(ci.sold_price) AS grand_total 
                    FROM cart_items ci 
                    WHERE ci.cart_id = $cart_id";
    $total_result = $conn->query($total_query);
    $grand_total = $total_result->fetch_assoc()['grand_total'];

    // Insert into transactions
    $shipping_address = "Default Address"; // Replace with dynamic input
    $buyer_name = $_SESSION['first_name'] . " " . $_SESSION['last_name']; // Replace as needed
    $conn->query("INSERT INTO transactions 
                  (cart_id, transaction_status, shipping_address, total_unit, grand_total, buyer_name, created_at) 
                  VALUES ($cart_id, 'Completed', '$shipping_address', $quantity, $grand_total, '$buyer_name', NOW())");

    // Clear the cart
    $conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id");
    $conn->query("UPDATE carts SET quantity = 0 WHERE cart_id = $cart_id");

    echo "Transaction completed! Total: $grand_total";
} else {
    echo "No active cart.";
}
?>
