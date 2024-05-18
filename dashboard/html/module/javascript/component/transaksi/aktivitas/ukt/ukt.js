

// Mutasi Anggota Table
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
}

// Call the function when the document is ready
$(document).ready(function() { // Function Call Table
  callTable();
});

function populateFields() { // Funciton Populate Fields
  var selectize = $("#selectize-dropdown")[0].selectize;
  // Clear the second Selectize dropdown
  var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize;
  var selectizeInstance3 = $('#selectize-dropdown3')[0].selectize;
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }


  // Get the selected value
  var selectedValue = selectize.getValue();

  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailanggota.php',
    method: 'POST',
    data: { ANGGOTA_KEY: selectedValue },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#DAERAH_AWAL").val(data.DAERAH_DESKRIPSI);
      $("#CABANG_AWAL").val(data.CABANG_KEY);
      $("#CABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $("#TINGKATAN_ID").val(data.TINGKATAN_ID);
      $("#TINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#TINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#ANGGOTA_RANTING").val(data.ANGGOTA_RANTING);

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data:'ANGGOTA_KEY='+selectedValue,
        success: function(data){
          $("#loadpic").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
}

function populateFieldsEdit() { // Funciton Populate Fields in Edit Modal
  var selectize = $("#selectize-dropdown4")[0].selectize;
  // Clear the second Selectize dropdown
  var selectizeInstance2 = $('#selectize-dropdown5')[0].selectize;
  var selectizeInstance3 = $('#selectize-dropdown6')[0].selectize;
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }


  // Get the selected value
  var selectedValue = selectize.getValue();

  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailanggota.php',
    method: 'POST',
    data: { ANGGOTA_KEY: selectedValue },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#editDAERAH_AWAL").val(data.DAERAH_DESKRIPSI);
      $("#editCABANG_AWAL").val(data.CABANG_KEY);
      $("#editCABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $("#editTINGKATAN_ID").val(data.TINGKATAN_ID);
      $("#editTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#editTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#editANGGOTA_RANTING").val(data.ANGGOTA_RANTING);

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data:'ANGGOTA_KEY='+selectedValue,
        success: function(data){
          $("#loadpicedit").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
}

function resetPreview() { // Function Reset Preview Dropdown Value
  var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize; // Add Tingkatan
  var selectizeInstance3 = $('#selectize-dropdown3')[0].selectize; // Add Anggota
  var selectizeInstance5 = $('#selectize-dropdown4')[0].selectize; // Add PIC

  var isExist = $('#selectize-dropdown9').length > 0 && $('#selectize-dropdown10').length > 0 && $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;

  if (isExist) {
    var selectizeInstance9 = $('#selectize-dropdown9')[0].selectize;
    var selectizeInstance10 = $('#selectize-dropdown10')[0].selectize;
    var selectizeInstance11 = $('#selectize-dropdown11')[0].selectize;
    var selectizeInstance12 = $('#selectize-dropdown12')[0].selectize;
  }

  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }
  if (selectizeInstance3) {
    selectizeInstance3.clear();
  }
  if (selectizeInstance5) {
    selectizeInstance5.clear();
  }
  if (selectizeInstance9) {
    selectizeInstance9.clear();
  }
  if (selectizeInstance10) {
    selectizeInstance10.clear();
  }
  if (selectizeInstance11) {
    selectizeInstance11.clear();
  }
  if (selectizeInstance12) {
    selectizeInstance12.clear();
  }

  // Get the reference to your HTML table body
  var anggotaTableBody = document.getElementById('anggota-table').getElementsByTagName('tbody')[0];
  var picTableBody = document.getElementById('pic-table').getElementsByTagName('tbody')[0];

  // Clear all rows from the table body
  anggotaTableBody.innerHTML = '';
  picTableBody.innerHTML = '';
}

function savePDFToDrive(MUTASI_ID) { // Function Save PDF to Drive
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasifile.php',
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

$('#EditMutasiAnggota').on('hidden.bs.modal', handleModalHidden);
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

          // Example usage:
          showLoadingOverlay('Proses pembuatan dokumen dan pengiriman email...');

          // Save PDF to Drive and send email notification concurrently
          Promise.all([savePDFToDrive(PPD_ID), sendEmailNotification(PPD_ID)])
            .then(function (responses) {
              const pdfResponse = responses[0];
              const emailResponse = responses[1];

              // Handle the responses if needed
              if (pdfResponse) {
              }

              if (emailResponse === 'Success') {
                MailNotification('Email pemberitahuan berhasil dikirimkan!');
              } else {
                failedNotification(emailResponse);
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

function handleModalHidden() {
  if (formSubmitted) { // Check if the form has not been submitted
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_deletedetailppd.php',
          method: 'POST',
          dataType: 'json',
          success: function(data) {
              // console.log('Data Deleted Status: ' + data);
          },
          error: function(xhr, status, error) {
              console.error('Error deleting data:', status, error);
          }
      });
  }

  resetPreview();
  // Reset the formSubmitted flag for the next form submission
  formSubmitted = false;
}

$(document).ready(function() {
  // add Anggota
  handleForm('#AddPPD-form', SuccessNotification, FailedNotification, UpdateNotification);
  // edit Anggota
  handleForm('#EditMutasiAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve Anggota
  handleForm('#ApproveMutasiAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);

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

  // Get Dropdown Anggota
  // Event listener for the daerah awal dropdown change
  
  // Check if the administrator-cabang elements exist
  var isValid = $('#selectize-dropdown10').length > 0;

  if (isValid) {
    $('#selectize-dropdown10, #selectize-dropdown2').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect9 = $('#selectize-dropdown10').selectize();
      var selectizeSelect10 = $('#selectize-dropdown2').selectize();
  
      // Get the Selectize instances
      var selectizeInstance9 = selectizeSelect9[0].selectize;
      var selectizeInstance10 = selectizeSelect10[0].selectize;
  
      // Get the selected values from both dropdowns
      var CABANG = selectizeInstance9.getValue();
      var TINGKATAN = selectizeInstance10.getValue();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { CABANG_KEY: CABANG, TINGKATAN_ID: TINGKATAN },
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

      // Request For PIC PPD
      $.ajax({
        url: 'module/ajax/transaksi/aktivitas/ppd/aj_getpicppd.php',
        method: 'POST',
        data: { CABANG_KEY: CABANG, TINGKATAN_ID: TINGKATAN },
        dataType: 'json', // Specify the expected data type as JSON
        success: function (data) {
          // Clear options in the third dropdown
          var PICSelect = $('#selectize-dropdown3').selectize();
          var PICInstance = PICSelect[0].selectize;
          PICInstance.clearOptions();

          // Add new options to the third dropdown
          PICInstance.addOption(data);

          // Update the value of the third dropdown
          PICInstance.setValue('');

          // Refresh the Selectize instance to apply changes
          // selectizeInstance3.refreshOptions();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching cabang data:', status, error);
        }
      });
    });
  } else {
    $('#selectize-dropdown2').change(function() {
      // Initialize Selectize on the first and second dropdowns
      var selectizeSelect10 = $('#selectize-dropdown2').selectize();
  
      // Get the Selectize instances
      var selectizeInstance10 = selectizeSelect10[0].selectize;
  
      // Get the selected values from both dropdowns
      var TINGKATAN = selectizeInstance10.getValue();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: { TINGKATAN_ID: TINGKATAN },
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
      // Request For PIC PPD
      $.ajax({
        url: 'module/ajax/transaksi/aktivitas/ppd/aj_getpicppd.php',
        method: 'POST',
        data: { TINGKATAN_ID: TINGKATAN },
        dataType: 'json', // Specify the expected data type as JSON
        success: function (data) {
            // Clear options in the third dropdown
            var PICSelect = $('#selectize-dropdown3').selectize();
            var PICInstance = PICSelect[0].selectize;
            PICInstance.clearOptions();

            // Add new options to the third dropdown
            PICInstance.addOption(data);

            // Update the value of the third dropdown
            PICInstance.setValue('');

            // Refresh the Selectize instance to apply changes
            // selectizeInstance3.refreshOptions();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching cabang data:', status, error);
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

// Delete Anggota
function eventmutasi(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk mereset / menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      MUTASI_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasianggota.php',
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
            url: 'module/ajax/transaksi/anggota/mutasianggota/aj_tablemutasianggota.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#mutasianggota-table').DataTable().destroy();
              $("#mutasianggotadata").html(response);
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

// View Anggota
$(document).on("click", ".open-ViewMutasiAnggota", function () {
  
  var key = $(this).data('id');
  var anggota = $(this).data('anggota');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailmutasi.php',
    method: 'POST',
    data: { MUTASI_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewDAERAH_AWAL_KEY").val(data.DAERAH_AWAL_KEY);
      $("#viewDAERAH_AWAL_DES").val(data.DAERAH_AWAL_DES);
      $("#viewCABANG_AWAL").val(data.CABANG_AWAL);
      $("#viewCABANG_AWAL_DES").val(data.CABANG_AWAL_DES);
      $("#viewDAERAH_TUJUAN_KEY").val(data.DAERAH_TUJUAN_KEY);
      $("#viewDAERAH_TUJUAN_DES").val(data.DAERAH_TUJUAN_DES);
      $("#viewCABANG_TUJUAN").val(data.CABANG_TUJUAN);
      $("#viewCABANG_TUJUAN_DES").val(data.CABANG_TUJUAN_DES);
      $("#viewANGGOTA_KEY").val(data.ANGGOTA_KEY);
      $("#viewANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#viewANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#viewANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#viewANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#viewTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#viewTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#viewMUTASI_DESKRIPSI").val(data.MUTASI_DESKRIPSI);
      $("#viewMUTASI_TANGGAL").val(data.MUTASI_TANGGAL);
      $("#viewTANGGAL_EFEKTIF").val(data.TANGGAL_EFEKTIF);
      $("#viewINPUT_BY").text(data.INPUT_BY);
      $("#viewINPUT_DATE").text(data.INPUT_DATE);
      $("#viewAPPROVE_BY").text(data.APPROVE_BY);
      $("#viewMUTASI_APP_TANGGAL").text(data.MUTASI_APP_TANGGAL);
      $("#viewMUTASI_STATUS_DES").html(data.MUTASI_STATUS_DES);

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data:'ANGGOTA_KEY='+anggota,
    success: function(data){
      $("#loadpicview").html(data);
    }
  });
  
  // console.log(id);
});

// Edit Anggota
$(document).on("click", ".open-EditMutasiAnggota", function () {
  
  var key = $(this).data('id');
  var anggota = $(this).data('anggota');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailmutasi.php',
    method: 'POST',
    data: { MUTASI_ID: key },
    success: function(data) {
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      var daerahawal = data.DAERAH_AWAL_KEY;
      var cabangawal = data.CABANG_AWAL;
      var daerahtujuan = data.DAERAH_TUJUAN_KEY;
      var cabangtujuan = data.CABANG_TUJUAN;

      $("#editMUTASI_ID").val(data.MUTASI_ID);
      $("#editDAERAH_AWAL_KEY").val(data.DAERAH_AWAL_KEY);
      $("#editDAERAH_AWAL_DES").val(data.DAERAH_AWAL_DES);
      $("#editCABANG_AWAL").val(data.CABANG_AWAL);
      $("#editCABANG_AWAL_DES").val(data.CABANG_AWAL_DES);
      $("#editDAERAH_TUJUAN_KEY").val(data.DAERAH_TUJUAN_KEY);
      $("#editDAERAH_TUJUAN_DES").val(data.DAERAH_TUJUAN_DES);
      $("#editCABANG_TUJUAN").val(data.CABANG_TUJUAN);
      $("#editCABANG_TUJUAN_DES").val(data.CABANG_TUJUAN_DES);
      $("#editANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#editANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#editANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#editANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#editTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#editTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#editMUTASI_DESKRIPSI").val(data.MUTASI_DESKRIPSI);
      $("#editMUTASI_TANGGAL").val(data.MUTASI_TANGGAL);
      $("#datepicker5").val(data.MUTASI_TANGGAL);
      $("#editINPUT_BY").text(data.INPUT_BY);
      $("#editINPUT_DATE").text(data.INPUT_DATE);
      $("#editeditAPPROVE_BY").text(data.APPROVE_BY);
      $("#editMUTASI_APP_TANGGAL").text(data.MUTASI_APP_TANGGAL);
      $("#editMUTASI_STATUS_DES").html(data.MUTASI_STATUS_DES);
      
      // Check if the administrator-specific elements exist
      var isExist = $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;

      if (isExist) {

        $(".modal-body #selectize-dropdown11")[0].selectize.setValue(daerahawal);
        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
        $(".modal-body #selectize-dropdown12")[0].selectize.setValue(cabangawal);
        }, 200); // You may need to adjust the delay based on your application's behavior
        setTimeout(function () {
        $(".modal-body #selectize-dropdown4")[0].selectize.setValue(anggota);
        }, 400); // You may need to adjust the delay based on your application's behavior
        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
          $(".modal-body #selectize-dropdown5")[0].selectize.setValue(daerahtujuan);
        }, 500); // You may need to adjust the delay based on your application's behavior
        setTimeout(function () {
          $(".modal-body #selectize-dropdown6")[0].selectize.setValue(cabangtujuan);
        }, 850); // You may need to adjust the delay based on your application's behavior

      } else {
        
        $(".modal-body #selectize-dropdown4")[0].selectize.setValue(anggota);
        // Wait for the options in the second dropdown to be populated before setting its value
        setTimeout(function () {
          $(".modal-body #selectize-dropdown5")[0].selectize.setValue(daerahtujuan);
        }, 100); // You may need to adjust the delay based on your application's behavior

        setTimeout(function () {
          $(".modal-body #selectize-dropdown6")[0].selectize.setValue(cabangtujuan);
        }, 500); // You may need to adjust the delay based on your application's behavior
        
      }
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data:'ANGGOTA_KEY='+anggota,
    success: function(data){
      $("#loadpicedit").html(data);
    }
  });
});

// Approve Mutasi Anggota
$(document).on("click", ".open-ApproveMutasiAnggota", function () {
  
  var key = $(this).data('id');
  var anggota = $(this).data('anggota');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailmutasi.php',
    method: 'POST',
    data: { MUTASI_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#appMUTASI_ID").val(data.MUTASI_ID);
      $("#appDAERAH_AWAL_KEY").val(data.DAERAH_AWAL_KEY);
      $("#appDAERAH_AWAL_DES").val(data.DAERAH_AWAL_DES);
      $("#appCABANG_AWAL").val(data.CABANG_AWAL);
      $("#appCABANG_AWAL_DES").val(data.CABANG_AWAL_DES);
      $("#appDAERAH_TUJUAN_KEY").val(data.DAERAH_TUJUAN_KEY);
      $("#appDAERAH_TUJUAN_DES").val(data.DAERAH_TUJUAN_DES);
      $("#appCABANG_TUJUAN").val(data.CABANG_TUJUAN);
      $("#appCABANG_TUJUAN_DES").val(data.CABANG_TUJUAN_DES);
      $("#appANGGOTA_KEY").val(data.ANGGOTA_KEY);
      $("#appANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#appANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#appANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#appANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#appTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#appTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#appMUTASI_DESKRIPSI").val(data.MUTASI_DESKRIPSI);
      $("#appMUTASI_TANGGAL").val(data.MUTASI_TANGGAL);
      $("#appTANGGAL_EFEKTIF").val(data.TANGGAL_EFEKTIF);
      $("#appINPUT_BY").text(data.INPUT_BY);
      $("#appINPUT_DATE").text(data.INPUT_DATE);
      $("#appAPPROVE_BY").text(data.APPROVE_BY);
      $("#appMUTASI_APP_TANGGAL").text(data.MUTASI_APP_TANGGAL);
      $("#appMUTASI_STATUS_DES").html(data.MUTASI_STATUS_DES);

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data:'ANGGOTA_KEY='+anggota,
    success: function(data){
      $("#loadpicapp").html(data);
    }
  });
});

// Mutasi Anggota Filtering
// Attach debounced event handler to form inputs
$('.filterMutasiAnggota select, .filterMutasiAnggota input').on('change input', debounce(filterMutasiAnggotaEvent, 500));
function filterMutasiAnggotaEvent() {
  // Your event handling code here
  const daerahAwal = $('#selectize-select3').val();
  const cabangAwal = $('#selectize-select2').val();
  const daerahTujuan = $('#selectize-select4').val();
  const cabangTujuan = $('#selectize-select5').val();
  const tingkatan = $('#selectize-select').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const status = $('#filterMUTASI_STATUS').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_AWAL_KEY: daerahAwal,
    CABANG_AWAL_KEY: cabangAwal,
    DAERAH_TUJUAN_KEY: daerahTujuan,
    CABANG_TUJUAN_KEY: cabangTujuan,
    TINGKATAN_ID: tingkatan,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    MUTASI_STATUS: status
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_tablemutasianggota.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#mutasianggota-table').DataTable().destroy();
      $("#mutasianggotadata").html(response);
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
    var selectizeInstance4 = $('#selectize-select4')[0].selectize;
    var selectizeInstance5 = $('#selectize-select5')[0].selectize;
  } else {
    var selectizeInstance3 = $('#selectize-select')[0].selectize;
    var selectizeInstance4 = $('#selectize-select4')[0].selectize;
    var selectizeInstance5 = $('#selectize-select5')[0].selectize;

  }
  
  // Clear the fourth Selectize dropdown
  if (selectizeInstance4) {
    selectizeInstance4.clear();
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

  document.getElementById("filterMutasiAnggota").reset();
  // Call the reloadDataTable() function after inserting data to reload the DataTable
  $.ajax({
    type: 'POST',
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_tablemutasianggota.php',
    success: function(response) {
      // Destroy the DataTable before updating
      $('#mutasianggota-table').DataTable().destroy();
      $("#mutasianggotadata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    },
    error: function(xhr, status, error) {
      // Handle any errors
    }
  });
}
// ----- End of function to reset form ----- //

// ----- End of Anggota Section ----- //