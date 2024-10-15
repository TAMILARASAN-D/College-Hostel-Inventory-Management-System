<!DOCTYPE html>
<html>
<head>
	<title>Report Generation</title>
  <link rel="stylesheet" href="style1.css">
  <script src="functions.js"></script>
</head>
<body class="item">
	<h1>Report Generation</h1>
	<form action="purchase_report.php" method="post">
		<label for="start_date">Start Date:</label>
		<input type="date" name="start_date" id="start_date">
		<label for="end_date">End Date:</label>
		<input type="date" name="end_date" id="end_date">
    <button type="submit">Generate Report</button>
	</form>
</body>
</html>
