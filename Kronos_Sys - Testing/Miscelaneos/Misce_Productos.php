<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	$codigoPermiso='31';
	if(isset($_SESSION['permitido'][$codigoPermiso]) && $_SESSION['permitido'][$codigoPermiso]=="rwx")
	{
		include("Controlador_Misce/db_Producto.php");
	include("Controlador_Misce/crud_misce.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Productos| Misceláneos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
      <script type="text/javascript" src="../css/teclasN.js"></script>

</head>

<body>
<?php	include("../components/barra_lateral2.php"); ?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="POST" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Tipos de producto</b>
				</div>
<div class="panel-body">
			
<div class="col-xs-3">
<label for="descripcion">Identificador</label>
<input type="text" id="descripcion" class="form-control" name="descripcion" value="" size="20" placeholder="Nombre Producto" required></div>
<div class="col-xs-3">
<label for="comboPresentacion">Presentación</label>
<select name='comboPresentacion' required id="comboPresentacion" class="form-control">
	<option value="EtiqAbierta">Etiqueta abierta</option>
	<option value="Predeterminados">Etiqueta cerrada</option>
	<option value="holograma">Holograma</option>
</select>
</div>



<?php /*
<div class="col-xs-3"><p>Alias:</p>
<p><input type="text" name="alias" value="" size="20" placeholder="Alias Corto" required>
</p></div>
<div class="col-xs-3"><p>Parámetros:</p>
<p><input type="text" name="parametros" value="" size="20" placeholder="parámetros" required>
</p></div>

*/ ?>
<div class="col-xs-3">
<label for="procesos"># Procesos</label>
<input type="text" name="procesos" class="form-control" id="procesos" onkeypress="return numeros(event)" value="" size="20" placeholder="Cantidad de procesos" required>
</div>
</div>
 
 
<?php
if(isset($_GET['edit'])){ ?>
	<button class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
	<?php
}
else{ ?>
	<button class="btn btn-default" style="float:right;"  type="submit" name="save">Guardar</button>
	<?php
} ?>
<p class="bg-danger">Nota:La abreviatura utilizada para los códigos de barras del producto son las tres primeras letras del nombre</p>
</form>

</div>
<h4 class="ordenMedio">Productos: </h4>
<div class="table-responsive">
<table  cellpadding='15' border="0" class="table table-hover">
<?php include ("Controlador_Misce/show.php"); ?>
</table>
</div>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush();
	}
	else
	{
		?>
		<head>
			 <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
			<script src="../css/jquery-2.0.1.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
		</head>
		<body style="background:#E3E5E5;padding-top:250px">
			<center>
				<h1 style="font:bold 20px Sansation">No tienes permisos para estar aquí</h1>
			<img class="img-responsive" width="120" height="120" src="../pictures/candado.png"><br>
			<a class="btn btn-info" href="javascript:window.history.back();"><img width="40" height="40" src="../pictures/sub_black_prev.png" title="Regresar"></a>
			</center>
			
		</body>
		<?php
	}
	
}
else{
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>';
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
} ?>