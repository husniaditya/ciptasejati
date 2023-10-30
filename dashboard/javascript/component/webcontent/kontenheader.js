

// Konten Header Table
$(document).ready(function() {
    $('#kontenheader-table').DataTable({
      responsive: true,
      columnDefs: [
        { width: '150px', targets: 0 }, // Set width for column 1
        { width: '300px', targets: 2 }, // Set width for column 3
        { width: '100px', targets: 3 }, // Set width for column 4
        { width: '200px', targets: 4 }, // Set width for column 5
        { width: '150px', targets: 5 }, // Set width for column 6
        { width: '150px', targets: 6 }, // Set width for column 7
        // Add more columnDefs as needed
      ],
      dom: 'Bfrtip',
      // "pageLength": 7,
      scrollX: true,
      scrollY: '350px', // Set the desired height here
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
});