

// Tingkatan Table
$(document).ready(function() {
    $('#tingkatgelar-table').DataTable({
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
});


// ----- Start of Tingkatan Section ----- //
$(document).ready(function() {
  // add Tingkatan
  $('#addTingkatGelar-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var tingkatannama = document.getElementById("TINGKATAN_NAMA").value;
    var tingkatangelar = document.getElementById("TINGKATAN_GELAR").value;
    var tingkatansebutan = document.getElementById("TINGKATAN_SEBUTAN").value;
    var tingkatanlevel = document.getElementById("TINGKATAN_LEVEL").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    if (tingkatannama !== '' && tingkatangelar !== '' && tingkatanlevel !== '' && tingkatansebutan !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_tingkatgelar.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Data saved successfully!');
            
            // Close the modal
            $('#AddTingkatGelar').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/tingkatgelar/aj_tingkatgelar.php',
              success: function(response) {
                $("#tingkatgelardata").html(response);
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
      FailedNotification('Mohon untuk melengkapi semua isian form!');
    }
    // console.log(formData);
  });  

  // edit Tingkatan
  $('#editTingkatGelar-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var Edittingkatannama = document.getElementById("editTINGKATAN_NAMA").value;
    var Edittingkatangelar = document.getElementById("editTINGKATAN_GELAR").value;
    var Edittingkatansebutan = document.getElementById("editTINGKATAN_SEBUTAN").value;
    var Edittingkatanlevel = document.getElementById("editTINGKATAN_LEVEL").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (Edittingkatannama !== '' && Edittingkatangelar !== '' && Edittingkatansebutan !== '' && Edittingkatanlevel !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_tingkatgelar.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            UpdateNotification('Data Berhasil Diubah!');
            
            // Close the modal
            $('#EditTingkatGelar').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/tingkatgelar/aj_tingkatgelar.php',
              success: function(response) {
                $("#tingkatgelardata").html(response);
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
      FailedNotification('Mohoin dilengkapi semua isian form!');
    }
    // console.log(formData);
  });  
});

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
      url: 'module/backend/t_tingkatgelar.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'GET',
            url: 'module/ajax/tingkatgelar/aj_tingkatgelar.php',
            success: function(response) {
                $("#tingkatgelardata").html(response);
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