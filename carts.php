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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Your Cart</h1>
        <table class="table table-bordered">
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
                            <button class="btn btn-danger" onclick="confirmRemove(<?= $item['cart_item_id'] ?>)">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total Items: <?= count($cartItems) ?></p>
        <p>Total Price: <?= $totalPrice ?></p>
        <button class="btn btn-success" onclick="confirmCheckout()">Checkout</button>
    </div>

    <!-- Remove Confirmation Modal -->
    <div class="modal" id="removeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this item?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="remove_from_cart.php">
                        <input type="hidden" name="cart_item_id" id="removeCartItemId">
                        <button type="submit" class="btn btn-danger">Yes, Remove</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Confirmation Modal -->
    <div class="modal" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to checkout?</p>
                </div>
                <div class="modal-footer">
                    <a href="checkout.php" class="btn btn-success">Yes, Checkout</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmRemove(cartItemId) {
            document.getElementById('removeCartItemId').value = cartItemId;
            const modal = new bootstrap.Modal(document.getElementById('removeModal'));
            modal.show();
        }

        function confirmCheckout() {
            const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            modal.show();
        }
    </script>
</body>

</html>