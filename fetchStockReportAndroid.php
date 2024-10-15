<?php
// Connect to the database
include "db_connect.php";

// Get the selected product names from the Android application
$product_names = $_POST['product_names'];

$productArray = explode(',',$product_names);

// If no product names are selected
if ($product_names == null) {
  echo json_encode(array("error" => "Select Products"));
  exit;
}

// Build the SQL query to retrieve the item name and quantity based on the selected product names
if ($product_names === 'All Products') {
    $sql = "SELECT item_name, quantity FROM stock";
} else {
    $product_names = $conn->real_escape_string($product_names);
    $sql = "SELECT item_name, quantity FROM stock WHERE item_name = '$product_names'";
}

// Execute the SQL query
$results = mysqli_query($conn, $sql);

// Fetch the results
$row_results = mysqli_fetch_all($results, MYSQLI_ASSOC);

// Build the JSON response array
$response = array();
foreach ($row_results as $row) {
    $item = array(
        "product_name" => $row['item_name'],
        "quantity" => $row['quantity']
    );
    $response[] = $item;
}

// Convert the response array to JSON
$json_response = json_encode($response);

// Output the JSON response
header('Content-Type: application/json');
echo $json_response;
?>
