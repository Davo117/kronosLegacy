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
$_SESSION['estatus']="";//Variable para el estatus de mensajes de error
$_SESSION['estatusActualizar']="";//Variable de estatus para mensajes de error en pesaje de empaques
$_SESSION['cod']="";//Variable de pruebas
?>
<!doctype html>
<html lang="es">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/Stylish.css" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#000">
	 <link rel="stylesheet" href="css/formulary.css" type="text/css" media="screen"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
</head>
<body class="texto">
	
	<div class="body">
		<div id="texto" >
			<p id="texto-contenedor-1"><img src="pictures/logo-labro.png"></img></p>
<div class="modulo"><a href="Personal"><IMG src="pictures/photoBig.png"></IMG><p>Recursos Humanos</p></a></div>
<div class="modulo"><a href="Logistica/Logistica_Clientes.php"><IMG src="pictures/exchangeBig.png"></IMG><p>Logística</p></a></div>
<div class="modulo"><a><IMG src="pictures/gearBig.png"></IMG></a><p>Producción</p></div>
<div class="modulo"><a><IMG src="pictures/designBig.png"></IMG></a><p>Diseño</p></div>
<div class="modulo"><a><IMG src="pictures/shopping_cartBig.png"></IMG></a><p>Materia prima</p></div>
<div class="modulo"><a><IMG src="pictures/boxBig.png"></IMG></a><p>Empaque</p></div>
<div class="modulo"><a><IMG src="pictures/khe.png"></IMG></a><p>Misceláneos</p></div>
<div class="modulo"><a><IMG src="pictures/folder_search.png"></IMG></a><p>Buscador</p></div>
<div class="modulo"><a><IMG src="pictures/windowsBig.png"></IMG></a><p>Sistema</p></div>
	</div>
</div>

</body>

</html>