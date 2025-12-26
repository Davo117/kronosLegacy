<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '50030';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 

    if ($_SESSION['cdgusuario'] != '')
    { if ($_POST['btn_buscar'])
      { if (trim($_POST['txt_idlote']) != '')
        { $progLote_idlote = trim($_POST['txt_idlote']);

          $link_mysqli = conectar();
          $progLoteSelect = $link_mysqli->query("
            SELECT * FROM proglote
            WHERE (lote = '".$progLote_idlote."' OR cdglote = '".$progLote_idlote."') AND
              sttlote = '1'
            ORDER BY cdglote"); 

          if ($progLoteSelect->num_rows > 0)
          { if ($progLoteSelect->num_rows > 1)
            { $msg_alert = 'La Bobina Jumbo que buscas tiene mas de una coincidencia, favor de informar al proveedor se filtrara el primer registro encontrado.'; }
            
            $regProgLote = $progLoteSelect->fetch_object();
            $progLote_lote = $regProgLote->lote;
            $progLote_infolongitud = $regProgLote->longitud;
            $progLote_infoamplitud = $regProgLote->amplitud;
            $progLote_amplitud = $regProgLote->amplitud;
            $progLote_infoespesor = $regProgLote->espesor;
            $progLote_infoencogimiento = $regProgLote->encogimiento;
            $progLote_infopeso = $regProgLote->peso;
            $progLote_peso = $regProgLote->peso;
            $progLote_cdglote = $regProgLote->cdglote;

            $txt_lote = 'autofocus';
          } 
          else 
          { $txt_lote = 'autofocus'; }
        }
      }

      if ($_POST['btn_salvar'])
      { if (trim($_POST['txt_idlote']) != '')
        { $progLote_idlote = trim($_POST['txt_idlote']);

          $link_mysqli = conectar();
          $progLoteSelect = $link_mysqli->query("
            SELECT * FROM proglote
            WHERE (cdglote = '".$progLote_idlote."'
            OR lote = '".$progLote_idlote."')
            AND sttlote = '1'
            ORDER BY cdglote"); 

          if ($progLoteSelect->num_rows > 0)
          { if ($progLoteSelect->num_rows > 1)
            { $msg_alert = 'La Bobina Jumbo que buscas tiene mas de una coincidencia, favor de informar al proveedor se filtrara el primer registro encontrado.'; }
            
            $regProgLote = $progLoteSelect->fetch_object();

            $progLote_lote = $regProgLote->lote;            
            $progLote_longitud = $regProgLote->longitud;            
            $progLote_amplitud = $regProgLote->amplitud;            
            $progLote_espesor = $regProgLote->espesor;
            $progLote_encogimiento = $regProgLote->encogimiento;
            $progLote_peso = $regProgLote->peso;            
            $progLote_cdglote = $regProgLote->cdglote;

            $progLote_infolongitud = $regProgLote->longitud;
            $progLote_infoamplitud = $regProgLote->amplitud;
            $progLote_infoespesor = $regProgLote->espesor;
            $progLote_infoencogimiento = $regProgLote->encogimiento;
            $progLote_infopeso = $regProgLote->peso;            

            $txt_amplitud = 'autofocus';

            if (is_numeric($_POST['txt_amplitud']))
            { $progLote_amplitud = $_POST['txt_amplitud'];

              $txt_peso = 'autofocus';

              if (is_numeric($_POST['txt_peso']))
              { $progLote_peso = $_POST['txt_peso']; 

                $fchoperacion = date('Y-m-d');                

                $link_mysqli = conectar();
                $link_mysqli->query("
                  INSERT INTO prodloteope
                    (cdglote, cdgoperacion, cdgempleado, cdgmaquina, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$progLote_cdglote."', '00090', '".$_SESSION['cdgusuario']."', '00000', '".$fchoperacion."', NOW())");

                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert .= 'Operacion registrada. \n'; 

                  $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO prodloteproc
                      (cdglote, cdgproceso, longitud, amplitud, peso, fchmovimiento)
                    VALUES
                      ('".$progLote_cdglote."', '09', '".$progLote_infolongitud."', '".$progLote_infoamplitud."', '".$progLote_infopeso."', NOW())");                 

                  if ($link_mysqli->affected_rows > 0) 
                  { $msg_alert .= 'Proceso registrado. \n'; 

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE proglote
                      SET amplitud = '".$progLote_amplitud."',
                        peso = '".$progLote_peso."',
                        fchmovimiento = NOW(),
                        sttlote = '7'
                      WHERE cdglote = '".$progLote_cdglote."'");

                    if ($link_mysqli->affected_rows > 0) 
                    { $msg_alert .= 'Bobina afectada.'; }
                  }
                } else
                { $msg_alert = 'INSERT'.$_SESSION['cdgusuario']; }

                $progLote_lote = '';
                $progLote_infolongitud = '';
                $progLote_infoamplitud = '';
                $progLote_amplitud = '';
                $progLote_infoespesor = '';
                $progLote_infoencogimiento = '';
                $progLote_infopeso = '';
                $progLote_peso = '';
                $progLote_cdglote = '';  
                
                $txt_lote = 'autofocus';
                $txt_amplitud = ''; 
                $txt_peso = '';             
              }
              else
              { $progLote_peso = $_POST['txt_peso'];
                $txt_peso = 'autofocus'; 
                $msg_alert = 'Something is bad peso.'; }
            }
            else
            { $txt_amplitud = 'autofocus'; 
              $msg_alert = 'Something is bad amplitud.';}
          } 
          else
          { $txt_lote = 'autofocus';
            $msg_alert = 'Something is bad lote.'; }
        }
      } //Salvar
    } 

    echo '
    <form id="frm_progliberacion" name="frm_progliberacion" method="POST" action="progLiberacion.php">
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><label for="ttl_empleado"><strong>ID </strong>'.$_SESSION['idusuario'].'</label><br/>              
              <label for="info_empleado"><strong>Nombre </strong>'.$_SESSION['usuario'].'</label>              
          <tr><td ><label for="ttl_lote">Lote/Bobina PVC</label><br/>
              <input type="text" id="txt_idlote" name="txt_idlote" style="width:200px" maxlenght="24" value="'.$progLote_lote.'" title="Identificador de lote/bobina" '.$txt_lote.' required/></td>
            <td><label for="ttl_lote"><strong>ID Bobina PVC</strong><br/>'.$progLote_cdglote.'</label></td></tr>
          <tr><td>
              <table align="center">
                <tr align="center"><th colspan="3"><label for="ttl_informacion">Informaci&oacute;n</label></th></tr>
                <tr><td><strong>Longitud</strong></td>
                  <td align="right">'.$progLote_infolongitud.'</td>
                  <td><strong>Metros</strong></td></tr>
                <tr><td><strong>Amplitud</strong></td>
                  <td align="right">'.$progLote_infoamplitud.'</td>
                  <td><strong>Milimetros</strong></td></tr>
                <tr><td><strong>Espesor</strong></td>
                  <td align="right">'.$progLote_infoespesor.'</td>
                  <td><strong>Micras</strong></td></tr>
                <tr><td><strong>Encogimiento </strong></td>
                  <td align="right">'.$progLote_infoencogimiento.'</td>
                  <td><strong>%</strong></td></tr>
                <tr><td><strong>Peso</strong></td>
                  <td align="right">'.$progLote_infopeso.'</td>
                  <td><strong>Kilogramos</strong></td></tr>
              </table>
            </td>
            <td>
              <table align="center">
                <tr align="center"><th colspan="2"><label for="ttl_actualizacion">Actualizaci&oacute;n</label></th></tr>
                <tr align="center"><td><label for="ttl_milimetros">Amplitud</label><br/>
                    <input type="text" id="txt_amplitud" name="txt_amplitud" style="width:100px; text-align:right;" maxlenght="16" value="'.$progLote_amplitud.'" title="Nueva amplitud de lote/bobina" '.$txt_amplitud.'/></td>
                  <td><label for="ttl_kilogramos">Kilogramos</label><br/>
                    <input type="text" id="txt_peso" name="txt_peso" style="width:100px; text-align:right;" maxlenght="16" value="'.$progLote_peso.'" title="Nuevo peso de lote/bobina" '.$txt_peso.'/></td></tr>
              </table>
            </td></tr>            
        </tbody>
        <tfoot>';

    if ($_POST['btn_buscar'])
    { echo '
          <tr><td colspan="2" align="right"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></td></tr>'; }
    else
    { echo '
          <tr><td colspan="2" align="right"><input type="submit" id="btn_buscar" name="btn_buscar" value="Buscar" /></td></tr>'; }    
          
    echo '
        </tfoot>
      </table><br/>
    </form>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>