function disableButton() {
    // Disable the button
    document.getElementById('sendemail').disabled = true;
  
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
        document.getElementById('sendemail').disabled = false;
        countdownElement.style.display = 'none';
      }
    }, 1000); // Update every 1 second
  }

  function sendEmailNotification(ID, PASS) { // Function Send Email Notification
    return new Promise(function(resolve, reject) {
      $.ajax({
        type: 'POST',
        url: 'module/backend/loginregister/t_mailreset.php',
        data: { id: ID, pass: PASS },
        success: function(response) {
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

function generateRandomPassword() {
  const length = 8;
  const chars = 'ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789@#$%&';

  let password = '';
  for (let i = 0; i < length; i++) {
      const randomChar = chars.charAt(Math.floor(Math.random() * chars.length));
      password += randomChar;
  }

  return password;
}

$(document).ready(function () {
  
  handleForm('#form-reset', SuccessNotification, FailedNotification, UpdateNotification);

    // AJAX function to be called
    function performAjaxCall(value) {
        // Make an AJAX request to fetch additional data based on the selected value

        $.ajax({
            url: 'module/ajax/loginregister/aj_resetPass.php',
            method: 'POST',
            data: { id: value },
            success: function(data) {
                // console.log(data);
                // Assuming data is a JSON object with the required information
                // Make sure the keys match the fields in your returned JSON object

                // Populate the form fields with the retrieved data
                $("#ANGGOTA_EMAIL").val(data.ANGGOTA_EMAIL);
                $("#ANGGOTA_REMARKS").val(data.ANGGOTA_REMARKS);

                // Clear any previous error message
                if (value === '') {
                  $('#cekAnggota').text('');
                } else {
                  $('#cekAnggota').text(data.ANGGOTA_REMARKS);
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

    // Format as 11.11.1111.11111 (groups: 2-2-4-5)
    var digits = inputValue.substring(0, 13); // limit to max 13 digits
    var parts = [];
    if (digits.length > 0) parts.push(digits.substring(0, Math.min(2, digits.length)));
    if (digits.length > 2) parts.push(digits.substring(2, Math.min(4, digits.length)));
    if (digits.length > 4) parts.push(digits.substring(4, Math.min(8, digits.length)));
    if (digits.length > 8) parts.push(digits.substring(8, Math.min(13, digits.length)));
    var formattedValue = parts.join('.');

    // Update the input value with the formatted value
    $(this).val(formattedValue);

    // Call the debounced AJAX function with the formatted value
    debouncedAjaxCall(formattedValue);
  });
});


// ----- Start of Anggota Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
    // Function to show the full-screen loading overlay with a progress bar
    function showLoadingOverlay(message) {
      var overlayHtml = '<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div><div class="loading-message">' + message + '</div><div class="progress-bar"><div class="progress"></div></div></div>';
      $('body').append(overlayHtml);
    }
    
    // Generate random password
    const randomPassword = generateRandomPassword();
  
    $(formId).submit(function (event) {
      // Example usage:
      showLoadingOverlay('Data sedang diproses, mohon ditunggu.');
      
      event.preventDefault(); // Prevent the default form submission
  
      var formData = new FormData($(this)[0]); // Create FormData object from the form
      var buttonId = $(event.originalEvent.submitter).attr('id'); // Retrieve button ID);
  
      // Manually add the button title or ID to the serialized data
      formData.append(buttonId, 'edit');
      
      // Add random password to FormData
      formData.append('randomPassword', randomPassword);

      // console.log("Values inside formData:", Array.from(formData.values()));

  
      var ID; // Declare ID here to make it accessible in the outer scope
      var PASS; // Declare PASS here to make it accessible in the outer scope
  
      $.ajax({
        type: 'POST',
        url: 'module/backend/loginregister/t_resetpassword.php',
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting content type
        success: function (response) {
          // Split the response into parts using a separator (assuming a dot in this case)
          var parts = response.split(',');
          var successMessage = parts[0];
          ID = parts[1]; // Assign value to ID
          PASS = parts[2]; // Assign value to PASS
          // console.log(response);
  
          // Check the response from the server
          if (successMessage === 'Success') {
  
            // Hide the loading overlay after the initial processing
            hideLoadingOverlay();
  
            // Example usage:
            showLoadingOverlay('Proses pembuatan dokumen dan pengiriman email...');
  
            // Send email notification concurrently
            Promise.all([sendEmailNotification(ID, PASS)])
            .then(function (responses) {
              const emailResponse = responses[0];

              if (emailResponse === 'Success') {
                MailNotification('Email reset password berhasil dikirimkan!');
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
  
  