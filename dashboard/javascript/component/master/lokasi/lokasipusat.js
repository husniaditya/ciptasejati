

// Pusat Table
function callTable() {
    $('#lokasipusat-table').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  }

  // Call the function when the document is ready
  $(document).ready(function() {
    callTable();
  });

// Load Pusat Maps
function getMapsAdd(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/master/lokasipusat/aj_getmap.php",
  data:'maps='+val,
  success: function(data){
    $("#addPusatMap").html(data);
  }
  });
  // console.log(val);
}

function getMapsEdit(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/master/lokasipusat/aj_getmap.php",
  data:'maps='+val,
  success: function(data){
    $("#editPusatMap").html(data);
  }
  });
  // console.log(val);
}


// ----- Start of Pusat Section ----- //
function handleCabangForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasipusat.php',
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
            url: 'module/ajax/master/lokasipusat/aj_tablepusat.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#lokasipusat-table').DataTable().destroy();
              $("#pusatdata").html(response);
              // Reinitialize Daerah Table
              callTable();
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
  // add Pusat
  handleCabangForm('#AddPusat-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Pusat
  handleCabangForm('#EditPusat-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Pusat
function deletePusat(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      PUSAT_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasipusat.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/lokasipusat/aj_tablepusat.php',
            success: function(response) {
                $("#pusatdata").html(response);
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

// View Pusat
$(document).on("click", ".open-ViewPusat", function () {
  var pusatid = $(this).data('id');
  var pusatdesk = $(this).data('desc');
  var pusatsekre = $(this).data('sekre');
  var pusatpengurus = $(this).data('pengurus');
  var pusatmap = $(this).data('map');
  var pusatlat = $(this).data('lat');
  var pusatlong = $(this).data('long');
  
  // Set the values in the modal input fields
  $(".modal-body #viewPUSAT_ID").val(pusatid);
  $(".modal-body #viewPUSAT_DESKRIPSI").val(pusatdesk);
  $(".modal-body #viewPUSAT_SEKRETARIAT").val(pusatsekre);
  $(".modal-body #viewPUSAT_KEPENGURUSAN").val(pusatpengurus);
  $(".modal-body #viewPUSAT_MAP").val(pusatmap);
  $(".modal-body #viewPUSAT_LAT").val(pusatlat);
  $(".modal-body #viewPUSAT_LONG").val(pusatlong);

  // Set the source URL to the iframe
  document.getElementById('ViewPusatMap').src = pusatmap;
  
  // console.log(tingatannama);
});

// Edit Pusat
$(document).on("click", ".open-EditPusat", function () {
  var pusatid = $(this).data('id');
  var pusatdesk = $(this).data('desc');
  var pusatsekre = $(this).data('sekre');
  var pusatpengurus = $(this).data('pengurus');
  var pusatmap = $(this).data('map');
  var pusatlat = $(this).data('lat');
  var pusatlong = $(this).data('long');

  // Set the values in the modal input fields
  $(".modal-body #editPUSAT_ID").val(pusatid);
  $(".modal-body #editPUSAT_DESKRIPSI").val(pusatdesk);
  $(".modal-body #editPUSAT_SEKRETARIAT").val(pusatsekre);
  $(".modal-body #editPUSAT_KEPENGURUSAN").val(pusatpengurus);
  $(".modal-body #editPUSAT_MAP").val(pusatmap);
  $(".modal-body #editPUSAT_LAT").val(pusatlat);
  $(".modal-body #editPUSAT_LONG").val(pusatlong);
  
  // Set the source URL to the iframe
  document.getElementById('EditPusatMap').src = pusatmap;

  // console.log(tingkatanstatus);
});

// ----- End of Pusat Section ----- //