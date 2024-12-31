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
                            <th rowspan="2">Transaction ID</th>
                            <th rowspan="2">Cart ID</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Shipping Address</th>
                            <th rowspan="2">Total Units</th>
                            <th rowspan="2">Grand Total</th>
                            <th rowspan="2">Buyer Name</th>
                            <th rowspan="2">Created At</th>
                            <th colspan="4">Items</th>
                        </tr>
                        <tr>
                            <th>Transaction Item ID</th>
                            <th>Cart ID</th>
                            <th>Sold Price</th>
                            <th>IMEI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <?php
                            // Fetch items for the current transaction
                            $transactionItemQuery = "SELECT * FROM transaction_items WHERE cart_id = ?";
                            $itemStmt = $conn->prepare($transactionItemQuery);
                            $itemStmt->bind_param("i", $transaction['CART_ID']);
                            $itemStmt->execute();
                            $transactionItems = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            $itemCount = count($transactionItems);
                            ?>
                            <tr>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['TRANSACTIONS_ID']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['CART_ID']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['TRANSACTION_STATUS']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['SHIPPING_ADDRESS']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['TOTAL_UNIT']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['GRAND_TOTAL']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['BUYER_NAME']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['CREATED_AT']); ?></td>
                                <?php if (!empty($transactionItems)): ?>
                                    <td><?= htmlspecialchars($transactionItems[0]['TRANSACTION_ITEM_ID']); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['CART_ID']); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['SOLD_PRICE']); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['IMEI']); ?></td>
                                <?php else: ?>
                                    <td colspan="4" class="text-center">No items found</td>
                                <?php endif; ?>
                            </tr>
                            <?php if (!empty($transactionItems)): ?>
                                <?php for ($i = 1; $i < $itemCount; $i++): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($transactionItems[$i]['TRANSACTION_ITEM_ID']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['CART_ID']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['SOLD_PRICE']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['IMEI']); ?></td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
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