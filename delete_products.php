<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    $updateQuery = "UPDATE PRODUCTS SET IS_DELETED = 1 WHERE PRODUCT_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo "<script>alert('Product marked as deleted successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();

// include 'conn.php';
// session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// if (isset($_GET['id'])) {
//     $productId = $_GET['id'];

//     $deleteQuery = "DELETE FROM PRODUCTS WHERE PRODUCT_ID = ?";
//     $stmt = $conn->prepare($deleteQuery);
//     $stmt->bind_param("i", $productId);

//     if ($stmt->execute()) {
//         echo "<script>alert('Product deleted successfully!'); window.location.href='products.php';</script>";
//     } else {
//         echo "<script>alert('Error: " . $conn->error . "');</script>";
//     }

//     $stmt->close();
// }
// $conn->close();
