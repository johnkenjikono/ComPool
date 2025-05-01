<?php
session_start();
require 'db_connect.php'; // Include database connection

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
                // Successful login, create session
                $_SESSION["username"] = $userid;
                $_SESSION["logged_in"] = true;

                // Send 201 Created response indicating login session created
                http_response_code(201);
                echo json_encode(['message' => 'Login successful, session created']);
                exit(); // Make sure to stop further execution
            } else {
                // Incorrect password: return 401 Unauthorized with error message
                http_response_code(401);
                echo json_encode(['message' => 'Wrong User ID or password.']);
                exit(); // Ensure no HTML is outputted and stop further execution
            }
        } else {
            // User not found: return 401 Unauthorized with error message
            http_response_code(401);
            echo json_encode(['message' => 'Wrong User ID or password.']);
            exit(); // Ensure no HTML is outputted and stop further execution
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
</head>

<body>
    <a href="login.php" class="login-button">Login</a>
    <div class="logo-container">
    <a href="index.html">
        <img src="images/Logo3.png" alt="ComPool Logo">
    </a>
    <h1 style="text-align: center;">Pool Money and Compete!</h1>
    </div>

<div class="navbar">
    <nav>
        <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="About.html">About</a></li>
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
?>