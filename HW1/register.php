<?php
session_start(); // Start the session
require 'db_connect.php'; // Include database connection

// Redirect logged-in users to the dashboard
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

//form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    //basic validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 10) {
        $error = "Passwords must be at least 10 characters.";
    } else {
        //check if username already exists
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            //hash and salt the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            //insert new user (parameterized)
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                // Auto-login: Start session and store username
                $_SESSION["username"] = $username;
                // Redirect to dashboard (index.php)
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Register for ComPool</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <!--Created form-->
    <form method="POST" action="register.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>
        <button type="reset">Reset</button> <!--Reset button to clear input fields-->
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
