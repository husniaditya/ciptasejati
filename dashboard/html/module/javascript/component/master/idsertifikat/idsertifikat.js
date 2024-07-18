

// Sertifikat Table
function callTable() {
  $('#idsertifikat-table').DataTable({
      responsive: true,
      order: [],
      dom: 'Bfrtlip',
      columnDefs: [
          { width: '100px', targets: 0 }, // Set width for column 1
          { width: '250px', targets: 2 }, // Set width for column 2
          { width: '350px', targets: 3 }, // Set width for column 3
          { width: '250px', targets: 4 }, // Set width for column 4
          { width: '250px', targets: 5 }, // Set width for column 5
          { width: '150px', targets: 6 }, // Set width for column 6
          { width: '150px', targets: 7 }, // Set width for column 6
          { width: '250px', targets: 8 }, // Set width for column 6
          // Add more columnDefs as needed
      ],
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
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
      url: 'module/backend/master/idsertifikat/t_idsertifikat.php',
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
            url: 'module/ajax/master/idsertifikat/aj_tablesertifikat.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#idsertifikat-table').DataTable().destroy();
              $("#idsertifikatdata").html(response);
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
  handleForm('#AddSertifikat-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Sertifikat
  handleForm('#EditSertifikat-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Sertifikat
function deletesertifikat(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      IDSERTIFIKAT_ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/idsertifikat/t_idsertifikat.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Call the reloadDataTable() function after inserting data to reload the DataTable
          $.ajax({
            type: 'POST',
            url: 'module/ajax/master/idsertifikat/aj_tablesertifikat.php',
            success: function(response) {
              // Destroy the DataTable before updating
              $('#idsertifikat-table').DataTable().destroy();
              $("#idsertifikatdata").html(response);
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

// View Sertifikat
$(document).on("click", ".open-ViewSertifikat", function () {
  var id = $(this).data('id');
  var tingkatannama = $(this).data('tingkatannama');
  var desk = $(this).data('desk');
  var status = $(this).data('sertifikatstatus');
  
  // Set the values in the modal input fields
  $(".modal-body #viewTINGKATAN_ID").val(tingkatannama);
  $(".modal-body #viewIDSERTIFIKAT_DESKRIPSI").val(desk);
  $(".modal-body #viewDELETION_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/master/idsertifikat/aj_loadidcard.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#loadviewid").html(data);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/master/idsertifikat/aj_loadsertifikat.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#loadviewsertifikat").html(data);
    }
  });
  
  // console.log(id);
});

// Edit Sertifikat
$(document).on("click", ".open-EditSertifikat", function () {
  var id = $(this).data('id');
  var tingkatanid = $(this).data('tingkatanid');
  var desk = $(this).data('desk');
  var status = $(this).data('status');
  
  // Set the values in the modal input fields
  $(".modal-body #editIDSERTIFIKAT_ID").val(id);
  $(".modal-body #selectize-dropdown2")[0].selectize.setValue(tingkatanid);
  $(".modal-body #editIDSERTIFIKAT_DESKRIPSI").val(desk);
  $(".modal-body #editDELETION_STATUS").val(status);

  $.ajax({
    type: "POST",
    url: "module/ajax/master/idsertifikat/aj_loadidcard.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#loadeditid").html(data);
    }
  });

  $.ajax({
    type: "POST",
    url: "module/ajax/master/idsertifikat/aj_loadsertifikat.php",
    data:'EVENT_ID='+id,
    success: function(data){
      $("#loadeditsertifikat").html(data);
    }
  });

  // console.log(pusatid);
});


// ----- End of Pusat Section ----- //