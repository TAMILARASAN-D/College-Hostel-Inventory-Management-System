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
//$sql_stock1 = "SELECT * FROM stock WHERE item_name='$product_name' AND available_quantity >= '$quantity'";
$sql_stock1 = "SELECT *
FROM stock
WHERE item_name = '$product_name'
AND available_quantity >= '$quantity'
AND timestamp = (SELECT MAX(timestamp) FROM stock WHERE item_name = '$product_name')
";
$sql_purchase1 = "SELECT * FROM purchase WHERE item_name='$product_name' AND stock >= '$quantity' ORDER BY date ASC";
$result_stock = mysqli_query($conn, $sql_stock1);
$result_purchase = mysqli_query($conn, $sql_purchase1);

if (mysqli_num_rows($result_stock) > 0 && mysqli_num_rows($result_purchase) > 0) {

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

  $remaining_quantity = $quantity;
  $success = false;

  // Fetch stock row data
  $row_stock = mysqli_fetch_assoc($result_stock);

  while ($row_purchase = mysqli_fetch_assoc($result_purchase)) {
    $current_date = $row_purchase["date"];
    $stock_quantity_in_purchase = $row_purchase["stock"];
    $price = $row_purchase["price"];

    if ($stock_quantity_in_purchase >= $remaining_quantity) {
      // Sufficient stock available in this purchase record
      $new_quantity_for_purchase = $stock_quantity_in_purchase - $remaining_quantity;

      // Update purchase table
      $sql_purchase2 = "UPDATE purchase SET stock='$new_quantity_for_purchase' WHERE item_name='$product_name' AND date='$current_date' AND stock='$stock_quantity_in_purchase' AND price='$price'";
      mysqli_query($conn, $sql_purchase2);

      // Fetch the latest stock quantity from the stock table
      //$sql_stock3 = "SELECT quantity FROM stock WHERE item_name='$product_name'";
      $sql_stock3 = "SELECT available_quantity
      FROM stock
      WHERE item_name = '$product_name'
      AND timestamp = (SELECT MAX(timestamp) FROM stock WHERE item_name = '$product_name')";
      $result_stock3 = mysqli_query($conn, $sql_stock3);
      $row_stock3 = mysqli_fetch_assoc($result_stock3);
      $current_stock_quantity = $row_stock3["available_quantity"];

      // Update stock table
      $new_quantity_for_stock = $current_stock_quantity - $remaining_quantity;
      $sql_stock2 = "INSERT INTO `stock` (hostel, date, item_name, distribution_quantity, available_quantity, editor) VALUES ('$hostel', '$date', '$product_name', '$remaining_quantity', '$new_quantity_for_stock', '$username')";
      //$sql_stock2 = "UPDATE stock SET quantity='$new_quantity_for_stock' WHERE item_name='$product_name'";
      mysqli_query($conn, $sql_stock2);

      // Insert data into distribution table
      $sql3 = "INSERT INTO `distribution` (hostel, date, item_name, quantity, price, editor) VALUES ('$hostel', '$date', '$product_name', '$remaining_quantity', $price, '$username')";
      mysqli_query($conn, $sql3);

      $success = true;
      break;
    } elseif ($stock_quantity_in_purchase > 0) {
      // Insufficient stock in this purchase record, reduce stock to zero
      $new_quantity_for_purchase = 0;

      // Update purchase table
      $sql_purchase2 = "UPDATE purchase SET stock='$new_quantity_for_purchase' WHERE item_name='$product_name' AND date='$current_date' AND stock='$stock_quantity_in_purchase' AND price='$price'";
      mysqli_query($conn, $sql_purchase2);

      // Fetch the latest stock quantity from the stock table
      //$sql_stock3 = "SELECT quantity FROM stock WHERE item_name='$product_name'";
      $sql_stock3 = "SELECT available_quantity
      FROM stock
      WHERE item_name = '$product_name'
      AND timestamp = (SELECT MAX(timestamp) FROM stock WHERE item_name = '$product_name')";
      $result_stock3 = mysqli_query($conn, $sql_stock3);
      $row_stock3 = mysqli_fetch_assoc($result_stock3);
      $current_stock_quantity = $row_stock3["available_quantity"];

      // Update stock table
      $new_quantity_for_stock = $current_stock_quantity - $stock_quantity_in_purchase;
      $sql_stock2 = "INSERT INTO `stock` (hostel, date, item_name, distribution_quantity, available_quantity, editor) VALUES ('$hostel', '$date', '$product_name', '$stock_quantity_in_purchase', '$new_quantity_for_stock', '$username')";
      //$sql_stock2 = "UPDATE stock SET quantity='$new_quantity_for_stock' WHERE item_name='$product_name'";
      mysqli_query($conn, $sql_stock2);


      // Insert data into distribution table
      $sql4 = "INSERT INTO `distribution` (hostel, date, item_name, quantity, price, editor) VALUES ('$hostel', '$date', '$product_name', '$stock_quantity_in_purchase', $price, '$username')";
      mysqli_query($conn, $sql4);

      $remaining_quantity -= $stock_quantity_in_purchase;
      if ($remaining_quantity === 0)
      {
        $success = true;
      }
    }
  }

  if ($success) {
    $response['success'] = true;
    $response['message'] = "Data inserted successfully";
  }else {
    $response['success'] = false;
    $response['message'] = $product_name . " does not have enough quantity";
  }
} else {
  $response['success'] = false;
  $response['message'] = "Sorry you are restricted to made entry on this date";
}

} else {
  $response['success'] = false;
  $response['message'] = $product_name . " is not available in stock";
}

mysqli_close($conn);

// Send response back to client as JSON
echo json_encode($response);
exit();
?>






/*
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
$sql_stock1 = "SELECT * FROM stock WHERE item_name='$product_name'";
$sql_purchase1 = "SELECT * FROM purchase WHERE item_name='$product_name' AND stock > 0 AND date = (
  SELECT MIN(date)
  FROM purchase
  WHERE item_name = '$product_name'
);";
$result_stock = mysqli_query($conn, $sql_stock1);
$result_purchase = mysqli_query($conn, $sql_purchase1);
$stock_row_data = mysqli_fetch_assoc($result_stock);
$purchase_row_data = mysqli_fetch_assoc($result_purchase);
$stock_quantity_in_stock = $stock_row_data["quantity"];
$stock_quantity_in_purchase = $purchase_row_data["stock"];
$price = $purchase_row_data["price"];

if (mysqli_num_rows($result_stock) > 0 && $stock_quantity_in_stock >= $quantity && mysqli_num_rows($result_purchase) > 0 && $stock_quantity_in_purchase >= $quantity) {

  // Update stock table
  $new_quantity_for_stock = $stock_quantity_in_stock - $quantity;
  $new_quantity_for_purchase = $stock_quantity_in_purchase - $quantity;
  $sql_stock2 = "UPDATE stock SET quantity='$new_quantity_for_stock' WHERE item_name='$product_name'";
  $sql_purchase2 = "UPDATE purchase
  SET stock = '$new_quantity_for_purchase'
  WHERE item_name = '$product_name'
  AND date = (
    SELECT MIN(date)
    FROM purchase
    WHERE item_name = '$product_name'
  );
  ";
  mysqli_query($conn, $sql_stock2);
  mysqli_query($conn, $sql_purchase2);

  // Insert data into distribution table
  $sql3 = "INSERT INTO `distribution` (hostel, date, item_name, quantity, price, editor) VALUES ('$hostel', '$date', '$product_name', '$quantity', $price, '$username')";

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

*/