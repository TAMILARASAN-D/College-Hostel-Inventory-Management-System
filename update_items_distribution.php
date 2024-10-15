<?php
require_once 'session_check.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>HIMS Distribute Items</title>
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
<button onclick="goHosteluserHome()" id="back">Back</button>
  <div class="item">
    <header>
      <h1>Entry for Distributed Product</h1>
    </header>
    <main>
      <form id="distributionForm">

      <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
        <br>

        <label for="hostel">Hostel</label>
        <select id="hostel" name="hostel" required>
          <option value="">--Select--</option>
          <option value="Boys">Boys Hostel</option>
          <option value="Girls">Girls Hostel</option>
        </select>
        <br>

        
        
        <!--
        <label for="product_name">Product Name</label>
        <br>/*
        include "db_connect.php";
        $result = mysqli_query($conn,"select * from product_list");

          echo '<select id="product_name" name="product_name" class="chosen" required>';
          echo '<option value="">--select a product--</option>';
            while($row=mysqli_fetch_array($result))
            {
              echo "<option>$row[products]</option>";
            }
            echo "</select>";
            mysqli_close($conn);
        ?>*/

        <br>
          -->
          <label for="product_name">Product Name</label>
<br>
<select id="product_name" name="product_name" class="chosen" required onchange="fetchAvailableQuantity()">
  <option value="">--select a product--</option>
  <?php
  include "db_connect.php";
  $result = mysqli_query($conn, "SELECT * FROM product_list");
  while ($row = mysqli_fetch_array($result)) {
    echo "<option value='$row[products]'>$row[products]</option>";
  }
  mysqli_close($conn);
  ?>
</select>
<br>
<label for="available_quantity" style="display: none;">Available Quantity (Kg)</label>
<input type="text" id="available_quantity" readonly style="display: none;">
<br>

      
        <label for="quantity">Quantity(Kg)</label>
        <input type="number" id="quantity" name="quantity" min="0.10" step="0.01" placeholder="Enter quantity" required>
        <br>
        <div id="messageDiv"></div> <!-- Add this div to display the message -->
      
        <button id="addToList" type="submit">Add to list</button>
        <button onclick="clearInputs()">Clear All</button>
      </form>
    </main>
  </div>
  <div id="preheader">
  <h1>Previously added products to list</h1>
  </div>
  <div class="container" id="distributionList">
    <!--fetched from functios.js file-->
      <br>
         <table id="distributionList">
            <thead>
              <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity(Kg)</th>
                <th>Price(Kg)</th>
                <th>Total Price</th>
                <th>Hostel</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
    </div>
</div>
<div id="toast"></div>
</body>
<script type="text/javascript">
  $(".chosen").chosen();
  </script>
</html>
 <!-- Call the handleTabSwitch function to activate tab switch handling -->
 <script>
    handleTabSwitch();
  </script>
  <!--
<script>
  function fetchAvailableQuantity() {
    var selectedProduct = $("#product_name").val();
    $.ajax({
      url: "get_available_quantity.php",
      method: "GET",
      data: { product_name: selectedProduct },
      success: function (data) {
        if (data !== null) {
          if (parseFloat(data) > 0) {
            $("#available_quantity").val(data);
            $("label[for='available_quantity']").show(); // Show the label
            $("#available_quantity").show(); // Show the textbox
            $("#messageDiv").hide(); // Show the textbox
            var isDisabled = false;
            setButtonDisabled(isDisabled);
          } else {
            showMessage(selectedProduct + " is Out of Stock","error");
            // Check if the button should be disabled based on some conditions (Example: isDisabled)
            var isDisabled = true;
            setButtonDisabled(isDisabled);
            $("#available_quantity").val(""); // Clear the textbox
        $("label[for='available_quantity']").hide(); // Hide the label
        $("#available_quantity").hide(); // Hide the textbox
        $("#messageDiv").show(); // Show the textbox
          }
        } else {
          $("#available_quantity").val(""); // Clear the textbox
          $("label[for='available_quantity']").hide(); // Hide the label
          $("#available_quantity").hide(); // Hide the textbox
          $("#messageDiv").show(); // Show the textbox
          var isDisabled = false;
            setButtonDisabled(isDisabled);
        }
      },
      error: function () {
        $("#available_quantity").val(""); // Clear the textbox
        $("label[for='available_quantity']").hide(); // Hide the label
        $("#available_quantity").hide(); // Hide the textbox
        $("#messageDiv").show(); // Show the textbox
        var isDisabled = false;
            setButtonDisabled(isDisabled);
      }
    });
  }
</script>
-->
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

  function setButtonDisabled(disabled) {
    console.log("Button disabled status: " + disabled);
      var addToList = $("#addToList");
      if (disabled) {
        addToList.addClass("disabled-button");
        addToList.prop("disabled", true);
      } else {
        addToList.removeClass("disabled-button");
        addToList.prop("disabled", false);
      }
    }
</script>

<style>
  #available_quantity {
    color:"white";
  }
  #quantity {
    background-color: white;
  }
  #quantity.invalid {
    background-color: red; /* Light red background color for invalid quantity */
  }
  #addToList.disabled-button {
    pointer-events: none; /* Disable button click */
    opacity: 0.5; /* Dim the button */
  }
