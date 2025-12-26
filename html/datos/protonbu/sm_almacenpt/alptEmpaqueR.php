<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '80010';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);   

    if ($_SESSION['cdgusuario'] != '')
    { if ($_GET['cdgrollo'])
      { $empaque_codigo = $_SESSION['empaque'];
        $num_rollos = count($empaque_codigo);

        for ($id_rollo=1; $id_rollo<=$num_rollos; $id_rollo++)
        { if ($empaque_codigo[$id_rollo] != $_GET['cdgrollo'])
          { $newid_rollo++;
            $newempaque_codigo[$newid_rollo] = $empaque_codigo[$id_rollo]; }
        }

        $_SESSION['empaque'] = $newempaque_codigo; }

      if ($_POST['submit_salvar'])
      { if ($_POST['checkbox_empacar'] OR ($_POST['text_rollo'] == 'close'))
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
                SELECT MAX(noempaque) AS noempaque 
                FROM alptempaque
                WHERE cdgproducto = '".$_SESSION['cdgproducto']."' AND
                  tpoempaque = 'Q'");

              if ($alptEmpaqueSelect->num_rows > 0)
              { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

                if ($regAlptEmpaque->noempaque > 0)
                { $empaque_noempaque = $regAlptEmpaque->noempaque+1; }
                else
                { $empaque_noempaque = 1; }

                $msg_modulo .= "<br/> El NoEmpaque sera Q".$empaque_noempaque; }
              else
              { $msg_modulo .= "<br/> El NoEmpaque no ha sido iniciado."; 
                $empaque_noempaque = 1; } 

              $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO alptempaque
                  (cdgempaque, cdgproducto, noempaque, tpoempaque, cdgempleado, fchempaque)
                VALUES
                  ('".$empaque_cdgempaque."', '".$_SESSION['cdgproducto']."', '".$empaque_noempaque."', 'Q', '".$_SESSION['cdgusuario']."', NOW())");
                  
              if ($link_mysqli->affected_rows > 0)
              { $empaque_codigo = $_SESSION['empaque'];
                $empaque_cantidad = $_SESSION['cantidad'];
                $num_rollos = count($empaque_codigo);

                if ($num_rollos > 0)
                { for ($id_rollo=1; $id_rollo<=$num_rollos; $id_rollo++)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodrollo 
                      SET cdgempaque = '".$empaque_cdgempaque."'
                      WHERE cdgrollo = '".$empaque_codigo[$id_rollo]."' AND
                        cdgempaque = ''");

                    if ($link_mysqli->affected_rows == 0)
                    { $empaque_newempaque = false; 
                      $msg_modulo .= "<br/> El Rollo ".$empaque_codigo[$id_rollo]." NO fue agregado.";
                    } else
                    { $msg_modulo .= "<br/> El Rollo ".$empaque_codigo[$id_rollo]." FUE agregado."; }
                  }

                  if ($empaque_newempaque == true)
                  { for ($id_rollo=1; $id_rollo<=$num_rollos; $id_rollo++)
                    { $link_mysqli = conectar();
                      $alptEmpaqueRSelect = $link_mysqli->query("
                        SELECT MAX(nocontrol) AS nocontrol 
                        FROM alptempaquer
                        WHERE cdgproducto = '".$_SESSION['cdgproducto']."'");

                      if ($alptEmpaqueRSelect->num_rows > 0)
                      { $regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object();

                        if ($regAlptEmpaqueR->nocontrol > 0)
                        { $empaque_nocontrol = $regAlptEmpaqueR->nocontrol+1; }
                        else
                        { $empaque_nocontrol = 1; }
                      } else
                      { $msg_modulo .= "<br/> El NoControl no ha sido iniciado."; 
                        $empaque_nocontrol = 1; } 

                      /*Calcular piezas al momento de empacar, para contabilizar contra las ordenes de compra.*/

                      $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO alptempaquer
                          (cdgempaque, cdgproducto, nocontrol, cantidad, cdgrollo)
                        VALUES
                          ('".$empaque_cdgempaque."', '".$_SESSION['cdgproducto']."', '".$empaque_nocontrol."', '".$empaque_cantidad[$id_rollo]."', '".$empaque_codigo[$id_rollo]."')");

                      if ($link_mysqli->affected_rows == 0)
                      { $empaque_newempaque = false; 
                        $msg_modulo .= "<br/> El Rollo ".$empaque_codigo[$id_rollo]." NO fue insertado en el empaque.";
                      } else
                      { $msg_modulo .= "<br/> El Rollo ".$empaque_codigo[$id_rollo]." FUE insertado en el empaque."; }                    
                    }
                    
                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodrollo 
                      SET sttrollo = '9',
                        fchmovimiento = NOW()
                      WHERE cdgempaque = '".$empaque_cdgempaque."' AND
                       (sttrollo = '7' OR sttrollo = '8')"); 

                      $msg_modulo .= "<br/> Empaque ".$empaque_cdgempaque." de Rollos Generado.";
                  } else
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE prodrollo 
                      SET cdgempaque = ''
                      WHERE cdgempaque = '".$empaque_cdgempaque."'"); 

                    $link_mysqli = conectar();
                    $link_mysqli->query("
                      DELETE FROM alptempaque                    
                      WHERE cdgempaque = '".$empaque_cdgempaque."' AND 
                        sttempaque = '1'"); 

                    $msg_modulo .= "<br/> El empaque NO pudo ser generado, por que alguno de los rollos ya es parte de otro empaque."; 
                  }
                } else
                { $msg_modulo .= "<br/> El empaque NO pudo ser generado, no contiene rollos."; }               
              } else
              { $msg_modulo .= "<br/> El empaque NO pudo ser generado, intentalo nuevamente."; } 
              
              $id_indice = 10000; }
            
            $prodDocumentoSelect->close;          
          }

          unset($_SESSION['cdgproducto']);
          unset($_SESSION['corte']);
          unset($_SESSION['empaque']);
          unset($_SESSION['cantidad']);
        } else
        { $empaque_cdgrollo = $_POST['text_rollo'];
          $empaque_codigo = $_SESSION['empaque'];          
          $empaque_cantidad = $_SESSION['cantidad'];
          $num_rollos = count($empaque_codigo);

          $link_mysqli = conectar();
          $prodRolloSelect = $link_mysqli->query("
            SELECT CONCAT(prodlote.noop,'-',prodbobina.bobina,'-',prodrollo.rollo) AS noop,
              prodrollo.longitud,
              prodrollo.amplitud,
              prodrollo.peso,
              prodrollo.cdgrollo,
              prodrollo.sttrollo,
              pdtoimpresion.corte,
              pdtoimpresion.cdgimpresion
            FROM prodlote,
              prodbobina,
              prodrollo,
              pdtoimpresion
            WHERE prodlote.cdglote = prodbobina.cdglote AND
              prodbobina.cdgbobina = prodrollo.cdgbobina AND
              prodrollo.cdgrollo = '".$empaque_cdgrollo."' AND 
              prodrollo.cdgempaque = '' AND
              prodrollo.cdgproducto = pdtoimpresion.cdgimpresion");

          if ($prodRolloSelect->num_rows > 0)
          { $regProdRollo = $prodRolloSelect->fetch_object();
            $empaque_noop = $regProdRollo->noop;
            $empaque_longitud = $regProdRollo->longitud;
            $empaque_peso = $regProdRollo->peso;
            $empaque_sttrollo = $regProdRollo->sttrollo;
            $empaque_corte = $regProdRollo->corte;
            $empaque_cdgimpresion = $regProdRollo->cdgimpresion;            

            if ($num_rollos == 0)
            { $_SESSION['cdgproducto'] = $empaque_cdgimpresion; } 

            if ($_SESSION['cdgproducto'] == $empaque_cdgimpresion) 
            { if ($empaque_sttrollo == 1)
              { $msg_modulo = 'Rollo NO LIBERADO.'; 
              } else
              { $msg_modulo = 'Loading... '.$empaque_cdgimpresion;

                if ($empaque_sttrollo == 8 OR ($empaque_sttrollo == 7 AND $empaque_peso > 0))
                { $msg_modulo = 'Loading... '.$empaque_noop.' '.$empaque_cdgimpresion;

                  $empaque_update = true;
                  for ($id_rollo=1; $id_rollo<=$num_rollos; $id_rollo++)
                  { if ($empaque_codigo[$id_rollo] == $empaque_cdgrollo)
                    { $empaque_update = false; }
                  }

                  if ($empaque_update == true)
                  { $id_rollo = $num_rollos+1;

                    $empaque_codigo[$id_rollo] = $empaque_cdgrollo;    
                    $empaque_cantidad[$id_rollo] = ($empaque_longitud/$empaque_corte);

                    $_SESSION['empaque'] = $empaque_codigo;
                    $_SESSION['cantidad'] = $empaque_cantidad;
                    $msg_modulo = 'Rollo '.$empaque_noop.' agregado exitosamente.'; 

                    $_SESSION['empaque'] = $empaque_codigo; 
                  } else
                  { $msg_modulo = 'Rollo '.$empaque_noop.' ya se encuentra agregado.'; }
                } else
                { if ($empaque_sttrollo == 9)
                  { $msg_modulo = 'Rollo '.$empaque_noop.' EMPACADO.'; } 
                }
              }
            } else
            { $msg_modulo = 'Rollo incompatible con el contendido actual.'; } 
          } else 
          { $msg_modulo = 'Rollo no encontrado, es posible que ya sea parte de algun paquete.'; }
        }
      }

      echo '
    <form id="form_empaque" name="form_empaque" method="POST" action="alptEmpaqueR.php">
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_idempleado"><strong>ID:</strong> '.$_SESSION['idusuario'].'</label><br/>
              <label for="label_empleado"><strong>Empleado:</strong> '.$_SESSION['usuario'].'</label><br/>
              <label for="label_fchempaque"><strong>Fecha:</strong> '.date('Y-m-d').'</label></td></tr>
          <tr><td><label for="label_ttlrollo">Rollo</label><br/>
              <input type="text" style="width:120px" id="text_rollo" name="text_rollo" value="'.$prodRollo_cdgrollo.'" autofocus required/><br/>
              <input type="checkbox" id="checkbox_empacar" name="checkbox_empacar" />
              <label for="lbl_ttlempaque">Empaque</label>
              <a href="pdf/alptEmpaqueBCE.php?cdgempaque='.$_SESSION['cdgempaque'].'" target="_blank">'.$png_barcode.'</a></td></tr>
        </tbody>
        <tfoot>
        <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" />
        </td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr><td colspan="6" align="center"><strong>Contenido del Empaque</strong></td></tr>
          <tr><th><label for="label_ttlidempaque">Rollo</label></th>
            <th><label for="label_ttllongitud">Longitud</label></th>
            <th colspan="2"><label for="label_ttlunidades">Millares / Corte</label></th>
            <th><label for="label_ttlancho">Ancho plano</label></th>
            <th><label for="label_ttlpeso">Peso</label></th>
            <th></th></tr>
        </thead>
        <tbody>';

      $empaque_codigo = $_SESSION['empaque'];
      $num_rollos = count($empaque_codigo);

      if ($num_rollos > 0)
      { for ($id_rollo=1; $id_rollo<=$num_rollos; $id_rollo++)
        { $link_mysqli = conectar();
          $alptEmpaqueRolloSelect = $link_mysqli->query("
            SELECT prodlote.noop,
              prodbobina.bobina,
              prodrollo.rollo,
              prodrollo.longitud,
              prodrollo.amplitud,
              prodrollo.peso,
              prodrollo.cdgrollo,
              pdtoimpresion.corte
            FROM prodlote,
              prodbobina,
              prodrollo,
              pdtoimpresion
            WHERE prodlote.cdglote = prodbobina.cdglote AND
              prodbobina.cdgbobina = prodrollo.cdgbobina AND
              prodrollo.cdgrollo = '".$empaque_codigo[$id_rollo]."' AND
              prodrollo.cdgproducto = pdtoimpresion.cdgimpresion");

          if ($alptEmpaqueRolloSelect > 0)
          { $regAlptEmpaque = $alptEmpaqueRolloSelect->fetch_object();

            echo '
          <tr align="right"><td>'.$regAlptEmpaque->noop.'-'.$regAlptEmpaque->bobina.'-'.$regAlptEmpaque->rollo.'</td>
            <td>'.$regAlptEmpaque->longitud.'</td>
            <td>'.number_format(($regAlptEmpaque->longitud/$regAlptEmpaque->corte),3).'</td>
            <td>'.number_format($regAlptEmpaque->corte,0).'</td>
            <td>'.$regAlptEmpaque->amplitud.'</td>
            <td>'.number_format($regAlptEmpaque->peso,3).'</td>
            <td><a href="alptEmpaqueR.php?cdgrollo='.$regAlptEmpaque->cdgrollo.'&proceso=delete">'.$png_recycle_bin.'</a></td></tr>';
          } else
          { echo '
          <tr><td>'.$empaque_codigo[$id_rollo].'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td></tr>'; }
        }
      }
        
      echo '
        </tbody>
        <tfoot>
          <tr><td colspan="7" align="right"><label for="label_piedefiltro">['.$num_rollos.'] rollos agregados</label></td></tr>
        </tfoot>
      </table>
    </form>';

      if ($msg_alert != '')
      { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

      echo '<br/>
    <div align="center"><strong>'.$msg_modulo.'</strong></div>';       

    } else
    { echo '
    <br/><div align="center"><h1>Usuario no encontrado o bloqueado.</h1></div>'; }    
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>

  </body>
</html>
