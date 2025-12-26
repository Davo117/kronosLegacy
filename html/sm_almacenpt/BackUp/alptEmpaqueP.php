<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '80020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($_SESSION['cdgusuario'] != '')
    { if ($_GET['cdgpaquete'])
      { $empaque_codigo = $_SESSION['empaque'];
        $num_paquetes = count($empaque_codigo);

        for ($idPaquete=1; $idPaquete<=$num_paquetes; $idPaquete++)
        { if ($empaque_codigo[$idPaquete] != $_GET['cdgpaquete'])
          { $newidPaquete++;
            $newempaque_codigo[$newidPaquete] = $empaque_codigo[$idPaquete]; }
        }

        $_SESSION['empaque'] = $newempaque_codigo; 
      }

      if ($_POST['submit_salvar'])
      { if ($_POST['checkbox_empacar'] OR ($_POST['text_cdgpaquete'] == 'close'))
        { // Crea documento, bloquea los codigos y agregalos 
          $empaque_newempaque = true;

          $empaque_subcdgempaque = date('ymd');
          for ($id_indice = 1; $id_indice <= 9999; $id_indice++) 
          { $empaque_cdgempaque = $empaque_subcdgempaque.str_pad($id_indice,4,'0',STR_PAD_LEFT);

            $link_mysqli = conectar();
            $prodDocumentoSelect = $link_mysqli->query("
              SELECT * FROM alptempaque
              WHERE cdgempaque = '".$empaque_cdgempaque."'");

            if ($prodDocumentoSelect->num_rows == 0)
            { $_SESSION['cdgempaque'] = $empaque_cdgempaque;

              $link_mysqli = conectar();
              $alptEmpaqueSelect = $link_mysqli->query("
                SELECT MAX(noempaque) AS noempaque,
                     COUNT(noempaque) AS noempaques 
                  FROM alptempaque
                 WHERE cdgproducto = '".$_SESSION['cdgproducto']."' AND
                       tpoempaque = 'C'");

              if ($alptEmpaqueSelect->num_rows > 0)
              { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

                if ($regAlptEmpaque->noempaque > 0)
                { $empaque_noempaque = $regAlptEmpaque->noempaque+1; }
                else
                { $empaque_noempaque = 1; }

                $msg_window .= "<br>Ultimo ".$regAlptEmpaque->noempaque."<br>Contador ".$regAlptEmpaque->noempaques."<br/> El NoEmpaque sera C".$empaque_noempaque; 
              }
              else
              { $msg_window .= "<br/> El NoEmpaque no ha sido iniciado."; 
                $empaque_noempaque = 1; } 

              $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO alptempaque
                  (cdgempaque, cdgproducto, noempaque, tpoempaque, cdgempleado, fchempaque)
                VALUES
                  ('".$empaque_cdgempaque."', '".$_SESSION['cdgproducto']."', '".$empaque_noempaque."', 'C', '".$_SESSION['cdgusuario']."', NOW())");

              if ($link_mysqli->affected_rows > 0)
              { $empaque_codigo = $_SESSION['empaque'];
                $empaque_cdgcantidad = $_SESSION['cantidad'];
                $num_paquetes = count($empaque_codigo);

                if ($num_paquetes > 0)
                { for ($idPaquete=1; $idPaquete<=$num_paquetes; $idPaquete++)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodpaquete
                      SET cdgempaque = '".$empaque_cdgempaque."'
                      WHERE cdgpaquete = '".$empaque_codigo[$idPaquete]."' AND
                        cdgempaque = ''");

                    if ($link_mysqli->affected_rows == 0)
                    { $empaque_newempaque = false; 
                      $msg_alert .= "\n El Paquete ".$empaque_codigo[$idPaquete]." NO fue agregado."; } 
                    else
                    { $msg_alert .= "\n El Paquete ".$empaque_codigo[$idPaquete]." FUE agregado."; }
                  } 

                  if ($empaque_newempaque == true)
                  { for ($idPaquete=1; $idPaquete<=$num_paquetes; $idPaquete++)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO alptempaquep
                          (cdgempaque, cdgproducto, cdgpaquete)
                        VALUES
                          ('".$empaque_cdgempaque."', '".$_SESSION['cdgproducto']."', '".$empaque_codigo[$idPaquete]."')");

                      if ($link_mysqli->affected_rows == 0)
                      { $empaque_newempaque = false; 
                        $msg_alert .= "\n El Paquete ".$empaque_codigo[$idPaquete]." NO fue agregado."; } 
                      else
                      { $msg_alert .= "\n El Paquete ".$empaque_codigo[$idPaquete]." FUE agregado."; }    
                    } 
                    
                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodpaquete 
                      SET sttpaquete = '9',
                        fchmovimiento = NOW()
                      WHERE cdgempaque = '".$empaque_cdgempaque."' AND
                        sttpaquete = '1'"); 

                      $msg_window .= "<br/> Empaque ".$empaque_cdgempaque." generado con '".$link_mysqli->affected_rows."' paquetes."; 
                  }  
                  else
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodpaquete
                      SET cdgempaque = ''
                      WHERE cdgempaque = '".$empaque_cdgempaque."'"); 

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      DELETE FROM alptempaque                    
                      WHERE cdgempaque = '".$empaque_cdgempaque."' AND 
                        sttempaque = '1'"); 

                    $msg_window .= "<br/> El empaque NO pudo ser generado, por que alguno de los rollos ya es parte de otro empaque."; 
                  }
                } 
                else
                { $msg_window .= "\<br/> El empaque NO pudo ser generado, no contiene paquetes."; }           
              } 
              else
              { $msg_window .= "<br/> El empaque NO pudo ser generado, intentalo nuevamente."; } 

              $id_indice = 10000; 
              }
          } 

          unset($_SESSION['cdgproducto']);
          unset($_SESSION['empaque']);
          unset($_SESSION['cantidad']);
        }
        else
        { $empaque_cdgpaquete = $_POST['text_cdgpaquete'];
          $empaque_codigo = $_SESSION['empaque'];
          $num_paquetes = count($empaque_codigo);

          $link_mysqli = conectar();
          $prodPaqueteSelect = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
              prodrollo.amplitud,
              prodpaquete.cdgproducto,
              prodpaquete.cdgpaquete,
              prodpaquete.cantidad,
              prodpaquete.sttpaquete,
              pdtoimpresion.corte,
              pdtoimpresion.cdgimpresion
            FROM prodlote,
              prodbobina,
              prodrollo,
              prodpaquete,
              pdtoimpresion
            WHERE prodlote.cdglote = prodbobina.cdglote AND
              prodbobina.cdgbobina = prodrollo.cdgbobina AND
              prodrollo.cdgrollo = prodpaquete.cdgrollo AND
              prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
              prodpaquete.cdgpaquete = '".$empaque_cdgpaquete."' AND 
              prodpaquete.cdgempaque = ''");

          if ($prodPaqueteSelect->num_rows > 0)
          { $regProdPaquete = $prodPaqueteSelect->fetch_object();
            $empaque_noop = $regProdPaquete->noop;
            $empaque_cantidad = $regProdPaquete->cantidad;
            $empaque_cdgproducto = $regProdPaquete->cdgproducto;
            $empaque_sttpaquete = $regProdPaquete->sttpaquete;
            $empaque_cdgimpresion = $regProdPaquete->cdgimpresion;

            if (trim($empaque_cdgproducto) != '')
            { $empaque_cdgimpresion = $empaque_cdgproducto; }

            if ($num_paquetes == 0)
            { $_SESSION['cdgproducto'] = $empaque_cdgimpresion; }

            if ($_SESSION['cdgproducto'] == $empaque_cdgimpresion)
            { if ($empaque_sttpaquete == 1)
              { $msg_window = 'Loading... '.$empaque_cdgimpresion;

                $empaque_update = true;
                for ($idPaquete=1; $idPaquete<=$num_paquetes; $idPaquete++)
                { if ($empaque_codigo[$idPaquete] == $empaque_cdgpaquete)
                  { $empaque_update = false; }
                }

                if ($empaque_update == true)
                { $idPaquete = $num_paquetes+1;

                  $empaque_codigo[$idPaquete] = $empaque_cdgpaquete;
                  $empaque_cdgcantidad[$idPaquete] = $empaque_cantidad;

                  $_SESSION['empaque'] = $empaque_codigo;
                  $_SESSION['cantidad'] = $empaque_cdgcantidad;
                  $msg_window = 'Paquete '.$empaque_noop.' agregado exitosamente.';

                  $_SESSION['empaque'] = $empaque_codigo;
                } 
                else
                { $msg_window = 'Paquete '.$empaque_noop.' ya se encuentra agregado.'; }
              } 
              else
              { if ($empaque_sttpaquete == 9)
                { $msg_window = 'Paquete '.$empaque_noop.' EMPACADO.'; }
              }
            } 
            else
            { $msg_window = 'Paquete incompatible con el contendido actual.'; }
          } 
          else 
          { $msg_alert = 'Paquete no encontrado, es posible que ya sea parte de algun paquete.'; }
        }
      }

      echo '
    <form id="form_empaque" name="form_empaque" method="POST" action="alptEmpaqueP.php">
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_idempleado"><strong>ID:</strong> '.$_SESSION['idusuario'].'</label><br/>
              <label for="label_empleado"><strong>Empleado:</strong> '.$_SESSION['usuario'].'</label><br/>
              <label for="label_fchempaque"><strong>Fecha:</strong> '.date('Y-m-d').'</label></td></tr>
          <tr><td><label for="label_ttlrollo">Paquete</label><br/>
              <input type="text" style="width:120px" id="text_cdgpaquete" name="text_cdgpaquete" value="'.$prodPaquete_cdgpaquete.'" autofocus required/><br/>
              <input type="checkbox" id="checkbox_empacar" name="checkbox_empacar" />
              <label for="lbl_ttlempaque">Empaque</label>
              <a href="pdf/alptEmpaqueBCEC.php?cdgempaque='.$_SESSION['cdgempaque'].'" target="_blank">'.$png_barcode.'</a></td></tr>
        </tbody>
        <tfoot>
        <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" />
        </td></tr>
        </tfoot>
      </table><br/>

      <div align="center"><strong>'.$msg_window.'</strong></div><br/>';

      $empaque_codigo = $_SESSION['empaque'];
      $num_paquetes = count($empaque_codigo);

      echo '
      <table align="center">
        <thead>
          <tr><td colspan="4" align="center"><strong>Contenido del Empaque ['.$num_paquetes.'] paquetes</strong></td></tr>
          <tr><th><label for="label_ttlidempaque">Paquete</label></th>
            <th><label for="label_ttlancho">Ancho plano</label></th>            
            <th></th>
            <th></th></tr>
        </thead>
        <tbody>';

      if ($num_paquetes > 0)
      { for ($idPaquete=1; $idPaquete<=$num_paquetes; $idPaquete++)
        { $link_mysqli = conectar();
          $alptEmpaqueRolloSelect = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo,'-',prodpaquete.paquete) AS noop,
              prodrollo.amplitud,
              prodpaquete.cdgpaquete              
            FROM prodlote,
              prodbobina,
              prodrollo,
              prodpaquete,
              pdtoimpresion
            WHERE prodlote.cdglote = prodbobina.cdglote AND
              prodbobina.cdgbobina = prodrollo.cdgbobina AND
              prodrollo.cdgrollo = prodpaquete.cdgrollo AND
              prodpaquete.cdgpaquete = '".$empaque_codigo[$idPaquete]."' AND
              prodlote.cdgproducto = pdtoimpresion.cdgimpresion");

          if ($alptEmpaqueRolloSelect > 0)
          { $regAlptEmpaque = $alptEmpaqueRolloSelect->fetch_object();

            echo '
          <tr align="right">
            <td>'.$empaque_codigo[$idPaquete].'</td>
            <td>'.$regAlptEmpaque->noop.'</td>            
            <td>'.$regAlptEmpaque->amplitud.'</td>            
            <td><a href="alptEmpaqueP.php?cdgpaquete='.$regAlptEmpaque->cdgpaquete.'&proceso=delete">'.$png_recycle_bin.'</a></td></tr>';
          } else
          { echo '
          <tr><td>'.$empaque_codigo[$idPaquete].'</td>
            <td></td>
            <td></td>
            <td></td></tr>'; }
        }
      }
        
      echo '
        </tbody>
        <tfoot>
          <tr><td colspan="4" align="right"><label for="label_piedefiltro">['.$num_paquetes.'] paquetes agregados</label></td></tr>
        </tfoot>
      </table>
    </form>';

      if ($msg_alert != '')
      { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

//      echo '<br/>
//    <div align="center"><strong>'.$msg_window.'</strong></div>';       

    } else
    { echo '
    <br/><div align="center"><h1>Usuario no encontrado o bloqueado.</h1></div>'; }    
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>