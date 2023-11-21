

// Anggota Table
$(document).ready(function() {
    $('#lokasipusat-table').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});

// Load Pusat Maps
function getMapsAdd(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/lokasipusat/aj_getmap.php",
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
  url: "module/ajax/lokasipusat/aj_getmap.php",
  data:'maps='+val,
  success: function(data){
    $("#editPusatMap").html(data);
  }
  });
  // console.log(val);
}


// ----- Start of Pusat Section ----- //
$(document).ready(function() {
  // add Pusat
  $('#AddPusat-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var pusatdesk = document.getElementById("PUSAT_DESKRIPSI").value;
    var pusatsek = document.getElementById("PUSAT_SEKRETARIAT").value;
    var pusatlat = document.getElementById("PUSAT_LAT").value;
    var pusatlong = document.getElementById("PUSAT_LONG").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    if (pusatdesk !== '' && pusatsek !== '' && pusatlat !== '' && pusatlong !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_lokasipusat.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            SuccessNotification('Data berhasil tersimpan!');
            
            // Close the modal
            $('#AddPusat').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/lokasipusat/aj_tablepusat.php',
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
          // Handle any errors
        }
      });
    } else {
      // Display error notification
      FailedNotification('Mohon untuk melengkapi semua isian form!');
    }
    console.log(pusatdesk);
  });  

  // edit Pusat
  $('#EditPusat-form').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var pusatdesk = document.getElementById("editPUSAT_DESKRIPSI").value;
    var pusatsek = document.getElementById("editPUSAT_SEKRETARIAT").value;
    var pusatlat = document.getElementById("editPUSAT_LAT").value;
    var pusatlong = document.getElementById("editPUSAT_LONG").value;
  
    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'clicked');

    if (pusatdesk !== '' && pusatsek !== '' && pusatlat !== '' && pusatlong !== '') {
      $.ajax({
        type: 'POST',
        url: 'module/backend/t_lokasipusat.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            UpdateNotification('Data Berhasil Diubah!');
            
            // Close the modal
            $('#EditPusat').modal('hide');
    
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'GET',
              url: 'module/ajax/lokasipusat/aj_tablepusat.php',
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
          // Handle any errors
        }
      });
    } else {
      // Display error notification
      FailedNotification('Mohon untuk melengkapi semua isian form!');
    }
    // console.log(formData);
  });  
});

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

// Delete Pusat
function deleteTingkatan(value1,value2) {
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
      url: 'module/backend/t_lokasipusat.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'GET',
            url: 'module/ajax/lokasipusat/aj_tablepusat.php',
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
// ----- End of Pusat Section ----- //