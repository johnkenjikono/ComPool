<?php
session_start();
require 'db_connect.php';

// Redirect to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Get logged-in user

// Check if group ID is provided
if (!isset($_GET["id"])) {
    header("Location: index.php"); // Redirect if no ID is given
    exit();
}

$group_id = intval($_GET["id"]); // Get group ID from URL safely

// Fetch group details
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

// Restrict deletion to the group owner
if ($group["username"] !== $username) {
    echo "You do not have permission to delete this group.";
    exit();
}

// Handle delete confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["confirm"])) {
        // Delete group from database
        $stmt = $db->prepare("DELETE FROM groups WHERE id = ?");
        $stmt->bind_param("i", $group_id);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to dashboard after deletion
            exit();
        } else {
            echo "Something went wrong. Please try again.";
        }
        $stmt->close();
    } else {
        // Redirect back if "No" is clicked
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Group - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        a { text-decoration: none; color: blue; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>You are logged in as user: <?php echo htmlspecialchars($username); ?></h2>
    <a href="logout.php">Log Out</a>

    <h2>Delete Group</h2>
    <p>Are you sure you want to delete the group <span class="bold"><?php echo htmlspecialchars($group["group_name"]); ?></span>?</p>

    <form method="POST" action="delete_group.php?id=<?php echo $group_id; ?>">
        <button type="submit" name="confirm">Yes</button>
        <a href="index.php">No</a>
    </form>
</div>

</body>
</html>