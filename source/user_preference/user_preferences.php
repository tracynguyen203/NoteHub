<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();

    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Unauthorized']));
    }

    // prevent deleted accounts
    require __DIR__ . '/avatar_db/connect_database.php';
    $conn = connect();
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
        mysqli_close($conn);
        header("Location: register.php?error=account_not_found");
        exit();
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | NoteHub</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="user_preference.css">
</head>
<body>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="mb-3 mt-3 text-center" style="font-size: 200%; font-weight: bold;">Update Profile</h1>
            <form id="update-profile-form">
                <div>
                    <label for="avatar" class="block text-gray-700 text-sm font-bold mt-4 mb-4">Choose Avatar:</label>
                    <div class="box-avatar flex flex-wrap gap-2">
                        <input type="hidden" id="selected-avatar" name="avatar" value="">
                    </div>  
                </div>
                <div class="client-check reminder">
                    <label for="email-verified" class="inline-flex items-center">
                        <span class="ml-2 text-gray-700 text-sm">Don't forget to verified your email</span>
                    </label>
                </div>
                <div class="client-check">
                    <label for="demo-note" class="inline-flex items-center">
                        <input type="checkbox" id="demo-note" name="demo-note" class="form-checkbox text-green-600 rounded">
                        <span class="ml-2 text-gray-700 text-sm">This website was made by 3 students from Ton Duc Thang University, so please be a kind client!</span>
                    </label>
                </div>
                <div class="client-check">
                    <label for="confirmation" class="inline-flex items-center">
                        <input type="checkbox" id="confirmation" name="confirmation" required class="form-checkbox text-green-600 rounded">
                        <span class="ml-2 text-gray-700 text-sm">Have you double-checked your avatar yet?</span>
                    </label>
                </div>
                <button id="change_profile" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Change Profile
                </button>
            </form>
        </div>
    </div>

    <div id="message-box" class="hidden"></div>

    <div class="current-avatar mb-4 text-center">
        <?php if (!empty($current_avatar['avatar_url'])): ?>
            <img src="<?= htmlspecialchars($current_avatar['avatar_url']) ?>" 
                 alt="Current Avatar" 
                 class="w-24 h-24 rounded-full mx-auto border-2 border-red-500">
        <?php endif; ?>
    </div>

    <!-- Javascript -->
    <script src="user_preference.js"></script>
</body>
</html>
