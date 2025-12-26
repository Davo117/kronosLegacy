<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registro del Refilado</title>
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

  $sistModulo_cdgmodulo = '60030';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }

    //Buscame los datos ingresados
    $prodRefilado_cdgempleado = trim($_POST['textCdgEmpleado']);
    $prodRefilado_cdgmaquina = trim($_POST['textCdgMaquina']);
    $prodRefilado_cdglote = trim($_POST['textCdgLote']);

    //Buscar Empleado
    $rechEmpleadoSelect = $link->query("
      SELECT * FROM rechempleado
       WHERE (idempleado = '".$prodRefilado_cdgempleado."' OR 
              cdgempleado = '".$prodRefilado_cdgempleado."') AND 
              sttempleado >= '1'");

    if ($rechEmpleadoSelect->num_rows > 0)
    { $regRechEmpleado = $rechEmpleadoSelect->fetch_object();

      $prodRefilado_idempleado = $regRechEmpleado->idempleado;
      $prodRefilado_empleado = $regRechEmpleado->empleado;
      $prodRefilado_cdgempleado = $regRechEmpleado->cdgempleado;

      //Buscar Maquina Proceso 30 Refilado
      $prodMaquinaSelect = $link->query("
        SELECT * FROM prodmaquina
         WHERE (idmaquina = '".$prodRefilado_cdgmaquina."' OR 
                cdgmaquina = '".$prodRefilado_cdgmaquina."') AND 
                cdgsubproceso = '002' AND 
                sttmaquina >= '1'");

      if ($prodMaquinaSelect->num_rows > 0)
      { $regProdMaquina = $prodMaquinaSelect->fetch_object();

        $prodRefilado_idmaquina = $regProdMaquina->idmaquina;
        $prodRefilado_maquina = $regProdMaquina->maquina;
        $prodRefilado_cdgmaquina = $regProdMaquina->cdgmaquina;

        // Se realiza la búsqueda del lote, asegurandonos que fue registrado en impresión y que el código de juego de cilindro existe
        $prodLoteSelect = $link->query("
          SELECT proglote.idlote,
                 proglote.lote,
                 proglote.tarima,
                 prodlote.serie,
                 prodlote.noop,                 
                 prodlote.longitud,
                 prodlote.amplitud,
                 prodlote.peso,
                 prodlote.cdglote,
                 pdtojuego.alpaso,
                 pdtodiseno.diseno,
                 pdtoimpresion.impresion,
                 pdtoimpresion.cdgimpresion,
                 prodlote.sttlote
            FROM proglote,
                 prodlote,
                 prodloteope,
                 pdtodiseno,
                 pdtojuego,
                 pdtoimpresion
          WHERE (proglote.cdglote = prodlote.cdglote AND
                 prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgoperacion = '20001' AND
                 prodloteope.cdgjuego = pdtojuego.cdgjuego) AND
                (prodlote.cdglote = '".$prodRefilado_cdglote."' OR
          CONCAT(prodlote.serie,'.',prodlote.noop) = '".$prodRefilado_cdglote."') AND 
                (prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND 
                 pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno)");

        if ($prodLoteSelect->num_rows > 0)
        { $regProdLote = $prodLoteSelect->fetch_object();

          $prodRefilado_idlote = $regProdLote->idlote;
          $prodRefilado_lote = $regProdLote->lote;
          $prodRefilado_tarima = $regProdLote->tarima;
          $prodRefilado_serie = $regProdLote->serie;
          $prodRefilado_noop = $regProdLote->noop;
          $prodRefilado_longitud = $regProdLote->longitud;
          $prodRefilado_amplitud = $regProdLote->amplitud;
          $prodRefilado_peso = $regProdLote->peso;
          $prodRefilado_cdglote = $regProdLote->cdglote;
          $prodRefilado_numbobinas = $regProdLote->alpaso;          
          $prodRefilado_diseno = $regProdLote->diseno;
          $prodRefilado_impresion = $regProdLote->impresion;
          $prodRefilado_cdgimpresion = $regProdLote->cdgimpresion;
          $prodRefilado_sttlote = $regProdLote->sttlote;

          $error_longitud = false;
          $error_amplitud = false;
          $error_peso = false;

          for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
          { if (is_numeric($_POST['textLongitud'.$item]) AND ($_POST['textLongitud'.$item] >= 1000 AND $_POST['textLongitud'.$item] <= 9999))
            { $prodRefilado_newlongitud[$item] = $_POST['textLongitud'.$item]; }
            else
            { $error_longitud = true;              

              $item = $prodRefilado_numbobinas+1; }
          }

          if ($error_longitud == false)
          { for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
            { if (is_numeric($_POST['textAmplitud'.$item]))
              { $prodRefilado_newamplitud[$item] = $_POST['textAmplitud'.$item]; }
              else
              { $error_amplitud = true;                

                $item = $prodRefilado_numbobinas+1; }
            }

            if ($error_amplitud == false)
            { for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
              { if (is_numeric($_POST['textPeso'.$item]))
                { $prodRefilado_newpeso[$item] = $_POST['textPeso'.$item]; }
                else
                { $error_peso = true;                  

                  $item = $prodRefilado_numbobinas+1; }
              }
            }
          }

          if ($_POST['bttnSalvar'])
          { // Salvar
            $fchoperacion = date('Y-m-d');            

            if ($error_longitud == false)
            { if ($error_amplitud == false)
              { if ($error_peso == false)
                { if ($prodRefilado_sttlote == '1')
                  { $link->query("
                      INSERT INTO prodloteope
                        (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$prodRefilado_cdglote."', '30001', '".$prodRefilado_cdgempleado."', '".$prodRefilado_cdgmaquina."', '".$prodRefilado_longitud."', '".$prodRefilado_peso."', '".$fchoperacion."', NOW())");                
                  
                    for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
                    { $prodRefilado_cdgbobina = substr($prodRefilado_cdglote,0,8).$item.'000';

                      $link->query("
                        INSERT INTO prodbobina
                        (cdglote, bobina, cdgproducto, cdgproceso, longitud, amplitud, peso, cdgbobina, fchmovimiento)
                        VALUES
                        ('".$prodRefilado_cdglote."', '".$item."', '".$prodRefilado_cdgimpresion."', '30', '".$prodRefilado_newlongitud[$item]."', '".$prodRefilado_newamplitud[$item]."', '".$prodRefilado_newpeso[$item]."', '".$prodRefilado_cdgbobina."', NOW())");

                      $link->query("
                        INSERT INTO prodbobinaope
                          (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, longitudfin, peso, pesofin, fchoperacion, fchmovimiento)
                        VALUES
                          ('".$prodRefilado_cdgbobina."', '30001', '".$prodRefilado_cdgempleado."', '".$prodRefilado_cdgmaquina."', '".$prodRefilado_longitud."', '".$prodRefilado_newlongitud[$item]."', '".($prodRefilado_peso/$prodRefilado_numbobinas)."', '".$prodRefilado_newpeso[$item]."', '".$fchoperacion."', NOW())");

                      $msg_alert .= 'Refilado '.$item.' \n'; }

                    $link->query("
                    UPDATE prodlote
                       SET sttlote = '9',
                           fchmovimiento = NOW()
                     WHERE cdglote = '".$prodRefilado_cdglote."'");

                    if ($link->affected_rows > 0)
                    { $msg_alert .= 'Bobina afectada.'; }

                    $msg_modulo = '<a href="pdf/prodBobinasBC.php?cdglote='.$prodRefilado_cdglote.'" target="_blank">Generar etiquetas</a>'; 
                  } else
                  { // Actualiza la operación de refilado
                    if (substr($sistModulo_permiso,0,3) == 'rwx')
                    { for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
                      { $prodRefilado_cdgbobina = substr($prodRefilado_cdglote,0,8).$item.'000';

                        $prodBobinaSelect = $link->query("
                          SELECT * FROM prodbobina
                           WHERE cdgbobina = '".$prodRefilado_cdgbobina."'");

                        if ($prodBobinaSelect->num_rows > 0)
                        { $regProdBobina = $prodBobinaSelect->fetch_object();

                          $prodBobina_sttbobina = $regProdBobina->sttbobina;

                          $link->query("
                            UPDATE prodbobinaope
                               SET cdgempleado = '".$prodRefilado_cdgempleado."',
                                   cdgmaquina = '".$prodRefilado_cdgmaquina."',
                                   longitudfin = '".$prodRefilado_newlongitud[$item]."',
                                   pesofin = '".$prodRefilado_newpeso[$item]."'
                             WHERE cdgbobina = '".$prodRefilado_cdgbobina."' AND
                                   cdgoperacion = '30001'");

                          if ($link->affected_rows > 0) 
                          { // Actualiza la bobina
                              $link->query("
                                UPDATE prodbobina
                                   SET longitud = '".$prodRefilado_newlongitud[$item]."',
                                       amplitud = '".$prodRefilado_newamplitud[$item]."',
                                       peso = '".$prodRefilado_newpeso[$item]."'
                                 WHERE cdgbobina = '".$prodRefilado_cdgbobina."' AND
                                      (sttbobina = '1' OR sttbobina = '9')");
                            
                            if ($prodBobina_sttbobina == '9')
                            { // Actualiza la bobina en la operación de fusión
                              $link->query("
                                UPDATE prodbobinaope
                                   SET cdgempleado = '".$prodRefilado_cdgempleado."',
                                       cdgmaquina = '".$prodRefilado_cdgmaquina."',
                                       longitud = '".$prodRefilado_newlongitud[$item]."', 
                                       peso = '".$prodRefilado_newpeso[$item]."'                                       
                                 WHERE cdgbobina = '".$prodRefilado_cdgbobina."' AND
                                       cdgoperacion = '40001'"); }
                          }
                        } // Fin de la búsqueda de bobinas                        
                      }
                    } else
                    { $msg_alert .= 'La operación Refilado ya fue registrada en este lote, no tienes permisos para editar el registro.'; }

                    $msg_modulo = '<a href="pdf/prodBobinasBC.php?cdglote='.$prodRefilado_cdglote.'" target="_blank">Generar etiquetas</a>'; 
                  }

                  $prodRefilado_cdglote = '';                  
                } else
                { $msg_alert = 'Información de pesos, incorrecta.'; }
              } else
              { $msg_alert = 'Información de amplitudes, incorrecta.'; }
            } else
            { $msg_alert = 'Información de longitudes, incorrecta.'; }
          }
          // Fin del proceso de salvado
        } else
        { $prodRefilado_cdglote = '';
          $msg_alert = 'Información de bobina, incorrecta.'; }
      } else
      { $prodRefilado_cdgmaquina = '';
        $msg_alert = 'Información de máquina, incorrecta.'; }
    } else
    { $prodRefilado_cdgempleado = '';
      $msg_alert = 'Información de empleado, incorrecta.'; }

    if ($prodRefilado_cdgempleado == '' OR $prodRefilado_cdgmaquina == '' OR $prodRefilado_cdglote == '')
    { echo '
      <div class="bloque">
        <form id="formProdRefilado" name="formProdRefilado" method="POST" action=""/>
          <article class="subbloque">
            <label class="modulo_nombre">Registro del Refilado</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Operador</label><br/>
              <input type="text" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodRefilado_idempleado.'" required/>
            </article>

            <article>
              <label>Máquina</label><br/>
              <input type="text" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodRefilado_idmaquina.'" required/>
            </article>

            <article>
              <label>Lote</label><br/>
              <input type="text" id="textCdgLote" name="textCdgLote" value="'.$prodRefilado_cdglote.'" required/>
            </article>

            <article><br/>
              <input type="submit" id="bttnBuscar" name="bttnBuscar" value="Buscar" />
            </article>
          </section>
        </form>
      </div>'; 
    } else
    { echo '
      <form id="formProdRefilado" name="formProdRefilado" method="POST" action="prodRefilado.php"/>
        <div class="bloque">
          <input type="hidden" id="textCdgEmpleado" name="textCdgEmpleado" value="'.$prodRefilado_cdgempleado.'" />
          <input type="hidden" id="textCdgMaquina" name="textCdgMaquina" value="'.$prodRefilado_cdgmaquina.'" />
          <input type="hidden" id="textCdgLote" name="textCdgLote" value="'.$prodRefilado_cdglote.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Registro del Refilado</label>
          </article>
          <a href="prodRefilado.php">'.$_gearback.'</a>
          <label>NoOP <strong>'.$prodRefilado_serie.'.'.$prodRefilado_noop.'</strong></label>

          <section class="subbloque">
            <article>
              <label>Operador</label><br/>
              <label><b>'.$prodRefilado_empleado.'</b></label>
            </article><br/>

            <article>
              <label>Máquina</label><br/>
              <label><b>'.$prodRefilado_maquina.'</b></label>
            </article><br/>

            <article>
              <label>Lote</label><br/>
              <label><b>'.$prodRefilado_tarima.' | '.$prodRefilado_idlote.'</b> Ref. <b>'.$prodRefilado_lote.'</b></label>
            </article><br/>   

            <article>
              <label>Diseño</label><br/>
              <label><b>'.$prodRefilado_diseno.'</b></label>
            </article><br/>  

            <article>
              <label>Impresión</label><br/>
              <label><b>'.$prodRefilado_impresion.'</b></label>
            </article><br/> 

            <article>
              <label>Longitud</label><br/>
              <label><strong>'.number_format($prodRefilado_longitud,2).'</strong> metros</label>
            </article>

            <article>
              <label>Ancho del sustrato</label><br/>
              <label><strong>'.number_format($prodRefilado_amplitud).' </strong> milímetros</label>
            </article>

            <article>
              <label>Peso</label><br/>
              <label><strong>'.number_format($prodRefilado_peso,3).'</strong> kilos</label>
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </div>';
      
      if ($prodRefilado_numbobinas > 0)
      { for ($item = 1; $item <= $prodRefilado_numbobinas; $item++)
        { echo '
        <div class="bloque">
          <article class="modulo_listado">
            <label class="modulo_nombre">Información de la bobina '.$item.'</label>
          </article>

          <section class="subbloque">
            <article>
              <label>Longitud en metros</label><br />
              <input type="text" id="textLongitud'.$item.'" name="textLongitud'.$item.'" value="'.$prodRefilado_newlongitud[$item].'" placeholder="metros" required/>
            </article>

            <article>
              <label>Amplitud en milímetros</label><br />
              <input type="text" id="textAmplitud'.$item.'" name="textAmplitud'.$item.'" value="'.$prodRefilado_newamplitud[$item].'" placeholder="milímetros" required/>
            </article>

            <article>
              <label>Peso en kilos</label><br/>
              <input type="text" id="textPeso'.$item.'" name="textPeso'.$item.'" value="'.$prodRefilado_newpeso[$item].'" placeholder="kilos" required/>
            </article>
          </section>
        </div>'; }
      }

      echo '        
      </form>'; }

    if ($msg_modulo != '')
    { echo '
      <div align="center"><strong>'.$msg_modulo.'</strong></div>'; }
      
    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  ?>
    </div>
  </body>
</html>
