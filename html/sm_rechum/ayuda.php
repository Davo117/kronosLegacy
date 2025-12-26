<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';

  m3nu_r3chum();

  $sistModulo_cdgmodulo = '10000';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { ma1n(); }
    else 
    { echo '
      <div id="loginform">
        <form id="login" action="/sm_rechum/ayuda.php" method="post">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
      exit; }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { echo '
    <h2><a name="Empleados">Empleados</a></h2>
    <p>En este módulo se administra al personal que tendrá interacción con el sistema recolectando los siguientes datos:</p>
    
    <ul>
      <li>Número de empleado</li>
      <li>Nombre</li>
      <li>Teléfono</li>
    </ul>

    <p>En este catálogo se encuentran todos los empleados que registraran la realización de sus operaciones y los que necesiten ser contactados en caso necesario ya que también funciona como directorio telefónico. También deben registrarse aquí los empleados que se desea cuenten con permisos especiales para después vincularlos a un usuario en el módulo de usuarios.</p>'; }

  } else
  { echo '
    <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
  </body>
</html>