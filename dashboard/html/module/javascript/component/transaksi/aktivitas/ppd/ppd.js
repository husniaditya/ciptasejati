// PPD DataTable (server-side)
let ppdDt = null;
let lapppdDt = null;
function initPPDTable() {
  // Only initialize server-side on standard PPD pages (filter form present)
  if (!$('.filterPPD').length) return;
  const $tbl = $('#ppd-table');
  if (!$tbl.length) return;
  if ($.fn.DataTable.isDataTable($tbl)) {
    ppdDt = $tbl.DataTable();
    return;
  }
  ppdDt = $tbl.DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    paging: true,
    scrollX: true,
    scrollY: '350px',
    buttons: ['copy', 'csv', 'excel', 'pdf'],
    ajax: {
      url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppd_ssp.php',
      type: 'POST',
      data: function(d) {
        d.DAERAH_KEY   = $('#selectize-select3').val();
        d.CABANG_KEY   = $('#selectize-select2').val();
        d.PPD_LOKASI   = $('#selectize-dropdown').val();
        d.PPD_ID       = $('#filterPPD_ID').val();
        d.ANGGOTA_ID   = $('#filterANGGOTA_ID').val();
        d.ANGGOTA_NAMA = $('#filterANGGOTA_NAMA').val();
        d.TINGKATAN_ID = $('#selectize-select').val();
        d.PPD_JENIS    = $('#filterPPD_JENIS').val();
        d.PPD_TANGGAL  = $('#datepicker42').val();
        d._ts = Date.now(); // cache buster
      }
    },
    drawCallback: function() {
      // re-bind any tooltips or dynamic elements if needed
    }
  });
}

// Keep other tables as-is
function initOtherTables() {
  ['#ppdKoor-table'].forEach(id => {
    if ($(id).length && !$.fn.DataTable.isDataTable(id)) {
      $(id).DataTable({
        responsive: true,
        order: [],
        dom: 'Bfrtlip',
        paging: true,
        scrollX: true,
        scrollY: '350px',
        buttons: ['copy', 'csv', 'excel', 'pdf']
      });
    }
  });
}

// Laporan PPD DataTable (server-side)
function initLapPPDTable() {
  // Only initialize if laporan filter present
  if (!$('.filterLapPPD').length) return;
  const $tbl = $('#lapppd-table');
  if (!$tbl.length) return;
  if ($.fn.DataTable.isDataTable($tbl)) {
    lapppdDt = $tbl.DataTable();
    return;
  }
  let reqCounter = 0;
  lapppdDt = $tbl.DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    paging: true,
    scrollX: true,
    scrollY: '350px',
    buttons: ['copy', 'csv', 'excel', 'pdf'],
    ajax: {
      url: 'module/ajax/laporan/aktivitas/ppd/aj_tablelapppd_ssp.php',
      type: 'POST',
      data: function(d) {
        d.requestId   = 'req-' + (++reqCounter);
        d.DAERAH_KEY   = $('#selectize-select3').val();
        d.CABANG_KEY   = $('#selectize-select2').val();
        d.PPD_LOKASI   = $('#selectize-dropdown').val();
        d.PPD_ID       = $('#filterPPD_ID').val();
        d.ANGGOTA_ID   = $('#filterANGGOTA_ID').val();
        d.ANGGOTA_NAMA = $('#filterANGGOTA_NAMA').val();
        d.TINGKATAN_ID = $('#selectize-select').val();
        d.PPD_JENIS    = $('#filterPPD_JENIS').val();
        d.PPD_TANGGAL  = $('#datepicker42').val();
        d._ts = Date.now();
      }
    },
    columns: [
      { data: 0, orderable: false }, // action
      { data: 1 }, // No Dokumen + badges
      { data: 2 }, // ID Anggota
      { data: 3 }, // Nama
      { data: 4 }, // Daerah
      { data: 5 }, // Cabang
      { data: 6 }, // Ranting
      { data: 7 }, // Jenis
      { data: 8 }, // Tingkatan
      { data: 9 }, // Tingkatan PPD
      { data: 10 }, // Cabang PPD
      { data: 11 }, // Tanggal
      { data: 12 }, // Deskripsi
      { data: 13 }, // Dokumen UKT
      { data: 14 }, // Tanggal UKT
      { data: 15 }, // No Sertifikat
      { data: 16 }, // Input Oleh
      { data: 17 }, // Input Tanggal
    ]
  });
}

