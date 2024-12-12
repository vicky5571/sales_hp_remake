<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imei = $_POST['imei'];
    $user_id = $_SESSION['user_id']; // Assuming user session is active

    // Get user's current cart or create a new one
    $cart_query = "SELECT cart_id FROM carts WHERE user_id = $user_id";
    $cart_result = $mysqli->query($cart_query);

    if ($cart_result->num_rows > 0) {
        $cart = $cart_result->fetch_assoc();
        $cart_id = $cart['cart_id'];
    } else {
        $mysqli->query("INSERT INTO carts (user_id) VALUES ($user_id)");
        $cart_id = $mysqli->insert_id;
    }

    // Add the product unit to cart_items
    $cart_item_query = "INSERT INTO cart_items (cart_id, product_id) 
                        SELECT $cart_id, product_id FROM product_unit WHERE imei = '$imei'";
    if ($mysqli->query($cart_item_query)) {
        echo "Product added to cart!";
    } else {
        echo "Error adding to cart: " . $mysqli->error;
    }
}
?>
