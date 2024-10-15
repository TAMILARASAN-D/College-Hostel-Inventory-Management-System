<?php
// Connect to the database
include "db_connect.php";

// Get the selected product IDs
$product_names = $_POST['product_names'];

// Get the selected date range from the AJAX request
$date_range = $_POST['date_range'];


if ($product_names != null) {
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
} else {
    echo '<script>alert("Select Products")</script>';
}
// Build the SQL query to retrieve the stock products data for the for selected product names and selected date range
$sql = "SELECT * FROM stock WHERE date BETWEEN ? AND ?";
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

    // Initialize the table HTML
    $table = '<table>
                <thead>
                    <tr>
                        <th width=120px>Date</th>
                        <th width=300px>Product Name</th>
                        <th>Opening Stock (Kg)</th>
                        <th>Purchase Quantity (Kg)</th>
                        <th>Distribution Quantity (Kg)</th>
                        <th width=60px>Hostel</th>
                        <!--<th>Available Quantity (Kg)</th>-->
                        <th>Closing Stock (Kg)</th>
                    </tr>
                </thead>
                <tbody>';

    // Fetch opening stock for each product on each date
    $openingStock = array();
    foreach ($row_results as $row) {
        $product_name = $row['item_name'];
        $date = date("d-m-Y", strtotime($row['date'])); // convert date format to "d-m-Y" for display
        $available_quantity = $row['available_quantity'];

        // Fetch the earliest available quantity before the current date as the opening stock
        $sql_opening_stock = "SELECT available_quantity FROM stock WHERE item_name = ? AND date < ? AND timestamp < ? ORDER BY date DESC, timestamp DESC LIMIT 1";
        $stmt_opening_stock = mysqli_prepare($conn, $sql_opening_stock);
        mysqli_stmt_bind_param($stmt_opening_stock, 'sss', $product_name, $row['date'], $row['timestamp']);
        mysqli_stmt_execute($stmt_opening_stock);
        $result_opening_stock = mysqli_stmt_get_result($stmt_opening_stock);
        $row_opening_stock = mysqli_fetch_assoc($result_opening_stock);
        $opening_stock = $row_opening_stock['available_quantity'] ?? 0;
        $openingStock[$product_name][$row['date']] = $opening_stock;

        // Calculate the closing stock for the current date and product
        $closing_stock = $available_quantity;

        // Output the table row
        $table .= '<tr>';
        $table .= '<td>' . $date . '</td>';
        $table .= '<td>' . $product_name . '</td>';
        $table .= '<td>' . $opening_stock . '</td>';
        $table .= '<td>' . $row['purchase_quantity'] . '</td>';
        $table .= '<td>' . $row['distribution_quantity'] . '</td>';
        $table .= '<td>' . $row['hostel'] . '</td>';
        //$table .= '<td>' . $available_quantity . '</td>';
        $table .= '<td>' . $closing_stock . '</td>';
        $table .= '</tr>';
    }

    

    $table .= '</tbody></table>';

    // Output the HTML table
    echo $table;
?>















<!--
// Connect to the database
include "db_connect.php";

// Get the selected product IDs
$product_names = isset($_POST['product_names']) ? $_POST['product_names'] : array();

// Get the selected date range from the AJAX request
$date_range = isset($_POST['date_range']) ? $_POST['date_range'] : 'today';

// Initialize start and end dates
$start_date = date('Y-m-d');
$end_date = date('Y-m-d');

if ($date_range !== 'custom') {
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
    }
} else {
    // Custom date range selected
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
}

if (!empty($product_names)) {
    // Check if "All Products" is selected
    if (in_array('All Products', $product_names)) {
        // Remove "All Products" from the product names array
        $all_products_key = array_search('All Products', $product_names);
        unset($product_names[$all_products_key]);
    }

    // Build the SQL query to retrieve the products stock data for the selected product names and date range
    $sql = "SELECT * FROM stock WHERE date BETWEEN ? AND ?";
    if (!empty($product_names)) {
        $placeholders = str_repeat('?, ', count($product_names) - 1) . '?';
        $sql .= " AND item_name IN ($placeholders)";
    }
    $sql .= " ORDER BY date ASC";

    $stmt = mysqli_prepare($conn, $sql);

    // Build the bind parameters array with start date, end date, and product names (if any)
    $bind_params = array('ss', $start_date, $end_date);
    if (!empty($product_names)) {
        $bind_params = array_merge($bind_params, $product_names);
    }
    if (!empty($bind_params)) {
        mysqli_stmt_bind_param($stmt, ...$bind_params);
    }
} else {
    echo '<script>alert("Select Products")</script>';
    // Handle the case when no products are selected (optional)
    // You can add further code here if you need to handle this scenario differently
}

if (isset($stmt)) {
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $row_results = mysqli_fetch_all($results, MYSQLI_ASSOC);

    // Initialize the table HTML
    $table = '<table>
                <caption>' . $date_range . '</caption>
                <thead>
                    <tr>
                        <th width=100px>Date</th>
                        <th width=320px>Product Name</th>
                        <th>Purchase Quantity (Kg)</th>
                        <th>Distribution Quantity (Kg)</th>
                        <th width=60px>Hostel</th>
                        <th>Opening Stock (Kg)</th>
                        <th>Available Quantity (Kg)</th>
                        <th>Closing Stock (Kg)</th>
                    </tr>
                </thead>
                <tbody>';

    // Fetch opening stock for each product on each date
    $openingStock = array();
    foreach ($row_results as $row) {
        $product_name = $row['item_name'];
        $date = date("d-m-Y", strtotime($row['date'])); // convert date format to "d-m-Y" for display
        $available_quantity = $row['available_quantity'];

        // Fetch the earliest available quantity before the current date as the opening stock
        $sql_opening_stock = "SELECT available_quantity FROM stock WHERE item_name = ? AND date < ? ORDER BY date DESC LIMIT 1";
        $stmt_opening_stock = mysqli_prepare($conn, $sql_opening_stock);
        mysqli_stmt_bind_param($stmt_opening_stock, 'ss', $product_name, $row['date']);
        mysqli_stmt_execute($stmt_opening_stock);
        $result_opening_stock = mysqli_stmt_get_result($stmt_opening_stock);
        $row_opening_stock = mysqli_fetch_assoc($result_opening_stock);
        $opening_stock = $row_opening_stock['available_quantity'] ?? 0;
        $openingStock[$product_name][$row['date']] = $opening_stock;

        // Calculate the closing stock for the current date and product
        $closing_stock = $available_quantity;

        // Output the table row
        $table .= '<tr>';
        $table .= '<td>' . $date . '</td>';
        $table .= '<td>' . $product_name . '</td>';
        $table .= '<td>' . $row['purchase_quantity'] . '</td>';
        $table .= '<td>' . $row['distribution_quantity'] . '</td>';
        $table .= '<td>' . $row['hostel'] . '</td>';
        $table .= '<td>' . $opening_stock . '</td>';
        $table .= '<td>' . $available_quantity . '</td>';
        $table .= '<td>' . $closing_stock . '</td>';
        $table .= '</tr>';
    }

    

    $table .= '</tbody></table>';

    // Output the HTML table
    echo $table;
}
?>
-->