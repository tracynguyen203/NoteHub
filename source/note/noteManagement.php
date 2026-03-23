<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//set the time zone
date_default_timezone_set('UTC');

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Set username for display
$username = htmlspecialchars($_SESSION['username']);

// Include the database configuration file
include("../note/database/db_config_notes.php");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure database uses the same timezone like in the code
$conn->query("SET time_zone = '+00:00'");

// Check if the user is verified
$userID = $_SESSION['user_id'];
$isVerified = 0; // Default to not verified
$stmt = $conn->prepare("SELECT is_verified FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($isVerified);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Query preparation failed: " . $conn->error);
}

// Fetch user notes
$userID = $_SESSION['user_id'];
$sql = "SELECT id, title, content, created_at, updated_at, pin_note, is_password_protected, font_note, note_color FROM notes WHERE user_id = ? ORDER BY pin_note DESC, created_at DESC";
$stmt = $conn->prepare($sql);

$notes = []; // Initialize as an empty array

if ($stmt) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $notes[] = $row; // Add each note to the array
    }

    $stmt->close();
} else {
    die("Query preparation failed: " . $conn->error);
}

// Handle Password-Protected Note Access
if (isset($_POST['action']) && $_POST['action'] == 'fetchNote') {
    $noteID = $_POST['note_id'];
    $password = $_POST['password'] ?? null;
    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id, title, content, is_password_protected, password FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $noteID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Note not found or you do not have access."]);
        exit();
    }

    $note = $result->fetch_assoc();

    // Verify password if the note is password-protected
    if ($note['is_password_protected']) {
        if (!$password) {
            echo json_encode(["success" => false, "error" => "Password is required to access this note."]);
            exit();
        }

        if (!password_verify($password, $note['password'])) {
            echo json_encode(["success" => false, "error" => "Incorrect password."]);
            exit();
        }
    }

    // Return the note content
    unset($note['password']); // Do not expose the password in the response
    echo json_encode(["success" => true, "note" => $note]);
    exit();
}

//Delete note from database
if(isset($_POST['deleteTarget'])) {
    $target = $_POST['deleteOne'];
    
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->bind_param("i", $target); 

    if($stmt->execute()) {
        $_SESSION['status'] = "Note is deleted successfully"; 
    } else {
        $_SESSION['status'] = "Cannot delete the note. Please try again! Error: " . $stmt->errno . " - " . $stmt->error;
    }

    header('Location: noteManagement.php');
    exit();
}

