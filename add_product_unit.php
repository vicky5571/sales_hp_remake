<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imei = $_POST['imei'];
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $buy_price = $_POST['buy_price'];
    $srp = $_POST['srp'];
    $description = $_POST['product_unit_description'];

    // Validate inputs
    if (empty($imei) || empty($product_id) || empty($supplier_id) || empty($buy_price) || empty($srp)) {
        echo "<script>
            alert('All fields are required!');
            window.history.back();
        </script>";
        exit();
    }

    // Insert into the database
    $query = "INSERT INTO PRODUCT_UNIT (IMEI, PRODUCT_ID, SUPPLIER_ID, BUY_PRICE, SRP, PRODUCT_UNIT_DESCRIPTION, DATE_STOCK_IN, ADDED_TO_CART)
              VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siidds", $imei, $product_id, $supplier_id, $buy_price, $srp, $description);

    if ($stmt->execute()) {
        echo "<script>
            alert('Product unit added successfully!');
            window.location.href = 'product_unit.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to add product unit. Please try again.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: product_unit.php");
    exit();
}
