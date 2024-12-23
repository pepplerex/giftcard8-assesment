<?php
// api.php

require_once 'db/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Handles task management.
 */
class TaskManager
{
    private $conn;
    private $logFile = '../logs/error.log';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Fetch all tasks.
     * 
     * @return array
     */
    public function getTasks()
    {
        $query = "SELECT * FROM tasks";
        $stmt = $this->conn->query($query);
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Add a new task.
     * 
     * @param string $name
     * @param string $description
     * @return string
     */
    public function addTask($name, $description)
    {
        $query = "INSERT INTO tasks (name, description) VALUES (?, ?)";
        $preparedStmt = new PreparedStatement($this->conn);
        $stmt = $preparedStmt->execute($query, [$name, $description]);

        if ($stmt) {
            return "Task added successfully.";
        } else {
            $this->logError("Failed to add task.");
            return "Failed to add task.";
        }
    }

    /**
     * Update a task.
     * 
     * @param int $id
     * @param string $name
     * @param string $description
     * @return string
     */
    public function updateTask($id, $name, $description)
    {
        $query = "UPDATE tasks SET name = ?, description = ? WHERE id = ?";
        $preparedStmt = new PreparedStatement($this->conn);
        $stmt = $preparedStmt->execute($query, [$name, $description, $id]);

        if ($stmt) {
            return "Task updated successfully.";
        } else {
            $this->logError("Failed to update task.");
            return "Failed to update task.";
        }
    }

    /**
     * Delete a task.
     * 
     * @param int $id
     * @return string
     */
    public function deleteTask($id)
    {
        $query = "DELETE FROM tasks WHERE id = ?";
        $preparedStmt = new PreparedStatement($this->conn);
        $stmt = $preparedStmt->execute($query, [$id]);

        if ($stmt) {
            return "Task deleted successfully.";
        } else {
            $this->logError("Failed to delete task.");
            return "Failed to delete task.";
        }
    }

    /**
     * Log error messages to a file.
     * 
     * @param string $message
     */
    private function logError($message)
    {
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
    }
}
