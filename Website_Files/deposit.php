<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["group_id"])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION["username"];
$group_id = intval($_GET["group_id"]);

// Fetch group name
$group_query = $db->prepare("SELECT group_name FROM groups WHERE id = ?");
$group_query->bind_param("i", $group_id);
$group_query->execute();
$group_result = $group_query->get_result();
$group_data = $group_result->fetch_assoc();
$group_name = $group_data ? $group_data["group_name"] : "Unknown Group";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = floatval($_POST["amount"]);

    // Check user balance
    $check_stmt = $db->prepare("SELECT balance FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $balance_result = $check_stmt->get_result()->fetch_assoc();

    if ($balance_result["balance"] >= $amount && $amount > 0) {
        // Deduct from user
        $deduct_stmt = $db->prepare("UPDATE users SET balance = balance - ? WHERE username = ?");
        $deduct_stmt->bind_param("ds", $amount, $username);
        $deduct_stmt->execute();

        // Add to group
        $add_stmt = $db->prepare("UPDATE groups SET funds = funds + ? WHERE id = ?");
        $add_stmt->bind_param("di", $amount, $group_id);
        $add_stmt->execute();

        // Log message in group chat
        $msg_content = "$username deposited \$$amount into the group fund.";
        $msg_stmt = $db->prepare("INSERT INTO messages (group_id, username, content) VALUES (?, ?, ?)");
        $msg_stmt->bind_param("iss", $group_id, $username, $msg_content);
        $msg_stmt->execute();

        $success = true;
    } else {
        $error = "Insufficient balance or invalid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <title>Deposit Funds - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">
</head>
<body>
<a href="logout.php" class="login-button">Logout</a>

<!-- Header -->
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

<main class="form-wrapper">
    <div class="form-box">
        <h2>Deposit Funds to "<?php echo htmlspecialchars($group_name); ?>"</h2>

        <?php if (!empty($success)): ?>
            <p class="success-message">Deposit successful!</p>
        <?php elseif (!empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="amount">Amount to Deposit:</label>
            <input type="number" name="amount" id="amount" step="0.01" min="0.01" required>

            <button type="submit" class="add-group-button">Deposit</button>
        </form>

        <br>
        <a href="view_group.php?id=<?php echo $group_id; ?>" class="back-button">← Back to Group</a>
    </div>
</main>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>
</body>
</html>
