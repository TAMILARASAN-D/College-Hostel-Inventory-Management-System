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
      // Include database connection file
      require_once 'db_connect.php';

      // Retrieve data from database
      $sql = "SELECT * FROM purchase ORDER BY date DESC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Output table header
        echo "<table>";
        echo "<tr>";
        echo "<th>Date</th>";
        echo "<th>Product</th>";
        echo "<th>Quantity</th>";
        echo "<th>Price</th>";
        echo "<th>Total</th>";
        echo "</tr>";

        // Output table rows
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["date"] . "</td>";
          echo "<td>" . $row["item_name"] . "</td>";
          echo "<td>" . $row["quantity"] . "</td>";
          echo "<td>" . $row["price"] . "</td>";
          echo "<td>" . ($row["quantity"] * $row["price"]) . "</td>";
          echo "</tr>";
        }

        // Output table footer
        echo "<tr>";
        echo "<td colspan='4'>Total</td>";
        $sql = "SELECT SUM(quantity * price) as total FROM purchase";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo "<td>" . $row["total"] . "</td>";
        echo "</tr>";

        echo "</table>";
      } else {
        echo "No data found.";
      }

      // Close database connection
      $conn->close();
    ?>

    <br>
    <a href="export_pdf.php"><button>Export as PDF</button></a>
  </div>
</body>
</html>
