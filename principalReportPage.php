<?php
require_once 'session_check.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>TPGIT HIMS REPORT</title>
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

<button onclick="goHosteluserHome()" id="back">Back</button><br>

  <div class="item">
    <header>
      <h1>REPORT GENERATION</h1>
    </header>
    
      <a href="purchaseReport_for_principal.php"><button>Generate Purchase Report</button></a><br>
      <a href="distributionReport_for_principal.php"><button>Generate Distribution Report</button></a><br>
      <a href="stockReport.php"><button>Generate Stock Report</button></a><br>
      <a href="stockShortageReport.php"><button>Generate Stock Shortage Report</button></a><br>
    
  </div>
</body>
</html>
