<?php
session_start();
// Include the database connection file
require_once 'db_connect.php';

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


// Logout functionality
if (isset($_POST['logout'])) {
    logout();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>TPGIT HIMS REPORT</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="header">
        <div class="logo-left">
            <img src="tn1.png" alt="Left Logo">
        </div>
        <h1>THANTHAI PERIYAR GOVERNMENT INSTITUTE OF TECHNOLOGY <br>HOSTEL INVENTORY MANAGEMENT SYSTEM</h1>
        <div class="logo-right">
            <img src="01_tpgit logo_Final.png" alt="Right Logo">
        </div>
    </div>
    <form id="logout-form" method="post" action="">
        <button type="submit" name="logout" id="back">Logout</button>
    </form>
  <div class="item">
    <header>
      <h1>TPGIT HIMS REPORT GENERATION</h1>
    </header>
    
      <a href="purchaseReport.php"><button>Generate Purchase Report</button></a><br>
      <a href="distributionReport.php"><button>Generate Distribution Report</button></a><br>
      <a href="stockReport.php"><button>Generate Stock Report</button></a><br>
      <a href="stockShortageReport.php"><button>Generate Stock Shortage Report</button></a><br>
      <a href="stockHistoryReport.php"><button>Generate Stock History Report</button></a><br>
    
  </div>
</body>
</html>
