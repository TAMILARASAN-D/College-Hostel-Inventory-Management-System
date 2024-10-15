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

// Insert data into purchase table
$sql = "INSERT INTO purchase (date, item_name, quantity, price, editor) VALUES ('$date', '$product_name', '$quantity', '$price', '$username')";

if (!mysqli_query($conn, $sql)) {
  // Send error response back to client
  $response['success'] = false;
  $response['message'] = "Error: " . mysqli_error($conn);
  echo json_encode($response);
  mysqli_close($conn);
  exit();
}

// Insert or update data in stock table
$sql = "SELECT * FROM stock WHERE item_name='$product_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // Update existing record
  $row = mysqli_fetch_assoc($result);
  $new_quantity = $row["quantity"] + $quantity;

  $sql = "UPDATE stock SET quantity='$new_quantity' WHERE item_name='$product_name'";

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
  $sql = "INSERT INTO stock (item_name, quantity) VALUES ('$product_name', '$quantity')";

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
echo json_encode($response);
exit();
?>



/*
// Connect to the database
include 'db_connect.php';

// Insert data into purchase table
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];
$custom_product_name = $_POST["custom_product_name"];

// Check if the selected product is "Other"
if ($product_name === "other" && !empty($_POST["custom_product_name"])) {
  // Handle the "Other" option logic here
  // Perform the necessary operations and insert data into the product_list table using the custom product name
  $sql1 = "INSERT INTO product_list (products) VALUES ('$custom_product_name')";
  if (mysqli_query($conn, $sql1)) {
    $product_name = $custom_product_name; // Set the product_name to the custom product name
  } else {
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
  }
}
$sql = "INSERT INTO purchase (date, item_name, quantity, price) VALUES ('$date', '$product_name', '$quantity', '$price')";

if (mysqli_query($conn, $sql)) {
  //header("Location: add_items.php");
  // Send success response back to client
  $response['success'] = true;
  $response['message'] = "Data inserted successfully";
} else {
  //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  // Send error response back to client
  $response['success'] = false;
  $response['message'] = "Error: " . mysqli_error($conn);
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
  //header("Location: add_items.php");
  //exit();
    // Send success response back to client
    $response['success'] = true;
    $response['message'] = "Data inserted successfully";
} else {
  //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  // Query execution failed, show error message
  // Send error response back to client
  $response['success'] = false;
  $response['message'] = "Error: " . mysqli_error($conn);
}

mysqli_close($conn);

// Send response back to client as JSON
echo json_encode($response);
*/
?>
/*

// Connect to the database
include 'db_connect.php';

$response = array(); // Create a response array

// Check if product name is empty or not provided
if (empty($_POST["product_name"]) && empty($_POST["custom_product_name"])) {
  $response['success'] = false;
  $response['message'] = "Product name is required";
  echo json_encode($response);
  exit();
}

// Insert data into purchase table
$date = $_POST["date"];
$product_name = $_POST["product_name"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];

// Check if the selected product is "Other"
if ($product_name === "other" and !empty($_POST["custom_product_name"])) {
  // Handle the "Other" option logic here
  $custom_product_name = $_POST["custom_product_name"];
  // Perform the necessary operations and insert data into the product_list table using the custom product name
  $sql1 = "INSERT INTO product_list (products) VALUES ('$custom_product_name')";
  if (mysqli_query($conn, $sql1)) {
    $product_name = $custom_product_name; // Set the product_name to the custom product name
  } else {
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
    echo json_encode($response);
    exit();
  }
}

// Insert data into purchase table
$sql2 = "INSERT INTO purchase (date, item_name, quantity, price) VALUES ('$date', '$product_name', '$quantity', '$price')";

if (mysqli_query($conn, $sql2)) {
  // Update or insert data in the stock table
  $sql3 = "SELECT * FROM stock WHERE item_name='$product_name'";
  $result = mysqli_query($conn, $sql3);

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
    $response['success'] = true;
    $response['message'] = "Data inserted successfully";
  } else {
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);
  }
} else {
  $response['success'] = false;
  $response['message'] = "Error: " . mysqli_error($conn);
}

mysqli_close($conn);

// Send response back to client as JSON
header('Content-Type: application/json');
echo json_encode($response);
*/