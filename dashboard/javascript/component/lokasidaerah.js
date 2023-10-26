

// Anggota Table
$(document).ready(function() {
    $('#lokasidaerah-table').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      dom: 'Bfrtip',
      columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '250px', targets: 1 }, // Set width for column 2
        { width: '350px', targets: 2 }, // Set width for column 3
        { width: '50px', targets: 3 }, // Set width for column 4
        { width: '250px', targets: 4 }, // Set width for column 5
        { width: '250px', targets: 5 }, // Set width for column 6
        // Add more columnDefs as needed
      ],
      // "pageLength": 7,
      scrollX: true,
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});
