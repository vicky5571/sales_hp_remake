<?php
session_start();

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['MANAJER', 'OWNER', 'ADMIN'])) {
    echo '<script>alert("Access Denied! You do not have permission to access this page."); window.location.href="index.php";</script>';
}

require_once 'conn.php';

// Fetch users from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Navbar -->
    <link rel="stylesheet" href="navbar/navbarStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
    <script src="navbar/navbarScript.js"></script>

    <style>
        .container-for-bg {
            margin-top: 13vh;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .modal-header {
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>

<body>

    <?php include 'navbar/navbar.php'; ?>

    <div class="container container-for-bg rounded p-4">
        <div class="mb-4">
            <h1 class="text-center">Users Management</h1>
            <a href="add_user.php" class="btn btn-primary">Add User</a>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>USER_ID</th>
                        <th>FIRST_NAME</th>
                        <th>LAST_NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>USER_ROLE</th>
                        <th>CREATED_AT</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['USER_ID'] ?></td>
                            <td><?= $row['FIRST_NAME'] ?></td>
                            <td><?= $row['LAST_NAME'] ?></td>
                            <td><?= $row['EMAIL'] ?></td>
                            <td><?= $row['PHONE'] ?></td>
                            <td><?= $row['USER_ROLE'] ?></td>
                            <td><?= $row['CREATED_AT'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="showEditForm(<?= $row['USER_ID'] ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="showDeleteForm(<?= $row['USER_ID'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" action="edit_users.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-3">
                            <label for="edit_first_name" class="form-label">First Name:</label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Last Name:</label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email:</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone:</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_user_role" class="form-label">User Role:</label>
                            <select name="user_role" id="edit_user_role" class="form-select">
                                <option value="KARYAWAN">KARYAWAN</option>
                                <option value="MANAJER">MANAJER</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" action="delete_users.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="delete_user_id">
                        <p>Are you sure you want to delete this user?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(userId) {
            document.getElementById('edit_user_id').value = userId;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function showDeleteForm(userId) {
            document.getElementById('delete_user_id').value = userId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>