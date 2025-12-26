<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Unidades | Materia Prima</title>
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
$codigoPermiso='15';
include("../components/barra_latera_almacen.php");
include("Controlador_Bloques/crud_unidadesMedida.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" >
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Unidades de medida</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit();"> Mostrar todo
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
<label for="identificadorUnidad">Identificador:</label>
<input type="text" required name="identificadorUnidad" id="identificadorUnidad" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorUnidad'];  ?>"
    size="20" placeholder="nombre corto">
</div>

<div class="col-xs-3">
<label for="nombreUnidad">Nombre:</label>
<input type="text" required name="nombreUnidad" id="nombreUnidad" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreUnidad'];?>"
    size="20" placeholder="nombre completo">
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
	<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
</div>
<?php
if(isset($_POST['checkTodos']))
{?>
	<h4 class="ordenMedio">Unidades Activas</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showUnidadesMedida.php");
?>
</table>
</div>
<h4 class="ordenMedio">Unidades Inactivas</h4>
<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showUnidadesMedida_bajas.php");
?>
</table>
</div>
<?php
}
else
{?>
<h4 class="ordenMedio">Unidades Activas</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showUnidadesMedida.php");
?>
</table>
</div>
<?php
}
?>

</form>
	 <script type="text/javascript" src="../css/menuFunction.js"></script>
</div>
</center>
</body>
</html>
<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>