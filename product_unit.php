<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch data for dropdowns
$productsQuery = "SELECT PRODUCT_ID, PRODUCT_NAME, QUANTITY FROM PRODUCTS";
$productsResult = $conn->query($productsQuery);

$suppliersQuery = "SELECT SUPPLIER_ID, SUPPLIER_NAME FROM SUPPLIERS";
$suppliersResult = $conn->query($suppliersQuery);

// Fetch product units
$productUnitsQuery = "SELECT pu.IMEI, p.PRODUCT_NAME, s.SUPPLIER_NAME, pu.BUY_PRICE, pu.SRP, 
                      pu.PRODUCT_UNIT_DESCRIPTION, pu.DATE_STOCK_IN, pu.ADDED_TO_CART 
                      FROM PRODUCT_UNIT pu
                      JOIN PRODUCTS p ON pu.PRODUCT_ID = p.PRODUCT_ID
                      JOIN SUPPLIERS s ON pu.SUPPLIER_ID = s.SUPPLIER_ID";
$productUnitsResult = $conn->query($productUnitsQuery);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Units</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #addProductUnitSection {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div id="addProductUnitSection" class="container">
        <h2>Add Product Unit</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="imei" class="form-label">IMEI:</label>
                <input type="text" name="imei" id="imei" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="product_id" class="form-label">Product:</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    <?php while ($product = $productsResult->fetch_assoc()) : ?>
                        <option value="<?= $product['PRODUCT_ID']; ?>"><?= $product['PRODUCT_NAME']; ?> (Quantity: <?= $product['QUANTITY']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier:</label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">-- Select Supplier --</option>
                    <?php while ($supplier = $suppliersResult->fetch_assoc()) : ?>
                        <option value="<?= $supplier['SUPPLIER_ID']; ?>"><?= $supplier['SUPPLIER_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="buy_price" class="form-label">Buy Price:</label>
                <input type="number" name="buy_price" id="buy_price" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="srp" class="form-label">SRP:</label>
                <input type="number" name="srp" id="srp" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="product_unit_description" class="form-label">Description:</label>
                <textarea name="product_unit_description" id="product_unit_description" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_product_unit" class="btn btn-primary">Add Product Unit</button>
        </form>
    </div>

    <div class="container mt-4">
        <h1>Product Units</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>IMEI</th>
                    <th>Product Name</th>
                    <th>Supplier Name</th>
                    <th>Buy Price</th>
                    <th>SRP</th>
                    <th>Description</th>
                    <th>Date Stock In</th>
                    <th>Action</th>
                    <th>Additional Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($productUnit = $productUnitsResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($productUnit['IMEI']); ?></td>
                        <td><?= htmlspecialchars($productUnit['PRODUCT_NAME']); ?></td>
                        <td><?= htmlspecialchars($productUnit['SUPPLIER_NAME']); ?></td>
                        <td><?= htmlspecialchars($productUnit['BUY_PRICE']); ?></td>
                        <td><?= htmlspecialchars($productUnit['SRP']); ?></td>
                        <td><?= htmlspecialchars($productUnit['PRODUCT_UNIT_DESCRIPTION']); ?></td>
                        <td><?= htmlspecialchars($productUnit['DATE_STOCK_IN']); ?></td>
                        <td>
                            <?php if (!$productUnit['ADDED_TO_CART']) : ?>
                                <button class="btn btn-success" onclick="showSoldPriceModal('<?= htmlspecialchars($productUnit['IMEI']); ?>')">Add to Cart</button>
                            <?php else : ?>
                                <form action="remove_from_cart.php" method="post">
                                    <input type="hidden" name="imei" value="<?= htmlspecialchars($productUnit['IMEI']); ?>">
                                    <button type="submit" class="btn btn-danger">Remove from Cart</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product_unit.php?imei=<?= htmlspecialchars($productUnit['IMEI']); ?>" class="btn btn-warning">Edit</a>
                            <form action="delete_product_unit.php" method="post" style="display:inline;">
                                <input type="hidden" name="imei" value="<?= htmlspecialchars($productUnit['IMEI']); ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Sold Price -->
    <div class="modal fade" id="soldPriceModal" tabindex="-1" aria-labelledby="soldPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_to_cart.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="soldPriceModalLabel">Add Product Unit to Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="imei" id="modalImei">
                        <div class="mb-3">
                            <label for="sold_price" class="form-label">Sold Price:</label>
                            <input type="number" name="sold_price" id="sold_price" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showSoldPriceModal(imei) {
            document.getElementById('modalImei').value = imei;
            var modal = new bootstrap.Modal(document.getElementById('soldPriceModal'));
            modal.show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>