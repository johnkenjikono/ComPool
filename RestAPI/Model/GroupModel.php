<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class GroupModel extends Database
{
    // Fetch all groups from the database
    public function getGroups()
    {
        return $this->select("SELECT * FROM groups ORDER BY created_at DESC");
    }

    // Fetch a specific group by its ID
    public function getGroupById($id)
    {
        return $this->select("SELECT * FROM groups WHERE id = ?", ["i", $id]);
    }

    // Create a new group in the database
    public function createGroup($name, $creator, $size, $members)
    {
        $membersStr = implode(",", $members);  // Convert array to comma-separated string
        $query = "INSERT INTO groups (group_name, username, group_size, members) VALUES (?, ?, ?, ?)";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare createGroup statement.");
        }

        $stmt->bind_param("ssis", $name, $creator, $size, $membersStr);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Update an existing group's size and members
    public function updateGroup($id, $size, $members)
    {
        $membersStr = implode(",", $members);
        $query = "UPDATE groups SET group_size = ?, members = ? WHERE id = ?";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare updateGroup statement.");
        }

        $stmt->bind_param("isi", $size, $membersStr, $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Delete a group by ID
    public function deleteGroup($id)
    {
        $query = "DELETE FROM groups WHERE id = ?";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare deleteGroup statement.");
        }

        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function addFundsToGroup($groupId, $amount)
    {
        $query = "UPDATE groups SET funds = funds + ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare addFundsToGroup.");
        }
        $stmt->bind_param("di", $amount, $groupId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function addFundsToUser($username, $amount)
    {
        $query = "UPDATE users SET balance = balance + ? WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ds", $amount, $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    public function deductFundsFromGroup($groupId, $amount) {
        $query = "UPDATE groups SET funds = funds - ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("di", $amount, $groupId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

}
