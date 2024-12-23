<?php
require_once 'db/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validating form inputs
    $errors = [];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If there are no errors, we proceed to register the user
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create a new database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Prepare the SQL statement
        $query = "INSERT INTO users (name, email, password) VALUES (?, ? ,?)";
        $preparedStmt = new PreparedStatement($conn);
        $stmt = $preparedStmt->execute($query, [$name, $email, $hashed_password]);

        if ($stmt) {
            echo "User registered successfully.";
        } else {
            echo "Error: Could not register user.";
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
