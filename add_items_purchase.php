<?php
require_once 'session_check.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>HIMS Purchase Items</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style_input_page.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="functions.js"></script>
</head>
<body>
  <div class="all">
<button onclick="goHosteluserHome()" id="back">Back</button><br>
  <div class="item">
    <header>
      <h1>Entry for Purchased Product</h1>
    </header>
    <main>
      <form id="purchaseForm">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
        <br>
        
        <label for="product_name">Product Name</label>
        <br>
        <?php
        include "db_connect.php";
        $result = mysqli_query($conn,"select * from product_list");

          echo '<select id="product_name" name="product_name" class="chosen" required>';
            echo '<option value="">--select a product--</option>';
            while($row=mysqli_fetch_array($result))
            {
              echo "<option>$row[products]</option>";
            }

            echo '<option value="other">Other</option>';
            echo "</select>";
            mysqli_close($conn);
        ?>
        <br>
         
        <label for="quantity">Quantity(Kg)</label>
        <input type="number" id="quantity" name="quantity" min="0.10" step="0.01" placeholder="Enter quantity" required>
        <br>
        <label for="price">Price(per Kg)</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" placeholder="Enter price" required>
        <br>
        <div id="messageDiv"></div> <!-- Add this div to display the message -->

      
        <button type="submit">Add to list</button>
        
        <button onclick="clearInputs()">Clear All</button>
      </form>
      
    </main>
    
  </div>
  <div id="preheader">
  <h1>Previously added products to list</h1>
  </div>
	      <div class="container" id="purchaseList"><!--
          <h1 >Previously added products to list</h1>-->
          <br>
            <table id="purchaseList">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Product</th>
                  <th>Quantity(Kg)</th>
                  <th>Price(per Kg)</th>
                  <th>Total Price</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
        </div>
</div>
<div id="toast"></div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
      $(".chosen").chosen();
    // Handle product_name change event
      $('#product_name').change(function(event) {
        event.preventDefault(); // prevent default behavior of the dropdown
        if ($(this).val() === 'other') {
          // Hide the Chosen dropdown and show text input
          $(this).next('.chosen-container').hide();
          $(this).next('.chosen-container').next('.chosen-container').hide(); // hide the search dropdown
          $(this).next('.chosen-container').find('.chosen-single').addClass('chosen-hidden');
          $(this).hide().after('<input type="text" id="custom_product_name" name="custom_product_name" placeholder="Enter product name" required>');
        } else {
          $(this).next('.chosen-container').show();
          $(this).next('.chosen-container').next('.chosen-container').show(); // show the search dropdown
          $(this).next('.chosen-container').find('.chosen-single').removeClass('chosen-hidden');
          $(this).next('.chosen-container').find('.chosen-single').removeClass('chosen-with-drop');
          $('#custom_product_name').remove();
        }
  });
});
</script>
 <!-- Call the handleTabSwitch function to activate tab switch handling -->
 <script>
    handleTabSwitch();
  </script>
  
<script>
  function showMessage(message, type) {
    var messageDiv = $("#messageDiv");
    messageDiv.text(message);
    messageDiv.removeClass("success-message error-message"); // Remove both classes first
    if (type === true) {
      messageDiv.addClass("success-message");
    } else {
      messageDiv.addClass("error-message");
    }
    messageDiv.show();
  }
</script>
