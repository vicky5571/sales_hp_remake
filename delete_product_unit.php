<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imei = $_POST['imei'];

    // Delete the product unit
    $deleteQuery = "DELETE FROM PRODUCT_UNIT WHERE IMEI = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $imei);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product unit deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete the product unit.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to product_unit.php
    header("Location: product_unit.php");
    exit();
}
