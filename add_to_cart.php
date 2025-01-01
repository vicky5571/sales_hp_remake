<?php
session_start();
include 'conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $imei = $conn->real_escape_string($_POST['imei']);
    $sold_price = $conn->real_escape_string($_POST['sold_price']);
    $cart_id = $_SESSION['cart_id'];

    // Debug: Check if IMEI exists in product_unit table
    $check_product_query = "SELECT * FROM product_unit WHERE IMEI = '$imei'";
    $check_product_result = $conn->query($check_product_query);

    if ($check_product_result->num_rows === 0) {
        die("Error: IMEI '$imei' does not exist in the product_unit table.");
    }

    // Check if the IMEI is already in the cart
    $check_cart_query = "SELECT * FROM cart_items WHERE cart_id = '$cart_id' AND imei = '$imei'";
    $check_cart_result = $conn->query($check_cart_query);

    if ($check_cart_result->num_rows > 0) {
        echo "This item is already in your cart.";
    } else {
        // Insert item into cart_items table
        $insert_query = "INSERT INTO cart_items (cart_id, sold_price, imei) VALUES ('$cart_id', '$sold_price', '$imei')";
        if ($conn->query($insert_query) === TRUE) {
            // Update cart quantity
            $update_cart_query = "UPDATE carts SET quantity = quantity + 1 WHERE cart_id = '$cart_id'";
            $conn->query($update_cart_query);

            // Update ADDED_TO_CART to 1
            $update_product_unit_query = "UPDATE product_unit SET ADDED_TO_CART = 1 WHERE IMEI = '$imei'";
            $conn->query($update_product_unit_query);

            echo "Item added to cart successfully!";
        } else {
            die("Error adding item to cart: " . $conn->error);
        }
    }
} else {
    echo "Invalid request method.";
}
