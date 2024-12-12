<?php
// Include the database connection
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch products from the database
$productsQuery = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.COLOR, p.QUANTITY, c.CATEGORY_NAME 
                  FROM PRODUCTS p 
                  JOIN CATEGORIES c ON p.CATEGORY_ID = c.CATEGORY_ID";
$productsResult = $conn->query($productsQuery);

// Fetch categories for the dropdown
$categoriesQuery = "SELECT * FROM CATEGORIES";
$categoriesResult = $conn->query($categoriesQuery);

// Handle form submission
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
</head>

<body>
    <h1>Products</h1>
    <a href="index.php">Home</a>

    <!-- Display Products Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Category</th>
                <th>Product Name</th>
                <th>Color</th>
                <th>Quantity</th>
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
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>

    <!-- Add Product Form -->
    <h2>Add Product</h2>
    <form method="POST" action="">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while ($category = $categoriesResult->fetch_assoc()) : ?>
                <option value="<?= $category['CATEGORY_ID']; ?>"><?= $category['CATEGORY_NAME']; ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required>
        <br><br>

        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="0" min="0">
        <br><br>

        <button type="submit">Add Product</button>
    </form>
</body>

</html>
