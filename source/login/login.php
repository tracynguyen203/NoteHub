<?php

session_start();

// redirect to `noteManagement.php` if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../note/noteManagement.php");
    exit();
}

// database connection details
$servername = "localhost";
$db_username = "ijdtkfvr_nam"; // Database username
$db_password = ""; // Database password
$dbname = "ijdtkfvr_user_accounts"; // Database name

// database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// handle "Remember Me" function
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../note/noteManagement.php");
        exit();
    }
}

// initialize variables for form handling
$message = "";
$toastClass = "";
$identifier = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // sanitize and validate user inputs
    $identifier = trim($_POST['identifier']);
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        $message = "Please fill in all fields";
        $toastClass = "#dc3545"; // red for error
    } else {
        // determine whether the identifier is an email or username
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM users WHERE email = ?";
        } else {
            $query = "SELECT * FROM users WHERE username = ?";
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // handle "Remember Me" function
                if (isset($_POST['remember'])) {
                    // generate a random token
                    $token = bin2hex(random_bytes(16));

                    // store token in the database
                    $update = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $update->bind_param("si", $token, $user['id']);
                    $update->execute();

                    // set cookie - 86400 seconds = 1 day
                    setcookie("remember_token", $token, time() + (86400 * 30), "/");
                }

                // redirect to the note management page
                header("Location: ../note/noteManagement.php");
                exit();
            } else {
                $message = "Incorrect password";
                $toastClass = "#dc3545";
            }
        } else {
            $message = "No user found with the provided credentials";
            $toastClass = "#dc3545";
        }

        $stmt->close();
    }
}

$conn->close();
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
    <link rel="stylesheet" href="login.css">

    <!-- JS  -->
    <script src="login.js" defer></script>

    <!-- GG Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Bytesized&family=Limelight&family=Megrim&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Bytesized&family=Limelight&family=Megrim&family=Noto+Sans+Math&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600&display=swap" rel="stylesheet">
    
    <title> Sign In | NoteHub </title>
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
                <div class="icon-text"> NoteHub ദ്ദി(˵ •̀ ᴗ - ˵ ) ✧</div>
                <div class="register-title"> where you can create some notes  </div>
            </div>

            <div class="inputBox form-floating mb-3">
                <input type="text" name="identifier" id="floatingInput"
                    class="form-control" required 
                    value="<?= htmlspecialchars($identifier ?? '')?>">
                <label for="floatingInput">Email</label>
            </div>

            <div class="inputBox form-floating mb-3">
                <input type="password" name="password" id="floatingInput"
                    class="form-control" required
                    value="<?= htmlspecialchars($password ?? '')?>">
                <label for="floatingInput">Password</label>
            </div>

            <div class="form-group custom-control custom-checkbox rememberMe">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember"<?php if (isset($_COOKIE['user'])) echo 'checked' ?> checked>
                    <label class="custom-control-label" for="remember">Remember me</label>
            </div>

            <div class="form-group buttonBox">
                <button type="submit" class="btn btn-success btn-outline-light" style="background-color: #99BC85;"> Log In </button>
            </div>

            <div class="form-group">
                <p>Forgot password? <a href="/forgot_password/forgot_password.php">Click here</a></p>
                <p>Do not have an account? <a href="/register/register.php">Register now</a></p>
            </div>
        </form>
    </div>
</body>
</html>