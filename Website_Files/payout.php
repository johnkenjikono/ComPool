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

$group_id = intval($_GET["group_id"]);
$username = $_SESSION["username"];

$query = "SELECT id, group_name, username, members, funds FROM groups WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Group not found.";
    exit();
}

$group = $result->fetch_assoc();
$members = array_map('trim', explode(",", $group['members']));

if ($username !== $group['username']) {
    echo "Only the group leader can initiate a payout.";
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipient = $_POST['recipient'];

    if (!in_array($recipient, $members)) {
        $message = "Invalid recipient.";
    } else {
        $amount = $group['funds'];

        // Begin transaction
        $db->begin_transaction();

        try {
            // Add funds to selected member's balance
            $update_user = $db->prepare("UPDATE users SET balance = balance + ? WHERE username = ?");
            $update_user->bind_param("ds", $amount, $recipient);
            $update_user->execute();

            // Set group funds to 0
            $update_group = $db->prepare("UPDATE groups SET funds = 0 WHERE id = ?");
            $update_group->bind_param("i", $group_id);
            $update_group->execute();

            // Log payout in chat
            $chat_message = "$username paid out \$$amount to $recipient.";
            $insert_message = $db->prepare("INSERT INTO messages (group_id, username, content) VALUES (?, ?, ?)");
            $insert_message->bind_param("iss", $group_id, $username, $chat_message);
            $insert_message->execute();

            // Commit transaction
            $db->commit();
            $message = "Successfully paid \$$amount to $recipient.";
        } catch (Exception $e) {
            // Rollback in case of error
            $db->rollback();
            $message = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta charset="UTF-8">
    <title>Payout - ComPool</title>
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
    <div class="group-card">
        <h2>Payout funds from <?php echo htmlspecialchars($group['group_name']); ?></h2>
        <p><strong>Available Funds:</strong> $<?php echo number_format($group['funds'], 2); ?></p>

        <?php if ($message): ?>
            <p style="color: green; text-align: center;"> <?php echo htmlspecialchars($message); ?> </p>
        <?php endif; ?>

        <form method="POST" style="text-align: center;">
            <label for="recipient">Choose recipient:</label>
            <select name="recipient" id="recipient" required>
                <option value="">-- Select Member --</option>
                <?php foreach ($members as $member): ?>
                    <option value="<?php echo htmlspecialchars($member); ?>"><?php echo htmlspecialchars($member); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit" class="add-group-button">Payout Funds</button>
        </form>

        <div style="text-align: center; margin-top: 15px;">
            <a href="view_group.php?id=<?php echo $group_id; ?>" class="back-button">← Back to Group</a>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>
</body>
</html>
