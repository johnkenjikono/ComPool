<?php
session_start();
require 'db_connect.php';

// Initialize feedback messages
$error = '';
$success = '';

// Redirect logged-in users to the dashboard
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 10) {
        $error = "Passwords must be at least 10 characters.";
    } else {
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION["username"] = $username;
                $success = "You have been logged in and will be redirected.";
                header("Location: index.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
</head>
<body>
<a href="login.php" class="login-button">Login</a>

    <!-- Header -->
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


<div class="form-wrapper">
    <div class="form-box">
        <h2>Register for ComPool</h2>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

        <form method="POST" action="register.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <button type="submit" name="confirm" class="add-group-button">
                <img src="images/favicon.png" alt="Icon" class="button-image">
                <span>Register</span>
            </button>    
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
