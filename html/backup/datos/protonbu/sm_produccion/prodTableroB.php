<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
/*
  $link_mysqli = conectar();
  $prodBobinaSelect = $link_mysqli->query("
    SELECT prodbobina.cdglote,
      prodlote.cdgmezcla,
      prodbobina.cdgbobina,
      prodbobina.cdgproducto
    FROM prodlote,
      prodbobina
    WHERE prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgproducto = ''");

  if ($prodBobinaSelect->num_rows > 0)
  { while ($regProdBobina = $prodBobinaSelect->fetch_object())
    { $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT pdtoimpresion.cdgimpresion
        FROM pdtoimpresion,
          pdtomezcla
        WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion AND 
          pdtomezcla.cdgmezcla = '".$regProdBobina->cdgmezcla."'");

      if ($pdtoImpresionSelect->num_rows > 0)
      { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodbobina
          SET cdgproducto = '".$regPdtoImpresion->cdgimpresion."'
          WHERE cdgbobina = '".$regProdBobina->cdgbobina."'"); 
      }
    }
  }   //*/

  // Mezclas 
  $link_mysqli = conectar();
  $pdtoMezclaSelect = $link_mysqli->query("
    SELECT prodlote.cdgmezcla,
      pdtoimpresion.impresion, 
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla
    FROM prodlote,
      prodbobina,
      pdtoimpresion,
      pdtomezcla
    WHERE (prodlote.cdglote = prodbobina.cdglote) AND 
     (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND 
      pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion) AND 
      prodbobina.sttbobina > 0
    GROUP BY prodlote.cdgmezcla");

  $id_mezcla = 1;
  while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
  { $pdtoMezcla_cdgmezcla[$id_mezcla] =  $regPdtoMezcla->cdgmezcla; 
    $pdtoMezcla_impresion[$id_mezcla] =  $regPdtoMezcla->impresion; 
    $pdtoMezcla_idmezcla[$id_mezcla] =  $regPdtoMezcla->idmezcla; 
    $pdtoMezcla_mezcla[$id_mezcla] =  $regPdtoMezcla->mezcla; 

    $id_mezcla++; }

  $num_mezclas = $pdtoMezclaSelect->num_rows;
  $pdtoMezclaSelect->close;

  // Producto
  $link_mysqli = conectar();
  $prodBobinaSelect = $link_mysqli->query("  
    SELECT prodlote.cdgmezcla,
      prodbobina.sttbobina,
     COUNT(prodbobina.cdgbobina) AS unidades,
     (SUM(prodbobina.longitud)/pdtoimpresion.corte) AS cantidad
    FROM prodlote,
      prodbobina,
      pdtoimpresion,
      pdtomezcla
    WHERE (prodlote.cdglote = prodbobina.cdglote) AND       
     (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND 
      pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion) AND
      sttbobina > 0   
    GROUP BY prodlote.cdgmezcla,
      prodbobina.sttbobina");

  while ($regProdBobina = $prodBobinaSelect->fetch_object())
  { $prodBobina_unidades[$regProdBobina->cdgmezcla][$regProdBobina->sttbobina] = $regProdBobina->unidades;
    $prodBobina_cantidad[$regProdBobina->cdgmezcla][$regProdBobina->sttbobina] = $regProdBobina->cantidad; }  

  $prodBobinaSelect->close;
  
  echo '
    <table align="center">
      <thead>        
        <tr><th>Producto</th> 
          <th>Refilado</th>
          <th>Liberado</th>
          <th>Transferido</th>
          <th>Por Fusionar</th></tr>
      </thead>
      <tbody>';

  for ($id_mezcla = 1; $id_mezcla <= $num_mezclas; $id_mezcla++)
  { echo '
        <tr align="right">
          <td align="left">'.$pdtoMezcla_impresion[$id_mezcla].'<br/>
            '.$pdtoMezcla_idmezcla[$id_mezcla].'<br/>
            '.$pdtoMezcla_mezcla[$id_mezcla].'<br/></td>';

    if ($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['1'] > 0)
    { $tableroapt_cantidad = number_format($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['1'],3).'<br/>
            <a href="excel/prodBobina.php?sttbobina=1&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> 
            <a href="pdf/prodBobinaList.php?sttbobina=1&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$png_link.'</a> 
            <a href="pdf/prodBobinaBC.php?sttbobina=1&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tableroapt_cantidad = ''; }  

    echo '       
          <td><br/> '.$tableroapt_cantidad.'</td>';

    if ($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['7'] > 0)
    { $tableroapt_cantidad = number_format($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['7'],3).'<br/>
            <a href="excel/prodBobina.php?sttbobina=7&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> 
            <a href="pdf/prodBobinaList.php?sttbobina=7&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$png_link.'</a>
            <a href="pdf/prodBobinaBC.php?sttbobina=7&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tableroapt_cantidad = ''; }  

      echo '       
          <td><br/>'.$tableroapt_cantidad.'</td>';
                    
    if ($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['8'] > 0)
    { $tableroapt_cantidad = number_format($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['8'],3).'<br/>
            <a href="excel/prodBobina.php?sttbobina=8&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> 
            <a href="pdf/prodBobinaList.php?sttbobina=8&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$png_link.'</a>
            <a href="pdf/prodBobinaBC.php?sttbobina=8&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tableroapt_cantidad = ''; }  

      echo '       
          <td><br/>'.$tableroapt_cantidad.'</td>';
                              
    if ($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['9'] > 0)
    { $tableroapt_cantidad = number_format($prodBobina_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['9'],3).'<br/>
            <a href="excel/prodBobina.php?sttbobina=9&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> 
            <a href="pdf/prodBobinaList.php?sttbobina=9&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$png_link.'</a>
            <a href="pdf/prodBobinaBC.php?sttbobina=9&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tableroapt_cantidad = ''; }  

      echo '       
          <td><br/>'.$tableroapt_cantidad.'</td></tr>'; }

  echo '   
      </tbody>
      <tfoot>
        <tr><td colspan="5" align="right">
          Cantidades expresadas en <strong>millares</strong></td></tr>      
      </tfoot>
    </table>';
    
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>
