<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><?php

  include '../datos/mysql.php';
    
  if ($_GET['modo']=='logout') 
  { session_unset();
    session_destroy(); }

  if ($_POST['txt_idusuario'])
  { $link_mysql = conectar();  
    $sistUsuarioSelect = $link_mysql->query("
      SELECT usuario 
        FROM sistusuario
       WHERE idusuario = '".$_POST['txt_idusuario']."' AND
             sttusuario != '0'");

    if ($sistUsuarioSelect->num_rows > 0)
    { $regQuery = $sistUsuarioSelect->fetch_object();
      $_SESSION['usuario'] = $regQuery->usuario;

      if ($_POST['txt_password'])
      { $link_mysql = conectar();              
        $sistUsuarioSelectWithPassword = $link_mysql->query("
          SELECT * FROM sistusuario
           WHERE idusuario = '".$_POST['txt_idusuario']."' AND 
                 wordpass = MD5('".$_POST['txt_password']."')");
    
        if ($sistUsuarioSelectWithPassword->num_rows > 0)
        { $regSistUsuario = $sistUsuarioSelectWithPassword->fetch_object();
          $_SESSION['idusuario'] = $regSistUsuario->idusuario;
          $_SESSION['cdgusuario'] = $regSistUsuario->cdgusuario; }
        else
        { session_unset();
          session_destroy(); 

          $msg_alert = 'Password incorrecto'; }
          
      $sistUsuarioSelectWithPassword->close; }
      else
      { $msg_alert = 'Password invalido '; }
    }
    else
    { session_unset();
      session_destroy(); 

      $msg_alert = 'Usuario invalido'; }

    $sistUsuarioSelect->close; }

  echo '    
    <div id="siteName"><label for="label_ttlpagina">Prot&oacute;n Grupo Labro</label></div>';  
  
  if ($_SESSION['idusuario'])
  { echo '
    <form id="login">
      <label for="lbl_ttlusuario"><strong>'.$_SESSION['usuario'].'</strong></label>&nbsp;
      <a href="login.php?modo=logout"><strong>Log Out</strong></a>&nbsp;
    </form>'; } 
  else 
  { echo '
    <form id="login" action="login.php" method="post">
      <label for="lbl_ttlusuario">Usuario&nbsp;</label><input name="txt_idusuario" type="text" size="15" maxlength="15" value="'.$_POST['txt_idusuario'].'" />
      <label for="lbl_ttlpassword">Password&nbsp;</label><input name="txt_password" type="password" size="15" maxlength="15" value="'.$_POST['txt_password'].'" />
      <input name="btn_login" type="submit" value="Log In" />
    </form>'; }
  
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
?>
  </body>
</html>