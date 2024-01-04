

// Kas Anggota Table
function callTable() {
  $('#kasanggota-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
  });
}

function populateFields() { // Funciton Populate Fields
  var selectize = $("#selectize-dropdown")[0].selectize;

  // Get the selected value
  var selectedValue = selectize.getValue();

  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getdetailanggota.php',
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

function populateSaldoAwal() { // Funciton Populate Fields
  var selectize = $("#selectize-dropdown")[0].selectize;

  // Get the selected value
  var selectedValue = selectize.getValue();
  var jenisKas = $("#KAS_JENIS").val();

  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getsaldoawal.php',
    method: 'POST',
    data: { ANGGOTA_KEY: selectedValue, KAS_JENIS: jenisKas },
    success: function(data) {
      $("#KAS_SALDOAWAL").val(data.SALDOAWAL);
      // Trigger the main calculation when the saldoawal is populated
      $("#KAS_JUMLAH, #KAS_DK, #KAS_JENIS").trigger("input");
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
}

function populateSaldoAwalEdit() { // Funciton Populate Fields
  var selectize = $("#selectize-dropdown4")[0].selectize;

  // Get the selected value
  var selectedValue = selectize.getValue();
  var jenisKas = $("#editKAS_JENIS").val();
  var key = $("#editKAS_ID").val();

  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getsaldoawal.php',
    method: 'POST',
    data: { ANGGOTA_KEY: selectedValue, KAS_JENIS: jenisKas, KAS_ID: key },
    success: function(data) {
      $("#editKAS_SALDOAWAL").val(data.SALDOAWAL);
      // Trigger the main calculation when the saldoawal is populated
      $("#editKAS_JUMLAH, #editKAS_DK, #editKAS_JENIS").trigger("input");
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
}

function resetPreview() { // Function Reset Preview Dropdown Value
  var selectizeInstance = $('#selectize-dropdown')[0].selectize;
  var selectizeInstance4 = $('#selectize-dropdown4')[0].selectize;

  if (selectizeInstance) {
    selectizeInstance.clear();
  }
  if (selectizeInstance4) {
    selectizeInstance4.clear();
  }
}

// Assuming you have a Bootstrap modal with the ID "myModal"

$('#EditKasAnggota').on('hidden.bs.modal', handleModalHidden);
$('#AddKasAnggota').on('hidden.bs.modal', handleModalHidden);
function handleModalHidden() {
  resetPreview();
}

// Function to add thousand separators
function addThousandSeparator(value) {
  return value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Function to handle the input event
function handleInput(event) {
  // Get the input value
  let inputValue = event.target.value;

  // Remove non-numeric characters
  inputValue = inputValue.replace(/[^\d]/g, '');

  // Format the number with a thousand separator
  inputValue = addThousandSeparator(inputValue);

  // Update the input value
  event.target.value = inputValue;
}

// Array of element IDs
const elementIds = ['KAS_JUMLAH', 'editKAS_JUMLAH'];

// Apply the event listener to each element with the specified IDs
elementIds.forEach((id) => {
  document.getElementById(id).addEventListener('input', handleInput);
});


function savePDFToDrive(ID) { // Function Save PDF to Drive
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/kasanggota/t_kasfile.php',
      data: { ID: ID },
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

function sendEmailNotification(ID) { // Function Send Email Notification
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/kasanggota/t_kasmail.php',
      data: { ID: ID },
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

    var ID; // Declare MUTASI_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/kasanggota/t_kasanggota.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        ID = parts[1]; // Assign value to MUTASI_ID

        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/anggota/kasanggota/aj_tablekasanggota.php',
            success: function (response) {
              // Destroy the DataTable before updating
              $('#kasanggota-table').DataTable().destroy();
              $("#kasanggotadata").html(response);
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
          Promise.all([savePDFToDrive(ID), sendEmailNotification(ID)])
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
  // Call the function when the document is ready
  callTable();
  // add Anggota
  handleForm('#AddKasAnggota-form', SuccessNotification, FailedNotification, UpdateNotification);
  // edit Anggota
  handleForm('#EditKasAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);
  // Approve Anggota
  handleForm('#ApproveMutasiAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Attach an event listener to the "Jumlah" input
  function updateSaldoAkhir(jumlahElement, saldoAwalElement, kategoriElement, saldoAkhirElement) {
    // Get values
    var saldoAwal = parseFloat($(saldoAwalElement).val().replace(/\D/g, '')) || 0;
    var jumlah = parseFloat($(jumlahElement).val().replace(/\D/g, '')) || 0;
    var kategori = $(kategoriElement).val() || "";
  
    // Perform the calculation based on the selected dropdown value
    var saldoAkhir;
    if (kategori === "D") {
      saldoAkhir = saldoAwal + jumlah; // Sum for "Debit"
    } else {
      saldoAkhir = saldoAwal - jumlah; // Subtraction for "Kredit"
    }
  
    // Format the result with parentheses for negative values
    var formattedSaldoAkhir = saldoAkhir < 0 ? '(' + Math.abs(saldoAkhir).toLocaleString() + ')' : saldoAkhir.toLocaleString();
  
    // Update the "Saldo Akhir" input
    $(saldoAkhirElement).val(formattedSaldoAkhir);
  
    // Add red color to the text if negative
    if (saldoAkhir < 0) {
      $(saldoAkhirElement).css("color", "red");
    } else {
      $(saldoAkhirElement).css("color", ""); // Reset color to default if positive
    }
  }
  
  // Attach an event listener to the "Jumlah" input for the first modal
  $("#KAS_JUMLAH, #KAS_DK, #KAS_JENIS").on("input change", function () {
  updateSaldoAkhir("#KAS_JUMLAH", "#KAS_SALDOAWAL", "#KAS_DK", "#KAS_SALDOAKHIR");
  });
  
  // Attach an event listener to the "Jumlah" input for the second modal
  $("#editKAS_JUMLAH, #editKAS_DK, #editKAS_JENIS").on("input change", function () {
    updateSaldoAkhir("#editKAS_JUMLAH", "#editKAS_SALDOAWAL", "#editKAS_DK", "#editKAS_SALDOAKHIR");
  });
  
  // Additional event listener for the "change" event on dropdowns for the first modal
  $("#KAS_DK").on("change", function () {
    // Trigger the main calculation when the dropdown changes
    $("#KAS_JUMLAH, #KAS_DK").trigger("input");
  });
  
  // Additional event listener for the "change" event on dropdowns for the second modal
  $("#editKAS_DK").on("change", function () {
    // Trigger the main calculation when the dropdown changes
    $("#editKAS_JUMLAH, #editKAS_DK").trigger("input");
  });

  // DROPDOWN ADD MUTASI ANGGOTA
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

  // Event listener for the first dropdown change
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
            data: { daerah_id: selectedDaerah },
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
});

// Delete Anggota
function eventkas(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      KAS_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/kasanggota/t_kasanggota.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/anggota/kasanggota/aj_tablekasanggota.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#kasanggota-table').DataTable().destroy();
              $("#kasanggotadata").html(response);
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
$(document).on("click", ".open-ViewKasAnggota", function () {
  
  var key = $(this).data('id');
  var anggota = $(this).data('anggota');
  var jenis = $(this).data('jenis');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getdetailkas.php',
    method: 'POST',
    data: { KAS_ID: key, ANGGOTA_KEY: anggota, KAS_JENIS: jenis },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewDAERAH_KEY").val(data.DAERAH_KEY);
      $("#viewCABANG_KEY").val(data.CABANG_KEY);
      $("#viewDAERAH_DESKRIPSI").val(data.DAERAH_DESKRIPSI);
      $("#viewCABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $("#viewANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#viewANGGOTA_KEY").val(data.ANGGOTA_IDNAMA);
      $("#viewANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#viewANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#viewTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#viewTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#viewKAS_JENIS").val(data.KAS_JENIS);
      $("#viewKAS_DK").val(data.KAS_DK_DES);
      $("#viewKAS_JUMLAH").val(data.KAS_JUMLAH);
      $("#viewFKAS_JUMLAH").val(data.FKAS_JUMLAH);
      $("#viewKAS_SALDO").val(data.KAS_SALDO);
      $("#viewKAS_SALDOAKHIR").val(data.FKAS_SALDO);
      $("#viewKAS_SALDOAWAL").val(data.SALDOAWAL);
      $("#viewKAS_DESKRIPSI").val(data.KAS_DESKRIPSI);
      $("#viewINPUT_BY").text(data.INPUT_BY);
      $("#viewINPUT_DATE").text(data.INPUT_DATE);

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
$(document).on("click", ".open-EditKasAnggota", function () {
  
  var key = $(this).data('id');
  var anggota = $(this).data('anggota');
  var jenis = $(this).data('jenis');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getdetailkas.php',
    method: 'POST',
    data: { KAS_ID: key, ANGGOTA_KEY: anggota, KAS_JENIS: jenis },
    success: function(data) {
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object

      $("#editKAS_ID").val(data.KAS_ID);
      $("#editDAERAH_KEY").val(data.DAERAH_KEY);
      $("#editCABANG_KEY").val(data.CABANG_KEY);
      $("#editDAERAH_DESKRIPSI").val(data.DAERAH_DESKRIPSI);
      $("#editCABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $(".modal-body #selectize-dropdown4")[0].selectize.setValue(anggota);
      $("#editANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#editANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#editTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#editTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#editKAS_JENIS").val(data.KAS_JENIS);
      $("#editKAS_DK").val(data.KAS_DK);
      $("#editKAS_JUMLAH").val(data.KAS_JUMLAH);
      $("#editFKAS_JUMLAH").val(data.FKAS_JUMLAH);
      $("#editKAS_SALDO").val(data.KAS_SALDO);
      $("#editKAS_SALDOAKHIR").val(data.FKAS_SALDO);
      $("#editKAS_SALDOAWAL").val(data.SALDOAWAL);
      $("#editKAS_DESKRIPSI").val(data.KAS_DESKRIPSI);
      $("#editINPUT_BY").text(data.INPUT_BY);
      $("#editINPUT_DATE").text(data.INPUT_DATE);
      
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

// Mutasi Anggota Filtering
// Attach debounced event handler to form inputs
$('.filterMutasiAnggota select, .filterMutasiAnggota input').on('change input', debounce(filterMutasiAnggotaEvent, 500));
function filterMutasiAnggotaEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const tingkatan = $('#selectize-select').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const status = $('#filterMUTASI_STATUS').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
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
  var selectizeInstance1 = $('#selectize-select3')[0].selectize;
  var selectizeInstance2 = $('#selectize-select2')[0].selectize;
  var selectizeInstance3 = $('#selectize-select')[0].selectize;// Clear the second Selectize dropdown
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