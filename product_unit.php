<?php
include 'conn.php';

// Fetch data for dropdowns
$productsQuery = "SELECT PRODUCT_ID, PRODUCT_NAME, QUANTITY FROM PRODUCTS";
$productsResult = $conn->query($productsQuery);

$suppliersQuery = "SELECT SUPPLIER_ID, SUPPLIER_NAME FROM SUPPLIERS";
$suppliersResult = $conn->query($suppliersQuery);

// Fetch product units
$productUnitsQuery = "SELECT pu.IMEI, p.PRODUCT_NAME, s.SUPPLIER_NAME, pu.BUY_PRICE, pu.SRP, pu.PRODUCT_UNIT_DESCRIPTION, pu.DATE_STOCK_IN 
                      FROM PRODUCT_UNIT pu
                      JOIN PRODUCTS p ON pu.PRODUCT_ID = p.PRODUCT_ID
                      JOIN SUPPLIERS s ON pu.SUPPLIER_ID = s.SUPPLIER_ID";
$productUnitsResult = $conn->query($productUnitsQuery);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imei = $_POST['imei'];
    $productId = $_POST['product_id'];
    $supplierId = $_POST['supplier_id'];
    $buyPrice = $_POST['buy_price'];
    $srp = $_POST['srp'];
    $description = $_POST['product_unit_description'];
    $dateStockIn = date('Y-m-d H:i:s'); // Current date and time

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into product_unit table
        $insertQuery = "INSERT INTO PRODUCT_UNIT (IMEI, PRODUCT_ID, SUPPLIER_ID, BUY_PRICE, SRP, PRODUCT_UNIT_DESCRIPTION, DATE_STOCK_IN) 
                        VALUES ('$imei', '$productId', '$supplierId', '$buyPrice', '$srp', '$description', '$dateStockIn')";
        $conn->query($insertQuery);

        // Update quantity in products table
        $updateQuantityQuery = "UPDATE PRODUCTS SET QUANTITY = QUANTITY + 1 WHERE PRODUCT_ID = '$productId'";
        $conn->query($updateQuantityQuery);

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Product unit added successfully, and product quantity updated!'); window.location.href='product_unit.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Unit</title>
</head>

<body>
    <h1>Product Units</h1>

    <!-- Display Product Units Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IMEI</th>
                <th>Product Name</th>
                <th>Supplier Name</th>
                <th>Buy Price</th>
                <th>SRP</th>
                <th>Description</th>
                <th>Date Stock In</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($productUnit = $productUnitsResult->fetch_assoc()) : ?>
                <tr>
                    <td><?= $productUnit['IMEI']; ?></td>
                    <td><?= $productUnit['PRODUCT_NAME']; ?></td>
                    <td><?= $productUnit['SUPPLIER_NAME']; ?></td>
                    <td><?= $productUnit['BUY_PRICE']; ?></td>
                    <td><?= $productUnit['SRP']; ?></td>
                    <td><?= $productUnit['PRODUCT_UNIT_DESCRIPTION']; ?></td>
                    <td><?= $productUnit['DATE_STOCK_IN']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>

    <!-- Add Product Unit Form -->
    <h2>Add Product Unit</h2>
    <form method="POST" action="">
        <label for="imei">IMEI:</label>
        <input type="text" name="imei" id="imei" required>
        <br><br>

        <label for="product_id">Product:</label>
        <select name="product_id" id="product_id" required>
            <option value="">-- Select Product --</option>
            <?php while ($product = $productsResult->fetch_assoc()) : ?>
                <option value="<?= $product['PRODUCT_ID']; ?>"><?= $product['PRODUCT_NAME']; ?> (Quantity: <?= $product['QUANTITY']; ?>)</option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="supplier_id">Supplier:</label>
        <select name="supplier_id" id="supplier_id" required>
            <option value="">-- Select Supplier --</option>
            <?php while ($supplier = $suppliersResult->fetch_assoc()) : ?>
                <option value="<?= $supplier['SUPPLIER_ID']; ?>"><?= $supplier['SUPPLIER_NAME']; ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="buy_price">Buy Price:</label>
        <input type="number" name="buy_price" id="buy_price" step="0.01" required>
        <br><br>

        <label for="srp">SRP:</label>
        <input type="number" name="srp" id="srp" step="0.01" required>
        <br><br>

        <label for="product_unit_description">Description:</label>
        <textarea name="product_unit_description" id="product_unit_description" required></textarea>
        <br><br>

        <button type="submit">Add Product Unit</button>
    </form>
</body>

</html>
