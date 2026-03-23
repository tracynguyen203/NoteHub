<?php
session_start();
include("database/db_config_notes.php");

header('Content-Type: application/json');

file_put_contents(__DIR__ . '/pin_debug.log', date('c') . " POST: " . json_encode($_POST) . "\n", FILE_APPEND);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userID = $_SESSION['user_id'];
$noteId = $_POST['noteId'] ?? null;
$pinState = $_POST['pinState'] ?? null;

if ($noteId === null || $pinState === null) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$stmt = $conn->prepare("UPDATE notes SET pin_note = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("iii", $pinState, $noteId, $userID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>