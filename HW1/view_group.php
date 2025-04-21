<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$group_id = intval($_GET["id"]);

$query = "SELECT id, group_name, username, group_size, members FROM groups WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Group not found.";
    exit();
}

$group = $result->fetch_assoc();
$members_list = explode(",", $group["members"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="../images/favicon.png" />

    <meta charset="UTF-8">
    <title>View Group - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
    <style>
        .group-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }

        .group-section {
            margin-bottom: 20px;
            text-align: left;
        }

        .group-section h3 {
            color: #4B0082;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .group-section p {
            margin: 8px 0;
            color: #333;
        }

        .members-list {
            list-style-type: disc;
            padding-left: 20px;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4B0082;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #9370DB;
        }

        .user-info {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>
<body>
<a href="logout.php" class="login-button">Logout</a>

<!-- Header -->
<div class="logo-container">
    <img src="../images/Logo3.png" alt="ComPool Logo">
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

<main class="form-wrapper">
    <div class="group-card">

        <div class="user-info">
            Logged in as <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
        </div>

        <div class="group-section">
            <h3>Group Info</h3>
            <p><strong>Group ID:</strong> <?php echo $group["id"]; ?></p>
            <p><strong>Group Name:</strong> <?php echo htmlspecialchars($group["group_name"]); ?></p>
            <p><strong>Created By:</strong> <?php echo htmlspecialchars($group["username"]); ?></p>
            <p><strong>Group Size:</strong> <?php echo $group["group_size"]; ?></p>
        </div>

        <div class="group-section">
            <h3>Members</h3>
            <ul class="members-list">
                <?php foreach ($members_list as $member): ?>
                    <li><?php echo htmlspecialchars(trim($member)); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="index.php" class="back-button">← Back to Dashboard</a>
    </div>
</main>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
