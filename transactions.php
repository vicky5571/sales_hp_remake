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
    $transactionQuery = "SELECT 
                            t.transactions_id AS transactions_id,
                            t.cart_id AS cart_id,
                            t.transaction_status AS transaction_status,
                            t.shipping_address AS shipping_address,
                            t.total_unit AS total_unit,
                            t.grand_total AS grand_total,
                            t.buyer_name AS buyer_name,
                            t.created_at AS created_at,
                            u.first_name AS first_name
                        FROM transactions t 
                        JOIN users u ON t.user_id = u.user_id
                        WHERE t.created_at BETWEEN ? AND ? 
                        ORDER BY t.created_at DESC
                        ";
    $stmt = $conn->prepare($transactionQuery);
    $stmt->bind_param('ss', $startDate, $endDate);
} else {
    // Fetch all transactions if no filter is applied
    $transactionQuery = "SELECT 
    t.transactions_id AS transactions_id,
    t.cart_id AS cart_id,
    t.transaction_status AS transaction_status,
    t.shipping_address AS shipping_address,
    t.total_unit AS total_unit,
    t.grand_total AS grand_total,
    t.buyer_name AS buyer_name,
    t.created_at AS created_at,
    u.first_name AS first_name
FROM transactions t 
JOIN users u ON t.user_id = u.user_id
ORDER BY t.created_at DESC
";
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

    <link rel="stylesheet" href="./src/style.css">
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
    <style>
        @media print {
            body * {
                display: none;
            }

            .container-for-bg,
            .container-for-bg * {
                display: revert;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar/navbar.php'; ?>

    <div class="container container-for-bg rounded border border-primary" style="margin-top: 13vh; min-width: 90vw !important;">
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
                    <button type="submit" class="btn btn-primary w-100 no-print">Filter</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-success w-100 no-print" id="print" type="button">Print</button>
                    <!-- onclick="printPage()" -->
                </div>
            </div>
        </form>

        <?php if (!empty($transactions)): ?>
            <div class="table-responsive mt-4 bg-light">
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
                            <th rowspan="2">Kasir </th>
                            <th rowspan="2">Created At</th>
                            <th colspan="9">Items</th>
                        </tr>
                        <tr>
                            <th>Transaction Item ID</th>
                            <th>Sold Price</th>
                            <th>IMEI</th>
                            <th>Description</th>
                            <th>Buy Price</th>
                            <th>SRP</th>
                            <th>Profit</th>
                            <th>Date Stock In</th>
                            <th>Supplier Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <?php
                            // Fetch items for the current transaction
                            $transactionItemQuery = "SELECT 
        ti.transaction_item_id AS transaction_item_id,
        ti.sold_price AS sold_price,
        ti.imei AS imei,
        pu.buy_price AS buy_price,
        pu.srp AS srp,
        pu.product_unit_description AS product_unit_description,
        pu.date_stock_in AS date_stock_in,
        s.supplier_name AS supplier_name
    FROM transaction_items ti
    JOIN product_unit pu ON ti.imei = pu.imei
    JOIN suppliers s ON pu.supplier_id = s.supplier_id
    WHERE ti.cart_id = ?";
                            $itemStmt = $conn->prepare($transactionItemQuery);
                            $itemStmt->bind_param("i", $transaction['cart_id']);
                            $itemStmt->execute();
                            $transactionItems = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            $itemCount = count($transactionItems);
                            ?>
                            <tr>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['transactions_id']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['cart_id']); ?></td>
                                <!-- <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['transaction_status']); ?></td> -->
                                <td rowspan="<?= $itemCount ?: 1; ?>" onclick="openStatusModal('<?= $transaction['transactions_id']; ?>', '<?= htmlspecialchars($transaction['transaction_status']); ?>')">
                                    <?= htmlspecialchars($transaction['transaction_status']); ?>
                                </td>

                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['shipping_address']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['total_unit']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= number_format($transaction['grand_total'], 2, '.', ','); ?></td>

                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['buyer_name']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['first_name']); ?></td>
                                <td rowspan="<?= $itemCount ?: 1; ?>"><?= htmlspecialchars($transaction['created_at']); ?></td>

                                <?php if (!empty($transactionItems)): ?>
                                    <td><?= htmlspecialchars($transactionItems[0]['transaction_item_id']); ?></td>
                                    <td><?= number_format($transactionItems[0]['sold_price'], 2, '.', ','); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['imei']); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['product_unit_description']); ?></td>
                                    <td><?= number_format($transactionItems[0]['buy_price'], 2, '.', ','); ?></td>
                                    <td><?= number_format($transactionItems[0]['srp'], 2, '.', ','); ?></td>
                                    <td><?= number_format($transactionItems[0]['sold_price'] - $transactionItems[0]['buy_price'], 2, '.', ','); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['date_stock_in']); ?></td>
                                    <td><?= htmlspecialchars($transactionItems[0]['supplier_name']); ?></td>
                                <?php else: ?>
                                    <td colspan="9" class="text-center">No items found</td>
                                <?php endif; ?>
                            </tr>
                            <?php if (!empty($transactionItems)): ?>
                                <?php for ($i = 1; $i < $itemCount; $i++): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($transactionItems[$i]['transaction_item_id']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['sold_price']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['imei']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['product_unit_description']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['buy_price']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['srp']); ?></td>
                                        <td><?= number_format($transactionItems[$i]['sold_price'] - $transactionItems[$i]['buy_price'], 2, '.', ','); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['date_stock_in']); ?></td>
                                        <td><?= htmlspecialchars($transactionItems[$i]['supplier_name']); ?></td>
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

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="statusForm" method="POST" action="update_status.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Change Transaction Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="transactions_id" id="modalTransactionId">
                        <div class="mb-3">
                            <label for="transactionStatus" class="form-label">Transaction Status</label>
                            <select class="form-select" name="transaction_status" id="transactionStatus" required>
                                <option value="done" selected>Done</option>
                                <option value="on the way">On the Way</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openStatusModal(transactionId, currentStatus) {
            // Set transaction ID and current status in the modal
            document.getElementById('modalTransactionId').value = transactionId;
            document.getElementById('transactionStatus').value = currentStatus;

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }
    </script>

    <script>
        // Print
        const printBtn = document.getElementById('print');

        printBtn.addEventListener('click', function() {
            window.print();
        })
    </script>

</body>

</html>