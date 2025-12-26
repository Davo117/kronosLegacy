<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // Mezclas 
  $link_mysqli = conectar();
  $pdtoMezclaSelect = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
      pdtoimpresion.impresion,
      prodrollo.amplitud
    FROM prodlote,
      prodbobina,
      prodrollo,
      prodpaquete,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgrollo = prodpaquete.cdgrollo AND
      prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND
      sttrollo = 5
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
  $prodPaqueteSelect = $link_mysqli->query("
    SELECT pdtoimpresion.cdgimpresion,
      prodpaquete.sttpaquete,
     (COUNT(prodpaquete.cdgpaquete)*.5) AS millares
    FROM prodlote,
      prodbobina,
      prodrollo,
      prodpaquete,
      pdtoimpresion
    WHERE (prodlote.cdglote = prodbobina.cdglote AND
      prodbobina.cdgbobina = prodrollo.cdgbobina AND
      prodrollo.cdgrollo = prodpaquete.cdgrollo AND     
      prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND
      prodpaquete.sttpaquete = '1'
    GROUP BY pdtoimpresion.cdgimpresion,
      prodpaquete.sttpaquete");

  while ($regProdPaquete = $prodPaqueteSelect->fetch_object())
  { $prodPaquete_cantidad[$regProdPaquete->cdgimpresion][$regProdPaquete->sttpaquete] = $regProdPaquete->millares; }  

  $prodPaqueteSelect->close;

  $link_mysqli = conectar();
  $prodPaqueteSelect = $link_mysqli->query("  
    SELECT prodpaquete.cdgproducto,
     (COUNT(prodpaquete.cdgpaquete)*.5) AS millar
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
     (prodrollo.cdgproducto = pdtoimpresion.cdgimpresion) AND 
      alptempaque.cdgempaque = alptempaquep.cdgempaque AND       
      alptempaque.sttempaque = '1'
    GROUP BY prodpaquete.cdgproducto");

  while ($regProdPaquete = $prodPaqueteSelect->fetch_object())
  { $prodPaquete_cantidad[$regProdPaquete->cdgproducto]['9'] = $regProdPaquete->millar; }  

  $prodPaqueteSelect->close;

  echo '
    <table align="center">
      <thead>
        <tr><th>Producto</th> 
          <th>Asignado</th>';
        /*echo
          '<th>Revisado</th>
          <th>Liberado</th>
          <th>Pesado</th>'; //*/
        echo'
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

    if ($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'] > 0)
    { $tablero_cantidad = number_format($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=5&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=5&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['1'];

    echo '       
          <td>'.$tablero_cantidad.'</td>';
/*
    if ($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'] > 0)
    { $tablero_cantidad = number_format($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=6&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=6&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; } 

    $tablero_cantidadsuma += $prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['6'];

    echo '       
          <td>'.$tablero_cantidad.'</td>';  

    if ($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'] > 0)
    { $tablero_cantidad = number_format($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=7&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=7&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['7'];

      echo '       
          <td>'.$tablero_cantidad.'</td>';
                                                  
    if ($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'] > 0)
    { $tablero_cantidad = number_format($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'],3).'<br/>
            <a href="excel/prodRollo.php?sttrollo=8&cdgmezcla='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="pdf/prodRolloBC.php?sttrollo=8&cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['8'];

      echo '       
          <td>'.$tablero_cantidad.'</td>'; //*/

    if ($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'] > 0)
    { $tablero_cantidad = number_format($prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'],3).'<br/>
            <a href="excel/prodPaquete.php?cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'">'.$jpg_excel.'</a>
            <a href="../sm_almacenpt/pdf/alptEmpaqueBCEC.php?cdgimpresion='.$pdtoMezcla_cdgimpresion[$id_mezcla].'" target="_blank">'.$png_barcode.'</a>'; }
    else
    { $tablero_cantidad = ''; }  

    $tablero_cantidadsuma += $prodPaquete_cantidad[$pdtoMezcla_cdgimpresion[$id_mezcla]]['9'];

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
