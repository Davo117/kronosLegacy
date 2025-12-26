<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
/*
  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("
    SELECT prodlote.cdglote,
      prodlote.cdgmezcla,
      prodbobina.cdgbobina,
      prodrollo.cdgrollo,
      prodrollo.cdgproducto
    FROM prodlote,
      prodbobina,
      prodrollo
    WHERE prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgproducto = ''");

  if ($prodRolloSelect->num_rows > 0)
  { while ($regProdRollo = $prodRolloSelect->fetch_object())
    { $link_mysqli = conectar();
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT pdtoimpresion.cdgimpresion
        FROM pdtoimpresion,
          pdtomezcla
        WHERE pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion AND 
          pdtomezcla.cdgmezcla = '".$regProdRollo->cdgmezcla."'");

      if ($pdtoImpresionSelect->num_rows > 0)
      { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

        $link_mysqli = conectar();
        $link_mysqli->query("
          UPDATE prodrollo
          SET cdgproducto = '".$regPdtoImpresion->cdgimpresion."'
          WHERE cdgrollo = '".$regProdRollo->cdgrollo."'"); 
      }
    }
  }   //*/

  // Mezclas 
  $link_mysqli = conectar();
  $pdtoMezclaSelect = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
      pdtoimpresion.impresion
    FROM prodlote,
      prodbobina,
      prodrollo,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND     
      prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND
      sttrollo >= 1
    GROUP BY pdtoimpresion.cdgimpresion");

  $id_mezcla = 1;
  while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
  { $pdtoMezcla_cdgmezcla[$id_mezcla] =  $regPdtoMezcla->cdgmezcla; 
    $pdtoMezcla_cdgimpresion[$id_mezcla] =  $regPdtoMezcla->cdgimpresion;
    $pdtoMezcla_impresion[$id_mezcla] =  $regPdtoMezcla->impresion; 
    $pdtoMezcla_idmezcla[$id_mezcla] =  $regPdtoMezcla->idmezcla; 
    $pdtoMezcla_mezcla[$id_mezcla] =  $regPdtoMezcla->mezcla; 

    $id_mezcla++; }

  $nummezclas = $pdtoMezclaSelect->num_rows;
  $pdtoMezclaSelect->close;

  // Producto
  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("  
    SELECT pdtoimpresion.cdgimpresion,
      prodrollo.sttrollo,
     (SUM(prodrollo.longitud)/pdtoimpresion.corte) AS millares
    FROM prodlote,
      prodbobina,
      prodrollo,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND     
      prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND
     (sttrollo BETWEEN 1 AND 8)
    GROUP BY pdtoimpresion.cdgimpresion,
      prodrollo.sttrollo");

  while ($regProdRollo = $prodRolloSelect->fetch_object())
  { $prodRollo_cantidad[$regProdRollo->cdgimpresion][$regProdRollo->sttrollo] = $regProdRollo->millares; }  

  $prodRolloSelect->close;

  $link_mysqli = conectar();
  $prodRolloSelect = $link_mysqli->query("  
SELECT pdtoimpresion.cdgimpresion,
 (SUM(prodrollo.longitud)/pdtoimpresion.corte) AS millares 
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
GROUP BY pdtoimpresion.cdgimpresion");

  while ($regProdRollo = $prodRolloSelect->fetch_object())
  { $prodRollo_cantidad[$regProdRollo->cdgimpresion]['9'] = $regProdRollo->millares; }  

  $prodRolloSelect->close;

  
  echo '
    <table align="center">
      <thead>
        <tr><th>Producto</th> 
          <th>Fusionado</th>
          <th>Revisado</th>
          <th>Liberado</th>
          <th>Pesado</th>
          <th>Empacado</th>
          <th>Total</th></tr>
      </thead>
      <tbody>';

  for ($id_mezcla = 1; $id_mezcla <= $nummezclas; $id_mezcla++)
  { $tablero_cantidadsuma = 0;

    echo '
        <tr align="right">
          <td align="left">'.$pdtoMezcla_impresion[$id_mezcla].'<br/>
            '.$pdtoMezcla_idmezcla[$id_mezcla].'<br/>
            '.$pdtoMezcla_mezcla[$id_mezcla].'<br/></td>';

    if ($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'] > 0)
    { $tablero_cantidad = number_format($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=1&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=1&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'];

    echo '       
          <td>'.$tablero_cantidad.'</td>';

    if ($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'] > 0)
    { $tablero_cantidad = number_format($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=6&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=6&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; } 

    $tablero_cantidadsuma += $prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'];

    echo '       
          <td>'.$tablero_cantidad.'</td>';  

    if ($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'] > 0)
    { $tablero_cantidad = number_format($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=7&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=7&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'];

      echo '       
          <td>'.$tablero_cantidad.'</td>';
                                                  
    if ($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'] > 0)
    { $tablero_cantidad = number_format($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=8&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=8&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'];

      echo '       
          <td>'.$tablero_cantidad.'</td>'; 

    if ($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'] > 0)
    { $tablero_cantidad = number_format($prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=9&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBCE.php?sttrollo=9&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>
            <a href="../sm_almacenpt/pdf/alptEmbarqueBCE.php?cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_delivery.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodRollo_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'];

      echo '       
          <td>'.$tablero_cantidad.'</td>
          <td><strong>'.number_format($tablero_cantidadsuma,3).'</strong></td></tr>'; }          

  echo '
      </tbody>
      <tfoot>
        <tr><td colspan="7" align="right">
          Cantidades expresadas en <strong>millares</strong>  /  Ultima actualizaci&oacute;n <strong>'.date("H-m-d H:i:s").'</strong></td></tr>      
      </tfoot>
    </table>';
    
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>