<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

$query = "SELECT id, group_name, username, members FROM groups ORDER BY id ASC";
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
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

        <a href="add_group.php" class="add-group-button">
            <img src="images/teamwork.png" alt="Teamwork Image" class="button-image">
            <span>+ Add New Group</span>
        </a>

        <div class="group-cards-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="group-card">
                    <h3><?php echo htmlspecialchars($row["group_name"]); ?></h3>
                    <p><strong>Leader:</strong> <?php echo htmlspecialchars($row["username"]); ?></p>
                    <p><strong>Members:</strong> <?php echo htmlspecialchars($row["members"]); ?></p>
                    <div class="group-actions">
                        <a href="view_group.php?id=<?php echo $row["id"]; ?>" class="btn">View</a>
                        <?php if ($row["username"] === $username): ?>
                            <a href="update_group.php?id=<?php echo $row["id"]; ?>" class="btn">Update</a>
                            <a href="delete_group.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
