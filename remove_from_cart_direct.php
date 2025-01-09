<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the IMEI from the POST request
    $imei = $_POST['imei'];

    if ($imei) {
        // Start a transaction to ensure data consistency
        $conn->begin_transaction();

        try {
            // Update the ADDED_TO_CART field to 0 for the given IMEI
            $updateQuery = "UPDATE product_unit SET ADDED_TO_CART = 0 WHERE IMEI = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("s", $imei);
            $stmt->execute();
            $stmt->close();

            // Delete the corresponding row from cart_items
            $deleteQuery = "DELETE FROM cart_items WHERE IMEI = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("s", $imei);
            $stmt->execute();
            $stmt->close();

            // Commit the transaction
            $conn->commit();

            echo "<script>alert('Item removed from cart successfully.'); window.location.href='product_unit.php';</script>";
        } catch (Exception $e) {
            // Roll back the transaction in case of an error
            $conn->rollback();
            echo "<script>alert('Error removing item.'); window.location.href='product_unit.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid IMEI.'); window.location.href='product_unit.php';</script>";
    }
} else {
    header("Location: product_unit.php");
    exit();
}
