<?php
session_start();
require 'db_connect.php';

// Redirect to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Get logged-in user

// Fetch all groups from the database
$query = "SELECT id, group_name, username FROM groups ORDER BY id ASC";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .logout { margin-top: 10px; display: inline-block; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <a href="logout.php" class="logout">Log Out</a>

    <h2>Group List</h2>
    <a href="add_group.php">Add New Group</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Group Name</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["group_name"]); ?></td>
                <td>
                    <a href="view_group.php?id=<?php echo $row["id"]; ?>">View</a>

                    <?php if ($row["username"] === $username): ?>
                        | <a href="update_group.php?id=<?php echo $row["id"]; ?>">Update</a>
                        | <a href="delete_group.php?id=<?php echo $row["id"]; ?>">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
