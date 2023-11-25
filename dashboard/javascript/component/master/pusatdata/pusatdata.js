

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


// ----- Start of Sertifikat Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/pusatdata/t_pusatdata.php',
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
  $(".modal-body #selectize-dropdown2")[0].selectize.setValue(cabangid);
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

  // console.log(pusatid);
});


// ----- End of Pusat Section ----- //