<!DOCTYPE html>
<html>
<head>
  <title>Inventory Management System - Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style_report.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Report</h1>
    </header>

<?php
// Connect to the database
include 'db_connect.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get the start date and end date from the form
	$start_date = clean_input($_POST["start_date"]);
	$end_date = clean_input($_POST["end_date"]);
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	// Get the data from the database based on the selected date range
	$sql = "SELECT * FROM purchase WHERE date BETWEEN '$start_date' AND '$end_date'";
	$result = mysqli_query($conn, $sql);

	// Generate the report
	echo "<h1>Report for $start_date to $end_date</h1>";
	echo "<table>";
	echo "<tr><th>Date</th><th>Product</th><th>Quantity</th><th>Price</th></tr>";
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
		echo "<td>" . $row["date"] . "</td>";
		echo "<td>" . $row["item_name"] . "</td>";
		echo "<td>" . $row["quantity"] . "</td>";
		echo "<td>" . $row["price"] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	
	// Close the database connection
	mysqli_close($conn);
}

// Function to sanitize user input
function clean_input($input) {
	global $conn;
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	$input = mysqli_real_escape_string($conn, $input);
	return $input;
}
?>
    <a href="export_pdf.php"><button>Export as PDF</button></a>
  </div>
</body>
</html>
