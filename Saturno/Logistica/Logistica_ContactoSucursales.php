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
<title>Contacto Sucursal(Logística) | Grupo Labro</title>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
 <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudSucCon.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");

?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:300px;padding-top:20px";   id="titulo">Contacto de Sucursales por Cliente<p>
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
$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1 ORDER BY idsuc DESC");
//'nombresuc'

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
}	else{	//Sino manda la lista normalmente:  	

	?>
<option value="<?php echo $row ['nombresuc'];?>"><?php echo $row ['nombresuc'];?></option>
<?php 
} }  ?> 
</datalist>
</select>

    Nombre:<input type="text" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreconsuc'];  ?>" size="20" placeholder="Nombre Completo" required>    

	Puesto:<input type="text" name="Puesto" value="<?php if(isset($_GET['edit'])) echo $getROW['puestoconsuc'];  ?>" size="20" placeholder="Puesto" required>
	</p>   <br>

	<p>Teléfono:<input type="text" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['telconsuc'];  ?>"size="20" placeholder="Número Telefónico" required>   

	Celular:<input type="text" name="Celular" value="<?php if(isset($_GET['edit'])) echo $getROW['movilconsuc'];  ?>" size="20" placeholder="Celular" >

	Correo:<input type="text" name="Correo" value="<?php if(isset($_GET['edit'])) echo $getROW['emailconsuc'];  ?>" size="20" placeholder="Correo Electrónico" required>
	</p> 

<?php
if(isset($_GET['edit']))
{
	?>
	<button type="submit" name="update"onclick="valida_envia()">Actualizar</button>
	<?php
}
else
{
	?>
	<button type="submit" name="save" onclick="valida_envia()">Guardar</button>
	<?php } ?>
	
</form>

<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorSucCon.php"); ?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_ContactoSucursales.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>

<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include("funciones/nomostrar/mostrarTodoSucCon.php");
} 
?>
</form>

</div>
</center>
</body>
</html>