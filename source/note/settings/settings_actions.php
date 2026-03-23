<?php
file_put_contents(__DIR__ . '/debug.log', date('c') . " POST: " . json_encode($_POST) . "\n", FILE_APPEND);
session_start();
include("../database/db_config_notes.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';
$userID = $_SESSION['user_id'];

if ($action === 'change_font_color') {
    $font = $_POST['font'] ?? '';
    $color = $_POST['color'] ?? '';
    
    if (empty($font) || empty($color)) {
        die(json_encode(['success' => false, 'error' => 'Missing font or color']));
    }

    try {
        $stmt = $conn->prepare("UPDATE notes SET font_note = ?, note_color = ? WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssi", $font, $color, $userID);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Settings updated']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'change_password') {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];
    if ($new !== $confirm) {
        echo json_encode(['success' => false, 'error' => 'Passwords do not match']);
        exit;
    }
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($hash);
    $stmt->fetch();
    $stmt->close();
    if (!password_verify($current, $hash)) {
        echo json_encode(['success' => false, 'error' => 'Current password incorrect']);
        exit;
    }
    $newHash = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $newHash, $userID);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'change_username') {
    $username = trim($_POST['username']);
    if ($username === '') {
        echo json_encode(['success' => false, 'error' => 'Username cannot be empty']);
        exit;
    }
    $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
    $stmt->bind_param("si", $username, $userID);
    $stmt->execute();
    $stmt->close();
    $_SESSION['username'] = $username;
    echo json_encode(['success' => true, 'username' => $username]);
    exit;
}

if ($action === 'change_avatar') {
    $avatar = $_POST['avatar'];
    $stmt = $conn->prepare("UPDATE avatars SET avatar_url=? WHERE user_id=?");
    $stmt->bind_param("si", $avatar, $userID);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'avatar' => $avatar]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid action']);