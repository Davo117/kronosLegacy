<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
  ob_start();
  $_SESSION['codigoPermiso']='40010';
  include ("funciones/permisos.php");
  include ("funciones/bitacoras/embarqueB.php");
  include ("funciones/crud/crudArmado.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Armado de Embarques (Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript">
  function seleccionar_todo(){ 
    for (i=0;i<document.f1.elements.length;i++) 
      if(document.f1.elements[i].type == "checkbox")  
        document.f1.elements[i].checked=1 
  } 
function deseleccionar_todo(){ 
   for (i=0;i<document.f1.elements.length;i++) 
      if(document.f1.elements[i].type == "checkbox")  
         document.f1.elements[i].checked=0 
}
</script>
</head>

<style type="text/css">
a.marcar{
    font:bold 85% Sansation;
}
a.marcar:hover{
  color:white;
  background: gray;
}
div.lotesDisponibles{
   width: 91%; 
  -webkit-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
  -moz-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
  box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
  font:80% Sansation;
}

.lotesSeleccionados{
width: 91%;
  font:80% Sansation;
  -webkit-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
  -moz-box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
  box-shadow: 0px 1px 5px 0px rgba(143,143,143,1);
}

p.irrelevance{
  font:15px Sansation;  color:black;
}
</style>

<body> 
<?php   include("../components/barra_lateral2.php"); ?>

	<div class="panel-heading container-fluid col-xs-12 justify-content-between">
  <b class="titulo">Armado de Embarques</b>
  </div>
<?php 
$confir = $MySQLiconn->query("SELECT * FROM $embarq WHERE idEmbarque='".$_GET['id']."' && numEmbarque='".$_GET['cdgEmb']."'");

$getconfir = $confir->fetch_array();

$dato=$MySQLiconn->query("SELECT cantidadConfi, surtido from $confirProd where enlaceEmbarque='".$getconfir['numEmbarque']."' && idConfi='".$getconfir['idorden']."'");

$total=$dato->fetch_array();  

$restante= $total['cantidadConfi']- $total['surtido']; ?>
<div class="panel panel-default  container-fluid col-xs-12 justify-content-around">
<form method="post" name="formulary" id="formulary">
<div class="fields col-xs-3"><p>Embarque</p>

<p><b><?php echo $getconfir['numEmbarque']; ?></b>
</p></div>

<div class="fields col-xs-3"><p>Fecha Embarque</p>
<p><b><?php echo $getconfir['diaEmb']; ?></b>
</p></div>


<div class="fields col-xs-3 "><p><b>Cerrar Embarque</b></p>
<a  href="?cerrar=<?php echo $_GET['cdgEmb'].'&id='.$_GET['id'].'&emp='.$_GET['emp']; ?>" ><IMG src="funciones/img/terminar.png" title='Cerrar Embarque' style=" width: 30%;"></a>
</div>

<div class="fields col-xs-5" ><p>Sucursal</p>
<p><b><?php echo $getconfir['sucEmbFK']; ?></b>
</p></div>

<div class="fields col-xs-5" ><p>Producto</p>
<p><b><?php echo $getconfir['prodEmbFK']; ?></b>
</p></div>

<div class="fields col-xs-2"><p>Referencia</p>
<p><b><?php echo $getconfir['referencia']; ?></b>
</p></div>

<div class="fields col-xs-3"><p>Millares Solicitados</p>
<p><b><?php echo $total['cantidadConfi']; ?></b>
</p></div>


<div class="fields col-xs-3"><p>Millares Restantes</p>
<p><b><?php echo number_format($restante,3); ?></b>
</p></div>

</form>
</div>


<div class="col-xs-12 text-center">

<form method="post" name="f1">
  
  <button  type="submit" name="saveA">Guardar</button>
  <br>
  Marcar:
  <a class="marcar" href="javascript:seleccionar_todo()">todos</a> | 
<a class="marcar" href="javascript:deseleccionar_todo()">ninguno</a>
<?php
$_SESSION['productillo']=$getconfir['prodEmbFK'];
if ($_GET['emp']=='caja') {  include ('funciones/mostrar/tablaArmado1.php'); }

if ($_GET['emp']=='rollo') {  include ('funciones/mostrar/tablaArmado.php'); } ?>
</form>
</div>



</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush();
} 
else {
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>"; 
  exit;
} ?>