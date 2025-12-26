<?php

session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();	 
	include ("Controlador_Disenio/db_Producto.php");
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Anillox | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="../css/teclasN.js"></script>
</head>

<body> 
<?php
//$_SESSION['codigoPermiso']='20005';
//include ("funciones/permisos.php");
//include("Controlador_Disenio/bitacoras/bitacoraBS.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal2.php");
include("Controlador_Disenio/crud_Anillox.php"); ?>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<p  id="titulo">Anillox<p>

<div class="fields"><p>Identificador</p>
<input required type="text" name="identificador" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorAnillox'].'" readonly="true';  ?>" size="20" placeholder="identificador">
</div>

<div class="fields"><p>Num Líneas</p>
 <input required type="text" name="lineas" value="<?php if(isset($_GET['edit'])) echo $getROW['num_lineas'];?>" size="20" placeholder="número líneas" onkeypress="return numeros(event)">
</div>

<div class="fields"><p>BCM</p>
<input required type="text" name="bcm" value="<?php if(isset($_GET['edit'])) echo $getROW['bcm'];?>"
    size="10" placeholder="bcm" onkeypress="return numeros(event)">
</div>


<div class="fields"><p>Procesos</p>
<p><?php // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="proceso" required id="example">
    <option value="">[Seleccione un Proceso]</option>
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM procesos where baja=1 ORDER BY descripcionProceso");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:
	if(isset($_GET['edit'])){ 
    	if($getROW['proceso'] == $row['descripcionProceso']){  ?>
			<option value="<?php echo $getROW['proceso'];?>" selected> <?php echo $getROW['descripcionProceso'];?></option>
			<?php  
		}
		else{  //Sino manda la lista normalmente:          ?>
			<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
			<?php 
		} 
	}
	else{   //Sino manda la lista normalmente:          ?>
		<option value="<?php echo $row ['descripcionProceso'];?>"><?php echo $row ['descripcionProceso'];?></option>
		<?php 
	} 
}  ?> 
</datalist>
</select>
</p></div> 

<?php
if(isset($_GET['edit'])){	?>
	<button type="submit" class="btnStyle1" name="update">Actualizar</button>
	<?php
}
else{	?>
	<button class="btnStyle1" type="submit" name="save">Guardar</button>
	<?php
} ?>
</form>
<p class="ordenMedio">Anillox Activos</p>
<table border='0' cellpadding='15' style='margin-left:15%; width: 60%;'>
<?php
include ("Controlador_Disenio/showAnillox.php");
?>
</table>
</div>
</center>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
ob_end_flush();
}
else {
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>"; 
	exit;
} ?>