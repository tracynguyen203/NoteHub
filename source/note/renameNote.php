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

// DB config
if($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $noteID = isset($_POST['id']) ? intval($_POST['id']) : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    
    if (!$userID) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    if ($noteID === null) {
        http_response_code(400);
        echo json_encode(["error" => "Missing noteID"]);
        exit;
    }

    if (empty($title)) {
        echo json_encode(["success" => false, "message" => "Title cannot be empty."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE notes SET title = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $title, $noteID, $userID);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "id" => $noteID,
            "newTitle" => htmlspecialchars($title)
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed."]);
    }

    $stmt->close();
}
    $conn->close();
?>