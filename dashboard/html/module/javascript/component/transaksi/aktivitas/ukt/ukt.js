

// UKT Table
function callTable() {
  $('#ukt-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });

  $('#ApprovePPDGuru, #ViewPPDGuru').on('shown.bs.modal', function () {
    // Destroy DataTable for riwayatmutasi-table if it exists
    if ($.fn.DataTable.isDataTable('#detailppd-table')) {
      $('#detailppd-table').DataTable().destroy();
    }

    $('#detailppd-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
  });
}

function resetPreview() { // Function Reset Preview Dropdown Value
  // var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize; // Add Tingkatan
  var selectizeInstance4 = $('#selectize-dropdown4')[0].selectize; // Add Anggota
  var selectizeInstance8 = $('#selectize-dropdown8')[0].selectize; // Add Lokasi PPD
  var selectizeInstance6 = $('#selectize-dropdown6')[0].selectize; // Edit Tingkatan
  var selectizeInstance7 = $('#selectize-dropdown7')[0].selectize; // Edit Anggota
  var selectizeInstance11 = $('#selectize-dropdown11')[0].selectize; // Edit Lokasi PPD

  var isExist = $('#selectize-dropdown9').length > 0 && $('#selectize-dropdown10').length > 0 && $('#selectize-dropdown3').length > 0 && $('#selectize-dropdown5').length > 0;

  if (isExist) {
    var selectizeInstance9 = $('#selectize-dropdown9')[0].selectize;
    var selectizeInstance10 = $('#selectize-dropdown10')[0].selectize;
    var selectizeInstance3 = $('#selectize-dropdown3')[0].selectize;
    var selectizeInstance5 = $('#selectize-dropdown5')[0].selectize;
  }

  if (selectizeInstance9) {
    selectizeInstance9.clear();
  }
  if (selectizeInstance10) {
    selectizeInstance10.clear();
  }
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }
  if (selectizeInstance5) {
    selectizeInstance5.clear();
  }
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  if (selectizeInstance4) {
    selectizeInstance4.clear();
  }
  if (selectizeInstance8) {
    selectizeInstance8.clear();
  }
  if (selectizeInstance6) {
    selectizeInstance6.clear();
  }
  if (selectizeInstance7) {
    selectizeInstance7.clear();
  }
  if (selectizeInstance11) {
    selectizeInstance11.clear();
  }
}

function savePDFToDrive(UKT_ID) { // Function Save PDF to Drive
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ukt/t_uktfile.php',
      data: { id: UKT_ID },
      success: function(response) {
        // Check the response from the server
        // console.log(response);
        resolve(response);
      },
      error: function(xhr, status, error) {
        reject('Error! ' + xhr.status + ' ' + error);
      }
    });
  });
}

function sendEmailNotification(id) { // Function Send Email Notification
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasimail.php',
      data: { id: id },
      success: function(response) {
        // Check the response from the server
        resolve(response);
      },
      error: function(xhr, status, error) {
        reject('Error! ' + xhr.status + ' ' + error);
      }
    });
  });
}

// Assuming you have a Bootstrap modal with the ID "myModal"

$('#AddUKT').on('hidden.bs.modal', function() {
  resetPreview('#AddUKT');

  var HIDE_ADD_MODAL = "HIDE_ADD_MODAL"

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
    data: HIDE_ADD_MODAL,
    success: function(response) {
      $('#addPenguji-table').DataTable().destroy();
      $("#addPengujiData").html("");
    },
    error: function(xhr, status, error) {
      console.error('Request failed. Status: ' + xhr.status);
    }
  });
});
$('#ViewUKT').on('hidden.bs.modal', function() {
  resetPreview('#ViewUKT');
});
$('#EditUKT').on('hidden.bs.modal', function() {
  resetPreview('#EditUKT');
});

