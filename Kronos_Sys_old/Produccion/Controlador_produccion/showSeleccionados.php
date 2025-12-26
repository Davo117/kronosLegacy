<?php
include_once 'db_produccion.php';
$q=$_GET['q'];
$MySQLiconn->query("TRUNCATE PRUEBAS");
$MySQLiconn->query("UPDATE lotestemporales set baja=1 where noLote='$q'");
$result=$MySQLiconn->query("SELECT noLote,longitud,unidades,anchura,referencia,peso,(SELECT dato from cache where id=9) as tipo from lotestemporales where baja=1");
?>
<p style="text-align:justify;width:200px;margin-right:310px;">Lotes Seleccionados  (<?php echo $result->num_rows;?>)</p><br>

<?php
while($row=$result->fetch_array())
{
	?>
	<div class='lotesSeleccionados'>
	<p class='irrelevance'><input type='checkbox' checked onclick="enviarLotes(this.value),cargarLote()" value="<?php echo $row['noLote'] ?>"'><?php
if($row['tipo']!="BS")
{
		print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'>".number_format($row['unidades'],3)." &nbsp;</b> Millares aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
	print("</div><br>");
	
}
else
{
	print("<br>No. de lote:&nbsp; <b class='bloqs_first'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'> ".number_format($row['unidades'],3)." &nbsp;</b>Metros aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
	print("</div><br>");
}

}
?>
</div>

<?php
?>
