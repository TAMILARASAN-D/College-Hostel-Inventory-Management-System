<?php
include "db_connect.php";

if (isset($_GET['product_name'])) {
    $product_name = $_GET['product_name'];

    //$query = "SELECT quantity FROM stock WHERE item_name = ?";
    $query = "SELECT available_quantity
    FROM stock
    WHERE item_name = ?
    AND timestamp = (SELECT MAX(timestamp) FROM stock WHERE item_name = ?);
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $product_name,$product_name);
    $stmt->execute();
    $stmt->bind_result($available_quantity);
    $stmt->fetch();
    $stmt->close();

    echo $available_quantity;
}
mysqli_close($conn);
?>
