<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales HP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/tiles.css" />

</head>

<body>
    <div id="container">
        <div class="tile"></div>
    </div>
    <div class="container mt-5">
        <h1 class="text-center text-light">POS System Dashboard</h1>
        <a href="logout.php" class="btn btn-primary">Logout</a>
        <div class="row mt-4">
            <!-- Card -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <p class="card-text">Manage categories in the system.</p>
                        <a href="categories.php" class="btn btn-primary">Go to Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <p class="card-text">dolor amet</p>
                        <a href="products.php" class="btn btn-primary">Go to Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text">iye aye</p>
                        <a href="suppliers.php" class="btn btn-primary">Go to Suppliers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Product Unit</h5>
                        <p class="card-text">hehehe</p>
                        <a href="product_unit.php" class="btn btn-primary">Go to Product Unit</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Carts</h5>
                        <p class="card-text">hehehe</p>
                        <a href="carts.php" class="btn btn-primary">Go to Carts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Transactions</h5>
                        <!-- <p class="card-text"></p> -->
                        <a href="transactions.php" class="btn btn-primary">Go to Transactions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <!-- <p class="card-text"></p> -->
                        <a href="users.php" class="btn btn-primary">Go to Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- partial -->
    <script src="https://codepen.io/Hyperplexed/pen/xxYJYjM/54407644e24173ad6019b766443bf2a6.js"></script>
    <script src="src/tiles.js"></script>
</body>

</html>