<?php
session_start();
require 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Logged-in user
$error = "";

// Fetch all users for the members selection dropdown
$user_query = "SELECT username FROM users";
$user_result = $db->query($user_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = trim($_POST["group_name"]);
    $group_size = intval($_POST["group_size"]);
    $selected_members = isset($_POST["members"]) ? $_POST["members"] : [];

    // Ensure the creator is always included
    if (!in_array($username, $selected_members)) {
        array_unshift($selected_members, $username); // Add creator to the start of the list
    }

    // Check if selected members exceed group size
    if (count($selected_members) > $group_size) {
        $error = "You selected more members than the allowed group size!";
    } elseif (empty($group_name) || $group_size < 1) {
        $error = "All fields are required and group size must be greater then 1.";
    } else {
        // Convert selected members array to comma-separated string
        $members = implode(",", $selected_members);

        // Insert new group
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Group - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        a { text-decoration: none; color: blue; }
        .error { color: red; }
    </style>
    <script>
        function validateForm() {
            let size = document.getElementById("group_size").value;
            let selectedMembers = document.getElementById("members").selectedOptions.length + 1; // +1 to account for creator

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

<div class="container">
    <h2>Add Group</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="add_group.php" onsubmit="return validateForm()">
        <label for="group_name">Group Name:</label><br>
        <input type="text" id="group_name" name="group_name" required><br><br>

        <label for="group_size">Group Size (Remeber you are always included): </label><br>
        <input type="number" id="group_size" name="group_size" required min="1"><br><br>

        <label for="members">Select Members (You can add more later):</label><br>
        <select name="members[]" id="members" multiple required>
            <?php while ($user = $user_result->fetch_assoc()): ?>
                <?php if ($user["username"] !== $username): // Exclude creator from dropdown ?>
                    <option value="<?php echo $user["username"]; ?>"><?php echo $user["username"]; ?></option>
                <?php endif; ?>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Submit</button>
        <a href="index.php">Cancel</a>
    </form>
</div>

</body>
</html>
