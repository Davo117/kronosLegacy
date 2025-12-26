<?php
ob_start();
session_start();
	if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	?>

<head>
<title>Impresiones | Producto</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<meta name="viewport" content="width=device−width, initial−scale=1.0" />
    	 <script type="text/javascript" src="../css/teclasN.js"></script>
</head>
<body> 
<?php
//include("Controlador_Disenio/bitacoras/bitacoraImpresion.php");

$codigoPermiso='4';
include ("funciones/permisos.php");
include("../components/barra_lateral2.php");
//include("../css/barra_horizontal2.php");
include("Controlador_Disenio/crud_impresion.php");
//$SQL=$MySQLiconn->query("TRUNCATE pruebas");
?>

<div id="page-wrapper">
	<div class="container-fluid">
		<form method="post" name="formulary" id="formulary" role="form">

<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Impresión</b>

<?php
if(isset($_POST['checkTodos']))
{
	?>

	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_Impresiones.php';"> Mostrar todo
		</label>
	<?php
}
else
{
?>

<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1"  value="checkTodos" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
		</label>
<?php
} ?>

	</div>
<div class="panel-body">


<div class="col-xs-3">

<label for="comboDisenios">Diseño:</label>
<select onChange="showCombo(this.value)" name="comboDisenios" id="comboDisenios" class="form-control">
   <option value="">--</option> <?php
