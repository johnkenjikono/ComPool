<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class ChatModel extends Database
{
    // Get all chat messages for a specific group
    public function getMessagesByGroupId($groupId)
    {
        return $this->select(
            "SELECT * FROM messages WHERE group_id = ? ORDER BY timestamp ASC",
            ["i", $groupId]
        );
    }

    // Insert new message
    public function sendMessage($groupId, $username, $content)
    {
        $query = "INSERT INTO messages (group_id, username, content) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
    
        if (!$stmt) {
            throw new Exception("Failed to prepare insert statement for message.");
        }
    
        $stmt->bind_param("iss", $groupId, $username, $content);
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }
    
}
