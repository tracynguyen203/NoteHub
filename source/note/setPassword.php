<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database configuration
include("../note/database/db_config_notes.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "You must be logged in to set a password for a note."]);
    exit();
}

// Decode the incoming JSON payload
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['note_id']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing required fields: note_id or password."]);
    exit();
}

$noteID = $data['note_id'];
$password = $data['password'];
$userID = $_SESSION['user_id'];

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Create a database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Verify that the note exists and belongs to the logged-in user
$stmt = $conn->prepare("SELECT id FROM notes WHERE id = ? AND user_id = ?");
if (!$stmt) {
    error_log("Statement preparation failed: " . $conn->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare statement."]);
    exit();
}
$stmt->bind_param("ii", $noteID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    error_log("Note not found or user does not have permission.");
    http_response_code(403);
    echo json_encode(["error" => "You do not have permission to modify this note or it does not exist."]);
    $stmt->close();
    $conn->close();
    exit();
}

// Update the note with the password and set is_password_protected to TRUE
$updateStmt = $conn->prepare("UPDATE notes SET password = ?, is_password_protected = 1 WHERE id = ?");
if (!$updateStmt) {
    error_log("Statement preparation failed: " . $conn->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare statement for updating password."]);
    exit();
}
$updateStmt->bind_param("si", $hashedPassword, $noteID);

if ($updateStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password has been set successfully for the note."]);
} else {
    error_log("Error executing statement: " . $updateStmt->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to update the password."]);
}

$updateStmt->close();
$conn->close();
?>