include ("Controlador_Disenio/db_Producto.php");
//$inProd=0;
$fuz=0;
$hologram=0;
$reslt = $MySQLiconn->query("SELECT dato from cache where id=1");
$rau = $reslt->fetch_array();
$tipodescripcion=$rau['dato'];
$MySQLiconn->query("UPDATE pantone set state=1");
$resultado = $MySQLiconn->query("SELECT DISTINCT p.descripcion,p.id,t.tipo,pr.estado,p.fus,p.holograma FROM producto p 
LEFT JOIN produccion pr 
ON pr.disenio=p.descripcion
inner join tipoproducto t
on p.tipo=t.id
 where p.baja=1");

while ($row = $resultado->fetch_array()) {
	
if( $row ['id']==$tipodescripcion)
{
	if($row['estado']>0)
	{
		//$inProd=1;
	}
	if($row['fus']==1)
	{
		$fuz=1;
	}
	if($row['holograma']==1)
	{
		$hologram=1;
	}
	?>
	<option value="<?php echo $row ['id'];?>" selected><?php echo  $row['descripcion'].' ['.$row['tipo'].']';?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row ['id'];?>"><?php echo $row['descripcion'].' ['.$row['tipo'].']';?></option>
<?php 
}
} 
?> 
</select>
</div>

<div class="col-xs-3">
<label for="comboSustratos">Sustrato:</label>
<select required name="comboSustratos" id="comboSustratos" class="form-control">
<?php
$resultado = $MySQLiconn->query("SELECT descripcionSustrato,idSustrato FROM sustrato where baja=1");
?>
<option value="">--</option>
<?php
while ($row = $resultado->fetch_array()) {
?>
<?php
if( $getROW['sustrato']==$row['idSustrato'])
{
	?>
	<option value="<?php echo $getROW['sustrato'];?>" selected><?php  echo $row['descripcionSustrato'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['idSustrato'];?>"><?php echo $row['descripcionSustrato'];?></option>
<?php 
}
} 
?> 
</select>
</div>
<?php
if($fuz==1)
{
	?> 
	<div class="col-xs-3">
<label for="comboBSPP">Banda de seguridad:</label>
<select  name="comboBSPP" id="comboBSPP" class="form-control">
<?php
$resultado = $MySQLiconn->query("SELECT nombreBanda,IDBanda FROM bandaseguridad where baja=1");
?>
<option value="">--</option>
<?php
while ($row = $resultado->fetch_array()){
?> 
<?php
if( $getROW['nombreBanda']==$row['nombreBanda'])
{
	?>
	<option value="<?php echo $getROW['IDBanda'];?>" selected><?php  echo $getROW['nombreBanda'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['IDBanda'];?>"><?php echo $row['nombreBanda'];?></option>
<?php 
}
} 
?> 
</select>
</div>
<?php
}
if($hologram==1)
{
	$SAL=$MySQLiconn->query("SELECT tipo FROM tipoproducto WHERE presentacion='Holograma'");
	
if(isset($_GET['edit']))
{?>
	<div class="col-xs-3"  style="border-style:solid;border-radius:5px;border-width:1px;border-color:#BCBCBC;padding:4px">
	<?php
}
else
{?>
	<div class="col-xs-3">
	<?php
}
	?>

	

		<label for="hologram">Holograma</label>
	<select name="hologram" id="hologram" class="form-control">
		<option value="">--</option>
		<?php
		while($hog=$SAL->fetch_array())
		{
if(isset($_GET['edit']) && $getROW['holograma']==$hog['tipo'])
{
	?>
	<option value="<?php echo $hog['tipo'];?>" selected><?php echo $hog['tipo'];?></option>

	<?php 
}
	else{
		?>
			<option value="<?php echo $hog['tipo'];?>"><?php echo $hog['tipo'];?></option>
			<?php
		}
	}?>
		
	</select>

	<?php
if(isset($_GET['edit']) && !empty($getROW['holograma']))
{
	$sut=$MySQLiconn->query("SELECT consumo FROM hlogpproducto WHERE tipo='".$getROW['holograma']."' AND impresion='".$getROW['descripcionImpresion']."' ") ;
	$hol=$sut->fetch_array();
	?>
	<div style="padding:3px;margin:5px">
		<label for="consumoH">Consumo</label>
		<input placeholder="metros" style="float:right;" value="<?php echo $hol['consumo'];?>" onkeypress="return numeros(event)" type="text" size="10" name="consumoH" id="consumoH" class="form-control">
	</div>


<?php
}?>
</div>
<?php
}
?>



<div class="col-xs-3">
<label for="comboPCliente">Código cliente: </label>
<select required name="comboPCliente" id="comboPCliente" class="form-control">
    <?php
$resultado = $MySQLiconn->query("SELECT nombre,IdentificadorCliente,IdProdCliente FROM productoscliente where baja=1");
?>
<option value="">--</option>
<?php
while ($row = $resultado->fetch_array()) {
?>
<?php
if( $getROW['codigoCliente']==$row['IdProdCliente'])
{
	?>

	<option value="<?php echo $getROW['codigoCliente'];?>" selected><?php echo $row['IdentificadorCliente']." |".$getROW['codigoCliente'];?></option>
	<?php 
}
	else{
		?>
<option value="<?php echo $row['IdProdCliente'];?>"><?php echo $row['IdentificadorCliente']." |".$row['nombre'];?></option>
<?php 
}
}

?> 
</select>
</div>
<div class="col-xs-3">
	<label for="comboPCliente">Etiqueta corte:</label><br>
	<div  style="border-style:solid;border-radius:5px;border-width:1px;border-color:#BCBCBC;padding:4px">
	<label class="checkbox-inline">
  		<input type="checkbox" id="checkboxEnLinea2" <?php if(isset($_GET['edit']) and $getROW['haslote']==1){ echo "checked"; }?> name="lote">Colocar lote
		</label>
	</div>
</div>
<?php
$resultado = $MySQLiconn->query("SELECT tipo FROM producto where ID='$tipodescripcion'");
while($row = $resultado->fetch_array()){
	$e=$row['tipo'];

	$rest = $MySQLiconn->query("SELECT numParametro,nombreparametro,leyenda,requerido,placeholder FROM juegoparametros where identificadorJuego=(SELECT juegoparametros from tipoproducto where id='$e')");

while($rex = $rest->fetch_array()){
	$valiu=$rex['nombreparametro'];
	if($rex['requerido']==1)
	{
		?>
<div class="col-xs-3" style="<?php if($rex['numParametro']=='PAM1') echo 'clear:left;' ?>">
<label for="<?php echo $rex['nombreparametro']?>"><?php echo $rex['leyenda'] ?></label>
<input style="float:left;" <?php if($rex['nombreparametro']=="tintas") {echo "type='number' max=10 min=0";} else{echo "type='text'";}?> required  name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])){ echo $getROW[$valiu]; if($rex['nombreparametro']=='porcentajeMPR' || $rex['nombreparametro']=='millaresPorRollo'  ){  }else if($inProd==0){}else{ echo '"readonly="false';}} ?>"
    size="18" placeholder="<?php echo $rex['placeholder'] ?>" class="form-control">
</div>

<?php

	}
	else
	{
		if($rex['nombreparametro']=="refParcial" and isset($_GET['edit']))
		{
			$DRAN=$MySQLiconn->query("SELECT sustrato.anchura-impresiones.espacioregistro as anchoT,impresiones.anchoPelicula from sustrato inner join impresiones on impresiones.sustrato=sustrato.idSustrato where sustrato.idSustrato='".$getROW['sustrato']."' limit 1");
			$rom=$DRAN->fetch_assoc();
			$porcen=$rom['anchoT']/$rom['anchoPelicula'];
			?>
<div class="col-xs-3">
<label for="refParcial">Refilado parcial</label>
<select name="refParcial" id="refParcial" class="form-control">
	<option value="1">Por defecto</option>
	<option value="2" <?php if($porcen==2 or !is_int($porcen/2)){ echo "disabled";} if($getROW['refParcial']==2){ echo "selected";}?>>1/2</option>
	<option value="3" <?php if($porcen==3 or !is_int($porcen/3)){ echo "disabled";} if($getROW['refParcial']==3){ echo "selected";}?>>1/3</option>
	<option value="4" <?php if($porcen==4 or !is_int($porcen/4)){ echo "disabled";} if($getROW['refParcial']==4){ echo "selected";}?>>1/4</option>
	<option value="5" <?php if($porcen==5 or !is_int($porcen/5)){ echo "disabled";} if($getROW['refParcial']==5){ echo "selected";}?>>1/5</option>
</select>
</div>

<?php
}
else
{
		?>
<div class="col-xs-3">
<label for="<?php echo $rex['nombreparametro']?>"><?php echo $rex['leyenda'] ?></label>
<input style="float:left;" type="text" name="<?php echo $rex['nombreparametro'] ?>" value="<?php if(isset($_GET['edit'])){ echo $getROW[$valiu]; if($rex['nombreparametro']=='millaresPorRollo' || $rex['nombreparametro']=='porcentajeMPR' || $rex['nombreparametro']=='observaciones' || $rex['nombreparametro']=='logproveedor'){  }else if($inProd==0){}else{ echo '"readonly="true';}} ?>"
    size="18" placeholder="<?php echo $rex['placeholder'] ?>" class="form-control" >
</div>

<?php
}
		}

}
}

