<?php
// Connect to the database
include "db_connect.php";

// Get the selected product IDs
$product_names = $_POST['product_names'];

// Get the selected date range from the AJAX request
$date_range = $_POST['date_range'];

$productArray = explode(',',$product_names);

// If no product names are selected
if ($product_names == null) {
  echo json_encode(array("error" => "Select Products"));
  exit;
}

// Determine the start and end dates based on the selected range
switch ($date_range) {
  case 'today':
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d');
    break;
  case 'yesterday':
    $start_date = date('Y-m-d', strtotime('-1 day'));
    $end_date = date('Y-m-d', strtotime('-1 day'));
    break;
  case 'last_week':
    $start_date = date('Y-m-d', strtotime('-1 week'));
    $end_date = date('Y-m-d');
    break;
  case 'this_month':
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t');
    break;    
  case 'last_month':
    $start_date = date('Y-m-01', strtotime('-1 month'));
    $end_date = date('Y-m-t', strtotime('-1 month'));
    break;
  case 'custom':
    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
          $start_date = $_POST['start_date'];
          $end_date = $_POST['end_date'];
    } else {
        // Handle missing start_date or end_date
        echo json_encode(array("error" => "Missing start_date or end_date"));
        exit;
      }
      break;
  default:
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d');
    break;
}

/* If the selected date range is "Custom" and a custom month is specified
if ($date_range == 'custom' && $_POST['custom_month']) {
  $year = substr($_POST['custom_month'], 0, 4);
  $month = substr($_POST['custom_month'], 5, 2);
  $start_date = date('Y-m-01', strtotime($year . '-' . $month . '-01'));
  $end_date = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
}
*/

// Build the SQL query to retrieve the purchased products data for the for selected product names and selected date range
$sql = "SELECT * FROM purchase WHERE date BETWEEN ? AND ?";
if (!in_array('All Products', $productArray)) {
  $product_names1 = array();
  foreach ($productArray as $product_id) {
      $product_names1[] = $conn->real_escape_string($product_id);
  }
  $product_names1 = implode("', '", $product_names1);
  $sql .= " AND item_name IN ('$product_names1')";
}
$sql .= " ORDER BY date ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $start_date, $end_date);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$row_results = mysqli_fetch_all($results, MYSQLI_ASSOC);

// Build the array of data to be returned in JSON format
$data = array();
$total = 0;
$product_total = 0;
foreach ($row_results as $row) {
  $product_total = $row['quantity'] * $row['price'];
  $total += $product_total;

  $data[] = array(
    "date" => $row['date'],
    "product_name" => $row['item_name'],
    "quantity" => $row['quantity'],
    "price" => $row['price'],
    "product_total" => $product_total,
    "grand_total" => $total
    );
    }
    
    
    // Convert the array to JSON format
    $json_data = json_encode($data);
    
    // Set the response header to indicate that JSON data is being returned
    header('Content-Type: application/json');
    
    // Output the JSON data
    echo $json_data;
    ?>
