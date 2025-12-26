<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
/*
  $prodTablero_ano = date("Y"); //Año en YYYY
  $prodTablero_mesano = date("n"); //Mes del 1 al 12
  $prodTablero_diames = date("j"); //Día del 1 al 31

  for ($id = 0; $id <= 21; $id++)
  { if (checkdate($prodTablero_mesano, $prodTablero_diames, $prodTablero_ano))
    { $prodTablero_semana = date("W", mktime(0,0,0,$prodTablero_mesano, $prodTablero_diames, $prodTablero_ano));
    
      echo 'Semana: '.$prodTablero_semana;

    }
    else
    {
    $prodTablero_diasxmes = date("t", mktime(0,0,0,$prodTablero_mesano, $prodTablero_diames, $prodTablero_ano)); //Dias del mes 28/31

    if (($prodTablero_diames+1) > $prodTablero_diasxmes)
    { $prodTablero_diames = $prodTablero_diasxmes-($prodTablero_diames+1); 
      $prodTablero_mesano++; }
    else
    { $prodTablero_diames = $prodTablero_diames; 
      $prodTablero_mesano = $prodTablero_mesano; }      

    }
  } //*/

  $prodTablero_dsdano = date("Y");
  $prodTablero_dsdmes = date("n");
  $prodTablero_dsddia = date("j");

  $prodTablero_diasmes = date("t", mktime(0,0,0,($prodTablero_dsdmes),1,$prodTablero_dsdano));

  if (($prodTablero_dsddia+6) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstano = (date("Y")+1); 
      $prodTablero_hstmes = 1;
      $prodTablero_hstdia = (($prodTablero_dsddia+6)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstano = $prodTablero_dsdano; 
      $prodTablero_hstmes = (date("n")+1);
      $prodTablero_hstdia = (($prodTablero_dsddia+6)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstano = date("Y");
    $prodTablero_hstmes = date("n");
    $prodTablero_hstdia = (date("j")+6); 
  } 
  
  $prodTablero_fechas[fchinicialA] = '2013-01-01';
  $prodTablero_fechas[fchfinalA] = $prodTablero_hstano.'-'.str_pad($prodTablero_hstmes,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdia,2,'0',STR_PAD_LEFT);


  if (($prodTablero_dsddia+7) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoB = (date("Y")+1); 
      $prodTablero_hstmesB = 1;
      $prodTablero_hstdiaB = (($prodTablero_dsddia+7)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstanoB = $prodTablero_dsdano; 
      $prodTablero_hstmesB = (date("n")+1);
      $prodTablero_hstdiaB = (($prodTablero_dsddia+7)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstanoB = date("Y");
    $prodTablero_hstmesB = date("n");
    $prodTablero_hstdiaB = (date("j")+7); }

  $prodTablero_fechas[fchinicialB] = $prodTablero_hstanoB.'-'.str_pad($prodTablero_hstmesB,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaB,2,'0',STR_PAD_LEFT);

  if (($prodTablero_dsddia+13) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoB = (date("Y")+1); 
      $prodTablero_hstmesB = 1;
      $prodTablero_hstdiaB = (($prodTablero_dsddia+13)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstanoB = $prodTablero_dsdano; 
      $prodTablero_hstmesB = (date("n")+1);
      $prodTablero_hstdiaB = (($prodTablero_dsddia+13)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstanoB = date("Y");
    $prodTablero_hstmesB = date("n");
    $prodTablero_hstdiaB = (date("j")+13); }

  $prodTablero_fechas[fchfinalB] = $prodTablero_hstanoB.'-'.str_pad($prodTablero_hstmesB,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaB,2,'0',STR_PAD_LEFT);
  

  if (($prodTablero_dsddia+14) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoC = (date("Y")+1); 
      $prodTablero_hstmesC = 1;
      $prodTablero_hstdiaC = (($prodTablero_dsddia+14)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstanoC = $prodTablero_dsdano; 
      $prodTablero_hstmesC = (date("n")+1);
      $prodTablero_hstdiaC = (($prodTablero_dsddia+14)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstanoC = date("Y");
    $prodTablero_hstmesC = date("n");
    $prodTablero_hstdiaC = (date("j")+14); }

  $prodTablero_fechas[fchinicialC] = $prodTablero_hstanoC.'-'.str_pad($prodTablero_hstmesC,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaC,2,'0',STR_PAD_LEFT);

  if (($prodTablero_dsddia+20) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoC = (date("Y")+1); 
      $prodTablero_hstmesC = 1;
      $prodTablero_hstdiaC = (($prodTablero_dsddia+20)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstanoC = $prodTablero_dsdano; 
      $prodTablero_hstmesC = (date("n")+1);
      $prodTablero_hstdiaC = (($prodTablero_dsddia+20)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstanoC = date("Y");
    $prodTablero_hstmesC = date("n");
    $prodTablero_hstdiaC = (date("j")+20); }

  $prodTablero_fechas[fchfinalC] = $prodTablero_hstanoC.'-'.str_pad($prodTablero_hstmesC,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaC,2,'0',STR_PAD_LEFT);


if (($prodTablero_dsddia+21) > $prodTablero_diasmes)
  { if ($prodTablero_dsdmes == 12)
    { $prodTablero_hstanoD = (date("Y")+1); 
      $prodTablero_hstmesD = 1;
      $prodTablero_hstdiaD = (($prodTablero_dsddia+21)-$prodTablero_diasmes);
    } 
    else
    { $prodTablero_hstanoD = $prodTablero_dsdano; 
      $prodTablero_hstmesD = (date("n")+1);
      $prodTablero_hstdiaD = (($prodTablero_dsddia+21)-$prodTablero_diasmes);
    }
  }
  else
  { $prodTablero_hstanoD = date("Y");
    $prodTablero_hstmesD = date("n");
    $prodTablero_hstdiaD = (date("j")+21); }

  $prodTablero_fechas[fchinicialD] = $prodTablero_hstanoD.'-'.str_pad($prodTablero_hstmesD,2,'0',STR_PAD_LEFT).'-'.str_pad($prodTablero_hstdiaD,2,'0',STR_PAD_LEFT);

  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto, SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE sttlote = '1'
    GROUP BY cdgproducto");

  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendido[$regVntsOClote->cdgproducto] = number_format($regVntsOClote->millar,3,'.',''); }
  }

  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT vntsoclote.cdgempaque, pdtoempaque.empaque
    FROM vntsoclote, pdtoempaque
    WHERE vntsoclote.cdgempaque = pdtoempaque.cdgempaque AND
      vntsoclote.sttlote = '1'
    GROUP BY vntsoclote.cdgempaque, pdtoempaque.empaque");

  if ($vntsOCloteSelect->num_rows > 0)
  { $idEmpaque = 1;
    while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_cdgempaque[$idEmpaque] = $regVntsOClote->cdgempaque;
      $prodTablero_empaque[$idEmpaque] = $regVntsOClote->empaque; 

      $idEmpaque++; }

    $numEmpaques = $vntsOCloteSelect->num_rows;
  }

  $link_mysqli = conectar();
  $vntsOCloteSelect = $link_mysqli->query("
    SELECT cdgproducto, cdgempaque, SUM(cantidad-surtido) AS millar
    FROM vntsoclote
    WHERE fchembarque BETWEEN '".$prodTablero_fechas[fchinicialA]."' AND '".$prodTablero_fechas[fchfinalA]."' AND
      sttlote = '1'
    GROUP BY cdgproducto, cdgempaque");

  if ($vntsOCloteSelect->num_rows > 0)
  { while ($regVntsOClote = $vntsOCloteSelect->fetch_object())
    { $prodTablero_vendidoA[$regVntsOClote->cdgproducto] += number_format($regVntsOClote->millar,3,'.',''); 
      $prodTablero_vendidoAD[$regVntsOClote->cdgproducto][$regVntsOClote->cdgempaque] = number_format($regVntsOClote->millar,3,'.','');  }
  }

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

  $link_mysqli = conectar();
  $prodLoteSelect = $link_mysqli->query("
    SELECT prodlote.cdgproducto,
      prodlote.cdgproceso,
     ((SUM(prodlote.longitud)/pdtoimpresion.corte)*pdtoimpresion.alpaso) AS millar
    FROM prodlote,
      pdtoimpresion
    WHERE prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
      prodlote.sttlote = '1'
    GROUP BY prodlote.cdgproducto,
      prodlote.cdgproceso");

  if ($prodLoteSelect->num_rows > 0)
  { while ($regProdLote = $prodLoteSelect->fetch_object())
    { if ($regProdLote->cdgproceso == '10' AND $regProdLote->millar > .001)
      { $prodTablero_programado[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); }

      if ($regProdLote->cdgproceso == '30' AND $regProdLote->millar > .001)
      { $prodTablero_impreso[$regProdLote->cdgproducto] = number_format($regProdLote->millar,3,'.',''); }
    }
  }

  $link_mysqli = conectar();
  $prodBobinaSelect = $link_mysqli->query("
    SELECT prodbobina.cdgproducto,
      prodbobina.sttbobina,
     (SUM(prodbobina.longitud)/pdtoimpresion.corte) AS millar
    FROM prodbobina,
      pdtoimpresion
    WHERE prodbobina.cdgproducto = pdtoimpresion.cdgimpresion
    GROUP BY prodbobina.cdgproducto,
      prodbobina.sttbobina");

  if ($prodBobinaSelect->num_rows > 0)
  { while ($regProdBobina = $prodBobinaSelect->fetch_object())
    { if ($regProdBobina->sttbobina == '1' AND $regProdBobina->millar > .001)
      { $prodTablero_refilado[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '7' AND $regProdBobina->millar > .001)
      { $prodTablero_liberado1[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '8' AND $regProdBobina->millar > .001)
      { $prodTablero_transferido[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }

      if ($regProdBobina->sttbobina == '9' AND $regProdBobina->millar > .001)
      { $prodTablero_enbobina[$regProdBobina->cdgproducto] = number_format($regProdBobina->millar,3,'.',''); }
    }
  }

  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("
    SELECT prodrollo.cdgproducto,
      prodrollo.sttrollo,
     (SUM(prodrollo.longitud)/pdtoimpresion.corte) AS millar
    FROM prodrollo,
      pdtoimpresion
    WHERE prodrollo.cdgproducto = pdtoimpresion.cdgimpresion
    GROUP BY prodrollo.cdgproducto,
      prodrollo.sttrollo");

  if ($prodRolloSelect->num_rows > 0)
  { while ($regProdRollo = $prodRolloSelect->fetch_object())
    { if ($regProdRollo->sttrollo == '1' AND $regProdRollo->millar > .001)
      { $prodTablero_fusionado[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }

      if ($regProdRollo->sttrollo == '6' AND $regProdRollo->millar > .001)
      { $prodTablero_revisado[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }

      if ($regProdRollo->sttrollo == '7' AND $regProdRollo->millar > .001)
      { $prodTablero_liberado2[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }

      if ($regProdRollo->sttrollo == '8' AND $regProdRollo->millar > .001)
      { $prodTablero_pesado[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }
    }
  }

  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("
    SELECT prodrollo.cdgproducto,
     (SUM(prodrollo.longitud)/pdtoimpresion.corte) AS millar
    FROM alptempaque,
      alptempaquer,
      prodlote,
      prodbobina,
      prodrollo,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND
      alptempaque.cdgempaque = alptempaquer.cdgempaque AND
      alptempaquer.cdgrollo = prodrollo.cdgrollo AND
      alptempaque.sttempaque = '1'
    GROUP BY prodrollo.cdgproducto");

  if ($prodRolloSelect->num_rows > 0)
  { while ($regProdRollo = $prodRolloSelect->fetch_object())
    { $prodTablero_queso[$regProdRollo->cdgproducto] = number_format($regProdRollo->millar,3,'.',''); }
  }

  // Producto
  $link_mysqli = conectar();
  $prodPaqueteSelect = $link_mysqli->query("
    SELECT prodpaquete.cdgproducto,
      prodpaquete.sttpaquete,
  SUM(prodpaquete.cantidad) AS millar
    FROM prodpaquete
    GROUP BY prodpaquete.cdgproducto,
      prodpaquete.sttpaquete");

  if ($prodPaqueteSelect->num_rows > 0)
  { while ($regProdPaquete = $prodPaqueteSelect->fetch_object())
    { if ($regProdPaquete->sttpaquete == '1')
      { $prodTablero_paquete[$regProdPaquete->cdgproducto] = number_format($regProdPaquete->millar,3,'.',''); }
    }
  }

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

  $alptEmpaqueP_Select = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
	  pdtoimpresion.impresion,
	  SUM(alptempaquep.cantidad)  AS millares
	FROM pdtodiseno,
	  pdtoimpresion,
	  alptempaque,
	  alptempaquep
	WHERE pdtodiseno.sttdiseno = '1' AND
	  pdtodiseno.cdgdiseno = pdtoimpresion.cdgproyecto AND
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
    SELECT proyecto
    FROM pdtodiseno
    WHERE sttdiseno = '1'
    GROUP BY proyecto
    ORDER BY proyecto");

  if ($pdtoDiseno_Select->num_rows > 0)
  { $num_proyectos = $pdtoDiseno_Select->num_rows;
  
    $idProyecto = 1;
    while ($regPdtoDiseno = $pdtoDiseno_Select->fetch_object())
    { $prodTablero_proyectos[$idProyecto] = $regPdtoDiseno->proyecto;
  
      // Diseño
      $link_mysqli = conectar();
      $pdtoImpresion_Select = $link_mysqli->query("
        SELECT pdtoimpresion.cdgimpresion,
          pdtoimpresion.impresion
        FROM pdtodiseno, 
          pdtoimpresion
        WHERE pdtodiseno.sttdiseno = '1' AND          
          pdtodiseno.proyecto = '".$regPdtoDiseno->proyecto."' AND
          pdtodiseno.cdgdiseno = pdtoimpresion.cdgproyecto AND
          pdtoimpresion.sttimpresion = '1'
        ORDER BY pdtodiseno.diseno,
          pdtoimpresion.impresion");
      
      if ($pdtoImpresion_Select->num_rows > 0)
      { $num_productos[$idProyecto] = $pdtoImpresion_Select->num_rows;

        $idProducto = 1;
        while ($regPdtoImpresion = $pdtoImpresion_Select->fetch_object())
        { $prodTablero_impresion[$idProyecto][$idProducto] =  $regPdtoImpresion->impresion;
          $prodTablero_cdgimpresion[$idProyecto][$idProducto] =  $regPdtoImpresion->cdgimpresion;

          $idProducto++; 
        }
      }  

      $idProyecto++; 
    }
  }

  for ($idProyecto=1; $idProyecto<=$num_proyectos; $idProyecto++)
  { echo '
  
    <table align="center">
      <thead>
        <tr><td align="left" colspan="15" style="background:#FFFFFF"><strong>'.$prodTablero_proyectos[$idProyecto].'</strong></td></tr>
        <tr><th>Producto</th>          
          <th>ImpresiOn</th>
          <th>FusiOn</th>
          <th>AlmacEn<br>Producto terminado</th>
          <th>Inventario</th>
          <th>AlmacEn<br>Materia Prima</th>
          <th><label title="Hasta '.$prodTablero_fechas[fchfinalA].'"> O.C. 07 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialB].' al '.$prodTablero_fechas[fchfinalB].'"> O.C. 14 </label></th>
          <th><label title="Del '.$prodTablero_fechas[fchinicialC].' al '.$prodTablero_fechas[fchfinalC].'"> O.C. 21 </label></th>
          <th><label title="Desde '.$prodTablero_fechas[fchinicialD].'"> O.C. +21 </label></th>
          <th>Enviado</th></tr>
      </thead>
      <tbody>';        

    for ($idProducto=1; $idProducto<=$num_productos[$idProyecto]; $idProducto++)
    {  $prodTablero_total = ($prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_pesado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]+$prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);

      if ($prodTablero_total > 0 OR $prodTablero_programado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0 OR $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
      { echo '
        <tr align="right">
          <td align="left"><strong>'.$prodTablero_impresion[$idProyecto][$idProducto].'</strong></td>
          <td>
            <table>';

        if ($prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumimpresion[0] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumimpresion[$idProyecto] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
          echo '
              <tr><td align="right"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Impreso">'.number_format($prodTablero_impreso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodLotesPdf.php?cdgproceso=30&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodLotesBC.php?cdgproceso=30&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; };

        if ($prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumrefilado[0] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumrefilado[$idProyecto] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right" bgcolor="#FBA3A3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Refilado">'.number_format($prodTablero_refilado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumliberadob[0] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumliberadob[$idProyecto] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right" bgcolor="#FBCFA3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Liberado">'.number_format($prodTablero_liberado1[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumtranferido[0] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumtranferido[$idProyecto] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumaimpresion[$idProyecto][$idProducto] += $prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
          echo '
              <tr><td align="right" bgcolor="#A3FBA9"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Transferido">'.number_format($prodTablero_transferido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=8&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        echo '
              <tr><td colspan="3" align="right"><label title="Suma">'.number_format($prodTablero_sumaimpresion[$idProyecto][$idProducto],3,'.',',').'</label></td></tr>
            </table></td>
          <td>
            <table>';

        if ($prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumbobina[0] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumbobina[$idProyecto] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          
          echo '
              <tr><td align="right"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Por fusionar">'.number_format($prodTablero_enbobina[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodBobinasPdf.php?sttbobina=9&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodBobinasBC.php?sttbobina=9&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumfusionado[0] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumfusionado[$idProyecto] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right" bgcolor="#FBA3A3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Fusionado">'.number_format($prodTablero_fusionado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumrevisado[0] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumrevisado[$idProyecto] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right" bgcolor="#FBCFA3"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Revisado">'.number_format($prodTablero_revisado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=6&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumliberador[0] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumliberador[$idProyecto] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          $prodTablero_sumafusion[$idProyecto][$idProducto] += $prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right" bgcolor="#A3FBA9"><b><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' Liberado">'.number_format($prodTablero_liberado2[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></b></td>
                <td><a href="pdf/prodRollosPdf.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a></td>
                <td><a href="pdf/prodRollosBC.php?sttrollo=7&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        echo '
              <tr><td colspan="3" align="right"><label title="Suma">'.number_format($prodTablero_sumafusion[$idProyecto][$idProducto],3,'.',',').'</label></td></tr>
            </table>
          </td>
          <td style="background:#99BCBF">
            <table>';

        if ($prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumempacadoq[0] += $prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumempacadoq[$idProyecto] += $prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
              <tr><td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">Queso: <strong>'.number_format($prodTablero_queso[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong><br/>
                  <a href="excel/prodRollo.php?cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'">'.$jpg_excel.'</a>
                  <a href="../sm_almacenpt/pdf/alptEmbarqueBCE.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumpaquete[0] += $prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumpaquete[$idProyecto] += $prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
         
          echo '
              <tr><td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">Cortado: '.number_format($prodTablero_paquete[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label><br/>
                  <a href="pdf/prodPaquetesPdf.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a>
                  <a href="pdf/prodPaqueteBC.php?sttpaquete=1&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        if ($prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumempacadoc[0] += $prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumempacadoc[$idProyecto] += $prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '<tr><td align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">Caja: <strong>'.number_format($prodTablero_caja[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong></label><br/>
                  <a href="excel/prodPaquete.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'">'.$jpg_excel.'</a>
                  <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgimpresion='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>'; }

        echo '
            </table></td>
          <td>';

        if ($prodTablero_total > 0 OR $prodTablero_programado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumexistencia[0] += $prodTablero_total;
          $prodTablero_sumexistencia[$idProyecto] += $prodTablero_total; 
        
          echo '
            <table>
              <tr><td colspan="2" align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><font color="green">'.number_format($prodTablero_total,3,'.',',').'</font></strong></label></td></tr> 
              <tr><td colspan="2" align="right"><strong><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'">Prog. '.number_format($prodTablero_programado[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</label></strong><br/>
                  <a href="pdf/prodLotesPdf.php?cdgproceso=10&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_acrobat.'</a>
                  <a href="pdf/prodLotesBC.php?cdgproceso=10&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.$png_barcode.'</a></td></tr>
            </table>'; }

        echo '</td>
          <td>';
 
          $sinprogramar = 0;
          $sinprogramar = $prodTablero_total-$prodTablero_vendido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];

          if ($sinprogramar > 0) 
          { $colortexto = 'blue'; }
          else 
          { $colortexto = 'red'; 

            $sinprogramar = $prodTablero_vendido[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]-$prodTablero_total;

            $link_mysqli = conectar();
            $boomSelectTinta = $link_mysqli->query("
              SELECT pdtotinta.tinta, 
                pdtoimpresiontnt.consumo,
                pdtotinta.cdgtinta,
                pdtotinta.cdghex
              FROM pdtoimpresion, 
                pdtoimpresiontnt, 
                pdtotinta 
              WHERE pdtoimpresion.cdgimpresion = '".$prodTablero_cdgimpresion[$idProyecto][$idProducto]."' AND
                pdtoimpresion.cdgimpresion = pdtoimpresiontnt.cdgimpresion AND 
                pdtoimpresiontnt.cdgtinta = pdtotinta.cdgtinta
              ORDER BY pdtoimpresiontnt.notinta"); 

            if ($boomSelectTinta->num_rows > 0)
            { $numTintas = $boomSelectTinta->num_rows;
              
              $idTinta = 1;
              while ($regBoomTinta = $boomSelectTinta->fetch_object())
              { $boomTinta_tinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomTinta->tinta;
                $boomTinta_consumo[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomTinta->consumo;
                $boomTinta_cdgtinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomTinta->cdgtinta;
                $boomTinta_cdghex[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomTinta->cdghex;

                $idTinta++; }

            } else
            { $numTintas = 0; }             

            $link_mysqli = conectar();
            $boomSelectSustrato = $link_mysqli->query("
              SELECT pdtosustrato.sustrato,
                (((pdtoimpresion.ancho+pdtoimpresion.ceja+pdtoimpresion.tolerancia)*(pdtoimpresion.corte/1000))/pdtosustrato.rendimiento) AS consumo,
                pdtosustrato.cdgsustrato
              FROM pdtoimpresion,                
                pdtosustrato
              WHERE pdtoimpresion.cdgimpresion = '".$prodTablero_cdgimpresion[$idProyecto][$idProducto]."' AND
                pdtoimpresion.cdgsustrato = pdtosustrato.cdgsustrato"); 

            if ($boomSelectSustrato->num_rows > 0)
            { $regBoomSustrato = $boomSelectSustrato->fetch_object();
              
              $boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->sustrato;
              $boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->consumo; 
              $boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] = $regBoomSustrato->cdgsustrato; }
          }

          echo '
            <table align="left">
              <tr><td colspan="3" align="right"><label title="'.$prodTablero_impresion[$idProyecto][$idProducto].' '.$numTintas.' tintas"><strong><font color="'.$colortexto.'">'.number_format(($sinprogramar),3,'.',',').'</font></strong></label></td></tr>';
          
          if ($numTintas > 0 AND $colortexto == 'red')
          { echo '
              <tr><td colspan="2">Tinta</td>
                <td>Requerido</td></tr>';

            for ($idTinta=1; $idTinta<=$numTintas; $idTinta++)
            { $ampTinta_tinta[$boomTinta_cdgtinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] = $boomTinta_tinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
              $ampTinta_tintareque[$boomTinta_cdgtinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] += ($sinprogramar*$boomTinta_consumo[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);    
              
              echo '
              <tr><td>
                  <table align="center">
                    <tr><td bgcolor="'.$boomTinta_cdghex[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]].'">&nbsp;</td></tr>
                  </table></td>
                <td>'.$boomTinta_tinta[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]].'</td>                
                <td align="right"><strong>'.number_format(($sinprogramar*$boomTinta_consumo[$idTinta][$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),3,'.',',').'</strong> Kgs.</td></tr>'; }
          
            echo '
              <tr><td colspan="2">Sustrato</td>
                <td>Requerido</td></tr>';

            $ampSustrato_sustrato[$boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] = $boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
            $ampSustrato_sustratoreque[$boomSustrato_cdgsustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]][$idProyecto] += ($sinprogramar*$boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]);    
              
            echo '
              <tr><td colspan="2">'.$boomSustrato_sustrato[$prodTablero_cdgimpresion[$idProyecto][$idProducto]].'</td>                
                <td align="right"><strong>'.number_format(($sinprogramar*$boomSustrato_consumo[$prodTablero_cdgimpresion[$idProyecto][$idProducto]]),3,'.',',').'</strong> Kgs.</td></tr>'; 
          }
          echo '              
            </table>
          </td>
          <td>';          

        if ($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumAporsurtir[0] += $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumAporsurtir[$idProyecto] += $prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialA].'&hstfecha='.$prodTablero_fechas[fchfinalA].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoA[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</a></strong></label>';
            
          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoAD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
        }

        echo '
          </td>
          <td>';

        if ($prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumBporsurtir[0] += $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumBporsurtir[$idProyecto] += $prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialB].'&hstfecha='.$prodTablero_fechas[fchfinalB].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoB[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</a></strong></label>'; 

          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoBD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
        }

        echo '
          </td>
          <td>';

        if ($prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumCporsurtir[0] += $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumCporsurtir[$idProyecto] += $prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialC].'&hstfecha='.$prodTablero_fechas[fchfinalC].'&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoC[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</a></strong></label>'; 

          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoCD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
        }

        echo '
          </td>
          <td>';

        if ($prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumDporsurtir[0] += $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumDporsurtir[$idProyecto] += $prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong><a href="pdf/prodCalEntregaPdf.php?dsdfecha='.$prodTablero_fechas[fchinicialD].'&hstfecha=2099-12-31&cdgproducto='.$prodTablero_cdgimpresion[$idProyecto][$idProducto].'" target="_blank">'.number_format($prodTablero_vendidoD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</a></strong></label>'; 

          for ($idEmpaque=1; $idEmpaque<=$numEmpaques; $idEmpaque++)
          { if ($prodTablero_vendidoDD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]] > 0)
            { echo'<br/>
            <label title="'.$prodTablero_empaque[$idEmpaque].'">'.$prodTablero_vendidoDD[$prodTablero_cdgimpresion[$idProyecto][$idProducto]][$prodTablero_cdgempaque[$idEmpaque]].' <strong>'.$prodTablero_cdgempaque[$idEmpaque].'</strong></label>'; }
          }
        }

        echo '
          </td>
          <td>';          

        if ($prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]] > 0)
        { $prodTablero_sumenviado[0] += $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
          $prodTablero_sumenviado[$idProyecto] += $prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]];
        
          echo '
            <label title="'.$prodTablero_impresion[$idProyecto][$idProducto].'"><strong>'.number_format($prodTablero_productoenv[$prodTablero_cdgimpresion[$idProyecto][$idProducto]],3,'.',',').'</strong></label>'; }

        echo '</td></tr>';  
      }         
    }
    
    echo '
        <tr align="right"><td></td>          
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
              <tr><td align="right"><label title="Liberado">'.number_format($prodTablero_sumliberador[$idProyecto],3,'.',',').'</label></td></tr></table></b></th>         
          <th><b>
            <table align="right">
              <tr><td>'.number_format($prodTablero_sumempacadoq[$idProyecto],3,'.',',').'</td></tr>
              <tr><td>'.number_format($prodTablero_sumpaquete[$idProyecto],3,'.',',').'</td></tr>
              <tr><td>'.number_format($prodTablero_sumempacadoc[$idProyecto],3,'.',',').'</td></tr></table></b></th>
          <th><b>'.number_format($prodTablero_sumexistencia[$idProyecto],3,'.',',').'</b></th>
          <td>
            <table>';

      $link_mysqli = conectar();
      $pdtoTinta_Select = $link_mysqli->query("
        SELECT * FROM pdtotinta ORDER BY tinta");

      if ($pdtoTinta_Select->num_rows > 0)
      { while ($regTinta = $pdtoTinta_Select->fetch_object())
        { if ($ampTinta_tintareque[$regTinta->cdgtinta] > 0)
          { echo '
                <tr><td>'.$ampTinta_tinta[$regTinta->cdgtinta][$idProyecto].'</td>
                  <td>
                    <table align="center">
                      <tr><td bgcolor="'.$regTinta->cdghex.'">&nbsp;</td></tr>
                    </table></td>
                  <td align="right">'.number_format($ampTinta_tintareque[$regTinta->cdgtinta][$idProyecto],3,'.',',').' Kgs.</td></tr>'; }
        }        
      }

      unset($ampTinta_tinta);  
      unset($ampTinta_tintareque);  

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
          <th><b>'.number_format($prodTablero_sumAporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumBporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumCporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumDporsurtir[$idProyecto],3,'.',',').'</b></th>
          <th><b>'.number_format($prodTablero_sumenviado[$idProyecto],3,'.',',').'</b></th></tr>
      </tbody>       
    </table><br/>';      
  }

  $ahora = date("Y/m/d H:i:s");
  
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
    </table>';  
?>

  </body>
</html>
