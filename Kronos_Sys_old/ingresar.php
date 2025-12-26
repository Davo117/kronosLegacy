 <?php 
  $nombre_fichero = 'css/logu.css';
  $bootstrap='bootstrap/css/bootstrap.min.css';
  $nombresistema='Kronos_Sys';
if (file_exists($nombre_fichero)) {
} else {
   $nombre_fichero = '../css/logu.css';
}
$file_proceso='Controler/procesarlogeo.php';
if(file_exists($file_proceso))
{
}
else
{
  $file_proceso='../Controler/procesarlogeo.php';
}
$file_logo="pictures/logo-labro.png";
if(file_exists($file_logo)){}
else{
  $file_logo="../pictures/logo-labro.png";
}

$file_menu='Menu.php';
if(file_exists($file_menu)){}
else{
  $file_menu='../Menu.php';
}


  $ruta=getcwd();
  $ruta=explode($nombresistema.'/',$ruta);
  $ruta=$ruta['1'];
    $archivo_actual = basename($_SERVER['PHP_SELF']);
    $redr=$ruta.'/'.$archivo_actual;

    if (file_exists($bootstrap)) {
} else {
   $bootstrap = '../bootstrap/css/bootstrap.min.css';
}
  ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="apple-touch-icon" sizes="76x76" href="pictures/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="pictures/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="pictures/favicon-16x16.png">
  <link rel="manifest" href="pictures/manifest.json">
  <link rel="mask-icon" href="pictures/safari-pinned-tab.svg" color="#5bbad5">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="pictures/favicon-16x16.png">
<link rel="manifest" href="pictures/manifest.json">
<style type="text/css">
  





</style>
  <link href="<?php echo $bootstrap;?>" rel="stylesheet" id="bootstrap-css">
<link rel="mask-icon" href="pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
  <title>Login</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <link rel="stylesheet" type="text/css" href="<?php echo $nombre_fichero;?>"  />
</head>
<body style="overflow:hidden;">
  <div  class="grid" style="border-radius:10px;border-color:black;margin-top:10%;webkit-box-shadow: 1px 1px 27px 4px rgba(0,0,0,0.75);
-moz-box-shadow: 1px 1px 27px 4px rgba(0,0,0,0.75);
box-shadow: 1px 1px 27px 4px rgba(0,0,0,0.75);padding:20px">
   <img id="foto-contenedor-1" class="img-responsive" src="<?php echo $file_logo;?>"/>

   <form action="<?php echo $file_proceso;?>" method="POST" class="form login">

      <div class="wrap-input100 validate-input">
            <input class="input100" type="text" name="user" placeholder="Usuario">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </span>
          </div>

          <div class="wrap-input100 validate-input" data-validate = "Password is required">
            <input class="input100" type="password" name="pass" placeholder="Contraseña">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
          </div>

    <div class="container-login100-form-btn" >
      <input class="login100-form-btn" type="submit" value="Ingresar" >
    </div>
    <div style="text-align:center;color:black;margin-top:15px"> 
<a class="btn btn-info" href="<?php echo $file_menu;?>"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Regresar</a> 
</div>
     <input  style="visibility:hidden;" type="text" name="txtRuta" value="<?php echo $redr;?>">;

  </form>


</div>

<svg xmlns="http://www.w3.org/2000/svg" class="icons"><symbol id="arrow-right" viewBox="0 0 1792 1792"><path d="M1600 960q0 54-37 91l-651 651q-39 37-91 37-51 0-90-37l-75-75q-38-38-38-91t38-91l293-293H245q-52 0-84.5-37.5T128 1024V896q0-53 32.5-90.5T245 768h704L656 474q-38-36-38-90t38-90l75-75q38-38 90-38 53 0 91 38l651 651q37 35 37 90z"/></symbol><symbol id="lock" viewBox="0 0 1792 1792"><path d="M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z"/></symbol><symbol id="user" viewBox="0 0 1792 1792"><path d="M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z"/></symbol></svg>
</body>

</html>