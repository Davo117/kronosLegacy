$('#profileclick').on('click', function(){
   $('#filePhotoInput').click();
});

$('#filePhotoInput').change(function(){
//----------------------------------------
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $('#formProfilePhoto');
form.validate({
  rules: {
    file: "required",
  },
  messages: {
    file: "Debes de elegir una imagen.",
  }
});
var dado = form.valid();
if (dado == true){
  //información del formulario
  var formpicture = new FormData($("#formProfilePhoto")[0]);
  //hacemos la petición ajax  
  $.ajax({
      url: 'ajax',  
      type: 'POST',
      // Form data
      //datos del formulario
      data: formpicture,
      //necesario para subir archivos via ajax
      cache: false,
      contentType: false,
      processData: false,
      //mientras enviamos el archivo
      beforeSend: function(){

      },
      //una vez finalizado correctamente
      success: function(datapicprofile){

      	$('#profileclick').attr('src', datapicprofile);
      	$('#filePhotoInput').val('');

       },
      //si ha ocurrido un error
      error: function(){

      }
  });
}else{
  return;
}
  return false;
//----------------------------------------
});



$('#laststepbutton').on('click', function(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#laststepform");
form.validate({
  rules: {
    phone: "required",
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
             $('#laststepbutton').hide();
             $('#clock-login').show();
          },
          success: function(response){
             
            if (response == 1)
            {
                $('#clock-login').hide();
                $('#success-register').show();
                setTimeout(function(){window.location.href = "dashboard";},5000);
            }
            else if (response == 2)
            {

            }

          },
          error: function(){

          }
    });
}else{
  return;
  }
});


function editProfile(){

     $('#myprofileform input').prop("disabled", false).addClass('form-white');
     $('#myprofileform select').prop("disabled", false).addClass('form-white');
     $('#editprofilebutton').hide();
     $('#savechangesprofile').show();

}

function saveChangeProfile(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#myprofileform");
form.validate({
  rules: {
    nombre: "required",
    apellido: "required",
    pais: "required",
    phone: "required",
  },
});

var dado = form.valid();
if (dado == true){
        var changeprofile = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: changeprofile,
          beforeSend: function(){

          },
          success: function(response){


            if (response == 2)
            {
               var textnotification = 'Tus datos se han actualizado correctamente.';
            }
            else
            {
               var textnotification = 'Hubo un error al actualizar tus datos.';
            }

            var x = noty({
              text        : '<div id="copy-success-link" class="alert alert-default"><p><strong>'+textnotification+'</p></div>',
              layout      : 'topRight', //or left, right, bottom-right...
              theme       : 'made',
              maxVisible  : 10,
              animation   : {
                  open  : 'animated bounceIn',
                  close : 'animated bounceOut'
              },
              timeout: 5000,
          });

          $('#myprofileform input').prop("disabled", true).removeClass('form-white');
          $('#myprofileform select').prop("disabled", true).removeClass('form-white');
          $('#editprofilebutton').show();
          $('#savechangesprofile').hide();

          },
          error: function(){

          }
    });
}else{
  return;
  }


}



$('.tree-user').on('click', function(){

      $('#myModal').modal('show');
});

//------------------------------------------------------------------------------------------------------
function AgregarAlArbol(){
////////////////////////////

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#addtotree");
form.validate({
  rules: {
    email: "required",
    mensaje: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var regtreed = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: regtreed,
          beforeSend: function(){
             form.hide();
             $('#modalfootertree').hide();
             $('#alertadd1').show();
          },
          success: function(response){

              form[0].reset();
             
              if (response == 3){

                $('#alertadd1').hide();
                $('#alertadd2').show();

                setTimeout(function(){
                   form.show();
                   $('#modalfootertree').show();
                   $('#alertadd1').hide();
                },1000);

              }else if (response == 2){

                $('#alertadd1').hide();
                $('#alertadd3').show();

                setTimeout(function(){
                   form.show();
                   $('#modalfootertree').show();
                   $('#alertadd1').hide();
                },1000);
              }

          },
          error: function(){

          }
    });
}else{
  return;
  }




////////////////////////////
}
//------------------------------------------------------------------------------------------------------


$('#linkper').on('click', function(){
       $(this).hide();
       $('#linkpersave').show();
       $('#referalfrmlnk>input').removeAttr('readonly').addClass('form-white');
});


$('#linkpersave').on('click', function(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#referalfrmlnk");
form.validate({
  rules: {
    referalink: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var lnkrefupda = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: lnkrefupda,
          beforeSend: function(){

          },
          success: function(response){
             
            if (response == 2)
            {
               var textnotification = 'No puedes dejar los espacios en blanco ni usar caracteres ilegibles para tu enlace.';
            }
            else
            {
               var textnotification = 'Tu link de referencia se ha actualizado correctamente.';
            }

            var x = noty({
              text        : '<div id="copy-success-link" class="alert alert-default"><p><strong>'+textnotification+'</p></div>',
              layout      : 'topRight', //or left, right, bottom-right...
              theme       : 'made',
              maxVisible  : 10,
              animation   : {
                  open  : 'animated bounceIn',
                  close : 'animated bounceOut'
              },
              timeout: 5000,
          });

            $('#linkpersave').hide();
            $('#linkper').show();
            $('#referalfrmlnk>input').attr('readonly', '').removeClass('form-white');
            $('#referalfrmlnk>input').val(response);


          },
          error: function(){

          }
    });
}else{
  return;
  }


});


function ChangeMyPass(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#passform");
form.validate({
  rules: {
    actual: "required",
    newpass: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var changepass = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: changepass,
          beforeSend: function(){

          },
          success: function(response){

              form[0].reset();

              if(response == 2){
                 $('#chgpass2').show();
                 setTimeout(function(){$('#chgpass2').hide();},2500);
              }else if(response == 3){
                 $('#chgpass1').show();
                 setTimeout(function(){$('#myModalC').modal('toggle');},2000);
              }else if(response == 4){
                 $('#chgpass3').show();
                 setTimeout(function(){$('#chgpass3').hide();},2500);
              }


          },
          error: function(){

          }
    });
}else{
  return;
  }


}

//---------------------------------------------------------------------
function ChangeMyEmail(){

jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
var form = $("#emailfrmch");
form.validate({
  rules: {
    newemail: "required",
    actual: "required"
  },
});

var dado = form.valid();
if (dado == true){
        var cangeemail = form.serialize();
        $.ajax({
          type: 'POST',
          url: 'ajax',
          data: cangeemail,
          beforeSend: function(){

          },
          success: function(response){

              form[0].reset();

              if(response == 2){
                 $('#chgemail4').show();
                 setTimeout(function(){$('#chgemail4').hide();},2500);
              }else if(response == 3){
                 $('#chgemail2').show();
                 setTimeout(function(){$('#chgemail2').hide();},2500);
              }else if(response == 4){
                 $('#chgemail3').show();
                 setTimeout(function(){$('#chgemail3').hide();},2500);
              }else if(response == 5){
                 $('#chgemail1').show();
                 setTimeout(function(){$('#chgemail1').hide();},2500);
                 setTimeout(function(){$('#myModalE').modal('toggle');},1000);
              }


          },
          error: function(){

          }
    });
}else{
  return;
  }


}