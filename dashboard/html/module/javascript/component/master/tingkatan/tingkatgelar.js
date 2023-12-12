
// Declare dataTable globally
var dataTable;

// Tingkatan Table
function callTable() {
    datatable = $('#tingkatgelar-table').DataTable({
      responsive: true,
      columnDefs: [
        { width: '10%', targets: 0 }, // Set width for column 1
        { width: '20%', targets: 2 }, // Set width for column 3
        { width: '20%', targets: 3 }, // Set width for column 4
        { width: '10%', targets: 4 }, // Set width for column 5
        // { width: '10%', targets: 5 }, // Set width for column 6
        { width: '10%', targets: 6 }, // Set width for column 6
        { width: '10%', targets: 7 }, // Set width for column 6
        // Add more columnDefs as needed
      ],
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


// ----- Start of Tingkatan Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/tingkatan/t_tingkatgelar.php',
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
            url: 'module/ajax/master/tingkatan/aj_tingkatgelar.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#tingkatgelar-table').DataTable().destroy();
              $("#tingkatgelardata").html(response);
              // Reinitialize Daerah Table
              callTable();
              // Stay on the Same Page
              dataTable.page(currentPage).draw('page');
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
  handleForm('#AddTingkatGelar-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Daerah
  handleForm('#EditTingkatGelar-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Tingkatan
function deleteTingkatan(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      TINGKATAN_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/tingkatan/t_tingkatgelar.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/tingkatan/aj_tingkatgelar.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#tingkatgelar-table').DataTable().destroy();
              $("#tingkatgelardata").html(response);
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

// View tingkatan
$(document).on("click", ".open-ViewTingkatGelar", function () {
  var tingatannama = $(this).data('nama');
  var tingkatangelar = $(this).data('gelar');
  var tingkatansebutan = $(this).data('sebutan');
  var tingkatanlevel = $(this).data('level');
  var tingkatanstatus = $(this).data('status');
  $(".modal-body #viewTINGKATAN_NAMA").val( tingatannama );
  $(".modal-body #viewTINGKATAN_GELAR").val( tingkatangelar );
  $(".modal-body #viewTINGKATAN_SEBUTAN").val(tingkatansebutan);
  $(".modal-body #viewTINGKATAN_LEVEL").val( tingkatanlevel );
  $(".modal-body #viewTINGKATAN_STATUS").val( tingkatanstatus );
  
  // console.log(tingatannama);
});

// Edit Tingkatan
$(document).on("click", ".open-EditTingkatGelar", function () {
  var tingkatanid = $(this).data('id');
  var tingatannama = $(this).data('nama');
  var tingkatangelar = $(this).data('gelar');
  var tingkatansebutan = $(this).data('sebutan');
  var tingkatanlevel = $(this).data('level');
  var tingkatanstatus = $(this).data('status');

  // Set the values in the modal input fields
  $(".modal-body #editTINGKATAN_ID").val(tingkatanid);
  $(".modal-body #editTINGKATAN_NAMA").val(tingatannama);
  $(".modal-body #editTINGKATAN_GELAR").val(tingkatangelar);
  $(".modal-body #editTINGKATAN_SEBUTAN").val(tingkatansebutan);
  $(".modal-body #editTINGKATAN_LEVEL").val(tingkatanlevel);
  $(".modal-body #editTINGKATAN_STATUS").val(tingkatanstatus);

  // console.log(tingkatanstatus);
});


// ----- End of Countdown Section ----- //