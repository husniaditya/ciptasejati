
function callTable() {
    // Destroy DataTable for riwayatmutasi-table if it exists
    if ($.fn.DataTable.isDataTable('#riwayatmutasi-table')) {
      $('#riwayatmutasi-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#mutasikas-table')) {
      $('#mutasikas-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#riwayatukt-table')) {
      $('#riwayatukt-table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#riwayatppd-table')) {
      $('#riwayatppd-table').DataTable().destroy();
    }

    $('#riwayatmutasi-table').DataTable({
    responsive: true,
    order: [],
    dom: 'Bfrtip',
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
        dom: 'Bfrtip',
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
    
    $('#riwayatukt-table').DataTable({
    responsive: true,
    order: [],
    dom: 'Bfrtip',
    paging: true,
    scrollX: true,
    scrollY: '350px', // Set the desired height here
    buttons: [
        'copy', 'csv', 'excel', 'pdf'
    ]
    });

    $('#riwayatppd-table').DataTable({
        responsive: true,
        order: [],
        dom: 'Bfrtip',
        paging: true,
        scrollX: true,
        scrollY: '350px', // Set the desired height here
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ],
        columnDefs: [
            { width: '5%', targets: 0 },
            { width: '10%', targets: 1 },
            { width: '10%', targets: 2 },
            { width: '10%', targets: 3 },
            { width: '10%', targets: 4 },
            { width: '10%', targets: 5 },
            { width: '5%', targets: 6 },
            { width: '5%', targets: 7 },
            { width: '10%', targets: 8 },
            { width: '10%', targets: 9 },
            { width: '10%', targets: 10 },
            { width: '10%', targets: 11 },
            { width: '5%', targets: 12 }
        ],
        autoWidth: false,
        fixedColumns: true,
    }).columns.adjust().draw();
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

function fetchDataAndPopulateForm(value1, value2, value3) {
    // Assign PHP session variables to JavaScript variables
    var anggotaKey = value1;
    var cabangKey = value2;
    var anggotaid = value3;


    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
        url: 'module/ajax/page-profile/aj_getprofile.php',
        method: 'POST',
        data: { ANGGOTA_ID: anggotaid, CABANG_KEY: cabangKey },
        success: function(data) {
            // Set the values of the Profile form fields
            $("#ANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
            $("#ANGGOTA_PIC").attr("src", data.ANGGOTA_PIC);
            $("#preview-image").attr("src", data.ANGGOTA_PIC);
            $("#profileImage").attr("src", data.ANGGOTA_PIC);
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
            $("#ANGGOTA_JOIN").val(data.ANGGOTA_JOIN);
            $("#ANGGOTA_RESIGN").val(data.ANGGOTA_RESIGN);
            $("#ANGGOTA_EMAIL").val(data.ANGGOTA_EMAIL);
            $("#ANGGOTA_HP").val(data.ANGGOTA_HP);
        },
        error: function(error) {
            console.error('Error fetching data:', error);
        }
    });
}

$(document).on("click", ".mutasikas", function () {
    
    var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;
    var cabangKey = document.getElementById('JCABANG_KEY').innerHTML;
    
    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasikas.php",
        data: { id: anggotaid, cabang: cabangKey },
        success: function(data){
            // console.log(data);
            // Destroy the DataTable before updating
            $('#mutasikas-table').DataTable().destroy();
            $("#riwayatkas").html(data);
            // Reinitialize Sertifikat Table
            callTable();
        }
    });
});

$(document).on("click", ".riwayatppd", function () {
    
    var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;
    
    // Make an AJAX request to fetch additional data based on the selected value
    
    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getppdanggota.php",
        data:'id='+anggotaid,
        success: function(data){
            // Destroy the DataTable before updating
            $('#riwayatppd-table').DataTable().destroy();
            $("#daftariwayatppd").html(data);
            // Reinitialize Sertifikat Table
            callTable();
        }
    });
});

$(document).on("click", ".riwayatukt", function () {
    
    var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;
    
    // Make an AJAX request to fetch additional data based on the selected value
    
    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getuktanggota.php",
        data:'id='+anggotaid,
        success: function(data){
            // Destroy the DataTable before updating
            $('#riwayatukt-table').DataTable().destroy();
            $("#daftarriwayatukt").html(data);
            // Reinitialize Sertifikat Table
            callTable();
        }
    });
});

$(document).on("click", ".mutasi", function () {

    var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;

    // Make an AJAX request to fetch additional data based on the selected value
    $.ajax({
        type: "POST",
        url: "module/ajax/transaksi/anggota/daftaranggota/aj_getmutasianggota.php",
        data:'id='+anggotaid,
        success: function(data){
            // console.log(data);
            // Destroy the DataTable before updating
            $('#riwayatmutasi-table').DataTable().destroy();
            $("#riwayatmutasi").html(data);
            // Reinitialize Sertifikat Table
            callTable();
        }
    });
});

function handleForm(formId, successNotification, failedNotification, updateNotification) {
    $(formId).submit(function (event) {
      
        var anggotaKey = document.getElementById('JANGGOTA_KEY').innerHTML;
        var cabangKey = document.getElementById('JCABANG_KEY').innerHTML;
        var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;

        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData($(this)[0]); // Create FormData object from the form
        var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);

        // Manually add the button title or ID to the serialized data
        formData.append(buttonId, 'edit');

        $.ajax({
        type: 'POST',
        url: 'module/backend/page-profile/t_page-profile.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function (response) {
            // Check the response from the server
            if (response === 'Success') {
            // Display success notification
            successNotification('Data berhasil diubah!');
            fetchDataAndPopulateForm(anggotaKey,cabangKey,anggotaid);
            } else {
            // Display error notification
            failedNotification(response);
            }
        },
        error: function (xhr, status, error) {
            // Display error notification
            failedNotification('Terjadi kesalahan saat mengirim data!');
        }
        });
    });
}


$(document).ready(function() {
    var anggotaKey = document.getElementById('JANGGOTA_KEY').innerHTML;
    var cabangKey = document.getElementById('JCABANG_KEY').innerHTML;
    var anggotaid = document.getElementById('JANGGOTA_ID').innerHTML;

    // Call the function when needed
    fetchDataAndPopulateForm(anggotaKey,cabangKey,anggotaid);
    callTable();
    handleForm('#EditProfile-form', SuccessNotification, FailedNotification, UpdateNotification);
});