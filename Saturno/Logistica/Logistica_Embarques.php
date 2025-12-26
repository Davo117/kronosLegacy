<?php
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	 
	} else {
	  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	 
	exit;
	}
	 
	$now = time();
	 
	if($now > $_SESSION['expire']) {
	session_destroy();
	 
	echo '<script language="javascript">alert("Tu sesión caducó");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Embarques(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
 <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudEmbarque.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");
?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:590px;padding-top:20px";   id="titulo">Embarques<p>
</div>
</center>
<br>
<center>
<div id="form">

<form method="post" name="formulary" id="formulary">
<br>
<p>Sucursal:<?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="sucursal" list="exampleList">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['sucEmbFK'] == $row['nombresuc']){
?>
<option value="<?php echo $getROW['sucEmbFK'];?>" selected> <?php echo $getROW['sucEmbFK'];?></option>
<?php  
} }
elseif( $row ['nombresuc']==$_SESSION['descripcion'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado  ?>
  <option value="<?php echo $row ['nombresuc'];?>" selected>
  <?php echo $row ['nombresuc'];?>    
  </option>
  <?php 
} else{ //Sino manda la lista normalmente:      ?>
<option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
<?php 
} }  ?> 
</datalist>
</select>

  Producto: 
<?php 

// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="producto" list="exampleList1">
<datalist id="exampleList1">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $impresion where baja=1 ");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['prodEmbFK'] == $row['descripcionDisenio']){
?>
<option value="<?php echo $getROW['prodEmbFK'];?>" selected> <?php echo $getROW['prodEmbFK'];?></option>
<?php  
} }
  else{ //Sino manda la lista normalmente:  


?>
<option value="<?php echo $row ['descripcionDisenio'];?>"><?php echo $row ['descripcionDisenio'];?></option>
<?php 
 } } ?> 
</datalist>
</select>

&nbsp 

Empaque: 
<?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="empaque" list="exampleList2">
<datalist id="exampleList2">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM empaque ");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {  ?> 

<option  value="<?php echo $row ['nameEm'];?>"><?php echo $row['nameEm'];?></option>
<?php } ?> 
</datalist>
</select>
</p>
<br>

<p>Transporte:<input type="text" name="transporte" value="<?php if(isset($_GET['edit'])) echo $getROW['transpEmb'];  ?>"  size="20" placeholder="Transporte" required>    
&nbsp &nbsp &nbsp 
  Referencia:<input type="text" name="referencia" value="<?php if(isset($_GET['edit'])) echo $getROW['referencia'];  ?>" size="20" placeholder="Referencia" >
  </p> 

<br>
<p>Fecha Embarque:<input type="date" name="dateEmbar" value="<?php if(isset($_GET['edit'])) echo $getROW['diaEmb'];  ?>" size="20" required>
&nbsp  &nbsp &nbsp 
 Observaciones:<input type="text" name="observacion" value="<?php if(isset($_GET['edit'])){ echo $getROW['observaEmb'];} else{ echo "Sin Observaciones";}  ?>" size="20" placeholder="Observaciones" >
  </p>   

<?php
if(isset($_GET['edit']))
{	?>
	<button type="submit" name="update" onclick="valida_envia()">Actualizar</button>
	<?php  }
else
{  ?>
	<button type="submit" name="save" onclick="valida_envia()">Guardar</button>
	<?php } ?>
</form>



<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorEmbarque.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_Embarques.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>

<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoEmbarque.php");
} 

?>
</form>
</div>
</center>




</body>
<script type="text/javascript">

	function valida_envia(){ 
   	
   	if (document.formulary.transporte.value.length==0){ 
      	alert("Tiene que escribir un transporte valido") 
      	document.formulary.transporte.focus(); 
      	return 0; 
   	} 

 	if (document.formulary.dateEmbar.value.length==0){ 
      	alert("Tiene que escribir una fecha valida") 
      	document.formulary.dateEmbar.focus(); 
      	return 0; 
   	} 
}

</script>

</html>
