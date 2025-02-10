

// Menu Table
function callTable() {
  $('#menuakses-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });
}

// ----- Start of Menu Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    // Get the state of the switch
    var ADD = $('#ADD').is(':checked') ? 'Y' : 'N';
    var VIEW = $('#VIEW').is(':checked') ? 'Y' : 'N';
    var EDIT = $('#EDIT').is(':checked') ? 'Y' : 'N';
    var DELETE = $('#DELETE').is(':checked') ? 'Y' : 'N';
    var APPROVE = $('#APPROVE').is(':checked') ? 'Y' : 'N';
    var PRINT = $('#PRINT').is(':checked') ? 'Y' : 'N';
    // Add the switch value to the form data
    formData.append('ADD', ADD);
    formData.append('VIEW', VIEW);
    formData.append('EDIT', EDIT);
    formData.append('DELETE', DELETE);
    formData.append('APPROVE', APPROVE);
    formData.append('PRINT', PRINT);

    $.ajax({
      type: 'POST',
      url: 'module/backend/admin/menu/t_menu.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/menu/aj_tablemenu.php',
            success: function(response) {
              filterMenuEvent();
            },
            error: function(xhr, status, error) {
              // Handle any errors
            }
          });
        } else {
          // Display error notification
          failedNotification(response);
        }
      },
      error: function(xhr, status, error) {
        // Handle any errors
      }
    });
  });
}


$(document).ready(function() {
  callTable();
  // edit Profil
  handleForm('#EditMenu-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Edit Profil
$(document).on("click", ".open-EditMenu", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/admin/menu/aj_getmenu.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#MENU_KEY").val(data.MENU_KEY);
      $("#MENU_ID").val(data.MENU_ID);
      $("#MENU_NAMA").val(data.MENU_NAMA);
      $("#USER_AKSES").val(data.USER_AKSES);

      // Iterate over the keys of the data object for checkboxes and set the switch state
      for (let key in data) {
        // Set the switch based on the value of data[key]
        $('#' + key).prop('checked', data[key] === 'Y');
      }

      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});


// Menu Filtering
// Attach debounced event handler to form inputs
$('.filterMenu select, .filterMenu input').on('change input', debounce(filterMenuEvent, 500));
function filterMenuEvent() {
  // Your event handling code here
  const id = $('#filterMENU_ID').val();
  const grup = $('#selectize-dropdown3').val();
  const nama = $('#filterMENU_NAMA').val();
  const akses = $('#filterUSER_AKSES').val();

  // Create a data object to hold the form data
  const formData = {
    MENU_ID: id,
    GRUP_ID: grup,
    MENU_NAMA: nama,
    USER_AKSES: akses
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/admin/menu/aj_tablemenu.php',
    data: formData,
    success: function(response){
      // console.log(response);
      // Destroy the DataTable before updating
      $('#menuakses-table').DataTable().destroy();
      $("#menuaksesdata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

// ----- Function to reset form ----- //
function clearForm() {

  var selectizeInstance1 = $('#selectize-dropdown3')[0].selectize;
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }
  document.getElementById("filterMenu").reset();
  // Call the reloadDataTable() function after inserting data to reload the DataTable
  $.ajax({
    type: 'POST',
    url: 'module/ajax/admin/menu/aj_tablemenu.php',
    success: function(response) {
      // Destroy the DataTable before updating
      $('#menuakses-table').DataTable().destroy();
      $("#menuaksesdata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    },
    error: function(xhr, status, error) {
      // Handle any errors
    }
  });
}
// ----- End of function to reset form ----- //
// ----- End of Menu Section ----- //