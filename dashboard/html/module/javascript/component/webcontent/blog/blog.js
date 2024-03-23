

// Profil-Institut Table
function callTable() {
    $('#blog-table').DataTable({
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

function previewImage(input) {
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
  
function previewImageedit(input) {
  var previewImageEdit = document.getElementById('preview-image-edit');
  var previewContainerEdit = document.getElementById('preview-container-edit');

  var reader = new FileReader();

  reader.onload = function (e) {
    previewImageEdit.src = e.target.result;
    previewContainerEdit.style.display = 'block';
  };

  // Make sure to read the file even if no file is selected
  reader.readAsDataURL(input.files[0]);
}

function resetPreview() {
  var previewImage = document.getElementById('preview-image');
  var previewContainer = document.getElementById('preview-container');

  // Reset the image source and hide the preview container
  previewImage.src = '#';
  previewContainer.style.display = 'none';

}

$('#AddBlog').on('hidden.bs.modal', handleModalHidden);
$('#EditBlog').on('hidden.bs.modal', handleModalHidden);
function handleModalHidden() {
  resetPreview();
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
      url: 'module/backend/cms/blog/t_blog.php',
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
            url: 'module/ajax/cms/blog/aj_tableblog.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#blog-table').DataTable().destroy();
              $("#blogdata").html(response);
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
  // Add Blog
  handleForm('#AddBlog-form', SuccessNotification, FailedNotification, UpdateNotification);
  // Edit Blog
  handleForm('#EditBlog-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// View Blog
$(document).on("click", ".open-ViewBlog", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/cms/blog/aj_getblog.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#viewBLOG_ID").val(data.BLOG_ID);
      $("#viewBLOG_TITLE").val(data.BLOG_TITLE);
      $("#viewBLOG_MESSAGE").val(data.BLOG_MESSAGE);
      $("#viewBLOG_IMAGE").attr("src", data.BLOG_IMAGE);
      $("#viewDELETION_STATUS").val(data.DELETION_STATUS);
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});
  
// Edit Blog
$(document).on("click", ".open-EditBlog", function () {
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/cms/blog/aj_getblog.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      console.log(data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#editBLOG_ID").val(data.BLOG_ID);
      $("#editBLOG_TITLE").val(data.BLOG_TITLE);
      $("#editBLOG_MESSAGE").val(data.BLOG_MESSAGE);
      $("#preview-image-edit").attr("src", data.BLOG_IMAGE);
      $("#editDELETION_STATUS").val(data.DELETION_STATUS);
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Delete Blog
function deleteblog(value1,value2) {
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
      url: 'module/backend/cms/blog/t_blog.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/cms/blog/aj_tableblog.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#blog-table').DataTable().destroy();
              $("#blogdata").html(response);
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