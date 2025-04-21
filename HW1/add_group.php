<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$error = "";

// Fetch all users for the members selection dropdown
$user_query = "SELECT username FROM users";
$user_result = $db->query($user_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = trim($_POST["group_name"]);
    $group_size = intval($_POST["group_size"]);
    $selected_members = isset($_POST["members"]) ? $_POST["members"] : [];

    if (!in_array($username, $selected_members)) {
        array_unshift($selected_members, $username);
    }

    if (count($selected_members) > $group_size) {
        $error = "You selected more members than the allowed group size!";
    } elseif (empty($group_name) || $group_size < 1) {
        $error = "All fields are required and group size must be greater than 1.";
    } else {
        $members = implode(",", $selected_members);
        $stmt = $db->prepare("INSERT INTO groups (group_name, username, group_size, members) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $group_name, $username, $group_size, $members);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="../images/favicon.png" />
    <meta charset="UTF-8">
    <title>Add Group - ComPool</title>
    <link rel="stylesheet" href="style_sample.css">

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

    <script>
        function validateForm() {
            let size = document.getElementById("group_size").value;
            let selectedMembers = document.getElementById("members").selectedOptions.length + 1;

            if (isNaN(size) || size <= 0) {
                alert("Group size must be a positive number.");
                return false;
            }
            if (selectedMembers > size) {
                alert("You selected more members than the allowed group size!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>


<main class="form-wrapper">
    <div class="form-box">
        <h2>Add Group</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="add_group.php" onsubmit="return validateForm()">
            <label for="group_name">Group Name:</label>
            <input type="text" id="group_name" name="group_name" required>

            <label for="group_size">Group Size (you are always included):</label>
            <input type="number" id="group_size" name="group_size" required min="1">

            <label for="members">Select Members:</label>
            <select name="members[]" id="members" multiple required>
                <?php while ($user = $user_result->fetch_assoc()): ?>
                    <?php if ($user["username"] !== $username): ?>
                        <option value="<?php echo $user["username"]; ?>"><?php echo $user["username"]; ?></option>
                    <?php endif; ?>
                <?php endwhile; ?>
            </select>

            <button type="submit">Create Group</button>
            <a href="index.php" class="login-button">Cancel</a>
        </form>
    </div>
</main>

<!-- Footer -->
<footer>
    <div>&copy; 2025 ComPool. All rights reserved.</div>
    <div>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</div>
</footer>

</body>
</html>
