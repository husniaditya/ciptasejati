

// Daerah Table
function callTable() {
  $('#lokasidaerah-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtip',
      columnDefs: [
          { width: '100px', targets: 0 }, // Set width for column 1
          { width: '250px', targets: 1 }, // Set width for column 2
          { width: '250px', targets: 2 }, // Set width for column 2
          { width: '350px', targets: 3 }, // Set width for column 3
          { width: '50px', targets: 4 }, // Set width for column 4
          { width: '250px', targets: 5 }, // Set width for column 5
          { width: '250px', targets: 6 }, // Set width for column 6
          // Add more columnDefs as needed
      ],
      scrollX: true,
      scrollY: '300px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
  });
}

// Call the function when the document is ready
$(document).ready(function() {
  callTable();
});


// ----- Start of Daerah Section ----- //
function handleCabangForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasidaerah.php',
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
            url: 'module/ajax/master/lokasidaerah/aj_tabledaerah.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#lokasidaerah-table').DataTable().destroy();
              $("#daerahdata").html(response);
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
  // add Daerah
  handleCabangForm('#AddDaerah-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Daerah
  handleCabangForm('#EditDaerah-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Daerah
function deletedaerah(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      DAERAH_KEY: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasidaerah.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/lokasidaerah/aj_tabledaerah.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#lokasidaerah-table').DataTable().destroy();
              $("#daerahdata").html(response);
              // Reinitialize Daerah Table
              callTable();
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

// edit Daerah
$(document).on("click", ".open-ViewDaerah", function () {
  var daerahid = $(this).data('shortid');
  var pusatdes = $(this).data('pusatdes');
  var daerahdes = $(this).data('daerahdes');
  var status = $(this).data('daerahstatus');
  
  // Set the values in the modal input fields
  $(".modal-body #viewDAERAH_ID").val(daerahid);
  $(".modal-body #viewPUSAT_KEY").val(pusatdes);
  $(".modal-body #viewDAERAH_DESKRIPSI").val(daerahdes);
  $(".modal-body #viewDELETION_STATUS").val(status);
  
  // console.log(tingatannama);
});

// Edit Daerah
$(document).on("click", ".open-EditDaerah", function () {
  var key = $(this).data('key');
  var daerahid = $(this).data('shortid');
  var pusatid = $(this).data('pusatid');
  var daerahdes = $(this).data('daerahdes');
  var status = $(this).data('status');

  // Set the values in the modal input fields
  $(".modal-body #editDAERAH_KEY").val(key);
  $(".modal-body #editDAERAH_ID").val(daerahid);
  $(".modal-body #editPUSAT_KEY").val(pusatid);
  $(".modal-body #editDAERAH_DESKRIPSI").val(daerahdes);
  $(".modal-body #editDELETION_STATUS").val(status);

  // console.log(pusatid);
});


// ----- End of Pusat Section ----- //