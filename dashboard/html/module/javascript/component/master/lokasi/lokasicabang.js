

// Cabang Table (server-side DataTables)
function initCabangTable() {
  var table = $('#lokasicabang-table').DataTable({
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    processing: true,
    serverSide: true,
    scrollX: true,
    scrollY: '400px',
    buttons: [ 'copy', 'csv', 'excel', 'pdf' ],
    ajax: {
      url: 'module/ajax/master/lokasicabang/aj_tablecabang_ssp.php',
      type: 'POST',
      data: function(d){ d._ts = Date.now(); }
    },
    columns: [
      { data: 0, orderable: false },
      { data: 1 },
      { data: 2 },
      { data: 3 },
      { data: 4 },
      { data: 5 },
      { data: 6 },
      { data: 7 },
      { data: 8, orderable: false }
    ]
  });

  return table;
}

// Initialize on ready
var cabangDT;
$(document).ready(function() {
  cabangDT = initCabangTable();
});

// Load Cabang Maps
function getMapsAdd(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/master/lokasicabang/aj_mapcabang.php",
  data:'maps='+val,
  success: function(data){
    $("#addCabangMap").html(data);
  }
  });
  // console.log(val);
}

function getMapsEdit(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/master/lokasicabang/aj_mapcabang.php",
  data:'maps='+val,
  success: function(data){
    $("#editCabangMap").html(data);
  }
  });
  // console.log(val);
}


// ----- Start of Cabang Section ----- //
function handleCabangForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData($(this)[0]); // Create FormData object from the form
    var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID

    // Manually add the button title or ID to the serialized data
    formData.append(buttonId, 'edit');

    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasicabang.php',
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

          // Reload server-side DataTable
          try { if (cabangDT && cabangDT.ajax) cabangDT.ajax.reload(null, true); } catch(e) {}
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
  // add Cabang
  handleCabangForm('#AddCabang-form', SuccessNotification, FailedNotification, UpdateNotification);

  // edit Cabang
  handleCabangForm('#EditCabang-form', UpdateNotification, FailedNotification, UpdateNotification);
});

// Delete Cabang
function deleteCabang(value1,value2) {
  // Ask for confirmation
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    // Create the data object
    var eventdata = {
      ID: value1,
      EVENT_ACTION: value2
    };

    // Perform the AJAX request
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/lokasi/t_lokasicabang.php',
      data: eventdata,
      success: function(response) {
        // Check the response from the server
        if (response === 'Success') {
          // Display success notification
          DeleteNotification('Data berhasil dihapus!');
          
          // Reload server-side DataTable
          try { if (cabangDT && cabangDT.ajax) cabangDT.ajax.reload(null, false); } catch(e) {}

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



// View Cabang
$(document).on("click", ".open-ViewCabang", function () {
  var daerahdes = $(this).data('daerahdes');
  var shortid = $(this).data('shortid');
  var desk = $(this).data('desk');
  var pengurus = $(this).data('pengurus');
  var sekre = $(this).data('sekre');
  var map = $(this).data('map');
  var lat = $(this).data('lat');
  var long = $(this).data('long');
  
  // Set the values in the modal input fields
  $(".modal-body #viewDAERAH_ID").val(daerahdes);
  $(".modal-body #viewCABANG_ID").val(shortid);
  $(".modal-body #viewCABANG_DESKRIPSI").val(desk);
  $(".modal-body #viewCABANG_PENGURUS").val(pengurus);
  $(".modal-body #viewCABANG_SEKRETARIAT").val(sekre);
  $(".modal-body #viewCABANG_MAP").val(map);
  $(".modal-body #viewCABANG_LAT").val(lat);
  $(".modal-body #viewCABANG_LONG").val(long);
  
  // Set the source URL to the iframe
  document.getElementById('ViewCabangMap').src = map;
  
});

// Edit Cabang
$(document).on("click", ".open-EditCabang", function () {
  var key = $(this).data('key');
  var id = $(this).data('cabangid');
  var daerahid = $(this).data('daerahid');
  var shortid = $(this).data('shortid');
  var desk = $(this).data('desk');
  var pengurus = $(this).data('pengurus');
  var sekre = $(this).data('sekre');
  var map = $(this).data('map');
  var lat = $(this).data('lat');
  var long = $(this).data('long');
  
  // Set the values in the modal input fields
  $(".modal-body #editCABANG_KEY").val(key);
  $(".modal-body #editID").val(id);
  $(".modal-body #selectize-dropdown2")[0].selectize.setValue(daerahid);
  $(".modal-body #editCABANG_ID").val(shortid);
  $(".modal-body #editCABANG_DESKRIPSI").val(desk);
  $(".modal-body #editCABANG_PENGURUS").val(pengurus);
  $(".modal-body #editCABANG_SEKRETARIAT").val(sekre);
  $(".modal-body #editCABANG_MAP").val(map);
  $(".modal-body #editCABANG_LAT").val(lat);
  $(".modal-body #editCABANG_LONG").val(long);
  
  // Set the source URL to the iframe
  document.getElementById('EditCabangMap').src = map;
  
});

// ----- End of Pusat Section ----- //