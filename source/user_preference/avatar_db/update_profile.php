<?php
session_start();
header('Content-Type: application/json');

// authentication check
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require 'connect_database.php';

$data = json_decode(file_get_contents('php://input'), true);
$avatar_number = isset($data['avatar']) ? (int)$data['avatar'] : 0;

// validate avatar selection
if ($avatar_number < 1 || $avatar_number > 20) {
    die(json_encode(['success' => false, 'message' => 'Invalid avatar']));
}

$avatar_url = "https://n0tehub.me/user_preference/avatar/$avatar_number.png";
$conn = connect();

$stmt = mysqli_prepare($conn, 
    "INSERT INTO avatars (user_id, avatar_url) 
     VALUES (?, ?) 
     ON DUPLICATE KEY UPDATE avatar_url = ?"
);
mysqli_stmt_bind_param($stmt, 'iss', $_SESSION['user_id'], $avatar_url, $avatar_url);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Avatar updated!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>