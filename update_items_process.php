<?php
session_start();
$username = $_SESSION['username'];
// Connect to the database
include 'db_connect.php';

// Insert data into distribution table
$hostel = $_POST["hostel"];
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];

// Check if item is available in stock and has enough quantity to distribute
$sql1 = "SELECT * FROM stock WHERE item_name='$product_name'";
$result1 = mysqli_query($conn, $sql1);
$row_data = mysqli_fetch_assoc($result1);
$stock_quantity = $row_data["quantity"];

if (mysqli_num_rows($result1) > 0 && $stock_quantity >= $quantity) {

  // Update stock table
  $new_quantity = $stock_quantity - $quantity;
  $sql2 = "UPDATE stock SET quantity='$new_quantity' WHERE item_name='$product_name'";
  mysqli_query($conn, $sql2);

  // Insert data into distribution table
  $sql3 = "INSERT INTO `distribution` (hostel, date, item_name, quantity, editor) VALUES ('$hostel', '$date', '$product_name', '$quantity', '$username')";

  if (mysqli_query($conn, $sql3)) {
    $response['success'] = true;
    $response['message'] = "Data inserted successfully";
  } else {
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
  }

} else {
  $response['success'] = false;
  $response['message'] = $product_name . " is not available in stock or does not have enough quantity";
}

mysqli_close($conn);

// Send response back to client as JSON
echo json_encode($response);
?>
