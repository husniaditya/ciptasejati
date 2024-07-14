

// PPD Table
function callTable() {
  $('#ppd-table').DataTable({
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

// Call the function when the document is ready
$(document).ready(function() { // Function Call Table
  callTable();
});

function resetPreview() { // Function Reset Preview Dropdown Value
  var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize; // Add Tingkatan
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

function savePDFToDrive(PPD_ID) { // Function Save PDF to Drive
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppdfile.php',
      data: { id: PPD_ID },
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

function sendEmailNotification(MUTASI_ID) { // Function Send Email Notification
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasimail.php',
      data: { MUTASI_ID: MUTASI_ID },
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

$('#EditPPD').on('hidden.bs.modal', handleModalHidden);
$('#AddPPD').on('hidden.bs.modal', handleModalHidden);

var formSubmitted = false; // Flag to indicate whether the form has been submitted

// ----- Start of Anggota Section ----- //
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

    var PPD_ID; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        PPD_ID = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppd.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ppd-table').DataTable().destroy();
              $("#ppddata").html(response);
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

function handleFormApproveKoordinator(formId, successNotification, failedNotification, updateNotification) {
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

    var PPD_ID; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        PPD_ID = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdKoordinator.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ppd-table').DataTable().destroy();
              $("#koordinatorppddata").html(response);
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
          Promise.all([savePDFToDrive(PPD_ID)])
            .then(function (responses) {
              const pdfResponse = responses[0];

              // Handle the responses if needed
              if (pdfResponse) {
              }
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

function handleFormApproveGuru(formId, successNotification, failedNotification, updateNotification) {
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

    var PPD_ID; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        PPD_ID = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuru.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ppd-table').DataTable().destroy();
              $("#ppddata").html(response);
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

function handleModalHidden() {

  resetPreview();
}

$(document).ready(function() {
  // add PPD
  handleForm('#AddPPD-form', SuccessNotification, FailedNotification, UpdateNotification);
  // edit PPD
  handleForm('#EditPPD-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve PPD Koordinator
  handleFormApproveKoordinator('#ApprovePPDKoordinator-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve PPD Guru
  handleFormApproveGuru('#ApprovePPDGuru-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Event listener for the daerah awal dropdown change
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

  // Event listener for the anggota dropdown change
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
  // Event listener for the anggota dropdown change
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

  // Get Dropdown Cabang from Daerah
  // Event listener for the daerah awal dropdown change
  $('#selectize-dropdown9').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect9 = $('#selectize-dropdown9').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizeSelect9[0].selectize;

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
                var selectizeSelect2 = $('#selectize-dropdown10').selectize();
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

  $('#selectize-dropdown3').change(function() {
    // Initialize Selectize on the first dropdown
    var selectizeSelect9 = $('#selectize-dropdown3').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizeSelect9[0].selectize;

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
                var selectizeSelect2 = $('#selectize-dropdown5').selectize();
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

  // Get Dropdown Anggota
  // Event listener for the daerah awal dropdown change
  
  // Check if the administrator-cabang elements exist
  var isValid = $('#selectize-dropdown10').length > 0;

  if (isValid) {
    $('#selectize-dropdown2, #PPD_JENIS').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect9 = $('#selectize-dropdown10').selectize();
      var selectizeSelect10 = $('#selectize-dropdown2').selectize();
  
      // Get the Selectize instances
      var selectizeInstance9 = selectizeSelect9[0].selectize;
      var selectizeInstance10 = selectizeSelect10[0].selectize;
  
      // Get the selected values from both dropdowns
      var CABANG = selectizeInstance9.getValue();
      var TINGKATAN = selectizeInstance10.getValue();
      var JENIS = $('#PPD_JENIS').val();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { CABANG_KEY: CABANG, TINGKATAN_ID: TINGKATAN, PPD_JENIS: JENIS},
          dataType: 'json', // Specify the expected data type as JSON
          success: function (data) {
              // Clear options in the third dropdown
              var selectizeSelect3 = $('#selectize-dropdown4').selectize();
              var selectizeInstance3 = selectizeSelect3[0].selectize;
              selectizeInstance3.clearOptions();
  
              // Add new options to the third dropdown
              selectizeInstance3.addOption(data);
  
              // Update the value of the third dropdown
              selectizeInstance3.setValue('');
  
              // Refresh the Selectize instance to apply changes
              // selectizeInstance3.refreshOptions();
          },
          error: function(xhr, status, error) {
              console.error('Error fetching cabang data:', status, error);
          }
      });
    });

    $('#selectize-dropdown5, #selectize-dropdown6, #editPPD_JENIS').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect9 = $('#selectize-dropdown5').selectize();
      var selectizeSelect10 = $('#selectize-dropdown6').selectize();
  
      // Get the Selectize instances
      var selectizeInstance9 = selectizeSelect9[0].selectize;
      var selectizeInstance10 = selectizeSelect10[0].selectize;
  
      // Get the selected values from both dropdowns
      var CABANG = selectizeInstance9.getValue();
      var TINGKATAN = selectizeInstance10.getValue();
      var JENIS = $('#editPPD_JENIS').val();

  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { CABANG_KEY: CABANG, TINGKATAN_ID: TINGKATAN, PPD_JENIS: JENIS},
          dataType: 'json', // Specify the expected data type as JSON
          success: function (data) {
              // Clear options in the third dropdown
              var selectizeSelect3 = $('#selectize-dropdown7').selectize();
              var selectizeInstance3 = selectizeSelect3[0].selectize;
              selectizeInstance3.clearOptions();
  
              // Add new options to the third dropdown
              selectizeInstance3.addOption(data);
  
              // Update the value of the third dropdown
              selectizeInstance3.setValue('');
  
              // Refresh the Selectize instance to apply changes
              // selectizeInstance3.refreshOptions();
          },
          error: function(xhr, status, error) {
              console.error('Error fetching cabang data:', status, error);
          }
      });
    });
  } else {
    $('#selectize-dropdown2, #PPD_JENIS').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect10 = $('#selectize-dropdown2').selectize();
  
      // Get the Selectize instances
      var selectizeInstance10 = selectizeSelect10[0].selectize;
  
      // Get the selected values from both dropdowns
      var TINGKATAN = selectizeInstance10.getValue();
      var JENIS = $('#PPD_JENIS').val();
      var CABANG = $('#CABANG_KEY').val();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { TINGKATAN_ID: TINGKATAN, PPD_JENIS: JENIS, CABANG_KEY: CABANG},
          dataType: 'json', // Specify the expected data type as JSON
          success: function (data) {
              // Clear options in the third dropdown
              var selectizeSelect3 = $('#selectize-dropdown4').selectize();
              var selectizeInstance3 = selectizeSelect3[0].selectize;
              selectizeInstance3.clearOptions();
  
              // Add new options to the third dropdown
              selectizeInstance3.addOption(data);
  
              // Update the value of the third dropdown
              selectizeInstance3.setValue('');
  
              // Refresh the Selectize instance to apply changes
              // selectizeInstance3.refreshOptions();
          },
          error: function(xhr, status, error) {
              console.error('Error fetching anggota data:', status, error);
          }
      });
    });

    $('#selectize-dropdown6, #editPPD_JENIS').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect6 = $('#selectize-dropdown6').selectize();
  
      // Get the Selectize instances
      var selectizeInstance6 = selectizeSelect6[0].selectize;
  
      // Get the selected values from both dropdowns
      var TINGKATAN = selectizeInstance6.getValue();
      var JENIS = $('#editPPD_JENIS').val();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { TINGKATAN_ID: TINGKATAN, PPD_JENIS: JENIS },
          dataType: 'json', // Specify the expected data type as JSON
          success: function (data) {
              // Clear options in the third dropdown
              var selectizeSelect7 = $('#selectize-dropdown7').selectize();
              var selectizeInstance7 = selectizeSelect7[0].selectize;
              selectizeInstance7.clearOptions();
  
              // Add new options to the third dropdown
              selectizeInstance7.addOption(data);
  
              // Update the value of the third dropdown
              selectizeInstance7.setValue('');
  
              // Refresh the Selectize instance to apply changes
              // selectizeInstance3.refreshOptions();
          },
          error: function(xhr, status, error) {
              console.error('Error fetching anggota data:', status, error);
          }
      });
    });
  }

  // Event Tambah Anggota onClick
  $('#tambahanggota').on('click', function (event) {
    event.preventDefault(); // Prevent the default form submission
    
    // Initialize Selectize on the first and second dropdowns
    var AnggotaSelect = $('#selectize-dropdown4').selectize();

    // Get the Selectize instances
    var AnggotaInstance = AnggotaSelect[0].selectize;

    // Get the selected values from both dropdowns
    var PPDAnggota = AnggotaInstance.getValue();

    $.ajax({
      url: 'module/ajax/transaksi/aktivitas/ppd/aj_addanggotappd.php',
      type: 'POST',
      data: { ANGGOTA_KEY: PPDAnggota }, // Make sure selectedDaerah is defined somewhere
      success: function (response) {
        // console.log(response);
        $("#dataanggota").html(response);
        // Reinitialize Sertifikat Table
      },
      error: function(xhr, status, error) {
          console.error('Error fetching cabang data:', status, error);
      }
    });

    // Additional step to prevent modal close
    $('#tambahanggota').attr('data-dismiss', ''); // Remove the data-dismiss attribute from the button
  });

  // Event Tambah PIC onClick
  $('#tambahpic').on('click', function (event) {
    event.preventDefault(); // Prevent the default form submission
    
    // Initialize Selectize on the first and second dropdowns
    var PicSelect = $('#selectize-dropdown3').selectize();

    // Get the Selectize instances
    var PicInstance = PicSelect[0].selectize;

    // Get the selected values from both dropdowns
    var PPDPic = PicInstance.getValue();
    
    $.ajax({
      url: 'module/ajax/transaksi/aktivitas/ppd/aj_addpicppd.php',
      type: 'POST',
      data: { ANGGOTA_KEY: PPDPic }, // Make sure selectedDaerah is defined somewhere
      success: function (response) {
        $("#datapic").html(response);
        // Reinitialize Sertifikat Table
      },
      error: function(xhr, status, error) {
          console.error('Error fetching cabang data:', status, error);
      }
    });

    // Additional step to prevent modal close
    $('#tambahpic').attr('data-dismiss', ''); // Remove the data-dismiss attribute from the button
  });
});

