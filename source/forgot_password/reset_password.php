<<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// database connection
$servername = "localhost";
$username = "ijdtkfvr_nam";
$password = "";
$dbname = "ijdtkfvr_user_accounts";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";
$token = $_GET['token'] ?? ''; // get the token from the URL

if (!empty($token)) {
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expiresAt = $row['expires_at'];

        if (strtotime($expiresAt) < time()) {
            $message = "This reset link has expired. Please request a new password reset.";
            $token = ""; // invalidate the token
        }
    } else {
        $message = "Invalid reset link. Please request a new password reset.";
        $token = "";
    }
    $stmt->close();
} else {
    $message = "Invalid reset link. Please request a new password reset.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($token)) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "Please fill in all fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (strlen($newPassword) < 8) {
        $message = "Password must be at least 8 characters long.";
    } else {
        // hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // update password
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        $updateStmt->execute();

        // delete reset token
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $deleteStmt->bind_param("s", $token);
        $deleteStmt->execute();

        $message = "Your password has been reset successfully. You can now log in.";
        $token = "";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d717a59f45.js" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="forgot_password.css">
</head>
<body>

    <div class="container mt-5 mb-5">
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($token)): ?>
            <form method="POST" class="form-container mt-4">
                <h2 class="text-center">Reset Your Password</h2>
                <p class="text-center">Enter your new password below.</p>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter new password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm your new password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>>