

$(document).on('keyup','.checkpassword',function(){
    var newpassword = $(NEWPASSWORD).val();
    var confirmpassword = $(CONFIRMPASSWORD).val();

    $.ajax({
        type: "POST",
        url: "./module/ajax/header/aj_checkpassword.php",
        data: {NEWPASSWORD :newpassword, CONFIRMPASSWORD:confirmpassword},
        success: function(data){
        $("#passwordcheck").html(data);
        }
        });
        // console.log(newpassword, confirmpassword);
});

function loadAndRefresh() {
    // Load content initially when the document is ready
    $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
    $('#listnotif').load('./module/ajax/header/aj_listnotif.php');

    // Set up an interval to refresh content every 5 seconds
    setInterval(function() {
        $('#loadnotif').load('./module/ajax/header/aj_loadnotif.php');
        $('#listnotif').load('./module/ajax/header/aj_listnotif.php');
    }, 5000);
}

// Call the function when the document is ready
$(document).ready(loadAndRefresh);

