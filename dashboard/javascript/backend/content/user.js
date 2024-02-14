// ----- Change Password Section ----- //
$(document).ready(function() {
    $('#changepassword-form').submit(function(event) {
      event.preventDefault(); // Prevent the default form submission
  
      var formData = $(this).serialize(); // Serialize form data
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
      // Manually add the button name or ID to the serialized data
      formData += '&' + encodeURIComponent(buttonId) + '=' + encodeURIComponent('clicked');
  
  
      $.ajax({
        type: 'POST',
        url: 'module/backend/loginregister/t_changepassword.php',
        data: formData,
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Password changed successfully!');
            
            // Close the modal
            $('#ChangePassword').modal('hide');
            
          } else {
            // Display error notification
            FailedNotification(response);
          }
        }
      });
      // console.log(formData);
    });
  });
  // ----- End of Change Password Section ----- //