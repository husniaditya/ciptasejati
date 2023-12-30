

// Konten Header Table
$(document).ready(function() {
    $('#profilinstitut-table').DataTable({
      dom: 'Bfrtlip',
      autoWidth: true,
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
});