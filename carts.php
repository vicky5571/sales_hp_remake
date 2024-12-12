<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Assuming user session is active

// Get the current cart for the user
$cart_query = "SELECT c.cart_id 
               FROM carts c 
               WHERE c.user_id = $user_id";
$cart_result = $mysqli->query($cart_query);

if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $cart_id = $cart['cart_id'];

    // Fetch cart items
    $cart_items_query = "SELECT ci.cart_item_id, pu.imei, p.product_name, pu.buy_price, pu.srp
                         FROM cart_items ci
                         JOIN product_unit pu ON ci.imei = pu.imei
                         JOIN products p ON pu.product_id = p.product_id
                         WHERE ci.cart_id = $cart_id";
    $cart_items_result = $mysqli->query($cart_items_query);
} else {
    echo "<p>No items in the cart.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <a href="index.php">Home</a>

    <?php if ($cart_items_result->num_rows > 0) : ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>IMEI</th>
                    <th>Product Name</th>
                    <th>Buy Price</th>
                    <th>SRP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $cart_items_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $item['imei']; ?></td>
                        <td><?= $item['product_name']; ?></td>
                        <td><?= $item['buy_price']; ?></td>
                        <td><?= $item['srp']; ?></td>
                        <td>
                            <form method="POST" action="remove_from_cart.php">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="checkout.php"><button>Checkout</button></a>
    <?php else : ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>
