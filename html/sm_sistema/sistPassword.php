<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Cambio de Password</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>    
    <div id="contenedor">
<?php

  include '../datos/mysql.php'; 
  $link = conectar();

  m3nu_sistema();

  $sistModulo_cdgmodulo = '90040';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="sistPassword.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

	  if ($_POST['bttnSalvar']) 
    { $sistUsuarioSelectPW = $link->query("
        SELECT * FROM sistusuario
        WHERE idusuario = '".$_SESSION['idusuario']."' AND 
              wordpass = MD5('".$_POST['textPassword']."')");

      if ($sistUsuarioSelectPW->num_rows > 0) 
      { if (trim($_POST['textPasswordNew']) != '') 
        { if (strlen(trim($_POST['textPasswordNew'])) >= 8 AND strlen(trim($_POST['textPasswordNew'])) <= 16) 
          { if ($_POST['textPasswordNew'] == $_POST['textPasswordCnf']) 
            { $rechEmpleadoUpdate = $link->query("
                UPDATE sistusuario
                   SET wordpass = MD5('".$_POST['textPasswordNew']."')
                 WHERE idusuario = '".$_SESSION['idusuario']."' AND 
                       wordpass = MD5('".$_POST['textPassword']."')");

              if ($link->affected_rows > 0) 
              { $msg_alert = 'El password ha sido actualizado.'; 
              } else 
              { $msg_alert = 'El password no sufrio cambios.'; }
            } else 
            { $msg_alert = 'La confirmación de password es incorrecta.'; }
          } else 
          { $msg_alert = 'La longitud del nuevo password no es el recomendado (8 a 16).'; }
        } else 
        { $msg_alert = 'El nuevo password no puede ser nulo.'; }
      } else 
      { $msg_alert = 'Password incorrecto.'; 
        
        session_unset();
        session_destroy(); }
	  } //*/

	  echo '
      <form id="formSistPassword" name="formSistPassword" method="POST" action="sistPassword.php">
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_nombre">Actualización de password</label>
          </article>      
          
          <section class="subbloque">
            <article>
              <label>Password</label><br/>
              <input type="password" id="textPassword" name="textPassword" value="" required/>            
            </article>

            <article>
              <label>Nuevo Password</label><br/>
              <input type="password" id="textPasswordNew" name="textPasswordNew" value="" required/>            
            </article>

            <article>
              <label>Confirmación</label><br/>
              <input type="password" id="textPasswordCnf" name="textPasswordCnf" value="" required/>            
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>           
          </section>            
        </div>
      </form>';

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }  
?>
    </div>
  </body>
</html>
