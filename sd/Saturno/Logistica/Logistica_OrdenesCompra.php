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
<title>Orden Compra(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudOrdenC.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");
?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:520px;padding-top:20px";   id="titulo">Ordenes compra<p>
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
<select name="example" list="exampleList">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['sucFK'] == $row['nombresuc']){
?>
<option value="<?php echo $getROW['sucFK'];?>" selected> <?php echo $getROW['sucFK'];?></option>
<?php  
}	}
elseif( $row ['nombresuc']==$_SESSION['descripcion'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado	?>
	<option value="<?php echo $row ['nombresuc'];?>" selected>
	<?php echo $row ['nombresuc'];?>		
	</option>
	<?php 
}	else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
<?php 
} }  ?> 
</datalist>
</select>


	Orden Compra:<input type="text" name="ordenC" value="<?php if(isset($_GET['edit'])) echo $getROW['orden'];  ?>"	size="20" placeholder="Número Orden" >
	</p> <br>
	<p>Fecha Documento:<input type="date" name="dateDoc" value="<?php if(isset($_GET['edit'])) echo $getROW['documento'];  ?>" size="20">

	Fecha Recepción:<input type="date" name="dateRec" value="<?php if(isset($_GET['edit'])) echo $getROW['recepcion'];  ?>"size="20" >
	</p>   
	
<?php
if(isset($_GET['edit']))
{	//Si se da clic en Editar, el boton formulario cambia su valor a Actualizar?>
	<button type="submit" name="update" onclick="valida_envia()">Actualizar</button>
	<?php  }
else
{  //Sino El boton tendra su Valos de Guardar?>
	<button type="submit" name="save" onclick="valida_envia()">Guardar</button>
	<?php } ?>
</form>





<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorOC.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_OrdenesCompra.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>

<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>

<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoOC.php");
} 
?>

</form>
</div>
</center>







</body>
<script type="text/javascript">
//ordenC  dateDoc dateRec
	function valida_envia(){ 
   	//validar los campos: nombre 
   	if (document.formulary.ordenC.value.length==0){ 
      	alert("Tiene que escribir una orden valida") ;
      	document.formulary.ordenC.focus(); 
      	return 0; 
   	} 

 	if (document.formulary.dateDoc.value.length==0){ 
      	alert("Tiene que escoger una fecha para el documento") ;
      	document.formulary.dateDoc.focus(); 
      	return 0; 
   	} 

	if (document.formulary.dateRec.value.length==0){ 
      	alert("Tiene que escoger una fecha para la recepción") ;
      	document.formulary.dateRec.focus(); 
      	return 0; 
   	} 
}

</script>

</html>
