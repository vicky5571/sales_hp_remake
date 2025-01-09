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
    $totalPrice += $item['sold_price'];
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
    <link rel="stylesheet" href="./src/style.css">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>

    <style>
        .summary-container {
            background-color: #f8f9fa;
            /* Light background */
            border-radius: 15px;
            /* Rounded corners */
            padding: 20px;
            /* Space inside the div */
            margin-top: 20px;
            /* Space above the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            text-align: left;
            /* Center the text */
            font-family: inherit;
            /* Inherit font-family from parent or match the table */
            font-weight: bold;
            /* Match the <thead> bold text */
            width: 100%;
            /* Full width by default */
            max-width: 400px;
            /* Limit the maximum width */
        }

        .summary-container p {
            font-size: 1.1rem;
            /* Adjust to match <thead> font size */
            margin: 10px 0;
            /* Space between paragraphs */
            color: #000;
            /* Ensure text color matches <thead> */
        }

        .summary-container .btn {
            margin-top: 10px;
            /* Space above the button */
            padding: 10px 20px;
            /* Comfortable button size */
            font-size: 1rem;
            /* Button text size matches table head */
            font-weight: bold;
            /* Bold text to match <thead> */
        }
    </style>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container container-for-bg rounded border border-primary " style="margin-top: 13vh">
        <div class="table-responsive mt-4 bg-light" style="max-height: 800px; overflow-y: auto;">
            <table class="table table-striped table-bordered mb-4">
                <thead class="table-dark">
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
                            <td><?= number_format($item['BUY_PRICE'], 2, '.', ','); ?></td>
                            <td><?= number_format($item['SRP'], 2, '.', ','); ?></td>
                            <td><?= number_format($item['sold_price'], 2, '.', ','); ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="confirmRemove(<?= $item['cart_item_id'] ?>)">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="summary-container">
            <p>Total Items : <?= count($cartItems) ?></p>
            <p>Total Price: Rp<?= number_format($totalPrice, 2, ',', '.') ?></p>
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

        <!-- Checkout Form Modal -->
        <div class="modal" id="checkoutFormModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="checkout.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Checkout</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="transactionStatus" class="form-label">Transaction Status</label>
                                <select name="transaction_status" id="transactionStatus" class="form-select">
                                    <option value="done" selected>Done</option>
                                    <option value="on the way">On the Way</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="shippingAddress" class="form-label">Shipping Address</label>
                                <input type="text" name="shipping_address" id="shippingAddress" class="form-control" value="in store">
                            </div>
                            <div class="mb-3">
                                <label for="totalUnit" class="form-label">Total Unit</label>
                                <input type="number" name="total_unit" id="totalUnit" class="form-control" value="<?= count($cartItems) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="buyerName" class="form-label">Buyer Name</label>
                                <input type="text" name="buyer_name" id="buyerName" class="form-control" placeholder="Enter buyer name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Confirm Checkout</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
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
                const modal = new bootstrap.Modal(document.getElementById('checkoutFormModal'));
                modal.show();
            }
        </script>
</body>

</html>