// Fetch avatar URL for the logged-in user
$avatar_url = null;
$stmt = $conn->prepare("SELECT avatar_url FROM avatars WHERE user_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($avatar_url);
$stmt->fetch();
$stmt->close();

// Set a default avatar if none is set
if (!$avatar_url) {
    $avatar_url = "https://n0tehub.me/user_preference/avatar/1.png";
}

$conn->close();
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

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d717a59f45.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="noteManagement.css">
    

    <!-- Javascript -->
    <script src="noteManagement.js"></script>
    <script src="setPassword.js"></script>
</head>
<body>

    <div class="container">
     <?php if (!$isVerified): ?>
        <div class="alert alert-warning mt-3" role="alert">
                Your account is not verified. Please check your email to verify your account.
            </div>
        <?php endif; ?>

    <div class="full-width-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-5 col-sm-12 left">
                <aside class="management">
                    <div class="menu-bar">
                        <div class="container mt-2 mb-2 p-0">
                            <div class="d-flex align-items-center bar">
                                <button type="button" class="btn text-dark bar-btn" data-bs-toggle="collapse" data-bs-target="#menu"><i class="fa-solid fa-bars"></i></button>
                                <a class="navbar-brand" style="font-size: 125%;" href="https://n0tehub.me/index.php">
                                    <img src="https://n0tehub.me/images/noteHub.png" class="logo"> NoteHub
                                </a>
                                <div class="ms-auto">
                                    <button class="btn createBtn"><i class="fa-regular fa-pen-to-square"></i></button>
                                    <button class="grid-list-view" onclick="grid_list_view()">
                                        <i class="fa-solid fa-table-cells-large grid-icon"></i>
                                        <i class="fa-solid fa-table-list list-icon" style="display: none;"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="username d-flex align-items-center justify-content-center pt-2 pb-2">
                                <img src="<?= htmlspecialchars($avatar_url); ?>" alt="Avatar" class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;">
                                <span>Hello <?= $username; ?></span>
                            </div>

                            <div id="menu" class="collapse menu-options all-notes" style="background-color: #eed2b1;">
                                <i class="fa-regular fa-note-sticky"></i>
                                <h6 class="d-inline">All Notes</h6>
                            </div>

                            <a href="https://n0tehub.me/note/settings/settings.php#file">
                                <div id="menu" class="collapse menu-options">
                                    <i class="fa-regular fa-folder-open"></i>
                                    <h6 class="d-inline">File Management</h6>
                                </div>
                            </a>

                            <a href="https://n0tehub.me/note/settings/settings.php#settings">
                                <div id="menu" class="collapse menu-options">
                                    <i class="fa-solid fa-gear"></i>
                                    <h6 class="d-inline">Settings</h6>
                                </div>
                            </a>

                            <a href="https://n0tehub.me/note/settings/settings.php#profile">
                                <div id="menu" class="collapse menu-options">
                                    <i class="fa-regular fa-circle-user"></i>
                                    <h6 class="d-inline">Profile</h6>
                                </div>
                            </a>

                            <a href="../logout/logout.php">
                                <div id="menu" class="collapse log-out">
                                    <i class="fa-solid fa-door-open"></i>
                                    <h6 class="d-inline">Log out</h6>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Search note -->
                    <div class="search-content">
                        <form>
                            <div class="input-group m-auto mt-2 mb-2">
                                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <div id="searchBar"> 
                                    <input type="text" class="form-control" id="liveSearch" placeholder="Search.." autocomplete="off">
                                </div>
                            </div> 
                        </form>
                    </div>
                    <!-- Search result  -->
                    <div id="searchResult" class="list-group"> </div>

                    <!-- Main Content -->
                    <div class="list" id="list-view" style="display: none;">
                        <div class="full-width-container">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs flex-column" role="tablist">
                                <?php foreach ($notes as $n): ?>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center<?= $n['pin_note'] ? ' active-top' : '' ?>"
                                        data-bs-toggle="tab"
                                        href="#note-<?= htmlspecialchars($n['id']); ?>"
                                        data-id="<?= htmlspecialchars($n['id']); ?>"
                                        data-password-protected="<?= $n['is_password_protected'] ? 'true' : 'false'; ?>">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <button type="button"
                                                    onclick="pin_and_unpin(this, event)"
                                                    class="btn btn-sm btn-secondary pin-btn me-2"
                                                    data-id="<?= $n['id'] ?>"
                                                    data-pinned="<?= $n['pin_note'] ?>">
                                                    <i class="fa-solid fa-thumbtack" style="display:<?= $n['pin_note'] ? 'inline-block' : 'none' ?>"></i>
                                                    <i class="fa-solid fa-thumbtack-slash" style="display:<?= $n['pin_note'] ? 'none' : 'inline-block' ?>"></i>
                                                </button>
                                                <span><?= htmlspecialchars($n['title']); ?></span>
                                            </div>
                                            <div class="ms-auto">
                                                <button class="btn btn-sm rename-btn" style="background-color:#608BC1; color: white;" data-id="<?= htmlspecialchars($n['id']); ?>" data-title="<?= htmlspecialchars($n['title'])?>" >
                                                    <i class="fa-solid fa-marker"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning lock-btn" data-id="<?= $n['id'] ?>">
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $n['id'] ?>" data-title="<?= htmlspecialchars($n['title']) ?>">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="col-lg-9 col-md-7 right">
                <main class="note">
                    <div class="grid" id="grid-view" style="display: block;">                    
                        <!-- Nav tabs -->
                        <ul id="grid-note-items" class="nav nav-tabs flex-row" role="tablist" style="flex-wrap: wrap;">
                            <?php foreach ($notes as $n): ?>
                                <li class="grid-item nav-item" style="background: #fff;">
                                    <div class="grid-item-header">
                                        <div class="d-flex align-items-center">
                                            <button type="button"
                                                onclick="pin_and_unpin(this, event)"
                                                class="btn btn-sm btn-secondary pin-btn me-2"
                                                data-id="<?= $n['id'] ?>"
                                                data-pinned="<?= $n['pin_note'] ?>">
                                                <i class="fa-solid fa-thumbtack" style="display:<?= $n['pin_note'] ? 'inline-block' : 'none' ?>"></i>
                                                <i class="fa-solid fa-thumbtack-slash" style="display:<?= $n['pin_note'] ? 'none' : 'inline-block' ?>"></i>
                                            </button>
                                            <span><?= htmlspecialchars($n['title']); ?></span>
                                        </div>
                                        <div class="ms-auto">
                                            <button class="btn btn-sm rename-btn" style="background-color:#608BC1; color: white;" data-id="<?= htmlspecialchars($n['id']); ?>" data-title="<?= htmlspecialchars($n['title'])?>" >
                                                <i class="fa-solid fa-marker"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning lock-btn" data-id="<?= $n['id'] ?>">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $n['id'] ?>" data-title="<?= htmlspecialchars($n['title']) ?>">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid-item-content noteArea" contenteditable="true"
                                        style="font-family:<?= htmlspecialchars($n['font_note'] ?? 'Arial') ?>;
                                                background:<?= htmlspecialchars($n['note_color'] ?? '#FAF1E6') ?>;">
                                        <?php if ($n['is_password_protected']): ?>
                                            <p>This note is password-protected.</p>
                                        <?php else: ?>
                                            <p><?= $n['content'] ?? ''; ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div id="edit-<?= htmlspecialchars($n['id']); ?>" class="edit">
                                        <button class="btn" id="italicBtn-<?= htmlspecialchars($n['id']); ?>" onclick="changeToItalic(this)"><i class="fa-solid fa-italic d-inline"></i></button>
                                        <button class="btn" id="boldBtn-<?= htmlspecialchars($n['id']); ?>" onclick="changeToBold(this)"><i class="fa-solid fa-bold d-inline"></i></button>
                                        <button class="btn" id="underlineBtn-<?= htmlspecialchars($n['id']); ?>" onclick="changeToUnderline(this)"><i class="fa-solid fa-underline"></i></button>
                                        <button class="btn" id="checkboxBtn-<?= htmlspecialchars($n['id']); ?>" onclick="changeToCheckbox(this)"><i class="fa-solid fa-list-check d-inline"></i></button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="note-items" id="note-items" style="display: none;">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="note-bar">
                                <div class="container mt-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <button class="expand-collapse-button" onclick="expand_and_collapse(this)">
                                            <i class="fa-solid fa-up-right-and-down-left-from-center expand-icon"></i>
                                            <i class="fa-solid fa-down-left-and-up-right-to-center collapse-icon" style="display: none;"></i>
                                        </button>
                                        <div class="ms-auto">
                                                <button type="button" class="btn text-dark bar-btn" data-bs-toggle="collapse" data-bs-target="#edit"><i class="fa-solid fa-angle-down" onclick="changeArrowIcon(this)"></i></button>
                                        </div>
                                        <div id="edit" class="collapse">
                                            <button class="btn" id="italicBtn" onclick="changeToItalic()"><i class="fa-solid fa-italic d-inline"></i></button>
                                            <button class="btn" id="boldBtn" onclick="changeToBold()"><i class="fa-solid fa-bold d-inline"></i></button>
                                            <button class="btn" id="underlineBtn" onclick="changeToUnderline()"><i class="fa-solid fa-underline"></i></button>
                                            <button class="btn" id="checkboxBtn" onclick="changeToCheckbox()"><i class="fa-solid fa-list-check d-inline"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php if (!empty($notes)): ?>
                                    <?php foreach ($notes as $n): ?>
                                        <div class="note-status-holder"></div>
                                        <div id="note-<?= htmlspecialchars($n['id']); ?>" class="container tab-pane fade" contenteditable="true" data-id="<?= htmlspecialchars($n['id']);?>">                                            <br>
                                            <div class="noteArea"
                                                style="font-family:<?= htmlspecialchars($n['font_note'] ?? 'Arial') ?>;
                                                        background:<?= htmlspecialchars($n['note_color'] ?? '#FAF1E6') ?>;">
                                                <?php if ($n['is_password_protected']): ?>
                                                <p>This note is password-protected.</p>
                                                <?php else: ?>
                                                <p><?= htmlspecialchars($n['content'] ?? ''); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="noteManagement.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete a note</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete? <strong id="deleteTitle"></strong>?</p>
                                <input type="hidden" name="deleteOne" id="deleteOne">
                                <input type="hidden" name="deleteTarget" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Password Modal -->
    <div id="passwordSetModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="passwordForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Set Password for Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <input type="hidden" name="noteId" id="modalNoteId">
                        <div class="form-group">
                            <label for="notePassword">Enter Password</label>
                            <input type="password" class="form-control" id="notePassword" name="notePassword" required>
                        </div>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save Password</button>
                        </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Password Input Modal -->
<div id="passwordEnterModal" class="modal fade" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Enter Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>This note is password-protected. Please enter the password to access it.</p>
                <input type="password" id="notePasswordInput" class="form-control" placeholder="Enter password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="submitPasswordBtn" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

    <!-- Rename Confirm Modal -->
    <div id="renameModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="renameForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Rename <strong>Note</strong></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="noteID" id="renameNoteID">
                        
                        <div class="mb-3">
                            <label for="newTitle" class="form-label">New Title</label>
                            <input type="text" class="form-control" name="newTitle" id="newTitle" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Rename</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="createNote.js"></script>
    <script>
        //Delete
        $(document).ready(function () {
            //pin note
            $(document).on('click', '.pin-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = $(this);
                const noteId = btn.data("id");
                const currentlyPinned = btn.data("pinned") == 1 ? 1 : 0;
                const newPinState = currentlyPinned ? 0 : 1;

                console.log("Pin button clicked. Note ID:", noteId, "Current:", currentlyPinned, "New:", newPinState);

                $.ajax({
                    url: "update_pin.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        noteId: noteId,
                        pinState: newPinState
                    },
                    success: function(response) {
                        console.log("Pin AJAX response:", response);
                        if (response.success) {
                            // Update the data-pinned attribute for all pin buttons for this note
                            $(`.pin-btn[data-id="${noteId}"]`).data("pinned", newPinState);
                            // Optionally update icons here
                            location.reload(); // Reload to reflect new order
                        } else {
                            alert('Failed to update pin state: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error, xhr.responseText);
                        alert('Failed to update pin state.');
                    }
                });
            });
            //pin note

            $('#deleteModal').on('hidden.bs.modal', function () {
                // Remove the modal backdrop manually if it persists
                $('.modal-backdrop').remove();
                // Reset the body class
                $('body').removeClass('modal-open');
            });
            
            // show delete confirm
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const id = $(this).data("id");
                const title = $(this).data("title");

                $("#deleteOne").val(id);
                $("#deleteTitle").text(title);

                const modal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();

            });

            //Auto Save    
            var timeOutID;
            const updatedNote = $(this).find("[contenteditable='true']");

            $(".tab-pane[contenteditable='true']").on("input", function () {
                console.log('Content change');

                const updatedNote = $(this);
                clearTimeout(timeOutID);
                timeOutID = setTimeout(function () {
                    savetoDB(updatedNote);
                }, 1000);
            })

            function savetoDB(updatedNote) {
                console.log("Saving to DB");

                const id = updatedNote.data("id");
                const content = updatedNote.html();

                console.log("Auto-saving note ID: ", id);
                console.log("content:", content);
                
                $.ajax({
                    url: "autoSave.php",
                    type: "POST",
                    data: {
                        id: id, 
                        content: content
                    }, 
                    beforeSend: function(xhr) {
                        updatedNote.prev('.note-status-holder').html('Saving...').show();
                    }, 
                    success: function (response) {
                        if (response.success === true) {
                            let date = new Date();
                            let statusHolder = updatedNote.prev('.note-status-holder');

                            statusHolder.html(`Note saved at ${date.toLocaleTimeString()}`).show();
                            
                            if (statusHolder.data('hideTimeout')) {
                                clearTimeout(statusHolder.data('hideTimeout'));
                            }

                            let statusOutID = setTimeout(() => {
                                statusHolder.fadeOut('slow');
                            }, 1500);

                            statusHolder.data('hiderTimeout', statusOutID);

                        } else {
                            updatedNote.prev('.note-status-holder').html('Failed to save.').show();
                            console.error("Server responded:", response);
                        }
                    },
                    error: function (xhr, status, error) {
                        updatedNote.find('.note-status-holder').html('Failed to save.').show();
                        console.error("AJAX error:", error);
                    }
                })
            }

            //Live search function
            $("#liveSearch").keyup(function() {
                var input = $(this).val();

                console.log("Find"); 

                if(input != "") {
                    $.ajax({
                        url: "liveSearch.php", 
                        method: "POST", 
                        data: {
                            input: input
                        },

                        success: function(data) {
                            if(data.length > 0) {
                                let html = ''; 

                                const previewLength = 50;
                                data.forEach(note => {
                                    
                                    // Clean html tag
                                    let cleanContent = note.content.replace(/(<([^>]+)>)/gi, "").trim();
                                    
                                    // Take some words from the note with length > 50
                                    let preview = cleanContent.length > previewLength 
                                    ? "Content: "+ cleanContent.slice(0, previewLength)
                                    : "Content: "+ cleanContent;

                                    html += `<a href="#note-${note.id}" class="list-group-item list-group-item-warning" data-bs-toggle="tab" data-id="${note.id}">
                                            <strong style="border: none;"> ${note.title} </strong>
                                            <div style="font-size: 0.9em; color: #555; border: none">${preview}</div> </a>`                                
                                })

                                $("#searchResult").html(html).show(); 

                            } else {
                                $("#searchResult").html("<div class='list-group-item'>No notes found</div>").show(); 
                            }
                        }
                    })
                } else {
                    $("#searchResult").hide(); 
                }
            })

            //Hide the search result after clicking on it and make the tab active
            $(document).on("click", "#searchResult a", function (e) {
                e.preventDefault();
                $("#searchResult").hide();

                var noteID = $(this).data("id"); 
                const tabTrigger = document.querySelector(`a.nav-link[href="#note-${noteID}"]`);

                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            });

            //Open rename modal
            $(document).on('click', '.rename-btn', function(){
                const noteID = $(this).data("id"); 
                const title = $(this).data("title");

                $('#renameNoteID').val(noteID);
                $('#newTitle').val(title);

                //Rename function
                let renameModal = new bootstrap.Modal(document.getElementById('renameModal'));
                renameModal.show();

            })

            $('#renameForm').submit(function(e) {
                e.preventDefault(); 

                const newTitleValue = $('#newTitle').val();
                console.log("New Title Value:", newTitleValue); // Add this line

                $.ajax({
                    url: "renameNote.php", 
                    method: "POST", 
                    dataType: "json",
                    data: {
                        id:$('#renameNoteID').val(), 
                        title: newTitleValue
                    },

                    success: function(result) {
                        if(result.success) {
                            // Update the data-title attribute of the rename button
                            $(`.rename-btn[data-id="${result.id}"]`).data('title', result.newTitle);

                            // Update the text content of the corresponding <span> tag
                            $(`.rename-btn[data-id="${result.id}"]`)
                                .closest('.nav-item')
                                .find('a > div > span')
                                .text(result.newTitle);
                            
                            alert("Renamed successfully!");
                            renameModal.hide();
                        } else {
                            alert("Rename failed: " + result.message);
                        }
                    }, 
                    error: function(xhr, status, error) {
                        alert("AJAX error: " + error);
                    }
                })
            })
        })

