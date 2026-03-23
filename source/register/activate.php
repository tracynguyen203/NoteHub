<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost"; // Update if different
$username = "ijdtkfvr_nam"; // Verify this username
$password = ""; // Verify this password
$dbname = "ijdtkfvr_user_accounts"; // Verify this database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Variables for messages
$message = "";
$messageType = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare a query to retrieve the token details
    $stmt = $conn->prepare("SELECT email, expires_at FROM activation_tokens WHERE token = ?");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expiresAt = $row['expires_at'];

        if (strtotime($expiresAt) > time()) {
            // Mark the user as activated
            $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
            if (!$updateStmt) {
                die("SQL error: " . $conn->error);
            }
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();

            // Delete the token after activation
            $deleteStmt = $conn->prepare("DELETE FROM activation_tokens WHERE token = ?");
            if (!$deleteStmt) {
                die("SQL error: " . $conn->error);
            }
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();

            $message = "Account successfully activated! You can now log in.";
            $messageType = "success";
        } else {
            $message = "Activation link has expired. Please request a new activation email.";
            $messageType = "danger";
        }
    } else {
        $message = "Invalid activation token.";
        $messageType = "danger";
    }
} else {
    $message = "No activation token provided.";
    $messageType = "danger";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation - n0tehub</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome  -->
    <script src="https://kit.fontawesome.com/d717a59f45.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="activate.css">    
</head>
<body>
    <!--navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-2">
        <div class="d-flex align-items-center w-100 brand">
            <button class="navbar-toggler mr-2 ml-2" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="https://n0tehub.me/index.php">
                <img src="https://n0tehub.me/images/logo.png" class="logo"> n0tehub
            </a>
        </div>
            
        <!--Menu-->
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item-home active">
                    <a class="nav-link" href="https://n0tehub.me/index.php"><i class="fa-solid fa-house"></i> Home<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://n0tehub.me/about/aboutus.html"><i class="fa-solid fa-circle-info"></i> About us<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item-login">
                    <a class="nav-link" href="https://n0tehub.me/login/login.php"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://n0tehub.me/register/register.php"><i class="fa-solid fa-arrow-up-right-from-square"></i> Sign up<span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="mt-5">Account Activation</h1>
                <p class="lead mt-3">Welcome to NoteHub! Your journey to seamless note management starts here.</p>
                <div class="alert alert-<?php echo $messageType; ?> mt-4" role="alert">
                    <?php echo $message; ?>
                </div>
                <?php if ($messageType === "success"): ?>
                    <a href="https://n0tehub.me/login/login.php" class="btn btn-success mt-3">Log In</a>
                <?php else: ?>
                    <a href="https://n0tehub.me/register/register.php" class="btn btn-primary mt-3">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p class="text-center">&copy; No copyright - Since 2025.</p>
        <div style="text-align:center; padding-bottom:20px;">
          <a href="https://n0tehub.me/about/aboutus.html">Click here to see more about us and how it works!</a>
        </div>
    </footer>
</body>
</html>