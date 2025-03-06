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
    $userid = trim($_POST["userid"]); // Match class example's naming
    $password = trim($_POST["password"]);

    // Basic validation
    if (empty($userid) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Use placeholders in the SQL statement (parameterized)
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify hashed password
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $userid; // Store session
                header("Location: index.php"); // Redirect to dashboard
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Login to ComPool</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="login.php">
        <label for="userid">Username:</label><br>
        <input type="text" id="userid" name="userid" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
        <button type="reset">Reset</button>  <!-- Reset button to clear input fields -->
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
