<?php
// Connect to the database
include 'db_connect.php';

// Insert data into purchase table
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];

$sql = "INSERT INTO purchase (date, item_name, quantity, price) VALUES ('$date', '$product_name', '$quantity', '$price')";

if (mysqli_query($conn, $sql)) {
    $response = "success";
    echo json_encode($response);
} else {
    echo json_encode("Error");
}

// Insert or update data in stock table
$sql = "SELECT * FROM stock WHERE item_name='$product_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // Update existing record
  $row = mysqli_fetch_assoc($result);
  $new_quantity = $row["quantity"] + $quantity;

  $sql = "UPDATE stock SET quantity='$new_quantity' WHERE item_name='$product_name'";
} else {
  // Insert new record
  $sql = "INSERT INTO stock (item_name, quantity) VALUES ('$product_name', '$quantity')";
}

if (mysqli_query($conn, $sql)) {
    $response = "success";
    echo json_encode($response);
    exit();
} else {
  echo json_encode("Error: " . $sql . "<br>" . mysqli_error($conn));
}

mysqli_close($conn);
?>
