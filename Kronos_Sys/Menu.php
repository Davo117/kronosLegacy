<?php
session_start();
error_reporting(0);
?>
<?php
if(is_null($_SESSION['usuario']))
{
	$_SESSION['usuario'] = "";
	$_SESSION['nombre']="";
	$_SESSION['rol']="";
}

$_SESSION['array']=array();//Variable para el array de paquetes o rollos
$_SESSION['arrayBS']=array();//Variable para el array de bandas de seguridad 
$_SESSION['estatus']="";//Variable para el estatus de mensajes de error
$_SESSION['estatusActualizar']="";//Variable de estatus para mensajes de error en pesaje de empaques
$_SESSION['cod']="";//Variable de pruebas
?>
<!doctype html>
<html lang="es">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="apple-touch-icon" sizes="76x76" href="pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	 <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<meta name="theme-color" content="#000">
	
</head>
<body style="background-color:#F1F1F1;">
	<div style="margin-top:25px">

<div class="container" >
	<div class="col-xs-6"><a href="../Inventario/"><img style="" src="pictures/logo-labro.png" class="img-responsive"></a><h3 class="display-4">Sistema de control de producción (v1.8)</h3>


<br><br><br><br><br><br><br><br><br><br><br><br>

			
<hr style="color:black;background-color:black;border-color:#797979;width:100%">
<!--<div class="col-sm-6" style="margin-left:10%"></div> -->
<h4 >Accesos directos:</h4>
<a href="Sistema/grafico.php"><IMG style="width:10%;height:10%" src="pictures/Gauge.png"></IMG></a>
<button class="btn btn-sucess"><img class="img-rounded" width="40" src="pictures/plus.png"></button>
				</div>
	<div class="col-xs-6" >
				<a class="btn btn-info btn-block" href="Personal" role="button" href="Personal" style="float:left;"><IMG src="pictures/group2.png" style="margin: 0 auto" class="img-responsive"></IMG><p class="lead">Area Administrativa</p></a>
				<a class="btn btn-info btn-block" href="Produccion/Produccion_control.php?Manga Termoencogible" role="button" href="Produccion/Produccion_control.php?tipo=Termoencogible" style="float:left;"><IMG src="pictures/gerarBig.png" style="margin: 0 auto" class="img-responsive"></IMG>
					<p class="lead">Producción</p></a>
					<a class="btn btn-info btn-block" href="Almacen/Index_almacen.php" role="button" href="Empaque/Empaque_inventario.php" style="float:left;"><IMG style="margin: 0 auto" src="pictures/Almacen.png" class="img-responsive"><p class="lead">Almacén</p></a>
				</div>
</div>

	</body>

	</html>