$(document).ready(function () {
    // Show Password Set Modal
    $(document).on("click", ".lock-btn", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const noteID = $(this).data("id");
        $("#modalNoteId").val(noteID);

        const passwordSetModal = new bootstrap.Modal(document.getElementById("passwordSetModal"), {
            backdrop: "static",
            keyboard: false,
        });
        passwordSetModal.show();
    });

    // Handle Password Setting Form Submission
    $("#passwordForm").submit(function (e) {
        e.preventDefault();

        const noteID = $("#modalNoteId").val();
        const password = $("#notePassword").val();

        if (!password) {
            alert("Password cannot be empty.");
            return;
        }

        $.ajax({
            url: "setPassword.php",
            method: "POST",
            data: {
                noteId: noteID,
                password: password,
            },
            success: function (response) {
                if (response.success) {
                    alert("Password set successfully!");
                    const passwordSetModal = bootstrap.Modal.getInstance(document.getElementById("passwordSetModal"));
                    passwordSetModal.hide();
                } else {
                    alert("Error: " + response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", error);
            },
        });
    });

        // Handle .nav-link click for password-protected notes
    $(document).on('click', '.nav-link', function (e) {
        // Check if the clicked element is specifically the delete button
        if ($(e.target).hasClass('delete-btn') || $(e.target).closest('.delete-btn').length ||
            $(e.target).hasClass('lock-btn') || $(e.target).closest('.lock-btn').length) {
            return;
        }

        const isPasswordProtected = $(this).data("password-protected");

        // Open password modal only if the note is password-protected
        if (isPasswordProtected) {
            e.preventDefault();
            e.stopPropagation();

            $("#notePasswordInput").val(""); // Clear the password input

            const passwordEnterModal = new bootstrap.Modal(document.getElementById("passwordEnterModal"), {
                backdrop: "static",
                keyboard: false,
            });
            passwordEnterModal.show();

            // Handle Password Submission
            $("#submitPasswordBtn").off("click").on("click", function () {
                const password = $("#notePasswordInput").val();
                const noteID = $(".nav-link.active").data("id");

                if (!password) {
                    alert("Please enter a password.");
                    return;
                }

                $.ajax({
                    url: "../note/database/loadNote.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        note_id: noteID,
                        password: password,
                    },
                    success: function (response) {
                        if (response.success) {
                            const noteContent = response.note.content;
                            const noteArea = $(`#note-${response.note.id} .noteArea`);

                            if (noteArea.length) {
                                noteArea.empty();
                                noteArea.html(`<p>${noteContent}</p>`);
                            } else {
                                console.error("Error: .noteArea not found for note ID:", response.note.id);
                            }

                            const passwordEnterModalInstance = bootstrap.Modal.getInstance(document.getElementById("passwordEnterModal"));
                            passwordEnterModalInstance.hide();
                        } else {
                            alert(response.error || "An error occurred while fetching the note.");
                        }
                    },
                    error: function (xhr, status, error) {
                        const serverError = xhr.responseJSON?.error || "Failed to fetch the note.";
                        alert(serverError);
                        console.error("AJAX error:", error, xhr.responseText);
                    },
                });
            });
        }
    });

    // Reset modal state when closed
    $('#deleteModal, #passwordEnterModal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove(); // Remove any lingering backdrops
        $('body').removeClass('modal-open'); // Reset body class
    });
});
    </script>
</body>
</html>