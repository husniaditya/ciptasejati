

// Konten Header Table
$(document).ready(function() {
    $('#visi-table').DataTable({
      dom: 'Bfrtip',
      autoWidth: true,
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});

// Konten Header Table
$(document).ready(function() {
  $('#misi-table').DataTable({
    dom: 'Bfrtip',
    autoWidth: true,
    // "pageLength": 7,
    scrollX: true,
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
});