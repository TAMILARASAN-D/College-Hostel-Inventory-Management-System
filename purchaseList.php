<?php
// Database connection
include "db_connect.php";
// Fetch data from database
//$query = "SELECT * FROM purchase ORDER BY sno DESC LIMIT 10";
$query = "SELECT * FROM purchase ORDER BY sno DESC ";
$result = mysqli_query($conn, $query);

$data = array();
while($row = mysqli_fetch_assoc($result)){
	$data[] = $row;
}

echo json_encode($data);
mysqli_close($conn);
?>
