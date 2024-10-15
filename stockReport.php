<?php
require_once 'session_check.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="functions.js"></script>
  <title>Stock Report</title>
  <link rel="stylesheet" href="report_style_update_stock.css">
  <style>
    /* Add border style to the table for print */
    @media print {
      table {
        border-collapse: collapse;
      }

      th,
      td {
        border: 1px solid black;
        padding: 8px;
      }
    }

    /* Custom styling for the report page */
    body {
      font-family: "Times New Roman", serif;
    }

    h1 {
      font-size: 40px;
      text-align: center;
      margin-top: 20px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
<button onclick="goHosteluserHome()" id="back">Back</button><br>
  <h1>Stock Report</h1>
  <div class="item">
    <label for="product_name">&nbsp Choose Product &nbsp</label>
    <br>
  <?php
        include "db_connect.php";
        $result = mysqli_query($conn,"select * from product_list");

          echo '<select id="product_name" name="product_name[]" class="chosen" style="width:100%"; required>';
          echo '<option value="--select--" selected>--select--</option>';
          echo '<option value="all">All Products</option>';
            while($row=mysqli_fetch_array($result))
            {
              echo "<option>$row[products]</option>";
            }
            echo "</select>";
            mysqli_close($conn);
    ?>
<br>
<button onclick="printReport()" class="print-button">Print</button><br>
          </div>
          <div>
          <div class=container>
  <div id="report" class="print-content"></div>
          </div>
          </div>

  <script>
  function printReport() {
  var table_printContents = document.querySelector('.print-content').innerHTML;
  var originalContents = document.body.innerHTML;

  // Get the selected product name
  var productName = $('select[name="product_name[]"] option:selected').text();
  // Update the printContents with the title, product name, and date range
  printContents = '<h1>Stock Report</h1>' ;
                  //'<h2>Product: ' + productName + '</h2>';
                  printContents += table_printContents;

document.body.innerHTML = printContents;
window.print();
document.body.innerHTML = originalContents;
window.location.reload();
}
</script>
  <script>
    $(document).ready(function() {
      function fetchData() {
        //var product_ids = $('select[name="product_name[]"]').val();
        var product_names = $('select[name="product_name[]"] option:selected').map(function(){return $(this).text()}).get();

        if (product_names == '--select--') {
          $('.container').hide();
          $('.print-button').hide();
        } else {
          $('.container').show();
          $('.print-button').show();
        }
      
    // Make an AJAX request to fetch the report data
    $.ajax({
      url: 'fetchStockReport.php',
      type: 'POST',
      data: {
        product_names: product_names,
      },
      success: function(response) {
        // Update the report table with the response data
        $('#report').html(response);
      },
      error: function(xhr, status, error) {
        alert('An error occurred while getting the report data: ' + error);
      }
    });
  }
  // Call the fetchData function on page load
  fetchData();
  // Call fetchData function when there's a change event
      $('select[name="product_name[]"], select[name="date_range"], input[name="start_date"], input[name="end_date"], input[name="custom_month"]').change(fetchData);
});

  </script>
</body>

<script type="text/javascript">
  $(".chosen").chosen();
  </script>
</html>
