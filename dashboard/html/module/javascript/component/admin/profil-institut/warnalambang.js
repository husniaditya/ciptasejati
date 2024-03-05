

// Data Table
function callTable() {
    // Destroy and re-initialize DataTable on second tab
    if ($.fn.DataTable.isDataTable('#lambang-table')) {
        $('#lambang-table').DataTable().destroy();
    }
    // Initialize DataTable on first tab
    var visiTable = $('#lambang-table').DataTable({
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
      if (targetTab === "#tabWarna") {
        // Destroy and re-initialize DataTable on second tab
        if ($.fn.DataTable.isDataTable('#warna-table')) {
            $('#warna-table').DataTable().destroy();
        }
        var warnaTable = $('#warna-table').DataTable({
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
    } else if (targetTab === "#tabMakna") {
          // Destroy and re-initialize DataTable on second tab
          if ($.fn.DataTable.isDataTable('#makna-table')) {
              $('#makna-table').DataTable().destroy();
          }
          var maknaTable = $('#makna-table').DataTable({
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
          if ($.fn.DataTable.isDataTable('#lambang-table')) {
              $('#lambang-table').DataTable().destroy();
          }
          var lambangTable = $('#lambang-table').DataTable({
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
        url: 'module/backend/admin/profil-institut/t_warnalambang.php',
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
              url: 'module/ajax/admin/profil-institut/aj_tablelambang.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#lambang-table').DataTable().destroy();
                $("#lambangdata").html(response);
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
              url: 'module/ajax/admin/profil-institut/aj_tablewarna.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#warna-table').DataTable().destroy();
                $("#warnadata").html(response);
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
              url: 'module/ajax/admin/profil-institut/aj_tablemakna.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#makna-table').DataTable().destroy();
                $("#maknadata").html(response);
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
    handleForm('#AddWarnaLambang-form', SuccessNotification, FailedNotification, UpdateNotification);
    handleForm('#EditWarnaLambang-form', UpdateNotification, FailedNotification, UpdateNotification);
  });
  
  // Edit Profil
  $(document).on("click", ".open-EditWarnaLambang", function () {
    var key = $(this).data('id');
    
    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
      url: 'module/ajax/admin/profil-institut/aj_getwarnalambang.php',
      method: 'POST',
      data: { id: key },
      success: function(data) {
        // console.log(data);
        // Assuming data is a JSON object with the required information
        // Make sure the keys match the fields in your returned JSON object
  
        $("#editWLAMBANG_ID").val(data.WLAMBANG_ID);
        $("#editWLAMBANG_KATEGORI").val(data.WLAMBANG_KATEGORI);
        $("#editWLAMBANG_DESKRIPSI").val(data.WLAMBANG_DESKRIPSI);
        
      },
      error: function(error) {
        console.error('Error fetching data:', error);
      }
    });
  });
  
  // Delete Visi-Misi
  function deletewarnalambang(value1,value2) {
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
        url: 'module/backend/admin/profil-institut/t_warnalambang.php',
        data: eventdata,
        success: function(response) {
          // Check the response from the server
          if (response === 'Success') {
            // Display success notification
            DeleteNotification('Data berhasil dihapus!');
            
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'POST',
              url: 'module/ajax/admin/profil-institut/aj_tablelambang.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#lambang-table').DataTable().destroy();
                $("#lambangdata").html(response);
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
              url: 'module/ajax/admin/profil-institut/aj_tablewarna.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#warna-table').DataTable().destroy();
                $("#warnadata").html(response);
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
                url: 'module/ajax/admin/profil-institut/aj_tablemakna.php',
                success: function(response) {
                  // Destroy the DataTable before updating
                  $('#makna-table').DataTable().destroy();
                  $("#maknadata").html(response);
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