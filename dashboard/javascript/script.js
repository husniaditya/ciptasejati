

$(document).on('keyup','.checkpassword',function(){
    var newpassword = $(NEWPASSWORD).val();
    var confirmpassword = $(CONFIRMPASSWORD).val();
    
    $.ajax({
        type: "POST",
        url: "./module/ajax/aj_checkpassword.php",
        data: {NEWPASSWORD :newpassword, CONFIRMPASSWORD:confirmpassword},
        success: function(data){
        $("#passwordcheck").html(data);
        }
        });
        // console.log(newpassword, confirmpassword);
  });

  // Load Maps
function getMapsAdd(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/event/aj_event_loadmap.php",
  data:'maps='+val,
  success: function(data){
    $("#loadmapsadd").html(data);
  }
  });
  // console.log(val);
}

function getMapsEdit(val) {
  $.ajax({
  type: "POST",
  url: "module/ajax/event/aj_event_loadmap.php",
  data:'maps='+val,
  success: function(data){
    $("#loadmapsedit").html(data);
  }
  });
  // console.log(val);
}

  // Load Video
  function getVideoAdd(val) {
    $.ajax({
    type: "POST",
    url: "module/ajax/announcement/aj_announcement_video.php",
    data:'video='+val,
    success: function(data){
      $("#loadvideoadd").html(data);
    }
    });
    // console.log(val);
  }

// Event Table
$(document).ready(function() {
  $('#eventtable-tools').DataTable( {
      responsive: true,
      dom: 'Bfrtip',
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
  });
});

// Announcement Table
$(document).ready(function() {
  $('#anntable-tools').DataTable({
    responsive: true,
    columnDefs: [
      { width: '100px', targets: 0 }, // Set width for column 1
      { width: '150px', targets: 2 }, // Set width for column 3
      { width: '450px', targets: 3 }, // Set width for column 4
      { width: '150px', targets: 4 }, // Set width for column 5
      { width: '200px', targets: 5 }, // Set width for column 6
      { width: '200px', targets: 6 }, // Set width for column 7
      // Add more columnDefs as needed
    ],
    dom: 'Bfrtip',
    // "pageLength": 7,
    scrollX: true,
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
} );


// Countdown Table
$(document).ready(function() {
  $('#counttable-tools').DataTable({
    responsive: true,
    columnDefs: [
      { width: '100px', targets: 0 }, // Set width for column 1
      { width: '350px', targets: 2 }, // Set width for column 3
      { width: '450px', targets: 3 }, // Set width for column 4
      { width: '250px', targets: 4 }, // Set width for column 5
      // Add more columnDefs as needed
    ],
    dom: 'Bfrtip',
    // "pageLength": 7,
    scrollX: true,
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
} );

// Groom Bride Table
$(document).ready(function() {
  $('#groombridetable-tools').DataTable({
    responsive: true,
    columnDefs: [
      { width: '100px', targets: 0 }, // Set width for column 1
      { width: '100px', targets: 2 }, // Set width for column 3
      { width: '250px', targets: 3 }, // Set width for column 4
      { width: '250px', targets: 4 }, // Set width for column 5
      { width: '650px', targets: 5 }, // Set width for column 6
      // Add more columnDefs as needed
    ],
    dom: 'Bfrtip',
    // "pageLength": 7,
    scrollX: true,
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
} );