<?php
include 'conn.php';
session_start();

$user_id = $_SESSION['user_id'];

// Get the cart ID
$cart_query = "SELECT cart_id FROM carts WHERE user_id = $user_id";
$cart_result = $mysqli->query($cart_query);

if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    // Calculate totals
    $total_units_query = "SELECT COUNT(*) as total_units FROM cart_items WHERE cart_id = $cart_id";
    $total_units_result = $mysqli->query($total_units_query);
    $total_units = $total_units_result->fetch_assoc()['total_units'];

    $grand_total_query = "SELECT SUM(pu.srp) as grand_total
                          FROM cart_items ci
                          JOIN product_unit pu ON ci.imei = pu.imei
                          WHERE ci.cart_id = $cart_id";
    $grand_total_result = $mysqli->query($grand_total_query);
    $grand_total = $grand_total_result->fetch_assoc()['grand_total'];

    // Insert transaction
    $transaction_status = 'Pending';
    $shipping_address = 'Default Address'; // Replace with user input
    $buyer_name = 'Default Buyer'; // Replace with user input
    $created_at = date('Y-m-d H:i:s');

    $transaction_query = "INSERT INTO transactions 
                          (cart_id, transaction_status, shipping_address, total_unit, grand_total, buyer_name, created_at) 
                          VALUES ($cart_id, '$transaction_status', '$shipping_address', $total_units, $grand_total, '$buyer_name', '$created_at')";
    if ($mysqli->query($transaction_query)) {
        // Empty the cart
        $mysqli->query("DELETE FROM cart_items WHERE cart_id = $cart_id");

        echo "<script>alert('Checkout successful!'); window.location.href='transactions.php';</script>";
    } else {
        echo "Error during checkout: " . $mysqli->error;
    }
} else {
    echo "Cart not found!";
}
?>
