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
	header("Content-Type: text/html;charset=utf-8");
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Requerimientos por Orden(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudOrdenReq.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");

?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:200px;padding-top:20px";   id="titulo">Requerimientos de Producto Por Orden Compra<p>
</div>
</center>
<br>
<center>
<div id="form">

<form method="post" name="formulary" id="formulary">
<br>
<p id="demo">Orden: </p>
   <p>Orden Compra:<?php 

// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="orden" list="exampleList" id="mySelect" onchange="myFunction()">
<datalist id="exampleList">
    <?php
    

//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT idorden, orden, nombresuc FROM $tablaOrden, $tablasucursal where bajaOrden=1 and $tablasucursal.nombresuc =$tablaOrden.sucFK ");


//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['ordenReqFK'] == $row['orden'] ){

?>
<script type="text/javascript">

		document.getElementById("demo").innerHTML = "Orden: <?php echo $getROW['ordenReqFK']; ?> ";
	</script>

<option value="<?php echo $getROW['ordenReqFK'];?>" selected> 
<?php echo $getROW['ordenReqFK']."| ". $row['nombresuc']; ?>

</option>
<?php  
}	}



elseif( $row ['orden']==$_SESSION['descripcion'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado	?>

<script type="text/javascript">
		document.getElementById("demo").innerHTML = "Orden: <?php echo $row['orden']; ?> ";
	</script>

	<option value="<?php echo $row ['orden'];?>" selected>
	<?php echo $row['nombresuc']." | " .$row['orden'];?>	
	</option>
	<?php 
}	else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['orden'];?>">
<?php echo $row['nombresuc']." | " .$row['orden'];?>
</option>
<?php 
} }  ?> 
</datalist>
</select>
&nbsp 



   Producto: 
<?php 

// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="producto" list="exampleList1">
<datalist id="exampleList1">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $prodcli where baja=1 ");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['prodcliReqFK'] == $row['nombre']){
?>
<option value="<?php echo $getROW['prodcliReqFK'];?>" selected> <?php echo $getROW['prodcliReqFK'];?></option>
<?php  
}	}
	else{	//Sino manda la lista normalmente: 	


?>
<option value="<?php echo $row ['nombre'];?>"><?php echo $row ['nombre'];?></option>
<?php 
 } } ?> 
</datalist>
</select>


</p>
<br>
	<p>Cantidad:<input type="text" name="cantidad" value="<?php if(isset($_GET['edit'])) echo $getROW['cantReq'];  ?>"	size="20" placeholder="Cantidad" >    

	Referencia:<input type="text" name="referencia" value="<?php if(isset($_GET['edit'])) echo $getROW['refeReq'];  ?>" size="20" placeholder="Referencia" >
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
include ("funciones/mostrar/mostradorOrdenReq.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_RequerimientosOrden.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>


<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>

<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoOrdenReq.php");
} 
?>

</form>

</div>
</center>
</body>
<script type="text/javascript">

function myFunction() {
    var x = document.getElementById("mySelect").value;
<?php  $_SESSION['demoniaco']= $_GET['x'];?>    
    document.getElementById("demo").innerHTML = "Orden: " + x;
}

	function valida_envia(){ 
   	//validar los campos: nombre 
   	if (document.formulary.RFC.value.length==0){ 
      	alert("Tiene que escribir un RFC valido") 
      	document.formulary.RFC.focus(); 
      	return 0; 
   	} 

 	if (document.formulary.Nombre.value.length==0){ 
      	alert("Tiene que escribir un nombre valido") 
      	document.formulary.Nombre.focus(); 
      	return 0; 
   	} 

if (document.formulary.Domicilio.value.length==0){ 
      	alert("Tiene que escribir un domicilio valido") 
      	document.formulary.Domicilio.focus(); 
      	return 0; 
   	} 
if (document.formulary.Colonia.value.length==0){ 
      	alert("Tiene que escribir una colonia valida") 
      	document.formulary.Colonia.focus(); 
      	return 0; 
   	} 

	if (document.formulary.Ciudad.value.length==0){ 
      	alert("Tiene que escribir un ciudad valido") 
      	document.formulary.Ciudad.focus(); 
      	return 0; 
   	} 
   		if (document.formulary.CP.value.length==0){ 
      	alert("Tiene que escribir un Codigo Postal valido") 
      	document.formulary.CP.focus(); 
      	return 0; 
   	} 
   		if (document.formulary.TelefonoCli.value.length==0){ 
      	alert("Tiene que escribir un telefono valido") 
      	document.formulary.TelefonoCli.focus(); 
      	return 0; 
   	} 
}



</script>
</html>