</style>
<!--
<script>
  function fetchAvailableQuantity() {
    var selectedProduct = $("#product_name").val();
    $.ajax({
      url: "get_available_quantity.php",
      method: "GET",
      data: { product_name: selectedProduct },
      success: function (data) {
        if (data !== null) {
          var availableQuantity = parseFloat(data);
          var quantityInput = $("#quantity");
          if (availableQuantity > 0) {
            $("#available_quantity").val(availableQuantity);
            $("label[for='available_quantity']").show(); // Show the label
            $("#available_quantity").show(); // Show the textbox
            $("#available_quantity").css("background-color", "green"); // Set the border to green if available
            quantityInput.prop("disabled", false); // Enable quantity field
            quantityInput.removeClass("invalid"); // Remove the invalid class from quantity
            $("#addToList").removeClass("disabled-button"); // Enable the "Add to list" button
            var enteredQuantity = parseFloat(quantityInput.val());
            if (enteredQuantity > availableQuantity) {
              quantityInput.addClass("invalid"); // Mark quantity as invalid
              $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
            } else {
              quantityInput.removeClass("invalid"); // Remove the invalid class from quantity
            }
          } else {
            $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
            quantityInput.prop("disabled", true); // Disable quantity field
            $("#available_quantity").val("Out of Stock");
            $("label[for='available_quantity']").show(); // Show the label
            $("#available_quantity").show(); // Show the textbox
            quantityInput.addClass("invalid"); // Mark quantity as invalid
            $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
        } else {
          $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
          quantityInput.prop("disabled", true); // Disable quantity field
          $("#available_quantity").val("Out of Stock");
          $("label[for='available_quantity']").show(); // Show the label
          $("#available_quantity").show(); // Show the textbox
          quantityInput.addClass("invalid"); // Mark quantity as invalid
          $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
      },
      error: function () {
        $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
        quantityInput.prop("disabled", true); // Disable quantity field
        $("#available_quantity").val("Out of Stock");
        $("label[for='available_quantity']").show(); // Show the label
        $("#available_quantity").show(); // Show the textbox
        quantityInput.addClass("invalid"); // Mark quantity as invalid
        $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
    });
  }

</script>
-->

<script>
    $(document).ready(function () {
      const $quantityInput = $("#quantity");
      const $availableQuantity = $("#available_quantity");
      const $addToListButton = $("#addToList");
      

    function fetchAvailableQuantity() {
    var selectedProduct = $("#product_name").val();
    if (selectedProduct) {
    $.ajax({
      url: "get_available_quantity.php",
      method: "GET",
      data: { product_name: selectedProduct },
      success: function (data) {
        if (data !== null) {
          var availableQuantity = parseFloat(data);
          var quantityInput = $("#quantity");
          if (availableQuantity > 0) {
            $("#available_quantity").val(availableQuantity);
            $("label[for='available_quantity']").show(); // Show the label
            $("#available_quantity").show(); // Show the textbox
            $("#messageDiv").hide(); // hide the textbox
            $("#available_quantity").css("background-color", "yellow"); // Set the border to green if available
            quantityInput.prop("disabled", false); // Enable quantity field
            quantityInput.removeClass("invalid"); // Remove the invalid class from quantity
            $("#addToList").removeClass("disabled-button"); // Enable the "Add to list" button
            var enteredQuantity = parseFloat(quantityInput.val());
            if (enteredQuantity > availableQuantity) {
              quantityInput.addClass("invalid"); // Mark quantity as invalid
              $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
            } else {
              quantityInput.removeClass("invalid"); // Remove the invalid class from quantity
            }
          } else {
            $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
            quantityInput.prop("disabled", true); // Disable quantity field
            $("#available_quantity").val("Out of Stock");
            $("label[for='available_quantity']").show(); // Show the label
            $("#available_quantity").show(); // Show the textbox
            quantityInput.addClass("invalid"); // Mark quantity as invalid
            $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
        } else {
          $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
          quantityInput.prop("disabled", true); // Disable quantity field
          $("#available_quantity").val("Out of Stock");
          $("label[for='available_quantity']").show(); // Show the label
          $("#available_quantity").show(); // Show the textbox
          quantityInput.addClass("invalid"); // Mark quantity as invalid
          $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
      },
      error: function () {
        $("#available_quantity").css("background-color", "red"); // Set the border to red if not available
        quantityInput.prop("disabled", true); // Disable quantity field
        $("#available_quantity").val("Error");
        $("label[for='available_quantity']").show(); // Show the label
        $("#available_quantity").show(); // Show the textbox
        quantityInput.addClass("invalid"); // Mark quantity as invalid
        $("#addToList").addClass("disabled-button"); // Disable the "Add to list" button
          }
    });
  }
  else {
        // Handle the case when no product is selected
        $("#available_quantity").css("background-color", "transparent");
        $("#available_quantity").val("");
        $("label[for='available_quantity']").hide();
        $("#available_quantity").hide();
        $quantityInput.prop("disabled", true);
        $quantityInput.val("");
        $addToListButton.addClass("disabled-button");
      }
  }

      // Add real-time validation on quantity input
      $quantityInput.on("input", fetchAvailableQuantity);
      $("#product_name").on("change", fetchAvailableQuantity);

      // Trigger the product change event once on page load to fetch initial quantity
      //$("#product_name").trigger("change");
      // Fetch initial quantity when the page loads
      fetchAvailableQuantity();
    });
  </script>