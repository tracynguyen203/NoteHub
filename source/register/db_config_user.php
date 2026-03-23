<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost"; // Update if different
$username = "ijdtkfvr_nam"; // Verify this username
$password = ""; // Verify this password
$dbname = "ijdtkfvr_user_accounts"; // Verify this database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Success message
echo "Connected successfully!";
?>