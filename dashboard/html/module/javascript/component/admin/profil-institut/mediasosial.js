

// Media Sosial Table
function callTable() {
    $('#mediasosial-table').DataTable({
        responsive: true,
        order: [[1, 'asc']],
        dom: 'Bfrtlip',
        columnDefs: [
            { width: '100px', targets: 0 }, // Set width for column 1
            { width: '250px', targets: 2 }, // Set width for column 2
            { width: '350px', targets: 3 }, // Set width for column 3
            { width: '250px', targets: 4 }, // Set width for column 4
            { width: '250px', targets: 5 }, // Set width for column 5
            // Add more columnDefs as needed
        ],
        scrollX: true,
        scrollY: '350px', // Set the desired height here
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ]
    });
  }
  
  // ----- Start of Media Sosial Section ----- //
  function handleForm(formId, successNotification, failedNotification, updateNotification) {
    $(formId).submit(function(event) {
      event.preventDefault(); // Prevent the default form submission
  
      var formData = new FormData($(this)[0]); // Create FormData object from the form
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID
  
      // Manually add the button title or ID to the serialized data
      formData.append(buttonId, 'edit');
  
      $.ajax({
        type: 'POST',
        url: 'module/backend/admin/profil-institut/t_mediasosial.php',
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
              url: 'module/ajax/admin/profil-institut/aj_tablemedia.php',
              success: function(response) {
                // Destroy the DataTable before updating
                $('#mediasosial-table').DataTable().destroy();
                $("#mediadata").html(response);
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
    handleForm('#AddMedia-form', UpdateNotification, FailedNotification, UpdateNotification);
    handleForm('#EditMedia-form', UpdateNotification, FailedNotification, UpdateNotification);
  });
  
  // Edit Media Sosial
  $(document).on("click", ".open-EditMedia", function () {
    var key = $(this).data('id');
    
    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
      url: 'module/ajax/admin/profil-institut/aj_getmedia.php',
      method: 'POST',
      data: { id: key },
      success: function(data) {
        console.log(data);
        // Assuming data is a JSON object with the required information
        // Make sure the keys match the fields in your returned JSON object
  
        $("#editMEDIA_ID").val(data.MEDIA_ID);
        $("#editMEDIA_ICON").val(data.MEDIA_ICON);
        $("#editMEDIA_DESKRIPSI").val(data.MEDIA_DESKRIPSI);
        $("#editMEDIA_LINK").val(data.MEDIA_LINK);
        
      },
      error: function(error) {
        console.error('Error fetching data:', error);
      }
    });
  });
  
  // Delete Media Sosial
function deletemedia(value1,value2) {
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
      url: 'module/backend/admin/profil-institut/t_mediasosial.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/profil-institut/aj_tablemedia.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#mediasosial-table').DataTable().destroy();
              $("#mediadata").html(response);
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
  // ----- End of Media Sosial Section ----- //