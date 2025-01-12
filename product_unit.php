<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch data for dropdowns
$productsQuery = "SELECT PRODUCT_ID, PRODUCT_NAME, COLOR, QUANTITY FROM PRODUCTS";
$productsResult = $conn->query($productsQuery);

$suppliersQuery = "SELECT SUPPLIER_ID, SUPPLIER_NAME FROM SUPPLIERS";
$suppliersResult = $conn->query($suppliersQuery);

// Fetch product units
$productUnitsQuery = "SELECT pu.IMEI, p.PRODUCT_NAME,p.COLOR , s.SUPPLIER_NAME, pu.BUY_PRICE, pu.SRP, 
                      pu.PRODUCT_UNIT_DESCRIPTION, pu.DATE_STOCK_IN, pu.ADDED_TO_CART, pu.SOLD 
                      FROM PRODUCT_UNIT pu
                      JOIN PRODUCTS p ON pu.PRODUCT_ID = p.PRODUCT_ID
                      JOIN SUPPLIERS s ON pu.SUPPLIER_ID = s.SUPPLIER_ID";
$productUnitsResult = $conn->query($productUnitsQuery);

// Handle form edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product_unit'])) {
    $imei = $_POST['edit_imei'];
    $description = $_POST['edit_description'];
    $buyPrice = $_POST['edit_buy_price'];
    $srp = $_POST['edit_srp'];

    $updateQuery = "UPDATE PRODUCT_UNIT SET PRODUCT_UNIT_DESCRIPTION = ?, BUY_PRICE = ?, SRP = ? WHERE IMEI = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sdds", $description, $buyPrice, $srp, $imei);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product unit updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update the product unit.";
    }

    $stmt->close();
    header("Location: product_unit.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Units</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./src/style.css">
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

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>
    <div class="container container-for-bg rounded border border-primary" style="margin-top: 13vh">
        <div id="addProductUnitSection" class="container mt-3">
            <div class="row">
                <div class="col-12 mb-3" style="position: sticky; top: 0; z-index: 1000;">
                    <h2>Add Product Unit</h2>
                    <form method="POST" action="add_product_unit.php">
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label for="imei" class="form-label">IMEI:</label>
                                <input type="text" name="imei" id="imei" class="form-control" required>
                            </div>
                            <div class="mb-3 col-md-9">
                                <label for="product_unit_description" class="form-label">Description:</label>
                                <textarea name="product_unit_description" id="product_unit_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label for="product_id" class="form-label">Product:</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    <?php while ($product = $productsResult->fetch_assoc()) : ?>
                                        <option value="<?= $product['PRODUCT_ID']; ?>"><?= $product['PRODUCT_NAME'];  ?> <?= $product['COLOR'];  ?> </option>
                                        <!-- (Quantity: <?= $product['QUANTITY']; ?>) -->
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" id="supplier_id" class="form-select" required>
                                    <option value="">-- Select Supplier --</option>
                                    <?php while ($supplier = $suppliersResult->fetch_assoc()) : ?>
                                        <option value="<?= $supplier['SUPPLIER_ID']; ?>"><?= $supplier['SUPPLIER_NAME']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="buy_price" class="form-label">Buy Price:</label>
                                <input type="number" name="buy_price" id="buy_price" class="form-control" step="0.01" required>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="srp" class="form-label">SRP:</label>
                                <input type="number" name="srp" id="srp" class="form-control" step="0.01" required>
                            </div>
                        </div>

                        <button type="submit" name="add_product_unit" class="btn btn-primary">Add Product Unit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4 bg-light">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
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
                            <td><?= htmlspecialchars($productUnit['PRODUCT_NAME']); ?> - <?= htmlspecialchars($productUnit['COLOR']); ?></td>
                            <td><?= htmlspecialchars($productUnit['SUPPLIER_NAME']); ?></td>
                            <td><?= number_format($productUnit['BUY_PRICE'], 2, '.', ','); ?></td>
                            <td><?= number_format($productUnit['SRP'], 2, '.', ','); ?></td>
                            <td><?= htmlspecialchars($productUnit['PRODUCT_UNIT_DESCRIPTION']); ?></td>
                            <td><?= htmlspecialchars($productUnit['DATE_STOCK_IN']); ?></td>

                            <td>
                                <?php if ($productUnit['SOLD'] == 1) : ?>
                                    <button class="btn btn-primary" disabled>SOLD</button>
                                <?php elseif (!$productUnit['ADDED_TO_CART']) : ?>
                                    <button class="btn btn-success" onclick="showSoldPriceModal('<?= htmlspecialchars($productUnit['IMEI']); ?>')">Add to Cart</button>
                                <?php elseif ($productUnit['ADDED_TO_CART'] == 1) : ?>
                                    <form action="remove_from_cart_direct.php" method="post" onsubmit="return confirmDelete();">
                                        <input type="hidden" name="imei" value="<?= htmlspecialchars($productUnit['IMEI']); ?>">
                                        <button type="submit" class="btn btn-danger">Remove from Cart</button>
                                    </form>
                                <?php endif; ?>
                            </td>


                            <td>
                                <!-- <a href="edit_product_unit.php?imei=<?= htmlspecialchars($productUnit['IMEI']); ?>" class="btn btn-warning">Edit</a> -->
                                <a href="#" class="btn btn-warning"
                                    onclick="showEditModal('<?= htmlspecialchars($productUnit['IMEI']); ?>', 
                          '<?= htmlspecialchars($productUnit['PRODUCT_UNIT_DESCRIPTION']); ?>', 
                          <?= htmlspecialchars($productUnit['BUY_PRICE']); ?>, 
                          <?= htmlspecialchars($productUnit['SRP']); ?>)">
                                    Edit
                                </a>

                                <form action="delete_product_unit.php" method="post" style="display:inline;" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="imei" value="<?= htmlspecialchars($productUnit['IMEI']); ?>">
                                    <button type="submit" class="btn btn-danger mt-1">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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

    <!-- Modal for Editing Product Unit -->
    <div class="modal fade" id="editProductUnitModal" tabindex="-1" aria-labelledby="editProductUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductUnitModalLabel">Edit Product Unit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_imei" id="edit_imei">
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description:</label>
                            <textarea name="edit_description" id="edit_description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_buy_price" class="form-label">Buy Price:</label>
                            <input type="number" name="edit_buy_price" id="edit_buy_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_srp" class="form-label">SRP:</label>
                            <input type="number" name="edit_srp" id="edit_srp" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_product_unit" class="btn btn-primary">Save Changes</button>
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

        function confirmRemove(cartItemId) {
            document.getElementById('removeCartItemId').value = cartItemId;
            const modal = new bootstrap.Modal(document.getElementById('removeModal'));
            modal.show();
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this product?");
        }

        function showEditModal(imei, description, buyPrice, srp) {
            document.getElementById('edit_imei').value = imei;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_buy_price').value = buyPrice;
            document.getElementById('edit_srp').value = srp;

            var editModal = new bootstrap.Modal(document.getElementById('editProductUnitModal'));
            editModal.show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>