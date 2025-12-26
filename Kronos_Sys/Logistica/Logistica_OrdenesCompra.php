<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();	
	$codigoPermiso='21';
	?>

<!DOCTYPE>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Orden Compra(Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>

<body>
<?php	include("../components/barra_lateral2.php");
include ("funciones/bitacoras/ordencompraB.php"); 
	include("funciones/crud/crudOrdenC.php"); 
?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

<div class="panel-heading"><b class="titulo">Ordenes de compra</b><?php
if(isset($_POST['checkTodos'])){ ?>
    <label class="checkbox-inline" style="float:right;">
    <input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo </label><?php
}
else{?>
    <label class="checkbox-inline" style="float:right;">
        <input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo</label><?php
}?></div>
<div class="panel-body">
<div class="col-xs-3">
<label for="example">Sucursal</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select data-live-search="true"  class="selectpicker show-menu-arrow form-control" name="example" id="example" required >
	<option value="">[Seleccione una Sucursal]</option>
    <?php
    //esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT idcliFKS, nombresuc,idsuc FROM $tablasucursal where bajasuc=1 order by idcliFKS");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
   // Comparas tu variable con la empresa

$resEmpresa=$MySQLiconn->query("SELECT nombrecli FROM $tablacli where ID=".$row['idcliFKS']);
    $rowE = $resEmpresa->fetch_array();

    if($empresa != $rowE['nombrecli']) {
        // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        $empresa = $rowE['nombrecli'];
		//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
    	//no te olvides de la etiqueta de cierre ?>
	<optgroup label="<?php echo $empresa; ?>">
	<?php //cerramos el if anterior:
	}
	//en caso de editar los campos...
	if(isset($_GET['edit'])){ 
		if($getROW['sucFK'] == $row['idsuc']){?>
			<option value="<?php echo $row['idsuc'];?>" selected> <?php echo $row['nombresuc'];?></option><?php
		}
		else{	//Sino manda la lista normalmente:  		?>
			<option value="<?php echo $row['idsuc'];?>"><?php echo $row ['nombresuc'];?></option>
			<?php 
		}
	}
	elseif( $row ['idsuc']==$_SESSION['sucursal']){ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado?>
		<option value="<?php echo $row ['idsuc'];?>" selected>
		<?php echo $row ['nombresuc'];?>
		</option>	<?php
	}
	else{	//Sino manda la lista normalmente:  		?>
		<option value="<?php echo $row['idsuc'];?>"><?php echo $row ['nombresuc'];?></option>
		<?php
	}
} ?>
</optgroup>
</select>
</div>

<div class="col-xs-3">
<label for="ordenC">Orden Compra</label>
<input type="text" id="ordenC" class="form-control" name="ordenC" value="<?php if(isset($_GET['edit'])) echo $getROW['orden']; ?>"	size="20" placeholder="Número Orden" required>
</div>

<div class="col-xs-3">
<label for="dateDoc">Fecha Documento</label>
<input type="date" class="form-control" id="dateDoc" name="dateDoc" value="<?php if(isset($_GET['edit'])) echo $getROW['documento'];  ?>" size="20" required>
</div>

<div class="col-xs-3">
<label for="dateRec">Fecha Recepción</label>
<input type="date" name="dateRec" id="dateRec" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['recepcion'];  ?>" size="20" required>
</div>
</div>
<?php if(isset($_GET['edit'])){	//Si se da clic en Editar, el boton formulario cambia su valor a Actualizar?>
	<button class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
	<?php  
}
else{  //Sino El boton tendra su Valor de Guardar ?>
<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
	<?php 
} ?>
</form>
</div>

<?php 
if(isset($_POST['checkTodos'])){?>
<div class="table-responsive">
    <table border="0" cellpadding="15" class="table table-hover">
	<?php include("funciones/nomostrar/mostrarTodoOC.php"); 
echo "</table>
</div>";
}?>



<div class="table-responsive col-xs-10">
	<div class="col-xs-3"></div>
	<div class="col-xs-4"><input type="text"  name="caja_busqueda" class="form-control" id="caja_busqueda" onkeyup="tratar(this.value)"	placeholder="Búsqueda General" style="border-style:border: 2px solid; border-color: #31B0D5;
  border-radius: 5px;">
	</div>
	
	<div class="col-xs-3"><img class="img-responsive" width="30" height="30" src="../pictures/buscar.png">
	</div>
</div>

<div id="muestra" class="col-xs-12 table-responsive"></div>


</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript"> 
$(tratar(''));


function tratar(valor){
 	if (valor != "") {
 		$('#muestra').load('funciones/mostrar/mostradorOC.php?em='+encodeURI(valor));
 	}else{
 		$('#muestra').load('funciones/mostrar/mostradorOC.php');
 	}
}</script>
</html> <?php ob_end_flush(); 
} 
else{
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
}?>