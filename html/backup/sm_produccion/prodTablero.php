<!DOCTYPE html>
<html lang="es-MX">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body id="main">
    <div id="loader">
<?php
  include '../datos/mysql.php';

  $link_mysqli = conectar();
  $queryDiseno = $link_mysqli->query("
    SELECT * FROM pdtodiseno
  ORDER BY cdgdiseno");

  if ($queryDiseno->num_rows > 0)
  { echo '<h1>Here we go.</h1>'; 
    
    while ($regDiseno = $queryDiseno->fetch_object())
    { $nDiseno++;

      $alpaso = $regDiseno->alpaso;

      $queryImpresion = $link_mysqli->query("
        SELECT * FROM pdtoimpresion 
         WHERE cdgdiseno = '".$regDiseno->cdgdiseno."'
      ORDER BY cdgimpresion");

      if ($queryImpresion->num_rows > 0)
      { while ($regImpresion = $queryImpresion->fetch_object())
        { $nImpresion++; 

          $queryLote = $link_mysqli->query("
            SELECT * FROM prodlote
             WHERE sttlote = '9' AND
                   cdgproducto = '".$regImpresion->cdgimpresion."'");

          if ($alpaso == 0) { $alpaso = $regImpresion->alpaso; }

          if ($queryLote->num_rows > 0)
          { while ($regLote = $queryLote->fetch_object())
            { $nLote++; 

              $querySelectLoteOpe = $link_mysqli->query("
                SELECT * FROM prodloteope
                 WHERE cdglote = '".$regLote->cdglote."' AND
                       cdgoperacion = '30001'");

              if ($querySelectLoteOpe->num_rows > 0)
              { $regLoteOpe = $querySelectLoteOpe->fetch_object();

                for ($nbobina=1; $nbobina<=$alpaso; $nbobina++)
                { $cdgbobina = substr($regLote->cdglote,0,8).$nbobina.'000';

                  $link_mysqli->query("
                    INSERT INTO prodbobinaope
                      (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, cdgdefecto, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$cdgbobina."', '30001', '".$regLoteOpe->cdgempleado."', '".$regLoteOpe->cdgmaquina."', '".$regLote->longitud."', '".($regLote->peso/$alpaso)."', 'UP', '".$regLote->fchmovimiento."', NOW())");

                  if ($link_mysqli->affected_rows > 0)
                  { $nInsertB++; }
                }
              } else
              { /*
                $link_mysqli->query("
                  INSERT INTO prodloteope
                    (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longin, peso, cdgdefecto, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$regLote->cdglote."', '30001', '9999', '99999', '".$regLote->longitud."', '".$regLote->peso."', 'OP', '".$regLote->fchmovimiento."', NOW())");

                if ($link_mysqli->affected_rows > 0)
                { $nInsert++; 
                  

                  for ($nbobina=1; $nbobina<=$alpaso; $nbobina++)
                  { $cdgbobina = substr($regLote->cdglote,0,8).$nbobina.'000';

                    $link_mysqli->query("
                      INSERT INTO prodbobinaope
                        (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, cdgdefecto, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$cdgbobina."', '30001', '9999', '99999', '".$regLote->longitud."', '".($regLote->peso/$alpaso)."', 'OP', '".$regLote->fchmovimiento."', NOW())");

                    if ($link_mysqli->affected_rows > 0)
                    { $nInsertA++; }
                  }
                } //*/
              }
            }
          }



        }
      }
    } //*/

    echo $nDiseno.' '.utf8_decode(' Diseños').'<br>';
    echo $nImpresion.' '.utf8_decode(' Impresiones').'<br>';
    echo $nLote.' '.utf8_decode(' Lotes refilados').'<br>';
    echo $nInsert.' '.utf8_decode(' Lotes refilados (Insert)').'<br>';
    echo $nInsertA.' '.utf8_decode(' Bobinas refiladas (InsertA)').'<br>';
    echo $nInsertB.' '.utf8_decode(' Bobinas refiladas (InsertB)').'<br>';
  }


/*
  $link_mysqli = conectar();
  $querySelect = $link_mysqli->query("
    SELECT * FROM prodloteope
     WHERE cdgoperacion = '30001'");

  if ($querySelect->num_rows > 0)
  { 

    while ($regQuery = $querySelect->fetch_object())
    { $id++;
      for($nbobina=1;$nbobina<=2;$nbobina++)
      { $link_mysqli = conectar();
        $link_mysqli->query("
          INSERT INTO prodbobinaope
            (cdgbobina, cdgoperacion, longitud, amplitud, peso, fchmovimiento)
          VALUES
            ('30001', ".$regQuery->longitud."', '".$regQuery->amplitud."', '".$regQuery->peso."', '".$regQuery->fchmovimiento."')");
      }
    }

    echo '<h1>'.$id.'</h1>';
  } //*/

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
    WHERE fchembarque BETWEEN '".$prodTablero_fechas[fchinicialB]."' AND '".$prodTablero_fechas[fchfinalB]."' AND
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
    { if ($regProdLote->sttlote == 'A' AND $regProdLote->millar > 0)
      { $prodTablero_programado[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); }

      if ($regProdLote->sttlote == '1' AND $regProdLote->millar > 0)
      { $prodTablero_impreso[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); 
        $prodTablero_sumaimpresion[$regProdLote->cdgproducto] += $regProdLote->millar; }

      if ($regProdLote->sttlote == '7' AND $regProdLote->millar > 0)
      { $prodTablero_liberado0[$regProdLote->cdgproducto] = $regProdLote->millar; 
        $prodTablero_sumaimpresion[$regProdLote->cdgproducto] += $regProdLote->millar; }

      if ($regProdLote->sttlote == '9' AND $regProdLote->millar > 0)
      { $prodTablero_impresoHistR[$regProdLote->cdgproducto] = $regProdLote->millar; }
      
      if ($regProdLote->sttlote != 'A' AND $regProdLote->millar > 0)
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
      { $prodTablero_refilado[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); 
        $prodTablero_sumasliteo[$regProdBobina->cdgproducto] += $regProdBobina->millar; }

      if ($regProdBobina->sttbobina == '6' AND $regProdBobina->millar > .001)
      { $prodTablero_liberado1[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); 
        $prodTablero_sumasliteo[$regProdBobina->cdgproducto] += $regProdBobina->millar; }

      if ($regProdBobina->sttbobina == '7' AND $regProdBobina->millar > .001)
      { $prodTablero_transferido[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); 
        $prodTablero_sumasliteo[$regProdBobina->cdgproducto] += $regProdBobina->millar; }

      if ($regProdBobina->sttbobina == '8' AND $regProdBobina->millar > .001)
      { $prodTablero_recibido[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); 
        $prodTablero_sumasliteo[$regProdBobina->cdgproducto] += $regProdBobina->millar; }

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
      { $prodTablero_fusionado[$regProdRollo->cdgproducto] = $regProdRollo->millar; 
        $prodTablero_sumafusion[$regProdRollo->cdgproducto] += $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '5' AND $regProdRollo->millar > .001)
      { $prodTablero_cortadoHist[$regProdRollo->cdgproducto] = $regProdRollo->millar; }

      if ($regProdRollo->sttrollo == '6' AND $regProdRollo->millar > .001)
      { $prodTablero_revisado[$regProdRollo->cdgproducto] = $regProdRollo->millar; 
        $prodTablero_sumafusion[$regProdRollo->cdgproducto] += $regProdRollo->millar;  }

      if ($regProdRollo->sttrollo == '7' AND $regProdRollo->millar > .001)
      { $prodTablero_liberado2[$regProdRollo->cdgproducto] = $regProdRollo->millar; 
        $prodTablero_sumafusion[$regProdRollo->cdgproducto] += $regProdRollo->millar;  }

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
      { $prodTablero_paquete[$regProdPaquete->cdgproducto] = number_format($regProdPaquete->millar,3,'.',''); 
        $prodTablero_sumafusion[$regProdPaquete->cdgproducto] += $regProdPaquete->millar; }

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
    { $prodTablero_queso[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); 
      $prodTablero_sumaterminado[$regProdRollo->cdgproducto] += $regProdRollo->millar; }
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
    { $prodTablero_caja[$regProdPaquete->cdgproducto] = number_format($regProdPaquete->millar,3,'.',''); 
      $prodTablero_sumaterminado[$regProdPaquete->cdgproducto] += $regProdPaquete->millar; }
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
    SELECT pdtoimpresion.periodo
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND 
           pdtoimpresion.sttimpresion = '1'
  GROUP BY periodo
  ORDER BY periodo DESC");

  if ($pdtoDiseno_Select->num_rows > 0)
  { $nPeriodos = $pdtoDiseno_Select->num_rows;
  
    $idPeriodo = 1;
    while ($regPdtoDiseno = $pdtoDiseno_Select->fetch_object())
    { $prodTablero_proyectos[$idPeriodo] = $regPdtoDiseno->periodo;

      // Impresiones por diseño
      $link_mysqli = conectar();
      $pdtoImpresion_Select = $link_mysqli->query("
        SELECT pdtoimpresion.cdgimpresion,
               pdtoimpresion.impresion,
               pdtodiseno.alto,
               pdtoholograma.cdgholograma,
               pdtoholograma.holograma
          FROM pdtodiseno, 
               pdtoimpresion,
               pdtoholograma      
         WHERE pdtodiseno.sttdiseno = '1' AND          
               pdtoimpresion.periodo = '".$regPdtoDiseno->periodo."' AND
               pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtodiseno.cdgholograma = pdtoholograma.cdgholograma AND
               pdtoimpresion.sttimpresion = '1'
      ORDER BY pdtodiseno.diseno,
               pdtoimpresion.impresion");
      
      if ($pdtoImpresion_Select->num_rows > 0)
      { $num_productos[$idPeriodo] = $pdtoImpresion_Select->num_rows;

        $idProducto = 1;
        while ($regPdtoImpresion = $pdtoImpresion_Select->fetch_object())
        { $prodTablero_impresion[$idPeriodo][$idProducto] =  $regPdtoImpresion->impresion;
          $prodTablero_cdgimpresion[$idPeriodo][$idProducto] =  $regPdtoImpresion->cdgimpresion;
          $prodTablero_alto[$idPeriodo][$idProducto] = $regPdtoImpresion->alto;         
          $prodTablero_holograma[$idPeriodo][$idProducto] = $regPdtoImpresion->holograma;
          $prodTablero_cdgholograma[$idPeriodo][$idProducto] = $regPdtoImpresion->cdgholograma;

          $idProducto++; }
      }  

      $idPeriodo++; }
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

        echo '
    <table align="center">
      <tbody>';

  // Armado del tablero
  for ($idPeriodo=1; $idPeriodo<=$nPeriodos; $idPeriodo++)
  { for ($idProducto=1; $idProducto<=$num_productos[$idPeriodo]; $idProducto++)
    { $prodTablero_total = ($prodTablero_sumaimpresion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_sumasliteo[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_sumafusion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_queso[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_caja[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]);

      if ($prodTablero_total > 0 OR $prodTablero_programado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0 OR $prodTablero_productoenv[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0 OR $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0 OR $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0 OR $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0 OR $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]] > 0)
      { $prodTablero_sumexistencia[0] += $prodTablero_total;
        $prodTablero_sumexistencia[$idPeriodo] += $prodTablero_total; 

        $sinprogramar = 0;
        $sinprogramar = $prodTablero_total-$prodTablero_vendido[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]];
    
        if ($sinprogramar >= 0) 
        { $colortexto = 'blue'; }
        else 
        { $colortexto = 'red'; 

          $sinprogramar = $prodTablero_vendido[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-$prodTablero_total; }

        echo '
        <tr><th>Producto</th>
          <th>'.utf8_decode('Impresión').'</th>
          <th>'.utf8_decode('Sliteo').'</th>
          <th>'.utf8_decode('Fusion').'</th>
          <th>'.utf8_decode('Empaque').'</th>
          <th><label title="Antes del '.$prodTablero_fechas[fchfinalA].'"> O.C. 07 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialB].' al '.$prodTablero_fechas[fchfinalB].'"> O.C. 14 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialC].' al '.$prodTablero_fechas[fchfinalC].'"> O.C. 21 </label></th>
          <th><label title="Despues del '.$prodTablero_fechas[fchinicialD].'"> O.C. +21 </label></th></tr>
        <tr align="right">
          <td>
            <article>
              <dl><dt align="left"><b>'.$prodTablero_impresion[$idPeriodo][$idProducto].'</b><dt>
                <dd><b>'.$prodTablero_proyectos[$idPeriodo].'</b></dd>
                <dt align="left">'.utf8_decode('Histórico').'</dt>
                <dd><b>'.number_format($prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></dd>
                <dt align="left">Inventario en proceso</dt>
                <dd><b><font color="green">'.number_format($prodTablero_total,3,'.',',').'</font></b></dd>
                <dt align="left">Solicitado</dt>
                <dd><b>'.number_format(($prodTablero_vendido[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),3,'.',',').'</b></dd>                
                <dt align="left">Requerido</dt>
                <dd><b><font color="'.$colortexto.'">'.number_format(($sinprogramar),3,'.',',').'</font></b></dd>
                <dt align="left">Programado</dt>
                <dd><a href="pdf/prodLotesPdf.php?sttlote=A&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_programado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodLotesBC.php?sttlote=A&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Enviado</dt>
                <dd><b>'.number_format($prodTablero_productoenv[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b><br>
                  <b>caja </b> '.number_format($prodTablero_cajaenv[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'<br/>
                  <b>queso </b> '.number_format($prodTablero_rolloenv[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</dd>
                <dt align="left"><b>Desperdicio general</b></dt>
                <dd><h2>'.number_format(((($prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-($prodTablero_total+$prodTablero_productoenv[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]))*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),2,'.',',').' %</h2></dd>
                <dt align="left"><b>Necesidades MP</b></dt>
                <dd><dl>';

          if (($prodTablero_sumaimpresion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_sumasliteo[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]) > 0)
          { if ($prodTablero_cdgholograma[$idPeriodo][$idProducto] != '000000')
            { echo '
                  <dt align="left">'.$prodTablero_holograma[$idPeriodo][$idProducto].'</dt>
                  <dd><b>'.number_format((($prodTablero_sumaimpresion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]+$prodTablero_sumasliteo[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]])*$prodTablero_alto[$idPeriodo][$idProducto]),3,'.',',').'</b> Mts.</dd>'; }
          }
          
          if ($colortexto == 'red')
          { $link_mysqli = conectar();
            $querySelect = $link_mysqli->query("
              SELECT pdtodiseno.cdgsustrato,
                     pdtosustrato.sustrato,
                (((((pdtodiseno.ancho*2)+pdtodiseno.empalme)+(pdtodiseno.registro/pdtodiseno.alpaso))*(pdtodiseno.alto/1000))/pdtosustrato.rendimiento) AS consumo
                FROM pdtodiseno, 
                     pdtoimpresion,
                     pdtosustrato
               WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                     pdtodiseno.cdgsustrato = pdtosustrato.cdgsustrato AND
                     pdtoimpresion.cdgimpresion = '".$prodTablero_cdgimpresion[$idPeriodo][$idProducto]."'"); 

            if ($querySelect->num_rows > 0)
            { $regQuery = $querySelect->fetch_object();
              
              $bumMP_sustrato = $regQuery->sustrato;
              $bumMP_consumo = $regQuery->consumo; 

              echo '
                  <dt align="left">'.$bumMP_sustrato.'</dt>
                  <dd><b>'.number_format(($sinprogramar*$bumMP_consumo),3,'.',',').'</b> Kgs.</dd>'; }

            $link_mysqli = conectar();
            $querySelect = $link_mysqli->query("
              SELECT pdtoimpresiontnt.cdgtinta,
                     pdtopantone.pantone,
                     pdtoimpresiontnt.consumo
                FROM pdtoimpresiontnt,
                     pdtopantone
               WHERE pdtoimpresiontnt.cdgtinta = pdtopantone.HTML AND
                     pdtoimpresiontnt.cdgimpresion = '".$prodTablero_cdgimpresion[$idPeriodo][$idProducto]."'
            ORDER BY pdtoimpresiontnt.notinta");

            if ($querySelect->num_rows > 0)
            { while ($regQuery = $querySelect->fetch_object())
              { echo '
                  <dt align="left">'.$regQuery->pantone.'</dt>
                  <dd><b>'.number_format(($regQuery->consumo*$sinprogramar),3,'.',',').'</b> Kgs. <label style="border-radius: 4px;background-color:#'.$regQuery->cdgtinta.';color:#'.$regQuery->cdgtinta.'">Color</label></dd>'; }
            }
          }

          echo '</dl></dd>
              </dl>
            </article>
          </td>
          <td>
            <article>
              <dl>
                <dt align="left">Procesado</dt>
                <dd><a href="pdf/prodLotesPdf.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_impreso[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodLotesBC.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Liberado</dt>
                <dd><a href="pdf/prodLotesPdf.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_liberado0[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodLotesBC.php?sttlote=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Desperdicio</dt>
                <dd><b>'.number_format(((($prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-$prodTablero_impresoHist[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),2,'.',',').' %</b></dd>
                <dt align="left">Inventario</dt>
                <dd><h2>'.number_format($prodTablero_sumaimpresion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</h2></dd>
              </dl>
            </article>
          </td>
          <td>
            <article>
              <dl>
                <dt align="left">Procesado</dt>
                <dd><a href="pdf/prodBobinasPdf.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_refilado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodBobinasBC.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Liberado</dt>
                <dd><a href="pdf/prodBobinasPdf.php?sttbobina=6&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_liberado1[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodBobinasBC.php?sttbobina=6&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Transferido</dt>
                <dd><a href="pdf/prodBobinasPdf.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_transferido[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodBobinasBC.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Recibido</dt>
                <dd><a href="pdf/prodBobinasPdf.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_recibido[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodBobinasBC.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Desperdicio</dt>
                <dd><b>'.number_format(((($prodTablero_impresoHistR[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-$prodTablero_refiladoHist[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),2,'.',',').' %</b></dd>
                <dt align="left">Inventario</dt>
                <dd><h2>'.number_format($prodTablero_sumasliteo[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</h2></dd>                                
              </dl>
            </article>         
          </td>
          <td>
            <article>
              <dl>
                <dt align="left">Procesado</dt>
                <dd><b><a href="pdf/prodRollosPdf.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.number_format($prodTablero_fusionado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodRollosBC.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Revisado</dt>
                <dd><a href="pdf/prodRollosPdf.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_revisado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodRollosBC.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Liberado</dt>
                <dd><a href="pdf/prodRollosPdf.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_liberado2[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodRollosBC.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Cortado*</dt>
                <dd><a href="pdf/prodPaquetesPdf.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank"><b>'.number_format($prodTablero_paquete[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="pdf/prodPaqueteBC.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Desperdicio</dt>
                <dd><b>'.number_format(((($prodTablero_refiladoHistF[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-$prodTablero_fusionadoHist[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),2,'.',',').' | '.number_format(((($prodTablero_cortadoHist[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]-$prodTablero_paqueteAct[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]])*100)/$prodTableroHistorico[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]]),2,'.',',').'* %</b></dd>
                <dt align="left">Inventario</dt>
                <dd><h2>'.number_format($prodTablero_sumafusion[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</h2></dd>
              </dl>
            </article>
          </td>
          <td>
            <article>
              <dl>
                <dt align="left">En caja</dt>
                <dd><a href="excel/prodPaquete.php?cdgimpresion='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'"><b>'.number_format($prodTablero_caja[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgimpresion='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">En queso</dt>
                <dd><a href="excel/prodRollo.php?cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'"><b>'.number_format($prodTablero_queso[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</b></a>
                  <a href="../sm_almacenpt/pdf/alptEmbarqueBCE.php?cdgimpresion='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.$png_barcode.'</a></dd>
                <dt align="left">Inventario</dt>
                <dd><h2>'.number_format($prodTablero_sumaterminado[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</h2></dd>
              </dl>
            </article>          
          </td>
          <td>
            <article>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'
              <label title="'.$prodTablero_empaque[$idEmpaque].'"><b>'.$prodTablero_cdgempaque[$idEmpaque].'</b> '.$prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].'</label><br>'; }
          }

          echo '
              <label title="'.$prodTablero_impresion[$idPeriodo][$idProducto].'"><h2><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialA].'&hstfecha='.$prodTablero_fechas[fchfinalA].'&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</a></h2></label>
            </article>
          </td>
          <td>
            <article>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'
              <label title="'.$prodTablero_empaque[$idEmpaque].'"><b>'.$prodTablero_cdgempaque[$idEmpaque].'</b> '.$prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].'</label><br>'; }
          }

          echo '
              <label title="'.$prodTablero_impresion[$idPeriodo][$idProducto].'"><h2><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialB].'&hstfecha='.$prodTablero_fechas[fchfinalB].'&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoB[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</a></h2></label>
            </article>
          </td>          
          <td>
            <article>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'
              <label title="'.$prodTablero_empaque[$idEmpaque].'"><b>'.$prodTablero_cdgempaque[$idEmpaque].'</b> '.$prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].'</label><br>'; }
          }

          echo '
              <label title="'.$prodTablero_impresion[$idPeriodo][$idProducto].'"><h2><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialC].'&hstfecha='.$prodTablero_fechas[fchfinalC].'&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoC[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</a></h2></label>
            </article>
          </td> 
          <td>
            <article>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoDD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'
              <label title="'.$prodTablero_empaque[$idEmpaque].'"><b>'.$prodTablero_cdgempaque[$idEmpaque].'</b> '.$prodTablero_vendidoDD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].'</label><br>'; }
          }

          echo '
              <label title="'.$prodTablero_impresion[$idPeriodo][$idProducto].'"><h2><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialD].'&hstfecha=2099-12-31&cdgproducto='.$prodTablero_cdgimpresion[$idPeriodo][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoD[$prodTablero_cdgimpresion[$idPeriodo][$idProducto]],3,'.',',').'</a></h2></label>
            </article>
          </td>
        </tr>';  
        }
      }
    }

    echo '
      </tbody>       
    </table>';  
?>
    </div>
  </body>
</html>
