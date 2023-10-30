

// Konten Header Table
$(document).ready(function() {
    $('#profilinstitut-table').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      // "pageLength": 7,
      scrollX: 'true',
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});