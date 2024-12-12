<?php 
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cartId = $_SESSION['cart_id'] ?? 1;

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $imeiToRemove = $_POST['remove_item'];
    
    // Remove the item from CART_ITEMS
    $removeQuery = "DELETE FROM CART_ITEMS WHERE IMEI = '$imeiToRemove' AND CART_ID = '$cartId'";
    $conn->query($removeQuery);
    
    // Set `added_to_cart` to false in PRODUCT_UNIT
    $updateProductQuery = "UPDATE PRODUCT_UNIT SET added_to_cart = 0 WHERE IMEI = '$imeiToRemove'";
    $conn->query($updateProductQuery);
    
    // Update the cart quantity
    $updateCartQuery = "UPDATE CARTS 
                        SET QUANTITY = (SELECT COUNT(*) FROM CART_ITEMS WHERE CART_ID = '$cartId') 
                        WHERE CART_ID = '$cartId'";
    $conn->query($updateCartQuery);
    
    // Redirect to avoid resubmission on refresh
    header("Location: carts.php");
    exit();
}

// Fetch cart items
$cartItemsQuery = "SELECT ci.IMEI, pu.PRODUCT_UNIT_DESCRIPTION, pu.BUY_PRICE, pu.SRP 
                   FROM CART_ITEMS ci 
                   JOIN PRODUCT_UNIT pu ON ci.IMEI = pu.IMEI 
                   WHERE ci.CART_ID = '$cartId'";
$cartItemsResult = $conn->query($cartItemsQuery);

// Calculate total price
$totalPrice = 0;
$cartItems = [];
while ($item = $cartItemsResult->fetch_assoc()) {
    $cartItems[] = $item;
    $totalPrice += $item['SRP'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .button { padding: 6px 12px; background-color: red; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Your Cart</h1>
    <table>
        <thead>
            <tr>
                <th>IMEI</th>
                <th>Description</th>
                <th>Buy Price</th>
                <th>SRP</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($cartItems) > 0): ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['IMEI']); ?></td>
                        <td><?= htmlspecialchars($item['PRODUCT_UNIT_DESCRIPTION']); ?></td>
                        <td><?= htmlspecialchars($item['BUY_PRICE']); ?></td>
                        <td><?= htmlspecialchars($item['SRP']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <button type="submit" class="button" name="remove_item" value="<?= htmlspecialchars($item['IMEI']); ?>">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Your cart is empty.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <p><strong>Total Price:</strong> <?= $totalPrice; ?></p>
</body>
</html>
