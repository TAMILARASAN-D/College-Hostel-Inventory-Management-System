/*
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
    $sql = "SELECT item_name, quantity FROM stock WHERE quantity <= 5";
} else {
    $product_names = $conn->real_escape_string($product_names);
    $sql = "SELECT item_name, quantity FROM stock WHERE item_name = '$product_names' AND quantity <= 5";
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
*/
<?php
// Connect to the database
include "db_connect.php";

// Get the selected product names from the Android application
$product_names = $_POST['product_names'];

$productArray = explode(',', $product_names);

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
    $sql = "SELECT item_name, quantity FROM stock WHERE item_name IN ('$product_names')";
}

// Execute the SQL query
$results = mysqli_query($conn, $sql);

// Fetch the results
$row_results = mysqli_fetch_all($results, MYSQLI_ASSOC);

// Build the JSON response array
$response = array();
foreach ($row_results as $row) {
    $available_qty = $row['quantity'];
    $product_name = $row['item_name'];

    // Retrieve the limit from the product_list table
    $limit_query = "SELECT `quantity_limit` FROM product_list WHERE products = '$product_name'";
    $limit_result = mysqli_query($conn, $limit_query);
    $limit_row = mysqli_fetch_assoc($limit_result);
    $limit = ($limit_row && $limit_row['quantity_limit'] !== null) ? $limit_row['quantity_limit'] : 5;

    if ($available_qty <= $limit) {
        $item = array(
            "product_name" => $product_name,
            "quantity" => $available_qty
        );
        $response[] = $item;
    }
}

// Convert the response array to JSON
$json_response = json_encode($response);

// Output the JSON response
header('Content-Type: application/json');
echo $json_response;
?>
