<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Include DB
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

// DB config
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Get note id from db
    if(!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid or missing note ID"]);
        exit();
    }
    $noteID = intval($_POST['id']);

    //Get content
    if (!isset($_POST['content']) || trim($_POST['content']) === '') {
        http_response_code(400);
        echo json_encode(["error" => "Missing content"]);
        exit();
    }
    $content = $_POST['content'] ?? '';

    //Update note
    $stmt = $conn->prepare("UPDATE notes SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $content, $noteID, $userID);
    
    if ($stmt->execute()) {
        error_log("Autosave: Note ID " . $noteID . " updated successfully", 0);
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to save note"]);
    }
    
    $stmt->close();
    $conn->close();
}
?>