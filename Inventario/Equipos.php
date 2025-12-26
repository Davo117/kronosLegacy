<div class="navbar-header" >
            <a class="navbar-brand" href="index.php">
                <img src="pictures/logo-labro.png" alt="Grupo Labro Inventario" width="160" height="50">
            </a>
        </div>
<?php
	include ("funciones/crudEquipos.php"); ?>
	    
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Equipos TI | Grupo Labro</title>
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
	<div class="panel-heading"><b class="titulo">Inventario De Equipos</b>
	<?php 
	if(isset($_POST['checkTodos']))
	{	?>

	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Equipos.php';"> Mostrar todo
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
<label for="Descripcion">Descripcion</label>
<input class="form-control" type="text" id="Descripcion" name="Descripcion" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcion'];  ?>" size="20" required placeholder="Descripcion del Equipo"></p>
</div>

<div class="col-xs-3">
<label for="Nombre">Nombre</label>
<input type="text" id="Nombre" class="form-control" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombre'];  ?>" size="20" placeholder="Nombre del Equipo" required></p>
</div>

<div class="col-xs-3">
<label for="Responsable">Responsable</label>








<?php /* Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="Responsable" required id="Responsable" onChange="showCombo(this.value)" class="form-control">
    <option value="">[Seleccione un Cliente]</option>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM empleados where bajacli=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
    if($getROW['idcliFKS'] == $row['nombrecli']){  ?>
<option value="<?php echo $getROW['idcliFKS'];?>" selected> <?php echo $getROW['idcliFKS'];?></option>
<?php  
}else{  //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php } }

elseif( $row ['nombrecli']==$_SESSION['cliente'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
    <option value="<?php echo $row ['nombrecli'];?>" selected>
    <?php echo $row ['nombrecli'];?>        
    </option>
    <?php 
}   else{   //Sino manda la lista normalmente:          ?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php } }  ?> 
</select>
*/ ?>












<input type="text" id="Responsable" class="form-control" name="Responsable" value="<?php if(isset($_GET['edit'])) echo $getROW['responsable'];  ?>" size="20" placeholder="Responsable Del Equipo" required></p>
</div>

<div class="col-xs-3">
<label for="Departamento">Departamento</label>
<input type="text" id="Departamento" class="form-control" name="Departamento" value="<?php if(isset($_GET['edit'])) echo $getROW['departamento'];  ?>" size="20" placeholder="Departamento Del Equipo" required></p>
</div>
<div class="col-xs-3">
<label for="Marca">Marca</label>
<input type="text" id="Marca" class="form-control" name="Marca" value="<?php if(isset($_GET['edit'])) echo $getROW['marca'];  ?>" size="20" placeholder="Marca Del Equipo" required></p>
</div>

<div class="col-xs-3">
<label for="Modelo">Modelo</label>
<input type="text" id="Modelo" class="form-control" name="Modelo" value="<?php if(isset($_GET['edit'])) echo $getROW['modelo'];  ?>" size="20" placeholder="Modelo Del Equipo" required></p>
</div>


<div class="col-xs-3">
<label for="Procesador">Procesador</label>
<input type="text" id="Procesador" class="form-control" name="Procesador" value="<?php if(isset($_GET['edit'])) echo $getROW['procesador'];  ?>" size="20" placeholder="Tipo de Procesador" required></p>
</div>


<div class="col-xs-3">
<label for="MemoriaRAM">Memoria RAM</label>
<input type="text" id="MemoriaRAM" class="form-control" name="MemoriaRAM" value="<?php if(isset($_GET['edit'])) echo $getROW['memoriaRAM'];  ?>" size="20" placeholder="Tamaño RAM" required></p>
</div>


<div class="col-xs-3">
<label for="DiscoDuro">Disco Duro</label>
<input type="text" id="DiscoDuro" class="form-control" name="DiscoDuro" value="<?php if(isset($_GET['edit'])) echo $getROW['capacidadHDD'];  ?>" size="20" placeholder="Tamaño del Disco Duro" required></p>
</div>


<div class="col-xs-3">
<label for="Costo">Costo</label>
<input type="text" id="Costo" class="form-control" name="Costo" value="<?php if(isset($_GET['edit'])) echo $getROW['costoEquipo'];  ?>" size="20" placeholder="Costo Del Equipo" required></p>
</div>


<div class="col-xs-3">
<label for="Factura">Factura</label>
<input type="text" id="Factura" class="form-control" name="Factura" value="<?php if(isset($_GET['edit'])) echo $getROW['factura'];  ?>" size="20" placeholder="Factura Del Equipo" required></p>
</div>


<div class="col-xs-3">
<label for="fecha">Fecha de Ingreso</label>
<input type="date" id="fch_ingreso" class="form-control" name="fch_ingreso" value="<?php if(isset($_GET['edit'])) echo $getROW['fchEntrada'];  ?>" size="20" required></p>
</div>


<?php
if(isset($_GET['edit']))
{	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
	<?php  
}else
{	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="save">Guardar</button>
	<?php 
} ?>
</form>
</div>
<div class="table-responsive">
<table  border="0" cellpadding="0" class="table table-hover">
<?php include ("funciones/mostradorEquipos.php"); 
echo("</table></div>");

if(isset($_POST['checkTodos']))
{?>
	<div class="table-responsive">
	<table border='0' cellpadding='0' class="table table-hover">
	<?php 	include ("funciones/mostrarTodoEquipos.php");	?>
	</table></div>
	<?php
} ?>
</div>
</div>
</body>
</html>
<?php ob_end_flush();  ?>