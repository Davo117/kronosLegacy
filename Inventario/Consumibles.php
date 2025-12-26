<div class="navbar-header" >
            <a class="navbar-brand" href="index.php">
                <img src="pictures/logo-labro.png" alt="Grupo Labro Inventario" width="160" height="50">
            </a>
        </div>
<?php
	include ("funciones/crudConsumibles.php"); ?>
	    
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Consumibles TI | Grupo Labro</title>
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">

	<link href="../Kronos_Sys/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="../Kronos_Sys/css/jquery-2.0.1.js"></script>
    <link rel="stylesheet" href="../Kronos_Sys/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">
    <script src="../Kronos_Sys/bootstrap/js/bootstrap.min.js"></script>
	
	<meta name="theme-color" content="#fff">

	</head>
	
	<body> 
	<div id="page-wrapper">
	<div class="container-fluid">

	<form method="post" name="formulary" id="formulary" role="form">
	
	<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Consumibles</b>
	<?php 
	if(isset($_POST['checkTodos']))
	{	?>

	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Consumibles.php';"> Mostrar todo
		</label>
	 	<?php
	}
	else
	{ ?>
	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
		</label>
		<?php
 	} ?></div>
<div class="panel-body">

<div class="col-xs-3">
<label for="Tipo">Tipo</label>
<input class="form-control" type="text" id="tipo" name="tipo" value="<?php if(isset($_GET['edit'])) echo $getROW['tipo'];  ?>" size="20" placeholder="Tipo" required>
</div>

<div class="col-xs-3">
<label for="SKU">SKU</label>
<input class="form-control" type="text" id="SKU" name="SKU" value="<?php if(isset($_GET['edit'])) echo $getROW['sku'];  ?>" size="20" required placeholder="Numero de SKU"></p>
</div>

<div class="col-xs-3">
<label for="Descripcion">Descripcion</label>
<input type="text" id="Descripcion" class="form-control" name="Descripcion" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcion'];  ?>" size="20" placeholder="Descripcion del Consumible" required></p>
</div>

<div class="col-xs-3">
<label for="fecha">Fecha de Ingreso</label>
<input type="date" id="fch_ingreso" class="form-control" name="fch_ingreso" value="<?php if(isset($_GET['edit'])) echo $getROW['fch_ingreso'];  ?>" size="20" required></p>
</div>


<?php
if(isset($_GET['edit']))
{	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update" >Actualizar</button>
	<?php  
}else
{	?>
	<button class="btn btn-default" type="submit"  style="float:right;" name="save">Guardar</button>
	<?php 
} ?>
</form>
</div>
<div class="table-responsive">
<table  border="0" cellpadding="0" class="table table-hover">
<?php include ("funciones/mostradorConsumibles.php"); 
echo("</table></div>");

if(isset($_POST['checkTodos']))
{?>
	<div class="table-responsive">
	<table border='0' cellpadding='0' class="table table-hover">
	<?php 	include ("funciones/mostrarTodoConsumibles.php");	?>
	</table></div>
	<?php
} ?>
</div>
</div>
</body>
</html>
<?php ob_end_flush(); 
?>