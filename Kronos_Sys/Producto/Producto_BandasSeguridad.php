<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>
<head>
<title>BS | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>

<body> 
<?php

$codigoPermiso='7';
include ("funciones/permisos.php");
include("Controlador_Disenio/bitacoras/bitacoraBS.php");
include("../components/barra_lateral2.php");
include("Controlador_Disenio/crud_bandaSeguridad.php")
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">

<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Banda de seguridad</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodos" onclick="window.location='Producto_BandasSeguridad.php';"> Mostrar todo
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodos" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
							</label>
							<?php
						}?></div>
						<div class="panel-body">
<div class="col-xs-3">
<label for="identificador">Identificador:</label>
<input  required type="text" name="identificador" class="form-control" id="identificador" value="<?php if(isset($_GET['edit'])) echo $getROW['identificador'];?>"
    size="20" placeholder="identificador">
</div>
<div class="col-xs-3">
<label for="nombreBanda">Nombre:</label>
 <input required type="text" name="nombreBanda" class="form-control" id="nombreBanda" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreBanda'].'" readonly="true';  ?>"
    size="20" placeholder="nombre de banda">
</div>

<div class="col-xs-3">
<label for="anchura">Anchura:</label>
  <input type="text" name="anchura" class="form-control" id="anchura" value="<?php if(isset($_GET['edit'])) echo $getROW['anchura'];?>"
    size="10" placeholder="anchura">
</div>
</div>
<?php
?>
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
<h4 class="ordenMedio">Bandas Activas</h4>
<div class="table-responsive">
<table border='0' cellpadding='15' class="table table-hover">
<?php	include ("Controlador_Disenio/showBandaSeguridad.php");	?>
</table>
</div>
<?php 
if(isset($_POST['checkTodos']))
{?>
	<h4 class="ordenMedio">Bandas Inactivas</h4>
<div class="table-responsive">
<table border='0' cellpadding='15' class="table table-hover">
<?php	include ("Controlador_Disenio/showBandaSeguridad_bajas.php");	?>
</table>
</div>
<?php
}
?>
</div>
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