// Debounce function to limit AJAX calls
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

$(document).ready(function () {
    // AJAX function to be called
    function performAjaxCall(value) {
        // Make an AJAX request to fetch additional data based on the selected value
        $.ajax({
            url: 'module/ajax/loginregister/aj_getAnggota.php',
            method: 'POST',
            data: { id: value },
            success: function(data) {
                // Assuming data is a JSON object with the required information
                // Make sure the keys match the fields in your returned JSON object

                // Populate the form fields with the retrieved data
                $("#ANGGOTA_NAMA").val(data.ANGGOTA_NAMA);
                $("#ANGGOTA_TTL").val(data.ANGGOTA_TTL);
                $("#ANGGOTA_RANTING").val(data.ANGGOTA_RANTING);
                $("#CABANG_KEY").val(data.CABANG_KEY);
                $("#DAERAH_KEY").val(data.DAERAH_KEY);
                $("#ANGGOTA_EMAIL").val(data.ANGGOTA_EMAIL);

                // Clear any previous error message
                $('#cekAnggota').text(data.ANGGOTA_REMARKS);
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Debounced function for the input event
    var debouncedAjaxCall = debounce(function (value) {
        performAjaxCall(value);
    }, 300);

    $('input[name="ID_ANGGOTA"]').on('input', function () {
        var inputValue = $(this).val().replace(/[^0-9]/g, '');

        // Insert a dot after each group of three digits
        var formattedValue = inputValue.replace(/(\d{3})(\d{0,3})(\d{0,4})(\d{0,3})/, function (match, p1, p2, p3, p4) {
            var result = p1;
            if (p2) result += '.' + p2;
            if (p3) result += '.' + p3;
            if (p4) result += '.' + p4;
            return result;
        });

        // Update the input value with the formatted value
        $(this).val(formattedValue);

        // Call the debounced AJAX function with the formatted value
        debouncedAjaxCall(formattedValue);
    });
});