function resetPreview(modalId) { // Function Reset Preview Dropdown Value

  var isExist = $('#selectize-dropdown9').length > 0 && $('#selectize-dropdown10').length > 0 && $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;

  if (isExist) {
    var selectizeInstance = $('#selectize-dropdown9')[0].selectize; // DAERAH ADD
    var selectizeInstance2 = $('#selectize-dropdown10')[0].selectize; // CABANG ADD
    var selectizeInstance3 = $('#selectize-dropdown11')[0].selectize; // DAERAH EDIT
    var selectizeInstance4 = $('#selectize-dropdown12')[0].selectize; // CABANG EDIT
  }
  var tingkatanAdd = $('#selectize-dropdown2')[0].selectize; // TINGKATAN ADD
  var tingkatanEdit = $('#selectize-dropdown6')[0].selectize; // TINGKATAN EDIT
  var anggotaAdd = $('#selectize-dropdown4')[0].selectize; // ANGGOTA ADD
  var anggotaEdit = $('#selectize-dropdown5')[0].selectize; // ANGGOTA EDIT
  var lokasiAdd = $('#selectize-dropdown8')[0].selectize; // LOKASI ADD
  var lokasiEdit = $('#selectize-dropdown7')[0].selectize; // LOKASI EDIT

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
  anggotaAdd.clear();
  lokasiAdd.clear();
  tingkatanEdit.clear();
  anggotaEdit.clear();
  lokasiEdit.clear();
}

var formSubmitted = false; // Flag to indicate whether the form has been submitted

