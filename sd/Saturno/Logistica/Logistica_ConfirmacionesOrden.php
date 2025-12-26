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
<title>Confirmaciones(Logística) | Grupo Labro</title>
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
include("funciones/crud/crudOrdenConfi.php");
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");

?>
<center>
<div id="form">

<p style="font-size:25px;font-family: monospace;padding-right:190px;padding-top:20px"; id="titulo">Confirmaciones de Producto por Orden Compra<p>

</div>
</center>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<br>
<p id="demo" style="margin-right: 600px;">Orden: </p>

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
if($getROW['ordenConfi'] == $row['orden'] ){

?>
<script type="text/javascript">
    document.getElementById("demo").innerHTML = "Orden: <?php echo $row['ordenConfi']; ?> ";
  </script>
<option value="<?php echo $getROW['ordenConfi'];?>" selected> 
<?php echo $getROW['ordenConfi']."| ". $row['nombresuc']; ?>

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
$resultado = $MySQLiconn->query("SELECT * FROM $impresion where baja=1 ");

//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['prodConfi'] == $row['descripcionDisenio']){
?>
<option value="<?php echo $getROW['prodConfi'];?>" selected> <?php echo $getROW['prodConfi'];?></option>
<?php  
}	}
	else{	//Sino manda la lista normalmente: 	


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

<p>Cantidad:<input type="text" name="cantidad" value="<?php if(isset($_GET['edit'])) echo $getROW['cantidadConfi'];  ?>"	size="20" placeholder="Cantidad" >    
&nbsp &nbsp 
	Referencia:<input type="text" name="referencia" value="<?php if(isset($_GET['edit'])) echo $getROW['referenciaConfi'];  ?>" size="20" placeholder="Referencia" >
	</p> 

<br>
<p>Fecha Embarque:<input type="date" name="dateEmbar" value="<?php if(isset($_GET['edit'])) echo $getROW['embarqueConfi'];  ?>" size="20">
&nbsp  &nbsp 
	Fecha Entrega:<input type="date" name="dateEntre" value="<?php if(isset($_GET['edit'])) echo $getROW['entregaConfi'];  ?>"size="20" >
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
include ("funciones/mostrar/mostradorOrdenConfir.php"); 
?>

<a href="?mostrar" >Mostrar Todos</a>

<br>

<a href="../Logistica/Logistica_ConfirmacionesOrden.php" onclick="valor(this.form)"> Ocultar</a>


<form method="post">
<?php 
if(isset($_GET['mostrar']) ) { ?>
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<br /><br /><br /><br />
<?php
include("funciones/nomostrar/mostrarTodoOrdenConfir.php");
} 
?>

</form>
</div>
</center>
</body>



<script type="text/javascript">


function myFunction() {
    var x = document.getElementById("mySelect").value;
    
    document.getElementById("demo").innerHTML = "Orden: " + x;
}

	function valida_envia(){ 
   	//validar los campos: nombre 
   	if (document.formulary.dateEmbar.value.length==0){ 
      	alert("Tiene que escoger una fecha valida para el embarque") 
      	document.formulary.dateEmbar.focus(); 
      	return 0; 
   	} 

 	if (document.formulary.dateEntre.value.length==0){ 
      	alert("Tiene que escoger una fecha valida para la entrega") 
      	document.formulary.dateEntre.focus(); 
      	return 0; 
   	} 

if (document.formulary.cantidad.value.length==0){ 
      	alert("Tiene que escribir una cantidad valida") 
      	document.formulary.cantidad.focus(); 
      	return 0; 
   	} 
}

</script>

</html>
