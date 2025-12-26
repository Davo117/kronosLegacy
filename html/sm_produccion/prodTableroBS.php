<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Tablero de Control</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="tablero">      
      <section>
        <a href="ayudaBS.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>
        <Label><h1>Banda de Seguridad</h1></label>
      </section><?php

  include '../datos/mysql.php';
  
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '6TCBS';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }
  }

  $pdtoBandaSelect = $link->query("
    SELECT * FROM pdtobanda
     WHERE sttbanda = '1'
  ORDER BY banda");

  if ($pdtoBandaSelect->num_rows > 0)
  { $item = 0;

    while ($regPdtoBanda = $pdtoBandaSelect->fetch_object())
    { $item++;

      $prodTablero_banda[$item] = $regPdtoBanda->banda;
      $prodTablero_cdgbanda[$item] = $regPdtoBanda->cdgbanda;

      $pdtoBandaPSelect = $link->query("
        SELECT * FROM pdtobandap
         WHERE cdgbanda = '".$regPdtoBanda->cdgbanda."' AND
               sttbandap = '1'
      ORDER BY bandap");

      if ($pdtoBandaPSelect->num_rows > 0)
      { $subItem = 0;
        while ($regPdtoBandaP = $pdtoBandaPSelect->fetch_object())
        { $subItem++;

          $prodTablero_bandap[$item][$subItem] = $regPdtoBandaP->bandap;
          $prodTablero_preembosado[$item][$subItem] = $regPdtoBandaP->preembosado;
          $prodTablero_cdgbandap[$item][$subItem] = $regPdtoBandaP->cdgbandap;

          if ($regPdtoBandaP->preembosado == '0')
          { // Calcula lo que esta programado para embosar
            $prodLoteOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((pdtosustrato.anchura/pdtobanda.anchura)*prodloteope.longitudfin) AS metros
                FROM pdtobanda,
                     pdtobandap,
                     pdtosustrato,
                     prodlote,
                     prodloteope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
                     pdtobandap.preembosado = '0') AND
                    (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                     prodloteope.cdglote = prodlote.cdglote AND
                     prodloteope.cdgoperacion = '10001' AND
                     prodlote.sttlote = 'A')");

            if ($prodLoteOpeSelect->num_rows > 0)
            { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object();

              $infoTablero_progembosado[$regPdtoBandaP->cdgbandap] = $regProdLoteOpe->metros; }

            // Calcula lo que esta embosado
            $prodLoteOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((pdtosustrato.anchura/pdtobanda.anchura)*prodloteope.longitudfin) AS metros
                FROM pdtobanda,
                     pdtobandap,
                     pdtosustrato,
                     prodlote,
                     prodloteope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
                     pdtobandap.preembosado = '0') AND
                    (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                     prodloteope.cdglote = prodlote.cdglote AND
                     prodloteope.cdgoperacion = '20011' AND
                     prodlote.sttlote = '1')");

            if ($prodLoteOpeSelect->num_rows > 0)
            { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object();

              $infoTablero_embosado[$regPdtoBandaP->cdgbandap] = $regProdLoteOpe->metros; }

            // Calcula lo que esta laminado de lo embosado
            $prodLoteOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((pdtosustrato.anchura/pdtobanda.anchura)*prodloteope.longitudfin) AS metros
                FROM pdtobanda,
                     pdtobandap,
                     pdtosustrato,
                     prodlote,
                     prodloteope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
                     pdtobandap.preembosado = '0') AND
                    (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                     prodloteope.cdglote = prodlote.cdglote AND
                     prodloteope.cdgoperacion = '30015' AND
                     prodlote.sttlote = '5')");

            if ($prodLoteOpeSelect->num_rows > 0)
            { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object();

              $infoTablero_laminado[$regPdtoBandaP->cdgbandap] = $regProdLoteOpe->metros; }
          } else
          { // Calcula lo que esta programado para refilar
            $prodLoteOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((pdtosustrato.anchura/pdtobanda.anchura)*prodloteope.longitudfin) AS outSide
                FROM pdtobanda,
                     pdtobandap,
                     pdtosustrato,
                     prodlote,
                     prodloteope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtosustrato.cdgsustrato = pdtobandap.cdgsustrato AND
                     pdtobandap.preembosado = '1') AND
                    (prodlote.cdgproducto = pdtobandap.cdgbandap AND
                     prodloteope.cdglote = prodlote.cdglote AND
                     prodloteope.cdgoperacion = '10001' AND
                     prodlote.sttlote = 'A')");

            if ($prodLoteOpeSelect->num_rows > 0)
            { $regProdLoteOpe = $prodLoteOpeSelect->fetch_object();

              $infoTablero_progrefiladoOut[$regPdtoBandaP->cdgbandap] = $regProdLoteOpe->outSide; }

            // Calcula Scrap de lo que se ha refilado
            $prodBobinaOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM(prodbobinaope.amplitud*prodbobinaope.longitud) AS inSide,
                SUM(prodbobinaope.amplitudfin*prodbobinaope.longitudfin) AS outSide
                FROM pdtobanda,
                     pdtobandap,
                     prodbobina,
                     prodbobinaope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtobandap.preembosado = '1') AND
                    (prodbobina.cdgproducto = pdtobandap.cdgbandap AND
                     prodbobinaope.cdgbobina = prodbobina.cdgbobina AND
                     prodbobinaope.cdgoperacion = '30011')");

            if ($prodBobinaOpeSelect->num_rows > 0)
            { $regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object();

              if ($regProdBobinaOpe->inSide > 0 OR $regProdBobinaOpe->outSide > 0)
              { $infoTablero_refiladoScrap[$regPdtoBandaP->cdgbandap] = 100-(($regProdBobinaOpe->outSide*100)/$regProdBobinaOpe->inSide); }
            }

            // Calcula lo que esta refilado
            $prodBobinaOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((prodbobinaope.amplitudfin/pdtobanda.anchura)*prodbobinaope.longitudfin) AS outSide
                FROM pdtobanda,
                     pdtobandap,
                     prodbobina,
                     prodbobinaope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtobandap.preembosado = '1') AND
                    (prodbobina.cdgproducto = pdtobandap.cdgbandap AND
                     prodbobinaope.cdgbobina = prodbobina.cdgbobina AND
                     prodbobinaope.cdgoperacion = '30011' AND
                     prodbobina.sttbobina = '1')");

            if ($prodBobinaOpeSelect->num_rows > 0)
            { $regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object();

              $infoTablero_refiladoOut[$regPdtoBandaP->cdgbandap] = $regProdBobinaOpe->outSide; }

            // Calcula Scrap de lo que se ha laminado de lo refilado
            $prodBobinaOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM(prodbobinaope.amplitud*prodbobinaope.longitud) AS inSide,
                SUM(prodbobinaope.amplitudfin*prodbobinaope.longitudfin) AS outSide
                FROM pdtobanda,
                     pdtobandap,
                     prodbobina,
                     prodbobinaope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtobandap.preembosado = '1') AND
                    (prodbobina.cdgproducto = pdtobandap.cdgbandap AND
                     prodbobinaope.cdgbobina = prodbobina.cdgbobina AND
                     prodbobinaope.cdgoperacion = '30015')");

            if ($prodBobinaOpeSelect->num_rows > 0)
            { $regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object();

              if ($regProdBobinaOpe->inSide > 0 OR $regProdBobinaOpe->outSide > 0)
              { $infoTablero_laminadoScrap[$regPdtoBandaP->cdgbandap] = 100-(($regProdBobinaOpe->outSide*100)/$regProdBobinaOpe->inSide); }
            }

            // Calcula lo que esta laminado de lo sefilado
            $prodBobinaOpeSelect = $link->query("
              SELECT pdtobanda.anchura,
                SUM((prodbobinaope.amplitudfin/pdtobanda.anchura)*prodbobinaope.longitudfin) AS outSide
                FROM pdtobanda,
                     pdtobandap,
                     prodbobina,
                     prodbobinaope
              WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                     pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."' AND
                     pdtobandap.preembosado = '1') AND
                    (prodbobina.cdgproducto = pdtobandap.cdgbandap AND
                     prodbobinaope.cdgbobina = prodbobina.cdgbobina AND
                     prodbobinaope.cdgoperacion = '30015' AND
                     prodbobina.sttbobina = '5')");

            if ($prodBobinaOpeSelect->num_rows > 0)
            { $regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object();

              $infoTablero_laminadoOut[$regPdtoBandaP->cdgbandap] = $regProdBobinaOpe->outSide; }
          }

          // Calcula Scrap de lo que se ha sliteado
          $prodDiscoOpeSelect = $link->query("
            SELECT SUM(proddiscoope.longitud*proddiscoope.amplitud) AS inSide,
                   SUM(proddiscoope.longitudfin*proddiscoope.amplitudfin) AS outSide
              FROM pdtobanda,
                   pdtobandap,
                   proddisco,
                   proddiscoope
            WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                   pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."') AND
                  (proddisco.cdgproducto = pdtobandap.cdgbandap AND
                   proddiscoope.cdgdisco = proddisco.cdgdisco AND
                   proddiscoope.cdgoperacion = '40011')");

          if ($prodDiscoOpeSelect->num_rows > 0)
          { $regProdDiscoOpe = $prodDiscoOpeSelect->fetch_object();

            if ($regProdDiscoOpe->inSide > 0 OR $regProdDiscoOpe->outSide > 0)
            { $infoTablero_sliteadoScrap[$regPdtoBandaP->cdgbandap] = 100-(($regProdDiscoOpe->outSide*100)/$regProdDiscoOpe->inSide); }
          }

          // Calcula lo que esta sliteado
          $prodDiscoOpeSelect = $link->query("
            SELECT SUM(proddiscoope.longitudfin) AS outSide
              FROM pdtobanda,
                   pdtobandap,
                   proddisco,
                   proddiscoope
            WHERE (pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
                   pdtobandap.cdgbandap = '".$regPdtoBandaP->cdgbandap."') AND
                  (proddisco.cdgproducto = pdtobandap.cdgbandap AND
                   proddiscoope.cdgdisco = proddisco.cdgdisco AND
                   proddiscoope.cdgoperacion = '40011' AND
                   proddisco.sttdisco = '1')");

          if ($prodDiscoOpeSelect->num_rows > 0)
          { $regProdDiscoOpe = $prodDiscoOpeSelect->fetch_object();

            $infoTablero_sliteadoOut[$regPdtoBandaP->cdgbandap] = $regProdDiscoOpe->outSide; }
        }

        $nBandaPs[$item] = $pdtoBandaPSelect->num_rows; 
      }
    }

    $nBandas = $pdtoBandaSelect->num_rows;
  }

  // Generar Tablero de Control de Banda de Seguridad
  if ($nBandas > 0)
  { for ($item = 1; $item <= $nBandas; $item++)
    { if ($nBandaPs[$item] > 0)
      { for ($subItem = 1; $subItem <= $nBandaPs[$item]; $subItem++)
        { echo '
      <div class="bloque">
        <section class="subbloque">
          <label class="modulo_nombre">Producto <b>'.$prodTablero_banda[$item].' | '.$prodTablero_bandap[$item][$subItem].'</b></a></label><br/>';

          if ($prodTablero_preembosado[$item][$subItem] == '0')
          { // Programado para embosar
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodLotesBSPdf.php?sttlote=A&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Programado</a></label>
            <a href="pdf/prodLotesBS.php?sttlote=A&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format(($infoTablero_progembosado[$prodTablero_cdgbandap[$item][$subItem]]),2).'</b></label><br/>
            <label>&nbsp;</label>
          </article>';

            // Embosado
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodLotesBSPdf.php?sttlote=1&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Embosado</a></label>
            <a href="pdf/prodLotesBS.php?sttlote=1&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format($infoTablero_embosado[$prodTablero_cdgbandap[$item][$subItem]],2).'</b></label><br/>              
            <label>Scrap <i>'.number_format(((($prodTableroHistorico[$prodTablero_cdgbandap[$item][$subItem]]-$prodTablero_impresoHist[$prodTablero_cdgbandap[$item][$subItem]])*100)/$prodTableroHistorico[$prodTablero_cdgbandap[$item][$subItem]]),2).'%</i></label>
          </article>';
          
            // Laminado
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodLotesBSPdf.php?sttlote=5&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Laminado</a></label>
            <a href="pdf/prodLotesBS.php?sttlote=5&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format($infoTablero_laminado[$prodTablero_cdgbandap[$item][$subItem]],2).'</b></label><br/>              
            <label>Scrap <i>'.number_format(((($prodTableroHistorico[$prodTablero_cdgbandap[$item][$subItem]]-$prodTablero_impresoHist[$prodTablero_cdgbandap[$item][$subItem]])*100)/$prodTableroHistorico[$prodTablero_cdgbandap[$item][$subItem]]),2).'%</i></label>
          </article>';
          } else
          { // Programado para Refilar
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodLotesBSPdf.php?sttlote=A&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Programado</a></label>
            <a href="pdf/prodLotesBS.php?sttlote=A&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format(($infoTablero_progrefiladoOut[$prodTablero_cdgbandap[$item][$subItem]]),2).'</b></label><br/>
            <label>&nbsp;</label>
          </article>';
          
            // Refilado
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodBobinasBSPdf.php?sttbobina=1&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Refilado</a></label>
            <a href="pdf/prodBobinasBS.php?sttbobina=1&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format($infoTablero_refiladoOut[$prodTablero_cdgbandap[$item][$subItem]],2).'</b></label><br/>
            <label>Scrap <i>'.number_format($infoTablero_refiladoScrap[$prodTablero_cdgbandap[$item][$subItem]],2).'%</i></label>
          </article>';
          
            // Laminado
            echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodBobinasBSPdf.php?sttbobina=5&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">Laminado</a></label>
            <a href="pdf/prodBobinasBS.php?sttbobina=5&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format($infoTablero_laminadoOut[$prodTablero_cdgbandap[$item][$subItem]],2).'</b></label><br/>
            <label>Scrap <i>'.number_format($infoTablero_laminadoScrap[$prodTablero_cdgbandap[$item][$subItem]],2).'%</i></label>
          </article>'; }

          // Sliteado
          echo '
          <article class="subbloque" style="text-align:right">
            <label><a href="pdf/prodDiscosBSPdf.php?preembosado='.$prodTablero_preembosado[$item][$subItem].'&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'&preembosado='.$prodTablero_preembosado[$item][$subItem].'" target="_blank">Sliteo</a></label>
            <a href="pdf/prodDiscosBS.php?preembosado='.$prodTablero_preembosado[$item][$subItem].'&cdgproducto='.$prodTablero_cdgbandap[$item][$subItem].'" target="_blank">'.$png_barcode.'</a><br/>
            <label><b>'.number_format(($infoTablero_sliteadoOut[$prodTablero_cdgbandap[$item][$subItem]]),2).'</b></label><br/>            
            <label>Scrap <i>'.number_format($infoTablero_sliteadoScrap[$prodTablero_cdgbandap[$item][$subItem]],2).'%</i></label>
          </article>';

          echo '
        </section>
      </div>'; 

        }
      }
    }
  }
?>
    </div>
  </body>
</html>
