
function callTable() {

    $('#riwayatmutasi-table').DataTable({
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '150px', targets: 1 }, // Set width for column 2
        { width: '150px', targets: 2 }, // Set width for column 3
        { width: '150px', targets: 3 }, // Set width for column 4
        { width: '100px', targets: 4 }, // Set width for column 5
        { width: '100px', targets: 5 }, // Set width for column 6
        { width: '100px', targets: 6 }, // Set width for column 7
        { width: '100px', targets: 7 }, // Set width for column 8
        // Add more columnDefs as needed
    ],
    paging: true,
    scrollX: true,
    scrollY: '350px', // Set the desired height here
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
    });

    $('#mutasikas-table').DataTable({
        responsive: true,
        order: [], // Adjust the column index and order direction
        dom: 'Bfrtlip',
        paging: true,
        scrollX: true,
        scrollY: '350px', // Set the desired height here
        buttons: [
            {
                extend: 'copy',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'csv',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'excel',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI',
            },
            {
                extend: 'pdf',
                title: 'Riwayat Mutasi Kas - CIPTA SEJATI'
            }
        ]
    });
    
    $('#idsertifikat-table').DataTable({
    responsive: true,
    order: [[9, 'asc']],
    dom: 'Bfrtlip',
    columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '150px', targets: 1 }, // Set width for column 2
        { width: '150px', targets: 2 }, // Set width for column 3
        { width: '150px', targets: 3 }, // Set width for column 4
        { width: '100px', targets: 4 }, // Set width for column 5
        { width: '100px', targets: 5 }, // Set width for column 6
        { width: '100px', targets: 6 }, // Set width for column 7
        { width: '100px', targets: 7 }, // Set width for column 8
        { width: '100px', targets: 8 }, // Set width for column 9
        { width: '100px', targets: 9 }, // Set width for column 10
        // Add more columnDefs as needed
    ],
    paging: true,
    scrollX: true,
    scrollY: '350px', // Set the desired height here
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
    });

    $('#riwayatppd-table').DataTable({
    responsive: true,
    order: [[7, 'asc']],
    dom: 'Bfrtlip',
    columnDefs: [
        { width: '100px', targets: 0 }, // Set width for column 1
        { width: '150px', targets: 1 }, // Set width for column 2
        { width: '150px', targets: 2 }, // Set width for column 3
        { width: '150px', targets: 3 }, // Set width for column 4
        { width: '100px', targets: 4 }, // Set width for column 5
        { width: '100px', targets: 5 }, // Set width for column 6
        { width: '100px', targets: 6 }, // Set width for column 7
        { width: '100px', targets: 7 }, // Set width for column 8
        // Add more columnDefs as needed
    ],
    paging: true,
    scrollX: true,
    scrollY: '350px', // Set the desired height here
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
    });
  }

function previewImageedit(input) {
    var previewImage = document.getElementById('preview-image');
    var previewContainer = document.getElementById('preview-container');
  
    var reader = new FileReader();
  
    reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewContainer.style.display = 'block';
    };
  
    // Make sure to read the file even if no file is selected
    reader.readAsDataURL(input.files[0]);
}

function fetchDataAndPopulateForm(value1, value2) {
    // Assign PHP session variables to JavaScript variables
    var anggotaKey = value1;
    var cabangKey = value2;


    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
        url: 'module/ajax/page-profile/aj_getprofile.php',
        method: 'POST',
        data: { ANGGOTA_KEY: anggotaKey, CABANG_KEY: cabangKey },
        success: function(data) {
            // Set the values of the Profile form fields
            $("#ANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
            $("#ANGGOTA_PIC").attr("src", data.ANGGOTA_PIC);
            $("#preview-image").attr("src", data.ANGGOTA_PIC);
            $("#ANGGOTA_TEMPAT_LAHIR").val(data.ANGGOTA_TEMPAT_LAHIR);
            $("#datepicker44").val(data.ANGGOTA_TANGGAL_LAHIR);
            $("#ANGGOTA_AGAMA").val(data.ANGGOTA_AGAMA);
            $("#ANGGOTA_KELAMIN").val(data.ANGGOTA_KELAMIN);
            $("#ANGGOTA_KTP").val(data.ANGGOTA_KTP);
            $("#ANGGOTA_ALAMAT").val(data.ANGGOTA_ALAMAT);
            $("#ANGGOTA_PEKERJAAN").val(data.ANGGOTA_PEKERJAAN);
            $("#ANGGOTA_HP").val(data.ANGGOTA_HP);
            $("#KAS_ANGGOTA").html('Rp ' + data.KAS_ANGGOTA);
            $("#CABANG_DESKRIPSI2").html(data.CABANG_DESKRIPSI);

            // Set the values of the Member form fields
            $("#ANGGOTA_ID").val(data.ANGGOTA_ID);
            $("#TINGKATAN").val(data.TINGKATAN);
            $("#ANGGOTA_RANTING").val(data.ANGGOTA_RANTING);
            $("#CABANG_DESKRIPSI").val(data.CABANG_DESKRIPSI);
            $("#DAERAH_DESKRIPSI").val(data.DAERAH_DESKRIPSI);
            $("#ANGGOTA_EMAIL").val(data.ANGGOTA_EMAIL);
            $("#ANGGOTA_HP").val(data.ANGGOTA_HP);
        },
        error: function(error) {
            console.error('Error fetching data:', error);
        }
    });

    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasianggota.php",
        data:'ANGGOTA_KEY='+anggotaKey,
        success: function(data){
            $("#riwayatmutasi").html(data);
            // Reinitialize Sertifikat Table
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
    });
        
    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasikas.php",
        data:'ANGGOTA_KEY='+anggotaKey,
        success: function(data){
            $("#riwayatkas").html(data);
            // Reinitialize Sertifikat Table
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
    });
}


$(document).ready(function() {
    var anggotaKey = document.getElementById('JANGGOTA_KEY').innerHTML;
    var cabangKey = document.getElementById('JCABANG_KEY').innerHTML;
    // Call the function when needed
    fetchDataAndPopulateForm(anggotaKey,cabangKey);
    callTable()
});