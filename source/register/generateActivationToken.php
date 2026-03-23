<?php
function generateActivationToken($email) {
    // Generate a unique token
    $token = bin2hex(random_bytes(16));

    // Save the token to the database
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

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


    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO activation_tokens (email, token, expires_at) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("SQL error (prepare failed): " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("sss", $email, $token, $expiresAt);
    if (!$stmt->execute()) {
        die("SQL error (execute failed): " . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Construct the activation link
    $activationLink = "https://n0tehub.me/register/activate.php?token=$token";
    return $activationLink;
}
?>