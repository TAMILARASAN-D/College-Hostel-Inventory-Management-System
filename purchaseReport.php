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
  <title>Purchased Products Report</title>
  <script src="functions.js"></script>
  <link rel="stylesheet" href="report_style_update.css">
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
  <h1>Purchased Products Report</h1>
  <div class="item">
    <label for="product_name">&nbsp Choose Product &nbsp</label>
    <br>
  <?php
        include "db_connect.php";
        $result = mysqli_query($conn,"select * from product_list");

          echo '<select id="product_name" name="product_name[]" class="chosen" style="width:100%"; required>';
          echo '<option value="">--select--</option>';
          echo '<option value="all" selected>All Products</option>';
            while($row=mysqli_fetch_array($result))
            {
              echo "<option>$row[products]</option>";
            }
            echo "</select>";
            mysqli_close($conn);
    ?>
<br>
  <label for="date_range">&nbsp Select Date Range &nbsp</label>
  <br>
  <select name="date_range" id="date_range" style="width:30%";>
    <option value="--select--" selected>--select--</option>
    <option value="today" >Today</option>
    <option value="yesterday">Yesterday</option>
    <option value="last_week">Last Week</option>
    <option value="this_month">Current Month</option>
    <option value="last_month">Last Month</option>
    <option value="custom">Custom Range</option>
  </select>
  
  <div id="custom_dates" style="display: none;">
    <label for="start_date">&nbsp Start Date &nbsp</label>
    <br>
    <input type="date" name="start_date">
    <br>
    <label for="end_date">&nbsp End Date &nbsp</label>
    <br>
    <input type="date" name="end_date">
    <br><!--
    <div id="custom_month">
      <label for="custom_month_input">&nbsp Custom Month/Year &nbsp</label>
      <br>
      <input type="month" name="custom_month" id="custom_month_input">
    </div>-->
  </div>
  <br>
<button onclick="printReport()" class="print-button">Print</button><br>
          </div>
          <div class=container id="report_display_none">
            <div id="report" class="print-content" id="report_display_none"></div>
          </div>
<!-- To print the report
<script>
    function printReport() {
      var printContents = document.querySelector('.print-content').innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      window.location.reload();
    }
  </script>
  -->
  <script>
  function printReport() {
  var table_printContents = document.querySelector('.print-content').innerHTML;
  var originalContents = document.body.innerHTML;

  // Get the selected product name
  var productName = $('select[name="product_name[]"] option:selected').text();

  // Get the selected date range
  var dateRange = $('select[name="date_range"] option:selected').val();

  // Get the start date and end date
  var startDate = $('input[name="start_date"]').val();
  var endDate = $('input[name="end_date"]').val();

  // Get the custom month
  var customMonth = $('input[name="custom_month"]').val();

  // Update the printContents with the title, product name, and date range
  printContents = '<h1>Purchased Products Report</h1>' +
                  '<h2>Product: ' + productName + '</h2>' +
                  '<h2>Date Range: ' + $('select[name="date_range"] option:selected').text() + '</h2>';

  // Add the appropriate date format based on the selected date range
  if (dateRange === 'today') {
    printContents += '<h3>Date: ' + getCurrentDate() + '</h3>';
  } else if (dateRange === 'yesterday') {
    printContents += '<h3>Date: ' + getYesterdayDate() + '</h3>';
  } else if (dateRange === 'last_week') {
    printContents += '<h3>From: ' + getLastWeekStartDate() + '</h3>' +
                     '<h3>To: ' + getLastWeekEndDate() + '</h3>';
  } else if (dateRange === 'this_month') {
    printContents += '<h3>Month: ' + getCurrentMonth() + '</h3>';
  } else if (dateRange === 'last_month') {
    printContents += '<h3>Month: ' + getLastMonth() + '</h3>';
  } else if (dateRange === 'custom') {
    if (customMonth) {
      printContents += '<h3>Month: ' + getCustomMonth(customMonth) + '</h3>';
    } else {
      printContents += '<h3>From: ' + formattedDate(startDate) + '</h3>' +
                       '<h3>To: ' + formattedDate(endDate) + '</h3>';
    }
  }

  printContents += table_printContents;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  window.location.reload();
}

function getCurrentDate() {
  var currentDate = new Date();
  var day = currentDate.getDate();
  var month = currentDate.getMonth() + 1;
  var year = currentDate.getFullYear();
  return formatDate(day, month, year);
}

