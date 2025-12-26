<?php

session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();	 
	$_SESSION['codigoPermiso']='40009';
	include ("funciones/permisos.php");
	include ("funciones/bitacoras/embarqueB.php");
	include("funciones/crud/crudEmbarque.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Embarques(Logística) | Grupo Labro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>

<body> 
<?php	include("../components/barra_lateral2.php");?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary">
<div class="panel panel-default">

                <div class="panel-heading"><b class="titulo">Embarques</b>
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
<label for="exampleList">Sucursal</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="sucursal" required id="exampleList" class="form-control">
<option value="">[Seleccione una Sucursal]</option>
    <?php    //esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT  * FROM  $tablasucursal where bajasuc=1 order by idcliFKS;");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) { //Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
 	// Comparas tu variable con la empresa
    if($empresa != $row['idcliFKS']) {
        // Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        $empresa = $row['idcliFKS'];
		//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
        //no te olvides de la etiqueta de cierre ?>
		<optgroup label="<?php echo $empresa; ?>">
		<?php 
		//cerramos el if anterior:
	}//en caso de editar los campos...
	if(isset($_GET['edit'])){ 
		if($getROW['sucEmbFK'] == $row['nombresuc']){ ?>
			<option value="<?php echo $getROW['sucEmbFK'];?>" selected> <?php echo $getROW['sucEmbFK'];?></option>
		<?php  
		}
		else{ //Sino manda la lista normalmente:      ?>
			<option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
		<?php
		}
	}
	elseif( $row ['nombresuc']==$_SESSION['sucursal']){ 
		//Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
  		<option value="<?php echo $row ['nombresuc'];?>" selected><?php echo $row ['nombresuc'];?>    
  		</option>
  	<?php 
	}
	else{ //Sino manda la lista normalmente:      ?>
		<option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
	<?php
	}
} ?>
 </optgroup>
</select>
</div>

<div  class="col-xs-3">
<label for="producto">Producto</producto>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="producto"  required id="producto" class="form-control">
    <?php //esta variable es una bandera para la ordenacion de campos en el combo box
$producto="r";

//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT descripcionDisenio,descripcionImpresion,codigoImpresion, codigo,descripcion  FROM $impresion, $disenio where $impresion.baja=1 && descripcionDisenio=descripcion; "); ?>
<option value="" selected>[Selecciona un Producto]</option>
<?php //mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
	// Comparas tu variable con la empresa
    if($producto != $row['codigo']) {
        // Ahora habra que comparar con esta producto cada que de vuelta los datos 
        $producto = $row['codigo'];
		//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
        //no te olvides de la etiqueta de cierre ?>
		<optgroup label="<?php echo $producto; ?>">
		<?php
		//cerramos el if anterior:
	}
	if(isset($_GET['edit'])){ 
		if($getROW['prodEmbFK'] == $row['descripcionImpresion']){ ?>
			<option value="<?php echo $row ['descripcionImpresion'];?>" selected><?php echo $row ['descripcionImpresion'];?> (<?php echo $row['codigoImpresion'];?>)</option>
			<?php
		} 
  		else{ //Sino manda la lista normalmente: ?>
			<option value="<?php echo $row ['descripcionImpresion'];?>"><?php echo $row ['descripcionImpresion'];?> (<?php echo $row['codigoImpresion'];?>)</option>
			<?php
		}
	}
	else{ //Sino manda la lista normalmente: ?>
		<option value="<?php echo $row ['descripcionImpresion'];?>"><?php echo $row ['descripcionImpresion'];?> (<?php echo $row['codigoImpresion'];?>)</option>
		<?php
	}
} ?>
</optgroup>
</select>
</div>


<div  class="col-xs-3">
<label for="empaque">Empaque</label>
<?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="empaque" required id="empaque" class="form-control">
	<option value="">Seleccionar Empaque</option>
    <?php //Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM empaque ");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {  
	if(isset($_GET['edit'])){
		if ($row['nameEm']==$getROW['empaque']) {?> 
			<option  value="<?php echo $row ['nameEm'];?>" selected><?php echo $row['nameEm'];?></option>
			<?php 
		}
		else{	?>
			<option  value="<?php echo $row ['nameEm'];?>"><?php echo $row['nameEm'];?></option>
			<?php 
		}
	}else{	?>
		<option  value="<?php echo $row ['nameEm'];?>"><?php echo $row['nameEm'];?></option>
		<?php 
	}
}?> 
</select>
</div>

<div  class="col-xs-3">
<label for="Transporte">Transporte</label>
<input type="text" name="Transporte" id="Transporte" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['transpEmb'];  ?>"  size="20" placeholder="Transporte" required>
</div>

<div  class="col-xs-3">
<label for="DateEmb">Fecha Embarque</label>
<input type="date" name="DateEmb" id="DateEmb" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['diaEmb'];  ?>" size="20" required>
</div>

<div  class="col-xs-3">
<label for="Observa">Observaciones</label>
<input type="text" name="Observa" id="Observa" class="form-control" value="<?php if(isset($_GET['edit'])){ echo $getROW['observaEmb'];} else{ echo "Sin Observaciones";}  ?>" size="20" placeholder="Observaciones" >
</div>
</div>
<?php
if(isset($_GET['edit'])){	?>
	<button class="btn btn-default"  type="submit" style="float:right;" name="update">Actualizar</button>
	<?php  }
else
{  ?>
	<button class="btn btn-default" type="submit" style="float:right;" name="save" >Guardar</button>
	<?php } ?>
</form>
</div>
<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php include("funciones/mostrar/mostradorEmbarque.php"); 
echo'</table>';

if(isset($_POST['checkTodos'])){?>
<div class="table-responsive">
    <table border="0" cellpadding="15" class="table table-hover">
	<?php include("funciones/nomostrar/mostrarTodoEmbarque.php"); 
}?>
</table>
</div>
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