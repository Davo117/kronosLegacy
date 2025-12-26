<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
  ob_start();
	$_SESSION['codigoPermiso']='40008';
  include ("funciones/permisos.php");
  include ("funciones/crud/crudSurtido.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Surtidos(Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>
<body> 

<?php  include("../components/barra_lateral2.php"); ?>
<div class="panel-heading container-fluid col-xs-12 justify-content-between">
  <b class="titulo">Surtido de Confirmaciones</b>
  </div>

    <?php 
$confir = $MySQLiconn->query("SELECT ordenConfi FROM $confirProd WHERE idConfi='".$_SESSION['ordenes']."'");
    $getconfir = $confir->fetch_array();

$order = $MySQLiconn->query("SELECT * FROM $tablaOrden WHERE orden='".$getconfir['ordenConfi']."'");
    $getOrder = $order->fetch_array();
$sucs= $MySQLiconn->query("SELECT * FROM $tablasucursal WHERE nombresuc='".$getOrder['sucFK']."'");
$getSuc = $sucs->fetch_array();
 ?>
<div class="panel panel-default  container-fluid col-xs-12 justify-content-around">
<form method="post" name="formulary" id="formulary" style="height: 200px;">
<div  class="fields col-xs-3"><p>Orden de Compra</p>
<p><b><?php echo $getOrder['orden']; ?></b></p>
</div>

<div  class="fields col-xs-3"><p>Cliente</p>
<p><b><?php echo $getSuc['idcliFKS']; ?></b></p>
</div>

<div  class="fields col-xs-3"><p>Sucursal</p>
<p><b><?php echo $getSuc['nombresuc']; ?></b></p>
</div>


<div  class="fields col-xs-3" ><p>Fecha Documento</p>
<p><b><?php echo $getOrder['documento']; ?></b></p>
</div>

<div  class="fields col-xs-3"><p>Fecha Recepción</p>
<p><b><?php echo $getOrder['recepcion']; ?></b></p>
</div>

<div  class="fields col-xs-3"><p>Fecha Captura</p>
<p>    Dato Improvisado   </p>
</div>


<div  class="fields col-xs-12" ><p>Consignado a  <br>
    <b><?php echo $getSuc['domiciliosuc']; ?>, <?php echo $getSuc['coloniasuc']; ?>.</b>
    &nbsp; 
    CP:<b><?php echo $getSuc['cpsuc']; ?></b><br>
    <b><?php echo $getSuc['ciudadsuc']; ?></b>&nbsp; &nbsp; &nbsp; &nbsp; 
    Teléfono: <b><?php echo $getSuc['telefonosuc']; ?></b>
    </p>
</div>

</form>


<form method="post">
<table id="rounded-corner" style="margin-left: 10%; width: 50%"> 
<?php include ("funciones/mostrar/tablaSurtido.php"); ?>
</table>
</form>
</div>
</center>
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