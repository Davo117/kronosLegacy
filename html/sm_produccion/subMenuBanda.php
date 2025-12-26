<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Submenu bandas de seguridad</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Banda de Seguridad</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '40100';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($_SESSION['cdgusuario']) { ma1n(); }
?>
      <div class="bloque">
        <section class="subboque">
          <article class="submenu">
            <a href="prodTableroBS.php"><img src="../img_sistema/blackboard.png" border="0"/></a>
            <label><b>Tablero de Control</b></label>
          </article>

          <article class="submenu">
            <a href="prodProgramacionBSE.php"><img src="../img_sistema/calendar.png" border="0"/></a>
            <label><b>Programación del Embosado</b></label>
          </article>

          <article class="submenu">
            <a href="prodProgramacionBSR.php"><img src="../img_sistema/calendar.png" border="0"/></a>
            <label><b>Programación del Refilado</b></label>
          </article>

          <article class="submenu">
            <a href="prodEmbosadoBS.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Embosado</b></label>
          </article>

          <article class="submenu">
            <a href="prodRefiladoBS.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Refilado</b></label>
          </article>

          <article class="submenu">
            <a href="prodLaminadoBS.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Laminado</b></label>
          </article> 

          <article class="submenu">
            <a href="prodSliteoBS.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Sliteo</b></label>
          </article>      
        </section>
      </div>
    </div>
  </body>
</html>
