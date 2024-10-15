<?php
// Connect to the database
include 'db_connect.php';

// Insert data into distribution table
$hostel = $_POST["hostel"];
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];

$sql = "INSERT INTO `distribution` (hostel, date, item_name, quantity) VALUES ('$hostel', '$date', '$product_name', '$quantity')";

if (mysqli_query($conn, $sql)) {
  $response = "success";
  echo json_encode($response);
} else {
  echo json_encode("Error");
}

// Update data in stock table
$sql = "SELECT * FROM stock WHERE item_name='$product_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // Update existing record
  $row = mysqli_fetch_assoc($result);
  $new_quantity = $row["quantity"] - $quantity;

  $sql = "UPDATE stock SET quantity='$new_quantity' WHERE item_name='$product_name'";
} else {
  // Insert new record
  echo "Stock not available";
}

if (mysqli_query($conn, $sql)) {
  header("Location: update_items.php");
  exit();
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
