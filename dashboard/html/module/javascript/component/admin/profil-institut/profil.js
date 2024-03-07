

// Profil-Institut Table
function callTable() {
  $('#profilinstitut-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtlip',
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });
}

function previewImageedit(input) {
  var previewImage = document.getElementById('preview-image');
  var previewContainer = document.getElementById('preview-container');

  var reader = new FileReader();

  reader.onload = function (e) {
      previewImage.src = e.target.result;
      previewContainer.style.display = 'block';
  };

  // Make sure to read the file even if no file is selected
  reader.readAsDataURL(input.files[0]);
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
      url: 'module/backend/admin/profil-institut/t_profil.php',
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
            url: 'module/ajax/admin/profil-institut/aj_tableprofil.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#profilinstitut-table').DataTable().destroy();
              $("#profildata").html(response);
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
  handleForm('#EditProfil-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Edit Profil
$(document).on("click", ".open-EditProfil", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/admin/profil-institut/aj_profil.php',
    method: 'POST',
    data: { PROFIL_ID: key },
    success: function(data) {
      // console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#PROFIL_ID").val(data.PROFIL_ID);
      $("#PROFIL_NAMA").val(data.PROFIL_NAMA);
      $("#PROFIL_SEJARAH").val(data.PROFIL_SEJARAH);
      $("#PROFIL_TELP_1").val(data.PROFIL_TELP_1);
      $("#PROFIL_TELP_2").val(data.PROFIL_TELP_2);
      $("#PROFIL_EMAIL_1").val(data.PROFIL_EMAIL_1);
      $("#PROFIL_EMAIL_2").val(data.PROFIL_EMAIL_2);
      $("#preview-image").attr("src", data.PROFIL_LOGO);
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});


// ----- End of Pusat Section ----- //