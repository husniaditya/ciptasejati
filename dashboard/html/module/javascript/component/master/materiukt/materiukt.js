

// Sertifikat Table
function callTable() {
  $('#materi-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });
}

// ----- Start of Sertifikat Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  // Set the flag to true indicating that the form has been submitted
  formSubmitted = true;

  $(formId).submit(function (event) {
    
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    var id; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/materiukt/t_materiukt.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        id = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/materiukt/aj_tablemateriukt.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#materi-table').DataTable().destroy();
              $("#materidata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function (xhr, status, error) {
              // Handle any errors
            }
          });
          
        } else {
          // Display error notification
          failedNotification(response);
        }
      },
      error: function (xhr, status, error) {
        // Handle any errors

        // Hide the loading overlay in case of an error
        hideLoadingOverlay();
      }
    });
  });
}


$('#AddMateri').on('hidden.bs.modal', function() {
  resetPreview('#AddMateri');
});
$('#EditMateri').on('hidden.bs.modal', function() {
  resetPreview('#EditMateri');
});

function resetPreview(modalId) { // Function Reset Preview Dropdown Value
  if (modalId === '#AddMateri') {
    // Code to reset the dropdown for Add Materi
    var id = $("#TOKEN").val();
    
    // Create the data object
    var eventdata = {
      id: id,
      DELETE_MATERI_DETAIL: "deletemateridetail"
    };
    
    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/materiukt/t_materiukt.php',
      data: eventdata,
      success: function(response) {
        // console.log(response);
        // Check the response from the server
        // Add response if needed
      },
      error: function(xhr, status, error) {
        console.error('Request failed. Status: ' + xhr.status);
      }
    });
  }

  var isExist = $('#selectize-dropdown').length > 0 && $('#selectize-dropdown2').length > 0 && $('#selectize-dropdown3').length > 0 && $('#selectize-dropdown4').length > 0;

  if (isExist) {
    var selectizeInstance = $('#selectize-dropdown')[0].selectize; // DAERAH ADD
    var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize; // CABANG ADD
    var selectizeInstance3 = $('#selectize-dropdown3')[0].selectize; // DAERAH EDIT
    var selectizeInstance4 = $('#selectize-dropdown4')[0].selectize; // CABANG EDIT
  }
  var tingkatanAdd = $('#selectize-dropdown5')[0].selectize; // TINGKATAN ADD
  var tingkatanEdit = $('#selectize-dropdown6')[0].selectize; // TINGKATAN EDIT

  if (selectizeInstance) {
    selectizeInstance.clear();
  }
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }
  if (selectizeInstance4) {
    selectizeInstance4.clear();
  }
  tingkatanAdd.clear();
  tingkatanEdit.clear();
}

$(document).ready(function() {
  callTable();
  // add Sertifikat
  handleForm('#AddMateri-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Sertifikat
  handleForm('#EditMateri-form', UpdateNotification, FailedNotification, UpdateNotification);

  // DROPDOWN FILTER DAERAH - CABANG
  $('#selectize-select').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect = $('#selectize-select').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizeSelect[0].selectize;

    // Event listener for the first dropdown change
    selectizeInstance.on('change', function (selectedDaerah) {
        // Make an AJAX request to fetch data for the second dropdown based on the selected value
        $.ajax({
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
            method: 'POST',
            data: { id: selectedDaerah },
            dataType: 'json', // Specify the expected data type as JSON
            success: function (data) {
                // Clear options in the second dropdown
                var selectizeSelect2 = $('#selectize-select2').selectize();
                var selectizeInstance2 = selectizeSelect2[0].selectize;
                selectizeInstance2.clearOptions();

                // Add new options to the second dropdown
                selectizeInstance2.addOption(data);

                // Update the value of the second dropdown
                selectizeInstance2.setValue('');

                // Refresh the Selectize instance to apply changes
                // selectizeInstance2.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cabang data:', status, error);
            }
        });
    });
  });

  // DROPDOWN FILTER DAERAH ADD
  $('#selectize-dropdown').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect = $('#selectize-dropdown').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizeSelect[0].selectize;

    // Event listener for the first dropdown change
    selectizeInstance.on('change', function (selectedDaerah) {
        // Make an AJAX request to fetch data for the second dropdown based on the selected value
        $.ajax({
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
            method: 'POST',
            data: { id: selectedDaerah },
            dataType: 'json', // Specify the expected data type as JSON
            success: function (data) {
                // Clear options in the second dropdown
                var selectizeSelect2 = $('#selectize-dropdown2').selectize();
                var selectizeInstance2 = selectizeSelect2[0].selectize;
                selectizeInstance2.clearOptions();

                // Add new options to the second dropdown
                selectizeInstance2.addOption(data);

                // Update the value of the second dropdown
                selectizeInstance2.setValue('');

                // Refresh the Selectize instance to apply changes
                // selectizeInstance2.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cabang data:', status, error);
            }
        });
    });
  });
  // DROPDOWN FILTER DAERAH EDIT
  $('#selectize-dropdown3').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect3 = $('#selectize-dropdown3').selectize();
    
    // Get the Selectize instance
    var selectizeInstance3 = selectizeSelect3[0].selectize;

    // Event listener for the first dropdown change
    selectizeInstance3.on('change', function (selectedDaerah) {
        // Make an AJAX request to fetch data for the second dropdown based on the selected value
        $.ajax({
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
            method: 'POST',
            data: { id: selectedDaerah },
            dataType: 'json', // Specify the expected data type as JSON
            success: function (data) {
                // Clear options in the second dropdown
                var selectizeSelect4 = $('#selectize-dropdown4').selectize();
                var selectizeInstance4 = selectizeSelect4[0].selectize;
                selectizeInstance4.clearOptions();

                // Add new options to the second dropdown
                selectizeInstance4.addOption(data);

                // Update the value of the second dropdown
                selectizeInstance4.setValue('');

                // Refresh the Selectize instance to apply changes
                // selectizeInstance2.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cabang data:', status, error);
            }
        });
    });
  });
});

