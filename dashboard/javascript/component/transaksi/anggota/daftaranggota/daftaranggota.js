

// Anggota Table
function callTable() {
  $('#anggota-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtip',
      paging: true,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
  });
}

// Call the function when the document is ready
$(document).ready(function() {
  callTable();
});


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
      // Get the selected value from the first dropdown
      var selectedDaerah = $(this).val();

      // Make an AJAX request to fetch data for the second dropdown based on the selected value
      $.ajax({
          url: 'module/ajax/transaksi/anggota/daftaranggota/aj_getlistcabang.php', // Change this to the actual URL that fetches cabang data based on daerah
          method: 'POST',
          data: {daerah_id: selectedDaerah},
          success: function(data) {
              // Update the options of the second dropdown with the fetched data
              $('#CABANG_ID').html(data);
              console.log(data);
          }
      });
  });
});

// Delete Anggota
function deletepusatdata(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      PUSATDATA_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/pusatdata/t_pusatdata.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/pusatdata/aj_tablepusatdata.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#pusatdata-table').DataTable().destroy();
              $("#datapusatdata").html(response);
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
$(document).on("click", ".open-ViewPusatdata", function () {
  var id = $(this).data('pusatid');
  var cabangnama = $(this).data('cabangnama');
  var kategori = $(this).data('kategori');
  var judul = $(this).data('judul');
  var deskripsi = $(this).data('deskripsi');
  var pusatstatus = $(this).data('pusatstatus');
  
  // Set the values in the modal input fields
  $(".modal-body #viewCABANG_ID").val(cabangnama);
  $(".modal-body #viewPUSATDATA_KATEGORI").val(kategori);
  $(".modal-body #viewPUSATDATA_JUDUL").val(judul);
  $(".modal-body #viewPUSATDATA_DESKRIPSI").val(deskripsi);
  $(".modal-body #viewDELETION_STATUS").val(pusatstatus);

  $.ajax({
    type: "POST",
    url: "module/ajax/master/pusatdata/aj_loadpusatdata.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#viewpusatfile").html(data);
    }
  });
  
  // console.log(id);
});

// Edit Anggota
$(document).on("click", ".open-EditAnggota", function () {
  var id = $(this).data('pusatid');
  var cabangid = $(this).data('cabangid');
  var kategori = $(this).data('kategori');
  var judul = $(this).data('judul');
  var deskripsi = $(this).data('deskripsi');
  var status = $(this).data('status');
  
  // Set the values in the modal input fields
  $(".modal-body #EditAnggota_ID").val(id);
  $(".modal-body #selectize-dropdown2")[0].selectize.setValue(cabangid);
  $(".modal-body #EditAnggota_KATEGORI").val(kategori);
  $(".modal-body #EditAnggota_JUDUL").val(judul);
  $(".modal-body #EditAnggota_DESKRIPSI").val(deskripsi);
  $(".modal-body #editDELETION_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/master/pusatdata/aj_loadpusatdata.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#editpusatfile").html(data);
    }
  });

  // console.log(pusatid);
});

// Anggota Filtering
// Attach debounced event handler to form inputs
$('.filterAnggota select, .filterAnggota input').on('change input', debounce(filterAnggotaEvent, 500));
function filterAnggotaEvent() {
  // Your event handling code here
  const cabang = $('#selectize-select2').val();
  const nama = $('#filterANGGOTA_NAMA').val();
  const ktp = $('#filterANGGOTA_KTP').val();
  const hp = $('#filterANGGOTA_HP').val();
  const tingkatan = $('#selectize-select').val();
  const join = $('#datepicker41').val();

  // Create a data object to hold the form data
  const formData = {
    CABANG_ID: cabang,
    ANGGOTA_NAMA: nama,
    ANGGOTA_KTP: ktp,
    ANGGOTA_HP: hp,
    TINGKATAN_ID: tingkatan,
    ANGGOTA_JOIN: join
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
  // Clear the first Selectize dropdown
  var selectizeInstance1 = $('#selectize-select')[0].selectize;
  if (selectizeInstance1) {
    selectizeInstance1.clear();
  }

  // Clear the second Selectize dropdown
  var selectizeInstance2 = $('#selectize-select2')[0].selectize;
  if (selectizeInstance2) {
    selectizeInstance2.clear();
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