

// Mutasi Anggota Table
function callTable() {
    $('#mutasianggota-table').DataTable({
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
        $("#TINGKATAN_ID").val(data.TINGKATAN_NAMA);
        $("#TINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
  
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
        $("#editDAERAH_AWAL_DES").val(data.DAERAH_DESKRIPSI);
        $("#editCABANG_AWAL").val(data.CABANG_KEY);
        $("#editCABANG_AWAL_DES").val(data.CABANG_DESKRIPSI);
        $("#editTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
        $("#editTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
  
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
    var selectizeInstance = $('#selectize-dropdown')[0].selectize; // Add Anggota Dropdown
    var selectizeInstance2 = $('#selectize-dropdown2')[0].selectize; // Add Daerah Tujuan Dropdown
    var selectizeInstance3 = $('#selectize-dropdown3')[0].selectize; // Add Cabang Tujuan Dropdown
    var selectizeInstance5 = $('#selectize-dropdown5')[0].selectize; // Edit Daerah Tujuan Dropdown
    var selectizeInstance6 = $('#selectize-dropdown6')[0].selectize; // Edit Cabang Tujuan Dropdown
  
    var isExist = $('#selectize-dropdown9').length > 0 && $('#selectize-dropdown10').length > 0 && $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;
  
    if (isExist) {
      var selectizeInstance9 = $('#selectize-dropdown9')[0].selectize;
      var selectizeInstance10 = $('#selectize-dropdown10')[0].selectize;
      var selectizeInstance11 = $('#selectize-dropdown11')[0].selectize;
      var selectizeInstance12 = $('#selectize-dropdown12')[0].selectize;
    }
  
    if (selectizeInstance) {
      selectizeInstance.clear();
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
    if (selectizeInstance6) {
      selectizeInstance6.clear();
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
  $('#AddMutasiAnggota').on('hidden.bs.modal', handleModalHidden);
  function handleModalHidden() {
    resetPreview();
  }
  
  
  
  // ----- Start of Anggota Section ----- //
  function handleForm(formId, successNotification, failedNotification, updateNotification) {
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
  
      var MUTASI_ID; // Declare MUTASI_ID here to make it accessible in the outer scope
  
      $.ajax({
        type: 'POST',
        url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasianggota.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function (response) {
          // Split the response into parts using a separator (assuming a dot in this case)
          var parts = response.split(',');
          var successMessage = parts[0];
          MUTASI_ID = parts[1]; // Assign value to MUTASI_ID
  
          // Check the response from the server
          if (successMessage === 'Success') {
            // Display success notification
            successNotification('Data berhasil tersimpan!');
  
            // Close the modal
            $(formId.replace("-form", "")).modal('hide');
  
            // Call the reloadDataTable() function after inserting data to reload the DataTable
            $.ajax({
              type: 'POST',
              url: 'module/ajax/laporan/anggota/mutasianggota/aj_tablemutasianggota.php',
              success: function (response) {
                // Destroy the DataTable before updating
                $('#mutasianggota-table').DataTable().destroy();
                $("#mutasianggotadata").html(response);
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
            Promise.all([savePDFToDrive(MUTASI_ID), sendEmailNotification(MUTASI_ID)])
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
  
  
  $(document).ready(function() {
    // add Anggota
    handleForm('#AddMutasiAnggota-form', SuccessNotification, FailedNotification, UpdateNotification);
    // edit Anggota
    handleForm('#EditMutasiAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);
    // Approve Anggota
    handleForm('#ApproveMutasiAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);
  
    // DROPDOWN ADD MUTASI ANGGOTA
    // Event listener for the first dropdown change
    function initializeDropdownChange(param, mainDropdownId, dependentDropdownId) {
      // Initialize Selectize on the main dropdown
      var mainDropdown = $('#' + mainDropdownId).selectize();
      var mainSelectizeInstance = mainDropdown[0].selectize;
  
      // Event listener for the main dropdown change
      mainSelectizeInstance.on('change', function (selectedValue) {
          // Determine the appropriate URL based on the parameter
          var ajaxUrl = (param === 'daerah') ? 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php' : 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistanggota.php';
  
          // Make an AJAX request to fetch data for the dependent dropdown based on the selected value
          $.ajax({
            url: ajaxUrl,
            method: 'POST',
            data: { id: selectedValue },
            dataType: 'json',
            success: function (data) {
                // Clear options in the dependent dropdown
                var dependentDropdown = $('#' + dependentDropdownId).selectize();
                var dependentSelectizeInstance = dependentDropdown[0].selectize;
                dependentSelectizeInstance.clearOptions();
  
                // Add new options to the dependent dropdown
                dependentSelectizeInstance.addOption(data);
  
                // Update the value of the dependent dropdown
                dependentSelectizeInstance.setValue('');
  
                // Refresh the Selectize instance to apply changes
                // dependentSelectizeInstance.refreshOptions();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', status, error);
            }
        });
      });
    }
  
    var isAdmin = $('#selectize-dropdown9').length > 0 && $('#selectize-dropdown10').length > 0 && $('#selectize-dropdown11').length > 0 && $('#selectize-dropdown12').length > 0;
  
    if (isAdmin) {
      // Call the function for your specific dropdown IDs
      initializeDropdownChange('daerah', 'selectize-dropdown9', 'selectize-dropdown10');
      initializeDropdownChange('cabang', 'selectize-dropdown10', 'selectize-dropdown');
      initializeDropdownChange('daerah', 'selectize-dropdown11', 'selectize-dropdown12');
      initializeDropdownChange('cabang', 'selectize-dropdown12', 'selectize-dropdown4');
      // Add more calls if you have additional dropdowns
    }
  
    // Event listener for the first dropdown change
    $('#selectize-dropdown2').change(function() {
        // Initialize Selectize on the first dropdown
      var selectizedropdown = $('#selectize-dropdown2').selectize(); // GetDaerah Dropdown
      var cabangkey = $('#CABANG_AWAL').val(); // Get Cabang Awal
      
      // Get the Selectize instance
      var selectizeInstance = selectizedropdown[0].selectize;
  
      // Event listener for the first dropdown change
      selectizeInstance.on('change', function (selectedDaerah) {
          // Make an AJAX request to fetch data for the second dropdown based on the selected value
          $.ajax({
              url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getlistcabangtujuan.php',
              method: 'POST',
              data: { daerah_id: selectedDaerah, cabang_key: cabangkey },
              dataType: 'json', // Specify the expected data type as JSON
              success: function (data) {
                  // Clear options in the second dropdown
                  var selectizedropdown3 = $('#selectize-dropdown3').selectize();
                  var selectizeInstance2 = selectizedropdown3[0].selectize;
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
  
          // console.log(selectedDaerah, cabangkey);
      });
    });
  
    // DROPDOWN Edit MUTASI ANGGOTA
    // Event listener for the first dropdown change
    $('#selectize-dropdown5').change(function() {
      // Initialize Selectize on the first dropdown
      var selectizedropdown = $('#selectize-dropdown5').selectize(); // GetDaerah Dropdown
      var cabangkey = $('#editCABANG_AWAL').val(); // Get Cabang Awal
      
      // Get the Selectize instance
      var selectizeInstance = selectizedropdown[0].selectize;
  
      // Event listener for the first dropdown change
      selectizeInstance.on('change', function (selectedDaerah) {
          // Make an AJAX request to fetch data for the second dropdown based on the selected value
          $.ajax({
              url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getlistcabangtujuan.php',
              method: 'POST',
              data: { daerah_id: selectedDaerah, cabang_key: cabangkey },
              dataType: 'json', // Specify the expected data type as JSON
              success: function (data) {
                  // Clear options in the second dropdown
  
                  var selectizedropdown3 = $('#selectize-dropdown6').selectize();
                  var selectizeInstance2 = selectizedropdown3[0].selectize;
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
  
          // console.log(selectedDaerah, cabangkey);
      });
    });
  
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
    // Event listener for the daerah tujuan dropdown change
    $('#selectize-select4').change(function() {
      // Initialize Selectize on the first dropdown
      var selectizeSelect4 = $('#selectize-select4').selectize();
      
      // Get the Selectize instance
      var selectizeInstance = selectizeSelect4[0].selectize;
  
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
                  var selectizeSelect5 = $('#selectize-select5').selectize();
                  var selectizeInstance5 = selectizeSelect5[0].selectize;
                  selectizeInstance5.clearOptions();
  
                  // Add new options to the second dropdown
                  selectizeInstance5.addOption(data);
  
                  // Update the value of the second dropdown
                  selectizeInstance5.setValue('');
  
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
  
        $.ajax({
          type: "POST",
          url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
          data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_AWAL },
          success: function(data){
            $("#loadpicview").html(data);
          }
        });
  
      },
      error: function(error) {
        console.error('Error fetching data:', error);
      }
    });
    
    // console.log(id);
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
      url: 'module/ajax/laporan/anggota/mutasianggota/aj_tablemutasianggota.php',
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
      var selectizeInstance3 = $('#selectize-select')[0].selectize;
    } else {
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
      url: 'module/ajax/laporan/anggota/mutasianggota/aj_tablemutasianggota.php',
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