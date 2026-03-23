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

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'] ?? "Untitled Note";
$content = $data['content'] ?? " ";
$password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null; // Hash the password if provided

// Insert the note into the database
$stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, password) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit();
}
$stmt->bind_param("isss", $userID, $title, $content, $password);

if ($stmt->execute()) {
    $noteID = $stmt->insert_id; // Get the ID of the newly inserted note
    echo json_encode([
        "id" => $noteID,
        "userID" => $userID,
        "title" => $title,
        "content" => $content,
        "passwordProtected" => $password !== null
    ]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>