<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
    // Get a list of usernames, with optional limit
    public function getUsers($limit = null)
    {
        if ($limit !== null) {
            return $this->select("SELECT username FROM users LIMIT ?", ["i", $limit]);
        } else {
            return $this->select("SELECT username FROM users");
        }
    }

    // Get a single user by username
    public function getUserByUsername($username)
    {
        return $this->select("SELECT username FROM users WHERE username = ?", ["s", $username]);
    }

    // Create a new user with a hashed password
    public function createUser($username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare insert query.");
        }

        $stmt->bind_param("ss", $username, $hashedPassword);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Update the password for an existing user
    public function updateUserPassword($username, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare update query.");
        }

        $stmt->bind_param("ss", $hashedPassword, $username);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Delete a user by username
    public function deleteUser($username)
    {
        $query = "DELETE FROM users WHERE username = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare delete query.");
        }

        $stmt->bind_param("s", $username);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
