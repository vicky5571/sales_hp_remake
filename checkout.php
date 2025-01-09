<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'];

// Get form data
$transaction_status = $_POST['transaction_status'] ?? 'done';
$shipping_address = $_POST['shipping_address'] ?? 'in store';
$total_unit = $_POST['total_unit'] ?? 0;
$buyer_name = $_POST['buyer_name'] ?? '';

if (!$buyer_name || $total_unit == 0) {
    echo "<script>alert('Invalid form data.'); window.location.href='carts.php';</script>";
    exit();
}

// Fetch cart items
$cartQuery = "SELECT ci.cart_item_id, ci.cart_id, ci.sold_price, ci.imei, pu.sold 
              FROM cart_items ci
              JOIN product_unit pu ON ci.imei = pu.imei
              WHERE ci.cart_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cartItems)) {
    echo "<script>alert('Cart is empty.'); window.location.href='carts.php';</script>";
    exit();
}

// Calculate grand total
$grand_total = array_sum(array_column($cartItems, 'sold_price'));

// Insert transaction
$transactionQuery = "INSERT INTO transactions (user_id, cart_id, transaction_status, shipping_address, total_unit, grand_total, buyer_name, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($transactionQuery);
$stmt->bind_param("iissdss", $user_id, $cart_id, $transaction_status, $shipping_address, $total_unit, $grand_total, $buyer_name);
$stmt->execute();
$transaction_id = $stmt->insert_id;

// Move cart items to transaction_items and update product_unit
foreach ($cartItems as $item) {
    $insertTransactionItemQuery = "INSERT INTO transaction_items (cart_id, sold_price, imei) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertTransactionItemQuery);
    $stmt->bind_param("ids", $item['cart_id'], $item['sold_price'], $item['imei']);
    $stmt->execute();

    $updateProductQuery = "UPDATE product_unit SET SOLD = 1, ADDED_TO_CART = 0 WHERE IMEI = ?";
    $stmt->prepare($updateProductQuery);
    $stmt->bind_param("s", $item['imei']);
    $stmt->execute();

    $deleteCartQuery = "DELETE FROM cart_items WHERE cart_item_id = ?";
    $stmt->prepare($deleteCartQuery);
    $stmt->bind_param("i", $item['cart_item_id']);
    $stmt->execute();
}

// Clear cart quantity
$updateCartQuery = "UPDATE carts SET QUANTITY = 0 WHERE CART_ID = ?";
$stmt = $conn->prepare($updateCartQuery);
$stmt->bind_param("i", $cart_id);
$stmt->execute();

// echo "<script>alert('Checkout successful! Your transaction ID is $transaction_id'); window.location.href='transactions.php';</script>";

if ($user_role === 'KARYAWAN'):
    echo "<script>
        alert('Checkout successful! Your transaction ID is $transaction_id');
        window.location.href = 'index.php';
    </script>";
else:
    echo "<script>
        alert('Checkout successful! Your transaction ID is $transaction_id');
        window.location.href = 'transactions.php';
    </script>";
endif;
