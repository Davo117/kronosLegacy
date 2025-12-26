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
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
  <link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
   <link rel="stylesheet" href="../css/StyleTablas.css" type="text/css" media="screen"/>
</head>

<body> 
<?php

include("../components/barra_lateral2.php");
include("Controlador_Bloques/crud_lotes.php");
include("../css/barra_horizontal4.php");
?>
<center>
<div id="form">
<form method="post">
<p style="font-size:25px;font-family: monospace;padding-right:430px;padding-top:20px";   id="titulo">Lotes de materia prima<p>

  <select name="comboBloquesillos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");
$resultado = $MySQLiconn->query("SELECT * FROM bloquesMateriaPrima where baja=1");

while ($row = $resultado->fetch_array()) {

	if( $row ['nombreBloque']==$_SESSION['lote'])
{
	?>
	<option value="<?php echo $row ['nombreBloque'];?>" selected><?php echo $row['nombreBloque'];?>
	<?php
}
	else{
		?>
<option value="<?php echo $row ['nombreBloque'];?>"><?php echo $row['nombreBloque'];?>
<?php
}
}
?>
</option>
</option>
</datalist>
</select>

<p>Referencia:<input type="text" name="referenciaLote" value="<?php if(isset($_GET['edit'])) echo $getROW['referenciaLote'];?>"
    size="20" placeholder="nombre corto"></p>
<p>Longitud:<input type="text" name="longitud"  value="<?php if(isset($_GET['edit'])) echo $getROW['longitud'];?>"
    size="20" placeholder="nombre del elemento"></p>
    <p>Peso:<input type="text" name="peso"  value="<?php if(isset($_GET['edit'])) echo $getROW['peso'];?>"
    size="20" placeholder="nombre del elemento"></p>
    <p>Tarima:<input type="text" name="tarima"  value="<?php if(isset($_GET['edit'])) echo $getROW['tarima'];?>"
    size="20" placeholder="nombre del elemento"></p>
  
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<br>
<br>
<?php
include ("Controlador_Bloques/db_materiaPrima.php");
//Contar registros:

//
$MySQLiconn->query("UPDATE lotes set shower=1");

$resaltado= $MySQLiconn->query("SELECT tarima from lotes where baja=1 && bloque='".$_SESSION['lote']."'");
$rowes = $resaltado->fetch_array();
$rowe_cnt=$resaltado->num_rows;
printf("<p>Se muestran: ".$rowe_cnt." Registros Activos</p>");
$resaltado->close(); 
//
$resultado = $MySQLiconn->query("SELECT count(*),tarima from lotes where baja=1 and bloque='".$_SESSION['lote']."' group by tarima");
$count= mysqli_fetch_array($resultado);
$row_cnt=$resultado->num_rows;

$num=$row_cnt;
for ($i=0;$i<$num;$i++) {


	?>
<table width="55%" border="1" cellpadding="15" style='margin-left:200px;''>
<br>
<br>
<?php
//Selecciona las tarimas faltantes que aun tengan el campo "shower" en "1"
if($row_cnt<=1)
{
$resultado = $MySQLiconn->query("SELECT tarima from lotes where baja=1 and bloque='".$_SESSION['lote']."' order by tarima asc");

$cant= mysqli_fetch_array($resultado);
$_SESSION['tarima']=$cant[0];
include ("Controlador_Bloques/showLotes.php");
}
else
{
	$resultado = $MySQLiconn->query("SELECT tarima from lotes where baja=1 and bloque='".$_SESSION['lote']."' and tarima !='".$_SESSION['tarima']."' and shower=1 order by tarima asc");

$cant= mysqli_fetch_array($resultado);
$_SESSION['tarima']=$cant[0];
$MySQLiconn->query("UPDATE lotes set shower=0 where tarima='$cant[0]'");
include ("Controlador_Bloques/showLotes.php");
}
}
?>

</table>
</form>
</div>
</center>
</body>
</html>
