

// Anggota Table
$(document).ready(function() {
    $('#anggota-table').DataTable({
      responsive: true,
      dom: 'Bfrtlip',
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
});