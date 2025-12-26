<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="/css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '40100';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($_SESSION['cdgusuario'])
  { ma1n(); }
?>

    </div>
  </body>
</html>