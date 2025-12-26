<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
ob_start();
	$_SESSION['codigoPermiso']='10001';
	include ("funciones/permisos.php");
	include ("funciones/example.php");
	include ("funciones/crudEmpleado.php"); ?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Empleado(RH) | Grupo Labro</title>
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#fff">
	  <script type="text/javascript" src="../css/teclasN.js"></script>
	  <script type="text/javascript">
	  	/*
 function limitar(e, contenido, caracteres)
        {
            // obtenemos la tecla pulsada
            var unicode=e.keyCode? e.keyCode : e.charCode;
 
            // Permitimos las siguientes teclas:
            // 8 backspace
            // 46 suprimir
            // 13 enter
            // 9 tabulador
            // 37 izquierda
            // 39 derecha
            // 38 subir
            // 40 bajar
            if(unicode==8 || unicode==46 || unicode==13 || unicode==9 || unicode==37 || unicode==39 || unicode==38 || unicode==40)
                return true;
 
            // Si ha superado el limite de caracteres devolvemos false
            if(contenido.length>=caracteres)
                return false;
 
            return true;
        }*/
	  </script>
	</head>
	
	<body> 
	<?php 	include("../components/barra_lateral2.php");
		//include("../css/barra_horizontal1.php"); ?>
	<div id="page-wrapper">
	<div class="container-fluid">

	<form method="post" name="formulary" id="formulary" role="form">
	
	<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Personal</b>
	<?php 
	if(isset($_POST['checkTodos'])){	?>

	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='index.php';"> Mostrar todo
		</label>
	 	<?php
	}
	else{ ?>
	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
		</label>
		<?php
 	} ?></div>
<div class="panel-body">

<div class="col-xs-3">
<label for="NumEmp">Código empleado</label>
<input class="form-control" type="text" id="NumEmp" name="NumEmp" value="<?php if(isset($_GET['edit'])) echo $getROW['numemple'];  ?>" size="20" placeholder="Número Empleado" required maxlength='3' onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Nombre">Nombre(s)</label>
<input class="form-control" type="text" id="Nombre" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['Nombre'];  ?>" size="20" placeholder="Nombre del Empleado"></p>
</div>

<div class="col-xs-3">
<label for="Apellido">Apellidos</label>
<input type="text" id="Apellido" class="form-control" name="Apellido" value="<?php if(isset($_GET['edit'])) echo $getROW['apellido'];  ?>" size="20" placeholder="Apellidos" required></p>
</div>

<div class="col-xs-3">
<label for="Telefono">Teléfono</label>
<input type="text"  id="Telefono" class="form-control" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['Telefono'];  ?>" size="20" placeholder="Teléfono del Empleado" required onkeypress="return numeros(event)"></p>
</div>

<div class="col-xs-3">
<label for="Depa">Departamento</label>
<select name="Depa" class="form-control" list="exampleList" id="Depa" required>
<option value="">[Seleccione un Departamento]</option>

<?php //Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $depa where baja=1");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

	if(isset($_GET['edit'])){ 
    	if($getROW['departamento'] == $row['nombre']){  ?>
			<option value="<?php echo $getROW['departamento'];?>" selected> <?php echo $getROW['departamento'];?></option>
			<?php  
		}
		else{  //Sino manda la lista normalmente:  ?>
			<option value="<?php echo $row ['nombre'];?>"><?php echo $row ['nombre'];?></option>
			<?php 
		}	
	}
	else{	//Sino manda la lista normalmente:  		?>
		<option value="<?php echo $row ['nombre'];?>"><?php echo $row ['nombre'];?></option>
		<?php 
	} 
}  ?> 
</select>
</div>
</div>
<?php
if(isset($_GET['edit'])){	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update" >Actualizar</button>
	<?php  
}else{	?>
	<button class="btn btn-default" type="submit"  style="float:right;" name="save">Guardar</button>
	<?php 
} ?>
</form>
</div>
<div class="table-responsive">
<table  border="0" cellpadding="0" class="table table-hover">
<?php include ("funciones/mostradorEmpleado.php"); 
echo("</table></div>");

if(isset($_POST['checkTodos'])){?>
<div class="table-responsive">
	<table border='0' cellpadding='0' class="table table-hover">
	<?php 	include ("funciones/mostrarTodoEmpleado.php");	?>
	</table></div>
	<?php
} ?>
</div>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); 
}
else {
	include "../ingresar.php"; 
} ?>