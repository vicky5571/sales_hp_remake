<?php
// Include the database connection
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// User role constants
define('ROLE_OWNER', 'OWNER');
define('ROLE_ADMIN', 'ADMIN');

// Fetch user role
$userRole = $_SESSION['user_role'] ?? '';

// Fetch products from the database
$productsQuery = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.COLOR, p.QUANTITY, p.CATEGORY_ID, c.CATEGORY_NAME 
                  FROM PRODUCTS p 
                  JOIN CATEGORIES c ON p.CATEGORY_ID = c.CATEGORY_ID";
$productsResult = $conn->query($productsQuery);

// Fetch categories for the dropdown
$categoriesQuery = "SELECT * FROM CATEGORIES";
$categoriesResult = $conn->query($categoriesQuery);

// Handle form submission for adding products
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryId = $_POST['category_id'];
    $productName = $_POST['product_name'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'] ?? 0;

    // Insert data into the PRODUCTS table
    $insertQuery = "INSERT INTO PRODUCTS (CATEGORY_ID, PRODUCT_NAME, COLOR, QUANTITY) 
                    VALUES ('$categoryId', '$productName', '$color', '$quantity')";
    if ($conn->query($insertQuery)) {
        echo "<script>alert('Product added successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container-fluid p-4 container-for-bg" style="margin-top: 10vh">
        <div class="row">
            <!-- Add Product Section -->
            <div class="col-12 mb-3 rounded p-3" style="position: sticky; top: 0; z-index: 1000; background: #f8f9fa; border: 1px solid #dee2e6;">
                <h2>Add Product</h2>
                <form method="POST" action="" class="row g-3">
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Category:</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php while ($category = $categoriesResult->fetch_assoc()) : ?>
                                <option value="<?= $category['CATEGORY_ID']; ?>"><?= $category['CATEGORY_NAME']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="product_name" class="form-label">Product Name:</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="color" class="form-label">Color:</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>

            <!-- Products Table -->
            <div class="col-12">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Color</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $productsResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $product['PRODUCT_ID']; ?></td>
                                <td><?= $product['CATEGORY_NAME']; ?></td>
                                <td><?= $product['PRODUCT_NAME']; ?></td>
                                <td><?= $product['COLOR']; ?></td>
                                <td><?= $product['QUANTITY']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $product['PRODUCT_ID']; ?>">Edit</button>
                                    <a href="delete_products.php?id=<?= $product['PRODUCT_ID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $product['PRODUCT_ID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="edit_products.php" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" value="<?= $product['PRODUCT_ID']; ?>">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Category:</label>
                                                    <select name="category_id" class="form-select" <?= ($userRole == ROLE_OWNER || $userRole == ROLE_ADMIN) ? '' : 'disabled'; ?>>
                                                        <?php
                                                        $categoriesEditResult = $conn->query($categoriesQuery);
                                                        while ($category = $categoriesEditResult->fetch_assoc()) : ?>
                                                            <option value="<?= $category['CATEGORY_ID']; ?>" <?= ($category['CATEGORY_ID'] == $product['CATEGORY_ID']) ? 'selected' : ''; ?>>
                                                                <?= $category['CATEGORY_NAME']; ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="product_name" class="form-label">Product Name:</label>
                                                    <input type="text" name="product_name" class="form-control" value="<?= $product['PRODUCT_NAME']; ?>" <?= ($userRole == ROLE_OWNER || $userRole == ROLE_ADMIN) ? '' : 'disabled'; ?>>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="color" class="form-label">Color:</label>
                                                    <input type="text" name="color" class="form-control" value="<?= $product['COLOR']; ?>" <?= ($userRole == ROLE_OWNER || $userRole == ROLE_ADMIN) ? '' : 'disabled'; ?>>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity:</label>
                                                    <input type="number" name="quantity" class="form-control" value="<?= $product['QUANTITY']; ?>" <?= ($userRole == ROLE_OWNER || $userRole == ROLE_ADMIN) ? '' : 'disabled'; ?>>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Save changes</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>