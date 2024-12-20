<?php 
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include_once("conn.php");

if (isset($_POST['login'])) {
    // Sanitize user inputs
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to fetch user data
    $query = "SELECT * FROM users WHERE EMAIL='$email'";
    $result = $conn->query($query);

    // Check if user exists
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Verify password (direct comparison, since no hashing)
        if ($password === $user_data['USER_PASSWORD']) {
            // Set session variables
            $_SESSION['user_id'] = $user_data['USER_ID'];
            $_SESSION['first_name'] = $user_data['FIRST_NAME'];
            $_SESSION['last_name'] = $user_data['LAST_NAME'];
            $_SESSION['email'] = $user_data['EMAIL'];
            $_SESSION['user_role'] = $user_data['USER_ROLE'];

            $user_id = $user_data['USER_ID'];

            // Check if user already has an active cart
            $cart_query = "SELECT * FROM carts WHERE user_id = '$user_id' ORDER BY cart_id DESC LIMIT 1";
            $cart_result = $conn->query($cart_query);

            if ($cart_result->num_rows === 0) {
                // Create a new cart for the user
                $create_cart_query = "INSERT INTO carts (user_id, quantity) VALUES ('$user_id', 0)";
                $conn->query($create_cart_query);

                // Store the new cart_id in the session
                $_SESSION['cart_id'] = $conn->insert_id;
            } else {
                // Use the existing cart
                $cart = $cart_result->fetch_assoc();
                $_SESSION['cart_id'] = $cart['cart_id'];
            }

            // Redirect to index.php
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container login py-5">
        <div class="header">
            <h2>Login</h2>
        </div>
        <form method="post" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" name="login" value="Login" class="button">
        </form>
        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <ul>
            <li><a href="register.php" class="btn btn-primary">Register</a></li>
        </ul>
    </div>
</body>
</html>
