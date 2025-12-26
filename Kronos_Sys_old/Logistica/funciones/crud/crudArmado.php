<?php 
include("../Database/db.php");

if(isset($_POST['saveA'])){
  $contar=0;
  $agregados=0;
  
  $consulta=$MySQLiconn->query("SELECT * FROM embarque where numEmbarque='".$_GET['cdgEmb']."' && bajaEmb='1'");
  $row1=$consulta->fetch_array();
  
  if ($_GET['emp']=='caja') {
    
  	$SQL=$MySQLiconn->query("SELECT referencia, piezas, producto FROM caja where producto='".$_SESSION['productillo']."' && baja='2'");	
    while ($row=$SQL->fetch_array()) {	
      $cdg='number'.$row['referencia'];
      $chck = $MySQLiconn->real_escape_string($_POST[''.$cdg.'']);

      if ($chck!='--') {
        $updat=$MySQLiconn->query("UPDATE caja set baja='3', cdgEmbarque='".$_GET['cdgEmb']."' where referencia='".$chck."' && producto='".$row['producto']."' ");	
        $up=$MySQLiconn->query("UPDATE ensambleempaques set cdgEmbarque='".$_GET['cdgEmb']."' where referencia='".$chck."' && producto='".$row['producto']."' ");  

        $up2=$MySQLiconn->query("SELECT surtido from confirmarprod where prodConfi='".$row1['producto']."' && idConfi='".$row1['idorden']."'  && cantidadConfi='".$row1['cantidad']."'"); 
        $row2=$up2->fetch_array();
        $surtir=$row2['surtido']+ $row['piezas'];
        
        $up=$MySQLiconn->query("UPDATE confirmarprod set surtido='".$surtir."' where prodConfi='".$row1['producto']."' && cantidadConfi='".$row1['cantidad']."'"); 
        $contar++;
        $agregados=$agregados+$row['piezas'];
      }
    }    
  }
  elseif($_GET['emp']=='rollo') {
    $SQL=$MySQLiconn->query("SELECT referencia, piezas, producto FROM rollo where producto='".$_SESSION['productillo']."' && baja='2'"); 
    while ($row=$SQL->fetch_array()) {  
      $cdg='number'.$row['referencia'];
      $chck = $MySQLiconn->real_escape_string($_POST[''.$cdg.'']);
      if ($chck!='--') {
        $updat=$MySQLiconn->query("UPDATE rollo set baja='3', cdgEmbarque='".$_GET['cdgEmb']."' where referencia='".$chck."' && producto='".$row['producto']."'");  
        $up=$MySQLiconn->query("UPDATE ensambleempaques set cdgEmbarque='".$_GET['cdgEmb']."' where referencia='".$chck."' && producto='".$row['producto']."'"); 

        $up2=$MySQLiconn->query("SELECT surtido from confirmarprod where prodConfi='".$row1['producto']."' && cantidadConfi='".$row1['cantidad']."'"); 
        $row2=$up2->fetch_array();
        $surtir=$row2['surtido']+ $row['piezas'];
        
        $up=$MySQLiconn->query("UPDATE confirmarprod set surtido='".$surtir."' where prodConfi='".$row1['producto']."' && cantidadConfi='".$row1['cantidad']."'"); 
        $contar++;
        $agregados=$agregados+$row['piezas'];
      }
    }
  }
  echo"<script>alert('Se agregaron : $contar empaques, $agregados millares')</script>";
}



if(isset($_GET['desactivar'])){
  $variable=$_GET['desactivar'];
  $usuarioF=$_SESSION['usuario'];
  if($variable[0]=='C'){  $var='caja';  }
  elseif ($variable[0]=='Q'){ $var='rollo'; }
  $texto = strtoupper($var);
  $contar= strlen($variable);
  $concatenar='';
  for ($i=1; $i<$contar; $i++) { 
    $concatenar=$concatenar.''.$variable[$i];
  }

  $consulta=$MySQLiconn->query("SELECT referencia, producto, piezas from $var where id='$concatenar' && cdgEmbarque='".$_GET['cdgEmb']."'");
  $row=$consulta->fetch_array();

  $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Retorno el empaque ".$row['referencia']." (".$row['piezas']." millares) del embarque: ".$_GET['cdgEmb']."','Armado de Embarque','Logistica',NOW())");
  
  $consulta1=$MySQLiconn->query(" SELECT idorden from embarque where numEmbarque='".$_GET['cdgEmb']."'");
  $row1=$consulta1->fetch_array();

  $consulta2=$MySQLiconn->query(" SELECT surtido from confirmarprod where idConfi='".$row1['idorden']."'");
  $row2=$consulta2->fetch_array();

  $resta=$row2['surtido'] - $row['piezas'];

  $up=$MySQLiconn->query("UPDATE confirmarprod set surtido='$resta' where idConfi='".$row1['idorden']."'");

  $SQL=$MySQLiconn->query("UPDATE $var SET baja='2', cdgEmbarque='' where id='$concatenar' && cdgEmbarque='".$_GET['cdgEmb']."' && referencia='".$row['referencia']."' && producto='".$row['producto']."'");

  $SQL=$MySQLiconn->query("UPDATE ensambleempaques SET cdgEmbarque='' where tipoEmpaque='$texto' && cdgEmbarque='".$_GET['cdgEmb']."' && referencia='".$row['referencia']."' && producto='".$row['producto']."'");
  
  header("Location: Logistica_ArmadoEmbarque.php?cdgEmb=".$_GET['cdgEmb']."&id=".$_GET['id']."&emp=".$_GET['emp']."");
} 





if(isset($_GET['cerrar'])){
  $variable=$_GET['cerrar'];
  $usuarioF=$_SESSION['usuario'];
  $id=$_GET['id'];

  $SQL =$MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Finalizo el embarque: $variable','Armado de Embarque','Logistica',NOW())");
  

  $up=$MySQLiconn->query("UPDATE embarque set cerrar='1' where cerrar='0' && numEmbarque='$variable' && idEmbarque='$id'");

  header("Location: Logistica_Embarques.php");
} 
?>