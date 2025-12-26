<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registro de la Impresión</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        
        <Label><h1>Termoencogible</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '60020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }
      
    //Buscame los datos ingresados  
    $prodImpresion_cdgempleado = trim($_POST['textCdgEmpleado']);
    $prodImpresion_cdgmaquina = trim($_POST['textCdgMaquina']);
    $prodImpresion_cdgjuego = trim($_POST['textCdgJuego']);
    $prodImpresion_cdglote = trim($_POST['textCdgLote']);

    //Buscar Empleado
    $rechEmpleadoSelect = $link->query("
      SELECT * FROM rechempleado 
       WHERE (idempleado = '".$prodImpresion_cdgempleado."' OR 
              cdgempleado = '".$prodImpresion_cdgempleado."') AND 
              sttempleado >= '1'");
    
    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $prodImpresion_idempleado = $regRechEmpleado->idempleado;
      $prodImpresion_empleado = $regRechEmpleado->empleado;
      $prodImpresion_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina del subproceso 001 Impresión
      $prodMaquinaSelect = $link->query("
        SELECT * FROM prodmaquina 
        WHERE (idmaquina = '".$prodImpresion_cdgmaquina."' OR 
               cdgmaquina = '".$prodImpresion_cdgmaquina."') AND 
               cdgsubproceso = '001' AND 
               sttmaquina >= '1'");
      
      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodImpresion_idmaquina = $regProdMaquina->idmaquina;
        $prodImpresion_maquina = $regProdMaquina->maquina;
        $prodImpresion_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar juego de cilíndros
        $pdtoJuegoSelect = $link->query("
          SELECT * FROM pdtojuego
           WHERE (idjuego = '".$prodImpresion_cdgjuego."' OR
                  cdgjuego = '".$prodImpresion_cdgjuego."') AND
                  sttjuego = '1'");

        if ($pdtoJuegoSelect->num_rows > 0)
        { $regPdtoJuego = $pdtoJuegoSelect->fetch_object();

          $prodImpresion_idjuego = $regPdtoJuego->idjuego;
          $prodImpresion_cdgjuego = $regPdtoJuego->cdgjuego;

          //Buscar lote Proceso 10 Liberada
          $progLoteSelect = $link->query("
            SELECT proglote.idlote,
                   proglote.lote,
                   prodlote.serie,
                   prodlote.noop,
                   pdtodiseno.diseno,
                   pdtojuego.idjuego,
                   pdtojuego.proveedor,
                   pdtojuego.cdgjuego,
                   pdtoimpresion.impresion,
                   pdtoimpresion.cdgimpresion,
                   proglote.longitud AS longin,
                   prodlote.longitud,
                   proglote.amplitud,
                   prodlote.peso,
                   proglote.tarima,
                   prodlote.cdglote,
                   prodlote.sttlote
              FROM proglote,
                   prodlote,
                   pdtodiseno,
                   pdtojuego,
                   pdtoimpresion
             WHERE proglote.cdglote = prodlote.cdglote AND
                  (prodlote.cdglote = '".$prodImpresion_cdglote."' OR
            CONCAT(prodlote.serie,'.',prodlote.noop) = '".$prodImpresion_cdglote."') AND
                  (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
                   pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
                  (pdtoimpresion.cdgimpresion = pdtojuego.cdgimpresion AND
                   pdtojuego.cdgjuego = '".$prodImpresion_cdgjuego."')");
          
          if ($progLoteSelect->num_rows > 0)
          { $regProgLote = $progLoteSelect->fetch_object();

            $prodImpresion_idlote = $regProgLote->idlote;
            $prodImpresion_lote = $regProgLote->lote;
            $prodImpresion_serie = $regProgLote->serie;
            $prodImpresion_noop = $regProgLote->noop;
            $prodImpresion_longin = $regProgLote->longin;
            $prodImpresion_longitud = $regProgLote->longitud;
            $prodImpresion_longitudlote = $regProgLote->longitud;
            $prodImpresion_amplitud = $regProgLote->amplitud;
            $prodImpresion_peso = $regProgLote->peso;
            $prodImpresion_pesolote = $regProgLote->peso;
            $prodImpresion_tarima = $regProgLote->tarima;
            $prodImpresion_diseno = $regProgLote->diseno;
            $prodImpresion_idjuego = $regProgLote->idjuego;
            $prodImpresion_cdgjuego = $regProgLote->cdgjuego;
            $prodImpresion_proveedor = $regProgLote->proveedor;
            $prodImpresion_impresion = $regProgLote->impresion;
            $prodImpresion_cdgimpresion = $regProgLote->cdgimpresion;
            $prodImpresion_cdglote = $regProgLote->cdglote;
            $prodImpresion_sttlote = $regProgLote->sttlote;

            if (is_numeric($_POST['textLongitud']))
            { $prodImpresion_longitudlote = $_POST['textLongitud']; }

            if (is_numeric($_POST['textPeso']))
            { $prodImpresion_pesolote = $_POST['textPeso']; }
        
            // Inicio del proceso de salvado
            if ($_POST['bttnSalvar'])
            { // Salvar            
              $fchoperacion = date('Y-m-d');

              if ($prodImpresion_longitudlote >= 1000 AND $prodImpresion_longitudlote <= 9999)
              { if ($prodImpresion_pesolote > 0)
                { if ($prodImpresion_sttlote == 'A')
                  { // Registra la operación de impresión
                    $link->query("
                      INSERT INTO prodloteope
                        (cdglote, cdgoperacion, cdgempleado, cdgmaquina, cdgjuego, longitud, longitudfin, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodImpresion_cdglote."', '20001', '".$prodImpresion_cdgempleado."', '".$prodImpresion_cdgmaquina."', '".$prodImpresion_cdgjuego."', '".$prodImpresion_longin."', '".$prodImpresion_longitudlote."', '".$fchoperacion."', NOW())");                       

                    if ($link->affected_rows > 0) 
                    { $msg_alert .= 'Operacion registrada. \n';

                      $link->query("
                        UPDATE prodlote
                           SET sttlote = '1',
                               longitud = '".$prodImpresion_longitudlote."',
                               peso = '".$prodImpresion_pesolote."', 
                               fchmovimiento = NOW()
                         WHERE cdglote = '".$prodImpresion_cdglote."'");

                      if ($link->affected_rows > 0) 
                      { $msg_alert .= 'Bobina afectada.';

                        $link->query("
                          UPDATE proglote
                             SET sttlote = '9',
                                 fchmovimiento = NOW()
                           WHERE cdglote = '".$prodImpresion_cdglote."'");
                      } else
                      { //Cancela toda la operacion
                        $link->query("
                          DELETE FROM prodloteope
                           WHERE cdglote = '".$prodImpresion_cdglote."' AND
                                 cdgoperacion = '20001'");

                        $msg_alert .= 'No fue posible registrar la AFECTACION completa del lote.'; }
                    } else
                    { $msg_alert .= 'No fue posible registrar la OPERACION.'; }
                  } else
                  { // Actualiza la operación de impresión
                    if (substr($sistModulo_permiso,0,3) == 'rwx')
                    { $prodLoteSelect = $link->query("
                        SELECT * FROM prodlote
                         WHERE cdglote = '".$prodImpresion_cdglote."' AND
                               empacado = 0");

                      if ($prodLoteSelect->num_rows > 0)
                      { $link->query("
                          UPDATE prodloteope
                             SET cdgempleado = '".$prodImpresion_cdgempleado."',
                                 cdgmaquina = '".$prodImpresion_cdgmaquina."',
                                 cdgjuego = '".$prodImpresion_cdgjuego."',
                                 longitudfin = '".$prodImpresion_longitudlote."'
                           WHERE cdglote = '".$prodImpresion_cdglote."' AND
                                 cdgoperacion = '20001'");
                      } else
                      { $link->query("
                        UPDATE prodloteope
                           SET cdgempleado = '".$prodImpresion_cdgempleado."',
                               cdgmaquina = '".$prodImpresion_cdgmaquina."',
                               longitudfin = '".$prodImpresion_longitudlote."'
                         WHERE cdglote = '".$prodImpresion_cdglote."' AND
                               cdgoperacion = '20001'");

                        $msg_alert .= 'Este lote ya tiene aplicado el cálculo de merma, no puede modificarse el juego de cilindros empleado.'; }

                      if ($link->affected_rows > 0)
                      { // Actualiza el lote activo
                          $link->query("
                            UPDATE prodlote
                               SET longitud = '".$prodImpresion_longitudlote."', 
                                   peso = '".$prodImpresion_pesolote."'
                             WHERE cdglote = '".$prodImpresion_cdglote."' AND
                                  (sttlote = '1' OR sttlote = '9')");
                        
                        if ($prodImpresion_sttlote == '9')
                        { // Actualiza el lote y las bobinas
                          $link->query("
                            UPDATE prodloteope
                               SET longitud = '".$prodImpresion_longitudlote."', 
                                   peso = '".$prodImpresion_pesolote."'
                             WHERE cdglote LIKE '".$prodImpresion_cdglote."%' AND
                                   cdgoperacion = '30001'");

                          $prodImpresion_cdglote = substr($prodImpresion_cdglote,0,8);

                          $link->query("
                            UPDATE prodbobinaope
                               SET cdgempleado = '".$prodImpresion_cdgempleado."',
                                   cdgmaquina = '".$prodImpresion_cdgmaquina."',
                                   longitud = '".$prodImpresion_longitudlote."'
                             WHERE cdgbobina LIKE '".$prodImpresion_cdglote."%' AND
                                   cdgoperacion = '30001'");
                        }
                      } else
                      { $msg_alert .= 'La captura no presento cambios.'; }
                    } else
                    { $msg_alert .= 'La operación Impresión ya fue registrada en este lote, no tienes permisos para editar el registro.'; }
                  }
  
                  $prodImpresion_cdglote = '';
                } else
                { $msg_alert = 'Información de peso, incorrecta.'; } 
              } else 
              { $msg_alert = 'Información de longitud, incorrecta.'; }
            } 
            // Fin de proceso de salvado
          } else
          { $prodImpresion_cdglote = '';
            $msg_alert = 'Información de bobina, incorrecta.'; }
        } else
        { $prodImpresion_cdgjuego = '';
          $msg_alert = 'Información de juego de cilindros, incorrecta.'; }
      } else
      { $prodImpresion_cdgmaquina = '';
        $msg_alert = 'Información de máquina, incorrecta.'; }
    } else
    { $prodImpresion_cdgempleado = '';
      $msg_alert = 'Información de empleado, incorrecta.'; }

    if ($prodImpresion_cdgempleado == '' OR $prodImpresion_cdgmaquina == '' OR $prodImpresion_cdgjuego == '' OR $prodImpresion_cdglote == '')
    { echo '
      <div class="bloque">
        <form id="formProdImpresion" name="formProdImpresion" method="POST" action="prodImpresion.php"/>
          <article class="subbloque">
            <label class="modulo_nombre">Registro de la Impresión</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Operador</label><br/>
              <input type="text" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodImpresion_idempleado.'" required/>
            </article>

            <article>
              <label>Máquina</label><br/>
              <input type="text" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodImpresion_idmaquina.'" required/>
            </article>

            <article>
              <label>Juego de cilíndros</label><br/>
              <input type="text" id="textCdgJuego" name="textCdgJuego" value="'.$prodImpresion_cdgjuego.'" required/>
            </article>

            <article>
              <label>Lote</label><br/>
              <input type="text" id="textCdgLote" name="textCdgLote" value="'.$prodImpresion_cdglote.'" required/>
            </article>

            <article><br/>
              <input type="submit" id="bttnBuscar" name="bttnBuscar" value="Buscar" />
            </article>
          </section>
        </form>
      </div>'; 
    } else
    { echo '
      <form id="formProdImpresion" name="formProdImpresion" method="POST" action="prodImpresion.php"/>
        <div class="bloque">
          <input type="hidden" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodImpresion_cdgempleado.'" />
          <input type="hidden" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodImpresion_cdgmaquina.'" />
          <input type="hidden" id="textCdgJuego" name="textCdgJuego" value="'.$prodImpresion_cdgjuego.'" />
          <input type="hidden" id="textCdgLote" name="textCdgLote" value="'.$prodImpresion_cdglote.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Registro de la Impresión</label>
          </article>
          <a href="prodImpresion.php">'.$_gearback.'</a>
          <label>NoOP <strong>'.$prodImpresion_serie.'.'.$prodImpresion_noop.'</strong></label>

          <section class="subbloque">
            <article>
              <label>Operador</label><br/>
              <label><b>'.$prodImpresion_empleado.'</b></label>
            </article><br/>

            <article>
              <label>Máquina</label><br/>
              <label><b>'.$prodImpresion_maquina.'</b></label>
            </article><br/>

            <article>
              <label>Juego de cilíndros</label><br/>
              <label><b>'.$prodImpresion_idjuego.'</b> | <i>'.$prodImpresion_proveedor.'</i></label>
            </article><br/>

            <article>
              <label>Lote</label><br/>
              <label><b>'.$prodImpresion_tarima.' | '.$prodImpresion_idlote.'</b> Ref. <b>'.$prodImpresion_lote.'</b></label>
            </article><br/>

            <article>
              <label>Diseño</label><br/>
              <label><b>'.$prodImpresion_diseno.'</b></label>
            </article><br/>

            <article>
              <label>Impresión</label><br/>
              <label><b>'.$prodImpresion_impresion.'</b></label>
            </article><br/>
          </section>
        </div>

        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Información del lote</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Longitud</label><br/>
              <input type="text" id="textLongitud" name="textLongitud" value="'.$prodImpresion_longitudlote.'" placeholder="metros" required/>
            </article>

            <article>
              <label>Peso</label><br/>
              <input type="text" id="textPeso" name="textPeso" value="'.$prodImpresion_pesolote.'" placeholder="kilogramos" required/>
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>
      </form>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div><h1>Módulo no disponible o bloqueado.</h1></div>'; }
?>
    </div>
  </body>
</html>
