

// Anggota Table
function callTable() {
  $('#anggota-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtlip',
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ],
  });

  $('#ViewAnggota').on('shown.bs.modal', function () {
    // Destroy DataTable for riwayatmutasi-table if it exists
    if ($.fn.DataTable.isDataTable('#riwayatmutasi-table')) {
      $('#riwayatmutasi-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#mutasikas-table')) {
      $('#mutasikas-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#idsertifikat-table')) {
      $('#idsertifikat-table').DataTable().destroy();
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
    
    $('#idsertifikat-table').DataTable({
      responsive: true,
      order: [[9, 'asc']],
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
          { width: '100px', targets: 8 }, // Set width for column 9
          { width: '100px', targets: 9 }, // Set width for column 10
          // Add more columnDefs as needed
      ],
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
  const inputValue = input.value.replace(/[^0-9]/g, '').slice(0, 3);

  if (inputValue.length !== 3) {
      document.getElementById('warning-message').innerText = 'Mohon masukkan 3 digit angka!';
      document.getElementById('warning-message-edit').innerText = 'Mohon masukkan 3 digit angka!';
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

          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_tableanggota.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#anggota-table').DataTable().destroy();
              $("#anggotadata").html(response);
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
      },
      error: function(xhr, status, error) {
        // Handle any errors
      }
    });
  });
}


$(document).ready(function() {
  // add Anggota
  handleForm('#AddAnggota-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Anggota
  handleForm('#EditAnggota-form', UpdateNotification, FailedNotification, UpdateNotification);

  // Event listener for the first dropdown change
  $('#selectize-dropdown').change(function() {
      // Initialize Selectize on the first dropdown
    var selectizedropdown = $('#selectize-dropdown').selectize();
    
    // Get the Selectize instance
    var selectizeInstance = selectizedropdown[0].selectize;

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
    });
  });

  // Event listener for the first dropdown change
$('#selectize-dropdown4').change(function () {
  var selectedDaerah = $(this).val();

  // Make an AJAX request to fetch data for the second dropdown based on the selected value
  $.ajax({
      url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php',
      method: 'POST',
      data: { daerah_id: selectedDaerah },
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
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/transaksi/anggota/daftaranggota/aj_tableanggota.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#anggota-table').DataTable().destroy();
              $("#anggotadata").html(response);
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
$(document).on("click", ".open-ViewAnggota", function () {
  var key = $(this).data('key');
  var anggotaid = $(this).data('id');
  var daerahkey = $(this).data('daerahkey');
  var daerahdes = $(this).data('daerahdes');
  var cabangkey = $(this).data('cabangkey');
  var cabangdes = $(this).data('cabangdes');
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
    data:'ANGGOTA_KEY='+key,
    success: function(data){
      $("#loadpic").html(data);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasianggota.php",
    data:'ANGGOTA_KEY='+key,
    success: function(data){
      $("#riwayatmutasi").html(data);
    }
  });
  
  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasikas.php",
    data:'ANGGOTA_KEY='+key,
    success: function(data){
      $("#riwayatkas").html(data);
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
  var anggotaid = $(this).data('shortid');
  var daerahkey = $(this).data('daerahkey');
  var daerahdes = $(this).data('daerahdes');
  var cabangkey = $(this).data('cabangkey');
  var cabangdes = $(this).data('cabangdes');
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
  $(".modal-body #editANGGOTA_KTP").val(ktp);
  $(".modal-body #editANGGOTA_NAMA").val(nama);
  $(".modal-body #editANGGOTA_ALAMAT").val(alamat);
  $(".modal-body #editANGGOTA_PEKERJAAN").val(pekerjaan);
  $(".modal-body #editANGGOTA_KELAMIN").val(kelamin);
  $(".modal-body #editANGGOTA_TEMPAT_LAHIR").val(tempatlahir);
  $(".modal-body #datepicker46").val(tanggallahir);
  $(".modal-body #editANGGOTA_HP").val(hp);
  $(".modal-body #editANGGOTA_EMAIL").val(email);
  $(".modal-body #datepicker45").val(join);
  $(".modal-body #datepicker47").val(resign);
  $(".modal-body #editANGGOTA_AGAMA").val(agama);
  $(".modal-body #editANGGOTA_AKSES").val(akses);
  $(".modal-body #editANGGOTA_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/transaksi/anggota/daftaranggota/aj_loadpic.php",
    data:'ANGGOTA_KEY='+key,
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
$('.filterAnggota select, .filterAnggota input').on('change input', debounce(filterAnggotaEvent, 500));
function filterAnggotaEvent() {
  // Your event handling code here
  const daerah = $('#selectize-select3').val();
  const cabang = $('#selectize-select2').val();
  const id = $('#filterANGGOTA_ID').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const ktp = $('#filterANGGOTA_KTP').val();
  const hp = $('#filterANGGOTA_HP').val();
  const tingkatan = $('#selectize-select').val();
  const status = $('#filterANGGOTA_STATUS').val();

  // Create a data object to hold the form data
  const formData = {
    DAERAH_KEY: daerah,
    CABANG_KEY: cabang,
    ANGGOTA_ID: id,
    ANGGOTA_NAMA: nama,
    ANGGOTA_KTP: ktp,
    ANGGOTA_HP: hp,
    TINGKATAN_ID: tingkatan,
    ANGGOTA_STATUS: status
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/transaksi/anggota/daftaranggota/aj_tableanggota.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#anggota-table').DataTable().destroy();
      $("#anggotadata").html(response);
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
  $.ajax({
    type: 'POST',
    url: 'module/ajax/transaksi/anggota/daftaranggota/aj_tableanggota.php',
    success: function(response) {
      // Destroy the DataTable before updating
      $('#anggota-table').DataTable().destroy();
      $("#anggotadata").html(response);
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