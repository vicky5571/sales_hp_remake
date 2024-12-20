<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'];

// Fetch cart details
$cartQuery = "SELECT ci.cart_item_id, ci.sold_price, ci.IMEI, pu.SRP 
              FROM cart_items ci
              JOIN product_unit pu ON ci.IMEI = pu.IMEI
              WHERE ci.cart_id = '$cart_id'";
$cartResult = $conn->query($cartQuery);

// Initialize variables
$totalPrice = 0;
$totalItems = 0;
$cartItems = [];

// Gather cart data
while ($item = $cartResult->fetch_assoc()) {
    $cartItems[] = $item;
    $totalPrice += $item['SRP'];
    $totalItems++;
}

// Handle checkout process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $buyerName = $conn->real_escape_string($_POST['buyer_name']);
    $shippingAddress = $conn->real_escape_string($_POST['shipping_address']);
    $transactionStatus = "Completed"; // Default status

    $conn->begin_transaction();

    try {
        // Insert into transactions table
        $insertTransaction = "INSERT INTO transactions 
                              (cart_id, transaction_status, shipping_address, total_unit, grand_total, buyer_name, created_at) 
                              VALUES 
                              ('$cart_id', '$transactionStatus', '$shippingAddress', '$totalItems', '$totalPrice', '$buyerName', NOW())";
        $conn->query($insertTransaction);

        // Get the transaction ID
        $transactionId = $conn->insert_id;

        // Mark cart as cleared
        $clearCartItems = "DELETE FROM cart_items WHERE cart_id = '$cart_id'";
        $conn->query($clearCartItems);

        // Reset product_unit `added_to_cart`
        foreach ($cartItems as $item) {
            $imei = $item['IMEI'];
            $resetAddedToCart = "UPDATE product_unit SET added_to_cart = FALSE WHERE imei = '$imei'";
            $conn->query($resetAddedToCart);
        }

        // Update cart quantity to zero
        $resetCartQuantity = "UPDATE carts SET quantity = 0 WHERE cart_id = '$cart_id'";
        $conn->query($resetCartQuantity);

        $conn->commit();

        echo "<script>alert('Checkout successful! Transaction ID: $transactionId'); window.location.href = 'product_unit.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error during checkout: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>
    <h1>Checkout</h1>
    <form method="POST" action="">
        <label for="buyer_name">Buyer Name:</label>
        <input type="text" name="buyer_name" id="buyer_name" required>
        <br><br>

        <label for="shipping_address">Shipping Address:</label>
        <textarea name="shipping_address" id="shipping_address" required></textarea>
        <br><br>

        <p>Total Items: <?= $totalItems ?></p>
        <p>Total Price: <?= $totalPrice ?></p>
        <button type="submit" name="checkout">Confirm Checkout</button>
    </form>
</body>

</html>
