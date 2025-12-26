<!DOCTYPE html>
<html>
  <head>
    <title>Actualización de empaques</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '81010';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $alptEmpaque_cdgempleado = trim($_POST['text_empleado']);    
    $alptEmpaque_cdgempaque = trim($_POST['text_empaque']);
    
    if ($_GET['cdgempleado'])
    { $alptEmpaque_cdgempleado = $_GET['cdgempleado']; }

    //Buscar Empleado
    $link_mysql = conectar();
    $rechEmpleadoSelect = $link_mysql->query("
      SELECT * FROM rechempleado
      WHERE (idempleado = '".$alptEmpaque_cdgempleado."' OR cdgempleado = '".$alptEmpaque_cdgempleado."') AND
        sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { // Filtra al empleado
      $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $alptEmpaque_idempleado = $regRechEmpleado->idempleado;
      $alptEmpaque_empleado = $regRechEmpleado->empleado;
      $alptEmpaque_cdgempleado = $regRechEmpleado->cdgempleado;

      $rechEmpleadoSelect->close;

      //Buscar empaque
      $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT * FROM alptempaque 
        WHERE cdgempaque = '".$alptEmpaque_cdgempaque."'");

      if ($alptEmpaqueSelect->num_rows > 0)
      { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

        $alptEmpaque_noempaque = $regAlptEmpaque->noempaque;
        $alptEmpaque_cdgproducto = $regAlptEmpaque->cdgproducto;

        $alptEmpaqueSelect->close;

        $link_mysqli = conectar();
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgimpresion = '".$alptEmpaque_cdgproducto."'");

        if ($pdtoImpresionSelect->num_rows > 0)
        { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

          $alptEmpaque_impresion = $regPdtoImpresion->impresion;
          
          $pdtoImpresionSelect->close;

          $link_mysqli = conectar();
          $alptEmpaquePSelect = $link_mysqli->query("
            SELECT COUNT(prodpaquete.cdgpaquete) AS paquetes,              
              SUM(prodpaquete.cantidad) AS millares              
            FROM alptempaquep, prodpaquete
            WHERE prodpaquete.cdgpaquete = alptempaquep.cdgpaquete AND 
              alptempaquep.cdgempaque = '".$alptEmpaque_cdgempaque."'
            GROUP BY alptempaquep.cdgempaque");

          if ($alptEmpaquePSelect->num_rows > 0)
          { $regAlptEmpaqueP = $alptEmpaquePSelect->fetch_object();
          
            $alptEmpaque_paquetes = $regAlptEmpaqueP->paquetes;
            $alptEmpaque_millares = $regAlptEmpaqueP->millares;
			  
			  /*
            $id_paquete = 1;
            while ($regAlptEmpaqueP = $alptEmpaquePSelect->fetch_object()) 
            { $alptEmpaque_cdgpaquete[$id_paquete] = $regAlptEmpaqueP->nocontrol; 
              $alptEmpaque_noop[$id_paquete] = $regAlptEmpaqueP->noop;              
              $alptEmpaque_amplitud[$id_paquete] = $regAlptEmpaqueP->amplitud;              
              $alptEmpaque_cdgpaquete[$id_paquete] = $regAlptEmpaqueP->cdgpaquete;

              $alptEmpaque_millares = .5;

              $id_paquete++; } 

            $num_paquetes = $alptEmpaquePSelect->num_rows; //*/
            $alptEmpaquePSelect->close;

            if ($_POST['button_salvar'])
            { $fchoperacion = date('Y-m-d');  
              $alptEmpaque_pesobruto = trim($_POST['text_pesobruto']);

              if (is_numeric($alptEmpaque_pesobruto))
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE alptempaque
                  SET peso = '".$alptEmpaque_pesobruto."'
                  WHERE cdgempaque = '".$alptEmpaque_cdgempaque."' AND
                    cdgembarque = ''"); 

                if ($link_mysqli->affected_rows > 0)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO alptempaqueope
                      (cdgempaque, cdgoperacion, cdgempleado, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$alptEmpaque_cdgempaque."', '80001', '".$alptEmpaque_cdgempleado."', ".$fchoperacion."', NOW())");

                  $msg_info = "El peso bruto fue actualizado con exito. (".$alptEmpaque_noempaque.")";

	                $alptEmpaque_noempaque = '';
                  $text_empaque = "autofocus";
                } else
                { $msg_info = "El peso bruto del empaque ya fue afectado anteriormente. <br/>
                         (Posiblemente este paquete ya sea parte de un embarque)"; 

                  $text_empaque = "autofocus"; }
                
              } else
              { $msg_info = "El peso bruto del empaque no es valido. <br/>
                       (Verificar captura ".$alptEmpaque_pesobruto.")"; 

                $text_pesobruto = "autofocus"; }
            } else
            { $text_pesobruto = "autofocus"; }

            $text_pesobruto = "autofocus";

          } else
          { $msg_info = "El contenido del empaque no pudo ser encontrado. <br/>
                       (ERROR grave, perdida de informaci&oacute;n)"; }
        } else
        { $msg_info = "El producto contenido en el empaque no pudo ser encontrado. <br/>
                       (ERROR grave, perdida de informaci&oacute;n)"; }
      } else
      { $msg_info = "El c&oacute;digo de paquete no fue encontrado."; }
    }

    echo '
    <form id="form_empaque" name="form_empaque" method="POST" action="alptEmpaqueC.php"/>
      <table align="center">
        <thead>
          <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>';

    if ($alptEmpaque_noempaque != '')
    { echo '
        <tbody>
          <input type="hidden" id="text_empleado" name="text_empleado" value="'.$alptEmpaque_cdgempleado.'" />      
          <input type="hidden" id="text_empaque" name="text_empaque" value="'.$alptEmpaque_cdgempaque.'" />
          <tr><th rowspan="3">Empaque<br/>
              <h1>C'.$alptEmpaque_noempaque.'</h1></th>
              <td><label for="lbl_ttlempleado">Empleado</label><br/>
              <label for="lbl_empleado">[<strong>'.$alptEmpaque_idempleado.'</strong>] <strong>'.$alptEmpaque_empleado.'</strong></label></td></tr>
          <tr><td><label for="lbl_ttlimpresion">Producto</label><br/>
              <label for="lbl_producto"><strong>'.$alptEmpaque_impresion.'</strong></label></td></tr>
          <tr><td><label for="lbl_ttlpesobruto">Peso Bruto</label><br/>
              <input type="text" style="width:120px" id="text_pesobruto" name="text_pesobruto" value="'.$alptEmpaque_pesobruto.'" '.$text_pesobruto.' required/></td></tr>
          <tr><td colspan="2">
              <table align="center">
                <thead>
                  <tr><td colspan="6" align="center"><strong>Contenido del empaque</strong></td></tr>
                  <tr><th>No Control</th>
                    <th>NoOp</th>                    
                    <th>Ancho Plano</th>
                    <th>Cantidad</th></tr>
                </thead>
                <tbody>';
              
              //for ($id_paquete = 1; $id_paquete <= $num_paquetes; $id_paquete++)
              //{ 
				  echo '
                  <tr align="right"><td align="center"><strong>'.$alptEmpaque_paquetes.'</strong></td>
                    <td></td>                    
                    <td></td>
                    <td></td></tr>'; //}
                
              echo '
                  <tr align="right"><td></td>                    
                    <td></td>
                    <td></td>
                    <th><strong>'.number_format($alptEmpaque_millares,3).'</strong> mlls</th></tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right" colspan="2"><input type="submit" id="button_salvar" name="button_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table>
    </form>'; 
    } else
    { echo '
        <tbody>
          <tr><td><label for="lbl_ttlempleado">Empleado</label><br/>
              <input type="text" style="width:120px" id="text_empleado" name="text_empleado" value="'.$alptEmpaque_cdgempleado.'" '.$text_empleado.' required/></td></tr>
          <tr><td><label for="lbl_ttlempaque">Empaque</label><br/>
              <input type="text" style="width:120px" id="text_empaque" name="text_empaque" value="'.$alptEmpaque_cdgempaque.'" '.$text_empaque.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><th align="right"><input type="submit" id="button_buscar" name="button_buscar" value="Buscar" /></th></tr>
        </tfoot>
      </table>
    </form>'; }

    echo '<br/>
    <div align="center"><strong>'.$msg_info.'</strong></div>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
