

$(document).on('keyup','.checkpassword',function(){
    var newpassword = $(NEWPASSWORD).val();
    var confirmpassword = $(CONFIRMPASSWORD).val();
    
    $.ajax({
        type: "POST",
        url: "./module/ajax/aj_checkpassword.php",
        data: {NEWPASSWORD :newpassword, CONFIRMPASSWORD:confirmpassword},
        success: function(data){
        $("#passwordcheck").html(data);
        }
        });
        // console.log(newpassword, confirmpassword);
  });
