<?php
@session_start();
include_once 'db_produccion.php';
//print("<script>alert('Esta enviando el parametro');</script>");
if(!empty($_GET['c']))
{
	$result=$MySQLiconn->query("TRUNCATE lotestemporales");
	$descripcionImpresion=$_GET['c'];

}
if(!empty($_GET['e']))
{
$e=$_GET['e'];
if($_GET['e']=='Termoencogible')
{
	$e='Termoencogible';
	
}
$MySQLiconn->query("UPDATE cache set dato='".$e."' where id=6");
}


$result=$MySQLiconn->query("SELECT noLote from lotestemporales limit 1");
$row=$result->fetch_array();
$tipo=$_SESSION['tipoProg'];
if(empty($row['noLote'])|| empty($_GET['e'])|| !empty($_GET['c']))
{
	if($tipo!="BS")
	{
$result=$MySQLiconn->query("SELECT concat(lotes.tarima,' | ',lotes.numeroLote) as loteDisponible,lotes.peso,lotes.longitud,lotes.referenciaLote,(select anchura from sustrato where descripcionSustrato=(select sustrato from impresiones where descripcionImpresion='".$_GET['c']."')) as mm from lotes
 inner join sustrato on sustrato.descripcionSustrato=lotes.bloque 
where sustrato.descripcionSustrato=(select sustrato from impresiones where descripcionImpresion='".$_GET['c']."') and lotes.estado=2 and lotes.baja=1 and sustrato.baja=1");
	}
	else
	{

		$result=$MySQLiconn->query("SELECT concat(lotes.tarima,'|',lotes.numeroLote) as loteDisponible,lotes.peso,lotes.longitud,(SELECT anchura from bandaseguridad where nombreBanda=(SELECT identificadorBS from bandaspp where nombreBSPP='".$_GET['c']."')) as anchuraBS,
lotes.referenciaLote,(SELECT anchura from sustrato where descripcionSustrato=(SELECT
sustrato from bandaspp where nombreBSPP='".$_GET['c']."')) as mm from lotes
inner join sustrato on sustrato.descripcionSustrato=lotes.bloque where sustrato.descripcionSustrato=(SELECT sustrato from bandaspp where nombreBSPP='".$_GET['c']."')
and lotes.estado=2 and lotes.baja=1 and sustrato.baja=1");
	}


?>
<p style="text-align:justify;width:400px;margin-right:310px;">Lotes disponibles (<?php echo $result->num_rows;?>)</p><br>

<?php
while($row=$result->fetch_array())
{

	$Sick=$MySQLiconn->query("SELECT anchoPelicula,alturaEtiqueta from impresiones where descripcionImpresion='$descripcionImpresion' and baja=1");
	$riv=$Sick->fetch_array();
	$anchoPelicula=$riv['anchoPelicula'];
	$alturaEtiqueta=$riv['alturaEtiqueta'];
	if(!empty($alturaEtiqueta) && !empty($anchoPelicula))
	{
		$unidades=($row['longitud']*($row['mm']/$anchoPelicula)/($alturaEtiqueta/100))/100;
		$unidades=round($unidades,3);
	}
	else if($tipo=="BS")
	{
		$unidades=$row['mm']/$row['anchuraBS']*$row['longitud'];
	}
	else
	{
		$unidades='?';
	}
	
	
	//$unidades=((($row['mm']/1000)*$row['longitud'])*100/(($anchoPelicula*$alturaEtiqueta)/100))/10
	?>
	<div class='lotesDisponibles'>
	<p class='irrelevance'><input type='checkbox' onclick="cargarLote(this.value);cargarLote2(this.value)" value="<?php echo $row['loteDisponible'] ?>"><?php
if($tipo!="BS")
{
	print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['loteDisponible']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'> ".number_format($unidades,3)." &nbsp;</b>Millares aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['mm']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referenciaLote']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
	print("</div><br>");
}
else
{

	print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['loteDisponible']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'> ".number_format($unidades,3)." &nbsp;</b>Metros aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['mm']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referenciaLote']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
	print("</div><br>");
}
	
	
	$MySQLiconn->query("INSERT INTO lotestemporales (noLote, longitud, unidades, anchura, peso, referencia) VALUES ('".$row['loteDisponible']."', '".$row['longitud']."', '$unidades', '".$row['mm']."', '".$row['peso']."', '".$row['referenciaLote']."')");
}
?>
</div>
<?php
}

else
{
$result=$MySQLiconn->query("SELECT*from lotestemporales where baja=0 and noLote!='".$_GET['e']."'");
?>
<p style="text-align:justify;width:400px;margin-right:310px;">Lotes disponibles  (<?php echo $result->num_rows;?>)</p><br>

<?php
while($row=$result->fetch_array())
{
	?>
	<div class='lotesDisponibles'>
	<p class='irrelevance'><input type='checkbox' onclick="cargarLote(this.value);cargarLote2(this.value)" value="<?php echo $row['noLote'] ?>"><?php
	
if($tipo!="BS")
{

	print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'>".$row['unidades']." &nbsp;</b>Millares aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
}
else
{
	print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'>".number_format($row['unidades'],3)." &nbsp;</b>Metros aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
	
}
print("</div><br>");
	
}
?>
</div>
<?php
}
?>
