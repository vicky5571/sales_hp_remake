<?php
include 'conn.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if `id` is passed in the query string
if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];

    // Prepare the delete query
    $deleteQuery = "DELETE FROM SUPPLIERS WHERE SUPPLIER_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $supplierId);

    if ($stmt->execute()) {
        // If the query is successful
        echo "<script>
            alert('Supplier deleted successfully!');
            window.location.href = 'suppliers.php';
        </script>";
    } else {
        // If an error occurs
        echo "<script>
            alert('Error: Unable to delete supplier. " . $conn->error . "');
            window.location.href = 'suppliers.php';
        </script>";
    }

    // Close the statement
    $stmt->close();
} else {
    // If `id` is not passed, redirect back to the suppliers page
    header("Location: suppliers.php");
    exit();
}

// Close the database connection
$conn->close();
