function sendEmailNotification(ID) { // Function Send Email Notification
  return new Promise(function(resolve, reject) {
    $.ajax({
      type: 'POST',
      url: 'module/backend/loginregister/t_maildaftar.php',
      data: { id: ID },
      success: function(response) {
        console.log(response);
        // Check the response from the server
        resolve(response);
      },
      error: function(xhr, status, error) {
        reject('Error! ' + xhr.status + ' ' + error);
      }
    });
  });
}

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
    // Hide the button
    var resendButton = document.getElementById('resendemail');
    var daftarButton = document.getElementById('savedaftaruser');
    var passwordbaru = document.getElementById('passwordbaru');
    var konfirmasi = document.getElementById('konfirmasi');
    resendButton.style.display = 'none';
    daftarButton.style.display = 'block';

    // AJAX function to be called
    function performAjaxCall(value) {
        // Make an AJAX request to fetch additional data based on the selected value
        $.ajax({
            url: 'module/ajax/loginregister/aj_getAnggota.php',
            method: 'POST',
            data: { id: value },
            success: function(data) {
                // console.log(data);
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
                if (value === '') {
                  $('#cekAnggota').text('');
                } else {
                  $('#cekAnggota').text(data.ANGGOTA_REMARKS);
                }

                if (data.ANGGOTA_REMARKS === 'ID Anggota belum melakukan verifikasi!') {
                  daftarButton.style.display = 'none';
                  resendButton.style.display = 'block';
                  passwordbaru.style.display = 'none';
                  konfirmasi.style.display = 'none';
                } else {
                  daftarButton.style.display = 'block';
                  passwordbaru.style.display = 'block';
                  konfirmasi.style.display = 'block';
                  resendButton.style.display = 'none';
                }
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

    $('input[name="ANGGOTA_ID"]').on('input', function () {
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

function disableButton() {
  // Disable the button
  document.getElementById('resendemail').disabled = true;

  // Show the countdown element
  var countdownElement = document.getElementById('countdown');
  countdownElement.style.display = 'inline';

  // Set the initial countdown value
  var countdownValue = 15;

  // Update the countdown every second
  var countdownInterval = setInterval(function() {
    countdownValue -= 1;
    countdownElement.textContent = '(' + countdownValue + 'detik)';

    // Enable the button and hide the countdown when the countdown reaches 0
    if (countdownValue <= 0) {
      clearInterval(countdownInterval);
      document.getElementById('resendemail').disabled = false;
      countdownElement.style.display = 'none';
    }
  }, 1000); // Update every 1 second
}


// ----- Start of Anggota Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
    // Function to show the full-screen loading overlay with a progress bar
    function showLoadingOverlay(message) {
      var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
      $('body').append(overlayHtml);
    }
  
    $(formId).submit(function (event) {
      // Example usage:
      showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
      
      event.preventDefault(); // Prevent the default form submission
  
      var formData = new FormData($(this)[0]); // Create FormData object from the form
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);
  
      // Manually add the button title or ID to the serialized data
      formData.append(buttonId, 'edit');
  
      var ID; // Declare ID here to make it accessible in the outer scope
  
      $.ajax({
        type: 'POST',
        url: 'module/backend/loginregister/t_daftar.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function (response) {
          // Split the response into parts using a separator (assuming a dot in this case)
          var parts = response.split(',');
          var successMessage = parts[0];
          ID = parts[1]; // Assign value to ID
          console.log(response);
  
          // Check the response from the server
          if (successMessage === 'Success') {
            // Display success notification
            successNotification('Akun berhasil dibuat, mohon cek email anda untuk verifikasi akun!');
  
            // Hide the loading overlay after the initial processing
            hideLoadingOverlay();
  
            // Example usage:
            showLoadingOverlay('Proses pembuatan dokumen dan pengiriman email...');
  
            // Send email notification concurrently
            Promise.all([sendEmailNotification(ID)])
            .then(function (responses) {
              const emailResponse = responses[0];

              if (emailResponse === 'Success') {
                MailNotification('Email verifikasi berhasil dikirimkan!');
              } else {
                failedNotification(emailResponse);
              }
            })
            .catch(function (error) {
              // Handle the single error
              errorNotification(error);
            })
            .finally(function () {
              disableButton();
              // Hide the loading overlay after all asynchronous tasks are complete
              hideLoadingOverlay();
              // document.getElementById("form-register").reset();
              // Show the button
              var resendButton = document.getElementById('resendemail');
              resendButton.style.display = 'block';
            });
          } else {
            // Display error notification
            failedNotification(response);
  
            // Hide the loading overlay in case of an error
            hideLoadingOverlay();
          }
        },
        error: function (xhr, status, error) {
          // Handle any errors
  
          // Hide the loading overlay in case of an error
          hideLoadingOverlay();
        }
      });
    });
  }
  
  
  $(document).ready(function() {
    handleForm('#form-register', SuccessNotification, FailedNotification, UpdateNotification);
  });