<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Database connection class.
 */
class Database
{
    private $host = 'localhost';
    private $db_name = 'giftcard8';
    private $username = 'root';
    private $password = '';
    public $conn;

    /**
     * Get the database connection.
     * 
     * @return mysqli
     */
    public function getConnection()
    {
        $this->conn = null;

        // Create a MySQLi connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        // Check for connection errors
        if ($this->conn->connect_error) {
            die("Connection error: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}

/**
 * Class for executing prepared statements.
 */
class PreparedStatement
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Prepare and execute a statement with bound parameters.
     * 
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function execute($query, $params)
    {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }

        // Bind parameters dynamically
        if ($params) {
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }
}
