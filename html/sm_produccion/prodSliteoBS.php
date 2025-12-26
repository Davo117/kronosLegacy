<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registro del Sliteo</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <a href="ayudaBS.php#sliteo"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>
        <Label><h1>Banda de Seguridad</h1></label>
      </section><?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '60140';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); } 

    //Buscame los datos ingresados
    $prodSliteo_cdgempleado = trim($_POST['textCdgEmpleado']);
  	$prodSliteo_cdgmaquina = trim($_POST['textCdgMaquina']);
  	$prodSliteo_codigo = trim($_POST['textCodigo']);

  	//Buscar Empleado
  	$rechEmpleadoSelect = $link->query("
  		SELECT * FROM rechempleado
  		WHERE (idempleado = '".$prodSliteo_cdgempleado."' OR 
             cdgempleado = '".$prodSliteo_cdgempleado."') AND 
             sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

    	$prodSliteo_idempleado = $regRechEmpleado->idempleado;
    	$prodSliteo_empleado = $regRechEmpleado->empleado;
    	$prodSliteo_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina
    	$prodMaquinaSelect = $link->query("
        SELECT * FROM prodmaquina 
        WHERE (idmaquina = '".$prodSliteo_cdgmaquina."' OR 
               cdgmaquina = '".$prodSliteo_cdgmaquina."') AND 
               cdgsubproceso = '008' AND 
               sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

      	$prodSliteo_idmaquina = $regProdMaquina->idmaquina;
      	$prodSliteo_maquina = $regProdMaquina->maquina;
      	$prodSliteo_cdgmaquina = $regProdMaquina->cdgmaquina;

        //Buscar bobina
        $prodBobinaSelect = $link->query("
          SELECT proglote.idlote,
                 proglote.lote,
                 proglote.tarima,
                 prodlote.serie,
          CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
                 pdtobanda.banda,
                 pdtobanda.anchura,
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
                (prodbobina.cdgbobina = '".$prodSliteo_codigo."' OR
          CONCAT(prodlote.serie,'.',prodlote.noop,'-',prodbobina.bobina) = '".$prodSliteo_codigo."') AND
                (prodbobina.cdgproducto = pdtobandap.cdgbandap AND 
                 pdtobandap.cdgbanda = pdtobanda.cdgbanda)");

  	    if ($prodBobinaSelect->num_rows > 0)
  	    { $regProdBobina = $prodBobinaSelect->fetch_object();

          $prodSliteo_idlote = $regProdBobina->idlote;
          $prodSliteo_lote = $regProdBobina->lote;
          $prodSliteo_tarima = $regProdBobina->tarima;
          $prodSliteo_serie = $regProdBobina->serie;
          $prodSliteo_noop = $regProdBobina->noop;
          $prodSliteo_banda = $regProdBobina->banda;
          $prodSliteo_bandap = $regProdBobina->bandap;
          $prodSliteo_cdgbandap = $regProdBobina->cdgbandap;
          $prodSliteo_longitud = $regProdBobina->longitud;
          $prodSliteo_amplitud = $regProdBobina->amplitud;
          $prodSliteo_peso = $regProdBobina->peso;         
          $prodSliteo_numdiscos = ($regProdBobina->amplitud/$regProdBobina->anchura);
  	    	$prodSliteo_cdgbobina = $regProdBobina->cdgbobina;
          $prodSliteo_sttbobina = $regProdBobina->sttbobina;

  			  if ($_POST['bttnSalvar'])
  			  { $fchoperacion = date('Y-m-d');
            //Validar la captura
            $error_longitud = false;
            $error_amplitud = false;
            $error_peso = false;
            $error_bandera = false;

            for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
            { if (is_numeric($_POST['textLongitud'.$item]))
              { $prodSliteo_newlongitud[$item] = $_POST['textLongitud'.$item]; }
              else
              { $error_longitud = true;
                $item = $prodSliteo_numdiscos+1; }
            }

            if ($error_longitud == false)
            { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
              { if (is_numeric($_POST['textAmplitud'.$item]))
                { $prodSliteo_newamplitud[$item] = $_POST['textAmplitud'.$item]; }
                else
                { $error_amplitud = true;
                  $item = $prodSliteo_numdiscos+1; }
              }

              if ($error_amplitud == false)
              { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                { if (is_numeric($_POST['textPeso'.$item]))
                  { $prodSliteo_newpeso[$item] = $_POST['textPeso'.$item]; }
                  else
                  { $error_peso = true;
                    $item = $prodSliteo_numdiscos+1; }
                }

                if ($error_peso == false)
                {  for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                  { if (is_numeric($_POST['textBandera'.$item]))
                    { $prodSliteo_bandera[$item] = $_POST['textBandera'.$item];
                    } else
                    { $error_bandera = true;
                      $item = $prodSliteo_numdiscos+1; }
                  }
                }
              }
            } // Fin de la validación

            if ($error_longitud == false)
            { if ($error_amplitud == false)
              { if ($error_peso == false)
                { if ($error_bandera == false)
                  { if ($prodSliteo_sttbobina == '5')
                    { // Bobina Sliteo
                      $link->query("
                        INSERT INTO prodbobinaope
                          (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, amplitud, peso, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodSliteo_cdgbobina."', '40011', '".$prodSliteo_cdgempleado."', '".$prodSliteo_cdgmaquina."', '".$prodSliteo_longitud."', '".$prodSliteo_amplitud."', '".$prodSliteo_peso."', '".$fchoperacion."', NOW())");
                      
                      if ($link->affected_rows > 0)
                      { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                        { $prodSliteo_cdgdisco = substr($prodSliteo_cdgbobina,0,9).str_pad($item,2,'0',STR_PAD_LEFT).'0'; 

                          $link->query("
                            INSERT INTO proddisco
                            (cdgbobina, disco, cdgproducto, longitud, amplitud, peso, cdgdisco, fchmovimiento)
                            VALUES
                            ('".$prodSliteo_cdgbobina."', '".$item."', '".$prodSliteo_cdgbandap."', '".$prodSliteo_newlongitud[$item]."', '".$prodSliteo_newamplitud[$item]."', '".$prodSliteo_newpeso[$item]."', '".$prodSliteo_cdgdisco."', NOW())");

                          $link->query("
                            INSERT INTO proddiscoope
                              (cdgdisco, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, amplitud, amplitudfin, peso, pesofin, numbandera, fchoperacion, fchmovimiento)
                            VALUES
                              ('".$prodSliteo_cdgdisco."', '40011', '".$prodSliteo_cdgempleado."', '".$prodSliteo_cdgmaquina."', '".$prodSliteo_longitud."', '".$prodSliteo_newlongitud[$item]."', '".($prodSliteo_amplitud/round($prodSliteo_numdiscos, 0, PHP_ROUND_HALF_DOWN))."', '".$prodSliteo_newamplitud[$item]."', '".($prodSliteo_peso/round($prodSliteo_numdiscos, 0, PHP_ROUND_HALF_DOWN))."', '".$prodSliteo_newpeso[$item]."', '".$prodSliteo_bandera[$item]."', '".$fchoperacion."', NOW())");

                          $msg_alert .= 'Sliteo '.$item.' \n'; }

                        $link->query("
                          UPDATE prodbobina
                             SET sttbobina = '9',
                                 fchmovimiento = NOW()
                           WHERE cdgbobina = '".$prodSliteo_cdgbobina."'");

                        $msg_modulo = '<a href="pdf/prodDiscosBS.php?cdgbobina='.$prodSliteo_cdgbobina.'" target="_blank">Generar etiquetas</a>';
                      } else
                      { $link->query("
                          DELETE FROM prodbobinaope
                           WHERE cdgbobina = '".$prodSliteo_cdgbobina."' AND
                                 cdgoperacion = '40011'");

                        $msg_alert .= 'No fue posible registrar la operación.'; }
                    } else
                    { if (substr($sistModulo_permiso,0,3) == 'rwx')
                      { 
                      } else
                      { $msg_alert .= 'La operación Sliteo ya fue registrada en esta bobina, no tienes permisos para editar el registro.'; }
                    }

                    $prodSliteo_codigo = ''; 
                  } else
                  { $msg_alert = 'Información de banderas, incorrecta.'; }
                } else
                { $msg_alert = 'Información de pesos, incorrecta.'; }
              } else
              { $msg_alert = 'Información de amplitudes, incorrecta.'; }
            } else
            { $msg_alert = 'Información de longitudes, incorrecta.'; }
  			  } // Fin de proceso de salvado //*/
  	    } else
  	    { 
          // Aqui va la búsqueda por lote
          $prodLoteSelect = $link->query("
            SELECT proglote.idlote,
                   proglote.lote,
                   proglote.tarima,
                   prodlote.serie,
                   prodlote.noop,
                   pdtobanda.banda,
                   pdtobanda.anchura,
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
                  (prodlote.cdglote = '".$prodSliteo_codigo."' OR
            CONCAT(prodlote.serie,'.',prodlote.noop) = '".$prodSliteo_codigo."') AND
                  (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                   pdtobanda.cdgbanda = pdtobandap.cdgbanda)");

          if ($prodLoteSelect->num_rows > 0)
          { $regProdLote = $prodLoteSelect->fetch_object();

            $prodSliteo_idlote = $regProdLote->idlote;
            $prodSliteo_lote = $regProdLote->lote;
            $prodSliteo_tarima = $regProdLote->tarima;
            $prodSliteo_serie = $regProdLote->serie;
            $prodSliteo_noop = $regProdLote->noop;
            $prodSliteo_banda = $regProdLote->banda;
            $prodSliteo_bandap = $regProdLote->bandap;
            $prodSliteo_cdgbandap = $regProdLote->cdgbandap;
            $prodSliteo_longitud = $regProdLote->longitud;
            $prodSliteo_amplitud = $regProdLote->amplitud;
            $prodSliteo_peso = $regProdLote->peso;
            $prodSliteo_numdiscos = ($regProdLote->amplitud/$regProdLote->anchura);
            $prodSliteo_cdglote = $regProdLote->cdglote;
            $prodSliteo_sttlote = $regProdLote->sttlote;

            if ($_POST['bttnSalvar'])
            { $fchoperacion = date('Y-m-d');  
              //Validar la captura
              $error_longitud = false;
              $error_amplitud = false;
              $error_peso = false;
              $error_bandera = false;

              for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
              { if (is_numeric($_POST['textLongitud'.$item]))
                { $prodSliteo_newlongitud[$item] = $_POST['textLongitud'.$item]; }
                else
                { $error_longitud = true;
                  $item = $prodSliteo_numdiscos+1; }
              }

              if ($error_longitud == false)
              { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                { if (is_numeric($_POST['textAmplitud'.$item]))
                  { $prodSliteo_newamplitud[$item] = $_POST['textAmplitud'.$item]; }
                  else
                  { $error_amplitud = true;
                    $item = $prodSliteo_numdiscos+1; }
                }

                if ($error_amplitud == false)
                { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                  { if (is_numeric($_POST['textPeso'.$item]))
                    { $prodSliteo_newpeso[$item] = $_POST['textPeso'.$item]; }
                    else
                    { $error_peso = true;
                      $item = $prodSliteo_numdiscos+1; }
                  }

                  if ($error_peso == false)
                  {  for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                    { if (is_numeric($_POST['textBandera'.$item]))
                      { $prodSliteo_bandera[$item] = $_POST['textBandera'.$item];
                      } else
                      { $error_bandera = true;
                        $item = $prodSliteo_numdiscos+1; }
                    }
                  }
                }
              } // Fin de la validación

              if ($error_longitud == false)
              { if ($error_amplitud == false)
                { if ($error_peso == false)
                  { if ($error_bandera == false)
                    { if ($prodSliteo_sttlote == '5')
                      { // Lote Sliteo
                        $link->query("
                          INSERT INTO prodloteope
                            (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longitud, amplitud, peso, fchoperacion, fchmovimiento)
                          VALUES
                            ('".$prodSliteo_cdglote."', '40011', '".$prodSliteo_cdgempleado."', '".$prodSliteo_cdgmaquina."', '".$prodSliteo_longitud."', '".$prodSliteo_amplitud."', '".$prodSliteo_peso."', '".$fchoperacion."', NOW())");
                        
                        if ($link->affected_rows > 0)
                        { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
                          { $prodSliteo_cdgdisco = substr($prodSliteo_cdglote,0,9).str_pad($item,2,'0',STR_PAD_LEFT).'0'; 

                            $link->query("
                              INSERT INTO proddisco
                              (cdgbobina, disco, cdgproducto, longitud, amplitud, peso, cdgdisco, fchmovimiento)
                              VALUES
                              ('".$prodSliteo_cdglote."', '".$item."', '".$prodSliteo_cdgbandap."', '".$prodSliteo_newlongitud[$item]."', '".$prodSliteo_newamplitud[$item]."', '".$prodSliteo_newpeso[$item]."', '".$prodSliteo_cdgdisco."', NOW())");

                            $link->query("
                              INSERT INTO proddiscoope
                                (cdgdisco, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, amplitud, amplitudfin, peso, pesofin, numbandera, fchoperacion, fchmovimiento)
                              VALUES
                                ('".$prodSliteo_cdgdisco."', '40011', '".$prodSliteo_cdgempleado."', '".$prodSliteo_cdgmaquina."', '".$prodSliteo_longitud."', '".$prodSliteo_newlongitud[$item]."', '".($prodSliteo_amplitud/$prodSliteo_numdiscos)."', '".$prodSliteo_newamplitud[$item]."', '".($prodSliteo_peso/$prodSliteo_numdiscos)."', '".$prodSliteo_newpeso[$item]."', '".$prodSliteo_bandera[$item]."', '".$fchoperacion."', NOW())");

                            $msg_alert .= 'Sliteo '.$item.' \n'; }

                          $link->query("
                            UPDATE prodlote
                               SET sttlote = '9',
                                   fchmovimiento = NOW()
                             WHERE cdglote = '".$prodSliteo_cdglote."'");

                          $msg_modulo = '<a href="pdf/prodDiscosBS.php?cdglote='.$prodSliteo_cdglote.'" target="_blank">Generar etiquetas</a>';
                        } else
                        { $link->query("
                            DELETE FROM prodloteope
                             WHERE cdglote = '".$prodSliteo_cdglote."' AND
                                   cdgoperacion = '40011'");

                          $msg_alert .= 'No fue posible registrar la operación.'; }
                      } else
                      { if (substr($sistModulo_permiso,0,3) == 'rwx')
                        { 
                        } else
                        { $msg_alert .= 'La operación Sliteo ya fue registrada en este lote, no tienes permisos para editar el registro.'; }
                      }

                      $prodSliteo_codigo = '';  
                    } else
                    { $msg_alert = 'Información de banderas, incorrecta.'; }
                  } else
                  { $msg_alert = 'Información de pesos, incorrecta.'; }
                } else
                { $msg_alert = 'Información de amplitudes, incorrecta.'; }
              } else
              { $msg_alert = 'Información de longitudes, incorrecta.'; }
            } // Fin de proceso de salvado 
          } 
        } 
      } else
      { $prodSliteo_cdgmaquina = '';
        $msg_alert = 'Información de máquina, incorrecta.'; }
    } else
    { $prodSliteo_cdgempleado = '';
      $msg_alert = 'Información de empleado, incorrecta.'; }

    if ($prodSliteo_cdgempleado == '' OR $prodSliteo_cdgmaquina == '' OR $prodSliteo_codigo == '')
    { echo '
      <div class="bloque">
        <form id="formProdSliteoBS" name="formProdSliteoBS" method="post" action="prodSliteoBS.php"/>
          <article class="subbloque">
            <label class="modulo_nombre">Registro del Sliteo</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Empleado</label><br/>
              <input type="text" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodSliteo_idempleado.'" required/>
            </article>

            <article>
              <label>Máquina</label><br/>
              <input type="text" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodSliteo_idmaquina.'" required/>
            </article>

            <article>
              <label>Lote/Bobina</label><br/>
              <input type="text" id="textCodigo" name="textCodigo" value="'.$prodSliteo_codigo.'" />
            </article>

            <article><br/>
              <input type="submit" id="bttnBuscar" name="bttnBuscar" value="Buscar" />
            </article>          
          </section>          
        </form>
      </div>'; 
    } else
    { echo '
    <form id="formProdSliteoBS" name="formProdSliteoBS" method="post" action="prodSliteoBS.php"/>
      <div class="bloque">
        <input type="hidden" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodSliteo_cdgempleado.'" />
        <input type="hidden" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodSliteo_cdgmaquina.'" />
        <input type="hidden" id="textCodigo" name="textCodigo" value="'.$prodSliteo_codigo.'" required/>
      
        <article class="subbloque">
          <label class="modulo_nombre">Registro del Sliteo</label>
        </article>
        <a href="prodSliteoBS.php">'.$_gearback.'</a>
        <label>NoOP <strong>'.$prodSliteo_serie.'.'.$prodSliteo_noop.'</strong></label>

        <section class="subbloque">
          <article>
            <label>Empleado</label><br/>
            <label><b>'.$prodSliteo_empleado.'</b></label>
          </article><br/>

          <article>
            <label>Máquina</label><br/>
            <label><b>'.$prodSliteo_maquina.'</b></label>
          </article><br/>

          <article>
            <label>Lote</label><br/>
            <label><b>'.$prodSliteo_tarima.' | '.$prodSliteo_idlote.'</b> Ref. <b>'.$prodSliteo_lote.'</b></label>
          </article><br/>   

          <article>
            <label>Banda</label><br/>
            <label><b>'.$prodSliteo_banda.'</b> | <b>'.$prodSliteo_bandap.'</b></label>
          </article><br/> 

          <article>
            <label>Longitud</label><br/>
            <label><strong>'.number_format($prodSliteo_longitud,2).'</strong> metros</label>
          </article>

          <article>
            <label>Ancho del sustrato</label><br/>
            <label><strong>'.number_format($prodSliteo_amplitud).' </strong> milímetros</label>
          </article>

          <article>
            <label>Peso</label><br/>
            <label><strong>'.number_format($prodSliteo_peso,3).'</strong> kilos</label>
          </article>

          <article><br/>
            <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
          </article>
        </section>
      </div>';

      if ($prodSliteo_numdiscos > 0)
      { for ($item = 1; $item <= $prodSliteo_numdiscos; $item++)
        { echo '
        <div class="bloque">
          <article class="modulo_listado">
            <label class="modulo_nombre">Información del disco '.$item.'</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Longitud en metros</label><br />
              <input type="text" id="textLongitud'.$item.'" name="textLongitud'.$item.'" value="'.$prodSliteo_newlongitud[$item].'" placeholder="metros" required/>
            </article>

            <article>
              <label>Amplitud en milímetros</label><br />
              <input type="text" id="textAmplitud'.$item.'" name="textAmplitud'.$item.'" value="'.$prodSliteo_newamplitud[$item].'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Peso en kilos</label><br/>
              <input type="text" class="input_numero" id="textPeso'.$item.'" name="textPeso'.$item.'" value="'.$prodSliteo_newpeso[$item].'" placeholder="kilos" required/>
            </article>

            <article>
              <label>Flags</label><br/>
              <input type="text" class="input_numero" id="textBandera'.$item.'" name="textBandera'.$item.'" value="'.$prodSliteo_bandera[$item].'" placeholder="#" required/>
            </article>             
          </section>
        </div>'; }
      }

      echo '
      </form>'; }

    if ($msg_modulo != '')
    { echo '
      <div align="center"><strong>'.$msg_modulo.'</strong></div><br/>'; }

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