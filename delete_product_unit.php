<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imei = $_POST['imei'];

    // Update the IS_DELETED column for the product unit
    $updateQuery = "UPDATE PRODUCT_UNIT SET IS_DELETED = 1 WHERE IMEI = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("s", $imei);

    if ($stmt->execute()) {
        // Check the SOLD status of the product unit
        $checkSoldQuery = "SELECT PRODUCT_ID, SOLD FROM PRODUCT_UNIT WHERE IMEI = ?";
        $checkStmt = $conn->prepare($checkSoldQuery);
        $checkStmt->bind_param("s", $imei);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $productUnit = $result->fetch_assoc();

        if ($productUnit && $productUnit['SOLD'] == 0) {
            // Reduce the quantity by 1 in the PRODUCTS table if SOLD is 0
            $productId = $productUnit['PRODUCT_ID'];
            $updateQuantityQuery = "UPDATE PRODUCTS SET QUANTITY = QUANTITY - 1 WHERE PRODUCT_ID = ?";
            $quantityStmt = $conn->prepare($updateQuantityQuery);
            $quantityStmt->bind_param("i", $productId);
            $quantityStmt->execute();
            $quantityStmt->close();
        }

        $_SESSION['success_message'] = "Product unit marked as deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete the product unit.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to product_unit.php
    header("Location: product_unit.php");
    exit();
}


// include 'conn.php';
// session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $imei = $_POST['imei'];

//     // Delete the product unit
//     $deleteQuery = "DELETE FROM PRODUCT_UNIT WHERE IMEI = ?";
//     $stmt = $conn->prepare($deleteQuery);
//     $stmt->bind_param("s", $imei);

//     if ($stmt->execute()) {
//         $_SESSION['success_message'] = "Product unit deleted successfully.";
//     } else {
//         $_SESSION['error_message'] = "Failed to delete the product unit.";
//     }

//     $stmt->close();
//     $conn->close();

//     // Redirect back to product_unit.php
//     header("Location: product_unit.php");
//     exit();
// }
