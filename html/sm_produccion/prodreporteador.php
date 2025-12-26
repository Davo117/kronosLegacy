<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Merma</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Termoencogible</h1></label>
      </section><?php 

  include '../datos/mysql.php'; 
  $link = conectar();

  m3nu_produccion();  

  if ($_SESSION['cdgusuario'])
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    ma1n(); }

  $prodTablero_dsdano = date("Y");
  $prodTablero_dsdmes = date("n");
  $prodTablero_dsddia = date("j");  

  // Cierre de lotes contra empaque  
  $link = conectar();
  $prodLoteSelect = $link->query("
    SELECT SUBSTRING(cdglote,1,8) AS cdglote 
      FROM prodlote
     WHERE fchprograma >= '".($prodTablero_dsdano-2)."-".$prodTablero_dsdmes."-".$prodTablero_dsddia."' AND empacado <= 0");

  if ($prodLoteSelect->num_rows > 0)
  { while ($regProdLote = $prodLoteSelect->fetch_object())
    { // Calcular los millares programados de acuerdo con el juego de cilindros empleado para realizar la impresión
      $prodLotesSelect = $link->query("
        SELECT ((prodloteope.longitud*pdtojuego.alpaso)/pdtojuego.altura) AS programado
          FROM prodlote,
               prodloteope,
               pdtojuego
         WHERE prodlote.cdglote = prodloteope.cdglote AND 
               prodloteope.cdgoperacion = '20001' AND 
               pdtojuego.cdgjuego = prodloteope.cdgjuego AND
     SUBSTRING(prodlote.cdglote,1,8) = '".$regProdLote->cdglote."'");

      $prodLote_programado = 0;
      if ($prodLotesSelect->num_rows > 0)
      { $regProdLotes = $prodLotesSelect->fetch_object();

        $prodLote_programado = number_format($regProdLotes->programado,3,'.',''); }
      // Final del calculo

      // Busca rollos activos
      $prodRolloSelect = $link->query("
        SELECT * FROM prodrollo
         WHERE SUBSTRING(cdgrollo,1,8) = '".$regProdLote->cdglote."' AND
              (sttrollo = '1' OR
               sttrollo = '6' OR
               sttrollo = '7')");

      if ($prodRolloSelect->num_rows > 0)
      { // Se encontraron rollos aun activos
        // Este lote aun esta inconcluso
      } else
      { // Calcula los millares empacados como rollo
        $prodRolloSelect = $link->query("
        SELECT MAX(fchempaque) AS fchempaque, 
               SUM(cantidad) AS cantidad 
          FROM alptempaque,
               alptempaquer
         WHERE alptempaque.cdgempaque = alptempaquer.cdgempaque AND
     SUBSTRING(cdgrollo,1,8) = '".$regProdLote->cdglote."' AND 
        LENGTH(cdgrollo) = 12");

        if ($prodRolloSelect->num_rows > 0)
        { $regProdRollo = $prodRolloSelect->fetch_object();

          $prodLote_fchcierre = $regProdRollo->fchempaque;
          $prodLote_empacado = number_format($regProdRollo->cantidad,3,'.',''); }
        ////////////////

        $prodRolloSelect = $link->query("
        SELECT * FROM prodrollo
         WHERE SUBSTRING(cdgrollo,1,8) = '".$regProdLote->cdglote."' AND
               sttrollo = '5'");

        if ($prodRolloSelect->num_rows > 0)
        { // Busca paquetes activos
          $prodPaqueteSelect = $link->query("
            SELECT * FROM prodpaquete
             WHERE SUBSTRING(cdgpaquete,1,8) = '".$regProdLote->cdglote."' AND
                   sttpaquete = '1'");

          if ($prodPaqueteSelect->num_rows > 0)
          { // Se encontraron rollos aun activos
            // Este lote aun esta inconcluso
          } else
          { // Calcula los millares empacados como paquetes
            $prodPaqueteSelect = $link->query("
            SELECT MAX(fchempaque) AS fchempaque, 
                   SUM(cantidad) AS cantidad 
              FROM alptempaque,
                   alptempaquep
             WHERE alptempaque.cdgempaque = alptempaquep.cdgempaque AND
         SUBSTRING(cdgpaquete,1,8) = '".$regProdLote->cdglote."' AND 
            LENGTH(cdgpaquete) = 12");

            if ($prodPaqueteSelect->num_rows > 0)
            { $regProdPaquete = $prodPaqueteSelect->fetch_object();

              if ($prodLote_fchcierre < $regProdPaquete->fchempaque)
              { $prodLote_fchcierre = $regProdPaquete->fchempaque; }

              $prodLote_empacado += $regProdPaquete->cantidad; }
            ////////////////

              if ($prodLote_programado > 0)
              { // Cierra el lote
                $link->query("
                  UPDATE prodlote
                     SET programado = '".$prodLote_programado."',
                         fchcierre = '".$prodLote_fchcierre."',
                         empacado = '".$prodLote_empacado."'
                   WHERE SUBSTRING(cdglote,1,8) = '".$regProdLote->cdglote."'"); }
          }
        } else
        { if ($prodLote_programado > 0)
          { // Cierra el lote
            $link->query("
              UPDATE prodlote
                 SET programado = '".$prodLote_programado."',
                     fchcierre = '".$prodLote_fchcierre."',
                     empacado = '".$prodLote_empacado."'
               WHERE SUBSTRING(cdglote,1,8) = '".$regProdLote->cdglote."'"); }
        }
      }
    }
  }

  //////////////////////////////////////////  

  if ($_POST[dateinicial]) 
  { $rptdateinicial = $_POST[dateinicial]; 
  } else
  { $rptdateinicial = date("Y-m-d"); }

  if ($_POST[datefinal]) 
  { $rptdatefinal = $_POST[datefinal]; 
  } else
  { $rptdatefinal = date("Y-m-d"); }
  
  if ($_POST[selectproducto]) { $rptproducto = $_POST[selectproducto]; }

  if ($rptdateinicial != '' AND $rptdatefinal != '')
  { // Impresión
    $queryimpresioninside = $link->query("
        SELECT SUM(prodloteope.longitud) AS inside,
             SUM(((prodloteope.longitud/pdtodiseno.alto)*pdtodiseno.alpaso)) AS insidep,
               SUM(prodloteope.longitudfin) AS outside,
             SUM(((prodloteope.longitudfin/pdtodiseno.alto)*pdtodiseno.alpaso)) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodlote, 
               prodloteope
        WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
              (prodlote.cdglote = prodloteope.cdglote AND
               prodloteope.cdgoperacion = '20001') AND
              (prodloteope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."')");

    if ($queryimpresioninside->num_rows > 0)
    { $regimpresioninside = $queryimpresioninside->fetch_object();
        
      $impresion_inside = $regimpresioninside->inside; 
      $impresion_insidep = $regimpresioninside->insidep;
      $impresion_outside = $regimpresioninside->outside; 
      $impresion_outsidep = $regimpresioninside->outsidep;

      $queryimpresionesinside = $link->query("
        SELECT prodlote.cdgproducto,
           SUM(prodloteope.longitud) AS inside,
         SUM(((prodloteope.longitud/pdtodiseno.alto)*pdtodiseno.alpaso)) AS insidep,
           SUM(prodloteope.longitudfin) AS outside,
         SUM(((prodloteope.longitudfin/pdtodiseno.alto)*pdtodiseno.alpaso)) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodlote, 
               prodloteope
        WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
              (prodlote.cdglote = prodloteope.cdglote AND                
               prodloteope.cdgoperacion = '20001') AND
              (prodloteope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."')
      GROUP BY prodlote.cdgproducto");

      if ($queryimpresionesinside->num_rows > 0)
      { while ($regimpresioninside = $queryimpresionesinside->fetch_object())
        { $impresiones_inside[$regimpresioninside->cdgproducto] = $regimpresioninside->inside; 
          $impresiones_insidep[$regimpresioninside->cdgproducto] = $regimpresioninside->insidep;
          $impresiones_outside[$regimpresioninside->cdgproducto] = $regimpresioninside->outside; 
          $impresiones_outsidep[$regimpresioninside->cdgproducto] = $regimpresioninside->outsidep; }
      }  
    }

    // Refile
    $queryrefileinside = $link->query("
      SELECT SUM(prodlote.longitud) AS inside,
             SUM(prodlote.longitud/pdtodiseno.alto) AS insidep
        FROM pdtodiseno,
             pdtoimpresion,
             prodlote,
             prodbobina,
             prodbobinaope
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodlote.cdgproducto AND
            (prodlote.cdglote = prodbobina.cdglote AND
             prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
             prodbobinaope.cdgoperacion = '30001') AND
             prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

    if ($queryrefileinside->num_rows > 0)
    { $regrefileinside = $queryrefileinside->fetch_object();
        
      $refile_inside = $regrefileinside->inside; 
      $refile_insidep = $regrefileinside->insidep;

      $queryrefileoutside = $link->query("
        SELECT SUM(prodbobina.longitud) AS outside,
               SUM(prodbobina.longitud/pdtodiseno.alto) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodlote,
               prodbobina,
               prodbobinaope
         WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodlote.cdgproducto AND
              (prodlote.cdglote = prodbobina.cdglote AND
               prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
               prodbobinaope.cdgoperacion = '30001') AND
               prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

      if ($queryrefileoutside->num_rows > 0)
      { $regrefileoutside = $queryrefileoutside->fetch_object();
        
        $refile_outside = $regrefileoutside->outside; 
        $refile_outsidep = $regrefileoutside->outsidep;

        $queryrefilesinside = $link->query("
          SELECT prodbobina.cdgproducto,
                 SUM(prodlote.longitud) AS inside,
                 SUM(((prodlote.longitud/pdtodiseno.alto)*pdtodiseno.alpaso)) AS insidep
            FROM pdtodiseno,
                 pdtoimpresion,
                 prodlote,
                 prodbobina,
                 prodbobinaope
           WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                 pdtoimpresion.cdgimpresion = prodlote.cdgproducto AND
                 prodlote.cdglote = prodbobina.cdglote AND
                 prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgoperacion = '30001' AND
                 prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'
        GROUP BY prodbobina.cdgproducto");

        if ($queryrefilesinside->num_rows > 0)
        { while ($regrefilesinside = $queryrefilesinside->fetch_object())
          { $refiles_inside[$regrefilesinside->cdgproducto] = $regrefilesinside->inside; 
            $refiles_insidep[$regrefilesinside->cdgproducto] = $regrefilesinside->insidep; }

          $queryrefilesoutside = $link->query("
            SELECT prodbobina.cdgproducto,
                   SUM(prodbobina.longitud) AS outside,
                   SUM((prodbobina.longitud/pdtodiseno.alto)*pdtodiseno.alpaso) AS outsidep
              FROM pdtodiseno,
                   pdtoimpresion,
                   prodlote,
                   prodbobina,
                   prodbobinaope
             WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                   pdtoimpresion.cdgimpresion = prodlote.cdgproducto AND
                   prodlote.cdglote = prodbobina.cdglote AND
                   prodbobina.sttbobina != 'C' AND
                   prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                   prodbobinaope.cdgoperacion = '30001' AND
                   prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'
          GROUP BY prodlote.cdgproducto");

          if ($queryrefilesoutside->num_rows > 0)
          { while ($regrefilesoutside = $queryrefilesoutside->fetch_object())
            { $refiles_outside[$regrefilesoutside->cdgproducto] = $regrefilesoutside->outside; 
              $refiles_outsidep[$regrefilesoutside->cdgproducto] = $regrefilesoutside->outsidep; }
          }
        } 
      }
    }

    // Fusión
    $queryfusioninside = $link->query("
      SELECT SUM(prodbobina.longitud) AS inside,
             SUM(prodbobina.longitud/pdtodiseno.alto) AS insidep
        FROM pdtodiseno,
             pdtoimpresion,
             prodbobina,
             prodbobinaope
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodbobina.cdgproducto AND
             prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
             prodbobinaope.cdgoperacion = '40001' AND
             prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

    if ($queryfusioninside->num_rows > 0)
    { $regfusioninside = $queryfusioninside->fetch_object();
        
      $fusion_inside = $regfusioninside->inside; 
      $fusion_insidep = $regfusioninside->insidep;

      $queryfusionoutside = $link->query("
        SELECT SUM(prodrollo.longitud) AS outside,
               SUM(prodrollo.longitud/pdtodiseno.alto) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodbobina,
               prodrollo,
               prodrolloope
         WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodbobina.cdgproducto AND
               prodbobina.cdgbobina = prodrollo.cdgbobina AND
               prodrollo.cdgrollo = prodrolloope.cdgrollo AND
               prodrolloope.cdgoperacion = '40001' AND
               prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

      if ($queryfusionoutside->num_rows > 0)
      { $regfusionoutside = $queryfusionoutside->fetch_object();
        
        $fusion_outside = $regfusionoutside->outside; 
        $fusion_outsidep = $regfusionoutside->outsidep;

        $queryfusionesinside = $link->query("
          SELECT prodbobina.cdgproducto,
            SUM(prodbobina.longitud) AS inside,
             SUM(prodbobina.longitud/pdtodiseno.alto) AS insidep
        FROM pdtodiseno,
             pdtoimpresion,
             prodbobina,
             prodbobinaope
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodbobina.cdgproducto AND
             prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
             prodbobinaope.cdgoperacion = '40001' AND
             prodbobinaope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'
    GROUP BY prodbobina.cdgproducto");

        if ($queryfusionesinside->num_rows > 0)
        { while ($regfusionesinside = $queryfusionesinside->fetch_object())
          { $fusiones_inside[$regfusionesinside->cdgproducto] = $regfusionesinside->inside; 
            $fusiones_insidep[$regfusionesinside->cdgproducto] = $regfusionesinside->insidep; }

          $queryfusionesoutside = $link->query("
            SELECT prodrollo.cdgproducto,
               SUM(prodrollo.longitud) AS outside,
               SUM(prodrollo.longitud/pdtodiseno.alto) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodbobina,
               prodrollo,
               prodrolloope
         WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodbobina.cdgproducto AND
               prodbobina.cdgbobina = prodrollo.cdgbobina AND
               prodrollo.cdgrollo = prodrolloope.cdgrollo AND
               prodrolloope.cdgoperacion = '40001' AND
               prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'
      GROUP BY prodrollo.cdgproducto");

          if ($queryfusionesoutside->num_rows > 0)
          { while ($regfusionesoutside = $queryfusionesoutside->fetch_object())
            { $fusiones_outside[$regfusionesoutside->cdgproducto] = $regfusionesoutside->outside; 
              $fusiones_outsidep[$regfusionesoutside->cdgproducto] = $regfusionesoutside->outsidep; }
          }
        }
      }
    }

    // Revisión
    $queryrevision = $link->query("
      SELECT SUM(prodrolloope.longitud) AS inside,
             SUM(prodrolloope.longitudfin) AS outside,
             SUM(prodrolloope.longitud/pdtodiseno.alto) AS insidep,
             SUM(prodrolloope.longitudfin/pdtodiseno.alto) AS outsidep
        FROM pdtodiseno,
             pdtoimpresion,
             prodrollo,
             prodrolloope
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodrollo.cdgproducto AND
             prodrollo.cdgrollo = prodrolloope.cdgrollo AND
             prodrolloope.cdgoperacion = '40006' AND
             prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

    if ($queryrevision->num_rows > 0)
    { $regrevision = $queryrevision->fetch_object();
        
      $revision_inside = $regrevision->inside; 
      $revision_insidep = $regrevision->insidep;

      $revision_outside = $regrevision->outside; 
      $revision_outsidep = $regrevision->outsidep;      
    
      $queryrevisiones = $link->query("
        SELECT prodrollo.cdgproducto,
               SUM(prodrolloope.longitud) AS inside,
               SUM(prodrolloope.longitudfin) AS outside,
               SUM(prodrolloope.longitud/pdtodiseno.alto) AS insidep,
               SUM(prodrolloope.longitudfin/pdtodiseno.alto) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion,
               prodrollo,
               prodrolloope
         WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodrollo.cdgproducto AND
               prodrollo.cdgrollo = prodrolloope.cdgrollo AND
               prodrolloope.cdgoperacion = '40006' AND
               prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'
      GROUP BY prodrollo.cdgproducto");

      if ($queryrevisiones->num_rows > 0)
      { while ($regrevisiones = $queryrevisiones->fetch_object())
        { $revisiones_inside[$regrevisiones->cdgproducto] = $regrevisiones->inside; 
          $revisiones_insidep[$regrevisiones->cdgproducto] = $regrevisiones->insidep;

          $revisiones_outside[$regrevisiones->cdgproducto] = $regrevisiones->outside; 
          $revisiones_outsidep[$regrevisiones->cdgproducto] = $regrevisiones->outsidep; }
      }
    }

    // Corte
    $querycorteinside = $link->query("
      SELECT SUM(prodrolloope.longitud) AS inside,
             SUM(prodrolloope.longitud/pdtodiseno.alto) AS insidep
        FROM pdtodiseno,
             pdtoimpresion,
             prodrollo,
             prodrolloope
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodrollo.cdgproducto AND
             prodrollo.cdgrollo = prodrolloope.cdgrollo AND
             prodrolloope.cdgoperacion = '50001' AND
             prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'");

    if ($querycorteinside->num_rows > 0)
    { $regcorteinside = $querycorteinside->fetch_object();
        
      $corte_inside = $regcorteinside->inside; 
      $corte_insidep = $regcorteinside->insidep;

      $querycorteoutside = $link->query("
        SELECT SUM(prodpaquete.cantidad) AS outsidep
          FROM pdtodiseno,
               pdtoimpresion, 
               prodrollo, 
               prodrolloope,
               prodpaquete
         WHERE prodpaquete.cdgrollo = prodrollo.cdgrollo AND
               prodrolloope.cdgrollo = prodrollo.cdgrollo AND 
               prodpaquete.sttpaquete != 'C' AND
              (prodrolloope.cdgoperacion = '50001' AND 
               pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto) AND
               prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."'"); 

      if ($querycorteoutside->num_rows > 0)
      { $regcorteoutside = $querycorteoutside->fetch_object();

        $corte_outside = $regcorteoutside->outside;
        $corte_outsidep = $regcorteoutside->outsidep;

        $querycortesinside = $link->query("
          SELECT prodrollo.cdgproducto,
             SUM(prodrolloope.longitud) AS inside,
             SUM(prodrolloope.longitud/pdtodiseno.alto) AS insidep
            FROM pdtodiseno,
                 pdtoimpresion,
                 prodrollo,
                 prodrolloope
           WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                 pdtoimpresion.cdgimpresion = prodrollo.cdgproducto AND
                 prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgoperacion = '50001' AND
                (prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."')
        GROUP BY prodrollo.cdgproducto");

        if ($querycortesinside->num_rows > 0)
        { while ($regcortesinside = $querycortesinside->fetch_object())
          { $cortes_inside[$regcortesinside->cdgproducto] = $regcortesinside->inside; 
            $cortes_insidep[$regcortesinside->cdgproducto] = $regcortesinside->insidep; }

          $querycortesoutside = $link->query("
            SELECT prodpaquete.cdgproducto,
               SUM(prodpaquete.cantidad) AS outsidep
              FROM pdtodiseno,
                   pdtoimpresion, 
                   prodrollo, 
                   prodrolloope,
                   prodpaquete
             WHERE prodpaquete.cdgrollo = prodrollo.cdgrollo AND
                   prodrolloope.cdgrollo = prodrollo.cdgrollo AND 
                   prodpaquete.sttpaquete != 'C' AND
                  (prodrolloope.cdgoperacion = '50001' AND 
                   pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                   pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto) AND
                  (prodrolloope.fchoperacion BETWEEN '".$rptdateinicial."' AND '".$rptdatefinal."')
          GROUP BY prodpaquete.cdgproducto"); 

          if ($querycortesoutside->num_rows > 0)
          { while ($regcortesoutside = $querycortesoutside->fetch_object())
            { $cortes_outside[$regcortesoutside->cdgproducto] = $regcortesoutside->outside; 
              $cortes_outsidep[$regcortesoutside->cdgproducto] = $regcortesoutside->outsidep; }
          }
        }
      }
    }
  }

  echo '
      <form id="reporteador" method="POST" action="prodreporteador.php">
        <section class="dates">
          <article>
            <label>Fecha inicial</label><br>
            <input type="date" id="dateinicial" name="dateinicial" value="'.$rptdateinicial.'" required />
          </article>
          <article>
            <label>Fecha final</label><br>
            <input type="date" id="datefinal" name="datefinal" value="'.$rptdatefinal.'" required />
          </article>
          <article>
            <input type="submit" id="filtrar" />
          </article>
        </section>';

  $anoIndicador = (date("Y")-2);
  $mesIndicador = date("n");

  $mesNombre[1] = "Enero";
  $mesNombre[2] = "Febrero";
  $mesNombre[3] = "Marzo";
  $mesNombre[4] = "Abril";
  $mesNombre[5] = "Mayo";
  $mesNombre[6] = "Junio";
  $mesNombre[7] = "Julio";
  $mesNombre[8] = "Agosto";
  $mesNombre[9] = "Septiembre";
  $mesNombre[10] = "Octubre";
  $mesNombre[11] = "Noviembre";
  $mesNombre[12] = "Diciembre";

  $link = conectar();

  $prodIndicador = $link->query("
    SELECT SUBSTRING(fchcierre,1,4) AS ano,
           SUBSTRING(fchcierre,6,2) AS mes,
           SUM(programado) AS programado,
           SUM(empacado) AS empacado,
          (100-((SUM(empacado)*100)/SUM(programado))) AS merma
      FROM prodlote
     WHERE empacado > 0
  GROUP BY SUBSTRING(fchcierre,1,7)");
  echo "
    SELECT SUBSTRING(fchcierre,1,4) AS ano,
           SUBSTRING(fchcierre,6,2) AS mes,
           SUM(programado) AS programado,
           SUM(empacado) AS empacado,
          (100-((SUM(empacado)*100)/SUM(programado))) AS merma
      FROM prodlote
     WHERE empacado > 0
  GROUP BY SUBSTRING(fchcierre,1,7)";

  if ($prodIndicador->num_rows > 0)
  { while ($regProdIndicador = $prodIndicador->fetch_object())
    { $indicador_Programado[$regProdIndicador->ano][($regProdIndicador->mes+0)] = $regProdIndicador->programado;
      $indicador_Empacado[$regProdIndicador->ano][($regProdIndicador->mes+0)] = $regProdIndicador->empacado;
      $indicador_Merma[$regProdIndicador->ano][($regProdIndicador->mes+0)] = 100-(($regProdIndicador->empacado*100)/$regProdIndicador->programado);
    }
  }

  echo '
        <section class="mermagbloque">
          <label><b>Indicador</b></label><br>';

  for ($item=1; $item <= 25; $item++)
  { echo '
          <article class="mermagproceso">
            <b>'.$mesNombre[$mesIndicador].'</b> <i>'.($anoIndicador).'</i><br>
            <b>In</b> '.number_format($indicador_Programado[$anoIndicador][$mesIndicador],3).' mlls<br>
            <b>Out</b> '.number_format($indicador_Empacado[$anoIndicador][$mesIndicador],3).' mlls<br>
            <label>'.number_format($indicador_Merma[$anoIndicador][$mesIndicador],2).' %</label>
          </article>';

    $mesIndicador++;
    if ($mesIndicador == 13)
    { $mesIndicador = 1; 
      $anoIndicador++; }
  }

  echo '
        <section>

        <section class="mermagbloque">
          <article><label><b><a href="pdf/prodreporte.php?fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Merma general</a></b></label></article><br>
          <article class="mermagproceso">';

  if ($impresion_outsidep > 0)
  { echo '<a href="pdf/prodreporte.php?cdgoperacion=20001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Impresión</a><br><b>In</b> '.number_format($impresion_insidep,3,'.','').'<br><b>Out</b> '.number_format($impresion_outsidep,3,'.','').'<br>
            <label>'.number_format((100-(($impresion_outsidep*100)/$impresion_insidep)),2,'.','').' %</label>'; }
  else 
  { echo 'Impresión<br>
            <label>&nbsp;</label>'; }

  echo '</article>
          <article class="mermagproceso">';

  if ($refile_outsidep > 0)
  { echo '<a href="pdf/prodreporte.php?cdgoperacion=30001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Refilado</a><br><b>In</b> '.number_format($refile_insidep,3,'.','').'<br><b>Out</b> '.number_format($refile_outsidep,3,'.','').'<br>
            <label>'.number_format((100-(($refile_outsidep*100)/$refile_insidep)),2,'.','').' %</label>'; }
  else 
  { echo 'Refilado<br><label>&nbsp;</label>'; }

  echo '</article>
          <article class="mermagproceso">';

  if ($fusion_outsidep > 0)
  { echo '<a href="pdf/prodreporte.php?cdgoperacion=40001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Fusión</a><br><b>In</b> '.number_format($fusion_insidep,3,'.','').'<br><b>Out</b> '.number_format($fusion_outsidep,3,'.','').'<br>
            <label>'.number_format((100-(($fusion_outsidep*100)/$fusion_insidep)),2,'.','').' %</label>'; }
  else 
  { echo 'Fusión<br><label>&nbsp;</label>'; }

  echo '</article>
          <article class="mermagproceso">';

  if ($revision_outsidep > 0)
  { echo '<a href="pdf/prodreporte.php?cdgoperacion=40006&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Revisión</a><br><b>In</b> '.number_format($revision_insidep,3,'.','').'<br><b>Out</b> '.number_format($revision_outsidep,3,'.','').'<br>
            <label>'.number_format((100-(($revision_outsidep*100)/$revision_insidep)),2,'.','').' %</label>'; }
  else 
  { echo 'Revisión<br><label>&nbsp;</label>'; }

  echo '</article>
          <article class="mermagproceso">';

  if ($corte_outsidep > 0)
  { echo '<a href="pdf/prodreporte.php?cdgoperacion=50001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">Corte</a><br><b>In</b> '.number_format($corte_insidep,3,'.','').'<br><b>Out</b> '.number_format($corte_outsidep,3,'.','').'<br>
            <label>'.number_format((100-(($corte_outsidep*100)/$corte_insidep)),2,'.','').' %</label>'; }
  else 
  { echo 'Corte<br><label>&nbsp;</label>'; }

  echo '</article>
        </section>';

  if ($rptdateinicial != '' AND $rptdatefinal != '')
  { $queryprodimpresion = $link->query("
      SELECT cdgimpresion AS cdgproducto,
             impresion AS producto
        FROM pdtoimpresion
    ORDER BY impresion");

    if ($queryprodimpresion->num_rows > 0)
    { while ($regproducto = $queryprodimpresion->fetch_object())
      { if ($fusiones_outsidep[$regproducto->cdgproducto] > 0)
        { $fusiones_merma[$regproducto->cdgproducto] = number_format((100-(($fusiones_outsidep[$regproducto->cdgproducto]*100)/$fusiones_insidep[$regproducto->cdgproducto])),2,'.','').'%'; }
        else { $fusiones_merma[$regproducto->cdgproducto] = '&nbsp;'; }

        if ($revisiones_outsidep[$regproducto->cdgproducto] > 0)
        { $revisiones_merma[$regproducto->cdgproducto] = number_format((100-(($revisiones_outsidep[$regproducto->cdgproducto]*100)/$revisiones_insidep[$regproducto->cdgproducto])),2,'.','').'%'; }
        else { $revisiones_merma[$regproducto->cdgproducto] = '&nbsp;'; }

        if ($cortes_outsidep[$regproducto->cdgproducto] > 0)
        { $cortes_merma[$regproducto->cdgproducto] = number_format((100-(($cortes_outsidep[$regproducto->cdgproducto]*100)/$cortes_insidep[$regproducto->cdgproducto])),2,'.','').'%'; }
        else { $cortes_merma[$regproducto->cdgproducto] = '&nbsp;'; }

        if ($impresiones_outsidep[$regproducto->cdgproducto] > 0 OR $refiles_outsidep[$regproducto->cdgproducto] > 0 OR $fusiones_outsidep[$regproducto->cdgproducto] > 0 OR $revisiones_outsidep[$regproducto->cdgproducto] > 0 OR $cortes_outsidep[$regproducto->cdgproducto])
        { echo '
        <section class="mermabloque">
          <article><label><i><a href="pdf/prodreporte.php?cdgproducto='.$regproducto->cdgproducto.'&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">'.$regproducto->producto.'</a></i></label></article><br>
          <article class="mermaproceso">';

          if ($impresiones_outsidep[$regproducto->cdgproducto] > 0)
          { echo '
            <a href="pdf/prodreporte.php?cdgproducto='.$regproducto->cdgproducto.'&cdgoperacion=20001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">'.utf8_decode('Impresión').'</a><br>
            <label>'.number_format((100-(($impresiones_outsidep[$regproducto->cdgproducto]*100)/$impresiones_insidep[$regproducto->cdgproducto])),2,'.','').'</label>%'; }
          else 
          { echo '
            '.utf8_decode('Impresión').'<br>
            <label>&nbsp;</label>'; }

          echo '</article>
            <article class="mermaproceso">';          
          if ($refiles_outsidep[$regproducto->cdgproducto] > 0)
          { echo '
            <a href="pdf/prodreporte.php?cdgproducto='.$regproducto->cdgproducto.'&cdgoperacion=30001&fchini='.$rptdateinicial.'&fchfin='.$rptdatefinal.'" target="_blank">'.utf8_decode('Refilado').'</a><br>
            <label>'.number_format((100-(($refiles_outsidep[$regproducto->cdgproducto]*100)/$refiles_insidep[$regproducto->cdgproducto])),2,'.','').'</label>%'; }
          else 
          { echo '
            '.utf8_decode('Refilado').'<br>
            <label>&nbsp;</label>'; }

          echo '</article>
          <article class="mermaproceso">'.utf8_decode('Fusión').'<br>
            <label>'.$fusiones_merma[$regproducto->cdgproducto].'</label></article>
          <article class="mermaproceso">'.utf8_decode('Revisión').'<br>
            <label>'.$revisiones_merma[$regproducto->cdgproducto].'</label></article>
          <article class="mermaproceso">'.utf8_decode('Corte').'<br>
            <label>'.$cortes_merma[$regproducto->cdgproducto].'</label></article>
        </section>'; }
      }
    }

  }
?>
      </form>
    </div>
  </body>
</html>
