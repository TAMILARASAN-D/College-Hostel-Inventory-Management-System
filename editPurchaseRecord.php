<?php
session_start();
$username = $_SESSION['username'];

// Connect to the database
include 'db_connect.php';

// Define response array
$response = array();

// Retrieve the updated data from the frontend
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];
$original_date = $_POST["original_date"];

// Check if the selected product is "Other" and handle custom product logic as before

// Check if the new date is greater than all other dates in the records and less than or equal to the current date
$sql_check_purchase_date = "SELECT MAX(date) AS max_date FROM purchase";
$result_check_date = mysqli_query($conn, $sql_check_purchase_date);
$row_check_date = mysqli_fetch_assoc($result_check_date);
$max_date_purchase = $row_check_date['max_date'];

$sql_check_stock_date = "SELECT MAX(date) AS max_date FROM stock";
$result_check_date = mysqli_query($conn, $sql_check_stock_date);
$row_check_date = mysqli_fetch_assoc($result_check_date);
$max_date_stock = $row_check_date['max_date'];

if (strtotime($date) >= strtotime($max_date_purchase) && strtotime($date) >= strtotime($max_date_stock) && strtotime($date) <= strtotime(date("Y-m-d"))) {

  // Update data in the purchase table based on the original date
  $sql_update_purchase = "UPDATE purchase SET date='$date', item_name='$product_name', quantity='$quantity', price='$price', stock='$quantity', editor='$username' WHERE date='$original_date' AND item_name='$product_name'";

  if (!mysqli_query($conn, $sql_update_purchase)) {
    // Send error response back to client
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    mysqli_close($conn);
    exit();
  }

  // Update or insert data in the stock table
  $sql_check_existing_stock = "SELECT * FROM stock WHERE item_name='$product_name' AND date='$date'";
  $result_check_existing_stock = mysqli_query($conn, $sql_check_existing_stock);

  if (mysqli_num_rows($result_check_existing_stock) > 0) {
    // Update existing stock record
    $sql_update_stock = "UPDATE stock SET purchase_quantity='$quantity', available_quantity='$quantity', editor='$username' WHERE item_name='$product_name' AND date='$date'";
  } else {
    // Insert new stock record
    $sql_update_stock = "INSERT INTO stock (date, item_name, purchase_quantity, available_quantity, editor) VALUES ('$date', '$product_name', '$quantity', '$quantity', '$username')";
  }

  if (!mysqli_query($conn, $sql_update_stock)) {
    // Send error response back to client
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    mysqli_close($conn);
    exit();
  }

  mysqli_close($conn);

  // Send success response back to client
  $response['success'] = true;
  $response['message'] = "Data updated successfully";
} else {
  $response['success'] = false;
  $response['message'] = "Invalid date. The date should be greater than all other dates and less than or equal to the current date.";
}
echo json_encode($response);
exit();
?>
