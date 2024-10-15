<?php
// Connect to the database
include "db_connect.php";

// Check if product_names are selected
if (isset($_POST['product_names']) && !empty($_POST['product_names'])) {
    // Get the selected product IDs
    $product_names = $_POST['product_names'];

    // Prepare the product names for the WHERE clause (prevent SQL injection)
    $product_names1 = array();
    foreach ($product_names as $product_id) {
        $product_names1[] = $conn->real_escape_string($product_id);
    }
    $product_names1 = implode("', '", $product_names1);

    // Build the SQL query to retrieve the purchased products data for the selected product names and selected date range
    $sql = "SELECT s.item_name, s.available_quantity
    FROM stock s
    JOIN (
        SELECT item_name, MAX(timestamp) AS max_timestamp
        FROM stock
        GROUP BY item_name
    ) max_timestamps
    ON s.item_name = max_timestamps.item_name AND s.timestamp = max_timestamps.max_timestamp";

    // Check if "All Products" option is selected
    if (!in_array('All Products', $product_names)) {
        // Append the WHERE clause to the query for selected product names
        $sql .= " WHERE s.item_name IN ('$product_names1')";
    }
    // Execute the SQL query using prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Get the result set as an associative array
    $result = $stmt->get_result();

    // Fetch the results
    $row_results = $result->fetch_all(MYSQLI_ASSOC);

    // Build the HTML table to display the purchased products data
    $table = '<table><thead><tr><th>Product Name</th><th>Available Quantity(Kg)</th></tr></thead><tbody>';

    foreach ($row_results as $row) {
        $purchased_qty = isset($purchased_quantities[$row['item_name']]) ? $purchased_quantities[$row['item_name']] : 0;
        $distributed_qty = isset($distributed_quantities[$row['item_name']]) ? $distributed_quantities[$row['item_name']] : 0;
        $available_qty = $row['available_quantity'];
        $actual_sub_qty = $purchased_qty - $distributed_qty;

        // Retrieve the limit from the product_list table
        $limit = 5; // Default limit value if not found or null in the table
        $product_name = $row['item_name'];
        $limit_query = "SELECT `quantity_limit` FROM product_list WHERE products = '$product_name'";
        $limit_result = mysqli_query($conn, $limit_query);
        if ($limit_result && mysqli_num_rows($limit_result) > 0) {
            $limit_row = mysqli_fetch_assoc($limit_result);
            if ($limit_row['quantity_limit'] !== null) {
                $limit = $limit_row['quantity_limit'];
            }
        }

        if ($available_qty <= $limit) { // Only display products with available quantity less than 5
            $table .= '<tr>';
            $table .= '<td>' . $row['item_name'] . '</td>';
            $table .= '<td>' . $available_qty . '</td>';
            $table .= '</tr>';
        }
    }

    // Output the HTML table
    echo $table;
}
?>