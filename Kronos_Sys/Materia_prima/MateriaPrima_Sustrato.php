<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sustrato | Materia Prima</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">

</head>

<body> 
<?php
$codigoPermiso='12';
include("../components/barra_latera_almacen.php");
include("Controlador_Bloques/crud_sustrato.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Sustratos</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo
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
<label for="codigoSustrato">Código:</label>
<input type="text" name="codigoSustrato" id="codigoSustrato" required class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoSustrato'];?>"
    size="20" placeholder="nombre corto">
</div>

<div class="col-xs-3">
<label for="descripcionSustrato">Descripción:</label>
<input type="text" name="descripcionSustrato"  required class="form-control" id="descripcionSustrato" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionSustrato'].'" readonly="true';  ?>"
    size="20" placeholder="nombre completo">
</div>

<div class="col-xs-3">
<label for="rendimiento">Rendimiento:</label>
<input type="text" name="rendimiento" id="rendimiento" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['rendimiento'];?>"
    size="20" placeholder="m2*k">
</div>

<div class="col-xs-3">
<label for="anchura">Anchura:</label>
<input type="text" name="anchura" id="anchura" required class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['anchura'];?>"
    size="20" placeholder="mm">
</div>
</div>
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button  style="float:right;" class="btn btn-default" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button  style="float:right;" class="btn btn-default" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
</div>
<?php 
if(isset($_POST['checkTodos']))
{
	?>
	<h4 class="ordenMedio">Sustratos Activos</h4>
	<div class="table-responsive">

	<table border='0' cellpadding='15' class="table table-hover">
		
<?php
include ("Controlador_Bloques/showSustrato.php");
echo "</table>	<h4 class='ordenMedio'>Sustratos Inactivos</h4>
<div class='table-responsive'>

<table border='0' cellpadding='15' class='table table-hover'>"
	;
include ("Controlador_Bloques/showSustrato_bajas.php");
?>
</div>
</table>
	<?php
}
else
{
	?>
	<h4 class="ordenMedio">Sustratos Activos</h4>
	<div class='table-responsive'>
	<table border='0' cellpadding='15' class='table table-hover'>
<?php
include ("Controlador_Bloques/showSustrato.php");
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
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>