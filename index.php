<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize $user_role from the session
$user_role = $_SESSION['user_role'] ?? null;


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
    <link rel="stylesheet" href="src/vanilla-tilt.css">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>

</head>

<body>
    <?php include 'navbar/navbar.php'; ?>
    <div id="container">
        <div class="tile"></div>
    </div>


    <div class="container mt-5">
        <h1 class="text-center text-light fw-bold mb-5 pt-4" style="font-size: 4em !important">Store</h1>
        <div class="row mt-4 mb-4">
            <!-- Card -->
            <div class="col-md-4">
                <a href="categories.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Categories</h5>
                            <img class="project-img" src="img/category-alt.png" alt="Categories Image" />
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="products.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Products</h5>
                            <img class="project-img" src="img/products.png" alt="Products Image" />
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="suppliers.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Suppliers</h5>
                            <img class="project-img" src="img/supplier-alt.png" alt="Supplier Image" />
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="product_unit.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Product Unit</h5>
                            <img class="project-img" src="img/product-units.png" alt="Product Unit Image" />
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="carts.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Carts</h5>
                            <img class="project-img" src="img/carts.png" alt="Carts Image" />
                        </div>
                    </div>
                </a>
            </div>
            <!-- <div class="col-md-4">
                <a href="transactions.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Transactions</h5>
                            <img class="project-img" src="img/transactions.png" alt="Transactions Image" />
                        </div>
                    </div>
                </a>
            </div> -->
            <?php if ($user_role !== 'KARYAWAN'): ?>
                <div class="col-md-4">
                    <a href="transactions.php" class="text-decoration-none">
                        <div class="card text-center project-tilt-box">
                            <div class="card-body">
                                <h5 class="card-title">Transactions</h5>
                                <img class="project-img" src="img/transactions.png" alt="Transactions Image" />
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <!-- <div class="col-md-4">
                <a href="users.php" class="text-decoration-none">
                    <div class="card text-center project-tilt-box">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <img class="project-img" src="img/user.png" alt="Users Image" />
                        </div>
                    </div>
                </a>
            </div> -->
        </div>

    </div>

    <!-- partial -->
    <script src="https://codepen.io/Hyperplexed/pen/xxYJYjM/54407644e24173ad6019b766443bf2a6.js"></script>
    <script src="src/tiles.js"></script>

    <!-- vanilla tilt -->
    <script type="text/javascript" src="src/vanilla-tilt.min.js"></script>
    <script type="text/javascript">
        VanillaTilt.init(document.querySelectorAll(".project-tilt-box"), {
            max: 15,
            speed: 700,
            glare: true,
            scale: 1.1,
        });

        document.querySelectorAll('.project-tilt-box').forEach((box) => {
            const cardTitle = box.querySelector('.card-title').textContent;
            box.setAttribute('data-title', cardTitle.toUpperCase());
        });
    </script>

</body>

</html>