<?php
session_start();
require 'db_connect.php';

// Redirect to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"]; // Logged-in user
$error = "";

// Check if group ID is provided
if (!isset($_GET["id"])) {
    header("Location: index.php"); // Redirect if no ID is given
    exit();
}

$group_id = intval($_GET["id"]); // Get group ID safely

// Fetch group details
$query = "SELECT group_name, username, group_size, members FROM groups WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Group not found.";
    exit();
}

$group = $result->fetch_assoc();
$members_list = explode(",", $group["members"]); // Convert members string to array

// Ensure only the group creator can update the group
if ($group["username"] !== $username) {
    echo "You do not have permission to edit this group.";
    exit();
}

// Fetch all users for member selection
$user_query = "SELECT username FROM users";
$user_result = $db->query($user_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = trim($_POST["group_name"]);
    $group_size = intval($_POST["group_size"]);
    $selected_members = isset($_POST["members"]) ? $_POST["members"] : [];

    // Ensure the creator is always included
    if (!in_array($username, $selected_members)) {
        array_unshift($selected_members, $username);
    }

    // Check if selected members exceed group size
    if (count($selected_members) > $group_size) {
        $error = "You selected more members than the allowed group size!";
    } elseif (empty($group_name) || $group_size <= 0) {
        $error = "All fields are required and group size must be a positive number.";
    } else {
        // Convert selected members array to comma-separated string
        $members = implode(",", $selected_members);

        // Update group in database
        $stmt = $db->prepare("UPDATE groups SET group_name = ?, group_size = ?, members = ? WHERE id = ?");
        $stmt->bind_param("sisi", $group_name, $group_size, $members, $group_id);

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
    <title>Update Group - ComPool</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; text-align: left; }
        a { text-decoration: none; color: blue; }
        .error { color: red; }
    </style>
    <script>
        function validateForm() {
            let size = document.getElementById("group_size").value;
            let selectedMembers = document.getElementById("members").selectedOptions.length + 1; // +1 for creator

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
    <h2>Update Group</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="update_group.php?id=<?php echo $group_id; ?>" onsubmit="return validateForm()">
        <label for="group_name">Group Name:</label><br>
        <input type="text" id="group_name" name="group_name" value="<?php echo htmlspecialchars($group['group_name']); ?>" required><br><br>

        <label for="group_size">Group Size:</label><br>
        <input type="number" id="group_size" name="group_size" value="<?php echo $group['group_size']; ?>" required min="1"><br><br>

        <label for="members">Select Members (You are always included):</label><br>
        <select name="members[]" id="members" multiple required>
            <?php while ($user = $user_result->fetch_assoc()): ?>
                <?php if ($user["username"] !== $username): ?>
                    <option value="<?php echo $user["username"]; ?>" 
                        <?php echo in_array($user["username"], $members_list) ? "selected" : ""; ?>>
                        <?php echo $user["username"]; ?>
                    </option>
                <?php endif; ?>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Submit</button>
        <a href="index.php">Cancel</a>
    </form>
</div>

</body>
</html>
