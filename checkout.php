<?php
// Include database connection
require_once 'conn.php';

session_start();

$user_role = $_SESSION['user_role'] ?? null;

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID and cart ID from session
$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'];

// Fetch cart items for the user
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

// Verify none of the products are already sold
foreach ($cartItems as $item) {
    if ($item['sold'] === 'yes') {
        echo "<script>alert('Some items in your cart are already sold.'); window.location.href='carts.php';</script>";
        exit();
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

// Move cart items to transaction_items and update product_unit
foreach ($cartItems as $item) {
    // Insert into transaction_items
    $insertTransactionItemQuery = "INSERT INTO transaction_items (cart_id, sold_price, imei) 
                                    VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertTransactionItemQuery);
    $stmt->bind_param("ids", $item['cart_id'], $item['sold_price'], $item['imei']);
    $stmt->execute();

    // Update product status to sold and reset ADDED_TO_CART
    $updateProductQuery = "UPDATE product_unit SET SOLD = 1, ADDED_TO_CART = 0 WHERE IMEI = ?";
    $stmt = $conn->prepare($updateProductQuery);
    $stmt->bind_param("s", $item['imei']);
    $stmt->execute();

    // Remove the cart item
    $deleteCartQuery = "DELETE FROM cart_items WHERE cart_item_id = ?";
    $stmt = $conn->prepare($deleteCartQuery);
    $stmt->bind_param("i", $item['cart_item_id']);
    $stmt->execute();
}

// Clear cart quantity after checkout
$updateCartQuery = "UPDATE carts SET QUANTITY = 0 WHERE CART_ID = ?";
$stmt = $conn->prepare($updateCartQuery);
$stmt->bind_param("i", $cart_id);
$stmt->execute();

// Redirect to a success page
// echo "<script>alert('Checkout successful! Your transaction ID is $transaction_id'); window.location.href='index.php';</script>";
?>
<?php if ($user_role === 'KARYAWAN'): ?>
    echo "<script>
        alert('Checkout successful! Your transaction ID is $transaction_id');
        window.location.href = 'index.php';
    </script>";
<?php else: ?>
    echo "<script>
        alert('Checkout successful! Your transaction ID is $transaction_id');
        window.location.href = 'transactions.php';
    </script>";
<?php endif; ?>