// Simple initializer for static tables used elsewhere on the page(s)
function initSimpleTable(selector) {
  const $t = $(selector);
  if ($t.length && !$.fn.DataTable.isDataTable($t)) {
    $t.DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px',
      buttons: ['copy', 'csv', 'excel', 'pdf']
    });
  }
}

// Fallback HTML reload helper for non-SSP tables (used by Guru/Koordinator pages)
function reloadTable(tableId, url, htmlId, cb) {
  $.post(url, response => {
    if ($.fn.DataTable.isDataTable(tableId)) $(tableId).DataTable().destroy();
    $(htmlId).html(response);
    initSimpleTable(tableId);
    if (cb) cb();
  });
}

$(document).ready(function() {
  initPPDTable();
  initLapPPDTable();
  initOtherTables();

  // Initialize detail table when guru modals are shown
  $('#ApprovePPDGuru, #ViewPPDGuru').on('shown.bs.modal', function () {
    if ($.fn.DataTable.isDataTable('#detailppd-table')) {
      $('#detailppd-table').DataTable().destroy();
    }
    $('#detailppd-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px',
      buttons: ['copy', 'csv', 'excel', 'pdf']
    });
  });
  handleForm('#AddPPD-form', SuccessNotification, FailedNotification, UpdateNotification);
  handleForm('#EditPPD-form', UpdateNotification, FailedNotification, UpdateNotification);
  handleFormApproveKoordinator('#ApprovePPDKoordinator-form', UpdateNotification, FailedNotification, UpdateNotification);
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
          data: JSON.stringify({ // Convert data to JSON string before sending
              CABANG_KEY: CABANG, 
              TINGKATAN_ID: TINGKATAN, 
              PPD_JENIS: JENIS
          }),
          contentType: 'application/json', // Set the Content-Type as JSON
          dataType: 'json', // Specify the expected data type as JSON
          success: function (response) {
            if (response.result.message === "OK" && response.data) {
                // Extract the data array
                var anggotaData = response.data;
                
                // Get the instance of the selectize dropdown
                var selectizeSelect3 = $('#selectize-dropdown4').selectize();
                var selectizeInstance3 = selectizeSelect3[0].selectize;
    
                // Clear any existing options
                selectizeInstance3.clearOptions();
    
                // Add new options to the select dropdown
                selectizeInstance3.addOption(anggotaData);  // Adding the extracted data array
    
                // Optionally set the value to empty
                selectizeInstance3.setValue(''); 
            } else {
                console.error('Error: Invalid response or no data available');
            }
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
          data: JSON.stringify({ // Convert data to JSON string before sending
              CABANG_KEY: CABANG, 
              TINGKATAN_ID: TINGKATAN, 
              PPD_JENIS: JENIS
          }),
          contentType: 'application/json', // Set the Content-Type as JSON
          dataType: 'json', // Specify the expected data type as JSON
          success: function (response) {
              if (response.result.message === "OK" && response.data) {
                  // Extract the data array
                  var anggotaData = response.data;
                  
                  // Get the instance of the selectize dropdown
                  var selectizeSelect3 = $('#selectize-dropdown7').selectize();
                  var selectizeInstance3 = selectizeSelect3[0].selectize;
      
                  // Clear any existing options
                  selectizeInstance3.clearOptions();
      
                  // Add new options to the select dropdown
                  selectizeInstance3.addOption(anggotaData);  // Adding the extracted data array
      
                  // Optionally set the value to empty
                  selectizeInstance3.setValue(''); 
                
              } else {
                console.error('Error: Invalid response or no data available');
              }
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
      var CABANG = $("#TOKENC").val();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: JSON.stringify({ // Convert data to JSON string before sending
              CABANG_KEY: CABANG, 
              TINGKATAN_ID: TINGKATAN, 
              PPD_JENIS: JENIS
          }),
          contentType: 'application/json', // Set the Content-Type as JSON
          dataType: 'json', // Specify the expected data type as JSON
          success: function (response) {
              if (response.result.message === "OK" && response.data) {
                  // Extract the data array
                  var anggotaData = response.data;
                  
                  // Get the instance of the selectize dropdown
                  var selectizeSelect3 = $('#selectize-dropdown4').selectize();
                  var selectizeInstance3 = selectizeSelect3[0].selectize;
      
                  // Clear any existing options
                  selectizeInstance3.clearOptions();
      
                  // Add new options to the select dropdown
                  selectizeInstance3.addOption(anggotaData);  // Adding the extracted data array
      
                  // Optionally set the value to empty
                  selectizeInstance3.setValue(''); 
                
              } else {
                console.error('Error: Invalid response or no data available');
              }
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
      var CABANG = $("#TOKENC").val();
  
      // Make an AJAX request to fetch data for the third dropdown based on the selected values
      // Request For Anggota PPD
      $.ajax({
          url: 'module/ajax/transaksi/aktivitas/ppd/aj_getanggotappd.php',
          method: 'POST',
          data: JSON.stringify({ // Convert data to JSON string before sending
              CABANG_KEY: CABANG, 
              TINGKATAN_ID: TINGKATAN, 
              PPD_JENIS: JENIS
          }),
          contentType: 'application/json', // Set the Content-Type as JSON
          dataType: 'json', // Specify the expected data type as JSON
          success: function (response) {
            if (response.result.message === "OK" && response.data) {
                // Extract the data array
                var anggotaData = response.data;
                
                // Get the instance of the selectize dropdown
                var selectizeSelect7 = $('#selectize-dropdown7').selectize();
                var selectizeInstance7 = selectizeSelect7[0].selectize;
    
                // Clear any existing options
                selectizeInstance7.clearOptions();
    
                // Add new options to the select dropdown
                selectizeInstance7.addOption(anggotaData);  // Adding the extracted data array
    
                // Optionally set the value to empty
                selectizeInstance7.setValue(''); 
              
            } else {
              console.error('Error: Invalid response or no data available');
            }
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

// Utility: reload DataTable and update HTML
function reloadPPDTable(resetPaging=false) {
  if (ppdDt) {
    ppdDt.ajax.reload(null, resetPaging);
  }
}

// Reset Selectize dropdowns utility
function clearSelectize(ids) {
  ids.forEach(id => {
    let s = $(id)[0]?.selectize;
    if (s) s.clear();
  });
}

// Reset preview dropdowns
function resetPreview() {
  clearSelectize([
    '#selectize-dropdown2', '#selectize-dropdown4', '#selectize-dropdown8',
    '#selectize-dropdown6', '#selectize-dropdown7', '#selectize-dropdown11',
    '#selectize-dropdown9', '#selectize-dropdown10', '#selectize-dropdown3', '#selectize-dropdown5'
  ]);
}

// Save PDF to Drive
function savePDFToDrive(PPD_ID) {
  return $.post('module/backend/transaksi/aktivitas/ppd/t_ppdfile.php', { id: PPD_ID });
}

// Send Email Notification
function sendEmailNotification(MUTASI_ID) {
  return $.post('module/backend/transaksi/anggota/mutasianggota/t_mutasimail.php', { MUTASI_ID });
}

// ----- Start of Anggota Section ----- //
function handleForm(formId, successNotification, failedNotification) {
  $(formId).submit(function (event) {
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    event.preventDefault();
    var formData = new FormData(this);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var [successMessage, PPD_ID] = response.split(',');
        if (successMessage === 'Success') {
          successNotification('Data berhasil tersimpan!');
          $(formId.replace("-form", "")).modal('hide');
          reloadPPDTable(true);
        } else {
          failedNotification(response);
        }
        hideLoadingOverlay();
      },
      error: function () { hideLoadingOverlay(); }
    });
  });
}

function handleFormApproveKoordinator(formId, successNotification, failedNotification) {
  $(formId).submit(function (event) {
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    event.preventDefault();
    var formData = new FormData(this);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var [successMessage, PPD_ID] = response.split(',');
        if (successMessage === 'Success') {
          successNotification('Data berhasil tersimpan!');
          $(formId.replace("-form", "")).modal('hide');
          reloadTable('#ppdKoor-table', 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdKoordinator.php', '#koordinatorppddata', () => {
            showLoadingOverlay('Proses pembuatan dokumen...');
            savePDFToDrive(PPD_ID).always(hideLoadingOverlay);
          });
        } else {
          failedNotification(response);
          hideLoadingOverlay();
        }
      },
      error: function () { hideLoadingOverlay(); }
    });
  });
}

