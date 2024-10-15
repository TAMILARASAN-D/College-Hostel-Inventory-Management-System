/*
function goHosteluserHome(){
  //location.replace("index.php");
  clearInputs();
  history.go(-1);
}

function clearInputs() {
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].value = '';
    }
  }
  
Include the following JavaScript function for displaying the table in entry page 
$(document).ready(function(){
  $.ajax({
    url: 'purchaseList.php',
    type: 'post',
    dataType: 'json',
    success: function(response){
      var len = response.length;
      for(var i=0; i<len; i++){
        var date = response[i].date;
        var item_name = response[i].item_name;
        var quantity = response[i].quantity;
        var price = response[i].price;
        var html = '<tr>';
        html += '<td>'+date+'</td>';
        html += '<td>'+item_name+'</td>';
        html += '<td>'+quantity+'</td>';
        html += '<td>'+price+'</td>';
        html += '</tr>';
        $('#purchaseList tbody').append(html);
      }
    }
  });
});

//for distribution input page


$(document).ready(function() {
  // Fetch purchase list on page load
  fetchDistributionList();

  // Submit form data via AJAX
  $('#distributionForm').submit(function(e) {
    e.preventDefault(); // prevent the form from submitting normally

    // get the form data
    var formData = {
      'date' : $('input[name=date]').val(),
      'product_name' : $('select[name=product_name]').val(),
      'quantity' : $('input[name=quantity]').val(),
      'hostel' : $('select[name=hostel]').val()
    };

    // send the AJAX request
    $.ajax({
      type : 'POST',
      url : 'update_items_process.php',
      data : formData,
      dataType : 'json',
      encode : true
    })
    .done(function(data) {
      // show success message
      showToast(data.message, 'success');
      // clear the form
      $('#distributionForm').trigger('reset');
      // update purchase list
      fetchDistributionList();
    })
    .fail(function(data) {
      // show error message
      showToast(data.responseJSON.message, 'error');
    });
  });
});

//to add purchase items using ajax

$(document).ready(function() {
  // Fetch purchase list on page load
  fetchPurchaseList();

  // Submit form data via AJAX
  $('#purchaseForm').submit(function(e) {
    e.preventDefault(); // prevent the form from submitting normally

    // get the form data
    var formData = {
      'date' : $('input[name=date]').val(),
      'product_name' : $('select[name=product_name]').val(),
      'quantity' : $('input[name=quantity]').val(),
      'price' : $('input[name=price]').val(),
      'custom_product_name' : $('input[name=custom_product_name]').val()// Add this line to include the custom_product_name field
    };

    // send the AJAX request
    $.ajax({
      type : 'POST',
      url : 'add_items_process.php',
      data : formData,
      dataType : 'json',
      encode : true
    })
    .done(function(data) {
      // show success message
      showToast(data.message, 'success');
      // clear the form
      $('#purchaseForm').trigger('reset');
      //$('#purchaseForm')[0].reset();
      // update purchase list
      fetchPurchaseList();
    })
    .fail(function(data) {
      // show error message
      showToast(data.responseJSON.message, 'error');
    });
  });
});

function fetchPurchaseList() {
  // send the AJAX request to fetch purchase list
  $.ajax({
    url: 'purchaseList.php',
    type: 'post',
    dataType: 'json',
    success: function(response) {
      var len = response.length;
      var html = '';
      for(var i=0; i<len; i++) {
        //var date = response[i].date;
        var date1 = new Date(response[i].date);
        var formatted_date = ('0' + date1.getDate()).slice(-2) + '-' + ('0' + (date1.getMonth()+1)).slice(-2) + '-' + date1.getFullYear();
        var item_name = response[i].item_name;
        var quantity = response[i].quantity;
        var price = response[i].price;
        var total_price = (price * quantity).toFixed(2);
        html += '<tr>';
        html += '<td>'+formatted_date+'</td>';
        html += '<td>'+item_name+'</td>';
        html += '<td>'+quantity+'</td>';
        html += '<td>'+price+'</td>';
        html += '<td>'+total_price+'</td>';
        html += '</tr>';
      }
      $('#purchaseList tbody').html(html);
    }
  });
}
function showToast(message, type) {
  var toast = document.getElementById("toast");
  toast.innerText = message;
  toast.className = "show " + type;
  setTimeout(function() { 
    toast.className = toast.className.replace("show", ""); 
  }, 2000);
}
function fetchDistributionList() {
  // send the AJAX request to fetch purchase list
  $.ajax({
    url: 'DistributionList.php',
    type: 'post',
    dataType: 'json',
    success: function(response) {
      var len = response.length;
      var html = '';
      for(var i=0; i<len; i++) {
        //var date = response[i].date;
        var date1 = new Date(response[i].date);
        var formatted_date = ('0' + date1.getDate()).slice(-2) + '-' + ('0' + (date1.getMonth()+1)).slice(-2) + '-' + date1.getFullYear();
        var item_name = response[i].item_name;
        var quantity = response[i].quantity;
        var hostel = response[i].hostel;
        html += '<tr>';
        html += '<td>'+formatted_date+'</td>';
        html += '<td>'+item_name+'</td>';
        html += '<td>'+quantity+'</td>';
        html += '<td>'+hostel+'</td>';
        html += '</tr>';
      }
      $('#distributionList tbody').html(html);
    }
  });
}

//to set current date
// Get the date input field
var dateInput = document.getElementById("date");

// Add an onchange event listener to the date input field
dateInput.addEventListener("change", function() {
  // Get the current date
  var currentDate = new Date();

  // Get the date string in YYYY-MM-DD format
  var dateString = currentDate.toISOString().slice(0, 10);

  // Make an AJAX request to update the date on the server
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Update the date input field with the new date
        dateInput.value = dateString;
      } else {
        console.log("Error: " + xhr.status);
      }
    }
  };
  xhr.open("POST", "current_date.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("date=" + dateString);
});
*/
function goHosteluserHome() {
  clearInputs();
  history.go(-1);
}

