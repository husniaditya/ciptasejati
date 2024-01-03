
// ----- Function to display notification ----- //
// Success notification
function SuccessNotification(text) {
  $.gritter.add({
    title: 'Saved',
    text: text,
    time: 5000,
    image: '../image/notification/success.png',
    class_name: 'gritter-success',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}

// Update notification
function UpdateNotification(text) {
  $.gritter.add({
    title: 'Updated',
    text: text,
    time: 5000,
    image: '../image/notification/success.png',
    class_name: 'gritter-update',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}

// Info notification
function infoNotification(text) {
  $.gritter.add({
    title: 'Information',
    text: text,
    time: 5000,
    image: '../image/notification/info.png',
    class_name: 'gritter-update',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}

// Delete notification
function DeleteNotification(text) {
  $.gritter.add({
    title: 'Deleted',
    text: 'Data berhasil dihapus',
    time: 5000,
    image: '../image/notification/delete.png',
    class_name: 'gritter-delete',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}

// Failed notification
function FailedNotification(text) {
  $.gritter.add({
    title: 'Error',
    text: text,
    time: 5000,
    image: '../image/notification/failed.png',
    class_name: 'gritter-error',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}
// Mail notification
function MailNotification(text) {
  $.gritter.add({
    title: 'Email Terkirim',
    text: text,
    time: 5000,
    image: '../image/notification/mail.png',
    class_name: 'gritter-update',
    // (function) before the gritter notice is opened
    'before_open': function () {
      if ($('.gritter-item-wrapper').length === 3) {
          // Returning false prevents a new gritter from opening
          return false;
      }
  }
  });
}
// ----- End of function to display notification ----- //

// ----- Function to loading overlay ----- //
function showLoadingOverlay() {
  $('#loading-overlay').fadeIn();
}

function hideLoadingOverlay() {
  $('#loading-overlay').remove();
}
// ----- End of function to loading overlay ----- //

// ----- Function to refresh map iframe ----- //
function refreshIframe(value) {
  var iframe = document.getElementById(value);
  iframe.src = iframe.src;
}
// ----- End of function to refresh map iframe ----- //

// ----- Debounce function ----- //
function debounce(func, delay) {
  let timeoutId;

  return function() {
    clearTimeout(timeoutId);

    timeoutId = setTimeout(() => {
      func.apply(this, arguments);
    }, delay);
  };
}
// ----- End of debounce function ----- //


// ----- Clear form fields when the modal is hidden ----- //
$(document).ready(function() {
  // Clear form fields when the modal is hidden
  $('.modal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
  });
});
// ----- End of Clear form fields when the modal is hidden ----- //


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
      url: 'module/backend/t_changepassword.php',
      data: formData,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          SuccessNotification('Password berhasil diubah!');
          
          // Close the modal
          $('#ChangePassword').modal('hide');
          
        } else {
          // Display error notification
          FailedNotification(response);
        }
      }
    });
    console.log(formData);
  });
});
// ----- End of Change Password Section ----- //


