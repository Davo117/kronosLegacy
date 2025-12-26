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
<title>Contacto Clientes(Logística) | Grupo Labro</title>
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php
//incluimos el crud con todas sus funciones dentro:
include ("funciones/crud/crudContactoCliente.php"); 
include("../components/barra_lateral2.php");
include("../css/barra_horizontal3.php");
?>
<center>
<div id="form">
<p style="font-size:25px;font-family: monospace;padding-right:500px;padding-top:20px";   id="titulo">Contacto Clientes<p>
</div>
</center>
<br>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
    <br>
    <p>* Cliente:<?php 
// Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" list="exampleList" id="example" onchange="myFunction()">
<datalist id="exampleList">
    <?php
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablacli where bajacli=1 ORDER BY ID DESC");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

if(isset($_GET['edit'])){ 
if($getROW['idcliFK'] == $row['nombrecli']){
?>
<option value="<?php echo $getROW['idcliFK'];?>" selected> <?php echo $getROW['idcliFK'];?></option>
<?php  
}	}
elseif( $row ['nombrecli']==$_SESSION['descripcion'])
{ //Si fue seleccionado. en caso de editar el campo manda a llamar el nombre del "fk" sino, muestra el nombre del cliente seleccionado	?>
	<option value="<?php echo $row ['nombrecli'];?>" selected>
	<?php echo $row ['nombrecli'];?>		
	</option>
	<?php 
}	else{	//Sino manda la lista normalmente:  		?>
<option value="<?php echo $row ['nombrecli'];?>"><?php echo $row ['nombrecli'];?></option>
<?php 
} }  ?> 
</datalist>
</select>


    * Nombre:<input type="text" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreconcli'];  ?>"  size="20" placeholder="Nombre Completo">
  
	* Puesto:<input type="text" name="Puesto" value="<?php if(isset($_GET['edit'])) echo $getROW['puestoconcli'];  ?>" size="20" placeholder="Puesto" >
	</p>   <br>

	<p>* Teléfono:<input type="text" name="Telefono" value="<?php if(isset($_GET['edit'])) echo $getROW['telefonoconcli'];  ?>"size="20" placeholder="Número Telefónico">
	
	Celular:<input type="text" name="Celular" value="<?php if(isset($_GET['edit'])) echo $getROW['movilcl'];  ?>" 	size="20" placeholder="Celular" >
	
    * Correo:<input type="text" name="Correo" value="<?php if(isset($_GET['edit'])) echo $getROW['emailconcli'];  ?>" size="20" placeholder="Correo Electrónico" >
    </p> 
    
<?php
if(isset($_GET['edit']))
{
	?>
	<button type="submit" name="update" onclick="valida_envia()" >Actualizar</button>
	<?php
}
else
{
	?>
	<button type="submit" name="save" onclick="valida_envia()" >Guardar</button>
	<?php } ?>
</form>

<br /><br />
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include ("funciones/mostrar/mostradorContactoCliente.php"); ?>
<p id="holo" name="holo"> Merc</p
<form>
<a href="?mostrar" id="mos" onclick="valor(this.form)">Mostrar Todos</a> 

<br>

<a href="../Logistica/Logistica_ContactoClientes.php" id="ocu" onclick="valor(this.form)"> Ocultar</a>

</form>
<form method="post">
<?php 
if(isset($_GET['mostrar']) ) {
?>
<table width="20%" border="1" cellpadding="15" style="margin-left: 200px">
<?php
include("funciones/nomostrar/mostrarTodoContactoCliente.php");
} 

/*if (isset($_GET['return']))
{
header("Location: Logistica_ContactoClientes.php");
}*/
?>
</form>

</div>
</center>


</body>

<script>
function myFunction() {
    var x = document.getElementById("example").value;
    document.getElementById("holo").innerHTML = "Seleccionaste: " + x;
    
    
}
</script>







<script type="text/javascript">


  function valida_envia(){ 
    //validar los campos: nombre 
    if (document.formulary.Nombre.value.length==0){ 
        alert("Tiene que escribir un Nombre valido") 
        document.formulary.Nombre.focus(); 
        return 0; 
    } 

  if (document.formulary.Puesto.value.length==0){ 
        alert("Tiene que escribir un Puesto valido") 
        document.formulary.Puesto.focus(); 
        return 0; 
    } 

if (document.formulary.Telefono.value.length==0){ 
        alert("Tiene que escribir un Telefono valido") 
        document.formulary.Telefono.focus(); 
        return 0; 
    } 
if (document.formulary.Correo.value.length==0){ 
        alert("Tiene que escribir una Correo valida") 
        document.formulary.Correo.focus(); 
        return 0; 
    } 
}

function valor(x){

     form.mos.style.visibility = "visible"; 


}
</script>
</html>
