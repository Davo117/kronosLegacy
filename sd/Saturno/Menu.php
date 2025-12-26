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
?>
<!doctype html>
<html lang="es">
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="css/Stylish.css" />
	
	</head>

	<body>
		<?php
		include("barra_lateral.php");
		?>
		<div id="body">
			<div id="texto">
				<p id="texto-contenedor-1">
				
				</p>
				

			</div>

			<div id="foto">
					
			</div>
		</div>



	</body>
		
</html>