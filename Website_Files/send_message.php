<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    exit("Not logged in");
}

$username = $_SESSION["username"];
$group_id = intval($_POST['group_id']);
$content = trim($_POST['message']);

if ($content !== "") {
    $stmt = $db->prepare("INSERT INTO messages (group_id, username, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $group_id, $username, $content);
    $stmt->execute();
}
?>
