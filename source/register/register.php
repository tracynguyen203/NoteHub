<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ../note/noteManagement.php");
    exit();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer library and generateActivationToken function
require 'generateActivationToken.php';
require '/home/ijdtkfvr/tmp/phpmailer/src/Exception.php';
require '/home/ijdtkfvr/tmp/phpmailer/src/PHPMailer.php';
require '/home/ijdtkfvr/tmp/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection details
$servername = "localhost";
$username = "ijdtkfvr_nam";
$password = "";
$dbname = "ijdtkfvr_user_accounts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";
$toastClass = "";

$username = '';
$email = '';
$password = '';
$confirm_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required. Please fill in";
        $toastClass = "#dc3545";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format. Please try again.";
        $toastClass = "#dc3545"; // Danger color
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $toastClass = "#dc3545"; // Danger color
    } elseif ($password !== $confirm_password) {
        $message = "Password do not match. Please try again.";
        $toastClass = "#dc3545"; // Danger color
    } else {
        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        if ($checkEmailStmt === false) {
            die("SQL error (check email): " . $conn->error);
        }
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $message = "Email ID already exists. Please use a different email.";
            $toastClass = "#007bff"; // Primary color
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_verified) VALUES (?, ?, ?, 0)");
            if ($stmt === false) {
                die("SQL error (insert user): " . $conn->error);
            }
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {

                //AUTO LOGIN
                session_start();
                $_SESSION['user'] = [
                    'id' => $stmt->insert_id,
                    'username' => $username,
                    'email' => $email,
                    'is_verified' => 0
                ];
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                
                // Generate activation token and send email (executed after redirect)
                $activationLink = generateActivationToken($email);

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'n0tehubbb@gmail.com';
                    $mail->Password = 'dzdj drcv unur obnb';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('n0tehubbb@gmail.com', 'n0tehub');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Activate Your Account';
                    $mail->Body = "Dear $username,<br><br>Please click the link below to activate your account:<br><a href='$activationLink'>$activationLink</a><br><br>Thank you!";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mail Error: {$mail->ErrorInfo}");
                }
            } else {
                $message = "Error: " . $stmt->error;
                $toastClass = "#dc3545"; // Danger color
            }

            $stmt->close();
            header("Location: ../user_preference/user_preferences.php");
        }

        $checkEmailStmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!-- Others  -->
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">

    <!-- CSS  -->
    <link rel="stylesheet" href="register.css?v=1.0.3">

    <!-- JS  -->
    <script src="register.js" defer></script>

    <!-- GG Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Bytesized&family=Limelight&family=Megrim&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Bytesized&family=Limelight&family=Megrim&family=Noto+Sans+Math&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600&display=swap" rel="stylesheet">
        
    <title>Registration</title>
</head>

<body>
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" 
                role="alert" aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass; ?>;">

                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" class="form-control mt-5 p-4">

            <div class="header row text-center">
                <div class="icon-text"> Welcome ⎛⎝ ≽ > ⩊ < ≼ ⎠⎞</div>
                <div class="register-title"> create a new account </div>
            </div>

            <div class="inputBox mb-2">
                <input type="text" name="username" id="username"
                    class="form-control" placeholder="Name" required 
                    value="<?= htmlspecialchars($username)?>">
            </div>

            <div class="inputBox mb-2 mt-2">
                <input type="email" name="email" id="email"
                    class="form-control" placeholder="Email" required
                    value="<?= htmlspecialchars($email)?>">
            </div>

            <div class="inputBox mb-2 mt-2">
                <input type="password" name="password" id="password" 
                    class="form-control" minlength="6" placeholder="New password" required
                    value="<?= htmlspecialchars($password)?>">

                <input type="password" name="confirm_password" id="confirm_password"
                    class="form-control" style="margin-bottom: 10px; margin-top: 8px;" placeholder="Confirm password" required
                    value="<?= htmlspecialchars($confirm_password)?>">

                <button type="button" id="btnToggle" class="btn btn-sm btn-outline-secondary" onclick="displayPass()">
                    <i id="eyeIcon" class="fa fa-eye"></i> Show password </button>
            </div>

            <div class="buttonBox">
                <button id="register" type="submit" class="btn btn-success btn-outline-light" style="background-color: #99BC85;"> Register Now </button>
            </div>

            <div class="mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; color: navy;">Already have an account? <a href="https://n0tehub.me/login/login.php"
                        style="text-decoration: none;">Sign in here </a></p>
            </div>
        </form>
    </div>
</body>

</html>