function clearInputs() {
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].value = '';
  }
}
function clearInputsDistribution() {
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].value = '';
  }
  $("#available_quantity").val(""); // Clear the textbox
        $("label[for='available_quantity']").hide(); // Hide the label
        $("#available_quantity").hide(); // Hide the textbox
}
$(document).ready(function() {
  // Fetch purchase list on page load
  fetchDistributionList();

  // Submit form data via AJAX
  $('#distributionForm').submit(function(e) {
    e.preventDefault(); // prevent the form from submitting normally

    // get the form data
    var formData = {
      'date' : $('input[name=date]').val(),
      'product_name' : $('select[name=product_name]').val(),
      'quantity' : $('input[name=quantity]').val(),
      'hostel' : $('select[name=hostel]').val()
    };

    // send the AJAX request
    $.ajax({
      type : 'POST',
      url : 'update_items_process_new.php',
      data : formData,
      dataType : 'json',
    success: function(data) {
      console.log('AJAX request successful');
      // show success message
      // Handle the response based on success flag
      if (data.success) {
        // Show success message
        showMessage(data.message, true);
      } else {
        // Show error message
        showMessage(data.message, false);
      }
      //showMessage(data.message, 'success');
      $("#available_quantity").val(""); // Clear the textbox
      $("label[for='available_quantity']").hide(); // Hide the label
      $("#available_quantity").hide(); // Hide the textbox
      //showToast(data.message, 'success');
      $(".chosen").trigger("chosen:updated"); // update the chosen plugin
      $("#product_name").val(''); // reset the dropdown selection
      $("#product_name").trigger("chosen:updated"); // update the chosen plugin after resetting the selection
      $("#hostel").val(''); // reset the dropdown selection
      $("#hostel").trigger("chosen:updated"); // update the chosen plugin after resetting the selection
      // clear the form
      $('#distributionForm')[0].reset();
      // update purchase list
      fetchDistributionList();
    },
    error: function(xhr, status, error) {
        try {
          var response = JSON.parse(xhr.responseText);
          console.log('Error response:', response);
          showMessage(response.message, false);
          //showToast(response.message, 'error');
        } catch (e) {
          console.log('Error parsing response:', xhr.responseText);
          showMessage('An error occurred', false);
          //showToast('An error occurred', 'error');
        }
      }
  });
});
});

$(document).ready(function() {
  // Fetch purchase list on page load
  fetchPurchaseList();

  // Initialize Chosen plugin
  //$('.chosen').chosen();

  // Submit form data via AJAX
  $('#purchaseForm').submit(function(e) {
    e.preventDefault(); // prevent the form from submitting normally
    console.log('Form submitted');

    // get the form data
    var formData = {
      'date': $('#date').val(),
      'product_name': $('#product_name option:selected').val(),
      'quantity': $('#quantity').val(),
      'price': $('#price').val(),
      'custom_product_name': $('#custom_product_name').val()
    };

    // send the AJAX request
    $.ajax({
      type: 'POST',
      url: 'add_items_process_new.php',
      data: formData,
      dataType: 'json',
      success: function(data) {
        console.log('AJAX request successful');
        // show success message
        if (data.success) {
          // Show success message
          showMessage(data.message, true);
        } else {
          // Show error message
          showMessage(data.message, false);
        }
        //showMessage(data.message,'success');
        //showToast(data.message, 'success');
        
      
        // Clear and reset the form
        $('#purchaseForm')[0].reset();

        // Reset the product_name dropdown
        $('#product_name').val('').trigger('chosen:updated');
        
        $('#custom_product_name').remove();
        $('.chosen-container').show();
        $(".chosen").trigger("chosen:updated");

        // Update purchase list
        fetchPurchaseList();
        
/*
      $('#custom_product_name').remove();
      $('#product_name').next('.chosen-container').show();
      $('#product_name').next('.chosen-container').next('.chosen-container').show(); // show the search dropdown
      $('#product_name').next('.chosen-container').find('.chosen-single').removeClass('chosen-hidden');
      $('#product_name').next('.chosen-container').find('.chosen-single').removeClass('chosen-with-drop');
      $(".chosen").trigger("chosen:updated"); // update the chosen plugin
      $("#product_name").val(''); // reset the dropdown selection
      $("#product_name").trigger("chosen:updated"); // update the chosen plugin after resetting the selection
*/      

  
      },
      error: function(xhr, status, error) {
        try {
          var response = JSON.parse(xhr.responseText);
          console.log('Error response:', response);
          showMessage(response.message, false);
          //showToast(response.message, 'error');
        } catch (e) {
          console.log('Error parsing response:', xhr.responseText);
          showMessage('An error occurred', false);
          //showToast('An error occurred', 'error');
        }
      }
      
    });
  });

  /* Handle product_name change event
  $('#product_name').change(function() {
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
  });*/
});

