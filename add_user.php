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
    <meta charset="UTF-8">
    <title>Add User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <!-- <link rel="stylesheet" href="./style.css"> -->
    <style>
        @import url("https://fonts.googleapis.com/css?family=Raleway:400,700");

        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Raleway", sans-serif;
        }

        .container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .container:hover .top:before,
        .container:hover .top:after,
        .container:hover .bottom:before,
        .container:hover .bottom:after,
        .container:active .top:before,
        .container:active .top:after,
        .container:active .bottom:before,
        .container:active .bottom:after {
            margin-left: 350px;
            transform-origin: -350px 50%;
            transition-delay: 0s;
        }


        .container:hover .center,
        .container:active .center {
            opacity: 1;
            transition-delay: 0.2s;
        }

        .top:before,
        .top:after,
        .bottom:before,
        .bottom:after {
            content: "";
            display: block;
            position: absolute;
            width: 200vmax;
            height: 200vmax;
            top: 50%;
            left: 50%;
            margin-top: -100vmax;
            transform-origin: 0 50%;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            z-index: 10;
            opacity: 0.65;
            transition-delay: 0.2s;
        }

        .top:before {
            transform: rotate(0deg);
            background: #e46569;
        }

        .top:after {
            transform: rotate(90deg);
            background: #ecaf81;
        }

        .bottom:before {
            transform: rotate(180deg);
            background: #60b8d4;
        }

        .bottom:after {
            transform: rotate(-90deg);
            background: #3745b5;
        }

        .center {
            position: absolute;
            width: 400px;
            height: 400px;
            top: 50%;
            left: 50%;
            margin-left: -200px;
            margin-top: -200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            transition-delay: 0s;
            color: #333;
        }

        .center input {
            width: 100%;
            padding: 15px;
            margin: 5px;
            border-radius: 1px;
            border: 1px solid #ccc;
            font-family: inherit;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container" onclick="onclick">
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>