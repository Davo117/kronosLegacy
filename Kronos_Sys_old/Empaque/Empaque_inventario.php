<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Inventario(Empaque) | Grupo Labro</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#fff">
</head>
<body> 
	<?php
$_SESSION['codigoPermiso']='6600001';
	include ("../Database/db.php");
	include("../components/barra_lateral2.php");
	//include("../css/barra_horizontal8.php");
	?>
	<div id="page-wrapper">
	<div class="container-fluid">
<div class="panel panel-default">
<div class="panel-heading"><b class="titulo">Inventario de producto empacado</b>
</div>
</div>
<?php 
$consulta=$MySQLiconn->query("SELECT descripcionImpresion, id FROM impresiones where baja='1'");	

$item=1;	
while ($row=$consulta->fetch_array()) {

    if ($item%2 > 0) { 
        print('<div class="panel panel-default col col-lg-4"><div class="panel-heading">'.$row['descripcionImpresion'].'</div><div class="panel-body border" style="background:white;">'); } 
    else {
       	print('<div class="panel panel-heading col col-lg-4"><div class="panel-heading">'.$row['descripcionImpresion'].'</div><div class="panel-body border" style="background: #E8E8E8;">');  }   
	$item++;
        
	$total=0;
	$consulta1=$MySQLiconn->query("SELECT sum(piezas) as pieza FROM caja where producto='".$row['descripcionImpresion']."' and baja='2'");		
	while ($row1=$consulta1->fetch_array()) {
		//if (!empty($row1['pieza'])) {
			print('<a target="blank" href="pdf/InventarioPDF.php?cdg=C&prod='.$row['id'].'"><b class="bes"> caja:</b> <b class="bes2"> '.number_format($row1['pieza'],3).' Millares</b></a>');
			$total+=$row1['pieza'];
		//}
	}
	$consulta2=$MySQLiconn->query("SELECT sum(piezas) as pieza FROM rollo where producto='".$row['descripcionImpresion']."' and baja='2'");		
	while ($row2=$consulta2->fetch_array()) {
		//if (!empty($row2['pieza'])) {
			print('<a target="blank" href="pdf/InventarioPDF.php?cdg=R&prod='.$row['id'].'"><b class="bes"> rollo:</b><b class="bes2">  '.number_format($row2['pieza'],3).' Millares</b></a>');
			$total+=$row2['pieza'];
		//}
	}
	//if ($total!='0') {
		print('<center class="bes2">Total:'.number_format($total,3).' Millares</center>');
	//}
	print('</div></div></div>');		
} ?>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
	<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>