function fetchPurchaseList() {
  // send the AJAX request to fetch purchase list
  $.ajax({
    url: 'purchaseList.php',
    type: 'post',
    dataType: 'json',
    success: function(response) {
      var html = '';
      for (var i = 0; i < response.length; i++) {
        var date1 = new Date(response[i].date);
        var formatted_date = ('0' + date1.getDate()).slice(-2) + '-' + ('0' + (date1.getMonth() + 1)).slice(-2) + '-' + date1.getFullYear();
        var item_name = response[i].item_name;
        var quantity = response[i].quantity;
        var price = response[i].price;
        var total_price = (price * quantity).toFixed(2);
        html += '<tr>';
        html += '<td>' + formatted_date + '</td>';
        html += '<td>' + item_name + '</td>';
        html += '<td>' + quantity + '</td>';
        html += '<td>' + price + '</td>';
        html += '<td>' + total_price + '</td>';
        html += '</tr>';
      }
      $('#purchaseList tbody').html(html);

    }
  });
}

function showToast(message, type) {
  console.log('showToast called');
  var toast = document.getElementById("toast");
  toast.innerText = message;
  toast.className = "show " + type;
  setTimeout(function() {
    toast.className = toast.className.replace("show", "");
  }, 2000);
}

function fetchDistributionList() {
  // send the AJAX request to fetch purchase list
  $.ajax({
    url: 'distributionList.php',
    type: 'post',
    dataType: 'json',
    success: function(response) {
      var html = '';
      for (var i = 0; i < response.length; i++) {
        var date1 = new Date(response[i].date);
        var formatted_date = ('0' + date1.getDate()).slice(-2) + '-' + ('0' + (date1.getMonth() + 1)).slice(-2) + '-' + date1.getFullYear();
        var item_name = response[i].item_name;
        var quantity = response[i].quantity;
        var price = response[i].price;
        var hostel = response[i].hostel;
        var total_price = (price * quantity).toFixed(2);
        html += '<tr>';
        html += '<td>' + formatted_date + '</td>';
        html += '<td>' + item_name + '</td>';
        html += '<td>' + quantity + '</td>';
        html += '<td>' + price + '</td>';
        html += '<td>' + total_price + '</td>';
        html += '<td>' + hostel + '</td>';
        html += '</tr>';
      }
      $('#distributionList tbody').html(html);
    }
  });
}

function handleTabSwitch() {
        var hidden, visibilityChange;

        if (typeof document.hidden !== "undefined") {
            hidden = "hidden";
            visibilityChange = "visibilitychange";
        } else if (typeof document.msHidden !== "undefined") {
            hidden = "msHidden";
            visibilityChange = "msvisibilitychange";
        } else if (typeof document.webkitHidden !== "undefined") {
            hidden = "webkitHidden";
            visibilityChange = "webkitvisibilitychange";
        }

        function handleVisibilityChange() {
            if (document[hidden]) {
                // User has switched to another tab or minimized the window
                // You can consider this as a logout event and perform the necessary actions
                var request = new XMLHttpRequest();
                request.open('GET', 'logout.php', false);
                request.send();
            } else {
                // User has switched back to the tab or maximized the window
                // You can perform any desired actions here
                // Refresh the page
                location.reload();
                header("Location: index.php");
                exit();
            }
        }

        document.addEventListener(visibilityChange, handleVisibilityChange, false);

        // Handle form submission
  var logoutForm = document.getElementById('logout-form');
  if (logoutForm) {
    logoutForm.addEventListener('submit', function(e) {
      // Remove event listener and allow the form submission to proceed
      document.removeEventListener(visibilityChange, handleVisibilityChange, false);
    });
  }
}
