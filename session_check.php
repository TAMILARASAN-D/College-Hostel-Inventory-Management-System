<?php
session_start();
// Include the database connection file
//require_once 'db_connect.php';

// Function to log out the user
function logout() {
    // Destroy all session data
    session_destroy();

    // Redirect to the login page
    header("Location: index.php");
    exit();
}

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    // User is not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}

// Check for user activity
$inactive_timeout = 600; // 10 minutes in seconds

// Check if the last activity time is set
if (isset($_SESSION['last_activity'])) {
    $inactive_duration = time() - $_SESSION['last_activity'];

    // Check if the user has been inactive for too long
    if ($inactive_duration > $inactive_timeout) {
        logout();
    }
}

// Update the last activity time
$_SESSION['last_activity'] = time();

// Close the database connection
//$conn->close();
?>
