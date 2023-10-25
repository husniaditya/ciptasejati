


// Guest Table
$(document).ready(function() {
    $('#guesttable-tools').DataTable({
      responsive: true,
      columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '300px', targets: 2 }, // Set width for column 3
        { width: '450px', targets: 3 }, // Set width for column 4
        { width: '200px', targets: 4 }, // Set width for column 5
        { width: '150px', targets: 5 }, // Set width for column 6
        // Add more columnDefs as needed
      ],
      dom: 'Bfrtip',
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  } );




// ----- Start of Guest Section ----- //
$(document).ready(function() {
    // add guest
    $('#addguest-form').submit(function(event) {
      event.preventDefault(); // Prevent the default form submission
  
      var name = document.getElementById("GUEST_NAME").value;
    
      var formData = new FormData($(this)[0]); // Create FormData object from the form
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
    
      // Manually add the button name or ID to the serialized data
      formData.append(buttonId, 'clicked');
  
      if (name !== '') {
        $.ajax({
          type: 'POST',
          url: 'module/backend/t_guest.php',
          data: formData,
          processData: false, // Prevent jQuery from processing the data
          contentType: false, // Prevent jQuery from setting content type
          success: function(response) {
            // Check the response from the server
            if (response === 'Success') {
              // Display success notification
              SuccessNotification('Data saved successfully!');
              
              // Close the modal
              $('#AddGuest').modal('hide');
      
              // Call the reloadDataTable() function after inserting data to reload the DataTable
              $.ajax({
                type: 'GET',
                url: 'module/ajax/guest/aj_getguest.php',
                success: function(response) {
                  $("#guestdata").html(response);
                },
                error: function(xhr, status, error) {
                  // Handle any errors
                }
              });
            } else {
              // Display error notification
              FailedNotification(response);
            }
          },
          error: function(xhr, status, error) {
            // Handle any errors
          }
        });
      } else {
        // Display error notification
        FailedNotification('Please fill in all the required fields!');
      }
    });  
  
    // edit guest
    $('#editguest-form').submit(function(event) {
      event.preventDefault(); // Prevent the default form submission
  
      var name = document.getElementById("editGUEST_NAME").value;
    
      var formData = new FormData($(this)[0]); // Create FormData object from the form
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
    
      // Manually add the button name or ID to the serialized data
      formData.append(buttonId, 'clicked');
  
      if (name !== '') {
        $.ajax({
          type: 'POST',
          url: 'module/backend/t_guest.php',
          data: formData,
          processData: false, // Prevent jQuery from processing the data
          contentType: false, // Prevent jQuery from setting content type
          success: function(response) {
            // Check the response from the server
            if (response === 'Success') {
              // Display success notification
              UpdateNotification('Data updated successfully!');
              
              // Close the modal
              $('#EditGuest').modal('hide');
      
              // Call the reloadDataTable() function after inserting data to reload the DataTable
              $.ajax({
                type: 'GET',
                url: 'module/ajax/guest/aj_getguest.php',
                success: function(response) {
                  $("#guestdata").html(response);
                },
                error: function(xhr, status, error) {
                  // Handle any errors
                }
              });
            } else {
              // Display error notification
              FailedNotification(response);
            }
          },
          error: function(xhr, status, error) {
            // Handle any errors
          }
        });
      } else {
        // Display error notification
        FailedNotification('Please fill in all the required fields!');
      }
      // console.log(formData);
    });  
  });
  
  // Delete guest
  function GuestconfirmAndPost(value1,value2) {
    // Ask for confirmation
    if (confirm("Are you sure you want to delete this data?")) {
      // Create the data object
      var eventdata = {
        GUEST_ID: value1,
        EVENT_ACTION: value2
      };
  
      // Perform the AJAX request
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_guest.php',
        data: eventdata,
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            DeleteNotification('Data deleted successfully!');
            
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/guest/aj_getguest.php',
              success: function(response) {
                  $("#guestdata").html(response);
              },
              error: function(xhr, status, error) {
                // Handle any errors
              }
            });
  
          } else {
            // Display error notification
            FailedNotification(response);
          }
        },
        error: function(xhr, status, error) {
          console.error('Request failed. Status: ' + xhr.status);
        }
      });
      // console.log(response);
    }
  }
  
  // View guest
  $(document).on("click", ".open-ViewGuest", function () {
    var name = $(this).data('name');
    var address = $(this).data('address');
    var phone = $(this).data('phone');
    var relation = $(this).data('relation');
    $(".modal-body #viewGUEST_NAME").val( name );
    $(".modal-body #viewGUEST_ADDRESS").val( address );
    $(".modal-body #viewGUEST_PHONE").val( phone );
    $(".modal-body #viewGUEST_RELATION").val( relation );
    
    // console.log(map);
  });
  
  // Edit guest
  $(document).on("click", ".open-EditGuest", function () {
    var guestid = $(this).data('guestid');
    var name = $(this).data('name');
    var address = $(this).data('address');
    var phone = $(this).data('phone');
    var relation = $(this).data('relation');
    $(".modal-body #editGUEST_ID").val( guestid );
    $(".modal-body #editGUEST_NAME").val( name );
    $(".modal-body #editGUEST_ADDRESS").val( address );
    $(".modal-body #editGUEST_PHONE").val( phone );
    $(".modal-body #editGUEST_RELATION").val( relation );
    
    // console.log(map);
  });
  
  // Guest Filtering
  // Attach debounced event handler to form inputs
  $('.filterGuest input').on('input', debounce(filterGuestEvent, 500));
  function filterGuestEvent() {
    // Your event handling code here
    const name = $('#filterGUEST_NAME').val();
    const address = $('#filterGUEST_ADDRESS').val();
    const phone = $('#filterGUEST_PHONE').val();
    const relation = $('#filterGUEST_RELATION').val();
  
    // Create a data object to hold the form data
    const formData = {
      GUEST_NAME: name,
      GUEST_ADDRESS: address,
      GUEST_PHONE: phone,
      GUEST_RELATION: relation
    };
  
    $.ajax({
      type: "POST",
      url: "module/ajax/guest/aj_getguest.php",
      data: formData,
      success: function(data){
        $("#guestdata").html(data);
      }
    });
    // console.log(formData);
  }
  
  // ----- Function to reset form ----- //
  function clearForm() {
    document.getElementById("filterGuest").reset();
    reloadGuestTable();
  }
  // ----- End of function to reset form ----- //
  
  function reloadGuestTable() {
    $.ajax({
      type: "POST",
      url: "module/ajax/guest/aj_getguest.php",
      data: '',
      success: function(data){
        $("#guestdata").html(data);
      }
    });
  }
  // ----- End of Guest Section ----- //