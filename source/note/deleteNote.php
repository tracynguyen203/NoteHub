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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
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

    // **Read the JSON request body**
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    // Check if note_id exists in the decoded JSON data
    if (isset($data['note_id'])) {
        $noteID = $data['note_id'];

        // Corrected SQL query: using 'user_id' (lowercase)
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $noteID, $userID);


        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing note_id in JSON body"]);
        http_response_code(400); // Bad Request
    }

    $stmt->close();
    $conn->close();
}
?>