<?php
ob_start();
$buf=getcwd(); ?>
<!doctype html>
<html lang="es">
<div id="barra-lateral" onclick="";
  ">
	<div id="row" >
	<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Programación del día</b>
				</div>
				<div class="panel-body">
		<?php
		if(!empty($_GET['tipo']))
		{
			$tipo=$_GET['tipo'];
		}
		else
		{
			$tipo="Termoencogible";
		}
		$cilinders=0;
		$cirels=0;
		$suj=0;
		if(empty($_POST['fechaProduccion']))
		{
			$fechaMostrador=date('Y-m-d');
		}
		else
		{
			$fechaMostrador=$_POST['fechaProduccion'];
		}
		include('Controlador_produccion/db_produccion.php');	
		$SQL=$MySQLiconn->query("SELECT  produccion.cantLotes as lotes, produccion.maquina,produccion.nombreProducto,produccion.disenio,produccion.fechaProduccion,produccion.juegoCilindros,produccion.juegoCireles,produccion.suaje,produccion.tipo,produccion.unidades,produccion.juegoLotes from produccion where produccion.estado='2' and produccion.fechaProduccion='".$fechaMostrador."' and produccion.tipo='".$tipo."'");
		while($row=$SQL->fetch_array())
		{
			if(!empty($row['juegoCilindros']))
			{
				$cilinders=1;
				$Sicual=$MySQLiconn->query("SELECT proveedor from juegoscilindros where identificadorCilindro='".$row['juegoCilindros']."'");
			$reu=$Sicual->fetch_array();
			}
			if(!empty($row['juegoCireles']))
			{
				$Sicual=$MySQLiconn->query("SELECT num_dientes as dientes from juegoscireles where identificadorJuego='".$row['juegoCireles']."'");
			$reu=$Sicual->fetch_array();
				$cirels=1;
			}
			if(!empty($row['suaje']))
			{
				$Sim=$MySQLiconn->query("SELECT proveedor from suaje where identificadorSuaje='".$row['suaje']."'");
			$reo=$Sim->fetch_array();
				$suj=1;
			}
			

			?>
			<div class="documents">
			<a target="_blank" style="float:right;margin-right:5px;" href="?generar=<?php echo $row['juegoLotes'];?>"><IMG src="../pictures/barcodes.png" title='Imprimir etiquetas'></IMG></a>
			<p class="infoProgramacion">Diseño:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['disenio']?><b></p>

				<p class="infoProgramacion">Producto:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['nombreProducto']?></b></p>
				<p class="infoProgramacion">Maquina:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['maquina']?></b></p>
				<?php

						
				if($row['tipo']!="BS")
				{
					$Stay=$MySQLiconn->query("SELECT codigoImpresion,(SELECT codigo from maquinas where descripcionMaq='".$row['maquina']."') as maquina from impresiones where descripcionImpresion='".$row['nombreProducto']."'");
						$raw=$Stay->fetch_array();
						if($cilinders==1)
						{
							?>
					<p class="infoProgramacion">Juego de Cilindros:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['juegoCilindros'].' | '.$reu['proveedor'];?></b></p>
					<?php
						}
						if($cirels==1)
						{
							?>
					<p class="infoProgramacion">Juego de Cireles:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['juegoCireles'].' | '.$reu['dientes']." Dientes";?></b></p>
					<?php
						}
						if($suj==1)
						{
							?>
					<p class="infoProgramacion">Suaje:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['suaje'].' | '.$reo['proveedor'];?></b></p>
					<?php
						}
					?>
					<p class="infoProgramacion">Lotes:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['lotes']." | ".number_format($row['unidades'],3)." "."Millares"?></b><br>
						<?php

if($cilinders==1)
	{							?>

					
					&nbsp; <a target="_blank" title="Imprimir" style="margin-right:5px;" href="documentos/pro-fr06.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>&cdgjuego=<?php echo $row['juegoCilindros'];?>&millares=<?php echo $row['unidades'];?>&juegolotes=<?php echo $row['juegoLotes'];?>">PRO-FR06 |&nbsp;</a>
					&nbsp; <a target="_blank" title="Imprimir" style="margin-right:5px;" href="documentos/pro-fr07.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>&cdgjuego=<?php echo $row['juegoCilindros'];?>&juegolotes=<?php echo $row['juegoLotes'];?>">PRO-FR07 |&nbsp;</a>
					<?php /*&nbsp; <a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="documentos/pro-fr08.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>&cdgjuego=<?php echo $row['juegoCilindros'];?>">PRO-FR08 |&nbsp;</a>
					<?php*/ }?>

					<a target="_blank" style="margin-right:5px;" href="documentos/pro-fr05.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>&cdgjuego=<?php if($cilinders==1){echo $row['juegoCilindros'];}else{echo $row['juegoCireles'];}?>&juegolotes=<?php echo $row['juegoLotes'];?>">PRO-FR05 |&nbsp;</a> 

<?php
					/*<a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="documentos/cc-fr01.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>&juegoLotes=<?php echo $row['juegoLotes'];?>">CC-FR01&nbsp;*/?></div><br>
<?php
				}
				else if($row['tipo']=="BS")
				{
					$Stay=$MySQLiconn->query("SELECT nombrebspp,(SELECT codigo from maquinas where descripcionMaq='".$row['maquina']."' and baja=1) as maquina from bandaspp where nombrebspp='".$row['nombreProducto']."'");
						$raw=$Stay->fetch_array();
					?>
					<p class="infoProgramacion">Lotes:&nbsp; &nbsp;<b style="font:bold 16px Sansation;"><?php echo $row['lotes']." | ".number_format($row['unidades'],3)." "."Metros"?><br><br>
						</a></p></div><br>
						<?php


					/*<a target="_blank" style="margin-right:5px;" href="?generar=<?php echo $row['juegoLotes'];?>">PRO-FR05 |&nbsp;</a> 
					&nbsp; <a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="?generar01=<?php echo $row['juegoLotes'];?>">PRO-FR06 |&nbsp;</a>
					&nbsp; <a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="?generar=<?php echo $row['juegoLotes'];?>">PRO-FR07 |&nbsp;</a>
					&nbsp; <a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="?generar=<?php echo $row['juegoLotes'];?>">PRO-FR08 |&nbsp;</a>
					<a target="_blank" title="Imprimir algo" style="margin-right:5px;" href="documentos/cc-fr01.php?cdgimpresion=<?php echo $raw['codigoImpresion'];?>&cdgmaquina=<?php echo $raw['maquina'];?>&fchprograma=<?php echo $row['fechaProduccion'];?>">CC-FR01&nbsp;</a></p></div><br>*/


				}
				
					$resultado=$MySQLiconn->query("SELECT idLote,noop,noLote,longitud,referenciaLote,peso,anchuraBloque,unidades,estado from lotes where juegoLote='".$row['juegoLotes']."' and estado=1 or juegoLote='".$row['juegoLotes']."' and estado=5" );
			while($rak=$resultado->fetch_array())//Este guail(xd) agrega los lotes en cada programación diaria
			{
				if($row['tipo']!="BS")
					{
						$decision="Millares aprox.";
					}
					else
					{
						$decision="Metros aprox.";
					}
				if($rak['estado']==5)
				{
					
					?>
					<a style="float:left;" onclick="return confirm('Este lote ya fué impreso'); " ><IMG src="../pictures/gearfourth.png" title='Lote producido'></a>
					<?php
					print("<div class='lotesDiarios'>");
					print("<b style='font: 100% Sansation;'>NoOP:</b><b style='font:bold 100% Sansation;'>&nbsp;".$rak['noop']."</b>");
					print("<br>No. de lote:&nbsp; <b style='font:bold 100% Sansation;'>".$rak['noLote']."&nbsp; &nbsp; </b>Longitud:<b style='font:bold 100% Sansation;'>".$rak['longitud']."</b><br><b style='font:bold 100% Sansation;'> ".number_format($rak['unidades'],3)." &nbsp;</b>".$decision." &nbsp; Anchura: &nbsp;<b style='font:bold 100% Sansation;'>".$rak['anchuraBloque']."</b><br> Referencia: &nbsp; <b style='font:bold 100% Sansation;'>".$rak['referenciaLote']."</b> &nbsp; Peso: &nbsp;<b style='font:bold 100% Sansation;'>".$rak['peso']."</b>");
					print("</div><br>");
				}
				else
				{
					?>
					<a style="float:left;" href="?return=<?php echo $rak['idLote']; ?>&tipo=<?php echo $row['tipo'];?>" onclick="return confirm('¿Seguro que desea regresar este bloque?'); " ><IMG src="../pictures/unnamed.png" title='Regresar lote'></a>
					<?php
					print("<div class='lotesDiarios'>");
					print("<b style='font: 100% Sansation;'>NoOP:</b><b style='font:bold 100% Sansation;'>&nbsp;".$rak['noop']."</b>");
					print("<br>No. de lote:&nbsp; <b style='font:bold 100% Sansation;'>".$rak['noLote']."&nbsp; &nbsp; </b>Longitud:<b style='font:bold 100% Sansation;'>".$rak['longitud']."</b><br><b style='font:bold 100% Sansation;'>".number_format($rak['unidades'],3)." &nbsp;</b>".$decision." &nbsp; Anchura: &nbsp;<b style='font:bold 100% Sansation;'>".$rak['anchuraBloque']."</b><br> Referencia: &nbsp; <b style='font:bold 100% Sansation;'>".$rak['referenciaLote']."</b> &nbsp; Peso: &nbsp;<b style='font:bold 100% Sansation;'>".$rak['peso']."</b>");
					print("</div><br>");
				}
			}
		}
		?>
	</div>
</div>
</div></div>
<script type="text/javascript">
	function generar()
	{
		var p=document.getElementById("numero");
		var envio="generarEtiquetas.php?f="+p.value;
		var r=document.getElementById("impresion");
		r.src=envio;
	}
</script>
</html>
<?php
ob_end_flush();
?>
