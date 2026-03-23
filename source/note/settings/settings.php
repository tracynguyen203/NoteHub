<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | NoteHub</title>

    <link rel="icon" type="image/x-icon" href="https://n0tehub.me/favicon.ico">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome  -->
    <script src="https://kit.fontawesome.com/d717a59f45.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="settings.css">    

    <!-- Javascript -->
    <script src="settings.js"></script>
</head>
<body>
    <div class="full-width-container">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-1 left">
                <aside class="management">
                    <div class="menu-bar">
                        <div class="container mt-2 mb-2 p-0">
                            <div class="d-flex align-items-center bar">
                                <a class="navbar-brand" style="font-size: 125%;" href="https://n0tehub.me/index.php">
                                    <img src="https://n0tehub.me/images/noteHub.png" class="logo"> NoteHub
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="list">
                        <div class="full-width-container">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs flex-column" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="https://n0tehub.me/note/noteManagement.php">
                                        <i class="fa-regular fa-note-sticky"></i>
                                        <h6 class="d-inline">All Notes</h6>  
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#file">                                        
                                        <i class="fa-regular fa-folder-open"></i>
                                        <h6 class="d-inline">File Management</h6>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#settings">
                                        <i class="fa-solid fa-gear"></i>
                                        <h6 class="d-inline">Settings</h6>      
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#profile">
                                        <i class="fa-regular fa-circle-user"></i>
                                        <h6 class="d-inline">Profile</h6>      
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center log-out" href="https://n0tehub.me/logout/logout.php">
                                        <i class="fa-solid fa-door-open"></i>
                                        <h6 class="d-inline">Log out</h6>  
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>         
            </div>

            <div class="col-lg-10 col-md-9 col-sm-11 right">
                <main class="note">
                    <div class="note-bar">
                        <div class="container mt-3 mb-3">
                            <div class="d-flex align-items-center">
                                <button class="expand-collapse-button" onclick="expand_and_collapse(this)">
                                    <i class="fa-solid fa-up-right-and-down-left-from-center expand-icon"></i>
                                    <i class="fa-solid fa-down-left-and-up-right-to-center collapse-icon" style="display: none;"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="note-items">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="file" class="container tab-pane fade"><br>
                                <h3>Welcome to NoteHub!!!</h3>
                                <p>Nice to meet you!<br></p>
                                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                            <div id="settings" class="container tab-pane fade"><br>
                                <h3 style="text-align: center;">Settings</h3>
                                <div class="change-password">
                                    <h5 class="mt-3">Change password:</h5>
                                    <form class="change-password-form">
                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <span class="input-group-text">Current</span>
                                            <input type="password" class="form-control" placeholder="Your current password">
                                        </div>

                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <span class="input-group-text">New</span>
                                            <input type="password" class="form-control" placeholder="Your new password">
                                        </div>

                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <span class="input-group-text">Confirm</span>
                                            <input type="password" class="form-control" placeholder="Confirm your new password">
                                        </div>

                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <button type="submit" class="btn btn-primary">Change password</button>
                                        </div>
                                    </form>                 
                                </div>

                                <h5 class="mt-3">Change font and color:</h5>
                                <form id="font-color-form">
                                    <div class="input-group w-75 mt-3" style="margin: auto;">
                                        <span class="input-group-text">Font</span>
                                        <select class="form-select" name="font" id="font-select" required>
                                            <option value="Arial">Arial</option>
                                            <option value="Times New Roman">Times New Roman</option>
                                            <option value="Courier New">Courier New</option>
                                            <option value="Helvetica">Helvetica</option>
                                            <option value="Tahoma">Tahoma</option>
                                            <option value="Verdana">Verdana</option>
                                        </select>
                                    </div>
                                    <div class="input-group w-75 mt-3" style="margin: auto;">
                                        <span class="input-group-text">Color</span>
                                        <select class="form-select" name="color" id="color-select" required>
                                            <option value="#FAF1E6">Default</option>
                                            <option value="#E6D0DE">Dusty rose</option>
                                            <option value="#FFF9F0">Snow</option>
                                            <option value="#FFFAF0">Floralwhite</option>
                                            <option value="#F5F5DC">Beige</option>
                                            <option value="#B2DFDB">Muted teal</option>
                                        </select>
                                    </div>
                                    <div class="input-group w-75 mt-3" style="margin: auto;">
                                        <button type="submit" class="btn btn-primary">Change Font & Color</button>
                                    </div>
                                </form>            
                            </div>

                            <div id="profile" class="container tab-pane fade"><br>
                                <h3 style="text-align: center;">Profile and Avatar</h3>
                                <?php
                                $userID = $_SESSION['user_id'];
                                $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
                                include("../database/db_config_notes.php");
                                $stmt = $conn->prepare("SELECT avatar_url FROM avatars WHERE user_id=?");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $stmt->bind_result($avatar_url);
                                $stmt->fetch();
                                $stmt->close();
                                if (!$avatar_url) $avatar_url = "https://n0tehub.me/user_preference/avatar/1.png";
                                ?>
                                <div class="display-avatar-and-name text-center mb-3 mt-3">
                                    <img id="current-avatar" src="<?= htmlspecialchars($avatar_url) ?>" alt="Avatar" class="rounded-circle mb-2" style="width:150px;height:150px;object-fit:cover;">
                                    <div class="username mb-3" style="font-size: 140%; font-weight: bold;"><?= htmlspecialchars($username) ?></div>
                                </div>

                                <div class="change-name">
                                    <h5 class="mt-3">Change Name:</h5>
                                    <form class="change-name-form">
                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <span class="input-group-text">Name</span>
                                            <input type="text" class="form-control" placeholder="Your new name">
                                        </div>

                                        <div class="input-group w-75 mt-3" style="margin: auto;">
                                            <button type="submit" class="btn btn-primary">Change name</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Change Avatar Section -->
                                <div class="change-avatar">
                                    <h5 class="mt-3 mb-3">Change Avatar:</h5>
                                    <form id="change-avatar-form">
                                        <div class="row box-avatar justify-content-center mb-3 w-75 m-auto">
                                            <?php
                                            for ($i = 1; $i <= 20; $i++): 
                                                $url = "https://n0tehub.me/user_preference/avatar/$i.png";
                                            ?>
                                                <div class="col-2 mb-4 text-center p-0">
                                                    <img src="<?= $url ?>"
                                                        class="avatar-choice"
                                                        data-avatar-url="<?= $url ?>"
                                                        style="width: 90px; height: 90px; cursor: pointer; border: 2px solid #eee; border-radius: 6px;">
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" id="selected-avatar" name="avatar" value="">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-50 ">Change Avatar</button>
                                        </div>
                                    </form>
                                </div>
                                                       
                            </div>
                        </div>                        
                    </div>
                </main>            
            </div>            
        </div>
    </div>
    
</body>
</html>