// ----- Start of UKT Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  // Set the flag to true indicating that the form has been submitted
  formSubmitted = true;
  // Function to show the full-screen loading overlay with a progress bar
  function showLoadingOverlay(message) {
    var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
    $('body').append(overlayHtml);
  }

  $(formId).submit(function (event) {
    // Example usage:
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    var UKT_ID; // Declare UKT here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        UKT_ID = parts[1]; // Assign value to UKT_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableukt.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ukt-table').DataTable().destroy();
              $("#uktdata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function (xhr, status, error) {
              // Handle any errors
            }
          });

          // Hide the loading overlay after the initial processing
          hideLoadingOverlay();
          
        } else {
          // Display error notification
          failedNotification(response);

          // Hide the loading overlay in case of an error
          hideLoadingOverlay();
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

function handleFormKoordinator(formId, successNotification, failedNotification, updateNotification) {
  // Set the flag to true indicating that the form has been submitted
  formSubmitted = true;
  // Function to show the full-screen loading overlay with a progress bar
  function showLoadingOverlay(message) {
    var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
    $('body').append(overlayHtml);
  }

  $(formId).submit(function (event) {
    // Example usage:
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    var UKT_ID; // Declare UKT_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        UKT_ID = parts[1]; // Assign value to UKT_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableuktkoor.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ukt-table').DataTable().destroy();
              $("#uktdatakoor").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function (xhr, status, error) {
              // Handle any errors
            }
          });

          // Hide the loading overlay after the initial processing
          hideLoadingOverlay();

          // Example usage:
          showLoadingOverlay('Proses pembuatan dokumen...');

          // Save PDF to Drive and send email notification concurrently
          Promise.all([savePDFToDrive(UKT_ID)])
            .then(function (responses) {
              // 
            })
            .catch(function (errors) {
              // Handle errors
              for (const error of errors) {
                errorNotification(error);
              }
            })
            .finally(function () {
              // Hide the loading overlay after all asynchronous tasks are complete
              hideLoadingOverlay();
            });
          
        } else {
          // Display error notification
          failedNotification(response);

          // Hide the loading overlay in case of an error
          hideLoadingOverlay();
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

function handleFormGuru(formId, successNotification, failedNotification, updateNotification) {
  // Set the flag to true indicating that the form has been submitted
  formSubmitted = true;
  // Function to show the full-screen loading overlay with a progress bar
  function showLoadingOverlay(message) {
    var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
    $('body').append(overlayHtml);
  }

  $(formId).submit(function (event) {
    // Example usage:
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    var UKT_ID; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        UKT_ID = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableuktguru.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ukt-table').DataTable().destroy();
              $("#uktdataguru").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function (xhr, status, error) {
              // Handle any errors
            }
          });

          // Hide the loading overlay after the initial processing
          hideLoadingOverlay();
          
        } else {
          // Display error notification
          failedNotification(response);

          // Hide the loading overlay in case of an error
          hideLoadingOverlay();
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

$(document).ready(function() {
  callTable();
  // add UKT
  handleForm('#AddUKT-form', SuccessNotification, FailedNotification, UpdateNotification);
  // edit UKT
  handleForm('#EditUKT-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve UKT Koordinator
  handleFormKoordinator('#ApproveUKTKoordinator-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve UKT Guru
  handleFormGuru('#ApproveUKTGuru-form', UpdateNotification, FailedNotification, UpdateNotification);

  // OnChange Daerah Filtering
  $('#selectize-select3').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect3 = $('#selectize-select3').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizeSelect3[0].selectize;

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

  // OnChange Anggota Add Modal
  $('#selectize-dropdown4').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeAnggota = $('#selectize-dropdown4').selectize();
    var isExist = $('#selectize-dropdown10').length > 0;

    // Get the selected value
    if (isExist) {
      var selectizeCabang = $("#selectize-dropdown10")[0].selectize;
      var selectedCabang = selectizeCabang.getValue();
    } else {
      var selectedCabang = $('#CABANG_KEY').val();
    }
    
    // Get the Selectize instance
    var AnggotaInstance = selectizeAnggota[0].selectize;
    
    // Get the selected values from both dropdowns
    var ANGGOTA_KEY = AnggotaInstance.getValue();

    // Make an AJAX request to fetch data for the second dropdown based on the selected value
    $.ajax({
      type: "POST",
      url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
      data: { ANGGOTA_KEY: ANGGOTA_KEY, CABANG_KEY: selectedCabang },
      success: function(data){
        $("#loadpic").html(data);
      }
    });
  });

  // OnChange Anggota Edit Modal
  $('#selectize-dropdown7').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeAnggota = $('#selectize-dropdown7').selectize();
    var isExist = $('#selectize-dropdown5').length > 0;

    // Get the selected value
    if (isExist) {
      var selectizeCabang = $("#selectize-dropdown5")[0].selectize;
      var selectedCabang = selectizeCabang.getValue();
    } else {
      var selectedCabang = $('#CABANG_KEY').val();
    }
    
    // Get the Selectize instance
    var AnggotaInstance = selectizeAnggota[0].selectize;
    
    // Get the selected values from both dropdowns
    var ANGGOTA_KEY = AnggotaInstance.getValue();

    // Make an AJAX request to fetch data for the second dropdown based on the selected value
    $.ajax({
      type: "POST",
      url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
      data: { ANGGOTA_KEY: ANGGOTA_KEY, CABANG_KEY: selectedCabang },
      success: function(data){
        $("#loadpicedit").html(data);
      }
    });
  });

  // OnChange Daerah Add Modal
  $('#selectize-dropdown9').change(function() {
    // Initialize Selectize on the first dropdown
    var DaerahAddModal = $('#selectize-dropdown9').selectize();
    
    // Get the Selectize instance
    var DaerahAdd = DaerahAddModal[0].selectize;

    // Event listener for the first dropdown change
    DaerahAdd.on('change', function (DaerahAdd) {
        // Make an AJAX request to fetch data for the second dropdown based on the selected value
        $.ajax({
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
            method: 'POST',
            data: { id: DaerahAdd },
            dataType: 'json', // Specify the expected data type as JSON
            success: function (data) {
                // Clear options in the second dropdown
                var CabangAddModal = $('#selectize-dropdown10').selectize();
                var CabangAdd = CabangAddModal[0].selectize;
                CabangAdd.clearOptions();

                // Add new options to the second dropdown
                CabangAdd.addOption(data);

                // Update the value of the second dropdown
                CabangAdd.setValue('');

                // Refresh the Selectize instance to apply changes
                // selectizeInstance2.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cabang data:', status, error);
            }
        });
    });
  });

  // OnChange Daerah Edit Modal
  $('#selectize-dropdown11').change(function() {
    // Initialize Selectize on the first dropdown
    var DaerahEditModal = $('#selectize-dropdown11').selectize();
    
    // Get the Selectize instance
    var DaerahEdit = DaerahEditModal[0].selectize;

    // Event listener for the first dropdown change
    DaerahEdit.on('change', function (selectedDaerah) {
        // Make an AJAX request to fetch data for the second dropdown based on the selected value
        $.ajax({
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
            method: 'POST',
            data: { id: selectedDaerah },
            dataType: 'json', // Specify the expected data type as JSON
            success: function (data) {
                // Clear options in the second dropdown
                var CabangEditModal = $('#selectize-dropdown12').selectize();
                var CabangEdit = CabangEditModal[0].selectize;
                CabangEdit.clearOptions();

                // Add new options to the second dropdown
                CabangEdit.addOption(data);

                // Update the value of the second dropdown
                CabangEdit.setValue('');

                // Refresh the Selectize instance to apply changes
                // selectizeInstance2.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cabang data:', status, error);
            }
        });
    });
  });

  // OnChange Tingkatan Add Modal
  $('#selectize-dropdown2').change(function() {
    // Initialize Selectize on the first dropdown
    var tingkatanAddModal = $('#selectize-dropdown2').selectize();
    var isExist = $('#selectize-dropdown10').length > 0;

    // Get the selected value
    if (isExist) {
      var cabangAddModal = $("#selectize-dropdown10")[0].selectize;
      var cabang = cabangAddModal.getValue();
    } else {
      var cabang = $('#TOKENC').val();
    }
    
    // Get the Selectize instance
    var tingkatanAdd = tingkatanAddModal[0].selectize;
    
    // Get the selected values from both dropdowns
    var tingkatan = tingkatanAdd.getValue();

    // Make an AJAX request to fetch data for the second dropdown based on the selected value
    $.ajax({
      type: "POST",
      url: "module/ajax/transaksi/aktivitas/ukt/aj_getkategoriukt.php",
      data: { tingkatan: tingkatan, cabang: cabang },
      success: function(data){
        // console.log(data);
        $("#addrincianukt").html(data);
      }
    });
  });

  // OnChange Tingkatan Edit Modal
  $('#selectize-dropdown6').change(function() {
    // Initialize Selectize on the first dropdown
    var tingkatanEditModal = $('#selectize-dropdown6').selectize();
    var isExist = $('#selectize-dropdown12').length > 0;
    var tingkatanAwal = $('#editUKT_TINGKATAN').val();
    var key = $('#editUKT_ID').val();

    // Get the selected value
    if (isExist) {
      var cabangEditModal = $("#selectize-dropdown12")[0].selectize;
      var cabang = cabangEditModal.getValue();
    } else {
      var cabang = $('#TOKENC').val();
    }
    
    // Get the Selectize instance
    var tingkatanAdd = tingkatanEditModal[0].selectize;
    
    // Get the selected values from both dropdowns
    var tingkatan = tingkatanAdd.getValue();

    if (tingkatan === tingkatanAwal) {
      // AJAX request to fetch current Tingkatan UKT
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_geteditkategoriukt.php",
        data: { id: key },
        success: function(data){
          // console.log(data);
          $("#editrincianukt").html(data);
        }
      });
      
    } else {
      // AJAX request to fetch new Tingkatan UKT
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getkategoriukt.php",
        data: { tingkatan: tingkatan, cabang: cabang },
        success: function(data){
          // console.log(data);
          $("#editrincianukt").html(data);
        }
      });
      
    }
  });
});

