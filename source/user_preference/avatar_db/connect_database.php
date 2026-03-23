<?php
function connect() {
    // database connection
    $servername = "localhost";
    $username = "ijdtkfvr_nam";
    $password = "";
    $dbname = "ijdtkfvr_user_accounts";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
?>