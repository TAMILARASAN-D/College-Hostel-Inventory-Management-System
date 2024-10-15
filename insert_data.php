<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hostel_inventory";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $date = $_POST["date"];
  $item_name = $_POST["item_name"];
  $quantity = $_POST["quantity"];

  // Insert the data into the purchase table
  $sql1 = "INSERT INTO purchase (date, item_name, quantity) VALUES ('$date', '$item_name', $quantity)";

  if (mysqli_query($conn, $sql1)) {
    echo "Data inserted into purchase table successfully<br>";
  } else {
    echo "Error: " . $sql1 . "<br>" . mysqli_error($conn);
  }

  // Insert the data into the stock table
  $sql2 = "INSERT INTO stock (item_name, quantity) VALUES ('$item_name', $quantity)";

  if (mysqli_query($conn, $sql2)) {
    echo "Data inserted into stock table successfully";
  } else {
    echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
  }
}

// Close the database connection
mysqli_close($conn);
?>
