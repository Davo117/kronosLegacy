<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Pantone | Materia Prima</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#000">
</head>

<body> 
	<?php

	include("../components/barra_lateral2.php");
	include("Controlador_Bloques/crud_pantone.php");
	?>
	<div id="page-wrapper">
		<div class="container-fluid">

			<form target="_blank" method="POST" action="buscadorPantone.php"  name="formulary" id="formulary" role="form">
				<div class="panel panel-default">

					<div class="panel-heading"><b class="titulo">Pantone</b>
					</div>
					<div class="panel-body">
					<div class="col-xs-3">
						<label for="pan">Buscar codigo HTML/HEX:</label>
						<input required type="text" class="form-control" name="descripcionPantone" id="pan" placeholder="Código de pantone">
					</div>
				</div>
				<button class="btn btn-info" style="float:right;" type="submit" name="buscar" >Verificar</button>
			</div>
			</div>
			</form>
			<br>
		<div class="container-fluid">
			<form  method="post" name="formulary" id="formulary" role="form">
				<div class="panel panel-default">

					<div class="panel-heading"><b class="titulo">Registrar pantone</b>
					</div>
					<div class="panel-body">
					<div class="col-xs-3">
						<label for="descripcionPantone">Pantone:</label>
						<input required type="text" class="form-control" name="descripcionPantone" id="descripcionPantone" placeholder="Pantone XXXX X"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionPantone'].'" readonly="true';  ?>">
					</div>
					<div class="col-xs-3">
						<label for="codigoPantone">Código:</label>
						<input required type="text" class="form-control" id="codigoPantone" name="codigoPantone" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoPantone'];?>"
						size="20" placeholder="código">
					</div>
				</div>
				<?php
				if(isset($_GET['edit']))
				{

					?>
					<button class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
					<?php
				}
				else
				{
					?>
					<button class="btn btn-default"  style="float:right;" type="submit" name="save">Guardar</button>
					<?php
				}
				?>
			</form>
		</div>
		<h4 class="ordenMedio" >Pantones Activos</h4>
		<div class="table-responsive">
			<table  border="0" cellpadding="15" class="table table-hover">
				<?php
				include ("Controlador_Bloques/showPantone.php");
				?>
			</table>
		</div>
		<div class="table-responsive">
			<h4 class="ordenMedio" >Pantones Inactivos</h4>
			<table  border="0" cellpadding="15" class="table table-hover">
				<?php
				include ("Controlador_Bloques/showPantone_bajas.php");
				?>
			</table>
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