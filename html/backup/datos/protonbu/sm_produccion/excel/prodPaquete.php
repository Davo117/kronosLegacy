<html>
  <head>
  </head>
  <body><?php

  include '../../datos/mysql.php';

  header("Content-Type: application/vnd.ms-excel");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=Cajas.xls"); 
		
    $alptEmpaque_cdgproducto = substr($_GET['cdgimpresion'],0,6);

    $link_mysqli = conectar();
    $pdtoImpresionSelect = $link_mysqli->query("
      SELECT * FROM pdtoimpresion
      WHERE cdgimpresion = '".$alptEmpaque_cdgproducto."'");
        
    if ($pdtoImpresionSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

      $pdtoImpresion_cdgproyecto = $regPdtoImpresion->cdgproyecto;
      $pdtoImpresion_idimpresion = $regPdtoImpresion->idimpresion;
      $pdtoImpresion_impresion = $regPdtoImpresion->impresion;      
      $pdtoImpresion_ancho = $regPdtoImpresion->ancho;
      $pdtoImpresion_ceja = $regPdtoImpresion->ceja;
      $pdtoImpresion_alpaso = $regPdtoImpresion->alpaso;
      $pdtoImpresion_tolerancia = $regPdtoImpresion->tolerancia;
      $pdtoImpresion_corte = $regPdtoImpresion->corte;
      $pdtoImpresion_cdgproducto = $regPdtoImpresion->cdgproducto;
      $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
      $pdtoImpresion_sttimpresion = $regPdtoImpresion->sttimpresion; }

    echo '
    <table>
      <tr><th colspan="6"><h1>Producto: '.$pdtoImpresion_impresion.'</h1></th></tr>
      <tr><th></th>
        <th>Empaque</th>        
        <th>Peso Bruto Kgs</th>
        <th>Paquetes</th>
        <th>Piezas</th>
        <th>Suma Pzs</th></tr>';

    $link_mysqli = conectar();    
    $alptEmpaqueSelect = $link_mysqli->query("
      SELECT alptempaque.cdgempaque,
        alptempaque.noempaque,
        pdtoimpresion.impresion,
        COUNT(prodpaquete.cdgpaquete) AS paquetes,
        alptempaque.peso,
        SUM(prodpaquete.cantidad) AS piezas  
      FROM prodlote,
        prodbobina,
        prodrollo,
        prodpaquete,
        alptempaque,
        pdtoimpresion
      WHERE prodlote.cdglote = prodbobina.cdglote AND
        prodbobina.cdgbobina = prodrollo.cdgbobina AND
        prodrollo.cdgrollo = prodpaquete.cdgrollo AND
        prodpaquete.cdgempaque = alptempaque.cdgempaque AND
        prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
        alptempaque.cdgproducto  = '".$alptEmpaque_cdgproducto."' AND
        alptempaque.sttempaque = '1'
      GROUP BY alptempaque.noempaque");

    while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
    { $id_empaque++;
      $alptEmpaque_cdgempaque = $regAlptEmpaque->cdgempaque;
      $alptEmpaque_noempaque = $regAlptEmpaque->noempaque;
      $alptEmpaque_paquetes = $regAlptEmpaque->paquetes;
      $alptEmpaque_pesobruto = $regAlptEmpaque->peso;
      $alptEmpaque_piezas = $regAlptEmpaque->piezas;

      $alptEmpaque_cantidadsuma += $alptEmpaque_piezas;

      echo '
      <tr><td>'.$id_empaque.'</td>        
        <td><h1>C'.$alptEmpaque_noempaque.'</h1></td>
        <td>'.number_format($alptEmpaque_pesobruto,3).'</td>
        <td>'.$alptEmpaque_paquetes.'</td>
        <td>'.$alptEmpaque_piezas.'</td>
         <td>'.$alptEmpaque_cantidadsuma.'</td></tr>'; 

      $alptEmpaqueRSelect->close;
      unset($alptEmpaque_nocontrol);
      $num_rollos = 0; }  

    $alptEmpaqueSelect->close;

?>
    </table>
  </body>
</html>
