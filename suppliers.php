<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container-fluid" style="margin-top: 13vh">

        <!-- Add Supplier Form -->
        <div class="col-12 mb-3 rounded p-3" style="position: sticky; top: 0; z-index: 1000; background: #f8f9fa; border: 1px solid #dee2e6;">
            <h2>Add Supplier</h2>
            <form method="POST" action="">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="supplier_name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" id="supplier_name" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Supplier</button>
            </form>
        </div>

        <!-- Display Suppliers Table -->
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Supplier ID</th>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($supplier = $suppliersResult->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $supplier['SUPPLIER_ID']; ?></td>
                            <td><?= $supplier['SUPPLIER_NAME']; ?></td>
                            <td><?= $supplier['EMAIL']; ?></td>
                            <td><?= $supplier['PHONE']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="<?= $supplier['SUPPLIER_ID']; ?>"
                                    data-name="<?= $supplier['SUPPLIER_NAME']; ?>"
                                    data-email="<?= $supplier['EMAIL']; ?>"
                                    data-phone="<?= $supplier['PHONE']; ?>">Edit</button>
                                <a href="delete_suppliers.php?id=<?= $supplier['SUPPLIER_ID']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="edit_suppliers.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="supplier_id" id="editSupplierId">
                        <div class="mb-3">
                            <label for="editSupplierName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name" id="editSupplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="editPhone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fill modal with data for editing
        document.getElementById('editModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');

            document.getElementById('editSupplierId').value = id;
            document.getElementById('editSupplierName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
        });
    </script>
</body>

</html>