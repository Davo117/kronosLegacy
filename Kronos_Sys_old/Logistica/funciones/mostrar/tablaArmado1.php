
<?php  
include('../Database/db.php');
	
$SQL=$MySQLiconn->query("SELECT * FROM caja where producto='".$_SESSION['productillo']."' && cdgEmbarque='' && baja='2' ORDER BY referencia");	

$canti=$MySQLiconn->query("SELECT count(referencia) as cuenta, sum(piezas) as total FROM caja where producto='".$_SESSION['productillo']."' && cdgEmbarque='' && baja='2'");
$suma=$canti->fetch_array();?>
	
<div>
<div style="float:left;margin-left:20%;width:30%">
<p style="text-align:justify; margin-left:20%;">Empaques Disponibles  <?php echo $suma['cuenta'].' (Total: '.$suma['total'].' millares'; ?>)</p><br>

<?php 
while ($row=$SQL->fetch_array()) {	?>
	
	<div class='lotesDisponibles'>
	
	<input name="number<?php echo $row['referencia'];?>"  value="--" type="hidden"/>

	<p class='irrelevance'><input type="checkbox" name="number<?php echo $row['referencia'];?>" style="width: inherit;"  value="<?php echo $row['referencia'];?>">
	<?php
	echo "Empaque: <b style='font:bold 100% Sansation;'>"; echo $row['referencia']; echo "</b> &nbsp; Millares: <b style='font:bold 100% Sansation;'>"; echo $row['piezas']; echo "</b></p>
	<p class='irrelevance' style='margin-left:40%;'>Peso: <b style='font:bold 100% Sansation;'>"; echo $row['peso']; echo "</b>
	</input></p></div><br>";
}?>
</div>

<?php 
$canti2=$MySQLiconn->query("SELECT count(referencia) as cuenta, sum(piezas) as total FROM caja where producto='".$_SESSION['productillo']."' && cdgEmbarque='".$_GET['cdgEmb']."' && baja='3' ");
$suma2=$canti2->fetch_array();

$SQL=$MySQLiconn->query("SELECT * FROM caja where producto='".$_SESSION['productillo']."' && cdgEmbarque='".$_GET['cdgEmb']."' && baja='3' ORDER BY referencia"); ?>
<div style="float:right;margin-right:10%;width:30%">

<p style="text-align:justify;margin-right:15%;">Empaques Añadidos <?php echo $suma2['cuenta'].' <br>(Total: '.$suma2['total'].' millares'; ?>)</p>
<br>
<div style=" overflow-y:auto; overflow-x:auto; font:80% Sansation; text-align:justify;">
<?php 
while ($row=$SQL->fetch_array()) {	?>

	<div class='lotesSeleccionados'>
	
	<input name="number<?php echo $row['referencia'];?>"  value="2" type="hidden"/>

	<p class='irrelevance'><td> <a  href="?desactivar=C<?php echo $row["id"].'&cdgEmb='.$_GET['cdgEmb'].'&id='.$_GET['id'].'&emp='.$_GET['emp']; ?>" ><IMG src="funciones/img/deleteOtro.png" title='Modificar' style="margin-bottom: -10px;"></a>
	<?php
	echo "Empaque: <b style='font:bold 100% Sansation;'>"; echo $row['referencia']; echo "</b> &nbsp; Millares: <b style='font:bold 100% Sansation;'>"; echo $row['piezas']; echo "</b></p>

		<p class='irrelevance' style='margin-left:40%;'>Peso: <b style='font:bold 100% Sansation;'>"; echo $row['peso']; echo "</b>
				</td></p>
			</div><br>";
}?>
</div>
</a>
</p>
</div>