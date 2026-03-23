<?php

// Database connection details
$servername = "localhost"; // Update if different
$username = "ijdtkfvr_nam"; // Verify this username
$password = ""; // Verify this password
$dbname = "ijdtkfvr_user_accounts"; // Verify this database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to NoteHub !</title>
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
    <link rel="stylesheet" href="index.css">    

</head>
<body>
    <!--navbar-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-2">
        <div class="d-flex align-items-center w-100 brand">
            <button class="navbar-toggler mr-2 ml-2" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="https://n0tehub.me/index.php">
                <img src="https://n0tehub.me/images/noteHub.png" class="logo"> NoteHub
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

    <!--Slogan-->
    <div class="main">
        <h1 class="slogan">Never Lose a Thought Again!</h1>
        <p class="slogan">All your notes, files, and images are synced across all your devices.<br>Sign up now for a wonderful free trial on our website!<br></p>
        <a href="https://n0tehub.me/register/register.php">
            <button class="btn btn-success" type="button">Sign Up Now</button>
        </a>
    </div>

    <!--Sample-->
    <div class="full-width-container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 p-0">
                <img class="mock-up-sample" src="https://github.com/tracynguyen203/NoteHub/blob/main/sample.jpg?raw=true" alt="mock up">
            </div>
        </div>      
    </div>

    <!--Functionalities-->
    <div class="container">
        <h2 class="text-center mb-4">Simple Notes, Powerful Features.</h2>
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-regular fa-window-restore"></i>
              <h4 class="fs-5 mb-0">Anytime & Anyplace</h4>
            </div>
            <p class="small text-muted">Forget syncing. Your notes are always current, across every device you use.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-solid fa-tags"></i>
              <h4 class="fs-5 mb-0">Powerful tagging</h4>
            </div>
            <p class="small text-muted">Organize with tags and find everything in a flash with instant search.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-regular fa-handshake"></i>
              <h4 class="fs-5 mb-0">Work together</h4>
            </div>
            <p class="small text-muted">From to-do lists to online publications, sharing your notes is simple.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-solid fa-clock-rotate-left"></i>
              <h4 class="fs-5 mb-0">Go back in time</h4>
            </div>
            <p class="small text-muted">Easily access and restore previous versions of your notes, week after week.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-solid fa-shield-halved"></i>
              <h4 class="fs-5 mb-0">Safe and secure</h4>
            </div>
            <p class="small text-muted">Without the correct password, your notes are completely inaccessible to others.<p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12 features">
            <div class="d-flex align-items-center mb-2 gap">
              <i class="fa-solid fa-money-bills"></i>
              <h4 class="fs-5 mb-0">It's free</h4>
            </div>
            <p class="small text-muted">Seamless syncing, automatic backups, easy sharing – all at no cost.</p>
          </div>
        </div>
    </div>

    <footer>
        <p class="text-center">&copy; No copyright - Since 2025.</p>
        <div style="text-align:center; padding-bottom:20px;">
          <a href="https://n0tehub.me/about/aboutus.html">Click here to see more about us and how it works!</a>
        </div>
    </footer>
</body>
</html>