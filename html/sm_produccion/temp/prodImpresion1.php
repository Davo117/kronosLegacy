<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  if (trim($_POST['txt_idempleado']) != '')
  { $prodLoteOpe_idempleado = trim($_POST['txt_idempleado']);

    $link_mysqli = conectar();
    $rechEmpleadoSelect = $link_mysqli->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$prodLoteOpe_idempleado."' 
      OR cdgempleado = '".$prodLoteOpe_idempleado."')
      ORDER BY cdgempleado"); 

    if ($rechEmpleadoSelect->num_rows > 0)
    { if ($rechEmpleadoSelect->num_rows > 1)
      { $msg_alert = 'El Empleado que buscas tiene mas de una coincidencia, favor de informar a Recursos Humanos, se filtrara el primer registro encontrado.'; }
      
      $regRecHEmpleado = $rechEmpleadoSelect->fetch_object();
      $prodImpresion_idempleado = $regRecHEmpleado->idempleado;
      $prodImpresion_empleado = $regRecHEmpleado->empleado;
      $prodImpresion_cdgempleado = $regRecHEmpleado->cdgempleado;

      $txt_maquina = 'autofocus';

      if (trim($_POST['txt_idmaquina']) != '')
      { $prodLoteOpe_idmaquina = trim($_POST['txt_idmaquina']);

        $link_mysqli = conectar();
        $prodMaquinaSelect = $link_mysqli->query("
          SELECT * FROM prodmaquina
          WHERE (idmaquina = '".$prodLoteOpe_idmaquina."' 
          OR cdgmaquina = '".$prodLoteOpe_idmaquina."')
          AND cdgproceso = '10'
          ORDER BY cdgmaquina"); 

        if ($prodMaquinaSelect->num_rows > 0)
        { if ($prodMaquinaSelect->num_rows > 1)
          { $msg_alert = 'La Maquina que buscas tiene mas de una coincidencia, favor de informar a Produccion, se filtrara el primer registro encontrado.'; }
          
          $regProdMaquina = $prodMaquinaSelect->fetch_object();
          $_SESSION['prodLoteOpe_idmaquina'] = $regProdMaquina->idmaquina;
          $_SESSION['prodLoteOpe_maquina'] = $regProdMaquina->maquina;
          $_SESSION['prodLoteOpe_cdgmaquina'] = $regProdMaquina->cdgmaquina;

          $txt_lote = 'autofocus';

          if (trim($_POST['txt_idlote']) != '')
          { $prodLoteOpe_idlote = trim($_POST['txt_idlote']);

            $link_mysqli = conectar();
            $prodLoteSelect = $link_mysqli->query("
              SELECT prodlote.noop, 
                proglote.lote, 
                prodlote.cdgmezcla, 
                prodlote.longitud, 
                prodlote.peso,
                prodlote.cdglote
                FROM proglote,
                prodlote
              WHERE (proglote.cdglote = prodlote.cdglote)
              AND (prodlote.noop = '".$prodLoteOpe_idlote."'
              OR proglote.lote = '".$prodLoteOpe_idlote."'
              OR prodlote.cdglote = '".$prodLoteOpe_idlote."')
              AND prodlote.cdgproceso = '00'
              ORDER BY prodlote.cdglote"); 

            if ($prodLoteSelect->num_rows > 0)
            { if ($prodLoteSelect->num_rows > 1)
              { $msg_alert = 'La Maquina que buscas tiene mas de una coincidencia, favor de informar a Produccion, se filtrara el primer registro encontrado.'; }
              
              $regProdLote = $prodLoteSelect->fetch_object();              
              $prodImpresion_noop = $regProdLote->noop;
              $prodImpresion_lote = $regProdLote->lote;
              $prodImpresion_cdgmezcla = $regProdLote->cdgmezcla;
              $prodImpresion_infolongitud = $regProdLote->longitud;
              $prodImpresion_infopeso = $regProdLote->peso;
              $prodImpresion_fchprograma = $regProdLote->fchprograma;
              $prodImpresion_cdglote = $regProdLote->cdglote;

              $_SESSION['prodLoteOpe_noop'] = $regProdLote->noop;
              $_SESSION['prodLoteOpe_lote'] = $regProdLote->lote;
              $_SESSION['prodLoteOpe_cdgmezcla'] = $regProdLote->cdgmezcla;
              $_SESSION['prodLoteOpe_longitud'] = $regProdLote->longitud;
              $_SESSION['prodLoteOpe_peso'] = $regProdLote->peso;
              $_SESSION['prodLoteOpe_fchprograma'] = $regProdLote->fchprograma;
              $_SESSION['prodLoteOpe_cdglote'] = $regProdLote->cdglote;

              $txt_longitud = 'autofocus';

              if (is_numeric($_POST['txt_longitud']))
              { $prodLoteOpe_longitud = $_POST['txt_longitud'];

                $txt_peso = 'autofocus';

                if (is_numeric($_POST['txt_peso']))
                { $prodLoteOpe_peso = $_POST['txt_peso'];

                  if ($_POST['btn_salvar'])
                  { $fchoperacion = date('Y-m-d');
                    //$fchoperacion = '2012-09-03';

                    //if ($prodLoteOpe_longitud > $_SESSION['prodLoteOpe_longitud'])
                    //{ $prodLoteOpe_longitud = $_SESSION['prodLoteOpe_longitud']; }

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodproclote
                        (cdglote, cdgproceso, longitud, peso, fchmovimiento)
                      VALUES
                        ('".$_SESSION['prodLoteOpe_cdglote']."', '20', '".$prodLoteOpe_longitud."', '".$prodLoteOpe_peso."', NOW())"); 

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO prodloteope
                        (cdglote, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$_SESSION['prodLoteOpe_cdglote']."', '10001', '".$prodImpresion_cdgempleado."', '".$_SESSION['prodLoteOpe_cdgmaquina']."', '".$fchoperacion."', NOW())");                       

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodlote
                      SET cdgproceso = '20',
                        longitud = '".$prodLoteOpe_longitud."', 
                        peso = '".$prodLoteOpe_peso."', 
                        fchmovimiento = NOW()
                      WHERE cdglote = '".$_SESSION['prodLoteOpe_cdglote']."'");
                  }
                }
                else
                { $prodLoteOpe_peso = $_POST['txt_peso'];
                  $txt_peso = 'autofocus'; }
              }
              else
              { $prodLoteOpe_longitud = $_POST['txt_longitud'];
                $txt_longitud = 'autofocus'; }
            } 
            else 
            { $_SESSION['prodLoteOpe_noop'] = '';
              $_SESSION['prodLoteOpe_lote'] = '';
              $_SESSION['prodLoteOpe_cdgmezcla'] = '';
              $_SESSION['prodLoteOpe_longitud'] = '';
              $_SESSION['prodLoteOpe_peso'] = '';
              $_SESSION['prodLoteOpe_fchprograma'] = '';
              $_SESSION['prodLoteOpe_cdglote'] = '';

              $txt_lote = 'autofocus'; }
          }          
        } 
        else 
        { $_SESSION['prodLoteOpe_idmaquina'] = '';
          $_SESSION['prodLoteOpe_maquina'] = '';
          $_SESSION['prodLoteOpe_cdgmaquina'] = ''; 

          $txt_maquina = 'autofocus'; }
      }
    } 
    else 
    { $prodImpresion_idempleado = '';
      $prodImpresion_empleado = '';
      $prodImpresion_cdgempleado = '';

      $txt_empleado = 'autofocus'; }
  } 
  else
  { $txt_empleado = 'autofocus'; }


  echo '
    <form id="frm_prodimpresion" name="frm_prodimpresion" method="POST" action="prodImpresion.php">
      <table align="center">
        <thead>
          <tr><th>Captura de avances en impresi&oacute;n</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="ttl_empleado">Empleado</label><br/>
              <input type="text" id="txt_idempleado" name="txt_idempleado" style="width:120px" maxlenght="24" value="'.$prodImpresion_cdgempleado.'" title="Identificador de empleado" '.$txt_empleado.' required/><br/>
              <label for="info_empleado"><strong>Nombre </strong>'.$prodImpresion_empleado.'</label><br/>
              <label for="info_empleado"><strong>ID </strong>'.$prodImpresion_idempleado.'</label></td></tr>
          <tr><td><label for="ttl_maquina">Maquina</label><br/>
              <input type="text" id="txt_idmaquina" name="txt_idmaquina" style="width:120px" maxlenght="24" value="'.$_SESSION['prodLoteOpe_cdgmaquina'].'" title="Identificador de maquina" '.$txt_maquina.' required/><br/>
              <label for="info_maquina"><strong>Maquina </strong>'.$_SESSION['prodLoteOpe_maquina'].'</label><br/>
              <label for="info_maquina"><strong>ID </strong>'.$_SESSION['prodLoteOpe_idmaquina'].'</label></td></tr>
          <tr><td><label for="ttl_lote">Lote/Bobina</label><br/>
              <input type="text" id="txt_idlote" name="txt_idlote" style="width:120px" maxlenght="24" value="'.$_SESSION['prodLoteOpe_cdglote'].'" title="Identificador de lote/bobina" '.$txt_lote.' required/><br/>
              <label for="info_lote">
                <strong>Bobina </strong>'.$_SESSION['prodLoteOpe_lote'].'<br/>
                <strong>O.P. </strong>'.$_SESSION['prodLoteOpe_noop'].'</label></td></tr>
          <tr><td>
              <table align="center">
                <tr align="center"><th colspan="2"><label for="ttl_informacion">Informaci&oacute;n</label></th></tr>
                <tr align="center"><td><label for="ttl_longitud">Longitud</label><br/>
                    <label for="info_longitud"><strong>'.$_SESSION['prodLoteOpe_longitud'].'</strong> metros</label></td>
                  <td><label for="ttl_peso">Peso</label><br/>
                    <label for="info_peso"><strong>'.$_SESSION['prodLoteOpe_peso'].'</strong> kilogramos</label></td></tr>
              </table>
            </td></tr>
          <tr><td>
              <table align="center">
                <tr align="center"><th colspan="2"><label for="ttl_actualizacion">Actualizaci&oacute;n</label></th></tr>
                <tr align="center"><td><label for="ttl_metros">Metros</label><br/>
                    <input type="text" id="txt_longitud" name="txt_longitud" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodLoteOpe_longitud.'" title="Nueva longitud de lote/bobina" '.$txt_longitud.'/></td>
                  <td><label for="ttl_kilogramos">Kilogramos</label><br/>
                    <input type="text" id="txt_peso" name="txt_peso" style="width:80px; text-align:right;" maxlenght="16" value="'.$prodLoteOpe_peso.'" title="Nuevo peso de lote/bobina" '.$txt_peso.'/></td></tr>
              </table>
            </td></tr>            
        </tbody>
        <tfoot>';

    if ($_POST['btn_buscar'])
    { echo '
          <tr><td align="right"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></td></tr>'; }
    else
    { echo '
          <tr><td align="right"><input type="submit" id="btn_buscar" name="btn_buscar" value="Buscar" /></td></tr>'; }    
          
    echo '
        </tfoot>
      </table><br/>
    </form>';

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>
</html>