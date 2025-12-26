	<?php
	include('db_produccion.php');
	error_reporting(0);
	$q=$tipo;
	//Datos obtenidos a partir del tipo que e escogió
	if($q!="BS")//Si el producto no es banda de seguridad trae los datos desde producto
	{

		$resultado=$MySQLiconn->query("SELECT*FROM showTablero where tipo='$q'");
	}
	else if($q=="BS")
	{
		$resultado=$MySQLiconn->query("SELECT*FROM shoTableroBS");
	}
	?>

	
		<?php
		//Se obtienen los datos de la necesidad de producto
		while($row=$resultado->fetch_array())
		{
			?>
			<div class="panel panel-info">
			<?php
			$ss=0;
			$scrap=0;
			$stockcaja=0;
			$stockrollo=0;
			$Sick=$MySQLiconn->query("SELECT cantidadConfi-surtido as pendiente,surtido as stock, empaqueConfi  from confirmarprod where prodConfi=(select descripcionImpresion from impresiones where descripcionImpresion='".$row['descripcionImpresion']."') and bajaConfi=1");
			$contador=0;
			while($rou=$Sick->fetch_array()){

				$cantidad=$rou['pendiente'];
				${$rou['empaqueConfi']}=$rou['pendiente']+${$rou['empaqueConfi']};
				if($rou['empaqueConfi']=="caja")
				{
					//$stockcaja=$rou['stock'];
				}
				else
				{
					//$stockrollo=$rou['stock'];
				}				
				$ss=$ss+$cantidad;
			}
			$ska=$MySQLiconn->query("SELECT sum(unidades) as historico from produccion where nombreProducto='".$row['descripcionImpresion']."'");
			$sill=$ska->fetch_array();
 $enviado=0;//Esta variable solo simula los productos enviados,no son datos reales
 $historico=$sill['historico'];
 $enProceso=0;

 ?>
 <div class="panel-heading"><b class="titulo"><?php echo $row['productos'];?></b>
 	<?php
 	if($tipo!="BS")
 		{?>
 			<b style="margin:4px;float:right;font:bold 13px Sansation Light"> Excedente</b>
 			<b style="margin:4px;background-color:blue;float:right;color:blue;font:bold 13px Sansation Light">__</b>
 			<b style="margin:4px;float:right;font:bold 13px Sansation Light"> Faltante | </b>
 			<b style="margin:4px;background-color:#961010;float:right;color:#961010;font:bold 13px Sansation Light">__</b>
 			<b style="margin:4px;float:right;font:bold 13px Sansation Light"">Programado</b><b style="margin:4px;background-color:#2E6092;float:right;color:blue;font:bold 13px Sansation Light">__</b>

 		<b style="margin:4px;float:right;font:bold 13px Sansation Light">Por entregar</b><b style="margin:4px;background-color:#39734A;float:right;color:blue;font:bold 13px Sansation Light">__</b><?php

 		}

 		?>
 		<b style="margin:4px;float:right;font:bold 13px Sansation Light">Scrap | </b><b style="margin:4px;background-color:#D81737;float:right;color:#D81737;font:bold 13px Sansation Light">__</b><b style="float:right;">/</b><b style="margin:4px;background-color:#53BBCE;float:right;color:#53BBCE;font:bold 13px Sansation Light">__</b>
 		
 	</div>

 	<div class="panel-body">
 		<?php
		/*
if($q=="BS")
{?>
	<p style="padding-top:5px;padding-left:15px;padding-bottom:5px;font:bold 14px Sansation">Producto en proceso (Metros)</p><?php
}
else
{?>
	<p style="padding-top:5px;padding-left:15px;padding-bottom:5px;font:bold 14px Sansation">Producto en proceso (Millares)</p><?php
}*/

if($q!="BS")
{
	//Calculo de piezas de caja y rollo,para colocar el producto enviado total
	/*

	<p style="clear:right;padding-left:15px;padding-top:10px;padding-bottom:5px;font:bold 105% Sansation">Información de entregas</p>
	*/?>

	<div class="row">
		<?php

		$res=$MySQLiconn->query("SELECT* from juegoprocesos where baja=1 and identificadorJuego=(select juegoprocesos from tipoproducto where alias='$q') and tablero=1 ORDER by numeroProceso desc");
		$stopBanda=1;
	while ($rew=$res->fetch_array()) {//El while para los cuadros de procesos internos
if(!empty($row['nombreBanda']))//Si el producto tiene banda de seguridad,ubicamos en que nodo del proceso esta fusión
{
	
	$procesoActual=$rew['numeroProceso'];
	if($rew['descripcionProceso']=="fusion")
	{
		//$stopBanda=0;
		$numFus=$rew['numeroProceso'];
		$bandaRes=0;
	}
	else if(!empty($numFus))
	{
		if($numFus>=$procesoActual)
		{
			$stopBanda=0;
		}
	}
}
if($rew['numeroProceso']==0)
{
	$SK=$MySQLiconn->query("SELECT sum(lotes.unidades) as unidades from produccion inner join lotes on produccion.juegoLotes=lotes.juegoLote where produccion.nombreProducto='".$row['descripcionImpresion']."' and lotes.estado=1");
			$unidades=0.0."\n";//Esto coloca las unidades de programación
			$rem=$SK->fetch_array();
			if(!empty($rem['unidades']))
			{
				$unidades=$rem['unidades'];
			}

///////////Calculo de stock y el producto enviado
			$RCA=$MySQLiconn->query("SELECT SUM(piezas) as piezas FROM caja where baja=3 and producto='".$row['descripcionImpresion']."'");
			$RC=$RCA->fetch_array();
			$RRO=$MySQLiconn->query("SELECT SUM(piezas) as piezas FROM rollo where baja=3 and producto='".$row['descripcionImpresion']."'");
			$RR=$RRO->fetch_array();
			$send=$RC['piezas']+$RR['piezas'];
//Seleciona las piezas de caja y rollo,para ponerlas en stock
			$VAi=$MySQLiconn->query("SELECT sum(piezas) as totalCaja FROM caja where baja=2 and producto='".$row['descripcionImpresion']."'");
			$caj=$VAi->fetch_array();
			$Vei=$MySQLiconn->query("SELECT sum(piezas) as totalRollo FROM rollo where baja=2 and producto='".$row['descripcionImpresion']."'");
			$rol=$Vei->fetch_array();
			$stockcaja=$caj['totalCaja'];
			$stockrollo=$rol['totalRollo'];
			?>


			<div id="minidivs" class="col-lg-2">
				<p><?php echo ucwords($rew['descripcionProceso']);?>
					<a class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generar=<?php echo $rew['descripcionProceso']."|".$row['descripcionImpresion'];?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a></p>
			<b style="font:bold 12px Sansation"><?php if($rew['descripcionProceso']=='programado'){//Programa es especifico
				echo number_format($unidades,3)."\n";
				$redProg=$ss-$enProceso;
				if($tipo!="BS")
				{
					if($redProg<=0){print("<br><b style='font:bold 12px Sansation;color:blue;'>".number_format($redProg,3)."</b>");}else{print("<br><b style='font:bold 12px Sansation;color:#961010;'>".number_format($redProg,3)."</b>");}
						?>
						<div class="progress">
										<?php $set=($unidades/$redProg)*100;
										if($set<=25)
										{
											$clase="danger";
										}
										else if($set>25 && $set<=60)
										{
											$clase="warning";
										}
										else if($set>60 && $set<=99)
										{
											$clase="info";
										}
										else if($set>100)
										{
											$clase="success";
										}
										?>
										<div class="progress-bar progress-bar-<?php echo $clase?>" role="progressbar"
										aria-valuenow="<?php echo number_format($unidades)?>" aria-valuemin="0" aria-valuemax="<?php echo number_format($redProg) ?>" style="width: <?php echo number_format($set)."px"?>">
										<span class="sr-only"></span>
									</div>
								</div>
					<?php

				}


				 //Aqui va el pendiente por entregar menos el inventario en proceso
			}
				else if($rew['descripcionProceso']=="Embarque")//Embarque es especifico
				{
					echo "0.0"."\n";//Si no es programado pero es embarque entonces..
					print("<br><b style='font:bold 12px Sansation;color:#961010;'>"."0.0"."</b>");
				}
				else if($rew['descripcionProceso']=="caja")
				{
					$Pak=$MySQLiconn->query("SELECT sum(piezas) as piezas from caja where producto='".$row['descripcionImpresion']."' and baja=1");
					$num=$Pak->fetch_object();
					$enProceso=$enProceso+$num->piezas;
					echo number_format($num->piezas,3)."\n";
					$restC=${$rew['descripcionProceso']}-$stockcaja;
					if($restC>0){echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".$restC."</b>";}
						else{echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".$restC."</b>";}
						}
						else if($rew['descripcionProceso'=="rollo"])
						{
							$Pak=$MySQLiconn->query("SELECT sum(piezas) as piezas from rollo where producto='".$row['descripcionImpresion']."' and baja=2");
							$num=$Pak->fetch_object();
							$enProceso=$enProceso+$num->piezas;
							$restR=${$rew['descripcionProceso']}-$stockrollo;
							echo number_format($num->piezas,3)."\n";
							if($restR>0){echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".$restR."</b>";}
								else{echo "<br><b style='font:bold 12px Sansation;color:blue;'>".$restR."</b>";}
									?>
									<div class="progress">
										<?php $set=($num->piezas/$restR)*100;
										if($set<=25)
										{
											$clase="danger";
										}
										else if($set>25 && $set<=60)
										{
											$clase="warning";
										}
										else if($set>60 && $set<=99)
										{
											$clase="info";
										}
										else if($set>100)
										{
											$clase="success";
										}
										?>
										<div class="progress-bar progress-bar-<?php echo $clase?>" role="progressbar"
										aria-valuenow="<?php echo number_format($num->piezas)?>" aria-valuemin="0" aria-valuemax="<?php echo number_format($restR) ?>" style="width: <?php echo number_format($set)."px"?>">
										<span class="sr-only"></span>
									</div>
								</div>
								<?php
							}
							?>

				 <?//Coloca las unidades que faltan para los paquetes 'rollo','queso',etc
				 ${$rew['descripcionProceso']}=0;
				 ?></b></div>
				 <?php
				}
				else
				{
					$tabla="`pro".$rew['descripcionProceso']."`";
					$ont=$MySQLiconn->query("SELECT  sum(unidades) as unidadesTotal from ".$tabla." where producto='".$row['descripcionImpresion']."'and total=1 and rollo_padre=0");
					$ront=$ont->fetch_array();
					$uni=$ront['unidadesTotal'];
					$uni=(double) $uni;
					$enProceso=$uni+$enProceso;
					$enProceso=$enProceso;
			$MER=$MySQLiconn->query("SELECT  sum(unidadesIn)-sum(unidadesOut) as merma,sum(unidadesIn) as totales from merma where producto='".$row['descripcionImpresion']."' and proceso='".$rew['descripcionProceso']."'");//Consulta para la merma

			$mRow=$MER->fetch_array();

			$plusMer=$MySQLiconn->query("SELECT sum(unidades) as more FROM codigos_baja WHERE producto='".$row['descripcionImpresion']."' and proceso='".$rew['descripcionProceso']."'");
			$rev=$plusMer->fetch_array();
			
			$SQL_m_PP=$MySQLiconn->query("SELECT merma_p FROM procesos WHERE descripcionProceso='".$rew['descripcionProceso']."'");
			$r_merp=$SQL_m_PP->fetch_array();
			

			$m_permitida=$mRow['totales']-(($mRow['totales']*$r_merp['merma_p'])/100);
			
			$mermilla=($mRow['merma']+($m_permitida-$mRow['totales']))+$rev['more'];
			$m_proc=$mRow['totales']-$m_permitida;
			$mermaProceso=($mermilla*100)/$m_permitida;
			$mermaReal=($mermilla*100)/$mRow['totales'];
			if(!empty($row['nombreBanda']) && $stopBanda==0)
			{

				$bandaRes=$uni+$bandaRes;

			}
			//Ront es la variable que administra los datos internos de cada proceso como el scrap
			?>
			<div id="minidivs" class="dropdown" style="float:right;">
				
  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?php echo ucwords($rew['descripcionProceso']);?>
  	<span class="caret"></span></button>
				<p><a  class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generarAll=<?php echo $rew['descripcionProceso']."|".$row['descripcionImpresion'];?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a></p>
				
				<b style="font:bold 12px Sansation"><?php echo number_format($uni,3);?></b><br>
				<?php
				if($mermaProceso>0)
				{
					?>
					<b style="font:bold 12px Sansation Light;color:#D81737"><?php echo number_format($mermaProceso,3)."%";?></b>&nbsp;
					<?php
				}
				else if($mermaProceso<0)
				{
					?>
					<b style="font:bold 12px Sansation Light;color:#53BBCE;"><?php echo number_format($mermaProceso,3)."%";?></b>&nbsp;<?php
				}
				?>
				<ul class="dropdown-menu" style="padding:10px;">
					<li>Merma total:<p style="font:bold 16px Sansation;""><?php echo number_format($mermaProceso,3)."%";?></p></li><hr>
    <li>Merma del proceso:<p style="font:bold 16px Sansation;"><?php echo number_format($m_proc,3)." Mlls";?></p></li><hr>
    <li>Merma real:<p style="font:bold 16px Sansation;"><?php echo number_format($mermaReal,3)." %";?></p></li>
  </ul>
			</div>
			<?php



		}

	}
	?>
</div>
<hr>
<?php if($tipo!="BS")
{?>
	<div class="row" >
	<div title="Millares producidos hasta la actualidad" class="col-lg-2" id="div_">
		<p>Histórico<span class="glyphicon glyphicon-book pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1"><?php echo number_format($historico,3); ?></b>
		<p title="Historico de producto programado">
			<b class="num_tablero_1"><?php if(($historico-$unidades)>0){echo number_format($historico-$unidades,3);} ?></b>
		</p>
	</div>
	<div id="div_" title="Historico de producto impreso" class="col-lg-2" >
		<p>Scrap<span class="glyphicon glyphicon-trash pull-right" aria-hidden="true"></span></p><b class="num_tablero_1"><?php 
		if(($stockcaja+$stockrollo)+$enProceso+$send>0)
		{
			$scrap=number_format((((($sill['historico']-$unidades)-(($stockcaja+$stockrollo)+$enProceso+$send)))*100)/($sill['historico']-$unidades),3);//Esta saca el scrap
		}

		if($scrap>0 || $scrap<0){echo $scrap."%";}else{echo "0.0%";}?></b> 

	</div>

	<div title="Cantidad restante por producir" class="col-lg-2" id="div_">
		<p>Por entregar<span class="glyphicon glyphicon-share pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1"><?php echo $ss ?></b>
	</div>
	<div title="Millares de producto en producción" class="col-lg-2" id="div_">
		<p>En proceso<span class="glyphicon glyphicon-cog pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1"><?php echo number_format($enProceso,3) ?></b>
	</div>

	<div title="Producto enviado en millares" class="col-lg-2" id="div_">
		<p>Enviado<span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1"><?php echo number_format($send,3);?></b>
	</div>

	<div title="Producto en almacén(Sin enviar)" class="col-lg-2" id="div_">
		<p>Stock<span class="glyphicon glyphicon-list-alt pull-right" aria-hidden="true"></span></p>

		<p style="float:left;margin-left:5px">caja:<br><b style="float:right;" class="num_tablero_1"><?php echo number_format($caj['totalCaja'],3) ?></b></p>
		<p style="float:left;margin-left:40px">rollo:<br><b style="float:right;" class="num_tablero_1"><?php echo number_format($rol['totalRollo'],3) ?></b></p>
	</div>
</div>
<hr>
<?php
}
 if($tipo!="BS")
{?>
	<div class="row">
	<?php
	if(!empty($row['tintas']))
		{?>
			<div class="col-lg-4" id="div_" style="float:left;"><p class="cubs">Plan de entregas (Dias)</p>
				<?php
				$DATE=$MySQLiconn->query("SELECT entregaConfi,cantidadConfi as pendiente,empaqueConfi  from confirmarprod where prodconfi=(select descripcionImpresion from impresiones where descripcionImpresion='".$row['descripcionImpresion']."') and bajaConfi=1");
				$link=$DATE->fetch_array();
				?>

				<?php

				$hoy=date("Y-m-d");

				$hast7=date("Y-m-d", strtotime("+6 days", strtotime($hoy)));
				$hast15=date("Y-m-d", strtotime("+13 days", strtotime($hoy)));
				$hast21=date("Y-m-d", strtotime("+20 days", strtotime($hoy)));

				$hast7x=date("Y-m-d", strtotime("+1 days", strtotime($hast7)));
				$hast15x=date("Y-m-d", strtotime("+1 days", strtotime($hast15)));
				$hast21x=date("Y-m-d", strtotime("+1 days", strtotime($hast21)));


				$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN curdate() AND '$hast7' && bajaConfi='1' ORDER BY embarqueConfi");    

				$producto=''; $cuentaC=0; $cuentaR=0;

				while ($confirmado=$confirmar->fetch_array()){ 

					$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
					$primera=$primero->fetch_array();
					$producto=$primera['descripcionImpresion']; 
					if ($confirmado['prodConfi']==$producto) {
						if ($confirmado['empaqueConfi']=='caja') {
							$cuentaC+=$confirmado['cantidadConfi'];
						}
						if ($confirmado['empaqueConfi']=='rollo') {
							$cuentaR+=$confirmado['cantidadConfi'];
						}
					}
				} 
				?>

					<div class="col-lg-1" id="campos_plan_entregas"><a title="Reporte de producto faltante a una semana" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hoy."&hst=".$hast7."&suc=--&prod=".$row['id']."";?>">0-7</a>
						<p  class="Pdate" style="text-align:left;color:#FF0034;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#FF0034;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
					</div>
					<?php
					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast7x' AND '$hast15' && bajaConfi='1' ORDER BY embarqueConfi");    

					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
						$primera=$primero->fetch_array();
						$producto=$primera['descripcionImpresion']; 
						if ($confirmado['prodConfi']==$producto) {
							if ($confirmado['empaqueConfi']=='caja') {
								$cuentaC+=$confirmado['cantidadConfi'];
							}
							if ($confirmado['empaqueConfi']=='rollo') {
								$cuentaR+=$confirmado['cantidadConfi'];
							}
						}
					} 
					?>
					<div class="col-lg-1" id="campos_plan_entregas"><a title="Reporte de producto faltante de una semana a 14 dias" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast7x."&hst=".$hast15."&suc=--&prod=".$row['id']."";?>">8-14</a>
						<p  class="Pdate" style="text-align:left;color:#DC2349;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#DC2349;"><?php if($cuentaR!=0){echo 'R'.$cuentaR;} ?></p>
					</div>
					<?php
					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast15x' AND '$hast21' && bajaConfi='1' ORDER BY embarqueConfi");    


					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
						$primera=$primero->fetch_array();
						$producto=$primera['descripcionImpresion']; 
						if ($confirmado['prodConfi']==$producto) {
							if ($confirmado['empaqueConfi']=='caja') {
								$cuentaC+=$confirmado['cantidadConfi'];
							}
							if ($confirmado['empaqueConfi']=='rollo') {
								$cuentaR+=$confirmado['cantidadConfi'];
							}
						}
					} 
					?>

					<div class="col-lg-1" id="campos_plan_entregas"><a title="Reporte de producto faltante a 15 dias" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast15x."&hst=".$hast21."&suc=--&prod=".$row['id']."";?>" >15-21</a>
						<p  class="Pdate" style="text-align:left;color:#B73D56;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#B73D56;"><?php if($cuentaR!=0){echo 'R'.$cuentaR;} ?></p>
					</div>

					<?php
					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast21x' AND '2037-01-01' && bajaConfi='1' ORDER BY embarqueConfi");    

					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
						$primera=$primero->fetch_array();
						$producto=$primera['descripcionImpresion']; 
						if ($confirmado['prodConfi']==$producto) {
							if ($confirmado['empaqueConfi']=='caja') {
								$cuentaC+=$confirmado['cantidadConfi'];
							}
							if ($confirmado['empaqueConfi']=='rollo') {
								$cuentaR+=$confirmado['cantidadConfi'];
							}
						}
					} 
					?>
					<div class="col-lg-1" style="float:left;"><a title="Reporte de producto faltante a mas de 21 días" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast21x."&hst=2037-01-01&suc=--&prod=".$row['id']."";?>" style="float:left;">21+ </a>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaR!=0){echo 'R'.$cuentaR;} ?></p>
					</div>
					<?php // Ordenes de compra Atrasadas

					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi<curdate() ORDER BY embarqueConfi");    

					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
						$primera=$primero->fetch_array();
						$producto=$primera['descripcionImpresion']; 
						if ($confirmado['prodConfi']==$producto) {
							if ($confirmado['empaqueConfi']=='caja') {
								$cuentaC+=$confirmado['cantidadConfi'];
							}
							if ($confirmado['empaqueConfi']=='rollo') {
								$cuentaR+=$confirmado['cantidadConfi'];
							}
						}
					}
					if($cuentaR>=0 || $cuentaC>=0)
					{
						?>
					<div class="col-lg-1" style="float:left;"><a title="Reporte de producto faltante a mas de 21 días" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast21x."&hst=2037-01-01&suc=--&prod=".$row['id']."";?>" style="float:left;"> Atrasadas</a>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaR!=0){echo 'R'.$cuentaR;} ?></p>
					</div>
					<?php
					} 
					?>
					</div><?php
					}

					?>
						<div class="col-lg-auto" id="div_" style="float:left;">
						<p class="cubs">Sustrato</p>
							<?php

							$SUS=$MySQLiconn->query("SELECT rendimiento from sustrato where descripcionSustrato='".$row['sustrato']."'");
							$ren=$SUS->fetch_array();
							$consumo=(($row['anchoPelicula']*$row['alturaEtiqueta'])/1000)/$ren['rendimiento'];

							?>

							<p style="font:bold 15px Sansation;margin-right:10px;"><?php echo $row['sustrato']?></p>
							<b style="font:bold 13px Sansation Light">Requerido:</b><b  style="font:bold 14px Sansation;margin-left:18px;margin-right:6px;color:#39734A"><?php if($redProg>0){echo number_format($consumo*$redProg,3)." kgs";}else{echo 0.0." kgs";}?></b><br>
							<b style="font:bold 13px Sansation Light">programado:</b><b  style="font:bold 14px Sansation;margin-left:6px;margin-right:6px;color:#2E6092"><?php echo number_format($consumo*$unidades,3)." kgs";?></b>
						</div>
						<?php 
						if(!empty($row['nombreBanda']))
						{
							$consumoBanda=($bandaRes+$redProg+$unidades)*$row['alturaEtiqueta'];
		//$Ban=$MySQLiconn->query("SELECT anchura from bandaSeguridad where nombreBanda='".$row['nombreBanda']."'"); ?>
		<div class="col-lg-2" id="div_" style="float:left;">Banda de seguridad
			<p ><?php echo $row['nombreBanda']?></p>
			<p><?php echo number_format($consumoBanda,2)." mts";?></p>

		</div>
	<?php
}
?>
<?php
if(!empty($row['holograma']))
	{?>
		<div id="div_" class="col-lg-auto" style="float:left;">Holograma
			<p class="cubs"><?php echo $row['holograma']?></p>

			<?php
			$Hol=$MySQLiconn->query("SELECT consumo FROM hlogpproducto WHERE tipo='".$row['holograma']."' AND impresion='".$row['descripcionImpresion']."'");
			$sct=$Hol->fetch_array();
			$consumo=$sct['consumo'];
			?>

			<b style="font:bold 13px Sansation Light">Requerido:</b><b  style="font:bold 14px Sansation;margin-left:18px;margin-right:6px;color:#39734A"><?php if($redProg>0){echo number_format($consumo*$redProg,3)." mts";}else{echo 0.0." mts";}?></b><br>
			<b style="font:bold 13px Sansation Light">programado:</b><b  style="font:bold 14px Sansation;margin-left:6px;margin-right:6px;color:#2E6092"><?php echo number_format($consumo*$unidades,3)." mts";?></b><br>
		</div>
		<?php
	}
	?>
<div>
	<?php
	if($row['tintas']>0)
	{
		?>
		<div class="col-lg-auto" id="div_" style="float:left;">
			<p class="cubs">Consumo de tintas</p>


			<?php 
			$Colors=$MySQLiconn->query("SELECT*FROM pantonepcapa where codigoImpresion=(SELECT codigoImpresion from impresiones where descripcionImpresion='".$row['descripcionImpresion']."')");
			while($Com=$Colors->fetch_array()){
				$capa=$MySQLiconn->query("SELECT codigoPantone from pantone where descripcionPantone='".$Com['descripcionPantone']."'");
				$sou=$capa->fetch_array();
				?>

				<div style="float:left;margin:4px;border-radius:3px;padding:3px;background-color:<?php echo '#'.$sou['codigoPantone'];?>">
					<p style="text-shadow:
					-1px -1px 0 #000,
					1px -1px 0 #000,
					-1px 1px 0 #000,
					1px 1px 0 #000;color:white;font:bold 12px Sansation"><?php echo $Com['descripcionPantone']?></p>
					<p style="background-color:#FFF;border-radius:3px;padding:3px;clear:left;color:black;font:bold 12px Sansation Light"><?php if($redProg>0){echo "<b style='color:#39734A;font:bolder 13px Sansation Light'>".number_format($Com['consumoPantone']*$redProg,3)." kgs</b>";}else{echo "<b style='color:#39734A;font:bolder 13px Sansation;font-weight:900'>0.0</b>";}?><br><?php echo "<b style='color:#2E6092;font:bolder 13px Sansation;font-weight:900'>".number_format($unidades*$Com['consumoPantone'],3)." kgs</b>";?></p>
				</div>
				<?php
			}
			?>
		</div>
		<?php 
	}?>

</div>

</div>
<?php
}

} ?>
</div>
</div>
<?php
if($unidades+$enProceso==0)
{
	$MySQLiconn->query("UPDATE produccion SET estado=0 WHERE nombreProducto='".$row['descripcionImpresion']."'");
}


}
?>

</div>

<script type="text/javascript">

</script>
