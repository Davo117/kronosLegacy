<!DOCTYPE html>
<html lang="es-MX">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
    <style type="text/css">
      #main {
        margin: 0 auto; }

      #inventario {
        background-color: #DCDCDC;
        border-radius: 5px;
        font-size: 12px;
        top: 320px;
        left: 10px;

        margin: 0 auto;
        position: fixed;

        padding-top: 4px;
        padding-left: 2px;
        padding-right: 2px;
        padding-bottom: 5px; } 

      #inventario table {
        font-size: 9px; }

    </style>
    <script>
      function tamano(){ 
        if (document.getElementById("inventario").style.height=="50px")
        { document.getElementById("detalle").style.height="500px"; }
        else
        { document.getElementById("detalle").style.height="50px"; }
      }    
    </script>

  </head>
  <body id="main">
    <div id="loader">
<?php
  include '../datos/mysql.php';
/*
  $link_mysqli = conectar();
  $querySelect = $link_mysqli->query("
    SELECT amplitud, 
           espesor, 
           encogimiento, 
       SUM(peso) AS peso
      FROM proglote
     WHERE sttlote BETWEEN '1' AND '8'
  GROUP BY amplitud, 
           espesor, 
           encogimiento
  ORDER BY amplitud DESC");

  if ($querySelect->num_rows > 0)
  { $PVCs = $querySelect->num_rows;

    while ($regQuery = $querySelect->fetch_object())
    { $nPVC++;

      $pvcAmplitud[$nPVC] = $regQuery->amplitud;
      $pvcEspesor[$nPVC] = $regQuery->espesor;
      $pvcEncogimiento[$nPVC] = $regQuery->encogimiento;
      $pvcInventarios[$nPVC] = $regQuery->peso;
      $pvcInventario[0][$regQuery->amplitud][$regQuery->espesor][$regQuery->encogimiento] = $regQuery->peso; }
  }

  $link_mysqli = conectar();
    $querySelect = $link_mysqli->query("
      SELECT amplitud, 
             espesor, 
             encogimiento,
         SUM(peso) AS peso,
             sttlote
        FROM proglote
       WHERE sttlote BETWEEN '1' AND '8'
    GROUP BY amplitud, 
             espesor, 
             encogimiento,
             sttlote");

      if ($querySelect->num_rows > 0)
      { while ($regQuery = $querySelect->fetch_object())
        { $pvcInventario[$regQuery->sttlote][$regQuery->amplitud][$regQuery->espesor][$regQuery->encogimiento] = $regQuery->peso; }
      }  

  if ($PVCs > 0)
  { echo '
      <div id="inventario">
        <section id="pvc">
          <input type="button" onClick="tamano();" value="Inventario en PVC" />
          <table id="detalle">
            <tr><th>Amplitud</th>
              <th>Espesor</th>
              <th>Encogimiento</th>
              <th>Inventario</th>
              <th>Almac&eacute;n</th>
              <th>Liberado</th>
              <th>Programado</th></tr>';

    for ($nPVC=1; $nPVC<=$PVCs; $nPVC++)
    { echo '
              <tr align="right"><td>'.$pvcAmplitud[$nPVC].'mm</td>
                <td>'.$pvcEspesor[$nPVC].' µm</td>
                <td>'.$pvcEncogimiento[$nPVC].'</td>
                <td>'.number_format($pvcInventario[0][$pvcAmplitud[$nPVC]][$pvcEspesor[$nPVC]][$pvcEncogimiento[$nPVC]],3).'</td>
                <td>'.number_format($pvcInventario[1][$pvcAmplitud[$nPVC]][$pvcEspesor[$nPVC]][$pvcEncogimiento[$nPVC]],3).'</td>
                <td>'.number_format($pvcInventario[7][$pvcAmplitud[$nPVC]][$pvcEspesor[$nPVC]][$pvcEncogimiento[$nPVC]],3).'</td>
                <td>'.number_format($pvcInventario[8][$pvcAmplitud[$nPVC]][$pvcEspesor[$nPVC]][$pvcEncogimiento[$nPVC]],3).'</td></tr>'; }
    
    echo '
          </table>
        </section>
      </div>';
    
  }
//*/
  $prodTablero_dsdano = date("Y");
  $prodTablero_dsdmes = date("n");
  $prodTablero_dsddia = date("j");

  $prodTablero_diasmes = date("t", mktime(0,0,0,($prodTablero_dsdmes),1,$prodTablero_dsdano));

  // DefiniciOn del bloque +6 dias.
  if (($prodTablero_dsddia+6) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstano = (date("Y")+1); 
      $prodTablero_hstmes = 1;
      $prodTablero_hstdia = (($prodTablero_dsddia+6)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstano = $prodTablero_dsdano; 
      $prodTablero_hstmes = (date("n")+1);
      $prodTablero_hstdia = (($prodTablero_dsddia+6)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstano = date("Y");
    $prodTablero_hstmes = date("n");
    $prodTablero_hstdia = (date("j")+6); } 
  
  $prodTablero_fechas[fchinicialA] = '2013-01-01';
  $prodTablero_fechas[fchfinalA] = $prodTablero_hstano.'-'.str_pad($prodTablero_hstmes,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdia,2,'0',STR_PAD_LEFT);

  // DefiniciOn del bloque +7 a +14 dias.
  if (($prodTablero_dsddia+7) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoB = (date("Y")+1); 
      $prodTablero_hstmesB = 1;
      $prodTablero_hstdiaB = (($prodTablero_dsddia+7)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstanoB = $prodTablero_dsdano; 
      $prodTablero_hstmesB = (date("n")+1);
      $prodTablero_hstdiaB = (($prodTablero_dsddia+7)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstanoB = date("Y");
    $prodTablero_hstmesB = date("n");
    $prodTablero_hstdiaB = (date("j")+7); }

  $prodTablero_fechas[fchinicialB] = $prodTablero_hstanoB.'-'.str_pad($prodTablero_hstmesB,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaB,2,'0',STR_PAD_LEFT);

  if (($prodTablero_dsddia+13) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoB = (date("Y")+1); 
      $prodTablero_hstmesB = 1;
      $prodTablero_hstdiaB = (($prodTablero_dsddia+13)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstanoB = $prodTablero_dsdano; 
      $prodTablero_hstmesB = (date("n")+1);
      $prodTablero_hstdiaB = (($prodTablero_dsddia+13)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstanoB = date("Y");
    $prodTablero_hstmesB = date("n");
    $prodTablero_hstdiaB = (date("j")+13); }

  $prodTablero_fechas[fchfinalB] = $prodTablero_hstanoB.'-'.str_pad($prodTablero_hstmesB,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaB,2,'0',STR_PAD_LEFT);
  
  // DefiniciOn del bloque +14 +21 dias.
  if (($prodTablero_dsddia+14) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoC = (date("Y")+1); 
      $prodTablero_hstmesC = 1;
      $prodTablero_hstdiaC = (($prodTablero_dsddia+14)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstanoC = $prodTablero_dsdano; 
      $prodTablero_hstmesC = (date("n")+1);
      $prodTablero_hstdiaC = (($prodTablero_dsddia+14)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstanoC = date("Y");
    $prodTablero_hstmesC = date("n");
    $prodTablero_hstdiaC = (date("j")+14); }

  $prodTablero_fechas[fchinicialC] = $prodTablero_hstanoC.'-'.str_pad($prodTablero_hstmesC,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaC,2,'0',STR_PAD_LEFT);

  if (($prodTablero_dsddia+20) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoC = (date("Y")+1); 
      $prodTablero_hstmesC = 1;
      $prodTablero_hstdiaC = (($prodTablero_dsddia+20)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstanoC = $prodTablero_dsdano; 
      $prodTablero_hstmesC = (date("n")+1);
      $prodTablero_hstdiaC = (($prodTablero_dsddia+20)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstanoC = date("Y");
    $prodTablero_hstmesC = date("n");
    $prodTablero_hstdiaC = (date("j")+20); }

  $prodTablero_fechas[fchfinalC] = $prodTablero_hstanoC.'-'.str_pad($prodTablero_hstmesC,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaC,2,'0',STR_PAD_LEFT);

  // DefiniciOn del bloque +21 dias en adelante.
  if (($prodTablero_dsddia+21) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoD = (date("Y")+1); 
      $prodTablero_hstmesD = 1;
      $prodTablero_hstdiaD = (($prodTablero_dsddia+21)-$prodTablero_diasmes);
    } else
    { $prodTablero_hstanoD = $prodTablero_dsdano; 
      $prodTablero_hstmesD = (date("n")+1);
      $prodTablero_hstdiaD = (($prodTablero_dsddia+21)-$prodTablero_diasmes); }
  } else
  { $prodTablero_hstanoD = date("Y");
    $prodTablero_hstmesD = date("n");
    $prodTablero_hstdiaD = (date("j")+21); }

  $prodTablero_fechas[fchinicialD] = $prodTablero_hstanoD.'-'.str_pad($prodTablero_hstmesD,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaD,2,'0',STR_PAD_LEFT);

  // Determinar que cantidad esta comprometida y aun no se ha cubierto
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto,
           SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE sttlote = '1'
    GROUP BY cdgproducto");

  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendido[$regVntsOClote->cdgproducto] = number_format($regVntsOClote->millar,3,'.',''); }
  }

  // Determinar en que presentaciones se tiene pendiente distribuir
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT vntsoclote.cdgempaque,
           pdtoempaque.empaque
    FROM vntsoclote,
         pdtoempaque
    WHERE vntsoclote.cdgempaque = pdtoempaque.cdgempaque AND
          vntsoclote.sttlote = '1'
    GROUP BY vntsoclote.cdgempaque,
             pdtoempaque.empaque");

  if ($vntsOCloteSelect->num_rows > 0)
  { $idEmpaque = 1;
    while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_cdgempaque[$idEmpaque] = $regVntsOClote->cdgempaque;
      $prodTablero_empaque[$idEmpaque] = $regVntsOClote->empaque; 

      $idEmpaque++; }

    $numEmpaques = $vntsOCloteSelect->num_rows; }

  // Filtrar los compromisos a +6 dias
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto,
           cdgempaque,
           SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE fchembarque BETWEEN '".$prodTablero_fechas[fchinicialA]."' AND '".$prodTablero_fechas[fchfinalA]."' AND
          sttlote = '1'
    GROUP BY cdgproducto,
             cdgempaque");

  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendidoA[$regVntsOClote->cdgproducto] += number_format($regVntsOClote->millar,3,'.',''); 
      $prodTablero_vendidoAD[$regVntsOClote->cdgproducto][$regVntsOClote->cdgempaque] = number_format($regVntsOClote->millar,3,'.','');  }
  }

  // Filtrar los compromisos de +7 a +13 dias
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto, cdgempaque, SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE fchembarque <= '".$prodTablero_fechas[fchfinalB]."' AND
      sttlote = '1'
    GROUP BY cdgproducto, cdgempaque");

  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendidoB[$regVntsOClote->cdgproducto] += number_format($regVntsOClote->millar,3,'.',''); 
      $prodTablero_vendidoBD[$regVntsOClote->cdgproducto][$regVntsOClote->cdgempaque] = number_format($regVntsOClote->millar,3,'.','');}
  }

  // Filtrar los compromisos de +14 a +20 dias
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto, cdgempaque, SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE fchembarque BETWEEN '".$prodTablero_fechas[fchinicialC]."' AND '".$prodTablero_fechas[fchfinalC]."' AND
      sttlote = '1'
    GROUP BY cdgproducto, cdgempaque");
 
  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendidoC[$regVntsOClote->cdgproducto] += number_format($regVntsOClote->millar,3,'.',''); 
      $prodTablero_vendidoCD[$regVntsOClote->cdgproducto][$regVntsOClote->cdgempaque] = number_format($regVntsOClote->millar,3,'.','');}
  }

  // Filtrar los compromisos a +21 dias
  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto, cdgempaque, SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE fchembarque >= '".$prodTablero_fechas[fchinicialD]."' AND
      sttlote = '1'
    GROUP BY cdgproducto, cdgempaque");
 
  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendidoD[$regVntsOClote->cdgproducto] += number_format($regVntsOClote->millar,3,'.',''); 
      $prodTablero_vendidoDD[$regVntsOClote->cdgproducto][$regVntsOClote->cdgempaque] = number_format($regVntsOClote->millar,3,'.','');}
  }

  // Inventario en Lote
  $link_mysqli = conectar();
  $prodLoteSelect = $link_mysqli->query("
    SELECT prodlote.cdgproducto,
           prodlote.sttlote,
     ((SUM(prodlote.longitud)/pdtodiseno.alto)*(pdtodiseno.alpaso)) AS millar
      FROM prodlote,
           pdtodiseno,
           pdtoimpresion
     WHERE prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1')
  GROUP BY prodlote.cdgproducto,
           prodlote.sttlote");

  if ($prodLoteSelect->num_rows > 0)
  { while ($regProdLote = $prodLoteSelect->fetch_object())
    { if ($regProdLote->sttlote == 'A' AND $regProdLote->millar > .001)
      { $prodTablero_programado[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); }

      if ($regProdLote->sttlote == '1' AND $regProdLote->millar > .001)
      { $prodTablero_impreso[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); }

      if ($regProdLote->sttlote == '9' AND $regProdLote->millar > .001)
      { $prodTablero_impresoHistR[$regProdLote->cdgproducto] = $regProdLote->millar; }
      
      if ($regProdLote->sttlote != 'A' AND $regProdLote->millar > .001)
      { $prodTablero_impresoHist[$regProdLote->cdgproducto] += $regProdLote->millar; }
    }
  }

  // Inventario en Bobina
  $link_mysqli = conectar();
  $prodBobinaSelect = $link_mysqli->query("
    SELECT prodbobina.cdgproducto,
           prodbobina.sttbobina,
      (SUM(prodbobina.longitud)/pdtodiseno.alto) AS millar
      FROM prodbobina,
           pdtoimpresion,
           pdtodiseno
     WHERE prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1')
  GROUP BY prodbobina.cdgproducto,
           prodbobina.sttbobina");

  if ($prodBobinaSelect->num_rows > 0)
  { while ($regProdBobina = $prodBobinaSelect->fetch_object())
    { if ($regProdBobina->sttbobina == '1' AND $regProdBobina->millar > .001)
      { $prodTablero_refilado[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '6' AND $regProdBobina->millar > .001)
      { $prodTablero_liberado1[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '7' AND $regProdBobina->millar > .001)
      { $prodTablero_transferido[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '8' AND $regProdBobina->millar > .001)
      { $prodTablero_enbobina[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '9' AND $regProdBobina->millar > .001)
      { $prodTablero_refiladoHistF[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      $prodTablero_refiladoHist[$regProdBobina->cdgproducto] += $regProdBobina->millar;
    }
  }

  // Inventario en Rollo
  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("
    SELECT prodrollo.cdgproducto,
           prodrollo.sttrollo,
      (SUM(prodrollo.longitud)/pdtodiseno.alto) AS millar
      FROM prodrollo,
           pdtodiseno,
           pdtoimpresion
     WHERE prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1')  
  GROUP BY prodrollo.cdgproducto,
           prodrollo.sttrollo");

  if ($prodRolloSelect->num_rows > 0)
  { while ($regProdRollo = $prodRolloSelect->fetch_object())
    { if ($regProdRollo->sttrollo == '1' AND $regProdRollo->millar > .001)
      { $prodTablero_fusionado[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '5' AND $regProdRollo->millar > .001)
      { $prodTablero_cortadoHist[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '6' AND $regProdRollo->millar > .001)
      { $prodTablero_revisado[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '7' AND $regProdRollo->millar > .001)
      { $prodTablero_liberado2[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '8' AND $regProdRollo->millar > .001)
      { $prodTablero_pesado[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '9' AND $regProdRollo->millar > .001)
      { $prodTablero_fusionadoEmp[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      $prodTablero_fusionadoHist[$regProdRollo->cdgproducto] += $regProdRollo->millar;
    }
  }

  // Inventario Cortado
  $link_mysqli = conectar();
  $prodPaqueteSelect = $link_mysqli->query("
    SELECT prodpaquete.cdgproducto,
           prodpaquete.sttpaquete,
       SUM(prodpaquete.cantidad) AS millar
      FROM prodpaquete,
           pdtodiseno,
           pdtoimpresion
     WHERE prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1')
  GROUP BY prodpaquete.cdgproducto,
           prodpaquete.sttpaquete");

  if ($prodPaqueteSelect->num_rows > 0)
  { while ($regProdPaquete = $prodPaqueteSelect->fetch_object())
    { if ($regProdPaquete->sttpaquete == '1')
      { $prodTablero_paquete[$regProdPaquete->cdgproducto] = number_format($regProdPaquete->millar,3,'.',''); }

      if ($regProdPaquete->sttpaquete != 'C') 
      { $prodTablero_paqueteAct[$regProdPaquete->cdgproducto] = $regProdPaquete->millar; }
    }
  }

  // Inventario empacado en Queso
  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("
    SELECT prodrollo.cdgproducto,
      (SUM(prodrollo.longitud)/pdtodiseno.alto) AS millar
      FROM alptempaque,
           alptempaquer,
           prodlote,
           prodbobina,
           prodrollo,
           pdtoimpresion,
           pdtodiseno
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
           prodbobina.cdgbobina = prodrollo.cdgbobina AND
           prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
           pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno) AND
           alptempaque.cdgempaque = alptempaquer.cdgempaque AND
           alptempaquer.cdgrollo = prodrollo.cdgrollo AND
           alptempaque.sttempaque = '1'
  GROUP BY prodrollo.cdgproducto");

  if ($prodRolloSelect->num_rows > 0)
  { while ($regProdRollo = $prodRolloSelect->fetch_object())
    { $prodTablero_queso[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }
  }

  // Inventario en Caja
  $link_mysqli = conectar();
  $prodPaqueteSelect = $link_mysqli->query("
    SELECT prodpaquete.cdgproducto,
  SUM(prodpaquete.cantidad) AS millar
    FROM alptempaque,
      alptempaquep,
      prodlote,
      prodbobina,
      prodrollo,
      prodpaquete,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgrollo = prodpaquete.cdgrollo AND
      prodpaquete.cdgpaquete = alptempaquep.cdgpaquete) AND
     (prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion) AND
      alptempaque.cdgempaque = alptempaquep.cdgempaque AND
      alptempaque.sttempaque = '1'
    GROUP BY prodpaquete.cdgproducto");

  if ($prodPaqueteSelect->num_rows > 0)
  { while ($regProdPaquete = $prodPaqueteSelect->fetch_object())
    { $prodTablero_caja[$regProdPaquete->cdgproducto] = number_format($regProdPaquete->millar,3,'.',''); }
  }

  // Producto enviado en Queso
  $link_mysqli = conectar();
  $alptEmpaqueR_Select = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
	  pdtoimpresion.impresion,
	  SUM(alptempaquer.cantidad) AS millares
	FROM pdtodiseno,
	  pdtoimpresion,
	  alptempaque,
	  alptempaquer
	WHERE pdtodiseno.sttdiseno = '1' AND
	  pdtodiseno.cdgdiseno = pdtoimpresion.cdgproyecto AND
	  pdtoimpresion.cdgimpresion =  alptempaquer.cdgproducto AND
	  alptempaque.sttempaque = 'E' AND
	  alptempaque.cdgempaque = alptempaquer.cdgempaque
	GROUP BY alptempaquer.cdgproducto");

	if ($alptEmpaqueR_Select->num_rows > 0)
	{ while ($regEmpaqueR = $alptEmpaqueR_Select->fetch_object())
	  { $prodTablero_rolloenv[$regEmpaqueR->cdgimpresion] = $regEmpaqueR->millares;
	    $prodTablero_productoenv[$regEmpaqueR->cdgimpresion] = $regEmpaqueR->millares;
		}
	}

  // Producto enviado en Caja
  $alptEmpaqueP_Select = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
	  pdtoimpresion.impresion,
	  SUM(alptempaquep.cantidad)  AS millares
	FROM pdtodiseno,
	  pdtoimpresion,
	  alptempaque,
	  alptempaquep
	WHERE pdtodiseno.sttdiseno = '1' AND
	  pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
	  pdtoimpresion.cdgimpresion =  alptempaquep.cdgproducto AND
	  alptempaque.sttempaque = 'E' AND
	  alptempaque.cdgempaque = alptempaquep.cdgempaque
	GROUP BY alptempaquep.cdgproducto");

	if ($alptEmpaqueP_Select->num_rows > 0)
	{ while ($regEmpaqueP = $alptEmpaqueP_Select->fetch_object())
	  { $prodTablero_cajaenv[$regEmpaqueP->cdgimpresion] = $regEmpaqueP->millares;
	    $prodTablero_productoenv[$regEmpaqueP->cdgimpresion] = $prodTablero_productoenv[$regEmpaqueP->cdgimpresion]+$regEmpaqueP->millares;
		}
	} 
    
  // Proyectos en los diseños
  $link_mysqli = conectar();
  $pdtoDiseno_Select = $link_mysqli->query("
    SELECT periodo
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND 
           pdtoimpresion.sttimpresion = '1'
  GROUP BY periodo
  ORDER BY periodo");

  if ($pdtoDiseno_Select->num_rows > 0)
  { $num_proyectos = $pdtoDiseno_Select->num_rows;
  
    $idProyecto = 1;
    while ($regPdtoDiseno = $pdtoDiseno_Select->fetch_object())
    { $prodTablero_proyectos[$idProyecto] = $regPdtoDiseno->periodo;
  
      // Impresiones por diseño
      $link_mysqli = conectar();
      $pdtoImpresion_Select = $link_mysqli->query("
        SELECT pdtoimpresion.cdgimpresion,
          pdtoimpresion.impresion
        FROM pdtodiseno, 
          pdtoimpresion
        WHERE pdtodiseno.sttdiseno = '1' AND          
          pdtoimpresion.periodo = '".$regPdtoDiseno->periodo."' AND
          pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          pdtoimpresion.sttimpresion = '1'
        ORDER BY pdtodiseno.diseno,
          pdtoimpresion.impresion");
      
      if ($pdtoImpresion_Select->num_rows > 0)
      { $num_productos[$idProyecto] = $pdtoImpresion_Select->num_rows;

        $idProducto = 1;
        while ($regPdtoImpresion = $pdtoImpresion_Select->fetch_object())
        { $prodTablero_impresion[$idProyecto][$idProducto] =  $regPdtoImpresion->impresion;
          $prodTablero_cdgimpresion[$idProyecto][$idProducto] =  $regPdtoImpresion->cdgimpresion;

          $idProducto++; }
      }  

      $idProyecto++; }
  }

  // Histórico de programación
  $link_mysqli = conectar();
  $infoHistoricoSelect = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion, 
      (SUM(proglote.longitud)*(pdtodiseno.alpaso))/pdtodiseno.alto AS historico
    FROM proglote, prodlote, pdtoimpresion, pdtodiseno, pdtosustrato
    WHERE proglote.cdglote = prodlote.cdglote AND
      prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
      pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
      pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato AND
      prodlote.sttlote BETWEEN '1' AND '9'
    GROUP BY pdtoimpresion.cdgimpresion");

  while ($regInfoHistorico = $infoHistoricoSelect->fetch_object())
  { $prodTableroHistorico[$regInfoHistorico->cdgimpresion] = $regInfoHistorico->historico; }

  // Armado del tablero
  for ($idProyecto=1; $idProyecto<=$num_proyectos; $idProyecto++)
  { echo '
    <table align="center">
      <thead>
        <tr><td align="left" colspan="15" style="background:#FFFFFF"><strong>'.$prodTablero_proyectos[$idProyecto].'</strong></td></tr>
        <tr><th>Producto</th>
          <th>'.utf8_decode('Impresión').'</th>
          <th>'.utf8_decode('Fusión').'</th>
          <th>'.utf8_decode('Producto terminado').'</th>
          <th><label title="Hasta '.$prodTablero_fechas[fchfinalA].'"> O.C. 07 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialB].' al '.$prodTablero_fechas[fchfinalB].'"> O.C. 14 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialC].' al '.$prodTablero_fechas[fchfinalC].'"> O.C. 21 </label></th>
          <th><label title="Desde '.$prodTablero_fechas[fchinicialD].'"> O.C. +21 </label></th>
          <th>Enviado</th></tr>
      </thead>
      <tbody>';        

    for ($idProducto=1; $idProducto<=$num_productos[$idProyecto]; $idProducto++)
    { $prodTablero_total = ($prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_pesado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);

      if ($prodTablero_total > 0 OR $prodTablero_programado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
      { $prodTablero_sumexistencia[0] += $prodTablero_total;
        $prodTablero_sumexistencia[$idProyecto] += $prodTablero_total; 

        $sinprogramar = 0;
        $sinprogramar = $prodTablero_total-$prodTablero_vendido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
    
          if ($sinprogramar >= 0) 
          { $colortexto = 'blue'; }
          else 
          { $colortexto = 'red'; 

            $sinprogramar = $prodTablero_vendido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_total;
            
            $link_mysqli = conectar();
            $boomSelectSustrato = $link_mysqli->query("
              SELECT pdtodiseno.cdgsustrato,
                pdtosustrato.sustrato,
                (((((pdtodiseno.ancho*2)+pdtodiseno.empalme)+(pdtodiseno.registro/pdtodiseno.alpaso))*(pdtodiseno.alto/1000))/pdtosustrato.rendimiento) AS consumo
              FROM pdtodiseno, 
                pdtoimpresion,
                pdtosustrato
              WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato AND
                pdtoimpresion.cdgimpresion = '".$prodTablero_cdgimpresion[$idProyecto][$idProducto]."'"); 

            if ($boomSelectSustrato->num_rows > 0)
            { $reqSustrato = true;
            
              $regBoomSustrato = $boomSelectSustrato->fetch_object();
              
              $boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->sustrato;
              $boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->consumo; 
              $boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->cdgsustrato; }
            else
            { $reqSustrato = false; }            
          }

        echo '
        <tr>
          <td>
            <details><summary><strong>'.$prodTablero_impresion[$idProyecto][$idProducto].'</strong></summary>
            
            <table align="right">              
              <tr><td>Inventario</td>
                <td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><font color="green">'.number_format($prodTablero_total,3,'.',',').'</font></strong></label></td>
                <td></td>
                <td></td></tr>
              <tr><td>Requerido</td>
                <td colspan="3" align="right">
                  <details><summary><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' '.$numTintas.' tintas"><strong><font color="'.$colortexto.'">'.number_format(($sinprogramar),3,'.',',').'</font></strong></label></summary>';

          if ($reqSustrato = true OR $colortexto == 'red')
          { echo '<br>
            <table>
              <tr><th colspan="2">Sustrato</th>
                <th>Requerido</th></tr>';

            $ampSustrato_sustrato[$boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] = $boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
            $ampSustrato_sustratoreque[$boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] += ($sinprogramar*$boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);
              
            echo '
              <tr><td colspan="2">'.$boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]].'</td>                
                <td align="right"><strong>'.number_format(($sinprogramar*$boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),3,'.',',').'</strong> Kgs.</td></tr>
            </table>';
          }

          echo '
                  </td>
              </tr>  
              <tr><td>Programado</td>
                <td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong>'.number_format($prodTablero_programado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong></label></td>
                <td><a href="pdf/prodLotesPdf.php?sttlote=A&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodLotesBC.php?sttlote=A&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>
              <tr align="right"><td>Historico</td>
                <td>100.00 %</td>
                <td colspan="2">'.number_format($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</td></tr>';

          if ($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
          { $prodTablero_Desperdicio[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] += ((($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_impresoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);
            echo '
              <tr align="right"><td>Impresi&oacute;n</td>
                  <td>'.number_format(((($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_impresoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),2,'.',',').' %</td>
                  <td colspan="2">'.number_format(((($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_impresoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100),2,'.',',').' %</td></tr>'; }

          if ($prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
          { $prodTablero_Desperdicio[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] += ((($prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_refiladoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);
            echo '
              <tr align="right"><td>Refilado</td>
                  <td>'.number_format(((($prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_refiladoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),2,'.',',').' %</td>
                  <td colspan="2">'.number_format(((($prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_refiladoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])/$prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100),2,'.',',').' %</td></tr>'; }

          if ($prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
          { $prodTablero_Desperdicio[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] += ((($prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_fusionadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);
            echo '
              <tr align="right"><td>Fusi&oacute;n</td>
                  <td>'.number_format(((($prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_fusionadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),2,'.',',').' %</td>
                  <td colspan="2">'.number_format(((($prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_fusionadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])/$prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100),2,'.',',').' %</td></tr>'; }
          
          if ($prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
          { $prodTablero_Desperdicio[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] += ((($prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_paqueteAct[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);
            echo '
              <tr align="right"><td>Corte</td>
                  <td>'.number_format(((($prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_paqueteAct[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),2,'.',',').' %</td>
                  <td colspan="2">'.number_format(((($prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_paqueteAct[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])/$prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idProyecto][$idProducto]])*100),2,'.',',').' %</td></tr>'; }
                  echo '
              <tr align="right"><td>Desperdicio</td>
                <td>'.number_format($prodTablero_Desperdicio[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],2,'.',',').' %</td>
                <td colspan="2">'.number_format(((($prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-($prodTablero_total+$prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]))*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),2,'.',',').' %</td></tr>
            </table>
            </details>  
          </td>
          <td>';          

        echo '  
            <details>
            <table align="right">';

        $prodTablero_sumimpresion[0] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumimpresion[$idProyecto] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
        echo '
              <tr><td align="right"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Impreso">'.number_format($prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodLotesPdf.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodLotesBC.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumrefilado[0] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumrefilado[$idProyecto] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td align="right" bgcolor="#FBA3A3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Refilado">'.number_format($prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumliberadob[0] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumliberadob[$idProyecto] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td align="right" bgcolor="#FBCFA3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Liberado">'.number_format($prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumtranferido[0] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumtranferido[$idProyecto] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
        echo '
              <tr><td align="right" bgcolor="#A3FBA9"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Transferido">'.number_format($prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        echo '
            </table>
              <summary><label title="Suma"><b>'.number_format($prodTablero_sumaimpresion[$idProyecto][$idProducto],3,'.',',').'</b> Mlls</label></summary>
            </details>
          </td>
          <td>
            <details>
              <table align="right">';

        $prodTablero_sumbobina[0] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumbobina[$idProyecto] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
        echo '
              <tr><td align="right"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Por fusionar">'.number_format($prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumfusionado[0] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumfusionado[$idProyecto] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td align="right" bgcolor="#FBA3A3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Fusionado">'.number_format($prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumrevisado[0] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumrevisado[$idProyecto] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td align="right" bgcolor="#FBCFA3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Revisado">'.number_format($prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumliberador[0] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumliberador[$idProyecto] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

        $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr>
                <td align="right" bgcolor="#A3FBA9"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Liberado">'.number_format($prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumpaquete[0] += $prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumpaquete[$idProyecto] += $prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
         
        echo '
              <tr>
                <td align="right" bgcolor="cyan"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">'.number_format($prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodPaquetesPdf.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodPaqueteBC.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        echo '
            </table>
              <summary><label title="Suma"><b>'.number_format($prodTablero_sumafusion[$idProyecto][$idProducto],3,'.',',').'</b> Mlls</label></summary>
            </details>
          </td>
          <td>
            <details>
            <table align="right">';

        $prodTablero_sumempacadoc[0] += $prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumempacadoc[$idProyecto] += $prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumaterminado[$idProyecto][$idProducto] += $prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td>Caja</td>
                <td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong>'.number_format($prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong></label></td>
                <td><a href="excel/prodPaquete.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'">'.$jpg_excel.'</a></td>
                <td><a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        $prodTablero_sumempacadoq[0] += $prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumempacadoq[$idProyecto] += $prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        $prodTablero_sumaterminado[$idProyecto][$idProducto] += $prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
        echo '
              <tr><td>Queso</td>
                <td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong>'.number_format($prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong></td>
                <td><a href="excel/prodRollo.php?cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'">'.$jpg_excel.'</a></td>
                <td><a href="../sm_almacenpt/pdf/alptEmbarqueBCE.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; 

        echo '    
            </table>
              <summary><label title="Suma"><b>'.number_format($prodTablero_sumaterminado[$idProyecto][$idProducto],3,'.',',').'</b> Mlls</label></summary>
            </details>
          </td>
          <td align="right">';          

        if ($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumAporsurtir[0] += $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumAporsurtir[$idProyecto] += $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <details>
              <summary><strong>'.number_format($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong> Mlls</summary>
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">ver <strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialA].'&hstfecha='.$prodTablero_fechas[fchfinalA].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</a></strong></label>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
          echo '
          </details>';
        }

        echo '
          </td>
          <td align="right">';

        if ($prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumBporsurtir[0] += $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumBporsurtir[$idProyecto] += $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <details>
              <summary><strong>'.number_format($prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong> Mlls</summary>
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">ver <strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialB].'&hstfecha='.$prodTablero_fechas[fchfinalB].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">Destinos</a></strong></label>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
          echo '
          </details>';
        }

        echo '
          </td>
          <td align="right">';

        if ($prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumCporsurtir[0] += $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumCporsurtir[$idProyecto] += $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <details>
              <summary><strong>'.number_format($prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong> Mlls</summary>
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">ver <strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialC].'&hstfecha='.$prodTablero_fechas[fchfinalC].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">Destinos</a></strong></label>'; 

          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
          echo '
          </details>';
        }

        echo '
          </td>
          <td align="right">';

        if ($prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumDporsurtir[0] += $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumDporsurtir[$idProyecto] += $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <details>
              <summary><strong>'.number_format($prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong> Mlls</summary>
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">ver <strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialD].'&hstfecha=2099-12-31&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">Destinos</a></strong></label>'; 

          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoDD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          
          echo '
          </details>';
        }

        echo '
          </td>
          <td align="right" style="background:#99BCBF">';          

        if ($prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumenviado[0] += $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumenviado[$idProyecto] += $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <details>
              <summary><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">'.number_format($prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'  Mlls</label></summary>
            <label title="Pieza cortada"><strong>'.number_format($prodTablero_cajaenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').' </strong>C</label><br/>
            <label title="Banda"><strong>'.number_format($prodTablero_rolloenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').' </strong>Q</label>
            </details>'; }

        echo '</td></tr>';  
      }         
    }
    
    echo '
        <tr align="right">
          <td align="left">
            <table align="center">
              <thead>
                <tr><td colspan="2" >Inventario</td>
                  <td align="right"><b>'.number_format($prodTablero_sumexistencia[$idProyecto],3,'.',',').'</b></td></tr>
              </thead>
              <tbody>
                <tr><th colspan="2" >Sustrato requerido</th><th>Cantidad</th></tr>';

      $link_mysqli = conectar();
      $pdtoSustrato = $link_mysqli->query("
        SELECT * FROM pdtosustrato
        ORDER BY sustrato");

      if ($pdtoSustrato->num_rows > 0)
      { while ($regSustrato = $pdtoSustrato->fetch_object())
        { if ($ampSustrato_sustratoreque[$regSustrato->cdgsustrato] > 0)
          { echo '
                <tr><td colspan="2">'.$ampSustrato_sustrato[$regSustrato->cdgsustrato][$idProyecto].'</td>
                  <td align="right">'.number_format($ampSustrato_sustratoreque[$regSustrato->cdgsustrato][$idProyecto],3,'.',',').' Kgs.</td></tr>'; }
        }        
      }

      unset($ampSustrato_sustrato);  
      unset($ampSustrato_sustratoreque); 

      echo '
            </table>
          </td>
          <th><b>
            <table align="right">
              <tr><td align="right"><label title="Impreso">'.number_format($prodTablero_sumimpresion[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Refilado">'.number_format($prodTablero_sumrefilado[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Liberado">'.number_format($prodTablero_sumliberadob[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Trasferido">'.number_format($prodTablero_sumtranferido[$idProyecto],3,'.',',').'</label></td></tr></table></b></th>
          <th><b>
            <table align="right">
              <tr><td align="right"><label title="Por fusionar">'.number_format($prodTablero_sumbobina[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Fusionado">'.number_format($prodTablero_sumfusionado[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Revisado">'.number_format($prodTablero_sumrevisado[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Liberado">'.number_format($prodTablero_sumliberador[$idProyecto],3,'.',',').'</label></td></tr>
              <tr><td align="right"><label title="Cortado">'.number_format($prodTablero_sumpaquete[$idProyecto],3,'.',',').'</label></td></tr></table></b></th>
          <th><b>
            <table align="right">
              <tr><td>'.number_format($prodTablero_sumempacadoq[$idProyecto],3,'.',',').'</td></tr>              
              <tr><td>'.number_format($prodTablero_sumempacadoc[$idProyecto],3,'.',',').'</td></tr></table></b></th>
          <th><b>'.number_format($prodTablero_sumAporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumBporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumCporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumDporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumenviado[$idProyecto],3,'.',',').'</b></th></tr>
      </tbody>       
    </table><br/>'; //*/
  }

  $ahora = date("Y/m/d H:i:s");
  /*
  echo '
    <table align="center">
      <thead>
        <tr>
          <th>Ordenado</th>          
          <th>ImpresiOn</th>
          <th>FusiOn</th>        
          <th>Queso</th>
          <th>Corte</th>
          <th>Caja</th>
          <th>TOTAL</th>
          <th>O.C. 07</th>
          <th>O.C. 14</th>
          <th>O.C. 21</th>
          <th>O.C. +21</th>
          <th>Enviado</th></tr>
      </thead>
      </body>
        <tr align="right"><td><b>Totales</b></td>          
          <td><b>
            <table>
              <tr><th align="right">'.number_format($prodTablero_sumimpresion[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumrefilado[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumliberadob[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumtranferido[0],3,'.',',').'</th></tr></table></b></td>
          <td><b>
            <table>
              <tr><th align="right">'.number_format($prodTablero_sumbobina[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumfusionado[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumrevisado[0],3,'.',',').'</th></tr>
              <tr><th align="right">'.number_format($prodTablero_sumliberador[0],3,'.',',').'</th></tr></table></b></td>
          <td><b>'.number_format($prodTablero_sumempacadoq[0],3,'.',',').'</b></td>
          <td><b>'.number_format($prodTablero_sumpaquete[0],3,'.',',').'</b></td>
          <td><b>'.number_format($prodTablero_sumempacadoc[0],3,'.',',').'</b></td>
          <td><b>'.number_format($prodTablero_sumexistencia[0],3,'.',',').'</b></td>
          <td><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialA].'&hstfecha='.$prodTablero_fechas[fchfinalA].'" target="_blank"><b>'.number_format($prodTablero_sumAporsurtir[0],3,'.',',').'</b></a></td>
          <td><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialB].'&hstfecha='.$prodTablero_fechas[fchfinalB].'" target="_blank"><b>'.number_format($prodTablero_sumBporsurtir[0],3,'.',',').'</b></a></td>
          <td><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialC].'&hstfecha='.$prodTablero_fechas[fchfinalC].'" target="_blank"><b>'.number_format($prodTablero_sumCporsurtir[0],3,'.',',').'</b></a></td>
          <td><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialD].'&hstfecha=2099-12-31" target="_blank"><b>'.number_format($prodTablero_sumDporsurtir[0],3,'.',',').'</b></a></td>
          <td><b>'.number_format($prodTablero_sumenviado[0],3,'.',',').'</b></td></tr>
      </tbody> 
      <tfoot>    
        <tr align="right"><td colspan="15">Cantidades expresadas en <strong>millares</strong> '.$ahora.'</td></tr>
      </tfoot>
    </table>'; //*/
?>
    </div>
  </body>
</html>