function handleFormApproveGuru(formId, successNotification, failedNotification) {
  $(formId).submit(function (event) {
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    event.preventDefault();
    var formData = new FormData(this);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var [successMessage] = response.split(',');
        if (successMessage === 'Success') {
          successNotification('Data berhasil tersimpan!');
          $(formId.replace("-form", "")).modal('hide');
          reloadPPDTable(true);
        } else {
          failedNotification(response);
        }
        hideLoadingOverlay();
      },
      error: function () { hideLoadingOverlay(); }
    });
  });
}

function handleModalHidden() { resetPreview(); }
$('#EditPPD, #AddPPD').on('hidden.bs.modal', handleModalHidden);

// Delete PPD
function eventppd(value1, value2) {
  if (confirm("Apakah anda yakin untuk mereset / menghapus data ini?")) {
    $.post('module/backend/transaksi/aktivitas/ppd/t_ppd.php', {
      PPD_ID: value1,
      EVENT_ACTION: value2
    }, function(response) {
      if (response === 'Success') {
        value2 === 'cancel' ? UpdateNotification('Data berhasil direset!') : DeleteNotification('Data berhasil dihapus!');
        reloadPPDTable(true);
      } else {
        FailedNotification(response);
      }
    }).fail(xhr => console.error('Request failed. Status: ' + xhr.status));
  }
}

