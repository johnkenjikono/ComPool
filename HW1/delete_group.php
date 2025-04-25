<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$group_id = intval($_GET["id"]);

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

if ($group["username"] !== $username) {
    echo "You do not have permission to delete this group.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["confirm"])) {
        $stmt = $db->prepare("DELETE FROM groups WHERE id = ?");
        $stmt->bind_param("i", $group_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Something went wrong. Please try again.";
        }
        $stmt->close();
    } else {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <title>Delete Group - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
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
</head>
<body>


<main class="form-wrapper">
    <div class="form-box">
        <h2>Delete Group</h2>
        <p>Are you sure you want to delete the group <span class="bold"><?php echo htmlspecialchars($group["group_name"]); ?></span>?</p>

        <form method="POST" action="delete_group.php?id=<?php echo $group_id; ?>" class="confirm-form">
            <button type="submit" name="confirm" class="danger-button">Yes, Delete</button>
            <a href="index.php" class="login-button">Cancel</a>
        </form>
    </div>
</main>

<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
