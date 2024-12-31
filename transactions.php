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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">All Transactions</h1>
        <a href="index.php" class="btn btn-primary mb-3">Home</a>
        <button class="btn btn-success mb-3" onclick="printPage()">Print</button>
        <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
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
                                    <table class="table table-sm">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Transaction Item ID</th>
                                                <th>Cart ID</th>
                                                <th>Sold Price</th>
                                                <th>IMEI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
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
            </div>
        <?php else: ?>
            <p class="alert alert-warning text-center">No transactions found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
