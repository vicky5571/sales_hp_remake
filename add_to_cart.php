<?php
include 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imei = $conn->real_escape_string($_POST['imei']);
    $sold_price = $conn->real_escape_string($_POST['sold_price']);
    $user_id = $_SESSION['user_id'];

    // Check for an existing cart
    $cart_query = "SELECT cart_id FROM carts WHERE user_id = $user_id";
    $cart_result = $conn->query($cart_query);

    if ($cart_result->num_rows > 0) {
        $cart_id = $cart_result->fetch_assoc()['cart_id'];
    } else {
        // Create a new cart
        $conn->query("INSERT INTO carts (user_id, quantity) VALUES ($user_id, 0)");
        $cart_id = $conn->insert_id;
    }

    // Add the item to cart_items
    $conn->query("INSERT INTO cart_items (cart_id, sold_price, imei) 
                  VALUES ($cart_id, $sold_price, '$imei')");

    // Update the quantity in carts
    $conn->query("UPDATE carts SET quantity = quantity + 1 WHERE cart_id = $cart_id");

    header("Location: carts.php");
    exit();
}
?>
