<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registro del Laminado</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <a href="ayudaBS.php#laminado"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>
        <Label><h1>Banda de Seguridad</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '60135';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }

    //Buscame los datos ingresados  
    $prodLaminado_cdgempleado = trim($_POST['textCdgEmpleado']);
    $prodLaminado_cdgmaquina = trim($_POST['textCdgMaquina']);   
    $prodLaminado_codigo = trim($_POST['textCodigo']);

    //Buscar Empleado
    $rechEmpleadoSelect = $link->query("
      SELECT * FROM rechempleado 
      WHERE (idempleado = '".$prodLaminado_cdgempleado."' OR 
             cdgempleado = '".$prodLaminado_cdgempleado."') AND 
             sttempleado >= '1'");
      
    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $prodLaminado_idempleado = $regRechEmpleado->idempleado;
      $prodLaminado_empleado = $regRechEmpleado->empleado;
      $prodLaminado_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina
      $prodMaquinaSelect = $link->query("
        SELECT * FROM prodmaquina 
        WHERE (idmaquina = '".$prodLaminado_cdgmaquina."' OR 
               cdgmaquina = '".$prodLaminado_cdgmaquina."') AND 
               cdgsubproceso = '007' AND 
               sttmaquina >= '1'");
      
      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodLaminado_idmaquina = $regProdMaquina->idmaquina;
        $prodLaminado_maquina = $regProdMaquina->maquina;
        $prodLaminado_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar bobina 
        $prodBobinaSelect = $link->query("
          SELECT proglote.idlote,
                 proglote.lote,
                 proglote.tarima,
                 prodlote.serie,
          CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                 pdtobanda.banda,
                 pdtobandap.bandap,
                 pdtobandap.cdgbandap,
                 prodbobina.longitud,
                 prodbobina.amplitud,
                 prodbobina.peso,
                 prodbobina.cdgbobina,
                 prodbobina.sttbobina
            FROM proglote,
                 prodlote,
                 prodbobina,
                 pdtobanda,
                 pdtobandap
          WHERE (proglote.cdglote = prodlote.cdglote AND 
                 prodlote.cdglote = prodbobina.cdglote) AND
                (prodbobina.cdgbobina = '".$prodLaminado_codigo."' OR 
          CONCAT(prodlote.serie,'.',prodlote.noop,'-',prodbobina.bobina) = '".$prodLaminado_codigo."') AND
                (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                 pdtobanda.cdgbanda = pdtobandap.cdgbanda)");

        if ($prodBobinaSelect->num_rows > 0)
        { $regProdBobina = $prodBobinaSelect->fetch_object();

          $prodLaminado_idlote = $regProdBobina->idlote;
          $prodLaminado_lote = $regProdBobina->lote;
          $prodLaminado_tarima = $regProdBobina->tarima;
          $prodLaminado_serie = $regProdBobina->serie;
          $prodLaminado_noop = $regProdBobina->noop;
          $prodLaminado_banda = $regProdBobina->banda;
          $prodLaminado_bandap = $regProdBobina->bandap;
          $prodLaminado_cdgbandap = $regProdBobina->cdgbandap;
          $prodLaminado_longitud = $regProdBobina->longitud;
          $prodLaminado_amplitud = $regProdBobina->amplitud;
          $prodLaminado_peso = $regProdBobina->peso;
          $prodLaminado_cdgbobina = $regProdBobina->cdgbobina;
          $prodLaminado_sttbobina = $regProdBobina->sttbobina;

          if ($_POST['bttnSalvar'])
          { // Obtener la fecha de registro   
            $fchoperacion = date('Y-m-d');
            // Verificar que la longitud ingresada sea numérica
            if (is_numeric($_POST['textLongitud'])) { $prodLaminado_newlongitud = $_POST['textLongitud']; }
            // Verificar que la amplitud ingresada sea numérica
            if (is_numeric($_POST['textAmplitud'])) { $prodLaminado_newamplitud = $_POST['textAmplitud']; }
            // Verificar que el peso ingresado sea numérico
            if (is_numeric($_POST['textPeso'])) { $prodLaminado_newpeso = $_POST['textPeso']; }
            // verifica que la cantidad de banderas sea numérico
            if (is_numeric($_POST['textBandera'])) { $prodLaminado_bandera = $_POST['textBandera']; }

            if ($prodLaminado_newlongitud > 0)
            { if ($prodLaminado_newamplitud > 0)
              { if ($prodLaminado_newpeso > 0)
                { if ($prodLaminado_sttbobina == '1')
                  { // Bobina refilada
                    $link->query("
                      INSERT INTO prodbobinaope
                        (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, amplitud, amplitudfin, peso, pesofin, numbandera, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodLaminado_cdgbobina."', '30015', '".$prodLaminado_cdgempleado."', '".$prodLaminado_cdgmaquina."', '".$prodLaminado_longitud."', '".$prodLaminado_newlongitud."', '".$prodLaminado_amplitud."', '".$prodLaminado_newamplitud."', '".$prodLaminado_peso."', '".$prodLaminado_newpeso."', '".$prodLaminado_bandera."', '".$fchoperacion."', NOW())");

                    if ($link->affected_rows > 0)
                    { $link->query("
                        UPDATE prodbobina
                           SET sttbobina = '5',
                               longitud = '".$prodLaminado_newlongitud."',
                               amplitud = '".$prodLaminado_newamplitud."',
                               peso = '".$prodLaminado_newpeso."',
                               fchmovimiento = NOW()
                         WHERE cdgbobina = '".$prodLaminado_cdgbobina."' AND
                               sttbobina = '1'");
                      
                      if ($link->affected_rows > 0) 
                      { $msg_alert = 'Bobina afectada.'; }
                    } else
                    { $link->query("
                        DELETE FROM prodbobinaope
                         WHERE cdgbobina = '".$prodLaminado_cdgbobina."' AND
                               cdgoperacion = '30015'");

                      $msg_alert .= 'No fue posible registrar la operación.'; }
                  } else
                  { // Modifica la captura
                    if (substr($sistModulo_permiso,0,3) == 'rwx')
                    { $link->query("
                        UPDATE prodbobinaope
                           SET cdgempleado = '".$prodLaminado_cdgempleado."',
                               cdgmaquina = '".$prodLaminado_cdgmaquina."',
                               longitudfin = '".$prodLaminado_newlongitud."',
                               amplitudfin = '".$prodLaminado_newamplitud."',
                               pesofin = '".$prodLaminado_newpeso."',
                               numbandera = '".$prodLaminado_bandera."'
                         WHERE cdglote = '".$prodLaminado_cdgbobina."' AND
                               cdgoperacion = '30015'");

                      if ($link->affected_rows > 0)
                      { // Actualiza la bobina en caso
                        if ($prodRevision_sttrollo == '9')
                        { $link->query("
                            UPDATE prodbobina
                               SET longitud = '".$prodLaminado_newlongitud."',
                                   amplitud = '".$prodLaminado_newamplitud."',
                                   peso = '".$prodLaminado_newpeso."',
                                   bandera = '".$prodLaminado_bandera."'
                             WHERE cdgbobina = '".$prodLaminado_cdgbobina."' AND
                                   sttbobina = '9'"); } 
                      } else
                      { $msg_alert .= 'La captura no presento cambios.'; }
                    } else
                    { $msg_alert .= 'La operación Laminado ya fue registrada en esta bobina, no tienes permisos para editar el registro.'; }
                  }

                  $prodLaminado_codigo = '';
                } else
                { $msg_alert = 'Información de peso, incorrecta.'; }
              } else
              { $msg_alert = 'Información de amplitud, incorrecta.'; }
            } else
            { $msg_alert = 'Información de longitud, incorrecta.'; }
          } // Fin de proceso de salvado
        } else
        { // Buscar Lote
          $prodLoteSelect = $link->query("
            SELECT proglote.idlote,
                   proglote.lote,
                   proglote.tarima,
                   prodlote.serie,
                   prodlote.noop,
                   pdtobanda.banda,
                   pdtobandap.bandap,
                   pdtobandap.cdgbandap,
                   prodlote.longitud,
                   prodlote.amplitud,
                   prodlote.peso,
                   prodlote.cdglote,
                   prodlote.sttlote
              FROM proglote,
                   prodlote,
                   pdtobanda,
                   pdtobandap
             WHERE proglote.cdglote = prodlote.cdglote AND 
                  (prodlote.cdglote = '".$prodLaminado_codigo."' OR
            CONCAT(prodlote.serie,'.',prodlote.noop) = '".$prodLaminado_codigo."') AND
                  (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                   pdtobanda.cdgbanda = pdtobandap.cdgbanda)");
          
          if ($prodLoteSelect->num_rows > 0)
          { $regProdLote = $prodLoteSelect->fetch_object();

            $prodLaminado_idlote = $regProdLote->idlote;
            $prodLaminado_lote = $regProdLote->lote;
            $prodLaminado_tarima = $regProdLote->tarima;
            $prodLaminado_serie = $regProdLote->serie;
            $prodLaminado_noop = $regProdLote->noop;
            $prodLaminado_banda = $regProdLote->banda;
            $prodLaminado_bandap = $regProdLote->bandap;
            $prodLaminado_cdgbandap = $regProdLote->cdgbandap;
            $prodLaminado_longitud = $regProdLote->longitud;
            $prodLaminado_amplitud = $regProdLote->amplitud;
            $prodLaminado_peso = $regProdLote->peso;
            $prodLaminado_cdglote = $regProdLote->cdglote;
            $prodLaminado_sttlote = $regProdLote->sttlote;

            if ($_POST['bttnSalvar'])
            { // Obtener la fecha de registro
              $fchoperacion = date('Y-m-d');
              // Verificar que la longitud ingresada sea numérica
              if (is_numeric($_POST['textLongitud'])) { $prodLaminado_newlongitud = $_POST['textLongitud']; }
              // Verificar que la amplitud ingresada sea numérica
              if (is_numeric($_POST['textAmplitud'])) { $prodLaminado_newamplitud = $_POST['textAmplitud']; }
              // Verificar que el peso ingresado sea numérico
              if (is_numeric($_POST['textPeso'])) { $prodLaminado_newpeso = $_POST['textPeso']; }
              // verifica que la cantidad de banderas sea numérico
              if (is_numeric($_POST['textBandera'])) { $prodLaminado_bandera = $_POST['textBandera']; }               

              if ($prodLaminado_newlongitud > 0)
              { if ($prodLaminado_newamplitud > 0)
                { if ($prodLaminado_newpeso > 0)
                  { 
                    if ($prodLaminado_sttlote == '1')
                    { // Lote embosado
                      $link->query("
                        INSERT INTO prodloteope
                          (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, amplitud, amplitudfin, peso, pesofin, numbandera, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodLaminado_cdglote."', '30015', '".$prodLaminado_cdgempleado."', '".$prodLaminado_cdgmaquina."', '".$prodLaminado_longitud."', '".$prodLaminado_newlongitud."', '".$prodLaminado_amplitud."', '".$prodLaminado_newamplitud."', '".$prodLaminado_peso."', '".$prodLaminado_newpeso."', '".$prodLaminado_bandera."', '".$fchoperacion."', NOW())");

                      if ($link->affected_rows > 0)
                      { $link->query("
                          UPDATE prodlote
                             SET sttlote = '5',
                                 longitud = '".$prodLaminado_newlongitud."',
                                 amplitud = '".$prodLaminado_newamplitud."',
                                 peso = '".$prodLaminado_newpeso."',
                                 fchmovimiento = NOW()
                           WHERE cdglote = '".$prodLaminado_cdglote."' AND
                                 sttlote = '1'");
                        
                        if ($link->affected_rows > 0) 
                        { $msg_alert = 'Lote afectada.'; }
                      } else
                      { $link->query("
                          DELETE FROM prodloteope
                           WHERE cdglote = '".$prodLaminado_cdglote."' AND
                                 cdgoperacion = '30015'");

                        $msg_alert .= 'No fue posible registrar la operación.'; } 
                    } else
                    { if (substr($sistModulo_permiso,0,3) == 'rwx')
                      { $link->query("
                          UPDATE prodloteope
                             SET cdgempleado = '".$prodLaminado_cdgempleado."',
                                 cdgmaquina = '".$prodLaminado_cdgmaquina."',
                                 longitudfin = '".$prodLaminado_newlongitud."',
                                 amplitudfin = '".$prodLaminado_newamplitud."',
                                 pesofin = '".$prodLaminado_newpeso."',
                                 numbandera = '".$prodLaminado_bandera."'
                           WHERE cdglote = '".$prodLaminado_cdglote."' AND
                                 cdgoperacion = '30015'");

                        if ($link->affected_rows > 0)
                        { // Actualiza la bobina en caso
                          $link->query("
                            UPDATE prodlote
                               SET longitud = '".$prodLaminado_newlongitud."',
                                   amplitud = '".$prodLaminado_newamplitud."',
                                   peso = '".$prodLaminado_newpeso."'
                             WHERE cdglote = '".$prodLaminado_cdglote."' AND
                                  (sttlote = '5' OR sttlote = '9')");
                        } else
                        { $msg_alert .= 'La captura no presento cambios.'; }
                      } else
                      { $msg_alert .= 'La operación Laminado ya fue registrada en este lote, no tienes permisos para editar el registro.'; }
                    }

                    $prodLaminado_codigo = '';
                  } else
                  { $msg_alert = 'La información del peso , incorrecta.'; }
                } else
                { $msg_alert = 'Información de amplitud, incorrecta.'; }
              } else 
              { $msg_alert = 'Información de longitud, incorrecta.'; }
            } 
          } else
          { $prodRefilado_cdglote = '';
            $msg_alert = 'Información del lote, incorrecta.'; }

          // Fin de proceso de salvado
        }       
      } else
      { $prodLaminado_cdgmaquina = '';
        $msg_alert = 'Información de máquina, incorrecta.'; }
    } else
    { $prodLaminado_cdgempleado = '';
      $msg_alert = 'Información de empleado, incorrecta.'; }

    if ($prodLaminado_cdgempleado == '' OR $prodLaminado_cdgmaquina == '' OR $prodLaminado_codigo == '')
    { echo '
      <div class="bloque">
        <form id="formProdEmbosado" name="formProdEmbosado" method="POST" action="prodLaminadoBS.php"/>
          <article class="subbloque">
            <label class="modulo_nombre">Registro del Laminado</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Empleado</label><br/>
              <input type="text" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodLaminado_idempleado.'" required/>
            </article>

            <article>
              <label>Máquina</label><br/>
              <input type="text" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodLaminado_idmaquina.'" required/>
            </article>

            <article>
              <label>Lote/Bobina</label><br/>
              <input type="text" id="textCodigo" name="textCodigo" value="'.$prodLaminado_codigo.'" />
            </article>

            <article><br/>
              <input type="submit" id="bttnBuscar" name="bttnBuscar" value="Buscar" />
            </article>
          </section>
        </form>
      </div>'; 
    } else
    { echo '
      <form id="formProdEmbosado" name="formProdEmbosado" method="post" action="prodLaminadoBS.php"/>
        <div class="bloque">
          <input type="hidden" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodLaminado_cdgempleado.'" required/>
          <input type="hidden" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodLaminado_cdgmaquina.'" required/>
          <input type="hidden" id="textCodigo" name="textCodigo" value="'.$prodLaminado_codigo.'" required/>
          
          <article class="subbloque">
            <label class="modulo_nombre">Registro del Laminado</label>
          </article>
          <a href="prodLaminadoBS.php">'.$_gearback.'</a>
          <label>NoOP <strong>'.$prodLaminado_serie.'.'.$prodLaminado_noop.'</strong></label>        

          <section class="subbloque">
            <article>
              <label>Empleado</label><br/>
              <label><b>'.$prodLaminado_empleado.'</b></label>
            </article><br/>

            <article>
              <label>Máquina</label><br/>
              <label><b>'.$prodLaminado_maquina.'</b></label>
            </article><br/>

            <article>
              <label>Lote</label><br/>
              <label><b>'.$prodLaminado_tarima.' | '.$prodLaminado_idlote.'</b> Ref. <b>'.$prodLaminado_lote.'</b></label>
            </article><br/>

            <article>
              <label>Banda</label><br/>
              <label><b>'.$prodLaminado_banda.'</b> | <b>'.$prodLaminado_bandap.'</b></label>
            </article><br/> 

            <article>
              <label>Longitud</label><br/>
              <label><strong>'.number_format($prodLaminado_longitud,2).'</strong> metros</label>
            </article>

            <article>
              <label>Ancho del sustrato</label><br/>
              <label><strong>'.number_format($prodLaminado_amplitud).' </strong> milímetros</label>
            </article>

            <article>
              <label>Peso</label><br/>
              <label><strong>'.number_format($prodLaminado_peso,3).'</strong> kilos</label>
            </article>
          </section>
        </div>
        
        <div class="bloque">
          <article class="subbloque">
            <label class="modulo_listado">Información del lote</label>
          </article>          

          <section class="subbloque">
            <article>
              <label>Longitud</label><br/>
              <input type="text" class="input_numero" id="textLongitud" name="textLongitud" value="'.$prodLaminado_newlongitud.'" placeholder="metros" required/>
            </article>

            <article>
              <label>Amplitud</label><br/>
              <input type="text" class="input_numero" id="textAmplitud" name="textAmplitud" value="'.$prodLaminado_newamplitud.'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Peso</label><br/>
              <input type="text" class="input_numero" id="textPeso" name="textPeso" value="'.$prodLaminado_newpeso.'" placeholder="kilogramos" required/>
            </article>

            <article>
              <label>Flags</label><br/>
              <input type="text" class="input_numero" id="textBandera" name="textBandera" value="'.$prodLaminado_bandera.'" placeholder="#" required/>
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