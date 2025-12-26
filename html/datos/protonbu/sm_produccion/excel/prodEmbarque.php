<html>
  <head>
  </head>
  <body><?php

  include '../../datos/mysql.php';

  $alptEmpaque_cdgembarque = $_GET['cdgembarque'];

  $link_mysqli = conectar();
  $vntsEmbarqueSelect = $link_mysqli->query("
    SELECT * FROM vntsembarque
    WHERE cdgembarque = '".$alptEmpaque_cdgembarque."'");

  header("Content-Type: application/vnd.ms-excel");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=Embarque ".$alptEmpaque_cdgembarque.".xls"); 

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
        alptempaque.cdgembarque  = '".$alptEmpaque_cdgembarque."'
      GROUP BY alptempaque.noempaque");

    if ($alptEmpaqueSelect->num_rows > 0)
    { echo '
    <table>
      <tr><th></th>
        <th>Empaque</th>        
        <th>Peso Bruto Kgs</th>
        <th>Paquetes</th>
        <th>Piezas</th>
        <th>Suma Pzs</th></tr>'; 

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

    } else
    { echo '
    <table>
      <tr><th></th>
        <th>Empaque</th>
        <th>Rollo</th>
        <th>Metros</th>
        <th>Suma Mts</th>
        <th>Banderas</th>
        <th>Kilogramos</th>
        <th>Suma Kgs</th>
        <th>Peso Bruto Kgs</th>
        <th>Piezas</th>
        <th>Suma Pzs</th></tr>';

    $link_mysqli = conectar();    
    $alptEmpaqueSelect = $link_mysqli->query("
      SELECT * FROM alptempaque
      WHERE cdgembarque = '".$alptEmpaque_cdgembarque."'
      ORDER BY noempaque");

    while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
    { $id_empaque++;
      $alptEmpaque_cdgempaque = $regAlptEmpaque->cdgempaque;
      $alptEmpaque_noempaque = $regAlptEmpaque->noempaque;
      $alptEmpaque_pesobruto = $regAlptEmpaque->peso;

      $link_mysqli = conectar();
      $alptEmpaqueRSelect = $link_mysqli->query("
        SELECT alptempaquer.nocontrol, alptempaquer.cdgrollo,
          (prodrollo.longitud/pdtoimpresion.corte) AS cantidad,
          prodrollo.longitud, prodrollo.peso, prodrollo.bandera
        FROM alptempaquer, prodrollo, pdtoimpresion
        WHERE alptempaquer.cdgrollo = prodrollo.cdgrollo AND
          alptempaquer.cdgproducto = pdtoimpresion.cdgimpresion AND
          alptempaquer.cdgempaque = '".$alptEmpaque_cdgempaque."'
        ORDER BY alptempaquer.nocontrol");

      $id_rollo = 1;
      $alptEmpaque_cantidadsuma = 0;
      while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
      { $alptEmpaque_nocontrol[$id_rollo] = $regAlptEmpaqueR->nocontrol; 
        $alptEmpaque_longitud[$id_rollo] = $regAlptEmpaqueR->longitud;
        $alptEmpaque_bandera[$id_rollo] = $regAlptEmpaqueR->bandera;
        $alptEmpaque_peso[$id_rollo] = $regAlptEmpaqueR->peso;
        $alptEmpaque_cantidad[$id_rollo] = number_format($regAlptEmpaqueR->cantidad,3);

        $alptEmpaque_cantidadsuma += $alptEmpaque_cantidad[$id_rollo];

        $id_rollo++;}       

      $num_rollos = $alptEmpaqueRSelect->num_rows;

      echo '
      <tr><td>'.$id_empaque.'</td>        
        <td><h1>'.$alptEmpaque_noempaque.'</h1></td>
        <td>';

      for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
      { echo $alptEmpaque_nocontrol[$id_rollo].'<br/>'; } 

       echo '</td>        
        <td>';

      $alptEmpaque_longitudsuma = 0; 
      for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
      { echo $alptEmpaque_longitud[$id_rollo].'<br/>'; 

        $alptEmpaque_longitudsuma += $alptEmpaque_longitud[$id_rollo]; } 

       echo '</td>
        <td>'.number_format($alptEmpaque_longitudsuma,2).'</td>
        <td>';

      $alptEmpaque_pesosuma = 0; 
      for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
      { echo number_format($alptEmpaque_bandera[$id_rollo],3).'<br/>'; } 

       echo '</td><td>';

      $alptEmpaque_pesosuma = 0; 
      for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
      { echo number_format($alptEmpaque_peso[$id_rollo],3).'<br/>';

        $alptEmpaque_pesosuma += $alptEmpaque_peso[$id_rollo]; } 

       echo '</td>
        <td>'.number_format($alptEmpaque_pesosuma,3).'</td>
        <td>'.number_format($alptEmpaque_pesobruto,3).'</td>
        <td>';

      
      for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
      { echo number_format($alptEmpaque_cantidad[$id_rollo],3).'<br/>'; } 

       echo '</td>
         <td>'.number_format($alptEmpaque_cantidadsuma,3).'</td></tr>'; 

      $alptEmpaqueRSelect->close;
      unset($alptEmpaque_nocontrol);
      $num_rollos = 0; }  

    $alptEmpaqueSelect->close; }

    
  
?>
    </table>
  </body>
</html>
