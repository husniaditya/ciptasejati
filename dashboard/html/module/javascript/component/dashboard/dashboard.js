

// Anggota Table
$(document).ready(function() {
    $('#dashboard-table').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      pageLength: 5,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf'
      ]
    });
});