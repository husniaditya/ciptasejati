
// Informasi Table
function callTable() {
    $('#informasi-table').DataTable({
        responsive: true,
        order: [],
        dom: 'Bfrtip',
        paging: true,
        scrollX: true,
        scrollY: '350px', // Set the desired height here
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ]
    });
  }

// ----- Start of Informasi Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/cms/beranda/informasi/t_informasi.php',
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
            url: 'module/ajax/cms/beranda/informasi/aj_getinformasi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#informasi-table').DataTable().destroy();
              $("#informasidata").html(response);
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
  callTable();
  // add Informasi
  handleForm('#AddInformasi-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Informasi
  handleForm('#EditInformasi-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Informasi
function deleteInformasi(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      INFORMASI_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/cms/beranda/informasi/t_informasi.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/cms/beranda/informasi/aj_getinformasi.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#informasi-table').DataTable().destroy();
              $("#informasidata").html(response);
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

// View Informasi
$(document).on("click", ".open-ViewInformasi", function () {
  
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/cms/beranda/informasi/aj_getdetailinformasi.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewINFORMASI_JUDUL").val(data.INFORMASI_JUDUL);
      $("#viewINFORMASI_KATEGORI").val(data.INFORMASI_KATEGORI);
      $("#viewINFORMASI_DESKRIPSI").val(data.INFORMASI_DESKRIPSI);
      $("#viewINFORMASI_STATUS").val(data.INFORMASI_STATUS);
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Edit Informasi
$(document).on("click", ".open-EditInformasi", function () {
  
  var key = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/cms/beranda/informasi/aj_getdetailinformasi.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#editINFORMASI_ID").val(data.INFORMASI_ID);
      $("#editINFORMASI_JUDUL").val(data.INFORMASI_JUDUL);
      $("#editINFORMASI_KATEGORI").val(data.INFORMASI_KATEGORI);
      $("#editINFORMASI_DESKRIPSI").val(data.INFORMASI_DESKRIPSI);
      $("#editINFORMASI_STATUS").val(data.DELETION_STATUS);
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});


// ----- End of Countdown Section ----- //