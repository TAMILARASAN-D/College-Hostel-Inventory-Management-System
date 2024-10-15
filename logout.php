<?php
session_start();

// Function to log out the user
function logout() {
    // Destroy all session data
    session_unset();
    session_destroy();
    header("Location: index.php");
  exit();
}

logout();
?>
