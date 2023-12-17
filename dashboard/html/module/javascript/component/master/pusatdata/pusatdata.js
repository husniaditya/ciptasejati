

// Sertifikat Table
function callTable() {
  $('#pusatdata-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtip',
      // columnDefs: [
      //     { width: '100px', targets: 0 }, // Set width for column 1
      //     { width: '150px', targets: 2 }, // Set width for column 2
      //     { width: '150px', targets: 3 }, // Set width for column 3
      //     { width: '150px', targets: 4 }, // Set width for column 4
      //     { width: '400px', targets: 5 }, // Set width for column 5
      //     { width: '150px', targets: 6 }, // Set width for column 6
      //     { width: '100px', targets: 7 }, // Set width for column 6
      //     { width: '250px', targets: 8 }, // Set width for column 6
      //     { width: '250px', targets: 9 }, // Set width for column 6
      //     // Add more columnDefs as needed
      // ],
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

function reloadDataTable() {
  $.ajax({
    type: 'POST',
    url: 'module/ajax/master/pusatdata/aj_tablepusatdata.php',
    success: function(response) {
      $('#pusatdata-table').DataTable().destroy();
      $("#datapusatdata").html(response);
      callTable(); // Reinitialize Sertifikat Table
    },
    error: function(xhr, status, error) {
      // Handle any errors
    }
  });
}


// ----- Start of Sertifikat Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/pusatdata/t_pusatdata.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        var successMessage = response === 'Success' ? 'Data berhasil tersimpan!' : 'Data berhasil terupdate!';

        if (response === 'Success' || response === 'Update') {
          successNotification(successMessage);
          $(formId.replace("-form", "")).modal('hide');
          
          // Reload DataTable
          reloadDataTable();
        } else {
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
  // add Sertifikat
  handleForm('#AddPusatdata-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Sertifikat
  handleForm('#EditPusatdata-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Sertifikat
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

// View Pusatdata
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

// Edit Pusatdata
$(document).on("click", ".open-EditPusatdata", function () {
  var id = $(this).data('pusatid');
  var cabangid = $(this).data('cabangid');
  var kategori = $(this).data('kategori');
  var judul = $(this).data('judul');
  var deskripsi = $(this).data('deskripsi');
  var status = $(this).data('status');
  
  // Set the values in the modal input fields
  $(".modal-body #editPUSATDATA_ID").val(id);
  $(".modal-body #selectize-dropdown2").val(cabangid); // Set value directly for a regular select element
  $(".modal-body #editPUSATDATA_KATEGORI").val(kategori);
  $(".modal-body #editPUSATDATA_JUDUL").val(judul);
  $(".modal-body #editPUSATDATA_DESKRIPSI").val(deskripsi);
  $(".modal-body #editDELETION_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/master/pusatdata/aj_loadpusatdata.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#editpusatfile").html(data);
    }
  });

  // Update the Selectize control
  var selectize = $(".modal-body #selectize-dropdown2")[0].selectize;
  selectize.setValue(cabangid);

  // console.log(pusatid);
});

// Filtering
// Attach debounced event handler to form inputs
$('.filterPusatData select, .filterPusatData input').on('change input', debounce(filterPusatDataEvent, 500));
function filterPusatDataEvent() {
  // Your event handling code here
  const cabang = $('#selectize-select').val();
  const kategori = $('#selectize-select2').val();
  const judul = $('#filterPUSATDATA_JUDUL').val();
  const deskripsi = $('#filterPUSATDATA_DESKRIPSI').val();
  const status = $('#filterDELETION_STATUS').val();

  // Create a data object to hold the form data
  const formData = {
    CABANG_KEY: cabang,
    PUSATDATA_KATEGORI: kategori,
    PUSATDATA_JUDUL: judul,
    PUSATDATA_DESKRIPSI: deskripsi,
    DELETION_STATUS: status
  };

  $.ajax({
    type: "POST",
    url: 'module/ajax/master/pusatdata/aj_tablepusatdata.php',
    data: formData,
    success: function(response){
      // Destroy the DataTable before updating
      $('#pusatdata-table').DataTable().destroy();
      $("#datapusatdata").html(response);
      // Reinitialize Sertifikat Table
      callTable();
    }
  });
  // console.log(formData);
}

// ----- Function to reset form ----- //
function clearForm() {
  // Clear the first Selectize dropdown
  var selectizeInstance = $('#selectize-select')[0];
  var selectizeInstance2 = $('#selectize-select2')[0].selectize;

  if (selectizeInstance) {
    var selectizeInstance = selectizeInstance.selectize;
    if (selectizeInstance) {
      selectizeInstance.clear();
    }
  }
  // Clear the second Selectize dropdown (corrected ID)
  if (selectizeInstance2) {
    var selectizeInstance2 = selectizeInstance2.selectize;
    if (selectizeInstance2) {
      selectizeInstance2.clear();
    }
  }
  var selectizeInstance2 = $('#selectize-select2')[0].selectize;
  if (selectizeInstance2) {
    selectizeInstance2.clear();
  }

  document.getElementById("filterPusatData").reset();
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
}


// ----- End of Pusat Section ----- //