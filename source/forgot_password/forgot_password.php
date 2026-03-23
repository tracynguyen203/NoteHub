<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/ijdtkfvr/tmp/phpmailer/src/Exception.php';
require '/home/ijdtkfvr/tmp/phpmailer/src/PHPMailer.php';
require '/home/ijdtkfvr/tmp/phpmailer/src/SMTP.php';

// database connection
$servername = "localhost";
$username = "ijdtkfvr_nam";
$password = "";
$dbname = "ijdtkfvr_user_accounts";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// initialize the message and toast class
$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "We have sent an email, if your account exists you will receive an email.";
    $toastClass = "#28a745"; // toast for success

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                // generate a unique token
                $token = bin2hex(random_bytes(16));
                $expiresAt = date("Y-m-d H:i:s", strtotime("+1 hour"));

                // insert token into the database
                $insertStmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                if ($insertStmt) {
                    $insertStmt->bind_param("sss", $email, $token, $expiresAt);
                    $insertStmt->execute();

                    // send the email with PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'n0tehubbb@gmail.com';
                        $mail->Password = '';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // email content
                        $mail->setFrom('n0tehubbb@gmail.com', 'n0tehub');
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $resetLink = "https://n0tehub.me/forgot_password/reset_password.php?token=$token";
                        $mail->Body = "Hi,<br><br>Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a><br><br>If you did not request a password reset, please ignore this email.";

                        $mail->send();
                    } catch (Exception $e) {
                        // log the error but do not show it to the user
                        error_log("Mailer Error: " . $mail->ErrorInfo);
                    }
                }
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    
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
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-5">
            <h2 class="text-center mb-4">Forgot Password</h2>
            <p class="text-center">Enter your email address to reset your password.</p>

            <div class="mb-4">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
    </div>
</body>
</html>