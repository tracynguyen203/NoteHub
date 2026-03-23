<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database configuration
include("../note/database/db_config_notes.php");

// Check session
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Invalid user session. Please log in again.",
    ]);
    exit();
}

// Create database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get the logged-in user's ID
$userID = $_SESSION['user_id'];

// Verify if the user exists in the database
$userCheck = $conn->prepare("SELECT id FROM users WHERE id = ?");
$userCheck->bind_param("i", $userID);
$userCheck->execute();
$userCheckResult = $userCheck->get_result();
if ($userCheckResult->num_rows === 0) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "User does not exist. Please log in again."]);
    exit();
}

if(isset($_POST['input'])) {
    $input = "%" . $_POST['input'] . "%";

    $stmt = $conn->prepare("SELECT id, title, content FROM notes WHERE user_id = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC");
    $stmt->bind_param("iss", $userID, $input, $input);
    $stmt->execute();

    $result = $stmt->get_result();
    $response = [];

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    echo json_encode($response);
    exit();
}

echo json_encode($response);
?>