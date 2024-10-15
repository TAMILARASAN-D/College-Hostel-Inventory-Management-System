<?php
session_start();
// Include the database connection file
require_once 'db_connect.php';

// Function to log out the user
function logout() {
    // Destroy all session data
    session_unset();
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
        header("Location: index.php");
        exit();
    }
}

// Update the last activity time
$_SESSION['last_activity'] = time();


//Logout functionality
if (isset($_POST['logout'])) {
    logout();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>TPGIT HIMS Admin user entry</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
  <script src="functions.js"></script>
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
            <h1>HOME PAGE</h1>
        </header>
        <a href="add_items_purchase.php"><button>Entry for Purchased Items</button></a><br>
        <a href="update_items_distribution.php"><button>Entry for Distributed Items</button></a><br>
        <a href="adminReportPage.php"><button>Generate Report</button></a><br>
    </div>
    <!-- Call the handleTabSwitch function to activate tab switch handling -->
 <script>
    handleTabSwitch();
  </script>
</body>
</html>


<!--
<!DOCTYPE html>
<html>
<head>
  <title>TPGIT HIMS Admin user entry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style1.css">

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
  <div class="item">
    <header>
      <h1>HOME PAGE</h1>
    </header>
      <a href="add_items_purchase.php"><button>Entry for Purchased Items</button></a><br>
      <a href="update_items_distribution.php"><button>Entry for Distributed Items</button></a><br>
      <a href="adminReportPage.php"><button>Generate Report</button></a><br>
    
  </div>
</body>
</html>
-->