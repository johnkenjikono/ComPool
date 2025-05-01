<?php
require 'db_connect.php';

$group_id = intval($_GET['group_id']);

$stmt = $db->prepare("SELECT username, content, timestamp FROM messages WHERE group_id = ? ORDER BY timestamp ASC");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
