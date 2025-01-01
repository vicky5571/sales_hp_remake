<?php
session_start();

// Include database connection
require_once 'conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    die("Access denied. Please log in.");
}

// Get the user's role from the session
$user_role = $_SESSION['user_role'];

// Restrict access to MANAJER, OWNER, and ADMIN
if (!in_array($user_role, ['MANAJER', 'OWNER', 'ADMIN'])) {
    die("Access denied. You do not have permission to access this page.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Determine user role based on session role
    $new_user_role = ($user_role === 'OWNER' || $user_role === 'ADMIN') ? $_POST['user_role'] : 'KARYAWAN';

    // Insert into the database
    $query = "INSERT INTO users (FIRST_NAME, LAST_NAME, EMAIL, USER_PASSWORD, PHONE, USER_ROLE, CREATED_AT) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $phone, $new_user_role);

    if ($stmt->execute()) {
        echo "User added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add User</h2>
        <form method="post" action="add_user.php">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>

            <?php if ($user_role === 'OWNER' || $user_role === 'ADMIN'): ?>
                <label for="user_role">User Role:</label>
                <select id="user_role" name="user_role" required>
                    <option value="KARYAWAN">KARYAWAN</option>
                    <option value="MANAJER">MANAJER</option>
                </select><br>
            <?php else: ?>
                <input type="hidden" name="user_role" value="KARYAWAN">
            <?php endif; ?>

            <input type="submit" value="Add User">
        </form>
    </div>
</body>

</html>