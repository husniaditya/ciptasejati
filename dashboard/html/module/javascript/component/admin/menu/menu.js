

// Utility: debounce (local fallback)
if (typeof debounce !== 'function') {
  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }
}

// Menu Table (server-side)
var menuTable;
function callTable() {
  if ($.fn.DataTable.isDataTable('#menuakses-table')) { return; }
  menuTable = $('#menuakses-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    scrollX: true,
    scrollY: '350px',
    buttons: ['copy','csv','excel','pdf'],
    ajax: {
      url: 'module/ajax/admin/menu/aj_tablemenu_ssp.php',
      type: 'POST',
      data: function (d) {
        // Attach filter values each draw
        d.MENU_ID    = $('#filterMENU_ID').val();
        d.GRUP_ID    = $('#selectize-dropdown3').val();
        d.MENU_NAMA  = $('#filterMENU_NAMA').val();
        d.USER_AKSES = $('#filterUSER_AKSES').val();
      }
    },
    columnDefs: [
      { targets: 0, orderable: false, searchable: false },
      { targets: [4,5,6,7,8,9], orderable: false, searchable: false } // switches
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

          // Reload DataTable server-side
          if (menuTable) { menuTable.ajax.reload(null, false); }
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
  if (menuTable) { menuTable.ajax.reload(); }
}

// ----- Function to reset form ----- //
function clearForm() {

  var selectizeInstance1 = $('#selectize-dropdown3')[0].selectize;
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }
  document.getElementById("filterMenu").reset();
  if (menuTable) { menuTable.ajax.reload(); }
}
// ----- End of function to reset form ----- //
// ----- End of Menu Section ----- //