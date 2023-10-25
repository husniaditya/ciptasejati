
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

// Delete notification
function DeleteNotification(text) {
  $.gritter.add({
    title: 'Deleted',
    text: text,
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
// ----- End of function to display notification ----- //

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
          SuccessNotification('Password changed successfully!');
          
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


// ----- Event Section ----- //
// Add & Edit event
$(document).ready(function() {
  // add event
  $('#addevent-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var title = document.getElementById("EVENT_TITLE").value;
    var datetime = document.getElementById("datetime-picker").value;
    var desc = document.getElementById("EVENT_DESC").value;
    var location = document.getElementById("EVENT_LOCATION").value;
    var map = document.getElementById("EVENT_MAP").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    var fileInput = document.getElementById("EVENT_FILE");
    var files = fileInput.files;
  
    // Append each file to the FormData
    for (var i = 0; i < files.length; i++) {
      formData.append("files[]", files[i]);
    }
  
    // Manually add the button name or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (title !== '' && datetime !== '' && desc !== '' && location !== '' && map !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_weddingevents.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Data saved successfully!');
            
            // Close the modal
            $('#AddEvent').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/event/aj_getevent.php',
              success: function(response) {
                $("#eventdata").html(response);
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

  // edit event
  $('#editevent-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var title = document.getElementById("editEVENT_TITLE").value;
    var datetime = document.getElementById("datetime-pickerEdit").value;
    var desc = document.getElementById("editEVENT_DESC").value;
    var location = document.getElementById("editEVENT_LOCATION").value;
    var map = document.getElementById("editEVENT_MAP").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    var fileInput = document.getElementById("EVENT_FILE");
    var files = fileInput.files;
  
    // Append each file to the FormData
    for (var i = 0; i < files.length; i++) {
      formData.append("files[]", files[i]);
    }
  
    // Manually add the button name or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (title !== '' && datetime !== '' && desc !== '' && location !== '' && map !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_weddingevents.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            UpdateNotification('Data updated successfully!');
            
            // Close the modal
            $('#EditEvent').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/event/aj_getevent.php',
              success: function(response) {
                $("#eventdata").html(response);
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

// Delete event
function confirmAndPost(value1,value2) {
  // Ask for confirmation
  if (confirm("Are you sure you want to delete this data?")) {
    // Create the data object
    var eventdata = {
      EVENT_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/t_weddingevents.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data deleted successfully!');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'GET',
            url: 'module/ajax/event/aj_getevent.php',
            success: function(response) {
                $("#eventdata").html(response);
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
    // console.log(eventdata);
  }
}

// View event
$(document).on("click", ".open-ViewEvent", function () {
  var eventid = $(this).data('eventid');
  var title = $(this).data('title');
  var location = $(this).data('location');
  var datetime = $(this).data('datetime');
  var desc = $(this).data('desc');
  var map = $(this).data('map');
  var eventstatus = $(this).data('eventstatus');
  $(".modal-body #EVENT_ID2").val( eventid );
  $(".modal-body #EVENT_TITLE2").val( title );
  $(".modal-body #EVENT_LOCATION2").val( location );
  $(".modal-body #EVENT_DATETIME2").val( datetime );
  $(".modal-body #EVENT_DESC2").val( desc );
  $(".modal-body #EVENT_MAP2").val( map );
  $(".modal-body #EVENT_STATUS2").val( eventstatus );

  $.ajax({
    type: "POST",
    url: "module/ajax/event/aj_event_loadphoto.php",
    data:'EVENT_ID='+eventid,
    success: function(data){
      $("#loadphotoview").html(data);
    }
  });

  // Set the source URL to the iframe
  document.getElementById('EventMapView').src = map;
  
  // console.log(map);
});

// Edit event
$(document).on("click", ".open-EditEvent", function () {
  var eventid = $(this).data('eventid');
  var title = $(this).data('title');
  var location = $(this).data('location');
  var datetime = $(this).data('datetime');
  var desc = $(this).data('desc');
  var map = $(this).data('map');
  var eventstatus = $(this).data('eventstatus');
  $(".modal-body #editEVENT_ID").val( eventid );
  $(".modal-body #editEVENT_TITLE").val( title );
  $(".modal-body #editEVENT_LOCATION").val( location );
  $(".modal-body #datetime-pickerEdit").val( datetime );
  $(".modal-body #editEVENT_DESC").val( desc );
  $(".modal-body #editEVENT_MAP").val( map );
  $(".modal-body #editEVENT_STATUS").val( eventstatus );

  $.ajax({
    type: "POST",
    url: "module/ajax/event/aj_event_loadphoto.php",
    data:'EVENT_ID='+eventid,
    success: function(data){
      $("#loadphotoedit").html(data);
    }
    });

  // Set the source URL to the iframe
  document.getElementById('EventMapEdit').src = map;
  
  // console.log(map);
});

// Event Filtering
$('.filterEvent input').on('input', debounce(filterEvent, 500));
function filterEvent() {
  // Your event handling code here
  const title = $('#filterEVENT_TITLE').val();
  const location = $('#filterEVENT_LOCATION').val();
  const date = $('#datepicker1').val();
  const desc = $('#filterEVENT_DESC').val();

  // Create a data object to hold the form data
  const formData = {
    EVENT_TITLE: title,
    EVENT_LOCATION: location,
    EVENT_DATE: date,
    EVENT_DESC: desc
  };

  $.ajax({
    type: "POST",
    url: "module/ajax/event/aj_getevent.php",
    data: formData,
    success: function(data){
      $("#eventdata").html(data);
    }
  });
}

function clearEventForm() {
  document.getElementById("filterEvent").reset();
  reloadEventTable();
}

function reloadEventTable() {
  $.ajax({
    type: "POST",
    url: "module/ajax/event/aj_getevent.php",
    data: '',
    success: function(data){
      $("#eventdata").html(data);
    }
  });
}

// ----- End of Event Section ----- //


// ----- Start of Announcement Section ----- //
$(document).ready(function() {
  // add Announcement
  $('#addAnnouncement-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var title = document.getElementById("ANNOUNCEMENT_TITLE").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (title !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_announcement.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Data saved successfully!');
            
            // Close the modal
            $('#AddAnnouncement').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/announcement/aj_getannouncement.php',
              success: function(response) {
                $("#anndata").html(response);
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

  // edit Announcement
  $('#editannouncement-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var title = document.getElementById("editANNOUNCEMENT_TITLE").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (title !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_announcement.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            UpdateNotification('Data updated successfully!');
            
            // Close the modal
            $('#EditAnnouncement').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/announcement/aj_getannouncement.php',
              success: function(response) {
                $("#anndata").html(response);
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

// Delete Announcement
function AnnconfirmAndPost(value1,value2) {
  // Ask for confirmation
  if (confirm("Are you sure you want to delete this data?")) {
    // Create the data object
    var eventdata = {
      ANNOUNCEMENT_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/t_announcement.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data deleted successfully!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'GET',
            url: 'module/ajax/announcement/aj_getannouncement.php',
            success: function(response) {
                $("#anndata").html(response);
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

// View Announcement
$(document).on("click", ".open-ViewAnnouncement", function () {
  var title = $(this).data('title');
  var couple = $(this).data('couple');
  var textdate = $(this).data('textdate');
  var date = $(this).data('date');
  $(".modal-body #viewANNOUNCEMENT_TITLE").val( title );
  $(".modal-body #viewANNOUNCEMENT_COUPLE").val( couple );
  $(".modal-body #viewANNOUNCEMENT_TEXTDATE").val( textdate );
  $(".modal-body #viewANNOUNCEMENT_DATE").val( date );
  
  // console.log(map);
});

// Edit Announcement
$(document).on("click", ".open-EditAnnouncement", function () {
  var guestid = $(this).data('guestid');
  var title = $(this).data('title');
  var couple = $(this).data('couple');
  var textdate = $(this).data('textdate');
  var date = $(this).data('date');
  $(".modal-body #editANNOUNCEMENT_ID").val( guestid );
  $(".modal-body #editANNOUNCEMENT_TITLE").val( title );
  $(".modal-body #editANNOUNCEMENT_COUPLE").val( couple );
  $(".modal-body #editANNOUNCEMENT_TEXTDATE").val( textdate );
  $(".modal-body #editANNOUNCEMENT_DATE").val( date );
  
  // console.log(map);
});

// Announcement Filtering
// Attach debounced event handler to form inputs
$('.filterAnn input').on('input', debounce(filterAnnEvent, 500));
function filterAnnEvent() {
  // Your event handling code here
  const title = $('#filterAnnANNOUNCEMENT_TITLE').val();
  const couple = $('#filterAnnANNOUNCEMENT_COUPLE').val();
  const textdate = $('#filterAnnANNOUNCEMENT_TEXTDATE').val();
  const date = $('#datepicker1').val();

  // Create a data object to hold the form data
  const formData = {
    ANNOUNCEMENT_TITLE: title,
    ANNOUNCEMENT_COUPLE: couple,
    ANNOUNCEMENT_TEXTDATE: textdate,
    ANNOUNCEMENT_DATE: date
  };

  $.ajax({
    type: "POST",
    url: "module/ajax/announcement/aj_getannouncement.php",
    data: formData,
    success: function(data){
      $("#anndata").html(data);
    }
  });
  // console.log(formData);
}

// ----- Function to reset form ----- //
function AnnclearForm() {
  document.getElementById("filterAnn").reset();
  reloadAnnTable();
}
// ----- End of function to reset form ----- //

function reloadAnnTable() {
  $.ajax({
    type: "POST",
    url: "module/ajax/announcement/aj_getannouncement.php",
    data: '',
    success: function(data){
      $("#anndata").html(data);
    }
  });
}
// ----- End of Announcement Section ----- //


// ----- Start of Countdown Section ----- //
$(document).ready(function() {
  // add Countdown
  $('#addCountdown-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var counttext1 = document.getElementById("COUNTDOWN_TEXT1").value;
    var counttext2 = document.getElementById("COUNTDOWN_TEXT2").value;
    var countdate = document.getElementById("datetime-picker").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    if (counttext1 !== '' && counttext2 !== '' && countdate !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_countdown.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Data saved successfully!');
            
            // Close the modal
            $('#AddCountdown').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/countdown/aj_getcountdown.php',
              success: function(response) {
                $("#countdowndata").html(response);
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

  // edit Countdown
  $('#editcountdown-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var Editcounttext1 = document.getElementById("editCOUNTDOWN_TEXT1").value;
    var Editcounttext2 = document.getElementById("editCOUNTDOWN_TEXT2").value;
    var Editcountdate = document.getElementById("datetime-pickerEdit").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'clicked');

    console.log(Editcounttext1, Editcounttext2, Editcountdate);

    if (Editcounttext1 !== '' && Editcounttext2 !== '' && Editcountdate !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_countdown.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            UpdateNotification('Data updated successfully!');
            
            // Close the modal
            $('#EditCountdown').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/countdown/aj_getcountdown.php',
              success: function(response) {
                $("#countdowndata").html(response);
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

// View Countdown
$(document).on("click", ".open-ViewCountdown", function () {
  var countid = $(this).data('countid');
  var text1 = $(this).data('text1');
  var text2 = $(this).data('text2');
  var countdate = $(this).data('date');
  $(".modal-body #viewCOUNTDOWN_ID").val( countid );
  $(".modal-body #viewCOUNTDOWN_TEXT1").val( text1 );
  $(".modal-body #viewCOUNTDOWN_TEXT2").val( text2 );
  $(".modal-body #viewCOUNTDOWN_DATE").val( countdate );
  
  // console.log(map);
});

// Edit Countdown
$(document).on("click", ".open-EditCountdown", function () {
  var countid = $(this).data('countid');
  var text1 = $(this).data('text1');
  var text2 = $(this).data('text2');
  var countdate = $(this).data('date');
  $(".modal-body #editCOUNTDOWN_ID").val( countid );
  $(".modal-body #editCOUNTDOWN_TEXT1").val( text1 );
  $(".modal-body #editCOUNTDOWN_TEXT2").val( text2 );
  $(".modal-body #datetime-pickerEdit").val( countdate );
  
  // console.log(map);
});

// Delete Countdown
function CountconfirmAndPost(value1,value2) {
  // Ask for confirmation
  if (confirm("Are you sure you want to delete this data?")) {
    // Create the data object
    var eventdata = {
      COUNTDOWN_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/t_countdown.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data deleted successfully!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'GET',
            url: 'module/ajax/countdown/aj_getcountdown.php',
            success: function(response) {
                $("#countdowndata").html(response);
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
// ----- End of Countdown Section ----- //