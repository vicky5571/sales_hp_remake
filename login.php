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
    <meta charset="UTF-8">
    <title>Login Form</title>
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
            background-color: rgb(30, 34, 39);
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
            margin-left: 200px;
            transform-origin: -200px 50%;
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
            transform: rotate(45deg);
            background: rgb(248, 113, 113);
        }

        .top:after {
            transform: rotate(135deg);
            background: rgb(56, 189, 248);
        }

        .bottom:before {
            transform: rotate(-45deg);
            background: rgb(74, 222, 128);
        }

        .bottom:after {
            transform: rotate(-135deg);
            background: rgb(253, 224, 71);
        }

        .center {
            position: absolute;
            width: 400px;
            height: 400px;
            top: 50%;
            left: 50%;
            margin-left: -200px;
            margin-top: -185px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            transition-delay: 0s;
            color: #38bdf8;

        }

        .center input {
            width: 100%;
            padding: 15px;
            margin: 5px;
            border-radius: 1px;
            border: 2px solid #38bdf8;
            /* #ccc */
            font-family: inherit;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>

</head>

<body>
    <div class="container login py-5" onclick="onclick">
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
            <h2>Please Sign In</h2>
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

            <h2>&nbsp;</h2>
        </div>
    </div>
    <!-- partial -->
    <script src='https://codepen.io/banik/pen/ReNNrO/3f837b2f0085b5125112fc455941ea94.js'></script>
</body>

</html>