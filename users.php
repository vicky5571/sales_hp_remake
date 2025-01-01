<?php
session_start();

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['MANAJER', 'OWNER', 'ADMIN'])) {
    die("Access denied");
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
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Users Management</h1>
    <a href="add_user.php">Add user</a>
    <table border="1">
        <thead>
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
                        <button onclick="showEditForm(<?= $row['USER_ID'] ?>)">Edit</button>
                        <button onclick="showDeleteForm(<?= $row['USER_ID'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit Form Modal -->
    <div id="editModal" style="display:none;">
        <form id="editForm" action="edit_users.php" method="POST">
            <input type="hidden" name="user_id" id="edit_user_id">
            <label>First Name:</label>
            <input type="text" name="first_name" id="edit_first_name" required><br>
            <label>Last Name:</label>
            <input type="text" name="last_name" id="edit_last_name" required><br>
            <label>Email:</label>
            <input type="email" name="email" id="edit_email" required><br>
            <label>Phone:</label>
            <input type="text" name="phone" id="edit_phone" required><br>
            <label>User Role:</label>
            <select name="user_role" id="edit_user_role">
                <option value="KARYAWAN">KARYAWAN</option>
                <option value="MANAJER">MANAJER</option>
            </select><br>
            <button type="submit">Save</button>
            <button type="button" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <!-- Delete Form Modal -->
    <div id="deleteModal" style="display:none;">
        <form id="deleteForm" action="delete_users.php" method="POST">
            <input type="hidden" name="user_id" id="delete_user_id">
            <p>Are you sure you want to delete this user?</p>
            <button type="submit">Yes</button>
            <button type="button" onclick="closeDeleteForm()">No</button>
        </form>
    </div>

    <script>
        function showEditForm(userId) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditForm() {
            document.getElementById('editModal').style.display = 'none';
        }

        function showDeleteForm(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteForm() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>

</html>