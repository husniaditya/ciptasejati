

// User Table
function callTable() {
  $('#user-table').DataTable({
      responsive: true,
      order: [[2, 'asc']],
      dom: 'Bfrtlip',
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });
}

function resetPreview() {
  var previewImageEdit = document.getElementById('preview-image-edit');

  // Reset the image source and hide the preview container
  previewImageEdit.src = '#';
  previewContainerEdit.style.display = 'none';

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
      url: 'module/backend/admin/user/t_user.php',
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
            url: 'module/ajax/admin/user/aj_tableuser.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#user-table').DataTable().destroy();
              $("#userdata").html(response);
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
  // edit User
  handleForm('#EditUser-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Edit Media Sosial
$(document).on("click", ".open-EditUser", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/admin/user/aj_getuser.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#editDAERAH_DESKRIPSI").val(data.DAERAH_DESKRIPSI);
      $("#editCABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $("#editANGGOTA_RANTING").val(data.ANGGOTA_RANTING);
      $("#editANGGOTA_TINGKATAN").val(data.ANGGOTA_TINGKATAN);
      $("#editANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#editANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#editANGGOTA_AKSES").val(data.ANGGOTA_AKSES);
      $("#editUSER_STATUS").val(data.USER_STATUS);
      $("#preview-image-edit").attr("src", data.ANGGOTA_PIC);
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Delete Media Sosial
function deleteuser(value1,value2) {
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
      url: 'module/backend/admin/user/t_user.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/admin/user/aj_tableuser.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#user-table').DataTable().destroy();
              $("#userdata").html(response);
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