// Delete Materi
function deletemateri(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      MATERI_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/materiukt/t_materiukt.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/materiukt/aj_tablemateriukt.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#materi-table').DataTable().destroy();
              $("#materidata").html(response);
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


// Add Materi
$(document).on("click", ".open-AddMateri", function () {
  // Destroy the DataTable before updating
  if ($.fn.DataTable.isDataTable('#detailmateri-table')) {
      $('#detailmateri-table').DataTable().destroy();
  }
  
  // Clear the table body content
  $("#detailmateridata").empty();
});

// View Materi
$(document).on("click", ".open-ViewMateri", function () {
  var id = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/master/materiukt/aj_getmateri.php',
    method: 'POST',
    data: { MATERI_ID: id },
    success: function(data) {
      // console.log(data);
      $("#viewMATERI_ID").val(data.MATERI_ID);
      $("#viewMATERI_DESKRIPSI").val(data.MATERI_DESKRIPSI);
      $("#viewMATERI_BOBOT").val(data.MATERI_BOBOT);
      $("#viewDAERAH_KEY").val(data.DAERAH_DESKRIPSI);
      $("#viewCABANG_KEY").val(data.CABANG_DESKRIPSI);
      $("#viewTINGKATAN_ID").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);

      $.ajax({
        type: "POST",
        url: 'module/ajax/master/materiukt/aj_getdetailmateriview.php',
        data: { materi: id },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewdetailmateri-table').DataTable().destroy();
          $("#viewdetailmateridata").html(response);
          // Reinitialize Sertifikat Table
        }
      });
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Edit Materi
$(document).on("click", ".open-EditMateri", function () {
  var id = $(this).data('id');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/master/materiukt/aj_getmateri.php',
    method: 'POST',
    data: { MATERI_ID: id },
    success: function(data) {
      // console.log(data);
      $("#editMATERI_ID").val(data.MATERI_ID);
      $("#editMATERI_DESKRIPSI").val(data.MATERI_DESKRIPSI);
      $("#editMATERI_BOBOT").val(data.MATERI_BOBOT);
      $(".modal-body #selectize-dropdown6")[0].selectize.setValue(data.TINGKATAN_ID);
      
      // Check if the administrator-specific elements exist
      var isExist = $('#selectize-dropdown3').length > 0 && $('#selectize-dropdown4').length > 0;
      if (isExist) {

        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
          $(".modal-body #selectize-dropdown3")[0].selectize.setValue(data.DAERAH_KEY);
          
          // After setting the value for selectize-dropdown5, set the value for selectize-dropdown7
          setTimeout(function () {
            $(".modal-body #selectize-dropdown4")[0].selectize.setValue(data.CABANG_KEY);
          }, 200); // You may need to adjust the delay based on your application's behavior
          
        }, 200); // You may need to adjust the delay based on your application's behavior
    
      }

      $.ajax({
        type: "POST",
        url: 'module/ajax/master/materiukt/aj_getdetailmateriedit.php',
        data: { materi: id },
        success: function(response){
          // Destroy the DataTable before updating
          $('#editdetailmateri-table').DataTable().destroy();
          $("#editdetailmateridata").html(response);
          // Reinitialize Sertifikat Table
        }
      });
      
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Detail Materi
// Klik Detail Materi
$(document).on("click", ".selectdetail", function () {
  var id = $(this).data('id');
  var deskripsi = $(this).data('deskripsi');
  var bobot = $(this).data('bobot');

  $("#editID").val(id);
  $("#editDETAIL_DESKRIPSI").val(deskripsi);
  $("#editDETAIL_BOBOT").val(bobot);

});
// Tambah Detail Materi Add Modal
$(document).on("click", ".addtambahdetail", function () {
  var id = $("#TOKEN").val();
  var deskripsi = $("#DETAIL_DESKRIPSI").val();
  var bobot = $("#DETAIL_BOBOT").val();
  var totalbobot = $("#MATERI_BOBOT").val();

  // Create the data object
  var eventdata = {
    materi: id,
    deskripsi: deskripsi,
    bobot: bobot,
    totalbobot: totalbobot,
    ADD_MODAL_DETAIL: "addmodaldetail"
  };

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/master/materiukt/t_materiukt.php',
    data: eventdata,
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        // Display success notification
        SuccessNotification('Data berhasil tersimpan!');
        
        $.ajax({
          type: "POST",
          url: 'module/ajax/master/materiukt/aj_getdetailmateri.php',
          data: { materi: id},
          success: function(response){
            // console.log(response);
            // Destroy the DataTable before updating
            $('#detailmateri-table').DataTable().destroy();
            $("#detailmateridata").html(response);
            // Reinitialize Sertifikat Table
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

});
// Delete Detail Materi Add Modal
$(document).on("click", ".addhapusdetail", function () {
  var id = $(this).data('id');
  var materi = $(this).data('mtr');

  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      id: id,
      DELETE_DETAIL: "deletedetail"
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/materiukt/t_materiukt.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          $.ajax({
            type: "POST",
            url: 'module/ajax/master/materiukt/aj_getdetailmateri.php',
            data: { materi: materi },
            success: function(response){
              // console.log(response);
              // Destroy the DataTable before updating
              $('#detailmateri-table').DataTable().destroy();
              $("#detailmateridata").html(response);
              // Reinitialize Sertifikat Table
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
  }

});
// Tambah Detail Materi Edit Modal
$(document).on("click", ".tambahdetail", function () {
  var id = $("#editMATERI_ID").val();
  var deskripsi = $("#editDETAIL_DESKRIPSI").val();
  var bobot = $("#editDETAIL_BOBOT").val();

  // Create the data object
  var eventdata = {
    materi: id,
    deskripsi: deskripsi,
    bobot: bobot,
    ADD_DETAIL: "adddetail"
  };

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/master/materiukt/t_materiukt.php',
    data: eventdata,
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        // Display success notification
        SuccessNotification('Data berhasil tersimpan!');
        
        $.ajax({
          type: "POST",
          url: 'module/ajax/master/materiukt/aj_getdetailmateriedit.php',
          data: { materi: id},
          success: function(response){
            // console.log(response);
            // Destroy the DataTable before updating
            $('#editdetailmateri-table').DataTable().destroy();
            $("#editdetailmateridata").html(response);
            // Reinitialize Sertifikat Table
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

});
// Delete Detail Materi Edit Modal
$(document).on("click", ".hapusdetail", function () {
  var id = $(this).data('id');
  var materi = $(this).data('mtr');

  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      id: id,
      DELETE_DETAIL: "deletedetail"
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/materiukt/t_materiukt.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          $.ajax({
            type: "POST",
            url: 'module/ajax/master/materiukt/aj_getdetailmateriedit.php',
            data: { materi: materi },
            success: function(response){
              // console.log(response);
              // Destroy the DataTable before updating
              $('#editdetailmateri-table').DataTable().destroy();
              $("#editdetailmateridata").html(response);
              // Reinitialize Sertifikat Table
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
  }

});

// Filtering
// Attach debounced event handler to form inputs
$('.filterMateriUKT select, .filterMateriUKT input').on('change input', debounce(filterMateriUKTEvent, 500));
function filterMateriUKTEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select').val();
  const cabang = $('#selectize-select2').val();
  const tingkatan = $('#selectize-select3').val();
  const id = $('#filterMATERI_ID').val();
  const deskripsi = $('#filterMATERI_DESKRIPSI').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    TINGKATAN_ID: tingkatan,
    MATERI_ID: id,
    MATERI_DESKRIPSI: deskripsi,
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/master/materiukt/aj_tablemateriukt.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#materi-table').DataTable().destroy();
      $("#materidata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

// ----- Function to reset form ----- //
function clearForm() {
  // Clear the first Selectize dropdown
  var isExist = $('#selectize-select').length > 0 && $('#selectize-select2').length > 0;
  var selectizeInstance3 = $('#selectize-select3')[0].selectize;

  if (isExist) {
    var selectizeInstance = $('#selectize-select')[0].selectize;
    var selectizeInstance2 = $('#selectize-select2')[0].selectize;
    if (selectizeInstance) {
      selectizeInstance.clear();
    }
    if (selectizeInstance2) {
      selectizeInstance2.clear();
    }
  }
  selectizeInstance3.clear();
  document.getElementById("filterMateriUKT").reset();
}
// ----- End of Pusat Section ----- //
