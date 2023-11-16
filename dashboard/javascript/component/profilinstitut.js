

// Konten Header Table
$(document).ready(function() {
    $('#profilinstitut-table').DataTable({
      dom: 'Bfrtip',
      autoWidth: true,
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});