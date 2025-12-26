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
<form method="post" name="formulary" id="formulary" style="height:auto" >
<p style="font-size:25px;font-family: monospace;padding-right:610px;padding-top:6px";   id="titulo">Impresiones</p>
<p>Diseño: &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Sustrato:&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;Banda de seguridad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Código cliente: </p>
<select onChange="showCombo(this.value)" name="comboDisenios" list="exampleList" id="comboDisenios">
<datalist id="exampleList">
    <?php
include ("Controlador_Disenio/db_Producto.php");
$reslt = $MySQLiconn->query("SELECT *from cache order by id desc limit 1");
$rau = $reslt->fetch_array();
$tipodescripcion=$rau['dato'];
$MySQLiconn->query("DELETE from cache where id<".$rau['id']."");
$MySQLiconn->query("UPDATE pantone set state=1");
$resultado = $MySQLiconn->query("SELECT * FROM producto where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
<?php
if( $row ['descripcion']==$tipodescripcion)
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
 &nbsp;&nbsp;
<select name="comboSustratos" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");

$resultado = $MySQLiconn->query("SELECT * FROM sustrato where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
	<?php
if( $getROW['sustrato']==$row['descripcionSustrato'])
{
	?>
	<option value="<?php echo $getROW['sustrato'];?>" selected><?php  echo $getROW['sustrato'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['descripcionSustrato'];?>"><?php echo $row['descripcionSustrato'];?></option>

<?php 
}
} 
?> 
</option>
</option>
</datalist>
</select>
 &nbsp;&nbsp;
<select name="comboBSPP" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");

$resultado = $MySQLiconn->query("SELECT * FROM bandaseguridad where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
	<?php
if( $getROW['nombreBanda']==$row['nombreBanda'])
{
	?>
	<option value="<?php echo $getROW['nombreBanda'];?>" selected><?php  echo $getROW['nombreBanda'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['nombreBanda'];?>"><?php echo $row['nombreBanda'];?></option>

<?php 
}
} 
?> 
</option>
</option>
</datalist>
</select>
 &nbsp;&nbsp;
<select name="comboPCliente" list="exampleList">
<datalist id="exampleList">
    <?php
include ("Controlador_Bloques/db_materiaPrima.php");

$resultado = $MySQLiconn->query("SELECT * FROM productosCliente where baja=1");

while ($row = $resultado->fetch_array()) {
?> 
	<?php
if( $getROW['codigoCliente']==$row['nombre'])
{
	?>
	<option value="<?php echo $getROW['codigoCliente'];?>" selected><?php  echo $getROW['codigoCliente'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['nombre'];?>"><?php echo $row['nombre'];?></option>

<?php 
}
} 
?> 
</option>
</option>
</datalist>
</select>
<?php

include ("Controlador_Disenio/db_Producto.php");
$resultado = $MySQLiconn->query("SELECT tipo FROM producto where descripcion='$tipodescripcion'");
while($row = $resultado->fetch_array()){
if($row['tipo']=='Manga')
{
?>
<p>Codigo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descripcion:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ancho pelicula:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Altura de etiqueta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Número de tintas:</p>
<p><input  type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión" required >&nbsp;&nbsp;&nbsp; <input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción" required >&nbsp;&nbsp;<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="7" placeholder="mm" class="numericos" required>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="7" placeholder="mm" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="numTintas" value="<?php if(isset($_GET['edit'])) echo $getROW['tintas'];?>"
    size="7" placeholder="numero de tintas" class="numerosExtraGrandes"></p> <br>
     <p>Ancho de etiqueta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Espacio para fusión:&nbsp;&nbsp;&nbsp;&nbsp;Millares por rollo:&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;% +/- Millares por rollo:&nbsp;&nbsp;&nbsp;&nbsp;Millares por paquete:</p>
     <p><input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="7" placeholder="mm" class="numericos" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="espacioFusion" value="<?php if(isset($_GET['edit'])) echo $getROW['espacioFusion'];?>"
    size="5" placeholder="mm" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="5" placeholder="millares" class="numerosGrandes"> &nbsp;&nbsp;&nbsp; <input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="5" placeholder="porcentaje" class="numerosExtraGrandes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="5" placeholder="millares" class="numerosExtraGrandes"></p>
<?php
}
if($row['tipo']=='Flexografia')
{
	?>
	<p>Codigo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descripcion:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ancho pelicula:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Altura de etiqueta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Número de tintas:</p>
<p><input type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión" required >&nbsp;&nbsp;&nbsp; <input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción" required >&nbsp;&nbsp;<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="7" placeholder="mm" class="numericos" required>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="7" placeholder="mm" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="numTintas" value="<?php if(isset($_GET['edit'])) echo $getROW['tintas'];?>"
    size="7" placeholder="numero de tintas" class="numericos"></p> <br>
     <p>Ancho de etiqueta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Millares por rollo:&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;% +/- Millares por rollo:&nbsp;&nbsp;&nbsp;&nbsp;Millares por paquete:</p>
     <p><input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="7" placeholder="mm" class="numericos" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="5" placeholder="millares" class="numerosGrandes"> &nbsp;&nbsp;&nbsp; <input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="5" placeholder="porcentaje" class="numerosExtraGrandes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="5" placeholder="millares" class="numerosExtraGrandes"></p>
<?php
}
if($row['tipo']=='Holograma')
{
?>
<p>Codigo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descripcion:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ancho pelicula:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Altura de etiqueta:</p>
<p><input type="text" name="codigoImpresion" value="<?php if(isset($_GET['edit'])) echo $getROW['codigoImpresion'];?>"
    size="20" placeholder="codigo de impresión" required >&nbsp;&nbsp;&nbsp; <input type="text" name="descImpresion"  value="<?php if(isset($_GET['edit'])) echo $getROW['descripcionImpresion'];?>"
    size="20" placeholder="Descripción" required >&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="anchoPelicula" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoPelicula'];?>"
    size="7" placeholder="mm" class="numericos" required>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name=alturaEtiqueta value="<?php if(isset($_GET['edit'])) echo $getROW['alturaEtiqueta'];?>"
    size="7" placeholder="mm" class="numerosExtraGrandes"></p> <br>
    <p>Ancho de etiqueta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Millares por rollo:&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;% Millares por rollo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Millares por paquete:</p>
     <p><input type="text" name="anchoEtiqueta" value="<?php if(isset($_GET['edit'])) echo $getROW['anchoEtiqueta'];?>"
    size="7" placeholder="mm" class="numericos" class="numericos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorRollo'];?>"
    size="5" placeholder="millares" class="numerosExtraGrandes"> &nbsp;&nbsp;&nbsp;<input type="text" name="porcentajeMPR"  value="<?php if(isset($_GET['edit'])) echo $getROW['porcentajeMPR'];?>"
    size="5" placeholder="porcentaje" class="numerosExtraGrandes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="millaresPP" value="<?php if(isset($_GET['edit'])) echo $getROW['millaresPorPaquete'];?>"
    size="5" placeholder="millares" class="numerosExtraGrandes"></p>

<?php
}
}
?>
<p class="txtChecks2">Mostrar todo<input class="checks2" type="checkbox" name="checkTodos" checked onclick="if (this.checked==true){ location.href='Producto_Impresiones_bajas.php';}
else{
location.href='Producto_Impresiones.php';
};
			
			
			"></p>
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button class="botonPerson2" type="submit" name="update">Actualizar</button>
	<br>
	 <hr>
	<p  class="titulos" style="padding-left: 350px">Asignación de Pantone por capa</p>
	<br>
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
?>		<p id="pantones">Asignar pantone capa <?php echo $i+1 ?>
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
	<button  class="botonPerson2" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<p class="ordenMedio">Impresiones Activas</p>
<div id="txtHint">#LH</div>
<p class="ordenMedio">Impresiones Inactivas</p>
<div id="txtHunt">#LH</div>
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
<script>
function showCombo(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET","Controlador_Disenio/showImpresiones.php?q="+str,true);
        xmlhttp.send();
       window.location="Producto_Impresiones.php";
    }
}
window.onload = function() {
showComboLoad(comboDisenios.value);
showComboLoadBajas(comboDisenios.value);
};

function showComboLoad(guat) {
    if (guat == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","Controlador_Disenio/showImpresiones.php?q="+guat,true);
        xmlhttp.send();
    }
}

function showComboLoadBajas(wet) {
    if (wet == "") {
        document.getElementById("txtHunt").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHunt").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","Controlador_Disenio/showImpresiones_bajas.php?q="+wet,true);
        xmlhttp.send();
    }
}
</script>
             
</script>
</html>
