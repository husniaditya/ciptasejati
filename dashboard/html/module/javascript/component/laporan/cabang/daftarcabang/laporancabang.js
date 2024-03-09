

// Cabang Table
function callTable() {
    $('#lokasicabang-table').DataTable({
      responsive: true,
      order: [],
      // dom: 'Bfrtlip',
      dom: 'Bfrtlip',
      // "pageLength": 7,
      scrollX: true,
      scrollY: '400px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
  }

// Call the function when the document is ready
$(document).ready(function() {
  callTable();
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
// ----- End of Pusat Section ----- //