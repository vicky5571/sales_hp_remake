<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'];

// Fetch cart items
$cartItemsQuery = "SELECT ci.cart_item_id, ci.sold_price, ci.IMEI, pu.PRODUCT_UNIT_DESCRIPTION, pu.BUY_PRICE, pu.SRP 
                   FROM cart_items ci
                   JOIN product_unit pu ON ci.IMEI = pu.IMEI
                   WHERE ci.cart_id = '$cart_id'";
$cartItemsResult = $conn->query($cartItemsQuery);

// Calculate total price
$totalPrice = 0;
$cartItems = [];
while ($item = $cartItemsResult->fetch_assoc()) {
    $cartItems[] = $item;
    $totalPrice += $item['SRP'];
}

// Update quantity dynamically
$updateQuantityQuery = "UPDATE carts SET quantity = " . count($cartItems) . " WHERE cart_id = '$cart_id'";
$conn->query($updateQuantityQuery);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
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
                <th>Sold Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= $item['IMEI'] ?></td>
                    <td><?= $item['PRODUCT_UNIT_DESCRIPTION'] ?></td>
                    <td><?= $item['BUY_PRICE'] ?></td>
                    <td><?= $item['SRP'] ?></td>
                    <td><?= $item['sold_price']; ?></td>
                    <td>
                        <form method="POST" action="remove_cart_item.php">
                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Total Items: <?= count($cartItems) ?></p>
    <p>Total Price: <?= $totalPrice ?></p>
    <a href="checkout.php">Checkout</a>
</body>

</html>