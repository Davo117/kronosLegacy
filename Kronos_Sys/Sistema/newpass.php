<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
$codigoPermiso='28';

include ("funciones/crudChange.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cambiar Contraseña(Sistema) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>

<body> 
<?php include("../components/barra_lateral2.php");
//include("../css/barra_horizontalsistema.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
	<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Cambio de contraseña</b>
	</div>
<div class="panel-body">

<div class="col-xs-3">
	<label for="actual">Contraseña Actual</label>
<input type="password" name="actual" id="actual" size="20"  placeholder="Contraseña Actual" class="form-control" required>
</div>

<div class="col-xs-3">
<label for="nueva">Nueva Contraseña</label>
<input type="password" id="nueva" name="nueva" class="form-control" size="20" placeholder="Nueva Contraseña" required>
</div>

<div class="col-xs-3">
<label for="confirmar">Confirmar Nueva Contraseña</label>
<input type="password" name="confirmar" class="form-control" size="20" placeholder="Confirmar Cambio" required>
</div>
</div>   
	<button class="btn btn-info" style="float:right;" type="submit" name="save">Actualizar</button>
</form>
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