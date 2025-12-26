


$('#iniciarsession').on('click', function(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#loginfrm");
form.validate({
  rules: {
    email: "required",
    password: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var publicaciondata = $("#loginfrm").serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: publicaciondata,
          beforeSend: function(){
             form.hide();
             $('#login-links').hide();
             $('#clock-login').show();
          },
          success: function(response){
          	 
            if (response == 1)
            {
                window.location.href = "dashboard";
            }
            else
            {
                $('#clock-login').hide();
                form[0].reset();
                form.show();
                $('#login-links').show();
                $('#error-login').show();
                setTimeout(function(){$('#error-login').hide();},5000);
            }

          },
          error: function(){

          }
    });
}else{
  return;
  }
});




$('#recuperarmicuenta').on('click', function(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#loginfrm");
form.validate({
  rules: {
    email: "required",
  },
});

var dado = form.valid();
if (dado == true){
        var publicaciondata = $("#loginfrm").serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: publicaciondata,
          beforeSend: function(){
             form.hide();
             $('#login-links').hide();
             $('#clock-login').show();
          },
          success: function(response){
   

            if (response == 1)
            {

                $('#clock-login').hide();
                form[0].reset();
                form.show();
                $('#login-links').show();
                $('#success-recovery').show();
                setTimeout(function(){$('#success-recovery').hide();},5000);
                
            }
            else
            {
                $('#clock-login').hide();
                form[0].reset();
                form.show();
                $('#login-links').show();
                $('#error-recovery').show();
                setTimeout(function(){$('#error-recovery').hide();},5000);
            }


          },
          error: function(){

          }
    });
}else{
  return;
  }
});




$('#registrobutton').on('click', function(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#registerform");
form.validate({
  rules: {
    nombre: "required",
    apellido: "required",
    email: "required",
    password: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var registerdata = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: registerdata,
          beforeSend: function(){
             form.hide();
             $('#login-links').hide();
             $('#clock-login').show();
          },
          success: function(response){
             
            if (response == 1)
            {
                $('#success-register').show();
                $('#clock-login').hide();
            }
            else if (response == 2)
            {
                $('#clock-login').hide();
                form[0].reset();
                form.show();
                $('#login-links').show();
                $('#error-login').show();
                $('#clock-login').hide();
                setTimeout(function(){$('#error-login').hide();},5000);

            }

          },
          error: function(){

          }
    });
}else{
  return;
  }
});


function copyToClipboard() {

  var temp = $("<input>");
  $("body").append(temp);
  var valselected = $('#referallink').val();
  temp.val(valselected).select();
  document.execCommand("copy");
  temp.remove();
  var n = noty({
    text        : '<div id="copy-success-link" class="alert alert-default"><p><strong>Tu link de referencia ha sido copiado a tu porta-papeles.</p></div>',
    layout      : 'topRight', //or left, right, bottom-right...
    theme       : 'made',
    maxVisible  : 10,
    animation   : {
        open  : 'animated bounceIn',
        close : 'animated bounceOut'
    },
    timeout: 5000,
});

}




