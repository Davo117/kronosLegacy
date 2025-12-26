<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	$codigoPermiso='17'; ?>

<!DOCTYPE>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Clientes(Logística) | Grupo Labro</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#fff">
	<script type="text/javascript" src="../css/teclasN.js"></script>
</head>
<body> 
	<?php	include("../components/barra_lateral2.php");

	include ("funciones/bitacoras/clienteB.php"); 
	include ("funciones/crud/crudCliente.php"); ?>

<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">

<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Clientes</b>
	<?php 
	if(isset($_POST['checkTodos'])){ ?>
		<label class="checkbox-inline" style="float:right;">
		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo
		</label><?php
	}
	else{?>
		<label class="checkbox-inline" style="float:right;">
		<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
		</label><?php
	}?></div>

<div class="panel-body">

<div class="col-xs-3">
<label for="Ciudad">Ciudad</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="Ciudad" data-live-search="true"  class="selectpicker show-menu-arrow form-control" id="Ciudad" required>
	<option value="">[Seleccione una Ciudad]</option>
<?php //esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM estado where baja=1 order by id");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	// Comparas tu variable con el estado
    if($empresa != $row['nombreEstado']) {
        // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        $empresa = $row['nombreEstado'];
		//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
    	//no te olvides de la etiqueta de cierre ?>
	<optgroup label="<?php echo $empresa; ?>">
	<?php //cerramos el if anterior:
	}
	$consulta = $MySQLiconn->query("SELECT * FROM ciudad where baja=1  && estado='".$row['id']."' order by id");
	
	while($rowCity=$consulta->fetch_array()){
		$concat=$rowCity['nombreCity'].", ".$row['abreviatura'];
		//en caso de editar los campos...
		if(isset($_GET['edit'])){
			if($getROW['ciudadcli'] == $concat){?>
				<option value="<?php echo $concat;?>" selected> <?php echo $concat;?></option>
				<?php  
			}
			else
			{	//Sino manda la lista normalmente:  		?>
				<option value="<?php echo $concat;?>"><?php echo $concat;?></option>
				<?php 
			}
		}
		else
		{	//Sino manda la lista normalmente: ?>
			<option value="<?php echo $concat;?>"><?php echo $concat;?></option> <?php
		}
	}
} ?>
</optgroup>
</select>
</div>
<div  class="col-xs-3">
<label for="RFC">RFC</label>
<?php //Quite el readonly="true"; en el get edit ?>
<input type="text" name="RFC" id="RFC" class="form-control" value="<?php
 if(isset($_GET['edit'])) echo $getROW['rfccli'];  ?>"	size="20" placeholder="#" required>
</div>

<div class="col-xs-3"><label for="Nombre">Nombre</label>
<input type="text" name="Nombre" id="Nombre" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombrecli'];  ?>" size="20" placeholder="Razón Social" required>
</div>

<div class="col-xs-3">
<label for="Domicilio">Domicilio</label>
<input type="text" id="Domicilio" class="form-control" name="Domicilio" value="<?php if(isset($_GET['edit'])) echo $getROW['domiciliocli'];  ?>" size="20" placeholder="Calle y Número" required>
</div>

<div class="col-xs-3">
<label for="Colonia">Colonia</label>
<input type="text" class="form-control" id="Colonia" name="Colonia" value="<?php if(isset($_GET['edit'])) echo $getROW['coloniacli'];  ?>" size="20" placeholder="Colonia" required>
</div>

<div class="col-xs-3">
<label for="CP">CP</label>
<input type="text" id="CP" class="form-control" name="CP" value="<?php if(isset($_GET['edit'])) echo $getROW['cpcli'];  ?>" 	size="20" placeholder="Código Postal" required onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="TelefonoCli">Teléfono</label>
<input type="text" name="TelefonoCli" class="form-control"  value="<?php if(isset($_GET['edit'])) echo $getROW['telcli'];  ?>"size="20" placeholder="Número Telefónico" required onkeypress="return numeros(event)">
</div>

</div>
	<?php if(isset($_GET['edit'])){	?>
		<button class="btn btn-default" type="submit" style="float:right;" name="update">Actualizar</button>
		<?php
	}
	else{	?>
		<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
		<?php
	} ?>
	</form>
</div>


<div class="table-responsive col-xs-10">
	<div class="col-xs-3"></div>
	<div class="col-xs-4"><input type="text"  name="caja_busqueda" class="form-control" id="caja_busqueda" onkeyup="tratar(this.value)"	placeholder="Búsqueda General" style="border-style:border: 2px solid; border-color: #31B0D5;
  border-radius: 5px;">
	</div>
	
	<div class="col-xs-3"><img class="img-responsive" width="30" height="30" src="../pictures/buscar.png">
	</div>
</div>

<div id="muestra" class="col-xs-12 table-responsive"></div>

	
	<?php 
if(isset($_POST['checkTodos'])){?>
	<div class="table-responsive col-xs-10">
	<table  border='0' cellpadding='15' class="table table-hover">
	<?php	
include ("funciones/nomostrar/mostrarTodoCliente.php");	?>
	</table>
</div>
	<?php
}?>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript"> 
$(tratar(''));


function tratar(valor){
 	if (valor != "") {
 		$('#muestra').load('funciones/mostrar/mostradorCliente.php?em='+encodeURI(valor));
 	}else{
 		$('#muestra').load('funciones/mostrar/mostradorCliente.php');
 	}
}</script>
</html>
<?php ob_end_flush();
} 
else {
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
} ?>