

$(document).on('keyup','.checkpassword',function(){
    var newpassword = $(NEWPASSWORD).val();
    var confirmpassword = $(CONFIRMPASSWORD).val();

    $.ajax({
        type: "POST",
        url: "./module/ajax/header/aj_checkpassword.php",
        data: {NEWPASSWORD :newpassword, CONFIRMPASSWORD:confirmpassword},
        success: function(data){
        $("#passwordcheck").html(data);
        }
        });
        // console.log(newpassword, confirmpassword);
});

function togglePassword(passwordId) {
  var passwordInput = document.getElementById(passwordId);
  var icon = passwordInput.closest('.has-icon').querySelector(".toggle-password i");

  if (passwordInput.type === "password") {
      passwordInput.type = "text";
      icon.classList.remove("fa-lock");
      icon.classList.add("fa-unlock-alt");
  } else {
      passwordInput.type = "password";
      icon.classList.remove("fa-unlock-alt");
      icon.classList.add("fa-lock");
  }
}

function loadAndRefresh() {
    // Load content initially when the document is ready
    $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
    $('#listnotif').load('./module/ajax/header/aj_listnotif.php');

    // Set up an interval to refresh content every 9 seconds
    setInterval(function() {
        $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
        $('#listnotif').load('./module/ajax/header/aj_listnotif.php');
    }, 7000);
}

function savePPDToDrive(PPD_ID) { // Function Save PDF to Drive
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

function saveUKTToDrive(UKT_ID) { // Function Save PDF to Drive
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

function saveMutasiToDrive(MUTASI_ID) { // Function Save PDF to Drive
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

function sendEmailMutasi(MUTASI_ID) { // Function Send Email Notification
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

function handleNotif(formId, successNotification, failedNotification, updateNotification) {
  
  // Function to show the full-screen loading overlay with a progress bar
  function showLoadingOverlay(message) {
    var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
    $('body').append(overlayHtml);
  }

  $(formId).submit(function(event) {
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');

    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasianggota.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function(response) {
        // Split the response into parts using a separator (assuming a dot in this case)
        var parts = response.split(',');
        var successMessage = parts[0];
        var MUTASI_ID = parts[1];
        
        // Check the response from the server
        if (successMessage === 'Success') {
          // Display success notification
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

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
        
          // Hide the loading overlay after the initial processing
          hideLoadingOverlay();

          // Example usage:
          showLoadingOverlay('Proses pembuatan dokumen dan pengiriman email...');

          // Save PDF to Drive and send email notification concurrently
          Promise.all([saveMutasiToDrive(MUTASI_ID), sendEmailMutasi(MUTASI_ID)])
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
        }
      },
      error: function(xhr, status, error) {
          // Display error notification
          failedNotification(xhr, status, error);
      }
    });
  });
}

function ApproveNotifPPDKoordinator(formId, successNotification, failedNotification, updateNotification) {
  // Set the flag to true indicating that the form has been submitted
  formSubmitted = true;

  // Function to show the full-screen loading overlay with a progress bar
  function showLoadingOverlay(message) {
    var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
    $('body').append(overlayHtml);
  }

  $(formId).submit(function (event) {
    showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
    
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    var PPD_ID; // Declare PPD_ID here to make it accessible in the outer scope

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/aktivitas/ppd/t_ppd.php',
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting content type
      success: function (response) {
        var parts = response.split(',');
        var successMessage = parts[0];
        PPD_ID = parts[1]; // Assign value to PPD_ID

        if (successMessage === 'Success') {
          successNotification('Data berhasil tersimpan!');

          // Close the modal
          $(formId.replace("-form", "")).modal('hide');

          if ($.fn.DataTable.isDataTable('#ppdKoor-table')) {
            $.ajax({
              type: 'POST',
              url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppdKoordinator.php',
              success: function (response) {
                $('#ppdKoor-table').DataTable().destroy();
                $("#koordinatorppddata").html(response);
                callTable();
              },
              error: function (xhr, status, error) {
                // Handle any errors
              }
            });
          }

          if ($.fn.DataTable.isDataTable('#ppd-table')) {
            $.ajax({
              type: 'POST',
              url: 'module/ajax/transaksi/aktivitas/ppd/aj_tableppd.php',
              success: function (response) {
                $('#ppd-table').DataTable().destroy();
                $("#ppddata").html(response);
                callTable();
              },
              error: function (xhr, status, error) {
                // Handle any errors
              }
            });
          }

          if ($.fn.DataTable.isDataTable('#lapppd-table')) {
            $.ajax({
              type: 'POST',
              url: 'module/ajax/transaksi/aktivitas/ppd/aj_tablelapppd.php',
              success: function (response) {
                $('#lapppd-table').DataTable().destroy();
                $("#ppddata").html(response);
                callTable();
              },
              error: function (xhr, status, error) {
                // Handle any errors
              }
            });
          }

          hideLoadingOverlay();

          showLoadingOverlay('Proses pembuatan dokumen...');

          Promise.all([savePPDToDrive(PPD_ID)])
            .then(function (responses) {
              const pdfResponse = responses[0];
            })
            .catch(function (errors) {
              for (const error of errors) {
                errorNotification(error);
              }
            })
            .finally(function () {
              hideLoadingOverlay();
            });
        } else {
          failedNotification(response);
          hideLoadingOverlay();
        }
      },
      error: function (xhr, status, error) {
        // Handle any errors
        hideLoadingOverlay();
      }
    });
  });
}

