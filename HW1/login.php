<?php
session_start();
require 'db_connect.php'; // Include database connection

// Initialize variables
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST["userid"]);
    $password = trim($_POST["password"]);

    if (empty($userid) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {
                // Successful login, create session
                $_SESSION["username"] = $userid;
                $_SESSION["logged_in"] = true;

                // Send 201 Created response indicating login session created
                http_response_code(201);
                echo json_encode(['message' => 'Login successful, session created']);
                exit(); // Make sure to stop further execution
            } else {
                // Incorrect password: return 401 Unauthorized with error message
                http_response_code(401);
                echo json_encode(['message' => 'Wrong User ID or password.']);
                exit(); // Ensure no HTML is outputted and stop further execution
            }
        } else {
            // User not found: return 401 Unauthorized with error message
            http_response_code(401);
            echo json_encode(['message' => 'Wrong User ID or password.']);
            exit(); // Ensure no HTML is outputted and stop further execution
        }
        mysqli_stmt_close($stmt);
    }
}
?>