<?php
session_start();
require 'db_connect.php';

// Redirect to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Check if group ID is provided
if (!isset($_GET["id"])) {
    header("Location: index.php"); // Redirect to dashboard if no ID is given
    exit();
}

$group_id = intval($_GET["id"]); // Get the group ID from URL safely

// Fetch group details (parameterized)
$query = "SELECT id, group_name, username FROM groups WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Group not found.";
    exit();
}

$group = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Group - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        a { text-decoration: none; color: blue; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>You are logged in as user: <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <a href="logout.php">Log Out</a>

    <h2>View Group</h2>

    <p><span class="bold">Group ID:</span> <?php echo $group["id"]; ?></p>
    <p><span class="bold">Username:</span> <?php echo htmlspecialchars($group["username"]); ?></p>
    <p><span class="bold">Group Name:</span> <?php echo htmlspecialchars($group["group_name"]); ?></p>

    <a href="index.php">Back</a>
</div>

</body>
</html>
