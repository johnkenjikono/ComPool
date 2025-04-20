<?php
session_start();
require 'db_connect.php'; // Include database connection

// Redirect logged-in users to the dashboard
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST["userid"]);
    $password = trim($_POST["password"]);

    if (empty($userid) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $userid;
                header("Location: index.php");
                exit();
            } else {
                $error = "Wrong User ID or password.";
            }
        } else {
            $error = "Wrong User ID or password.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="../images/favicon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
    <style>
        .login-button {
            padding: 15px 25px;
            background-color: #4B0082;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            margin: 10px;
        }

        .login-button:hover {
            background-color: #9370DB;
        }
    </style>
</head>
<body>
<a href="login.php" class="login-button">Login</a>


<!-- Header -->
<div class="logo-container">
    <img src="../images/Logo3.png" alt="ComPool Logo">
    <h1 style="text-align: center;">Pool Money and Compete!</h1>
</div>

<div class="navbar">
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="About.html">About</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>
        </ul>
    </nav>
</div>

<!-- Main Login Section -->
<div class="form-wrapper">
    <div class="form-box">
        <h2>Login to ComPool</h2>

        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label for="userid">Username:</label>
                <input type="text" id="userid" name="userid" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="button-group">
                <button type="submit" class="form-login-button">Login</button>
                <button type="reset">Reset</button>
            </div>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
