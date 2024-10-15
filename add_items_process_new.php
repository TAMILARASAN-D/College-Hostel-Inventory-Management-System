<?php
session_start();
$username = $_SESSION['username'];
// Connect to the database
include 'db_connect.php';

// Define response array
$response = array();

// Insert data into purchase table
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];
//$custom_product_name = $_POST["custom_product_name"];
$custom_product_name = isset($_POST["custom_product_name"]) ? $_POST["custom_product_name"] : "";

// Check if the selected product is "Other"
if ($product_name === "other" && !empty($custom_product_name)) {
  // Handle the "Other" option logic here
  // Check if the custom product already exists in the product_list table
  $sql = "SELECT * FROM product_list WHERE products='$custom_product_name'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Custom product already exists, set the product_name to the custom product name
    $product_name = $custom_product_name;
  } else {
    // Custom product doesn't exist, insert it into the product_list table
    $sql = "INSERT INTO product_list (products) VALUES ('$custom_product_name')";

    if (!mysqli_query($conn, $sql)) {
      // Error occurred while inserting custom product, send error response back to the client
      $response['success'] = false;
      $response['message'] = "Error: " . mysqli_error($conn);
      echo json_encode($response);
      exit();
    }
    // Set the product_name to the custom product name
    $product_name = $custom_product_name;
  }
}
// Check if the new date is greater than all other dates in the records and less than or equal to the current date
$sql_check_purchase_date = "SELECT MAX(date) AS max_date FROM purchase";
$result_check_date = mysqli_query($conn, $sql_check_purchase_date);
$row_check_date = mysqli_fetch_assoc($result_check_date);
$max_date_purchase = $row_check_date['max_date'];

// Check if the new date is greater than all other dates in the records and less than or equal to the current date
$sql_check_stock_date = "SELECT MAX(date) AS max_date FROM stock";
$result_check_date = mysqli_query($conn, $sql_check_stock_date);
$row_check_date = mysqli_fetch_assoc($result_check_date);
$max_date_stock = $row_check_date['max_date'];

if (strtotime($date) >= strtotime($max_date_purchase) && strtotime($date) >= strtotime($max_date_stock) && strtotime($date) <= strtotime(date("Y-m-d"))) {

// Insert data into purchase table
$sql = "INSERT INTO purchase (date, item_name, quantity, price, stock, editor) VALUES ('$date', '$product_name', '$quantity', '$price', '$quantity', '$username')";

if (!mysqli_query($conn, $sql)) {
  // Send error response back to client
  $response['success'] = false;
  $response['message'] = "Error: " . mysqli_error($conn);
  echo json_encode($response);
  mysqli_close($conn);
  exit();
}


// Insert or update data in stock table
$sql = "SELECT *
FROM stock
WHERE item_name = '$product_name'
AND timestamp = (SELECT MAX(timestamp) FROM stock WHERE item_name = '$product_name');
";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // Update existing record
  $row = mysqli_fetch_assoc($result);
  $new_quantity = $row["available_quantity"] + $quantity;

      $sql = "INSERT INTO `stock` (date, item_name, purchase_quantity, available_quantity, editor) VALUES ('$date', '$product_name', '$quantity', '$new_quantity', '$username')";
      //$sql = "UPDATE stock SET quantity='$new_quantity' WHERE item_name='$product_name'";

  if (!mysqli_query($conn, $sql)) {
    // Send error response back to client
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    mysqli_close($conn);
    exit();
  }
} else {
  // Insert new record
      $sql = "INSERT INTO `stock` (date, item_name, purchase_quantity, available_quantity, editor) VALUES ('$date', '$product_name', '$quantity', '$quantity', '$username')";
      //$sql = "INSERT INTO stock (item_name, quantity) VALUES ('$product_name', '$quantity')";

  if (!mysqli_query($conn, $sql)) {
    // Send error response back to client
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    mysqli_close($conn);
    exit();
  }
}

mysqli_close($conn);

// Send success response back to client
$response['success'] = true;
$response['message'] = "Data inserted successfully";
} else {
  $response['success'] = false;
  $response['message'] = "Sorry you are restricted to made entry on this date";
  //$response['message'] = "Invalid date. The date should be greater than all other dates and less than or equal to the current date.";
}
echo json_encode($response);
exit();
?>