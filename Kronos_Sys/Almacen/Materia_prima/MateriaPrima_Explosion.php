<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Explosión | Materia Prima</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>

<body> 
<?php 
$codigoPermiso='16';
include("../components/barra_latera_almacen.php");
include("../Database/db.php");
include("Controlador_Bloques/crud_explosion.php"); ?>

<div id="page-wrapper">
	<div class="container-fluid">

<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Explosión de Materiales</b></div>
	<div class="panel-body">

<div class="col-xs-3">
<label for="example">Producto</label><?php  // Creamos un select para traer el nombre del cliente:
//El atributo name sirve para utilizar los option en el crud ?>
<select name="example" id="example" onChange="document.formulary.submit()"  class="form-control" >
    <option value="0">[Todos]</option>
<?php //Seleccionar todos los datos de la tabla los productos ya terminados cargados en el apartado de impresion
$resultado=$MySQLiconn->query("SELECT id,descripcionImpresion FROM impresiones where baja=1");
//mientras se tengan registros: te mostrara el nombre
while ($row = $resultado->fetch_array()) {	?>
	<option  value="<?php echo $row ['id'];?>"
	<?php
	if( isset($_POST['example']) && $_POST['example']==$row['id']) {
	 	echo "selected"; 
	}?> 
	><?php echo $row ['descripcionImpresion'];?></option>
	<?php
}  ?> 
</select>
</div>

<div class="col-xs-3">
<label for="planta">[Sucursal]O. C.:</label>
<select id="planta" name="planta" class="form-control">
   <option value="0">[Todos]</option>
  <?php
  $segunda=$MySQLiconn->query("SELECT ordencompra.orden FROM ordencompra INNER JOIN requerimientoprod ON ordencompra.idorden = requerimientoprod.ordenReqFK where requerimientoprod.prodcliReqFK= '".$_POST['example']."'");
while($row2 = $segunda->fetch_array()){
/*
	$quinto= $MySQLiconn->query("SELECT ordenReqFK FROM requerimientoprod where ordenReqFK= '".$_POST['example']."' "); 
	$row5 = $quinto->fetch_array();*/?> 

   	<option value="<?php echo $row2['orden'];?>"><?php echo $row2['orden']." ";?></option><?php
   }?>


</select>
</div>
</div>
<button class="btn btn-info" style="float:right;" type="submit" name="filtro" >Filtrar</button>
</form>
</div>
<?php include ("Controlador_Bloques/showExplosion.php"); ?>
</div>

</body>
	<script type="text/javascript" src="../css/menuFunction.js"></script>
<script src="Controlador_Bloques/ajax.js"></script>
<script type="text/javascript" language="JavaScript">

function showCombo(str){
	loadDoc("r="+str,"comboboxExplosion.php",function(){
    	if (xmlhttp.readyState==4 && xmlhttp.status==200){
      		document.getElementById("planta").innerHTML=xmlhttp.responseText;
    	}
  	});
}
</script>
</html>
<?php
ob_end_flush();
}else{
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	include "../ingresar.php";
} ?>