function ApproveNotifUKTKoordinator(formId, successNotification, failedNotification, updateNotification) {
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

          if ($.fn.DataTable.isDataTable('#uktkoor-table')) {
            $.ajax({
              type: 'POST',
              url: 'module/ajax/transaksi/aktivitas/ukt/aj_tableuktkoor.php',
              success: function (response) {
                // Destroy the DataTable before updating
                $('#uktkoor-table').DataTable().destroy();
                $("#uktdatakoor").html(response);
                // Reinitialize Sertifikat Table
                callTable();
              },
              error: function (xhr, status, error) {
                // Handle any errors
              }
            });
          }

          if ($.fn.DataTable.isDataTable('#ukt-table')) {
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
          }
          
          if ($.fn.DataTable.isDataTable('#lapukt-table')) {
            $.ajax({
              type: 'POST',
              url: 'module/ajax/transaksi/aktivitas/ukt/aj_tablelapukt.php',
              success: function (response) {
                // Destroy the DataTable before updating
                $('#lapukt-table').DataTable().destroy();
                $("#uktdata").html(response);
                // Reinitialize Sertifikat Table
                callTable();
              },
              error: function (xhr, status, error) {
                // Handle any errors
              }
            });
          }

          // Hide the loading overlay after the initial processing
          hideLoadingOverlay();

          // Example usage:
          showLoadingOverlay('Proses pembuatan dokumen...');

          // Save PDF to Drive and send email notification concurrently
          Promise.all([saveUKTToDrive(UKT_ID)])
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
  
$(document).ready(function() {
  loadAndRefresh();
  
  // Approve Anggota
  handleNotif('#ApproveNotifMutasi-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Approve PPD Koordinator
  ApproveNotifPPDKoordinator('#ApproveNotifPPDKoordinator-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Approve UKT Koordinator
  ApproveNotifUKTKoordinator('#ApproveNotifUKTKoordinator-form', UpdateNotification, FailedNotification, UpdateNotification);

  $('#ChangeLog').on('shown.bs.modal', function () {
    if ($.fn.DataTable.isDataTable('#logversi-table')) {
      $('#logversi-table').DataTable().destroy();
    }

    $('#logversi-table').DataTable({
      responsive: true,
      order: [],
      dom: 'frtip',
      columnDefs: [
        { width: '10%', targets: 0 }, // Set width for column 1
        { width: '50%', targets: 1 }, // Set width for column 2
        { width: '20%', targets: 2 }, // Set width for column 3
        { width: '20%', targets: 3 }, // Set width for column 4
        { width: '20%', targets: 4 }, // Set width for column 5
        // Add more columnDefs as needed
      ],
      pageLength: 5,
      scrollX: true, // Enable horizontal scrolling
      scrollY: '350px', // Set the desired height here
      scrollCollapse: true, // Enable vertical scrolling
      initComplete: function(settings, json) {
        $('#ChangeLog').modal('handleUpdate');
      }
    });
  });
});

function getNotif(obj) {
  $.ajax({
  type: "POST",
  url: "./module/ajax/header/aj_updatenotif.php",
  data:'NOTIFIKASI_ID='+obj.getAttribute("data-id")
  });
}

// ViewNotifMutasi
$(document).on("click", ".open-ViewNotifMutasi", function () {

  var key = $(this).data('dokumen');
  var anggota = $(this).data('anggota');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailmutasi.php',
    method: 'POST',
    data: { MUTASI_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#viewnotifDAERAH_AWAL_KEY").val(data.DAERAH_AWAL_KEY);
      $("#viewnotifDAERAH_AWAL_DES").val(data.DAERAH_AWAL_DES);
      $("#viewnotifCABANG_AWAL").val(data.CABANG_AWAL);
      $("#viewnotifCABANG_AWAL_DES").val(data.CABANG_AWAL_DES);
      $("#viewnotifDAERAH_TUJUAN_KEY").val(data.DAERAH_TUJUAN_KEY);
      $("#viewnotifDAERAH_TUJUAN_DES").val(data.DAERAH_TUJUAN_DES);
      $("#viewnotifCABANG_TUJUAN").val(data.CABANG_TUJUAN);
      $("#viewnotifCABANG_TUJUAN_DES").val(data.CABANG_TUJUAN_DES);
      $("#viewnotifANGGOTA_KEY").val(data.ANGGOTA_KEY);
      $("#viewnotifANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#viewnotifANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#viewnotifANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#viewnotifANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#viewnotifTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#viewnotifTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#viewnotifMUTASI_DESKRIPSI").val(data.MUTASI_DESKRIPSI);
      $("#viewnotifMUTASI_TANGGAL").val(data.MUTASI_TANGGAL);
      $("#viewnotifTANGGAL_EFEKTIF").val(data.TANGGAL_EFEKTIF);
      $("#viewnotifINPUT_BY").text(data.INPUT_BY);
      $("#viewnotifINPUT_DATE").text(data.INPUT_DATE);
      $("#viewnotifAPPROVE_BY").text(data.APPROVE_BY);
      $("#viewnotifMUTASI_APP_TANGGAL").text(data.MUTASI_APP_TANGGAL);
      $("#viewnotifMUTASI_STATUS_DES").html(data.MUTASI_STATUS_DES);

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_AWAL },
        success: function(data){
          $("#notifloadpicview").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

  // ApproveNotifMutasi
$(document).on("click", ".open-ApproveNotifMutasi", function () {
  
  var key = $(this).data('dokumen');
  var anggota = $(this).data('anggota');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/mutasianggota/aj_getdetailmutasi.php',
    method: 'POST',
    data: { MUTASI_ID: key },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#appnotifMUTASI_ID").val(data.MUTASI_ID);
      $("#appnotifDAERAH_AWAL_KEY").val(data.DAERAH_AWAL_KEY);
      $("#appnotifDAERAH_AWAL_DES").val(data.DAERAH_AWAL_DES);
      $("#appnotifCABANG_AWAL").val(data.CABANG_AWAL);
      $("#appnotifCABANG_AWAL_DES").val(data.CABANG_AWAL_DES);
      $("#appnotifDAERAH_TUJUAN_KEY").val(data.DAERAH_TUJUAN_KEY);
      $("#appnotifDAERAH_TUJUAN_DES").val(data.DAERAH_TUJUAN_DES);
      $("#appnotifCABANG_TUJUAN").val(data.CABANG_TUJUAN);
      $("#appnotifCABANG_TUJUAN_DES").val(data.CABANG_TUJUAN_DES);
      $("#appnotifANGGOTA_KEY").val(data.ANGGOTA_KEY);
      $("#appnotifANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#appnotifANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#appnotifANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#appnotifANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#appnotifTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#appnotifTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#appnotifMUTASI_DESKRIPSI").val(data.MUTASI_DESKRIPSI);
      $("#appnotifMUTASI_TANGGAL").val(data.MUTASI_TANGGAL);
      $("#appnotifTANGGAL_EFEKTIF").val(data.TANGGAL_EFEKTIF);
      $("#appnotifINPUT_BY").text(data.INPUT_BY);
      $("#appnotifINPUT_DATE").text(data.INPUT_DATE);
      $("#appnotifAPPROVE_BY").text(data.APPROVE_BY);
      $("#appnotifMUTASI_APP_TANGGAL").text(data.MUTASI_APP_TANGGAL);
      $("#appnotifMUTASI_STATUS_DES").html(data.MUTASI_STATUS_DES);

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_AWAL },
        success: function(data){
          $("#notifloadpicapp").html(data);
        }
      });
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// View Kas Anggota
$(document).on("click", ".open-ViewNotifKas", function () {
  
  var key = $(this).data('dokumen');
  var anggota = $(this).data('anggota');
  var jenis = $(this).data('jenis');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getdetailkas.php',
    method: 'POST',
    data: { KAS_ID: key, ANGGOTA_KEY: anggota, KAS_JENIS: jenis, CABANG_KEY: cabang },
    success: function(data) {
      // console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#kasviewDAERAH_KEY").val(data.DAERAH_KEY);
      $("#kasviewCABANG_KEY").val(data.CABANG_KEY);
      $("#kasviewDAERAH_DESKRIPSI").val(data.DAERAH_DESKRIPSI);
      $("#kasviewCABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
      $("#kasviewANGGOTA_ID").val(data.ANGGOTA_ID);
      $("#kasviewANGGOTA_KEY").val(data.ANGGOTA_IDNAMA);
      $("#kasviewANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
      $("#kasviewANGGOTA_IDNAMA").val(data.ANGGOTA_IDNAMA);
      $("#kasviewTINGKATAN_NAMA").val(data.TINGKATAN_NAMA);
      $("#kasviewTINGKATAN_SEBUTAN").val(data.TINGKATAN_SEBUTAN);
      $("#kasviewKAS_JENIS").val(data.KAS_JENIS);
      $("#kasviewKAS_DK").val(data.KAS_DK_DES);
      $("#kasviewKAS_JUMLAH").val(data.KAS_JUMLAH);
      $("#kasviewFKAS_JUMLAH").val(data.FKAS_JUMLAH);
      $("#kasviewKAS_SALDO").val(data.KAS_SALDO);
      $("#kasviewKAS_SALDOAKHIR").val(data.FKAS_SALDO);
      $("#kasviewKAS_SALDOAWAL").val(data.SALDOAWAL);
      $("#kasviewKAS_DESKRIPSI").val(data.KAS_DESKRIPSI);
      $("#kasviewINPUT_BY").text(data.INPUT_BY);
      $("#kasviewINPUT_DATE").text(data.INPUT_DATE);

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(data){
          $("#notifkaspic").html(data);
        }
      });
    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
  // console.log(id);
});

// View PPD
$(document).on("click", ".open-ViewNotifPPD", function () {
  
  var key = $(this).data('dokumen');
  var cabang = $(this).data('cabang');
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/aktivitas/ppd/aj_getdetailppd.php',
    method: 'POST',
    data: { PPD_ID: key },
    success: function(data) {
      console.log('response', data);
      // Assuming data is a JSON object with the required information
      // Make sure the keys match the fields in your returned JSON object
      $("#ViewNotifPPD_DAERAH").val(data.DAERAH_DESKRIPSI);
      $("#ViewNotifPPD_CABANG").val(data.CABANG_DESKRIPSI);
      $("#ViewNotifPPD_TANGGAL").val(data.PPD_TANGGAL);
      $("#ViewNotifPPD_JENIS").val(data.PPD_JENIS_DESKRIPSI);
      $("#ViewNotifPPD_TINGKATAN").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);
      $("#ViewNotifPPD_ANGGOTA").val(data.ANGGOTA_NAMA);
      $("#ViewNotifPPD_LOKASI").val(data.LOKASI_DAERAH + ' - ' + data.LOKASI_CABANG);
      $("#ViewNotifPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
        success: function(result){
          $("#notifpicppd").html(result);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve PPD Koordinator
$(document).on("click", ".open-ApproveNotifPPDKoordinator", function () {
  
  var key = $(this).data('dokumen');
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
      $("#notifKoordinatorPPD_ID").val(data.PPD_ID);
      $("#notifKoordinatorPPD_DAERAH").val(data.DAERAH_DESKRIPSI);
      $("#notifKoordinatorPPD_CABANG").val(data.CABANG_DESKRIPSI);
      $("#notifKoordinatorPPD_TANGGAL").val(data.PPD_TANGGAL);
      $("#notifKoordinatorPPD_JENIS").val(data.PPD_JENIS_DESKRIPSI);
      $("#notifKoordinatorPPD_TINGKATAN").val(data.TINGKATAN_NAMA + ' - ' + data.TINGKATAN_SEBUTAN);
      $("#notifKoordinatorPPD_ANGGOTA").val(data.ANGGOTA_NAMA);
      $("#notifKoordinatorPPD_LOKASI").val(data.LOKASI_DAERAH + ' - ' + data.LOKASI_CABANG);
      $("#notifKoordinatorPPD_DESKRIPSI").val(data.PPD_DESKRIPSI);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: cabang },
        success: function(result){
          $("#notifpicppdkoor").html(result);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// View UKT
$(document).on("click", ".open-ViewNotifUKT", function () {
  
  var key = $(this).data('dokumen');
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
      $("#ViewNotifUKT_DAERAH").val(data.DAERAH_DESKRIPSI);
      $("#ViewNotifUKT_CABANG").val(data.CABANG_DESKRIPSI);
      $("#ViewNotifUKT_TANGGAL").val(data.UKT_TANGGAL_DESKRIPSI);
      $("#ViewNotifUKT_ANGGOTA").val(data.ANGGOTA_ID + ' - ' + data.ANGGOTA_NAMA);
      $("#ViewNotifUKT_TINGKATAN").val(data.UKT_TINGKATAN_NAMA + ' - ' + data.UKT_TINGKATAN_SEBUTAN);
      $("#ViewNotifUKT_LOKASI").val(data.UKT_DAERAH + ' - ' + data.UKT_CABANG);
      $("#ViewNotifUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#ViewNotifUKT_TOTAL").html(data.UKT_TOTAL);
      var iconHtml = '<i class="' + data.UKT_NILAI + '"></i>';
      $("#ViewNotifUKT_NILAI").html(iconHtml);

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(result){
          $("#loadpicnotifukt").html(result);
        }
      });

      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewNotifPenguji-table').DataTable().destroy();
          $("#viewNotifPengujiData").html(response);
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
          $("#viewnotifrincianukt").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});

// Approve UKT Koordinator
$(document).on("click", ".open-ApproveNotifUKTKoordinator", function () {
  
  var key = $(this).data('dokumen');
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
      $("#viewAppNotifUKT_ID").val(data.UKT_ID);
      $("#viewAppNotifDAERAH_KEY").val(data.DAERAH_DESKRIPSI);
      $("#viewAppNotifCABANG_KEY").val(data.CABANG_DESKRIPSI);
      $("#viewAppNotifUKT_TANGGAL").val(data.UKT_TANGGAL_DESKRIPSI);
      $("#viewAppNotifANGGOTA_ID").val(data.ANGGOTA_ID + ' - ' + data.ANGGOTA_NAMA);
      $("#viewAppNotifTINGKATAN_ID").val(data.UKT_TINGKATAN_NAMA + ' - ' + data.UKT_TINGKATAN_SEBUTAN);
      $("#viewAppNotifUKT_LOKASI").val(data.UKT_DAERAH + ' - ' + data.UKT_CABANG);
      $("#viewAppNotifUKT_DESKRIPSI").val(data.UKT_DESKRIPSI);
      $("#viewAppNotifUKT_TOTAL").html(data.UKT_TOTAL);
      var iconHtml = '<i class="' + data.UKT_NILAI + '"></i>';
      $("#viewAppNotifUKT_NILAI").html(iconHtml);

      // GET ANGGOTA PIC
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
        data: { ANGGOTA_KEY: data.ANGGOTA_ID, CABANG_KEY: data.CABANG_KEY },
        success: function(result){
          $("#loadPicAppNotifUKT").html(result);
        }
      });

      // GET PENGUJI UKT
      $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/aktivitas/ukt/aj_getviewpengujiukt.php",
        data: { id: key },
        success: function(response){
          // Destroy the DataTable before updating
          $('#viewAppNotifPenguji-table').DataTable().destroy();
          $("#viewAppNotifPengujiData").html(response);
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
          $("#viewAppNotifrincianukt").html(data);
        }
      });

    },
    error: function(error) {
      console.error('Error fetching data:', error);
    }
  });
});