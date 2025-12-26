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
    SELECT prodlote.cdgmezcla,
      pdtoimpresion.impresion, 
      pdtomezcla.idmezcla,
      pdtomezcla.mezcla
    FROM prodlote,
      prodsublote,
      pdtoimpresion,
      pdtomezcla
    WHERE (prodlote.cdglote = prodsublote.cdglote)
    AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
     AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion)
    AND sttsublote = '1'
    GROUP BY prodlote.cdgmezcla");

  $id_mezcla = 1;
  while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
  { $pdtoMezcla_cdgmezcla[$id_mezcla] =  $regPdtoMezcla->cdgmezcla; 
    $pdtoMezcla_impresion[$id_mezcla] =  $regPdtoMezcla->impresion; 
    $pdtoMezcla_idmezcla[$id_mezcla] =  $regPdtoMezcla->idmezcla; 
    $pdtoMezcla_mezcla[$id_mezcla] =  $regPdtoMezcla->mezcla; 

    $id_mezcla++; }

  $nummezclas = $pdtoMezclaSelect->num_rows;
  $pdtoMezclaSelect->close;

  // Producto
  $link_mysqli = conectar();
  $prodSubLoteSelect = $link_mysqli->query("  
    SELECT prodlote.cdgmezcla,
      prodsublote.sttsublote,
     (SUM(prodsublote.longitud)/pdtoimpresion.corte) AS millares
    FROM prodlote,
      prodsublote,
      pdtoimpresion,
      pdtomezcla
    WHERE (prodlote.cdglote = prodsublote.cdglote) AND 
      prodsublote.cdgproceso = '40' AND
     (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND 
      pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion) AND 
     (sttsublote BETWEEN '1' AND '8')
    GROUP BY prodlote.cdgmezcla,
      prodsublote.sttsublote");

  while ($regProdSubLote = $prodSubLoteSelect->fetch_object())
  { $prodSubLote_cantidad[$regProdSubLote->cdgmezcla][$regProdSubLote->sttsublote] = $regProdSubLote->millares; }  

  $prodSubLoteSelect->close;

  // Procesos
  $link_mysqli = conectar();
  $prodProcesoSelect = $link_mysqli->query("
    SELECT * FROM prodproceso
    WHERE sttproceso = '1'
    AND cdgsector = '02'
    ORDER BY cdgproceso");
    
  
  $id_proceso = 1;
  while ($regProdProceso = $prodProcesoSelect->fetch_object())
  { $prodProceso_idproceso[$id_proceso] = $regProdProceso->idproceso;
    $prodProceso_proceso[$id_proceso] = $regProdProceso->proceso;
    $prodProceso_cdgproceso[$id_proceso] = $regProdProceso->cdgproceso;
    $prodProceso_sttproceso[$id_proceso] = $regProdProceso->sttproceso; 
      
    $id_proceso++; }
      
  $numprocesos = $prodProcesoSelect->num_rows;  
  $prodProcesoSelect->close;
  
  echo '
    <table align="center">
      <thead>
        <tr><th>Producto</th> 
          <th>Terminado</th>
          <th>Liberado</th>
          <th>Transferido</th></tr>
      </thead>
      <tbody>';

  for ($id_mezcla = 1; $id_mezcla <= $nummezclas; $id_mezcla++)
  { echo '
        <tr align="right">
          <td align="left">'.$pdtoMezcla_impresion[$id_mezcla].'<br/>
            '.$pdtoMezcla_idmezcla[$id_mezcla].'<br/>
            '.$pdtoMezcla_mezcla[$id_mezcla].'<br/></td>';

    if ($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['1'] > 0)
    { $tableroapt_cantidad = number_format($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['1'],3).' <a href="excel/prodSubLote.php?sttsublote=1&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> millares'; }
    else
    { $tableroapt_cantidad = ''; }  

    echo '       
          <td><br/> '.$tableroapt_cantidad.'</td>';

    if ($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['7'] > 0)
    { $tableroapt_cantidad = number_format($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['7'],3).' <a href="excel/prodSubLote.php?sttsublote=7&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> millares'; }
    else
    { $tableroapt_cantidad = ''; }  

      echo '       
          <td><br/>'.$tableroapt_cantidad.'</td>';
                    
    if ($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['8'] > 0)
    { $tableroapt_cantidad = number_format($prodSubLote_cantidad[$pdtoMezcla_cdgmezcla[$id_mezcla]]['8'],3).' <a href="excel/prodSubLote.php?sttsublote=8&cdgmezcla='.$pdtoMezcla_cdgmezcla[$id_mezcla].'">'.$jpg_excel.'</a> millares'; }
    else
    { $tableroapt_cantidad = ''; }  

      echo '       
          <td><br/>'.$tableroapt_cantidad.'</td></tr>'; }

  echo '   
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4"></td></tr>
      </tfoot>
    </table>';
    
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>
