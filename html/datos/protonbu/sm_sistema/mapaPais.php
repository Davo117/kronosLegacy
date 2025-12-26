<!DOCTYPE html>
<html>
  <head>
    <title>Paises en el mapa</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '9MP00';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_SESSION['cdgusuario'] != '')
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 
      

      if (substr($sistModulo_permiso,0,1) == 'r')
      { if ($_POST['check_vertodos'])
        { $vertodo = 'checked'; 
          // Filtrado completo
          $link_mysqli = conectar();
          $pdtoProyectoSelect = $link_mysqli->query("
          SELECT * FROM pdtoproyecto
          ORDER BY sttproyecto DESC,        
            proyecto,
            idproyecto"); 
        } else
        { // Buscar coincidencias
          $link_mysqli = conectar();
          $pdtoProyectoSelect = $link_mysqli->query("
          SELECT * FROM pdtoproyecto
          WHERE sttproyecto = '1'
          ORDER BY proyecto,
            idproyecto"); }
/*

        if ()
        $link_mysqli = conectar(); 
        $mapaPaisSelect = $link_mysqli->query("
          SELECT * FROM mapapais 
          "); //*/

      }    
    }

    echo '
    <form id="form_mapa" name="form_mapa" method="POST" action="mapaPais.php" />
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_pais">Pais</label><br/>
              <input type="text" id="text_pais" name="text_pais" style="width:140px" value="'.$mapaPais_pais.'" title="Nombre del pais" required/></td>
            <td><label for="lbl_idpais">Zip</label><br/>
              <input type="text" id="text_idpais" name="text_idpais" style="width:60px" value="'.$mapaPais_idpais.'" title="Abreviacion del pais" required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th colspan="2" align="right"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>

      <table align="center">
        <thead>
          <tr><th><input type="checkbox" id="check_vertodos" name="check_vertodos" onclick="document.form_mapa.submit()" '.$vertodo.' /></th></tr>
          <tr><th colspan="2"><label for="label_ttlpais">Paises</label></th>
            <th></th></tr>
        </thead>
      </table>
    </form>'; 
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }

  ?>

  </body>
</html>