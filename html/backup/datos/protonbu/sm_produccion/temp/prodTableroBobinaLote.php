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
        pdtoproyecto,
        pdtoimpresion,
        pdtomezcla
      WHERE prodlote.cdgmezcla = pdtomezcla.cdgmezcla
      AND pdtoproyecto.cdgproyecto = pdtoimpresion.cdgproyecto
      AND pdtoimpresion.cdgimpresion = pdtomezcla.cdgimpresion      
      AND prodlote.sttlote = '1'
      AND prodlote.fchprograma = '".$tablero_fchprograma[$idfchprograma]."'
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
      $tablero_cdgmezcla[$idfchprograma][$regProdLoteDetalle->cdgproceso][$idmezcla] =  $regProdLoteDetalle->cdgmezcla;
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
    AND cdgsector = '01'
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
                <td><strong>[ <a href="excel/prodLote.php?fchprograma='.$tablero_fchprograma[$idfchprograma].'&cdgmezcla='.$tablero_cdgmezcla[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].'&status='.$prodProceso_proceso[$id_proceso].'">'.$tablero_bobinas[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla].'</a> ]</strong> </td>
                <td>'.number_format($tablero_piezas[$idfchprograma][$prodProceso_cdgproceso[$id_proceso]][$idmezcla],3).'</td></tr>'; }
        }

        echo '
            </table>';
      }


    echo '
          </td>'; }

    echo '
        </tr>'; }

  echo '   
      </tbody>
      <tfoot>
        <tr>
          <td colspan="'.($numprocesos+1).'"></td></tr>
      </tfoot>
    </table>';
    
  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>	
</html>
