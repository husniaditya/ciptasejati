

// Konten Header Table
$(document).ready(function() {
    $('#visi-table').DataTable({
      dom: 'Bfrtlip',
      autoWidth: true,
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
});

// Konten Header Table
$(document).ready(function() {
  $('#misi-table').DataTable({
    dom: 'Bfrtlip',
    autoWidth: true,
    // "pageLength": 7,
    scrollX: true,
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
  });
});