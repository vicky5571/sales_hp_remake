<!DOCTYPE html>
<html lang="en">

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
            background: #e46569;
        }

        .top:after {
            transform: rotate(135deg);
            background: #ecaf81;
        }

        .bottom:before {
            transform: rotate(-45deg);
            background: #60b8d4;
        }

        .bottom:after {
            transform: rotate(-135deg);
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

</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="container" onclick="onclick">
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
            <h2>Please Sign In</h2>
            <input type="email" placeholder="email" />
            <input type="password" placeholder="password" />
            <h2>&nbsp;</h2>
        </div>
    </div>
    <!-- partial -->
    <script src='https://codepen.io/banik/pen/ReNNrO/3f837b2f0085b5125112fc455941ea94.js'></script>
</body>

</html>