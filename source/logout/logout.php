<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.php");
        exit();
    }

    // clear all session variables
    $_SESSION = array();

    // delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    // clear the remember token cookie
    setcookie('remember_token', '', time() - 3600, '/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Log Out | NoteHub</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
    integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous"/>

    <!-- jQuery JavaScript library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- JavaScript file for Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="logout.css">    

    <!-- Javascript -->
    <script src="logout.js"></script>
</head>
<body>
    <div class="container p-5 d-flex flex-column align-items-center">
        <h4>Log Out Successfully</h4>
        <p>Your account has been logged out of the system.</p>
        <p>Thanks for trying our website, have a nice day!</p>
        <p>Click <a href="https://n0tehub.me/login/login.php">here</a> to return to the login page, or the website will automatically redirect after <span id="counter" class="text-danger">10</span> second(s) left.</p>
        <a href="https://n0tehub.me/login/login.php" class="btn btn-success px-5">Log in</a>
    </div>
</body>
</html>