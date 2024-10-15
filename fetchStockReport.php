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

    // Build the SQL query to retrieve the purchased products data for the for selected product names and selected date range
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
        $available_qty = $row['available_quantity'];
    
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
    
        $table .= '<tr';
        if ($available_qty <= $limit) {
            $table .= ' class="glowing-row"'; // Apply CSS class for glowing effect
        }
    
        $table .= '>';

        // Sanitize output for display (optional)
        $item_name = htmlspecialchars($row['item_name']);
        $available_quantity = htmlspecialchars($row['available_quantity']);

        $table .= '<td>' . $item_name . '</td>';
        $table .= '<td>' . $available_quantity . '</td>';

        $table .= '</tr>';
    }

    $table .= '</tbody></table>';

    // Output the HTML table
    echo $table;
}
?>




<style>
    .glowing-row {
        animation: glowing-animation 1s infinite;
    }

    @keyframes glowing-animation {
        0% {
            background-color: red;
        }

        50% {
            background-color: transparent;
        }

        100% {
            background-color: red;
        }
    }
</style>