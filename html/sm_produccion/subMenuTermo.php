<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Proceso de Sello de seguridad</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Termoencogible</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '40100';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($_SESSION['cdgusuario']) { ma1n(); }

      /*
          <article class="submenu">
            <a href="prodCalendario.php"><img src="/img_sistema/calculator.png" border="0"/></a>
            <label><b>Productividad</b></label>
          </article>

          <article class="submenu">
            <a href="prodCalEntrega.php"><img src="/img_sistema/delivery.png" border="0"/></a>
            <label><b>Entregas</b></label>
          </article>
          <article class="submenu">
            <a href="prodTableroFilter.php"><img src="/img_sistema/blackboard.png" border="0"/></a>
            <label><b>Tablero de control</b></label>
          </article>
      */  
?>
      <div class="bloque">
        <section class="subboque">
          <article class="submenu">
            <a href="prodTablero.php"><img src="../img_sistema/blackboard.png" border="0"/></a>
            <label><b>Tablero de Control</b></label>
          </article>

          <article class="submenu">
            <a href="prodProgramacion.php"><img src="../img_sistema/calendar.png" border="0"/></a>
            <label><b>Programación de la Impresión</b></label>
          </article>
          
          <article class="submenu">
            <a href="prodImpresion.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro de la Impresión</b></label>
          </article>
          
          <article class="submenu">
            <a href="prodRefilado.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Refilado</b></label>
          </article>
          
          <article class="submenu">
            <a href="prodFusion.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro de la Fusión</b></label>
          </article>
          
          <article class="submenu">
            <a href="prodRevision.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro de la Revisión</b></label>
          </article>
          
          <article class="submenu">
            <a href="prodCorte.php"><img src="../img_sistema/gear.png" border="0"/></a>
            <label><b>Registro del Corte</b></label>
          </article>

          <article class="submenu">
            <a href="prodEntregas.php"><img src="../img_sistema/calendar.png" border="0"/></a>
            <label><b>Calendario de Entregas</b></label>
          </article>        

          <article class="submenu">
            <a href="prodreporteador.php"><img src="../img_sistema/recycle_bin.png" border="0"/></a>
            <label><b>Merma</b></label>
          </article>

          <article class="submenu">
            <a href="../sm_inspeccion/inspBuscador.php"><img src="../img_sistema/search.png" border="0"/></a>
            <label><b>Buscador</b></label>
          </article>         
        </section>
      </div>
    </div>
  </body>
</html>
