<?php
// Check if the request was made using AJAX
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  
  // Get the current date
  $currentDate = date("Y-m-d");
  
  // Update the date in the database (replace this with your own code)
  // $db->query("UPDATE table SET date = '$currentDate' WHERE id = 1");
  
  // Return the updated date
  echo $currentDate;
  exit;
}
?>
