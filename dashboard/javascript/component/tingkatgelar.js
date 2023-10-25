

// Anggota Table
$(document).ready(function() {
    $('#tingkatgelar-table').DataTable({
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
});