<?php
session_start();
require 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Logged-in user

// Fetch all groups
$query = "SELECT id, group_name, username, group_size, members FROM groups ORDER BY id ASC";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
</head>
<body>
<a href="logout.php" class="login-button">Logout</a>

<!-- Header -->
<div class="logo-container">
    <img src="images/Logo3.png" alt="ComPool Logo">
    <h1 style="text-align: center;">Pool Money and Compete!</h1>
</div>

<div class="navbar">
    <nav>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="About.html">About</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>
        </ul>
    </nav>
</div>

<!-- Dashboard Content -->
<div class="form-wrapper">
    <div class="form-box">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

        <h2>Group List</h2>
        <a href="add_group.php" class="add-group-button">
            <img src="../images/teamwork.png" alt="Teamwork Image" class="button-image">
            <span>+ Add New Group</span>
        </a>

        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Group Name</th>
                        <th>Group Size</th>
                        <th>Members</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["username"]); ?></td>
                            <td><?php echo htmlspecialchars($row["group_name"]); ?></td>
                            <td><?php echo $row["group_size"]; ?></td>
                            <td><?php echo htmlspecialchars($row["members"]); ?></td>
                            <td>
                                <a href="view_group.php?id=<?php echo $row["id"]; ?>">View</a>
                                <?php if ($row["username"] === $username): ?>
                                    | <a href="update_group.php?id=<?php echo $row["id"]; ?>">Update</a>
                                    | <a href="delete_group.php?id=<?php echo $row["id"]; ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