// Delete PPD
function eventppd(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk mereset / menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      PPD_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
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
            url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppd.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#ppd-table').DataTable().destroy();
              $("#ppddata").html(response);
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

// View PPD
$(document).on("click", ".open-ViewPPD", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_getdetailppd.php',
    method: 'POST',
    data: { PPD_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewPPD_DAERAH").val(data.DAERAH_DESKRIPSI);
      $("#viewPPD_CABANG").val(data.CABANG_DESKRIPSI);
      $("#viewPPD_TANGGAL").val(data.PPD_TANGGAL);
      $("#viewPPD_JENIS").val(data.PPD_JENIS_DESKRIPSI);
      $("#viewPPD_TINGKATAN").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);
      $("#viewPPD_ANGGOTA").val(data.ANGGOTA_NAMA);
      $("#viewPPD_LOKASI").val(data.LOKASI_DAERAH + ' - ' + data.LOKASI_CABANG);
      $("#viewPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
        success: function(result){
          $("#loadpicview").html(result);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Edit PPD
$(document).on("click", ".open-EditPPD", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_getdetailppd.php',
    method: 'POST',
    data: { PPD_ID: key },
    success: function(data) {
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      // Check if the administrator-specific elements exist
      var isExist = $('#selectize-dropdown3').length > 0 && $('#selectize-dropdown5').length > 0;

      $("#editPPD_ID").val(data.PPD_ID);
      $("#datepicker41").val(data.PPD_TANGGAL);
      $("#editPPD_JENIS").val(data.PPD_JENIS);
      $(".modal-body #selectize-dropdown11")[0].selectize.setValue(data.PPD_LOKASI);
      $("#editPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

      if (isExist) {

        $(".modal-body #selectize-dropdown3")[0].selectize.setValue(data.DAERAH_KEY);
        $(".modal-body #selectize-dropdown6")[0].selectize.setValue(data.TINGKATAN_ID);
        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
          $(".modal-body #selectize-dropdown5")[0].selectize.setValue(data.CABANG_KEY);
          
          // After setting the value for selectize-dropdown5, set the value for selectize-dropdown7
          setTimeout(function () {
            $(".modal-body #selectize-dropdown7")[0].selectize.setValue(data.ANGGOTA_ID);
          }, 200); // You may need to adjust the delay based on your application's behavior
          
        }, 300); // You may need to adjust the delay based on your application's behavior

      } else {
        $(".modal-body #selectize-dropdown6")[0].selectize.setValue(data.TINGKATAN_ID);

        setTimeout(function () {
        $(".modal-body #selectize-dropdown7")[0].selectize.setValue(data.ANGGOTA_ID);
        }, 200); // You may need to adjust the delay based on your application's behavior
        
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
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve PPD Koordinator
$(document).on("click", ".open-ApprovePPDKoordinator", function () {
  
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_getdetailppd.php',
    method: 'POST',
    data: { PPD_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#koordinatorPPD_ID").val(data.PPD_ID);
      $("#koordinatorPPD_DAERAH").val(data.DAERAH_DESKRIPSI);
      $("#koordinatorPPD_CABANG").val(data.CABANG_DESKRIPSI);
      $("#koordinatorPPD_TANGGAL").val(data.PPD_TANGGAL);
      $("#koordinatorPPD_JENIS").val(data.PPD_JENIS_DESKRIPSI);
      $("#koordinatorPPD_TINGKATAN").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);
      $("#koordinatorPPD_ANGGOTA").val(data.ANGGOTA_NAMA);
      $("#koordinatorPPD_LOKASI").val(data.LOKASI_DAERAH + ' - ' + data.LOKASI_CABANG);
      $("#koordinatorPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
        success: function(result){
          $("#loadpicview").html(result);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve PPD Guru
$(document).on("click", ".open-ApprovePPDGuru", function () {
  
  var tanggal = $(this).data('tanggal');
  var lokasi = $(this).data('cabangppd');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuruDetail.php',
    data: { PPD_TANGGAL: tanggal, PPD_LOKASI: lokasi },
    dataType: 'json',
    success: function(response){
      
      $("#guruPPD_LOKASI").val(lokasi);
      $("#guruPPD_LOKASI_DESKRIPSI").val(response.PPD_CABANG);
      $("#guruPPD_TANGGAL").val(tanggal);

      // Destroy the DataTable before updating
      $('#detailppd-table').DataTable().destroy();
      $("#detailppddata").html(response.table_rows);
    }
  });
});

// View PPD Guru
$(document).on("click", ".open-ViewPPDGuru", function () {
  
  var tanggal = $(this).data('tanggal');
  var lokasi = $(this).data('cabangppd');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuruDetailView.php',
    data: { PPD_TANGGAL: tanggal, PPD_LOKASI: lokasi },
    dataType: 'json',
    success: function(response){
      
      $("#guruPPD_LOKASI").val(lokasi);
      $("#guruPPD_LOKASI_DESKRIPSI").val(response.PPD_CABANG);
      $("#guruPPD_TANGGAL").val(tanggal);

      // Destroy the DataTable before updating
      $('#detailppd-table').DataTable().destroy();
      $("#detailppddata").html(response.table_rows);
    }
  });
});

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
          $("#loadpicukt").html(result);
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


// Mutasi Anggota Filtering
// Attach debounced event handler to form inputs
$('.filterPPD select, .filterPPD input').on('change input', debounce(filterPPDEvent, 500));
function filterPPDEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const cabangPPD = $('#selectize-dropdown').val();
  const ppd = $('#filterPPD_ID').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const tingkatan = $('#selectize-select').val();
  const jenis = $('#filterPPD_JENIS').val();
  const tanggal = $('#datepicker42').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    PPD_LOKASI: cabangPPD,
    PPD_ID: ppd,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    TINGKATAN_ID: tingkatan,
    PPD_JENIS: jenis,
    PPD_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppd.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ppd-table').DataTable().destroy();
      $("#ppddata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

$('.filterPPDKoordinator select, .filterPPDKoordinator input').on('change input', debounce(filterPPDKoordinatorEvent, 500));
function filterPPDKoordinatorEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const cabangPPD = $('#selectize-dropdown').val();
  const ppd = $('#filterPPD_ID').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const tingkatan = $('#selectize-select').val();
  const jenis = $('#filterPPD_JENIS').val();
  const tanggal = $('#datepicker42').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    PPD_LOKASI: cabangPPD,
    PPD_ID: ppd,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    TINGKATAN_ID: tingkatan,
    PPD_JENIS: jenis,
    PPD_TANGGAL: tanggal
  };
  // console.log(formData);

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdKoordinator.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ppd-table').DataTable().destroy();
      $("#koordinatorppddata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

$('.filterPPDGuru select, .filterPPDGuru input').on('change input', debounce(filterPPDGuruEvent, 500));
function filterPPDGuruEvent() {
  // Your event handling code here
  const cabangPPD = $('#selectize-dropdown').val();
  const tanggal = $('#selectize-dropdown2').val();

  // Create a data object to hold the form data
  const formData = {
    PPD_LOKASI: cabangPPD,
    PPD_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuru.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ppd-table').DataTable().destroy();
      $("#ppddata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

$('.filterPPDGuruReport select, .filterPPDGuruReport input').on('change input', debounce(filterPPDGuruReportEvent, 500));
function filterPPDGuruReportEvent() {
  // Your event handling code here
  const cabangPPD = $('#selectize-dropdown').val();
  const tanggal = $('#selectize-dropdown2').val();

  // Create a data object to hold the form data
  const formData = {
    PPD_LOKASI: cabangPPD,
    PPD_TANGGAL: tanggal
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuruReport.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#ppd-table').DataTable().destroy();
      $("#ppddata").html(response);
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
  filterPPDEvent();
  filterPPDKoordinatorEvent();
}

function clearFormGuru() {
  
  // Check if the administrator-specific elements exist

  var selectizeInstance1 = $('#selectize-dropdown')[0].selectize;
  var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize;
  
  // Clear the third Selectize dropdown
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  
  // JavaScript to reset all forms with the class "resettable-form"
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  filterPPDGuruEvent();
}

function clearFormReportGuru() {
  
  // Check if the administrator-specific elements exist

  var selectizeInstance1 = $('#selectize-dropdown')[0].selectize;
  var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize;
  
  // Clear the third Selectize dropdown
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  
  // JavaScript to reset all forms with the class "resettable-form"
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  filterPPDGuruReportEvent();
}
// ----- End of function to reset form ----- //

// ----- End of Anggota Section ----- //