<?php
// Include database connection
require_once 'conn.php';

// Fetch all transactions
$transactionQuery = "SELECT * FROM transactions ORDER BY created_at DESC";
$stmt = $conn->prepare($transactionQuery);
$stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Transactions</title>
    <style>
        button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <h1>All Transactions</h1>
    <a href="index.php">Home</a>
    <button onclick="printPage()">Print</button>
    <?php if (!empty($transactions)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Cart ID</th>
                    <th>Status</th>
                    <th>Shipping Address</th>
                    <th>Total Units</th>
                    <th>Grand Total</th>
                    <th>Buyer Name</th>
                    <th>Created At</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['TRANSACTIONS_ID']); ?></td>
                        <td><?= htmlspecialchars($transaction['CART_ID']); ?></td>
                        <td><?= htmlspecialchars($transaction['TRANSACTION_STATUS']); ?></td>
                        <td><?= htmlspecialchars($transaction['SHIPPING_ADDRESS']); ?></td>
                        <td><?= htmlspecialchars($transaction['TOTAL_UNIT']); ?></td>
                        <td><?= htmlspecialchars($transaction['GRAND_TOTAL']); ?></td>
                        <td><?= htmlspecialchars($transaction['BUYER_NAME']); ?></td>
                        <td><?= htmlspecialchars($transaction['CREATED_AT']); ?></td>
                        <td>
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>Transaction Item ID</th>
                                        <th>Cart ID</th>
                                        <th>Sold Price</th>
                                        <th>IMEI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch transaction items for this transaction's cart_id
                                    $transactionItemQuery = "SELECT * FROM transaction_items WHERE cart_id = ?";
                                    $itemStmt = $conn->prepare($transactionItemQuery);
                                    $itemStmt->bind_param("i", $transaction['CART_ID']);
                                    $itemStmt->execute();
                                    $transactionItems = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);

                                    foreach ($transactionItems as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['TRANSACTION_ITEM_ID']); ?></td>
                                            <td><?= htmlspecialchars($item['CART_ID']); ?></td>
                                            <td><?= htmlspecialchars($item['SOLD_PRICE']); ?></td>
                                            <td><?= htmlspecialchars($item['IMEI']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No transactions found.</p>
    <?php endif; ?>
</body>
</html>