// View PPD
$(document).on("click", ".open-ViewPPD", function () {
  var key = $(this).data('id');
  var cabang = $(this).data('cabang');
  $.ajax({
      url: 'module/ajax/transaksi/aktivitas/ppd/aj_getdetailppd.php',
      method: 'POST',
      data: JSON.stringify({ PPD_ID: key }),
      contentType: 'application/json',
      dataType: 'json',
      headers: {
        'X-Api-Key': '$2y$12$NMxDXU77/MPLgD44nkvdB.jPdB.n5kJLWcYGe8lxBoBiGyk/Jeysu'
      },
      success: function(response) {
        // console.log('response', response);
        
        // Correct the condition to check response.message
        if (response.result.message === "OK" && response.data && response.data.length > 0) {
          var data = response.data[0];
          // Populate the fields with the returned data
          $("#viewPPD_DAERAH").val(data.DAERAH_DESKRIPSI);
          $("#viewPPD_CABANG").val(data.CABANG_DESKRIPSI);
          $("#viewPPD_TANGGAL").val(data.PPD_TANGGAL);
          $("#viewPPD_JENIS").val(data.PPD_JENIS_DESKRIPSI);
          $("#viewPPD_TINGKATAN").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);
          $("#viewPPD_ANGGOTA").val(data.ANGGOTA_NAMA);
          $("#viewPPD_LOKASI").val(data.LOKASI_DAERAH + ' - ' + data.LOKASI_CABANG);
          $("#viewPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

          // Make an AJAX request to fetch data for the second dropdown
          $.ajax({
            type: "POST",
            url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
            data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
            success: function(result){
              // console.log(result);
              $("#loadpicview").html(result);
            }
          });
        } else {
          console.error('Error: Invalid response or no data available');
        }
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
    data: JSON.stringify({ // Convert data to JSON string before sending
      PPD_ID: key
    }),
    contentType: 'application/json', // Set the Content-Type as JSON
    dataType: 'json', // Specify the expected data type as JSON
    headers: {
      'X-Api-Key': '$2y$12$NMxDXU77/MPLgD44nkvdB.jPdB.n5kJLWcYGe8lxBoBiGyk/Jeysu'
    },
    success: function(response) {
      // console.log('response', response);
      if (response.result.message === "OK" && response.data && response.data.length > 0) {
        var data = response.data[0];

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
            }, 400); // You may need to adjust the delay based on your application's behavior
            
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
        
      } else {
        console.error('Error: Invalid response or no data available');
      }
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
    data: JSON.stringify({ // Convert data to JSON string before sending
      PPD_ID: key
    }),
    contentType: 'application/json', // Set the Content-Type as JSON
    dataType: 'json', // Specify the expected data type as JSON
    headers: {
      'X-Api-Key': '$2y$12$NMxDXU77/MPLgD44nkvdB.jPdB.n5kJLWcYGe8lxBoBiGyk/Jeysu'
    },
    success: function(response) {
      // console.log('response', data);
      if (response.result.message === "OK" && response.data && response.data.length > 0) {
        var data = response.data[0];

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
        
      } else {
        console.error('Error: Invalid response or no data available');
      }
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
    data: JSON.stringify({ // Convert data to JSON string before sending
      id: key
    }),
    contentType: 'application/json', // Set the Content-Type as JSON
    dataType: 'json', // Specify the expected data type as JSON
    success: function(response) {
      // console.log('response', data);
      if (response.result.message === "OK" && response.data && response.data.length > 0) {
        var data = response.data[0];

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
        
      } else {
        console.error('Error: Invalid response or no data available');
      }
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Filtering utilities
function filterPPDEvent() {
  // server-side reload
  reloadPPDTable(true);
}

function filterPPDKoordinatorEvent() {
  const formData = {
    DAERAH_KEY: $('#selectize-select3').val(),
    CABANG_KEY: $('#selectize-select2').val(),
    PPD_LOKASI: $('#selectize-dropdown').val(),
    PPD_ID: $('#filterPPD_ID').val(),
    ANGGOTA_ID: $('#filterANGGOTA_ID').val(),
    ANGGOTA_NAMA: $('#filterANGGOTA_NAMA').val(),
    TINGKATAN_ID: $('#selectize-select').val(),
    PPD_JENIS: $('#filterPPD_JENIS').val(),
    PPD_TANGGAL: $('#datepicker42').val()
  };
  reloadTable('#ppdKoor-table', 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdKoordinator.php', '#koordinatorppddata');
}

function filterPPDGuruEvent() {
  const cabangPPD = $('#selectize-dropdown').val();
  const tanggal = $('#selectize-dropdown2').val();
  const formData = { PPD_LOKASI: cabangPPD, PPD_TANGGAL: tanggal };
  reloadTable('#ppd-table', 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuru.php', '#ppddata');
}

function filterPPDGuruReportEvent() {
  const cabangPPD = $('#selectize-dropdown').val();
  const tanggal = $('#selectize-dropdown2').val();
  const formData = { PPD_LOKASI: cabangPPD, PPD_TANGGAL: tanggal };
  reloadTable('#ppd-table', 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdGuruReport.php', '#ppddata');
}

function filterLapPPDEvent() {
  if (lapppdDt) { lapppdDt.ajax.reload(null, true); }
}

// Attach debounced event handlers
$('.filterPPD select, .filterPPD input').on('change input', debounce(filterPPDEvent, 500));
$('.filterPPDKoordinator select, .filterPPDKoordinator input').on('change input', debounce(filterPPDKoordinatorEvent, 500));
$('.filterPPDGuru select, .filterPPDGuru input').on('change input', debounce(filterPPDGuruEvent, 500));
$('.filterPPDGuruReport select, .filterPPDGuruReport input').on('change input', debounce(filterPPDGuruReportEvent, 500));
$('.filterLapPPD select, .filterLapPPD input').on('change input', debounce(filterLapPPDEvent, 500));

// ----- Function to reset form ----- //
function clearForm() {
  clearSelectize(['#selectize-select3', '#selectize-select2', '#selectize-select', '#selectize-dropdown']);
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  if ($.fn.DataTable.isDataTable('#ppd-table')) filterPPDEvent();
  if ($.fn.DataTable.isDataTable('#ppdKoor-table')) filterPPDKoordinatorEvent();
}

function clearFormGuru() {
  clearSelectize(['#selectize-dropdown', '#selectize-dropdown2']);
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  filterPPDGuruEvent();
}

function clearFormReportGuru() {
  clearSelectize(['#selectize-dropdown', '#selectize-dropdown2']);
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  filterPPDGuruReportEvent();
}

function clearLapForm() {
  clearSelectize(['#selectize-select3', '#selectize-select2', '#selectize-select', '#selectize-dropdown']);
  document.querySelectorAll('.resettable-form').forEach(form => form.reset());
  if (lapppdDt) { lapppdDt.ajax.reload(null, true); }
}

// ...existing code for showLoadingOverlay/hideLoadingOverlay and other helpers...
// ----- End of Anggota Section ----- //