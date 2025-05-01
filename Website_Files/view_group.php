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
$username = $_SESSION["username"];

$query = "SELECT id, group_name, username, group_size, members, funds FROM groups WHERE id = ?";
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
<link rel="icon" type="image/png" href="images/favicon.png" />
<meta charset="UTF-8">
<title>View Group - ComPool</title>
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
    <?php if ($group["username"] === $username || in_array($username, $members_list)): ?>
        <div class="chat-container">
        <h3><?php echo htmlspecialchars($group["group_name"]); ?></h3>
            <div id="chat-box" class="chat-box"></div>

            <form id="chat-form">
                <input type="text" id="chat-message" placeholder="Type a message..." required />
                <button type="submit">Send</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="group-card">
        <div class="user-info">
            Logged in as <strong><?php echo htmlspecialchars($username); ?></strong>
        </div>

        <div class="group-section">
            <h3>Group Info</h3>
            <p><strong>Group ID:</strong> <?php echo $group["id"]; ?></p>
            <p><strong>Group Name:</strong> <?php echo htmlspecialchars($group["group_name"]); ?></p>
            <p><strong>Created By:</strong> <?php echo htmlspecialchars($group["username"]); ?></p>
            <p><strong>Group Size:</strong> <?php echo $group["group_size"]; ?></p>
            <p><strong>Funds:</strong> $<?php echo number_format($group["funds"], 2); ?></p>

            <!-- ✅ Deposit Funds Button -->
<div style="text-align: center; margin-top: 15px;">
    <a href="deposit.php?group_id=<?php echo $group["id"]; ?>" class="add-group-button">
        <img src="images/teamwork.png" alt="Teamwork Image" class="button-image">
        <span>+ Deposit Funds</span>
    </a>
</div>

<!-- ✅ Payout Funds Button (only for group leader) -->
<?php if ($username === $group["username"]): ?>
    <div style="text-align: center; margin-top: 0px;">
        <a href="payout.php?group_id=<?php echo $group["id"]; ?>" class="add-group-button">
            <img src="images/favicon.png" alt="compool Image" class="button-image">
            <span>+ Payout Funds</span>
        </a>
    </div>
<?php endif; ?>

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

<!-- Javascript for chat updating -->
<script>
const groupId = <?php echo $group_id; ?>;

function loadMessages() {
    fetch('get_messages.php?group_id=' + groupId)
        .then(res => res.json())
        .then(data => {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = '';
            data.forEach(msg => {
                const p = document.createElement('p');
                p.innerHTML = `<strong>${msg.username}</strong>: ${msg.content} <span style="font-size: 0.8em; color: gray;">(${msg.timestamp})</span>`;
                chatBox.appendChild(p);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

document.getElementById('chat-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = document.getElementById('chat-message').value;
    fetch('send_message.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `group_id=${groupId}&message=${encodeURIComponent(message)}`
    }).then(() => {
        document.getElementById('chat-message').value = '';
        loadMessages();
    });
});

setInterval(loadMessages, 3000); // Poll every 3 seconds
loadMessages(); // Initial load
</script>

</body>
</html>
