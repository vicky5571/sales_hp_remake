<?php
// Include database connection
require_once 'conn.php';

// Start session (assuming you're using sessions for user tracking)
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Fetch user ID and cart ID from session
$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'];

// Fetch cart items for the user
$cartQuery = "SELECT ci.cart_item_id, ci.cart_id, ci.sold_price, ci.imei, pu.SRP, pu.sold 
              FROM cart_items ci
              JOIN product_unit pu ON ci.imei = pu.imei
              WHERE ci.cart_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cartItems)) {
    die("Cart is empty.");
}

// Verify none of the products are already sold
foreach ($cartItems as $item) {
    if ($item['sold'] === 'yes') {
        die("Some items in your cart are already sold.");
    }
}

// Calculate transaction details
$shipping_address = "123 Example St, City"; // Example address, replace with input
$buyer_name = "John Doe"; // Example name, replace with input
$total_unit = count($cartItems);
$grand_total = array_sum(array_column($cartItems, 'sold_price'));

// Insert transaction details
$transactionQuery = "INSERT INTO transactions (user_id, cart_id, transaction_status, shipping_address, total_unit, grand_total, buyer_name, created_at)
                     VALUES (?, ?, 'Pending', ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($transactionQuery);
$stmt->bind_param("iissds", $user_id, $cart_id, $shipping_address, $total_unit, $grand_total, $buyer_name);
$stmt->execute();
$transaction_id = $stmt->insert_id;

// Update product `sold` status and clear cart items
foreach ($cartItems as $item) {
    // Update product status to sold
    $updateSoldQuery = "UPDATE product_unit SET sold = 'yes' WHERE imei = ?";
    $stmt = $conn->prepare($updateSoldQuery);
    $stmt->bind_param("s", $item['imei']);
    $stmt->execute();

    // Remove the cart item
    $deleteCartQuery = "DELETE FROM cart_items WHERE cart_item_id = ?";
    $stmt = $conn->prepare($deleteCartQuery);
    $stmt->bind_param("i", $item['cart_item_id']);
    $stmt->execute();
}

// Redirect to a success page or display confirmation
echo "Checkout successful! Your transaction ID is " . $transaction_id;
?>
