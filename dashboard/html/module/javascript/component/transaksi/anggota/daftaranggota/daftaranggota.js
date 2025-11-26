

// Anggota Table
function callTable() {
  var table = $('#anggota-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtlip',
      paging: true,
      processing: true,
      serverSide: true,
      scrollX: true,
      scrollY: '350px',
      buttons: [ 'copy', 'csv', 'excel', 'pdf' ],
      ajax: {
        url: 'module/ajax/transaksi/anggota/daftaranggota/aj_tableanggota_ssp.php',
        type: 'POST',
        data: function (d) {
          // attach current filters
          d.DAERAH_KEY = $('#selectize-select3').val();
          d.CABANG_KEY = $('#selectize-select2').val();
          d.ANGGOTA_RANTING = $('#filterANGGOTA_RANTING').val();
          d.ANGGOTA_ID = $('#filterANGGOTA_ID').val();
          d.ANGGOTA_NAMA = $('#filterANGGOTA_NAMA').val();
          d.ANGGOTA_AKSES = $('#filterANGGOTA_AKSES').val();
          d.TINGKATAN_ID = $('#selectize-select').val();
          d.ANGGOTA_STATUS = $('#filterANGGOTA_STATUS').val();
          // cache buster
          d._ts = Date.now();
        }
      },
      // columns correspond to table headers (Action + 16 data columns)
      columns: [
        { data: 0, orderable: false },
        { data: 1 },
        { data: 2 },
        { data: 3 },
        { data: 4 },
        { data: 5 },
        { data: 6 },
        { data: 7 },
        { data: 8 },
        { data: 9 },
        { data: 10 },
        { data: 11 },
        { data: 12 },
        { data: 13 },
        { data: 14 },
        { data: 15 },
        { data: 16 }
      ]
  });

  // When filters change, just reload via ajax instead of rebuilding the table
  $('.filterAnggota select, .filterAnggota input').off('change input').on('change input', debounce(function(){
    table.ajax.reload(null, true); // reset to first page
  }, 300));

  $('#ViewAnggota').on('shown.bs.modal', function () {
    // Destroy DataTable for riwayatmutasi-table if it exists
    if ($.fn.DataTable.isDataTable('#riwayatmutasi-table')) {
      $('#riwayatmutasi-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#mutasikas-table')) {
      $('#mutasikas-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#riwayatukt-table')) {
      $('#riwayatukt-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#riwayatppd-table')) {
      $('#riwayatppd-table').DataTable().destroy();
    }

    $('#riwayatmutasi-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      columnDefs: [
          { width: '100px', targets: 0 }, // Set width for column 1
          { width: '150px', targets: 1 }, // Set width for column 2
          { width: '150px', targets: 2 }, // Set width for column 3
          { width: '150px', targets: 3 }, // Set width for column 4
          { width: '100px', targets: 4 }, // Set width for column 5
          { width: '100px', targets: 5 }, // Set width for column 6
          { width: '100px', targets: 6 }, // Set width for column 7
          { width: '100px', targets: 7 }, // Set width for column 8
          // Add more columnDefs as needed
      ],
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });

    $('#mutasikas-table').DataTable({
        responsive: true,
        order: [], // Adjust the column index and order direction
        dom: 'Bfrtlip',
        paging: true,
        scrollX: true,
        scrollY: '350px', // Set the desired height here
        buttons: [
            {
                extend: 'copy',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'csv',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'excel',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'pdf',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI'
            }
        ]
    });
    
    $('#riwayatukt-table').DataTable({
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

    $('#riwayatppd-table').DataTable({
      responsive: true,
      order: [[7, 'asc']],
      dom: 'Bfrtlip',
      columnDefs: [
          { width: '100px', targets: 0 }, // Set width for column 1
          { width: '150px', targets: 1 }, // Set width for column 2
          { width: '150px', targets: 2 }, // Set width for column 3
          { width: '150px', targets: 3 }, // Set width for column 4
          { width: '100px', targets: 4 }, // Set width for column 5
          { width: '100px', targets: 5 }, // Set width for column 6
          { width: '100px', targets: 6 }, // Set width for column 7
          { width: '100px', targets: 7 }, // Set width for column 8
          // Add more columnDefs as needed
      ],
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
$(document).ready(function() {
  callTable();

  // ===== Global Upload Overlay (for long-running uploads/imports) =====
  function ensureUploadOverlay() {
    if (!document.getElementById('global-upload-overlay')) {
      var overlay = document.createElement('div');
      overlay.id = 'global-upload-overlay';
      overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45)';
      overlay.innerHTML = ''+
        '<div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:8px;padding:20px 24px;width:420px;max-width:calc(100% - 40px);box-shadow:0 10px 30px rgba(0,0,0,0.2);">'+
          '<div id="gup-title" style="font-weight:600;font-size:16px;margin-bottom:6px;">Mengunggah...</div>'+
          '<div id="gup-sub" style="font-size:13px;color:#666;margin-bottom:12px;">Mohon tunggu, ini mungkin memakan waktu beberapa menit.</div>'+
          '<div class="progress" style="height:10px;margin-bottom:8px;">'+
            '<div id="gup-bar" class="progress-bar progress-bar-striped active" role="progressbar" style="width:0%"></div>'+
          '</div>'+
          '<div id="gup-status" style="font-size:12px;color:#999;">0%</div>'+
        '</div>';
      document.body.appendChild(overlay);
    }
  }

  function showUploadOverlay(title, sub) {
    ensureUploadOverlay();
    $('#gup-title').text(title || 'Mengunggah...');
    $('#gup-sub').text(sub || 'Mohon tunggu, ini mungkin memakan waktu beberapa menit.');
    $('#gup-bar').css('width','0%');
    $('#gup-status').text('0%');
    $('#global-upload-overlay').fadeIn(100);
  }

  function updateUploadProgress(pct, status) {
    var p = Math.max(0, Math.min(100, parseInt(pct,10) || 0));
    $('#gup-bar').css('width', p + '%');
    $('#gup-status').text((status ? status + ' · ' : '') + p + '%');
  }

  function showProcessingOnOverlay() {
    $('#gup-title').text('Memproses di server...');
    $('#gup-sub').text('File berhasil diunggah. Sistem sedang memproses data Anda.');
    // Keep striped active bar; set to 100% to indicate upload complete
    $('#gup-bar').addClass('active').css('width','100%');
    $('#gup-status').text('Sedang memproses...');
  }

  function hideUploadOverlay() {
    $('#global-upload-overlay').fadeOut(150);
  }

  // Upload Template Anggota handler
  $(document).on('submit', '#UploadAnggota-form', function(e){
    e.preventDefault();
    var $form = $(this);
    var mode = ($('#importMode').val() || 'insert').toLowerCase();
    if (mode === 'replace') {
      if (!confirm('Mode Hapus semua data lama dan tambah baru\n\nIni akan menghapus seluruh data anggota pada Cabang aktif sebelum mengimpor yang baru. Lanjutkan?')) {
        return;
      }
    }
    var fd = new FormData(this);
    fd.set('upload_template_anggota', '1');
    fd.set('mode', mode);

    // Ensure CABANG_KEY is always sent so backend can scope operations correctly
    (function ensureCabangKey(){
      try {
  var cabangKey = null;
  // 1) Prefer Cabang select inside Upload modal (admin-only): #selectize-dropdown7 (or legacy #selectize-dropdown5)
  var $c = $('#selectize-dropdown7');
  if (!$c.length) { $c = $('#selectize-dropdown5'); }
        if ($c.length) {
          try {
            cabangKey = $c[0].selectize ? $c[0].selectize.getValue() : $c.val();
          } catch (e) {
            cabangKey = $c.val();
          }
        }
        // 2) Fallback to any hidden/input with name CABANG_KEY (non-admin case)
        if (!cabangKey) {
          var $hidden = $('[name="CABANG_KEY"]');
          if ($hidden.length) { cabangKey = $hidden.val(); }
        }
        // 3) Last resort: use current filter cabang from the page (if set)
        if (!cabangKey) {
          var $filterCab = $('#selectize-select2');
          if ($filterCab.length) {
            try {
              cabangKey = $filterCab[0].selectize ? $filterCab[0].selectize.getValue() : $filterCab.val();
            } catch (e2) {
              cabangKey = $filterCab.val();
            }
          }
        }
        if (cabangKey) { fd.set('CABANG_KEY', cabangKey); }
      } catch (e) { /* no-op if not resolvable */ }
    })();

    var $btn = $('#btnUploadAnggota');
    var prev = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengupload...');

    // Show overlay and track progress
    showUploadOverlay('Mengunggah file...', 'Mengirim file ke server.');

    $.ajax({
      url: 'module/backend/transaksi/anggota/daftaranggota/t_upload_temp_anggota.php',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      xhr: function(){
        var xhr = $.ajaxSettings.xhr();
        if (xhr && xhr.upload) {
          xhr.upload.addEventListener('progress', function(evt){
            if (evt.lengthComputable) {
              var pct = Math.round((evt.loaded / evt.total) * 100);
              updateUploadProgress(pct, 'Mengunggah');
            }
          }, false);
          xhr.upload.addEventListener('load', function(){
            // Upload finished, now server processes
            showProcessingOnOverlay();
          }, false);
        }
        return xhr;
      },
      success: function(resp){
        $btn.prop('disabled', false).html(prev);
        hideUploadOverlay();
        var txt = (resp || '').toString().trim();
        if (/^success$/i.test(txt)) {
          try { SuccessNotification('Upload berhasil.'); } catch(e) {}
          $('#UploadAnggota').modal('hide');
          // Refresh table (server-side DataTables)
          try {
            var dt = $('#anggota-table').DataTable();
            if (dt && dt.ajax) { dt.ajax.reload(null, true); }
            else {
              // Hard fallback: rebuild
              dt.destroy();
              callTable();
            }
          } catch(e) { /* no-op */ }
        } else if (/^berhasil:/i.test(txt)) {
          // Mixed result; show message
          try { FailedNotification(txt); } catch(e) { alert(txt); }
        } else {
          try { FailedNotification(txt); } catch(e) { alert('Gagal upload: ' + txt); }
        }
      },
      error: function(xhr){
        $btn.prop('disabled', false).html(prev);
        hideUploadOverlay();
        var msg = 'Terjadi kesalahan saat mengunggah';
        try { FailedNotification(msg); } catch(e) { alert(msg); }
      }
    });
  });

  // Ensure Selectize is initialized for Upload modal selects once the modal is shown
  $(document).on('shown.bs.modal', '#UploadAnggota', function(){
    var $daerah = $('#selectize-dropdown8');
    var $cabang = $('#selectize-dropdown7');
    try {
      if ($daerah.length && !$daerah[0].selectize) { $daerah.selectize(); }
      if ($cabang.length && !$cabang[0].selectize) { $cabang.selectize(); }
    } catch(e) { /* ignore if selectize not available */ }

    // Initialize Parsley on the upload form when modal opens
    try { $('#UploadAnggota-form').parsley(); } catch(e) { /* ignore if parsley not available */ }

    // If Daerah already has a value, trigger change to populate Cabang
    try {
      var currentVal = $daerah.val();
      if (currentVal) { $daerah.trigger('change'); }
    } catch(e) { /* ignore */ }
  });
});

function previewImage(input) {
  var previewContainer = document.getElementById('preview-container');
  var previewImage = document.getElementById('preview-image');

  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          previewImage.src = e.target.result;
          previewContainer.style.display = 'block';
      };

      reader.readAsDataURL(input.files[0]);
  } else {
      previewImage.src = '#';
      previewContainer.style.display = 'none';
  }
}
function previewImageedit(input) {
  var previewImage = document.getElementById('preview-image-edit');
  var previewContainer = document.getElementById('preview-container-edit');
  var loadPicDiv = document.getElementById('loadpicedit');

  var reader = new FileReader();

  reader.onload = function (e) {
      previewImage.src = e.target.result;
      previewContainer.style.display = 'block';

      // Hide the loadpicedit div when a new image is uploaded
      loadPicDiv.style.display = 'none';
  };

  // Make sure to read the file even if no file is selected
  reader.readAsDataURL(input.files[0]);
}
function resetPreview() {
  var previewImage = document.getElementById('preview-image');
  var previewContainer = document.getElementById('preview-container');
  var previewImageEdit = document.getElementById('preview-image-edit');
  var previewContainerEdit = document.getElementById('preview-container-edit');
  var loadPicDiv = document.getElementById('loadpic');
  var loadPicDivEdit = document.getElementById('loadpicedit');
  document.getElementById('warning-message').innerText = '';
  document.getElementById('warning-message-edit').innerText = '';

  // Reset the image source and hide the preview container
  previewImage.src = '#';
  previewContainer.style.display = 'none';
  previewImageEdit.src = '#';
  previewContainerEdit.style.display = 'none';

  // Show the loadpicedit div
  loadPicDiv.style.display = 'block';
  loadPicDivEdit.style.display = 'block';
}

// Assuming you have a Bootstrap modal with the ID "myModal"

$('#EditAnggota').on('hidden.bs.modal', handleModalHidden);
$('#AddAnggota').on('hidden.bs.modal', handleModalHidden);
function handleModalHidden() {
  resetPreview();
}


function validateInput(input) {
  const inputValue = input.value.replace(/[^0-9]/g, '').slice(0, 5);

  if (inputValue.length !== 5) {
      document.getElementById('warning-message').innerText = 'Mohon masukkan 5 digit angka!';
      document.getElementById('warning-message-edit').innerText = 'Mohon masukkan 5 digit angka!';
  } else {
      document.getElementById('warning-message').innerText = '';
      document.getElementById('warning-message-edit').innerText = '';
  }

  input.value = inputValue;
}

// ----- Start of Anggota Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/daftaranggota/t_daftaranggota.php',
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

          // Reload server-side DataTable to reflect changes without rebuilding
          try {
            var dt = $('#anggota-table').DataTable();
            if (dt && dt.ajax) {
              dt.ajax.reload(null, false); // keep current page
            }
          } catch (e) { /* ignore */ }
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
  // Auto-load cabang dropdown on page load if daerah is pre-selected (Pengurus Daerah)
  if ($('.filterAnggota').length) {
    var selectedDaerah = $('#selectize-select3').val();
    if (selectedDaerah) {
      $.ajax({
        url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
        method: 'POST',
        data: { id: selectedDaerah },
        dataType: 'json',
        success: function(data) {
          var selectizeSelect2 = $('#selectize-select2')[0].selectize;
          if (selectizeSelect2) {
            selectizeSelect2.clearOptions();
            selectizeSelect2.addOption(data);
            selectizeSelect2.setValue('');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error auto-loading cabang for anggota:', status, error);
        }
      });
    }
  }

  // add Anggota
  handleForm('#AddAnggota-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Anggota
  handleForm('#EditAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Bind Daerah (Upload modal) -> Cabang linkage; trigger on change
  $(document).on('change', '#selectize-dropdown8', function() {
    var selectedDaerah = $(this).val();
    $.ajax({
      url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
      method: 'POST',
      data: { id: selectedDaerah },
      dataType: 'json',
      success: function (data) {
        ['#selectize-dropdown7', '#selectize-dropdown5', '#selectize-dropdown3'].forEach(function(sel){
          if ($(sel).length) {
            var $sel = $(sel).selectize();
            var inst = $sel[0].selectize;
            inst.clearOptions();
            inst.addOption(data);
            inst.setValue('');
          }
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching cabang data:', status, error);
      }
    });
  });

  $(document).on('change', '#selectize-dropdown9', function() {
    var selectedDaerah = $(this).val();
    $.ajax({
      url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
      method: 'POST',
      data: { id: selectedDaerah },
      dataType: 'json',
      success: function (data) {
        ['#selectize-dropdown3'].forEach(function(sel){
          if ($(sel).length) {
            var $sel = $(sel).selectize();
            var inst = $sel[0].selectize;
            inst.clearOptions();
            inst.addOption(data);
            inst.setValue('');
          }
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching cabang data:', status, error);
      }
    });
  });

  // Event listener for the first dropdown change
$('#selectize-dropdown4').change(function () {
  var selectedDaerah = $(this).val();

  // Make an AJAX request to fetch data for the second dropdown based on the selected value
  $.ajax({
      url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
      method: 'POST',
      data: { id: selectedDaerah },
      dataType: 'json',
      success: function (data) {
          // Clear options in the second dropdown
          var selectizedropdown5 = $('#selectize-dropdown5').selectize();
          var selectizeInstance4 = selectizedropdown5[0].selectize;
          selectizeInstance4.clearOptions();

          // Add new options to the second dropdown
          selectizeInstance4.addOption(data);

          // Update the value of the second dropdown
          selectizeInstance4.setValue('');

          // Uncomment the following line if you encounter issues with options not refreshing properly
          // selectizeInstance4.refreshOptions();
      },
      error: function (xhr, status, error) {
          console.error('Error fetching cabang data:', status, error);
      }
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
});

// Delete Anggota
function deletedaftaranggota(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      ANGGOTA_KEY: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/transaksi/anggota/daftaranggota/t_daftaranggota.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Reload server-side DataTable to reflect deletion
          try {
            var dt = $('#anggota-table').DataTable();
            if (dt && dt.ajax) {
              dt.ajax.reload(null, false); // keep current page when possible
            }
          } catch (e) { /* ignore */ }

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
$(document).on("click", ".open-ViewAnggota", function () {
  var key = $(this).data('key');
  var anggotaid = $(this).data('id');
  var daerahkey = $(this).data('daerahkey');
  var daerahdes = $(this).data('daerahdes');
  var cabangkey = $(this).data('cabangkey');
  var cabangdes = $(this).data('cabangdes');
  var ranting = $(this).data('ranting');
  var tingkatanid = $(this).data('tingkatanid');
  var tingkatannama = $(this).data('tingkatannama');
  var ktp = $(this).data('ktp');
  var nama = $(this).data('nama');
  var alamat = $(this).data('alamat');
  var pekerjaan = $(this).data('pekerjaan');
  var kelamin = $(this).data('kelamin');
  var tempatlahir = $(this).data('tempatlahir');
  var tanggallahir = $(this).data('tanggallahir');
  var hp = $(this).data('hp');
  var email = $(this).data('email');
  var join = $(this).data('join');
  var resign = $(this).data('resign');
  var agama = $(this).data('agama');
  var akses = $(this).data('akses');
  var status = $(this).data('statusdes');
  
  // Set the values in the modal input fields
  $(".modal-body #viewANGGOTA_KEY").val(key);
  $(".modal-body #viewANGGOTA_ID").val(anggotaid);
  $(".modal-body #viewDAERAH_KEY").val(daerahdes);
  $(".modal-body #viewCABANG_KEY").val(cabangdes);
  $(".modal-body #viewANGGOTA_RANTING").val(ranting);
  $(".modal-body #viewTINGKATAN_ID").val(tingkatannama);
  $(".modal-body #viewANGGOTA_KTP").val(ktp);
  $(".modal-body #viewANGGOTA_NAMA").val(nama);
  $(".modal-body #viewANGGOTA_ALAMAT").val(alamat);
  $(".modal-body #viewANGGOTA_PEKERJAAN").val(pekerjaan);
  $(".modal-body #viewANGGOTA_KELAMIN").val(kelamin);
  $(".modal-body #viewANGGOTA_TEMPAT_LAHIR").val(tempatlahir);
  $(".modal-body #viewANGGOTA_TANGGAL_LAHIR").val(tanggallahir);
  $(".modal-body #viewANGGOTA_HP").val(hp);
  $(".modal-body #viewANGGOTA_EMAIL").val(email);
  $(".modal-body #viewANGGOTA_JOIN").val(join);
  $(".modal-body #viewANGGOTA_RESIGN").val(resign);
  $(".modal-body #viewANGGOTA_AGAMA").val(agama);
  $(".modal-body #viewANGGOTA_AKSES").val(akses);
  $(".modal-body #viewANGGOTA_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data: { ANGGOTA_KEY: anggotaid, CABANG_KEY: cabangkey },
    success: function(data){
      $("#loadpic").html(data);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasianggota.php",
    data:'id='+anggotaid,
    success: function(data){
      // Destroy the DataTable before updating
      $('#riwayatmutasi-table').DataTable().destroy();
      $("#riwayatmutasi").html(data);
      // Reinitialize Sertifikat Table
    }
  });
  
  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasikas.php",
    data: { id: anggotaid, cabang: cabangkey },
    success: function(data){
      // Destroy the DataTable before updating
      $('#mutasikas-table').DataTable().destroy();
      $("#riwayatkas").html(data);
      // Reinitialize Sertifikat Table
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getppdanggota.php",
    data:'id='+anggotaid,
    success: function(data){
      // Destroy the DataTable before updating
      $('#riwayatppd-table').DataTable().destroy();
      $("#daftariwayatppd").html(data);
      // Reinitialize Sertifikat Table
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getuktanggota.php",
    data:'id='+anggotaid,
    success: function(data){
      // Destroy the DataTable before updating
      $('#riwayatukt-table').DataTable().destroy();
      $("#daftarriwayatukt").html(data);
      // Reinitialize Sertifikat Table
    }
  });
  
  // console.log(id);
});

// Edit Anggota
$(document).on("click", ".open-EditAnggota", function () {
  var previewImage = document.getElementById('preview-image-edit');
  var previewContainer = document.getElementById('preview-container-edit');

  // Reset the image source and hide the preview container
  previewImage.src = '#';
  previewContainer.style.display = 'none';
  
  var key = $(this).data('key');
  var id = $(this).data('id');
  var anggotaid = $(this).data('shortid');
  var daerahkey = $(this).data('daerahkey');
  var daerahdes = $(this).data('daerahdes');
  var cabangkey = $(this).data('cabangkey');
  var cabangdes = $(this).data('cabangdes');
  var ranting = $(this).data('ranting');
  var tingkatanid = $(this).data('tingkatanid');
  var tingkatannama = $(this).data('tingkatannama');
  var ktp = $(this).data('ktp');
  var nama = $(this).data('nama');
  var alamat = $(this).data('alamat');
  var pekerjaan = $(this).data('pekerjaan');
  var kelamin = $(this).data('kelamin');
  var tempatlahir = $(this).data('tempatlahir');
  var tanggallahir = $(this).data('tanggallahir');
  var hp = $(this).data('hp');
  var email = $(this).data('email');
  var join = $(this).data('join');
  var resign = $(this).data('resign');
  var agama = $(this).data('agama');
  var akses = $(this).data('akses');
  var status = $(this).data('status');
  
  // Set the values in the modal input fields
  $(".modal-body #editANGGOTA_KEY").val(key);
  $(".modal-body #editANGGOTA_ID").val(anggotaid);
  $(".modal-body #selectize-dropdown6")[0].selectize.setValue(tingkatanid);
  $(".modal-body #editANGGOTA_RANTING").val(ranting);
  $(".modal-body #editANGGOTA_KTP").val(ktp);
  $(".modal-body #editANGGOTA_NAMA").val(nama);
  $(".modal-body #editANGGOTA_ALAMAT").val(alamat);
  $(".modal-body #editANGGOTA_PEKERJAAN").val(pekerjaan);
  $(".modal-body #editANGGOTA_KELAMIN").val(kelamin);
  $(".modal-body #editANGGOTA_TEMPAT_LAHIR").val(tempatlahir);
  $(".modal-body #datepicker46").val(tanggallahir);
  $(".modal-body #editANGGOTA_HP").val(hp);
  $(".modal-body #editANGGOTA_EMAIL").val(email);
  $(".modal-body #editANGGOTA_JOIN").val(join);
  $(".modal-body #datepicker45").val(join);
  $(".modal-body #datepicker47").val(resign);
  $(".modal-body #editANGGOTA_AGAMA").val(agama);
  $(".modal-body #editANGGOTA_AKSES").val(akses);
  $(".modal-body #editANGGOTA_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data: { ANGGOTA_KEY: id, CABANG_KEY: cabangkey },
    success: function(data){
      $("#loadpicedit").html(data);
    }
  });

  
  var isExist = $('#selectize-dropdown4').length > 0 && $('#selectize-dropdown5').length > 0;

  if (isExist) {
    $(".modal-body #selectize-dropdown4")[0].selectize.setValue(daerahkey);
    // Wait for the options in the second dropdown to be populated before setting its value
    setTimeout(function () {
        $(".modal-body #selectize-dropdown5")[0].selectize.setValue(cabangkey);
    }, 200); // You may need to adjust the delay based on your application's behavior
  }
});

// Anggota Filtering
// Attach debounced event handler to form inputs


// ----- Function to reset form ----- //
function clearForm() {
  // Check if the administrator-specific elements exist
  var isExist = $('#selectize-select3').length > 0 && $('#selectize-select2').length > 0;

  if (isExist) {
    var selectizeInstance1 = $('#selectize-select')[0].selectize;
    var selectizeInstance2 = $('#selectize-select2')[0].selectize;
    var selectizeInstance3 = $('#selectize-select3')[0].selectize;
  } else {
    var selectizeInstance1 = $('#selectize-select')[0].selectize;
  }
  
  // Clear the first Selectize dropdown
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

  document.getElementById("filterAnggota").reset();
  // Call the reloadDataTable() function after inserting data to reload the DataTable
  try {
    var dt = $('#anggota-table').DataTable();
    if (dt && dt.ajax) { dt.ajax.reload(null, true); }
  } catch(e) { /* ignore */ }
}
// ----- End of function to reset form ----- //

// ----- End of Anggota Section ----- //