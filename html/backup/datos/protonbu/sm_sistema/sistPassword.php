<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '90040';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '') { 
    $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

	  if ($_POST['btn_salvar']) { 
      $link_mysql = conectar();
      $rechEmpleadoSelectWP = $link_mysql->query("
        SELECT * FROM rechempleado
        WHERE idusuario = '".$_SESSION['idusuario']."'
        AND acceso = MD5('".$_POST['txt_password']."')");

      if ($rechEmpleadoSelectWP->num_rows > 0) { 
        if (trim($_POST['txt_passwordnew']) != '') {
          if (strlen(trim($_POST['txt_passwordnew'])) >= 8 AND strlen(trim($_POST['txt_passwordnew'])) <= 16) {
            if ($_POST['txt_passwordnew'] == $_POST['txt_passwordcnf']) {
              $link_mysql = conectar();
              $rechEmpleadoUpdate = $link_mysql->query("
                UPDATE rechempleado
                SET acceso = MD5('".$_POST['txt_passwordnew']."')
                WHERE idusuario = '".$_SESSION['idusuario']."' AND 
                  acceso = MD5('".$_POST['txt_password']."')");

              if ($link_mysql->affected_rows > 0) { 
                $msg_alert = 'El password ha sido actualizado.'; 
              } else { 
                $msg_alert = 'El password no sufrio cambios.'; 
              }
            } else {
              $msg_alert = 'La confirmación de password es incorrecta.'; 
            }
          } else {
            $msg_alert = 'La longitud del nuevo password no es el recomendado (8 a 16).';
          }
        } else {
          $msg_alert = 'El nuevo password no puede ser nulo.'; 
        }
      } else { 
        $msg_alert = 'Password incorrecto.'; 
        
        session_unset();
        session_destroy(); 
      }
	  }

	  echo '
    <form id="frm_sistPassword" name="frm_sistPassword" method="POST" action="sistPassword.php">
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><label for="ttl_idusuario">idUsuario</label><br/>
              <label for="lbl_idusuario"><strong>'.$_SESSION['idusuario'].'</strong></label></td>          
            <td><label for="ttl_usuario">Usuario</label><br/>
              <label for="lbl_usuario"><strong>'.$_SESSION['usuario'].'</strong></label></td></tr>
          <tr>
            <td colspan="2"><label for="ttl_password">Password MD5(pwd)")</label><br/>
              <input type="password" size="20" maxlength="16" id="txt_password" name="txt_password" value="" /></td></tr>
          <tr>
            <td><label for="ttl_passwordnew">Nuevo Password</label><br/>
              <input type="password" size="20" maxlength="16" id="txt_passwordnew" name="txt_passwordnew" value="" /></td>
            <td><label for="ttl_passwordcnf">Confirmar Password</label><br/>
              <input type="password" size="20" maxlength="16" id="txt_passwordcnf" name="txt_passwordcnf" value="" /></td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right"><input type="submit" id="btn_salvar" name="btn_salvar" value="Aplicar cambios" class="boton" /></th></tr>
        </tfoot>
      </table>
    </form>';


    if ($msg_alert != '') { 
      echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; 
    }

  } else { 
    echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; 
  }
?>

  </body>
</html>