if(isset($_GET['edit']))
{

?>

</div>
<hr style="width: 90%; color:#DCDCDC; height: 1px; background-color:#DCDCDC;" />
<div class="panel-body">
<?php
	
	include ("Controlador_Disenio/db_Producto.php");
	
		//$pantoneEnCurso=;
		$cod=$getROW["codigoImpresion"];
	$resultado = $MySQLiconn->query("SELECT (SELECT codigoHTML from pantone where descripcionPantone=p.descripcionPantone) as codigoHTML,p.descripcionPantone,p.codigoImpresion FROM pantonepcapa p where p.codigoImpresion='".$cod."'");
		$count= mysqli_fetch_array($resultado);
		//$row= $resultado->fetch_array();
		$row_cnt=$resultado->num_rows;	
		if($row_cnt>0)
		{
			?><h4  class="titulos" style="padding-left: 360px">Asignación de Pantone por capa</h4>
	
			<?php
		}
		$colorCombo1="#".$count["codigoHTML"];
		$contador=$row_cnt;
		$_SESSION['pantonesSend']=$contador;//envia el numero de registros para posteriormente modificarlos
		 $_SESSION['pantoneEnCurso']=$count["descripcionPantone"];
		for($i=0;$i<$row_cnt;$i++){
			$arreglo;
			$colorCombo="#".$row["codigoHTML"];
			 $arreglo[$i]=$_SESSION['pantoneEnCurso'];
		$row=$resultado->fetch_array();

?>
<div class="col-xs-3"   style="border-style:solid;border-radius:5px;border-width:1px;border-color:#BCBCBC;padding:4px"">
	<label  for="<?php echo "comboPantones".$i; ?>">Capa <?php echo $i+1 ?></label>
		<select class="form-control" style='color:white;margin-bottom:2%;


    text-shadow:
   -1px -1px 0 black,
    1px -1px 0 black,
   -1px 1px 0 black,
    1px 1px 0 black;background-color:<?php if($i!=0){echo $colorCombo; } else { echo $colorCombo1; } ?>;' name="<?php echo "comboPantones".$i; ?>" id="<?php echo "comboPantones".$i; ?>">
<?php
		$resulter = $MySQLiconn->query("SELECT pantone.descripcionPantone,pantonepcapa.codigoImpresion, pantone.codigoHTML FROM pantone left join pantonepcapa on pantonepcapa.descripcionPantone=pantone.descripcionPantone  where pantone.baja=1 and pantone.state=1 group by pantone.codigoHTML");
	while ($rowis= $resulter->fetch_array()) {
			$color="#".$rowis["codigoHTML"];
		if($_SESSION['pantoneEnCurso']==$rowis["descripcionPantone"])
		{
			?>
			<option  style='background-color:<?php echo $colorCombo ?>;' selected value="<?php echo $arreglo[$i];?>"><?php echo $arreglo[$i];?></option>
			<?php
			$_SESSION['pantoneEnCurso']= $row['descripcionPantone'];
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
$result=$MySQLiconn->query("SELECT pantonepcapa.disolvente,pantonepcapa.consumoPantone FROM pantonepcapa left join pantone on pantonepcapa.descripcionPantone=pantone.descripcionPantone  where pantone.baja=1 and pantonepcapa.codigoImpresion='".$cod."' and pantone.descripcionPantone='".$arreglo[$i]."'");
$row2= $result->fetch_array();
?>
<label for="<?php echo "consumoPantone".$i; ?>" style="float:left;clear:left;width:30%;font:bold 13px Sansation";>Consumo:</label>
<input style="width:80px;float:left;" type="text" class="form-control" name="<?php echo "consumoPantone".$i; ?>" value="<?php if(isset($_GET['edit'])) echo $row2['consumoPantone'];?>"
    		size="5" placeholder="0.0" id="<?php echo "consumoPantone".$i; ?>">
 <label for="<?php echo "disolvente".$i; ?>" style="float:left;clear:left;width:30%;font:bold 13px Sansation">Disolvente:</label><input class="form-control" style="float:left; width:80px;" type="text" name="<?php echo "disolvente".$i; ?>" value="<?php if(isset($_GET['edit'])) echo $row2['disolvente'];?>"
   		 	size="5" placeholder="0/0" id="<?php echo "disolvente".$i; ?>">
 
</div>
<?php
}//For principal
?>
</div>
<?php
}//if principal
else
{
	?>
</div>
	<button  class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
	<?php
}
if(isset($_GET['edit']))
{?>
	<button  type="submit" class="btn btn-default" style="float:right;" name="update">Actualizar</button>
	<?php
}
mysqli_free_result($resultado);
?>
</div>
</form>
<h4 class="ordenMedio">Impresiones Activas</h4>
<div id="txtHint" class="table-responsive">C:</div>
<?php
if(isset($_POST['checkTodos']))
{?>

	<h4 class="ordenMedio">Impresiones Inactivas</h4>
<div id="txtHunt" class="table-responsive">:C</div>
<?php
}
?>
</div>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
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
    
            // code for IE6, IE5
         
     
    
        xmlhttp.open("GET","Controlador_Disenio/showImpresiones.php?a="+str,true);
           window.location="Producto_Impresiones.php";
        xmlhttp.send();
       
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
</html>
<?php
ob_end_flush();
}
else {
      include "../ingresar.php";
}
?>