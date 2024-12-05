<?php
include 'conn.php';

// Fetch suppliers from the database
$suppliersQuery = "SELECT * FROM SUPPLIERS";
$suppliersResult = $conn->query($suppliersQuery);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplierName = $_POST['supplier_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $insertQuery = "INSERT INTO SUPPLIERS (SUPPLIER_NAME, EMAIL, PHONE) 
                    VALUES ('$supplierName', '$email', '$phone')";
    if ($conn->query($insertQuery)) {
        echo "<script>alert('Supplier added successfully!'); window.location.href='suppliers.php';</script>";
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
    <title>Suppliers</title>
</head>

<body>
    <h1>Suppliers</h1>

    <!-- Display Suppliers Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($supplier = $suppliersResult->fetch_assoc()) : ?>
                <tr>
                    <td><?= $supplier['SUPPLIER_ID']; ?></td>
                    <td><?= $supplier['SUPPLIER_NAME']; ?></td>
                    <td><?= $supplier['EMAIL']; ?></td>
                    <td><?= $supplier['PHONE']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>

    <!-- Add Supplier Form -->
    <h2>Add Supplier</h2>
    <form method="POST" action="">
        <label for="supplier_name">Supplier Name:</label>
        <input type="text" name="supplier_name" id="supplier_name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required>
        <br><br>

        <button type="submit">Add Supplier</button>
    </form>
</body>

</html>
