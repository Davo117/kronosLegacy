<html>
  <head>
  </head>
  <body><?php
  include '../../../Database/db.php';

  $getEmbarque = $_GET['numEmbarque'];

  $consulta=$MySQLiconn->query(" SELECT * FROM embarque WHERE numEmbarque = '".$getEmbarque."'");
  $embarque=$consulta->fetch_array();

  header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=Embarque ".$getEmbarque.".xls"); 

  $id_empaque=0;
  $alptEmpaque_cantidadsuma=0;

if ($embarque['empaque']=='caja') {
  echo '
    <table>
      <tr><th></th>
        <th>Empaque</th>        
        <th>Peso Bruto Kgs</th>
        <th>Paquetes</th>
        <th>Piezas</th>
        <th>Suma Pzs</th></tr>'; 
  
  $consulta1=$MySQLiconn->query("SELECT * FROM caja WHERE cdgembarque = '".$getEmbarque."' && baja='3' ORDER BY referencia");
  while ($cajaExistencia=$consulta1->fetch_array()) {
    $id_empaque++;
    $alptEmpaque_cantidadsuma += $cajaExistencia['piezas'];
    echo '
      <tr><td>'.$id_empaque.'</td>        
        <td><h1>'.$cajaExistencia['referencia'].'</h1></td>
        <td>'.number_format($cajaExistencia['peso'],3).'</td>
        <td>'.$cajaExistencia['noElementos'].'</td>
        <td>'.$cajaExistencia['piezas'].'</td>
         <td>'.$alptEmpaque_cantidadsuma.'</td></tr>'; 
  }  
} 
elseif($embarque['empaque']=='rollo'){
  echo '
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
  
  $consulta1=$MySQLiconn->query("SELECT * FROM rollo WHERE cdgEmbarque='".$getEmbarque."' && baja='3' ORDER BY referencia");
  while($rolloExiste=$consulta1->fetch_array()){ 
    $id_empaque++;
    $alptEmpaque_pesobruto=$rolloExiste['peso'];
    $consulta2 = $MySQLiconn->query("SELECT * from ensambleempaques where cdgEmbarque='".$getEmbarque."' && referencia='".$rolloExiste['referencia']."' && producto='".$rolloExiste['producto']."'");

    $id_rollo=1;
    $num_rollos=$consulta2->num_rows;      
    while($empaqueEx=$consulta2->fetch_array()) { 
      $consulta3= $MySQLiconn->query("SELECT peso, bandera from prorevision where rollo='".$empaqueEx['codigo']."' && producto='".$empaqueEx['producto']."'");
      $revision=$consulta3->fetch_array();
      $alptEmpaque_longitud[$id_rollo]=$empaqueEx['longitud'];
      $alptEmpaque_bandera[$id_rollo] = $revision['bandera'];
      $alptEmpaque_peso[$id_rollo]=$revision['peso'];
      $alptEmpaque_nocontrol[$id_rollo] =$empaqueEx['referencia'];
      $alptEmpaque_cantidad[$id_rollo] = number_format($empaqueEx['piezas'],3);

      $alptEmpaque_cantidadsuma += $alptEmpaque_cantidad[$id_rollo];
      $id_rollo++;
    }       
    echo '<tr><td>'.$id_empaque.'</td>   <td><h1>'.$rolloExiste['referencia'].'</h1></td>   <td>';

    for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
    {
      $letras="";//explode('Q', $alptEmpaque_nocontrol[$id_rollo]);

     echo 'Bobina '.' '.$id_rollo.'<br/>'; } 
    echo '</td> <td>';

    $alptEmpaque_longitudsuma=0; 
    for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++){ 
      echo $alptEmpaque_longitud[$id_rollo].'<br/>'; 
      $alptEmpaque_longitudsuma += $alptEmpaque_longitud[$id_rollo]; 
    }
    echo '</td> <td>'.number_format($alptEmpaque_longitudsuma,2).'</td> <td>';

    $alptEmpaque_pesosuma = 0; 
    // dentro del for antes del br: 
    for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++){ 
      $alptEmpaque_bandera[$id_rollo].'<br/>'; 
    } 
    echo '</td><td>';

    $alptEmpaque_pesosuma = 0; 
    for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++){ 
      echo number_format($alptEmpaque_peso[$id_rollo],3).'<br/>';

      $alptEmpaque_pesosuma += $alptEmpaque_peso[$id_rollo]; 
    }

    echo '</td><td>'.number_format($alptEmpaque_pesosuma,3).'</td>
      <td>'.number_format($alptEmpaque_pesobruto,3).'</td>
      <td>';

      
    for ($id_rollo = 1; $id_rollo <= $num_rollos; $id_rollo++)
    { echo number_format($alptEmpaque_cantidad[$id_rollo],3).'<br/>'; } 
    echo '</td>
    <td>'.number_format($alptEmpaque_cantidadsuma,3).'</td></tr>'; 

    unset($alptEmpaque_nocontrol);
    $num_rollos = 0; 
  }  
}  ?>
    </table>
  </body>
</html>