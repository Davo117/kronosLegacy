<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	include ("funciones/bitacora/bitacoraMaquinas.php");
	include ("funciones/crud/crudMaquinas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Máquinas | Producción</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" href="style.css" type="text/css" />

</head>

<body> 
<?php	include("../css/barra_horizontal5.php");?>
<div id="row">
    <div class="col-md-10 col-md-offset-1" >
<form method="post" name="formular" id="formular" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Máquinas</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formular.submit();"> Mostrar todo
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formular.submit()"> Mostrar todo
							</label>
							<?php
						}?></div>
						<div class="panel-body">
							
							
<div class="col-xs-3">
	<label for="Codigo">Código: </label>
	<input style="color:black" type="text" id="Codigo" class="form-control" name="Codigo" value="<?php if(isset($_GET['edit'])) echo $getROW['codigo'];  ?>" size="20" placeholder="Codigo Completo" required>
</div>

<div class="col-xs-3">
	<label for="Descripcion">Descripción:</label> 
	<input style="color:black" type="text" id="Descripcion" name="Descripcion" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionMaq'];  ?>" size="20" placeholder="Descripción Completa" required> 
</div>
	<div class="col-xs-3">
		<label for="Subproceso">Proceso:</label>
		<SELECT name="Subproceso"  id="Subproceso" class="form-control" onChange="showCombo(this.value)" required>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $sub where baja=1");
//mientras se tengan registros:
?>
<option value="">--</option>
<?php
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['subproceso'] == $row['descripcionProceso']){
?>
<option value="<?php echo $getROW['subproceso'];?>" selected> <?php echo $getROW['subproceso'];?></option>
<?php  
}else{  //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>

<?php }	}

else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
<?php 
} }  ?> 
</SELECT>
</div>	
 </div>
     <?php 
if(isset($_GET['edit']))
{	?>
	<button   class="btn btn-info"  style="float:right;" type="submit" name="update" >Actualizar</button>
	<?php  }
else
{	?>
	<button class="btn btn-info" style="float:right;" type="submit" name="save">Guardar</button>
	<?php } ?>

</form>
</div>
<div class="table-responsive">
<?php include ("funciones/mostrar/mostradorMaquinas.php");
echo("</table></div>");

if(isset($_POST['checkTodos'])){?>
<div class="table-responsive">
	<?php	include ("funciones/nomostrar/mostrarTodoMaquinas.php");
	echo("</table></div>");
}?>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush();
}
else {
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";	 
	exit;
} ?>