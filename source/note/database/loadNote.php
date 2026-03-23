<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["success" => false, "error" => "You must be logged in to access notes."]);
    exit();
}

// Get the logged-in user's ID from the session
$userID = $_SESSION['user_id'];

// Connect to the database
include("db_config_notes.php");

if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Get note ID and password from the request
$noteID = $_POST['note_id'] ?? null;
$password = $_POST['password'] ?? null;

if ($noteID) {
    // Fetch the specific note
    $sql = "SELECT id, title, content, is_password_protected, password FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $noteID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(404); // Not Found
            echo json_encode(["success" => false, "error" => "Note not found or you do not have access."]);
            $stmt->close();
            $conn->close();
            exit();
        }

        $note = $result->fetch_assoc();

        // If the note is password-protected, verify the password
        if ($note['is_password_protected']) {
            if (!$password) {
                http_response_code(403); // Forbidden
                echo json_encode(["success" => false, "error" => "This note is password-protected. Please provide a password to access it."]);
                $stmt->close();
                $conn->close();
                exit();
            }

            if (!password_verify($password, $note['password'])) {
                http_response_code(403); // Forbidden
                echo json_encode(["success" => false, "error" => "Incorrect password."]);
                $stmt->close();
                $conn->close();
                exit();
            }
        }

        // Return the note content
        unset($note['password']); // Do not expose the password in the response
        echo json_encode(["success" => true, "note" => $note]);

        $stmt->close();
        $conn->close();
        exit();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "error" => "Failed to prepare the query: " . $conn->error]);
        $conn->close();
        exit();
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "error" => "Note ID is required."]);
    $conn->close();
    exit();
}
?>