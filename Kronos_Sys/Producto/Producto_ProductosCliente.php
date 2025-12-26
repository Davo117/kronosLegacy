<?php
ob_start();
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	 
	} else {
	  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	 
	exit;
	}
	 
	$now = time();
	 
	if($now > $_SESSION['expire']) {
	session_destroy();
	 
	echo '<script language="javascript">alert("Tu sesión caducó");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ProductosCliente | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>

<body> 
<?php
$codigoPermiso='9';
include("Controlador_Disenio/bitacoras/bitacoraPC.php");
include("../components/barra_lateral2.php");
include("Controlador_Disenio/crud_ProductosCliente.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Productos cliente</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_ProductosCliente.php';"> Mostrar todo
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
							</label>
							<?php
						}?></div>
						<div class="panel-body">

<div class="col-xs-3">
<label for="IdentificadorCliente">Identificador:</label>
<input type="text" required name="IdentificadorCliente" class="form-control" id="IdentificadorCliente" value="<?php if(isset($_GET['edit'])) echo $getROW['IdentificadorCliente'];?>"
    size="20" placeholder="Identificador">
</div>

<div class="col-xs-3">
<label for="nombre">Nombre:</label>
<input type="text" name="nombre" required id="nombre" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombre'].'" readonly="true';  ?>"
    size="20" placeholder="nombre producto">
</div>
</div>
<?php

if(isset($_GET['edit']))
{
	
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
</div>
<?php
if(isset($_POST['checkTodos']))
{?>
	<h4 class="ordenMedio">Productos-Cliente activos</h4>
	<div class="table-responsive">
<table border='0' cellpadding='15' class="table table-hover">
<?php
include ("Controlador_Disenio/showProductosCliente.php");
?>
</table>
</div>
	<h4 class="ordenMedio">Productos-Cliente Inactivos</h4>
	<div class="table-responsive">
<table border='0' cellpadding='15' class="table table-hover">
<?php
include ("Controlador_Disenio/showProductosCliente_bajas.php");
?>
</table>
</div>
<?php
}
else
{?>
	<h4 class="ordenMedio">Productos-Cliente activos</h4>
	<div class="table-responsive">
<table border='0' cellpadding='15' class="table table-hover">
<?php
include ("Controlador_Disenio/showProductosCliente.php");
?>
</table>
</div>
<?php
}
?>
</div>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
ob_end_flush();
?>
