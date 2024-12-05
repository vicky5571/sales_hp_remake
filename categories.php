<?php
include 'conn.php'; // Connection to the database

// Fetch categories from the database
$query = "SELECT * FROM CATEGORIES";
$result = mysqli_query($conn, $query);

// Handle form submission to add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $insert_query = "INSERT INTO CATEGORIES (CATEGORY_NAME) VALUES ('$category_name')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: categories.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Categories</h1>
        <div class="mt-4">
            <!-- Add New Category Form -->
            <form method="POST" class="d-flex mb-4">
                <input type="text" name="category_name" class="form-control me-2" placeholder="Enter Category Name" required>
                <button type="submit" class="btn btn-success">Add</button>
            </form>

            <!-- Categories Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['CATEGORY_ID']; ?></td>
                            <td><?= $row['CATEGORY_NAME']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
