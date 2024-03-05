

// Visi-Misi Table
function callTable() {
  // Destroy and re-initialize DataTable on second tab
  if ($.fn.DataTable.isDataTable('#visi-table')) {
      $('#visi-table').DataTable().destroy();
  }
  // Initialize DataTable on first tab
  var visiTable = $('#visi-table').DataTable({
    responsive: true,
    order: [[1, 'asc']],
    dom: 'Bfrtlip',
    columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '450px', targets: 1 }, // Set width for column 2
        { width: '100px', targets: 2 }, // Set width for column 3
        { width: '100px', targets: 3 }, // Set width for column 4
        // Add more columnDefs as needed
    ],
    scrollX: true,
    scrollY: '350px', // Set the desired height here
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
  });

  // Handle tab change event
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    // Get the ID of the activated tab
    var targetTab = $(e.target).attr('href');
    
    // Check if the activated tab is the second tab
    if (targetTab === "#tabMisi") {
        // Destroy and re-initialize DataTable on second tab
        if ($.fn.DataTable.isDataTable('#misi-table')) {
            $('#misi-table').DataTable().destroy();
        }
        var misiTable = $('#misi-table').DataTable({
            responsive: true,
            order: [[1, 'asc']],
            dom: 'Bfrtlip',
            columnDefs: [
                { width: '100px', targets: 0 }, // Set width for column 1
                { width: '450px', targets: 1 }, // Set width for column 2
                { width: '100px', targets: 2 }, // Set width for column 3
                { width: '100px', targets: 3 }, // Set width for column 4
                // Add more columnDefs as needed
            ],
            scrollX: true,
            scrollY: '350px', // Set the desired height here
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ]
        });
    } else {
        // Destroy and re-initialize DataTable on first tab
        if ($.fn.DataTable.isDataTable('#visi-table')) {
            $('#visi-table').DataTable().destroy();
        }
        var visiTable = $('#visi-table').DataTable({
            responsive: true,
            order: [[1, 'asc']],
            dom: 'Bfrtlip',
            columnDefs: [
                { width: '100px', targets: 0 }, // Set width for column 1
                { width: '450px', targets: 1 }, // Set width for column 2
                { width: '100px', targets: 2 }, // Set width for column 3
                { width: '100px', targets: 3 }, // Set width for column 4
                // Add more columnDefs as needed
            ],
            scrollX: true,
            scrollY: '350px', // Set the desired height here
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ]
        });
    }
  });
}

// ----- Start of Sertifikat Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/admin/profil-institut/t_visimisi.php',
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
            url: 'module/ajax/admin/profil-institut/aj_tablevisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#visi-table').DataTable().destroy();
              $("#visidata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function(xhr, status, error) {
              // Handle any errors
            }
          });
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/profil-institut/aj_tablemisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#misi-table').DataTable().destroy();
              $("#misidata").html(response);
              // Reinitialize Sertifikat Table
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
  callTable();
  // edit Profil
  handleForm('#AddVisiMisi-form', SuccessNotification, FailedNotification, UpdateNotification);
  handleForm('#EditVisiMisi-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Edit Profil
$(document).on("click", ".open-EditVisiMisi", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/admin/profil-institut/aj_getvisimisi.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#editVISIMISI_ID").val(data.VISIMISI_ID);
      $("#editVISIMISI_KATEGORI").val(data.VISIMISI_KATEGORI);
      $("#editVISIMISI_DESKRIPSI").val(data.VISIMISI_DESKRIPSI);
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Delete Visi-Misi
function deletevisimisi(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      id: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/admin/profil-institut/t_visimisi.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/profil-institut/aj_getvisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#visi-table').DataTable().destroy();
              $("#visidata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function(xhr, status, error) {
              // Handle any errors
            }
          });

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/profil-institut/aj_tablevisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#visi-table').DataTable().destroy();
              $("#visidata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function(xhr, status, error) {
              // Handle any errors
            }
          });
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/profil-institut/aj_tablemisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#misi-table').DataTable().destroy();
              $("#misidata").html(response);
              // Reinitialize Sertifikat Table
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


// ----- End of Pusat Section ----- //