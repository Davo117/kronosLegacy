<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
	$_SESSION['codigoPermiso']='80003';
	include ("funciones/permisos.php");

include ("funciones/crudChange.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Usuario(Sistema) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>

<body> 
<?php include("../components/barra_lateral2.php");  ?>

<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">

<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Agregar usuarios</b>
				</div>
	<div class="panel-body">
<div class="col-xs-3">
<label for="example">Empleado:</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" id="example" required class="form-control">
	<option value="">[Seleccione un Empleado]</option>
<?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablaem where usuario=0 and Baja=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	//Mandamos la lista normalmente:  		?>
<option value="<?php echo $row ['ID'];?>">
<?php echo $row['numemple']." | " .$row['Nombre'];?>
</option>
<?php } ?>
</select>
</div>

<div class="col-xs-3">
<label for="rol">Rol</label>
<input type="text" name="rol" id="rol" class="form-control" size="20" placeholder="Área de Trabajo" required>
</div>

<div class="col-xs-3">
<label for="usuario">Usuario:</label>
<input type="text" name="usuario" class="form-control" id="usuario" size="20" placeholder="Usuario" required>
</div>

<div class="col-xs-3">
<label for="contra">Contraseña<b style="font-size: 80%;">(Por defecto: gl123)</b></label>
<input type="password" class="form-control" id="contra" name="contra"  size="20" placeholder=" Contraseña" required value="gl123">
</div>

<div class="col-xs-3">
<label for="confirmar">Repetir Contraseña<b style="font-size: 80%;">(Por defecto: gl123)</b></label>
<input type="password" id="confirmar" class="form-control" name="confirmar"  size="20" placeholder="Confirmar Cambio" required value="gl123">
</div>
</div>
	<button class="btn btn-default" style="float:right;" type="submit" name="saveUser">Agregar</button>
</form>
</div>
<br>
<h4 class="ordenMedio">Usuarios registrados</h4>
<div class="table-responsive">
<table cellpadding="15" border="0" class="table table-hover">
<?php include ("funciones/mostrarUsuario.php"); ?>
</div>
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