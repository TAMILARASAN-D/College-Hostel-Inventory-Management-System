<?php
// Connect to the database
include "db_connect.php";
// Get the selected product IDs
$product_names = $_POST['product_names'];

// Get the selected date range from the AJAX request
$date_range = $_POST['date_range'];
if($product_names != null ){
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
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    break;
}
}
else{
    echo '<script>alert("Select Products")</script>';
}

/* If the selected date range is "Custom" and a custom month is specified
if ($date_range == 'custom' && $_POST['custom_month']) {
    $year = substr($_POST['custom_month'], 0, 4);
    $month = substr($_POST['custom_month'], 5, 2);
    $start_date = date('Y-m-01', strtotime($year . '-' . $month . '-01'));
    $end_date = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
  }*/

// Build the SQL query to retrieve the purchased products data for the for selected product names and selected date range
$sql = "SELECT * FROM purchase WHERE date BETWEEN ? AND ?";
if (!is_null($product_names) && !in_array('All Products', $product_names)) {
    $product_names1 = array();
    foreach ($product_names as $product_id) {
        $product_names1[] = $conn->real_escape_string($product_id);
    }
    $product_names1 = implode("', '", $product_names1);
    $sql .= " AND item_name IN ('$product_names1')";
}
else{
    $sql .= " ";
}
$sql .= " ORDER BY date ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $start_date, $end_date);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$row_results = mysqli_fetch_all($results, MYSQLI_ASSOC);


// Build the HTML table to display the purchased products data
$table = '<table><thead><tr><th width=110px>Date</th><th width=300px>Product Name</th><th>Quantity(Kg)</th><th>Price(per Kg)</th><th>Total price(per product)</th><th>Editor</th></tr></thead><tbody>';
$total = 0;
$product_total = 0;

foreach ($row_results as $row) {

$date = date("d-m-Y", strtotime($row['date'])); // convert date format
$product_total = $row['quantity'] * $row['price'];

  $table .= '<tr>';
  $table .= '<td >' . $date . '</td>';
  $table .= '<td>' . $row['item_name'] . '</td>';
  $table .= '<td>' . $row['quantity'] . '</td>';
  $table .= '<td>₹' . number_format($row['price'], 2) . '</td>';
  $table .= '<td>₹' . number_format($product_total, 2) . '</td>';
  $table .= '<td>' . $row['editor'] . '</td>';
  $table .= '</tr>';
  
  

  $total += $row['quantity'] * $row['price'];
}

$table .= '</tbody><tr><td colspan="4">Grand Total:</td><td>₹' . number_format($total, 2) . '</td></tr></table>';

// Output the HTML table
echo $table;
?>
