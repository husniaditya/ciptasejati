

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

function loadAndRefresh() {
    // Load content initially when the document is ready
    $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
    $('#listnotif').load('./module/ajax/header/aj_listnotif.php');

    // Set up an interval to refresh content every 5 seconds
    setInterval(function() {
        $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
        $('#listnotif').load('./module/ajax/header/aj_listnotif.php');
    }, 5000);
}

// Call the function when the document is ready
$(document).ready(loadAndRefresh);


function handleNotif(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
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
        } else {
          // Display error notification
          failedNotification(response);
        }
        // Save PDF to Drive
        $.ajax({
          type: 'POST',
          url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasifile.php',
          data: { MUTASI_ID: MUTASI_ID },
          success: function(response) {
            // Check the response from the server
          },
          error: function(xhr, status, error) {
            errorNotification('Error! '+xhr.status+' '+error);
          }
        });
        // Send email notification
        $.ajax({
          type: 'POST',
          url: 'module/backend/transaksi/anggota/mutasianggota/t_mutasimail.php',
          data: { MUTASI_ID: MUTASI_ID },
          success: function(response) {
            // Check the response from the server
            if (response === 'Success') {
              // Display success notification
              MailNotification('Email pemberitahuan berhasil dikirimkan!');
            } else {
              // Display error notification
              failedNotification(response);
            }
          },
          error: function(xhr, status, error) {
            errorNotification('Error! '+xhr.status+' '+error);
          }
        });
      },
      error: function(xhr, status, error) {
          // Display error notification
          failedNotification(xhr, status, error);
      }
    });
  });
}
  
  
$(document).ready(function() {
// Approve Anggota
handleNotif('#ApproveNotifMutasi-form', UpdateNotification, FailedNotification, UpdateNotification);
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
      $("#notifloadpicview").html(data);
    }
  });
  
  // console.log(id);
});

  // ApproveNotifMutasi
$(document).on("click", ".open-ApproveNotifMutasi", function () {
  
  var key = $(this).data('dokumen');
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
      $("#notifloadpicapp").html(data);
    }
  });
  
  // console.log(id);
});

// View Kas Anggota
$(document).on("click", ".open-ViewNotifKas", function () {
  
  var key = $(this).data('dokumen');
  var anggota = $(this).data('anggota');
  var jenis = $(this).data('jenis');
  // console.log(key, anggota);
  
  // Make an AJAX request to fetch additional data based on the selected value
  $.ajax({
    url: 'module/ajax/transaksi/anggota/kasanggota/aj_getdetailkas.php',
    method: 'POST',
    data: { KAS_ID: key, ANGGOTA_KEY: anggota, KAS_JENIS: jenis },
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
      $("#notifkaspic").html(data);
    }
  });
  
  // console.log(id);
});