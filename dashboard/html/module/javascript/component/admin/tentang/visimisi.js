

// Profil-Institut Table
function callTable() {
  $('#cmsvisimisi-table').DataTable({
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
  var previewImage = document.getElementById('preview-image-edit');
  var previewContainer = document.getElementById('preview-container-edit');

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
      url: 'module/backend/cms/tentang/t_visimisi.php',
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
            url: 'module/ajax/cms/tentang/aj_tablevisimisi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#cmsvisimisi-table').DataTable().destroy();
              $("#cmsvisimisidata").html(response);
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
  handleForm('#EditVisiMisi-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Edit Visi Misi
$(document).on("click", ".open-EditVisiMisi", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/cms/tentang/aj_getvisimisi.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#editCMS_VISIMISI_ID").val(data.CMS_VISIMISI_ID);
      $("#editCMS_VISIMISI_KATEGORI").val(data.CMS_VISIMISI_KATEGORI);
      $("#preview-image-edit").attr("src", data.CMS_VISIMISI_PIC);
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});


// ----- End of Pusat Section ----- //