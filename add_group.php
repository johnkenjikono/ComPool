<?php
session_start();
require 'db_connect.php';

// Redirect to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Get the logged-in user
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = trim($_POST["group_name"]);

    // Validation: Check if group name is empty
    if (empty($group_name)) {
        $error = "Group name cannot be empty.";
    } else {
        // Insert new group into database (parameterized)
        $stmt = $db->prepare("INSERT INTO groups (group_name, username) VALUES (?, ?)");
        $stmt->bind_param("ss", $group_name, $username);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to dashboard after successful group creation
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
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
    <title>Add Group - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        a { text-decoration: none; color: blue; }
        .bold { font-weight: bold; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>You are logged in as user: <?php echo htmlspecialchars($username); ?></h2>
    <a href="logout.php">Log Out</a>

    <h2>Add Group</h2>
    <p>Here you can create a new group.</p>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="add_group.php">
        <p><span class="bold">Username:</span> <?php echo htmlspecialchars($username); ?></p>

        <label for="group_name">Group Name:</label><br>
        <input type="text" id="group_name" name="group_name" required><br><br>

        <button type="submit">Submit</button>
        <a href="index.php">Cancel</a>
    </form>
</div>

</body>
</html>