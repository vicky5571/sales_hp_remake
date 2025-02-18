<?php
// Include database connection
require_once 'conn.php';

session_start();

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['MANAJER', 'OWNER', 'ADMIN'])) {
    echo '<script>alert("Access Denied! You do not have permission to access this page."); window.location.href="index.php";</script>';
}


// Initialize filter variables
$startDate = '';
$endDate = '';

// Check if the filter form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['start_date'], $_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    // Fetch transactions within the selected date range
    $transactionQuery = "SELECT * FROM transactions WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($transactionQuery);
    $stmt->bind_param('ss', $startDate, $endDate);
} else {
    // Fetch all transactions if no filter is applied
    $transactionQuery = "SELECT * FROM transactions ORDER BY created_at DESC";
    $stmt = $conn->prepare($transactionQuery);
}

// Execute the query
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

    <link rel="stylesheet" href="./src/style.css">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container container-for-bg rounded border border-primary" style="margin-top: 13vh">
        <h1 class="text-center mt-4">Transactions</h1>

        <!-- Date Filter Form -->
        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate); ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-success w-100" onclick="printPage()">Print</button>
                </div>

            </div>
        </form>

        <?php if (!empty($transactions)): ?>
            <div class="table-responsive mt-4 bg-light">
                <!-- style="max-height: 400px; overflow-y: auto;" -->
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2">Transaction ID</th>
                            <th rowspan="2">Cart ID</th>
                            <th rowspan="2">Transaction Status</th>
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
            <p class="alert alert-warning text-center">No transactions found for the selected date range.</p>
        <?php endif; ?>
    </div>
</body>

</html>