// Tambah Detail Penguji Add Modal
$(document).on("click", ".addtambahdetail", function () {
  var id = $("#TOKEN").val();
  var cab = $("#TOKENC").val();
  // Initialize Selectize on the first dropdown
  var selectizeSelect13 = $('#selectize-dropdown13').selectize();
  
  // Get the Selectize instance
  var selectizeInstance13 = selectizeSelect13[0].selectize;

  var anggotaid = selectizeInstance13.getValue();

  // Create the data object
  var eventdata = {
    id: id,
    cabang: cab,
    anggota: anggotaid,
    ADD_MODAL_PENGUJI: "addmodalpenguji"
  };

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
    data: eventdata,
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/aktivitas/ukt/aj_getpengujiukt.php",
          data: eventdata,
          success: function(response){
            // Destroy the DataTable before updating
            $('#addPenguji-table').DataTable().destroy();
            $("#addPengujiData").html(response);
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

// Hapus Penguji Add Modal
$(document).on("click", ".addhapuspenguji", function () {
  var key = $(this).data('id');
  var cab = $(this).data('cabang');

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
    data: { id: key, DELETE_MODAL_PENGUJI: "deletemodalpenguji" },
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/aktivitas/ukt/aj_getpengujiukt.php",
          data: { cabang: cab },
          success: function(response){
            // Destroy the DataTable before updating
            $('#addPenguji-table').DataTable().destroy();
            $("#addPengujiData").html(response);
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

// Tambah Detail Penguji Edit Modal
$(document).on("click", ".edittambahdetail", function () {
  var id = $("#editUKT_ID").val();
  // Initialize Selectize on the first dropdown
  var selectizeSelect14 = $('#selectize-dropdown14').selectize();
  
  // Get the Selectize instance
  var selectizeInstance14 = selectizeSelect14[0].selectize;

  var anggotaid = selectizeInstance14.getValue();

  // Create the data object
  var eventdata = {
    id: id,
    anggota: anggotaid,
    EDIT_MODAL_PENGUJI: "editmodalpenguji"
  };

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
    data: eventdata,
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/aktivitas/ukt/aj_geteditpengujiukt.php",
          data: eventdata,
          success: function(response){
            // Destroy the DataTable before updating
            $('#editPenguji-table').DataTable().destroy();
            $("#editPengujiData").html(response);
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

// Hapus Penguji Edit Modal
$(document).on("click", ".edithapuspenguji", function () {
  var key = $(this).data('id');
  var ukt = $(this).data('ukt');

  // Perform the AJAX request
  $.ajax({
    type: 'POST',
    url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
    data: { id: key, DELETE_MODAL_PENGUJI: "deletemodalpenguji" },
    success: function(response) {
      // Check the response from the server
      if (response === 'Success') {
        
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/aktivitas/ukt/aj_geteditpengujiukt.php",
          data: { id: ukt },
          success: function(response){
            // Destroy the DataTable before updating
            $('#editPenguji-table').DataTable().destroy();
            $("#editPengujiData").html(response);
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

// Delete UKT
function eventukt(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk mereset / menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      id: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ukt/t_ukt.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          if (value2 === 'reset') {
            UpdateNotification('Data berhasil direset!');
          } else {
            DeleteNotification('Data berhasil dihapus!');
          }
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableukt.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ukt-table').DataTable().destroy();
              $("#uktdata").html(response);
              // Reinitialize Sertifikat Table
              callTable();
            },
            error: function (xhr, status, error) {
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

// View UKT
$(document).on("click", ".open-ViewUKT", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_getdetailukt.php',
    method: 'POST',
    data: { id: key, cabang: cabang },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewDAERAH_KEY").val(data.DAERAH_DESKRIPSI);
      $("#viewCABANG_KEY").val(data.CABANG_DESKRIPSI);
      $("#viewUKT_TANGGAL").val(data.UKT_TANGGAL_DESKRIPSI);
      $("#viewANGGOTA_ID").val(data.ANGGOTA_ID + ' - ' + data.ANGGOTA_NAMA);
      $("#viewTINGKATAN_ID").val(data.UKT_TINGKATAN_NAMA + ' - ' + data.UKT_TINGKATAN_SEBUTAN);
      $("#viewUKT_LOKASI").val(data.UKT_DAERAH + ' - ' + data.UKT_CABANG);
      $("#viewUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#viewUKT_TOTAL").html(data.UKT_TOTAL);
      var iconHtml = '<i class="' + data.UKT_NILAI + '"></i>';
      $("#viewUKT_NILAI").html(iconHtml);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(result){
          $("#loadpicview").html(result);
        }
      });

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewPenguji-table').DataTable().destroy();
          $("#viewPengujiData").html(response);
          // Reinitialize Sertifikat Table
        }
      });
      
      // AJAX request to fetch UKT Detail
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewkategoriukt.php",
        data: { id: key },
        success: function(data){
          // console.log(data);
          $("#viewrincianukt").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Edit UKT
$(document).on("click", ".open-EditUKT", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_getdetailukt.php',
    method: 'POST',
    data: { id: key },
    success: function(data) {
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      // Check if the administrator-specific elements exist
      var isExist = $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;

      $("#editUKT_ID").val(data.UKT_ID);
      $("#datepicker42").val(data.UKT_TANGGAL);
      $(".modal-body #selectize-dropdown6")[0].selectize.setValue(data.TINGKATAN_ID);
      $(".modal-body #selectize-dropdown7")[0].selectize.setValue(data.UKT_LOKASI);
      $("#editUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#editUKT_TINGKATAN").val(data.TINGKATAN_ID);

      if (isExist) {

        $(".modal-body #selectize-dropdown11")[0].selectize.setValue(data.DAERAH_KEY);
        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
          $(".modal-body #selectize-dropdown12")[0].selectize.setValue(data.CABANG_KEY);
          
          // After setting the value for selectize-dropdown5, set the value for selectize-dropdown7
          setTimeout(function () {
            $(".modal-body #selectize-dropdown5")[0].selectize.setValue(data.ANGGOTA_ID);
          }, 200); // You may need to adjust the delay based on your application's behavior
          
        }, 300); // You may need to adjust the delay based on your application's behavior

      } else {
        $(".modal-body #selectize-dropdown5")[0].selectize.setValue(data.ANGGOTA_ID);
        
      }

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
        success: function(result){
          $("#loadpicedit").html(result);
        }
      });

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_geteditpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#editPenguji-table').DataTable().destroy();
          $("#editPengujiData").html(response);
          // Reinitialize Sertifikat Table
        }
      });

      // AJAX request to fetch UKT Detail
      setTimeout(function () {
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/aktivitas/ukt/aj_geteditkategoriukt.php",
          data: { id: key },
          success: function(data){
            // console.log(data);
            $("#editrincianukt").html(data);
          }
        });
      }, 200); // You may need to adjust the delay based on your application's behavior
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve UKT Koordinator
$(document).on("click", ".open-ApproveUKTKoordinator", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_getdetailukt.php',
    method: 'POST',
    data: { id: key, cabang: cabang },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewUKT_ID").val(data.UKT_ID);
      $("#viewDAERAH_KEY").val(data.DAERAH_DESKRIPSI);
      $("#viewCABANG_KEY").val(data.CABANG_DESKRIPSI);
      $("#viewUKT_TANGGAL").val(data.UKT_TANGGAL_DESKRIPSI);
      $("#viewANGGOTA_ID").val(data.ANGGOTA_ID + ' - ' + data.ANGGOTA_NAMA);
      $("#viewTINGKATAN_ID").val(data.UKT_TINGKATAN_NAMA + ' - ' + data.UKT_TINGKATAN_SEBUTAN);
      $("#viewUKT_LOKASI").val(data.UKT_DAERAH + ' - ' + data.UKT_CABANG);
      $("#viewUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#viewUKT_TOTAL").html(data.UKT_TOTAL);
      var iconHtml = '<i class="' + data.UKT_NILAI + '"></i>';
      $("#viewUKT_NILAI").html(iconHtml);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(result){
          $("#loadpicview").html(result);
        }
      });

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewPenguji-table').DataTable().destroy();
          $("#viewPengujiData").html(response);
          // Reinitialize Sertifikat Table
        }
      });
      
      // AJAX request to fetch UKT Detail
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewkategoriukt.php",
        data: { id: key },
        success: function(data){
          // console.log(data);
          $("#viewrincianukt").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve UKT Guru
$(document).on("click", ".open-ApproveUKTGuru", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_getdetailukt.php',
    method: 'POST',
    data: { id: key, cabang: cabang },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewUKT_ID").val(data.UKT_ID);
      $("#viewDAERAH_KEY").val(data.DAERAH_DESKRIPSI);
      $("#viewCABANG_KEY").val(data.CABANG_DESKRIPSI);
      $("#viewUKT_TANGGAL").val(data.UKT_TANGGAL_DESKRIPSI);
      $("#viewANGGOTA_ID").val(data.ANGGOTA_ID + ' - ' + data.ANGGOTA_NAMA);
      $("#viewTINGKATAN_ID").val(data.UKT_TINGKATAN_NAMA + ' - ' + data.UKT_TINGKATAN_SEBUTAN);
      $("#viewUKT_LOKASI").val(data.UKT_DAERAH + ' - ' + data.UKT_CABANG);
      $("#viewUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#viewUKT_TOTAL").html(data.UKT_TOTAL);
      var iconHtml = '<i class="' + data.UKT_NILAI + '"></i>';
      $("#viewUKT_NILAI").html(iconHtml);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(result){
          $("#loadpicview").html(result);
        }
      });

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewPenguji-table').DataTable().destroy();
          $("#viewPengujiData").html(response);
          // Reinitialize Sertifikat Table
        }
      });
      
      // AJAX request to fetch UKT Detail
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewkategoriukt.php",
        data: { id: key },
        success: function(data){
          // console.log(data);
          $("#viewrincianukt").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// UKT Filtering
// UKT FILTERING TRANSACTION
$('.filterUKT select, .filterUKT input').on('change input', debounce(filterUKTEvent, 500));
function filterUKTEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const cabangUKT = $('#selectize-dropdown').val();
  const ukt = $('#filterUKT_ID').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const tingkatan = $('#selectize-select').val();
  const tanggal = $('#datepicker43').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    UKT_LOKASI: cabangUKT,
    UKT_ID: ukt,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    TINGKATAN_ID: tingkatan,
    UKT_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableukt.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ukt-table').DataTable().destroy();
      $("#uktdata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}
// UKT FILTERING KOORDINATOR
$('.filterUKTKoordinator select, .filterUKTKoordinator input').on('change input', debounce(filterUKTKoordinatorEvent, 500));
function filterUKTKoordinatorEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const cabangUKT = $('#selectize-dropdown').val();
  const ukt = $('#filterUKT_ID').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const tingkatan = $('#selectize-select').val();
  const tanggal = $('#datepicker43').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    UKT_LOKASI: cabangUKT,
    UKT_ID: ukt,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    TINGKATAN_ID: tingkatan,
    UKT_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableuktkoor.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ukt-table').DataTable().destroy();
      $("#uktdatakoor").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}
// UKT FILTERING GURU
$('.filterUKTGuru select, .filterUKTGuru input').on('change input', debounce(filterUKTGuruEvent, 500));
function filterUKTGuruEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const cabangUKT = $('#selectize-dropdown').val();
  const ukt = $('#filterUKT_ID').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const tingkatan = $('#selectize-select').val();
  const tanggal = $('#datepicker43').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    UKT_LOKASI: cabangUKT,
    UKT_ID: ukt,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    TINGKATAN_ID: tingkatan,
    UKT_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableuktguru.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ukt-table').DataTable().destroy();
      $("#uktdataguru").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

// ----- Function to reset form ----- //
function clearForm() {
  
  // Check if the administrator-specific elements exist
  var isExist = $('#selectize-select3').length > 0 && $('#selectize-select2').length > 0;

  if (isExist) {
    var selectizeInstance1 = $('#selectize-select3')[0].selectize;
    var selectizeInstance2 = $('#selectize-select2')[0].selectize;
    var selectizeInstance3 = $('#selectize-select')[0].selectize;
    var selectizeInstance5 = $('#selectize-dropdown')[0].selectize;
  } else {
    var selectizeInstance3 = $('#selectize-select')[0].selectize;
    var selectizeInstance5 = $('#selectize-dropdown')[0].selectize;

  }
  
  // Clear the fifth Selectize dropdown
  if (selectizeInstance5) {
    selectizeInstance5.clear();
  }
  // Clear the second Selectize dropdown
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }
  // Clear the second Selectize dropdown
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  // Clear the third Selectize dropdown
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }
  
  // JavaScript to reset all forms with the class "resettable-form"
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  filterUKTEvent();
  filterUKTKoordinatorEvent();
  filterUKTGuruEvent();
}
// ----- End of function to reset form ----- //

// ----- End of UKT Section ----- //