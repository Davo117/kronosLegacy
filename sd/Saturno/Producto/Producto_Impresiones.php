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
  <link rel="stylesheet" href="../css/formulary.css" type="text/css" media="screen"/>
</head>

<body> 
<?php

include("../components/barra_lateral2.php");
include("../css/barra_horizontal2.php");
include("Controlador_Disenio/crud_impresion.php");
?>
<center>
<div id="form">
<form method="post" name="formulary" id="formulary">
<p style="font-size:25px;font-family: monospace;padding-right:610px;padding-top:20px";   id="titulo">Impresiones</p>
<p style="font-size:12px;font-family: monospace;padding-right:610px;padding-top:20px";   id="changer"></p>

<select onChange="redireccionar()" name="comboDisenios" list="exampleList" id="comboDisenios">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$MySQLiconn->query("UPDATE pantone set state=1");
$resultado = $MySQLiconn->query("SELECT * FROM producto where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['descripcion']==$_SESSION['descripcion'])
{
	?>
	<option value="<?php echo $row ['descripcion'];?>" selected><?php echo $row['descripcion'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['descripcion'];?>"><?php echo $row['descripcion'];?></option>

<?php 
}
} 
?> 
</datalist>
</select>
<?php

$tipodescripcion=$_SESSION['descripcion'];
include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT tipo FROM producto where descripcion='$tipodescripcion'");
while($row = $resultado->fetch_array()){
if($row['tipo']=='Manga')
{
?>
<p>Codigo:<input type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión">    Descripcion:<input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción"></p>  
     Ancho pelicula:<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="7" placeholder="mm">    Altura de etiqueta:<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="7" placeholder="mm">   Número de tintas:<input type="text" name="numTintas" value="<?php if(isset($_GET['edit'])) echo $getROW['tintas'];?>"
    size="7" placeholder="numero de tintas"></p>
<p>Ancho de etiqueta:<input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="7" placeholder="mm">    Espacio para fusión:<input type="text" name="espacioFusion" value="<?php if(isset($_GET['edit'])) echo $getROW['espacioFusion'];?>"
    size="7" placeholder="mm">    Millares por rollo:<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="7" placeholder="millares"></p>
<p> % +/- Millares por rollo:<input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="7" placeholder="porcentaje">    Millares por paquete: <input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="7" placeholder="millares"></p>
<?php
}
if($row['tipo']=='Flexografia')
{
	?>
	<p>Codigo:<input type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión">   Descripcion:<input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción"></p>
<p>Ancho pelicula:<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="10" placeholder="mm">    Altura de etiqueta:<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="10" placeholder="mm">  Número de tintas:<input type="text" name="numTintas" value="<?php if(isset($_GET['edit'])) echo $getROW['tintas'];?>"
    size="10" placeholder="numero de tintas"></p>
<p>Ancho de etiqueta:<input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="10" placeholder="mm">    Millares por rollo:<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="10" placeholder="millares">   % +/- Millares por rollo:<input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="10" placeholder="porcentaje"></p>
<p>Millares por paquete: <input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="10" placeholder="millares"></p>
<?php
}
if($row['tipo']=='Holograma')
{
?>
<p>Codigo:<input type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión">  Descripcion:<input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción"></p>
<p>Ancho pelicula:<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="20" placeholder="mm">    Altura de etiqueta:<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="20" placeholder="mm">   Ancho de etiqueta:<input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="20" placeholder="mm"></p>
<p>Millares por rollo:<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="20" placeholder="millares">  % +/- Millares por rollo:<input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="20" placeholder="porcentaje"></p>
<p>Millares por paquete: <input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="20" placeholder="millares"></p>
<?php
}
}
?>
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button type="submit" name="update">Actualizar</button>
	<p>Asignación de Pantone por capa</p>
	<?php
	
	include ("Controlador_Disenio/db_Producto.php");
	
		//$pantoneEnCurso=;
		$cod=$getROW["codigoImpresion"];
	$resultado = $MySQLiconn->query("SELECT pantone.codigoHTML,pantone.descripcionPantone,pantonepcapa.codigoImpresion  FROM pantonePCapa left join pantone on pantonePCapa.descripcionPantone=pantone.descripcionPantone  where codigoImpresion='".$cod."' and pantone.baja=1 ");
		$count= mysqli_fetch_array($resultado);
		//$row= $resultado->fetch_array();
		$row_cnt=$resultado->num_rows;	
		$colorCombo1="#".$count["codigoHTML"];
		$contador=$row_cnt;
		$_SESSION['pantonesSend']=$contador;//envia el numero de registros para posteriormente modificarlos
		 $_SESSION['pantoneEnCurso']=$count["descripcionPantone"];
		for($i=0;$i<$row_cnt;$i++){
			$arreglo;
			$colorCombo="#".$row["codigoHTML"];
			 $arreglo[$i]=$_SESSION['pantoneEnCurso'];
		$row=$resultado->fetch_array();
?>		<p>Asignar pantone capa <?php echo $i+1 ?></p>
		<select style='background-color:<?php if($i!=0){echo $colorCombo; } else { echo $colorCombo1; } ?>; width: 250px' name="<?php echo "comboPantones".$i; ?>" id="comboPantones">
		<?php

		$resulter = $MySQLiconn->query("SELECT pantone.descripcionPantone,pantonepcapa.codigoImpresion, pantone.codigoHTML FROM pantone left join pantonepcapa on pantonePCapa.descripcionPantone=pantone.descripcionPantone  where pantone.baja=1 and pantone.state=1 group by pantone.codigoHTML");
		//$_SESSION['pantoneEnCurso']=$row["descripcionPantone"];


	while ($rowis= $resulter->fetch_array()) {
			$color="#".$rowis["codigoHTML"];

		if($_SESSION['pantoneEnCurso']==$rowis["descripcionPantone"])
		{
			?>

			<option  style='background-color:<?php echo $colorCombo ?>;' selected value="<?php echo $arreglo[$i];?>"><?php echo $arreglo[$i];?></option>
			
			
			<?php
			
			$_SESSION['pantoneEnCurso']= $row['descripcionPantone'];
			//$arreglo[$i]= $_SESSION['pantoneEnCurso'];	
			$MySQLiconn->query("UPDATE pantone set state=0 where descripcionPantone='".$arreglo[$i]."'");

		}
		else
		{
			?>
			<option  style='background-color:<?php echo $color; ?>' value="<?php echo $rowis['descripcionPantone'];?>"><?php echo $rowis['descripcionPantone'];?></option>

			<?php
		}

		}

		?>

</select>
<?php
$longitud=$contador;
$result=$MySQLiconn->query("SELECT pantonePCapa.disolvente,pantonePCapa.consumoPantone FROM pantonePCapa left join pantone on pantonePCapa.descripcionPantone=pantone.descripcionPantone  where pantone.baja=1 and pantonePCapa.codigoImpresion='".$cod."' and pantone.descripcionPantone='".$arreglo[$i]."'");
$row2= $result->fetch_array()

		?>
Consumo:<input type="text" name="<?php echo "consumoPantone".$i; ?>" value="<?php if(isset($_GET['edit'])) echo $row2['consumoPantone'];?>"
    		size="5" placeholder="0.0">    Disolvente:<input type="text" name="<?php echo "disolvente".$i; ?>" value="<?php if(isset($_GET['edit'])) echo $row2['disolvente'];?>"
   		 	size="5" placeholder="0/0">
<br>
<br>
	<?php


}//For principal

}//if principal
else
{
	?>
	<button type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<table width="55%" border="1" cellpadding="15" style='margin-left:200px;''>
<?php
include ("Controlador_Disenio/showImpresiones.php");
?>
</table>
</datalist>
</form>
</div>
</center>
</body>

<script type="text/javascript" language="JavaScript">

	var x= document.getElementById("comboDisenios").value;
	//var pagina="Producto_Impresiones.php";
	function redireccionar() 
	{
var combo = document.getElementById('comboDisenios');
if(combo.selectedIndex<0)
    alert('No hay opción seleccionada');
else
    //alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);
	//location.href=pagina;

	
	//setTimeout ("redireccionar()", 20000);
	document.getElementById("changer").innerHTML="Seleccionaste "+" "+combo.options[combo.selectedIndex].value;
}
</script>
             
}
</script>
</html>