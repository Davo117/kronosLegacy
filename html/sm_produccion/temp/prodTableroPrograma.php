<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // Fechas de programa activos
  $link_mysqli = conectar();
  $prodLoteSelect = $link_mysqli->query("
  	SELECT fchprograma
  	FROM prodlote 
  	WHERE sttlote = '1'
  	GROUP BY fchprograma
  	ORDER BY fchprograma");

  $idfchprograma = 1;
  while ($regProdLote = $prodLoteSelect->fetch_object())
  { $tablero_fchprograma[$idfchprograma] =  $regProdLote->fchprograma; 

    // Piezas por proceso en cuanto a lotes
    $link_mysqli = conectar();
    $prodLoteDetalleSelect = $link_mysqli->query("
      SELECT pdtoimpresion.cdgproyecto,
        pdtomezcla.cdgimpresion,
        pdtoimpresion.cdgproyecto,
        pdtoproyecto.proyecto,
        pdtomezcla.cdgimpresion,
        pdtoimpresion.impresion,
        prodlote.cdgmezcla,
        pdtomezcla.mezcla,
        COUNT(noop) AS bobinas, 
        ((SUM(prodlote.longitud)*pdtoimpresion.alpaso)/pdtoimpresion.corte) AS piezas,
        prodlote.cdgproceso,
        prodlote.fchprograma  
      FROM prodlote,
        prodproclote,
        pdtoproyecto,
        pdtoimpresion,
        pdtomezcla
      WHERE (prodlote.cdglote = prodproclote.cdglote
      AND prodlote.cdgproceso = prodproclote.cdgproceso)
      AND prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtoproyecto.cdgproyecto = pdtoimpresion.cdgproyecto
      AND pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion      
      AND prodlote.sttlote = '1'
      AND prodlote.fchprograma = '".$regProdLote->fchprograma."'
      GROUP BY prodlote.fchprograma, 
        pdtoimpresion.cdgproyecto,
        pdtomezcla.cdgimpresion,
        prodlote.cdgmezcla,
        prodlote.cdgproceso");
    
    $idmezcla = 1;
    while ($regProdLoteDetalle = $prodLoteDetalleSelect->fetch_object())
    { $tablero_piezas[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->piezas;
      $tablero_bobinas[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->bobinas;
      $tablero_mezcla[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->mezcla;
      $tablero_impresion[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->impresion; 
      $tablero_proyecto[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->proyecto; 

      $idmezcla++; }

    $idsmezcla[$idfchprograma] = $prodLoteDetalleSelect->num_rows;
    $prodLoteDetalleSelect->close;

    $idfchprograma++; }

  $idsfchprograma = $prodLoteSelect->num_rows;
  $prodLoteSelect->close;

  $link_mysqli = conectar();
  $prodProcesoSelect = $link_mysqli->query("
    SELECT * FROM prodproceso
    WHERE sttproceso = '1'
    ORDER BY cdgproceso");
    
  if ($prodProcesoSelect->num_rows > 0)
  { $id_proceso = 1;
    while ($regProdProceso = $prodProcesoSelect->fetch_object())
    { $prodProceso_idproceso[$id_proceso] = $regProdProceso->idproceso;
      $prodProceso_proceso[$id_proceso] = $regProdProceso->proceso;
      $prodProceso_cdgproceso[$id_proceso] = $regProdProceso->cdgproceso;
      $prodProceso_sttproceso[$id_proceso] = $regProdProceso->sttproceso; 
      
      $id_proceso++; }
      
    $numprocesos = $prodProcesoSelect->num_rows; }
  
  $prodProcesoSelect->close;
  
  echo '
    <table align="center">
      <thead>
        <tr>
          <th>Programa</th>'; 
          
    for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++)
    { echo '
          <th>'.$prodProceso_proceso[$id_proceso].'</th>'; }
          
    echo '      
        </tr>
      </thead>
      <tbody>';

  for ($idfchprograma = 1; $idfchprograma <= $idsfchprograma; $idfchprograma++)
  { echo '
        <tr align="left">
          <td align="center"><a href="pdf/prodConsumoByFchPrograma?fchprograma='.$tablero_fchprograma[$idfchprograma].'">'.$tablero_fchprograma[$idfchprograma].'</a></td>';

    for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++)
    { echo '       
          <td>';

      if ($idsmezcla[$idfchprograma] > 0)
      { echo '
            <table>';

        for ($idmezcla = 1; $idmezcla <= $idsmezcla[$idfchprograma]; $idmezcla++)
        { if ((int)$tablero_piezas[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla] > 0) 
          { echo '
              <tr><td colspan="2">'.$tablero_proyecto[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].'<br/>
              '.$tablero_impresion[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].'<br/>
              '.$tablero_mezcla[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].'</td></tr>
              <tr align="right">
                <td><strong>[ '.$tablero_bobinas[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].' ]</strong> </td>
                <td>'.number_format($tablero_piezas[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla],3).'</td></tr>'; }
        /*else
        { echo '
              <tr><td> [] </td>
                <td></td></tr>'; } //*/
        }

        echo '
            </table>';
      }


    echo '
          </td>'; }
/*
    echo '
          <td>';

    if ($idsmezcla[$idfchprograma] > 0)
    { echo '
            <table>';

      for ($idmezcla = 1; $idmezcla <= $idsmezcla[$idfchprograma]; $idmezcla++)
      { if ((int)$tablero_piezas[$idfchprograma]['10'][$idmezcla] > 0) 
        { echo '
              <tr><td colspan="2">'.$tablero_proyecto[$idfchprograma]['10'][$idmezcla].'<br/>
              '.$tablero_impresion[$idfchprograma]['10'][$idmezcla].'<br/>
              '.$tablero_mezcla[$idfchprograma]['10'][$idmezcla].'</td></tr>              
              <tr align="right">
                <td><strong>[ '.$tablero_bobinas[$idfchprograma]['10'][$idmezcla].' ]</strong> </td>
                <td>'.number_format($tablero_piezas[$idfchprograma]['10'][$idmezcla],3).'</td></tr>'; }
        else
        { echo '
              <tr><td> [] </td>
                <td></td></tr>'; }
      }

      echo '
            </table>';
    }

    echo '</td>
          <td>';
          

    if ($idsmezcla[$idfchprograma] > 0)
    { echo '
            <table>';

      for ($idmezcla = 1; $idmezcla <= $idsmezcla[$idfchprograma]; $idmezcla++)
      { if ((int)$tablero_piezas[$idfchprograma]['20'][$idmezcla] > 0) 
        { echo '
              <tr><td colspan="2">'.$tablero_proyecto[$idfchprograma]['20'][$idmezcla].'<br/>
              '.$tablero_impresion[$idfchprograma]['20'][$idmezcla].'<br/>
              '.$tablero_mezcla[$idfchprograma]['20'][$idmezcla].'</td></tr>
              <tr align="right">
                <td><strong>[ '.$tablero_bobinas[$idfchprograma]['20'][$idmezcla].' ]</strong> </td>
                <td>'.number_format($tablero_piezas[$idfchprograma]['20'][$idmezcla],3).'</td></tr>'; }
        else
        { echo '
              <tr><td> [] </td>
                <td></td></tr>'; } 
      }

      echo '
            </table>';
    }
          

    echo '</td>
          <td>'.$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['30'].'</td>
          <td>'.$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['40'].'</td>
          <td>'.$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['50'].'</td>
          <td>'.$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['90'].'</td>
          <td>'.$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['95'].'</td>'; //*/

    echo '
      </tr>'; 
/*
          $tableros_piezas['00'] = $tableros_piezas['00']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['00'];
          $tableros_piezas['10'] = $tableros_piezas['10']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['10'];
          $tableros_piezas['20'] = $tableros_piezas['20']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['20'];
          $tableros_piezas['30'] = $tableros_piezas['30']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['30'];
          $tableros_piezas['40'] = $tableros_piezas['40']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['40'];
          $tableros_piezas['50'] = $tableros_piezas['50']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['50'];
          $tableros_piezas['90'] = $tableros_piezas['90']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['90'];
          $tableros_piezas['95'] = $tableros_piezas['95']+$tablero_piezas[$tablero_fchprograma[$idfchprograma]]['95'];//*/ }

  echo '   
      </tbody>
      <tfoot>
        <tr>
          <td></td>'; 
          
    for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++)
    { echo '
          <th>'.$prodProceso_proceso[$id_proceso].'</th>'; }
          
    echo '      
        </tr>
      </tfoot>
    </table>';
    
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>