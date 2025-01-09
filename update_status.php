<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transactionId = $_POST['transactions_id'];
    $transactionStatus = $_POST['transaction_status'];

    // Update the transaction status in the database
    $query = "UPDATE transactions SET transaction_status = ? WHERE transactions_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $transactionStatus, $transactionId);

    if ($stmt->execute()) {
        echo '<script>alert("Transaction status updated successfully."); window.location.href="transactions.php";</script>';
    } else {
        echo '<script>alert("Failed to update transaction status."); window.location.href="transactions.php";</script>';
    }
}