function getYesterdayDate() {
  var yesterdayDate = new Date();
  yesterdayDate.setDate(yesterdayDate.getDate() - 1);
  var day = yesterdayDate.getDate();
  var month = yesterdayDate.getMonth() + 1;
  var year = yesterdayDate.getFullYear();
  return formatDate(day, month, year);
}

function getLastWeekStartDate() {
  var currentDate = new Date();
  currentDate.setDate(currentDate.getDate() - 6);
  var day = currentDate.getDate();
  var month = currentDate.getMonth() + 1;
  var year = currentDate.getFullYear();
  return formatDate(day, month, year);
}

function getLastWeekEndDate() {
  var currentDate = new Date();
  var day = currentDate.getDate();
  var month = currentDate.getMonth() + 1;
  var year = currentDate.getFullYear();
  return formatDate(day, month, year);
}


function getCurrentMonth() {
  var currentDate = new Date();
  var month = currentDate.getMonth() + 1;
  var year = currentDate.getFullYear();
  return getMonthName(month) + ' ' + year;
}

function getLastMonth() {
  var currentDate = new Date();
  var month = currentDate.getMonth();
  var year = currentDate.getFullYear();
  if (month === 0) {
    month = 12;
    year--;
  }
  return getMonthName(month) + ' ' + year;
}

function getCustomMonth(customMonth) {
  var customMonthDate = new Date(customMonth);
  var month = customMonthDate.getMonth() + 1;
  var year = customMonthDate.getFullYear();
  return getMonthName(month) + ' ' + year;
}

function formatDate(day, month, year) {
  return leadingZero(day) + '-' + leadingZero(month) + '-' + year;
}
function formattedDate(date) {
  var formattedDate = '';
  var parts = date.split('-');
  var year = parts[0];
  var month = parts[1];
  var day = parts[2];
  formattedDate = day + '-' + month + '-' + year;
  return formattedDate;
}


function leadingZero(value) {
  return value < 10 ? '0' + value : value;
}

function getMonthName(month) {
  var monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  return monthNames[month - 1];
}

</script>

  <script>
    $(document).ready(function() {
  function fetchData() {
        //var product_ids = $('select[name="product_name[]"]').val();
        var product_names = $('select[name="product_name[]"] option:selected').map(function(){return $(this).text()}).get();
        var date_range = $('select[name="date_range"]').val();
        var start_date = $('input[name="start_date"]').val();
        var end_date = $('input[name="end_date"]').val();
        var custom_month = $('input[name="custom_month"]').val();
        
        // If the selected date range is "This Month"
        if (date_range == 'this_month') {
          // Get the start and end dates of the current month
          var currentDate = new Date();
          var currentYear = currentDate.getFullYear();
          var currentMonth = currentDate.getMonth();
          var startOfMonth = new Date(currentYear, currentMonth, 1);
          var endOfMonth = new Date(currentYear, currentMonth + 1, 0);
          start_date = startOfMonth.toISOString().slice(0, 10);
          end_date = endOfMonth.toISOString().slice(0, 10);
        }
        
        if (date_range == 'custom') {
          $('#custom_dates').show();
        } else {
          $('#custom_dates').hide();
        }
        if (date_range == '--select--') {
          $('.container').hide();
          $('.print-button').hide();
        } else {
          $('.container').show();
          $('.print-button').show();
        }

        // If the selected date range is "Custom" and a custom month is specified
        if (date_range == 'custom' && custom_month) {
            // Parse the custom month string into a date object
            var customMonthDate = new Date(custom_month);
                  // Get the start and end dates of the custom month
      var customMonthYear = customMonthDate.getFullYear();
      var customMonthMonth = customMonthDate.getMonth();
      var startOfCustomMonth = new Date(customMonthYear, customMonthMonth, 1);
      var endOfCustomMonth = new Date(customMonthYear, customMonthMonth + 1, 0);
      start_date = startOfCustomMonth.toISOString().slice(0, 10);
      end_date = endOfCustomMonth.toISOString().slice(0, 10);
    }
    
    // Make an AJAX request to fetch the report data
    $.ajax({
      url: 'fetchPurchaseReport.php',
      type: 'POST',
      data: {
        product_names: product_names,
        date_range: date_range,
        start_date: start_date,
        end_date: end_date,
        custom_month: custom_month
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
