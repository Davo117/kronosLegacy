	<?php
	include('db_produccion.php');
	error_reporting(0);
	$q=$tipo;

	//Datos obtenidos a partir del tipo que e escogió
	if($q!="1")//Si el producto no es banda de seguridad trae los datos desde producto
	{
		if($q==30)
		{
			$updBand=$MySQLiconn->query("UPDATE bandaseguridad set necesidad=0");
		}
		$resultado=$MySQLiconn->query("SELECT*FROM showTablero where idtipo='$q' order by descripcionImpresion asc");
	}
	else if($q=="1")
	{
		$resultado=$MySQLiconn->query("SELECT*FROM shoTableroBS where baja=1");
	}
	?>

	
	<?php
		//Se obtienen los datos de la necesidad de producto (Bucle general)
	while($row=$resultado->fetch_array())
	{
		?>
		<div class="panel panel-info">
			<?php
			$ss=0;
			$scrap=0;
			$stockcaja=0;
			$stockrollo=0;
			$Sick=$MySQLiconn->query("SELECT cantidadConfi,surtido as stock, empaqueConfi,idConfi,enlaceEmbarque from confirmarprod where prodConfi='".$row['idImpresion']."' and bajaConfi=1");
			$caja=0;
			$rollo=0;
			$contador=0;
			while($rou=$Sick->fetch_array()){

				$cantidad=$rou['cantidadConfi'];
				
				if($rou['enlaceEmbarque']==0)
				{
					$ss=$ss+$cantidad;

				if($rou['empaqueConfi']=="rollo")
				{
					$rollo=$rou['cantidadConfi']+$rollo;
				}
				else if($rou['empaqueConfi']=="caja")
				{
					$caja=$rou['cantidadConfi']+$caja;
				}
				}
				/*else
				{


				if($rou['empaqueConfi']=="caja")
				{
					$stockcaja=$rou['stock'];
				}
				else
				{
					$stockrollo=$rou['stock'];
				}
				}	*/		
				
			}
			$ska=$MySQLiconn->query("SELECT sum(unidades) as historico from tbproduccion where nombreProducto='".$row['idImpresion']."' and tipo='".$tipo."'");
			$sill=$ska->fetch_array();
 $enviado=0;
 $historico=$sill['historico'];
 $enProceso=0;
 ?>
 <div class="panel-heading"><b class="titulo"><?php echo $row['productos'].' ['.$row['codigoImpresion'].']';?></b>
 	<a class="tooltip-test" style="float:right;"  data-toggle="modal" data-target="#exampleModalLong<?php echo $row['idImpresion'];?>" href="#" title="Sección de ayuda"><img src="../pictures/question.png" class="img-responsive" width="25" height="25"></a>
 	
 		
 	</div>

 	<div class="modal fade" id="exampleModalLong<?php echo $row['idImpresion'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">Ayuda</h3>
        <h4><?php echo $row['productos'];?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>Simbología:</h4>
        <ul>

<?php

 	if($tipo!="1")
 		{?>
 			
 			<li><b style="margin:4px;"> Excedente</b><b style="border-radius:3px;margin:4px;background-color:blue;color:blue;">__</b></li>
 			<li><b style="margin:4px;"> Faltante | </b><b style="border-radius:3px;margin:4px;background-color:#961010;color:#961010;">__</b></li>
 			<li><b style="margin:4px;">Programado</b><b style="border-radius:3px;margin:4px;background-color:#2E6092;color:blue;">__</b></li>

 			<li><b style="margin:4px;">Por entregar</b><b style="border-radius:3px;margin:4px;background-color:#39734A;color:blue;">__</b></li>
 			<?php


 		}

 		?>
 		<li><b style="margin:4px;">Scrap | Merma</b><b style="margin:4px;background-color:#D81737;color:#D81737;">__</b><b style="">/Excedente</b><b style="border-radius:3px;margin:4px;background-color:#53BBCE;color:#53BBCE;">__</b></li>
 		<hr>
 		<li><strong>Nota:</strong><br><h5><?php echo $row['observaciones'];?></h5></li>
</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
 	<div class="panel-body" style="">
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


	//Calculo de piezas de caja y rollo,para colocar el producto enviado total
	/*

	<p style="clear:right;padding-left:15px;padding-top:10px;padding-bottom:5px;font:bold 105% Sansation">Información de entregas</p>
	*/?>

	<div class="row">
		<div class="col-xs-4 col-md-4  col-lg-4">
		<?php

		$res=$MySQLiconn->query("SELECT j.numeroProceso,j.descripcionProceso,(select id from procesos where descripcionProceso=j.descripcionProceso) as idProceso from juegoprocesos j where j.baja=1 and j.identificadorJuego=(select juegoprocesos from tipoproducto where id='$q') and j.tablero=1 ORDER by j.id asc");
		$stopBanda=1;
		$arrayBanda=array();
		$i=0;
		while ($rew=$res->fetch_array()) 
	{//El while para los cuadros de procesos internos
		if(!empty($row['nombreBanda']))//Si el producto tiene banda de seguridad,ubicamos en que nodo del proceso esta fusión
		{

			$procesoActual=$rew['numeroProceso'];
			if($rew['descripcionProceso']=="fusion")
			{
		//$stopBanda=0;

				$numFus=$rew['numeroProceso'];

			}
			else if(empty($numFus))
			{

				if($numFus<=$procesoActual and $rew['descripcionProceso']!="caja" and $rew['descripcionProceso']!="rollo")
				{
					$stopBanda=0;
					array_push($arrayBanda,$rew['descripcionProceso']);
				}
			}
			
		}
		$i++;
		if($rew['numeroProceso']==0)
		{
			$SK=$MySQLiconn->query("SELECT sum(tblotes.unidades) as unidades from tbproduccion inner join tblotes on tbproduccion.juegoLotes=tblotes.juegoLote where tbproduccion.nombreProducto='".$row['idImpresion']."' and tblotes.estado=1 and tbproduccion.tipo='".$tipo."'");
			$unidades=0.0."\n";//Esto coloca las unidades de programación
			$rem=$SK->fetch_array();
			if(!empty($rem['unidades']))
			{
				
				$unidades=$rem['unidades'];
			}

///////////Calculo de stock y el producto enviado
			$RCA=$MySQLiconn->query("SELECT SUM(piezas) as piezas FROM caja where baja=3 and producto='".$row['idImpresion']."'");
			$RC=$RCA->fetch_array();
			$RRO=$MySQLiconn->query("SELECT SUM(piezas) as piezas FROM rollo where baja=3 and producto='".$row['idImpresion']."'");
			$RR=$RRO->fetch_array();
			$send=$RC['piezas']+$RR['piezas'];
//Seleciona las piezas de caja y rollo,para ponerlas en stock
			$VAi=$MySQLiconn->query("SELECT sum(piezas) as totalCaja FROM caja where baja=2 and producto='".$row['idImpresion']."' or baja=1 and producto='".$row['idImpresion']."'");
			$caj=$VAi->fetch_array();
			$Vei=$MySQLiconn->query("SELECT sum(piezas) as totalRollo FROM rollo where baja=2 and producto='".$row['idImpresion']."' or baja=1 and producto='".$row['idImpresion']."'");
			
			$rol=$Vei->fetch_array();
			$stockcaja=$caj['totalCaja'];
			$stockrollo=$rol['totalRollo'];
			
			?>


			
					<?php if($rew['descripcionProceso']=='programado')
			{//Programa es especifico

				?>
				<div id="minidivs">
				<p><?php echo ucwords($rew['descripcionProceso']);?>
				<a class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generar=<?php echo $rew['descripcionProceso']."|".$row['idImpresion']."&tipo=".$tipo;?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a></p>
				<b style="font:bold 12px Sansation">
				<?php
				echo number_format($unidades,3)."\n";
				?></b><br>
				<b id="penprog<?php echo $row['idImpresion'];?>" style="font:bold 12px Sansation"><?php
				//$redProg=$ss-$enProceso;

				if($tipo!="1")
				{

					/*if($redProg<=0)
					{
						echo "<br><b style='font:bold 12px Sansation;color:blue;'>".number_format($redProg*(-1),3)."</b>";
					}
					else
					{
						echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".number_format($redProg,3)."</b>";
					}*/
					?>
				</b>
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
				else if($tipo==1)
				{
					$ss=$row['necesidad'];
					?>
				</b>
					<div class="progress">
						<?php $set=($unidades/$cantBand['necesidad'])*100;
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
							aria-valuenow="<?php echo number_format($unidades)?>" aria-valuemin="0" aria-valuemax="<?php echo number_format($cantBand['necesidad']) ?>" style="width: <?php echo number_format($set)."px"?>">
							<span class="sr-only"></span>
						</div>
					</div>
					<?php
				}

echo "</b></div>";
				 //Aqui va el pendiente por entregar menos el inventario en proceso
			}
				else if($rew['descripcionProceso']=="Embarque")//Embarque es especifico
				{
					echo "0.0"."\n";//Si no es programado pero es embarque entonces..
					print("<br><b style='font:bold 12px Sansation;color:#961010;'>"."0.0"."</b>");
				}
				else if($rew['descripcionProceso']=="caja")
				{
					$isBox=1;
					//Asignación de datos Caja
					$PakC=$MySQLiconn->query("SELECT sum(piezas) as piezas from caja where producto='".$row['idImpresion']."' and baja=1 or producto='".$row['idImpresion']."' and baja=2");
					$numC=$PakC->fetch_object();
					$enProceso=$enProceso+$numC->piezas;
					$restC=$caja-$stockcaja;
					//colocar datos
					
					}
					else if($rew['descripcionProceso']=="rollo")
					{
						$isRoll=1;
						$PakR=$MySQLiconn->query("SELECT sum(piezas) as piezas from rollo where producto='".$row['idImpresion']."' and baja=1 or producto='".$row['idImpresion']."' and baja=2");
						$numR=$PakR->fetch_object();
						$enProceso=$enProceso+$numR->piezas;
						$restR=$rollo-$stockrollo;

						// Colorcar datos
						
							}
							?>

				 <?//Coloca las unidades que faltan para los paquetes 'rollo','queso',etc
				 
				 ?>
				 <?php
				 ${$rew['descripcionProceso']}=0;
				}
				else
				{

					$tabla="`tbpro".$rew['descripcionProceso']."`";
					$ont=$MySQLiconn->query("SELECT  sum(unidades) as unidadesTotal from ".$tabla." where producto='".$row['idImpresion']."'and total=1 and rollo_padre=0 and tipo='".$tipo."'");
					$ront=$ont->fetch_array();
					$uni=$ront['unidadesTotal'];
					$uni=(double) $uni;
					$enProceso=$uni+$enProceso;
					$enProceso=$enProceso;
					$MER=$MySQLiconn->query("SELECT  sum(unidadesIn)-sum(unidadesOut) as merma,sum(unidadesIn) as totales from tbmerma where producto='".$row['idImpresion']."' and proceso='".$rew['idProceso']."' and tipo='".$tipo."'");//Consulta para la merma

					$mRow=$MER->fetch_array();

					$plusMer=$MySQLiconn->query("SELECT sum(unidades) as more FROM codigos_baja WHERE producto='".$row['idImpresion']."' and proceso='".$rew['descripcionProceso']."' and tipo='".$tipo."'");
					$rev=$plusMer->fetch_array();

					$SQL_m_PP=$MySQLiconn->query("SELECT merma_p FROM procesos WHERE id='".$rew['idProceso']."'");
					$r_merp=$SQL_m_PP->fetch_array();


					$m_permitida=$mRow['totales']-(($mRow['totales']*$r_merp['merma_p'])/100);
					$mermilla=($mRow['merma']+($m_permitida-$mRow['totales']))+$rev['more'];
					$m_proc=$mRow['totales']-$m_permitida;
					$mermaProceso=($mermilla*100)/$m_permitida;
					$mermaReal=($mermilla*100)/$mRow['totales'];
					if(in_array($rew['descripcionProceso'], $arrayBanda))
					{

						$bandaRes=$uni+$bandaRes;

					}
			//Ront es la variable que administra los datos internos de cada proceso como el scrap
					?>
					
					<div id="minidivs2" class="dropdown" style="float:left;">

						<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?php if($rew['descripcionProceso']=="impresion-flexografica"){ echo ucwords('flexografía');} else{ echo ucwords($rew['descripcionProceso']);};?>
						<span class="caret"></span></button>
						<p>
							<?php
							if(isset($_SESSION['usuario']) and $_SESSION['usuario']!='' or $tipo==1)
							{
								?>
								<a  class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generarAll=<?php echo $rew['descripcionProceso']."|".$row['idImpresion']."&tipo=".$tipo;?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a>
								<?php
							}
							?>
						</p>

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
							<b style="font:bold 12px Sansation Light;color:#53BBCE;"><?php echo number_format($mermaProceso*(-1),3)."%";?></b>&nbsp;<?php
						}
						?>
						<ul class="dropdown-menu" style="padding:10px;">
							<li>Merma total:<p style="font:bold 16px Sansation;""><?php echo number_format($mermaProceso,3)."%";?></p></li><hr>
							<li>Merma del proceso:<p style="font:bold 16px Sansation;"><?php echo number_format($m_proc,3)." Mlls";?></p></li><hr>
							<li>Merma real:<p style="font:bold 16px Sansation;"><?php echo number_format($mermaReal,3)." %";?></p></li>
							<hr>
							<?php
							if(isset($_SESSION['usuario']) and $_SESSION['usuario']=="cmancilla" or isset($_SESSION['usuario']) and $_SESSION['usuario']=="core")
							{?>
								<li><a target="_blank" href="documentos/pro-qst.php?proces=<?php echo $rew['descripcionProceso']."&producto=".$row['idImpresion']."&tipo=".$q;?>">Listado de rollos</a></li><?php
							}
							?>
						</ul>
					</div>
					<?php
				}

			}
			?>
		</div>
		<div class="col-lg-2 col-xs-2 col-md-2">
<?php
if($isRoll==1)
{?>
	<div>
	<div id="minidivs" style="float:left;">
				<p><?php echo 'Rollo';?>
				<a class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generar=<?php echo 'rollo'."|".$row['idImpresion']."&tipo=".$tipo;?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a></p>
				<b style="font:bold 12px Sansation">
<?php
echo number_format($numR->piezas,3)."\n";
						if($restR>0)
						{
							echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".$restR."</b>";}
							else
							{
								echo "<br><b style='font:bold 12px Sansation;color:blue;'>".$restR*(-1)."</b>";}
								?>
								<div class="progress">
									<?php $set=($numR->piezas/$restR)*100;
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
								</div></b></div><?php
}
if($isBox==1)
{
?>
<div id="minidivs" style="float:left;">
				<p><?php echo 'Caja';?>
				<a class="etiqs" target="_blank" style="float:right;margin-right:5px;" href="?generar=<?php echo 'caja'."|".$row['idImpresion']."&tipo=".$tipo;?>"><IMG src="../pictures/barcodesMini.png" title='Imprimir etiquetas'></IMG></a></p>
				<b style="font:bold 12px Sansation">
					<?php
echo number_format($numC->piezas,3)."\n";
					
					if($restC>0)
					{
						echo "<br><b style='font:bold 12px Sansation;color:#961010;'>".$restC."</b>";}
						else
							{echo "<br><b style='font:bold 12px Sansation;color:blue;'>".$restC*(-1)."</b>";}
						?>
						<div class="progress">
									<?php $set=($numC->piezas/$restC)*100;
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
										aria-valuenow="<?php echo number_format($num->piezas)?>" aria-valuemin="0" aria-valuemax="<?php echo number_format($restC) ?>" style="width: <?php echo number_format($set)."px"?>">
										<span class="sr-only"></span>
									</div>
								</div>
						<?php
						echo "</b></div>";
}
?>
		</div>
		<?php if($tipo!="1")
		{?>
			
				
<?php
/*
	<div title="Producto en almacén(Sin enviar)" class="col-xs-2" id="div_">
		<p>Embarcado<span class="glyphicon glyphicon-list-alt pull-right" aria-hidden="true"></span></p>

		<p style="float:left;margin-left:5px">caja:<br><b style="float:right;" class="num_tablero_1"><?php echo number_format($caj['totalCaja'],3) ?></b></p>
		<p style="float:left;margin-left:40px">rollo:<br><b style="float:right;" class="num_tablero_1"><?php echo number_format($rol['totalRollo'],3) ?></b></p>
	</div>
	*/?>
</div>
<?php

if($tipo!="1")
	{?>
		<div class="col-md-6 col-xs-6 col-lg-6" id="divx" style="">
			<?php
			if($tipo!="1")
				{?>
					<div  id="div_"><p class="cubs">Plan de entregas (Dias)</p>
						<?php
						$DATE=$MySQLiconn->query("SELECT cantidadConfi-surtido as pendiente,surtido as stock, empaqueConfi,idConfi,enlaceEmbarque from confirmarprod where prodConfi='".$row['descripcionImpresion']."' and bajaConfi=1");

						$link=$DATE->fetch_array();
						?>

						<?php

						$hoy=date("Y-m-d");
						$atradas=date("Y-m-d", strtotime("-1 days", strtotime($hoy)));
						$hast7=date("Y-m-d", strtotime("+6 days", strtotime($hoy)));
						$hast15=date("Y-m-d", strtotime("+13 days", strtotime($hoy)));
						$hast21=date("Y-m-d", strtotime("+20 days", strtotime($hoy)));

						$hast7x=date("Y-m-d", strtotime("+1 days", strtotime($hast7)));
						$hast15x=date("Y-m-d", strtotime("+1 days", strtotime($hast15)));
						$hast21x=date("Y-m-d", strtotime("+1 days", strtotime($hast21)));

						$hast1990=date("Y-m-d", strtotime("-1 year", strtotime($hoy)));
						 // Ordenes de compra Atrasadas

					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi<curdate() && bajaConfi='1' and enlaceEmbarque=0 ORDER BY embarqueConfi");    

					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						//$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  id='".$row['idImpresion']."' "); 
						//$primera=$primero->fetch_array();
						$producto=$row['idImpresion']; 
						if ($confirmado['prodConfi']==$producto) {
							if ($confirmado['empaqueConfi']=='caja') {
								$cuentaC+=$confirmado['cantidadConfi'];
							}
							else if ($confirmado['empaqueConfi']=='rollo') {
								$cuentaR+=$confirmado['cantidadConfi'];
							}
						}
					}
					if($cuentaR>=0 || $cuentaC>=0)
					{
						?>
						<div id="campos_plan_entregas" style="float:left;"><a title="Reporte de producto retrasado" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast1990."&hst=".$atradas."&suc=--&prod=".$row['idImpresion']."";?>">Atrasadas</a>
							<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
							<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
						</div>
						<?php
					} 
					

						$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN curdate() AND '$hast7' && bajaConfi='1' and enlaceEmbarque=0 ORDER BY embarqueConfi");    

						$producto=''; $cuentaC=0; $cuentaR=0;

						while ($confirmado=$confirmar->fetch_array()){ 


							$producto=$row['idImpresion']; 
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

						<div  id="campos_plan_entregas" style="float:left;"><a title="Reporte de producto faltante a una semana" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hoy."&hst=".$hast7."&suc=--&prod=".$row['idImpresion']."";?>">0-7</a>
							<p  class="Pdate" style="text-align:left;color:#FF0034;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
							<p  class="Pdate" style="text-align:left;color:#FF0034;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
						</div>
						<?php
						$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast7x' AND '$hast15' && bajaConfi='1' and enlaceEmbarque=0 ORDER BY embarqueConfi");    

						$producto=''; $cuentaC=0; $cuentaR=0;

						while ($confirmado=$confirmar->fetch_array()){ 

						/*$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  id='".$row['idImpresion']."' "); 
						$primera=$primero->fetch_array();*/
						$producto=$row['idImpresion']; 
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
					<div  id="campos_plan_entregas" style="float:left;"><a title="Reporte de producto faltante de una semana a 14 dias" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast7x."&hst=".$hast15."&suc=--&prod=".$row['idImpresion']."";?>">8-14</a>
						<p  class="Pdate" style="text-align:left;color:#DC2349;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#DC2349;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
					</div>
					<?php
					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast15x' AND '$hast21' && bajaConfi='1' and enlaceEmbarque=0 ORDER BY embarqueConfi");    


					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						//$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  id='".$row['idImpresion']."' "); 
						//$primera=$primero->fetch_array();
						$producto=$row['idImpresion']; 
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

					<div  id="campos_plan_entregas" style="float:left;"><a title="Reporte de producto faltante a 15 dias" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast15x."&hst=".$hast21."&suc=--&prod=".$row['idImpresion']."";?>" >15-21</a>
						<p  class="Pdate" style="text-align:left;color:#B73D56;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#B73D56;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
					</div>

					<?php
					$confirmar = $MySQLiconn->query("SELECT * FROM confirmarprod WHERE embarqueConfi BETWEEN '$hast21x' AND '2037-01-01' && bajaConfi='1' and enlaceEmbarque=0 ORDER BY embarqueConfi");    

					$producto=''; $cuentaC=0; $cuentaR=0;

					while ($confirmado=$confirmar->fetch_array()){ 

						//$primero= $MySQLiconn->query("SELECT descripcionImpresion FROM impresiones WHERE  descripcionImpresion='".$row['descripcionImpresion']."' "); 
						//$primera=$primero->fetch_array();
						$producto=$row['idImpresion']; 
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
					<div id="campos_plan_entregas" style="float:left;"><a title="Reporte de producto faltante a mas de 21 días" class="aDate" target="_blank" href="funciones/pdf/EntregasPDF.php?dsd=<?php echo $hast21x."&hst=2037-01-01&suc=--&prod=".$row['idImpresion']."";?>">21+</a>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaC!=0){echo 'C'.$cuentaC;} ?></p>
						<p  class="Pdate" style="text-align:left;color:#9F4B5C;"><?php if($cuentaR!=0){echo 'Q'.$cuentaR;} ?></p>
					</div>
					
					</div><?php
				}
$redProg=$ss-$enProceso;
				?>

			<hr style="clear:both;">
				<div id="div_" class="col-lg-4 col-md-4 col-xs-4">
					<p class="cubs">Sustrato</p>
					<?php

					$SUS=$MySQLiconn->query("SELECT rendimiento from sustrato where descripcionSustrato='".$row['sustrato']."'");
					$ren=$SUS->fetch_array();
				?>

					<p style="font:bold 15px Sansation;margin-right:10px;"><?php echo $row['sustrato']?></p>
					<?php
						if(!empty($ren['rendimiento']))
					{
						$consumo=(($row['anchoPelicula']*$row['alturaEtiqueta'])/1000)/$ren['rendimiento'];
						?>
					<p style="font:bold 13px Sansation Light">Requerido:</p><p  style="font:bold 14px Sansation;margin-left:18px;margin-right:6px;color:#39734A"><?php if($redProg>0){echo number_format($consumo*$redProg,3)." kgs";}else{echo 0.0." kgs";}?></p><br>
					<p style="font:bold 13px Sansation Light">programado:</p><p  style="font:bold 14px Sansation;margin-left:6px;margin-right:6px;color:#2E6092"><?php if(($consumo*$unidades)>=0){echo number_format($consumo*$unidades,3)." kgs";}else{ echo "0 kgs";}?></p><?php
					}
					else
					{
						?>
						<p style="font:bold 12px Sansation;margin-right:10px;">No se ha agregado el rendimiento</p><?php
					}
					?>

					
				</div>
				<?php 
				if(!empty($row['nombreBanda']))
				{
					if(($bandaRes+$redProg)>0)
					{
						
						$consumoBanda=($bandaRes+$redProg)*$row['alturaEtiqueta'];
						$MySQLiconn->query("UPDATE bandaseguridad set necesidad=necesidad+'".$consumoBanda."' WHERE nombreBanda='".$row['nombreBanda']."'");
					}
					else
					{
						$consumoBanda=0;
						$MySQLiconn->query("UPDATE bandaseguridad set necesidad=necesidad+'".$consumoBanda."' WHERE nombreBanda='".$row['nombreBanda']."'");
					}
					
		//$Ban=$MySQLiconn->query("SELECT anchura from bandaSeguridad where nombreBanda='".$row['nombreBanda']."'"); ?>
		<div class="col-xs-3" id="div_" style="float:left;"><p class="cubs">Banda de seguridad</p>
			<p style="font:bold 15px Sansation;margin-right:10px;"><?php echo $row['nombreBanda']?></p>
			<p style="font:bold 14px Sansation;margin-left:6px;margin-right:6px;color:#2E6092"><?php echo number_format($consumoBanda,2)." mts";?></p>

		</div>
		<?php

	}

	?>
	<?php

	if(!empty($row['holograa']))
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
			<?php
			if($row['tintas']>0)
			{
				?>
				<hr style="clear:both;">
				<div class="col-lg-auto" style="float:left;">
					<p class="cubs">Consumo de tintas</p>


					<?php 
					
					$Colors=$MySQLiconn->query("SELECT*FROM pantonepcapa where codigoImpresion=(SELECT codigoImpresion from impresiones where id='".$row['idImpresion']."')");
					while($Com=$Colors->fetch_array()){
						$capa=$MySQLiconn->query("SELECT codigoPantone from pantone where descripcionPantone='".$Com['descripcionPantone']."'");
						$sou=$capa->fetch_array();
						?>

						<div style="float:left;margin:4px;border-radius:3px;border-style:solid;border-width:1px; padding:3px;border-color:#EAEAEA;background-color:<?php echo '#'.$sou['codigoPantone'];?>">
							<p style="text-shadow:
							-1px -1px 0 #000,
							1px -1px 0 #000,
							-1px 1px 0 #000,
							1px 1px 0 #000;color:white;font:bold 12px Sansation"><?php echo $Com['descripcionPantone']?></p>
							<p style="background-color:#FFF;border-radius:3px;border-style:solid;border-width:1px;padding:3px;border-color:#EAEAEA;clear:left;color:black;font:bold 12px Sansation Light"><?php echo "<b style='color:#2E6092;font:bolder 13px Sansation;font-weight:900'>".number_format($unidades*$Com['consumoPantone'],3)." kgs</b>";?><br><?php if(($redProg)>0){echo "<b  style='color:#39734A;font:bolder 13px Sansation Light'>".number_format($Com['consumoPantone']*($ss+$enProceso),3)." kgs</b>";}else{echo "<b style='color:#39734A;font:bolder 13px Sansation;font-weight:900'>0.0 kgs</b>";}?></p>
						</div>
						<?php
					}
					?>
				</div>
				<?php 
			}?>
	</div>
	<?php
}

} ?>
</div>
<hr>
<div class="row" id="divp">
<div title="Millares producidos hasta la actualidad" class="col-xs-2 col-order-1" id="div_" style="clear:both;">
					<p>Histórico<span class="glyphicon glyphicon-book pull-right" aria-hidden="true"></span></p>
					<b class="num_tablero_1"><?php echo number_format($historico,3); ?></b>
					<p title="Historico de producto programado">
						<b class="num_tablero_1"><?php if(($enProceso+$send)>0){echo number_format($enProceso+$send,3);} ?></b>
					</p>
				</div>
				<div id="div_" title="Historico de producto impreso" class="col-xs-2">
					<p>Scrap<span class="glyphicon glyphicon-trash pull-right" aria-hidden="true"></span></p><b class="num_tablero_1"><?php 
					if(($stockcaja+$stockrollo)+$enProceso+$send>0)
					{
			$scrap=number_format((((($sill['historico'])-($enProceso+$send)))*100)/($sill['historico']),3);//Esta saca el scrap
		}

		if($scrap>0 || $scrap<0){echo $scrap."%";}else{echo "0.0%";}?></b> 

	</div>
	<?php
	if($tipo!=1)
	{?>
		<div title="Cantidad restante por producir" id="div_" class="col-xs-2">
		<p>Por entregar<span class="glyphicon glyphicon-share pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1" hidden id="porentregar<?php echo $row['idImpresion'];?>"><?php echo $ss ?></b>
		<b class="num_tablero_1" ><?php echo number_format($ss,3) ?></b>
	</div>
		<?php
	}
	else
	{
		?>
		<div title="Cantidad restante por producir" id="div_" class="col-xs-2">
		<p>Necesidad<span class="glyphicon glyphicon-share pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1" hidden id="porentregar<?php echo $row['idImpresion'];?>"><?php echo $ss ?></b>
		<b class="num_tablero_1" ><?php echo number_format($ss) ?> mts</b>
	</div>
		<?php
	}
	?>
	<div title="Millares de producto en producción" id="div_" class="col-xs-2">
		<p>En proceso<span class="glyphicon glyphicon-cog pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1" hidden id="lblenproceso<?php echo $row['idImpresion'];?>"><?php echo $enProceso?></b>
		<b class="num_tablero_1" ><?php echo number_format($enProceso,3) ?></b>
	</div>
	<div title="Producto enviado en millares"  id="div_" class="col-xs-2">
		<p>Enviado<span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span></p>
		<b class="num_tablero_1"><?php echo number_format($send,3);?></b>
	</div>
	<div title="Producto enviado en millares"  id="div_" class="col-xs-2">
		<p>Observaciones<span class="glyphicon glyphicon-tags pull-right" aria-hidden="true"></span></p>
		<h5><?php echo $row['observaciones'];?></h5>
	</div>
	<script type="text/javascript">
 $(document).ready(function(){
          var eproceso=parseFloat($("#lblenproceso<?php echo $row['idImpresion'];?>").text());
          var pendiente=parseFloat($("#porentregar<?php echo $row['idImpresion'];?>").text());
          var total=parseFloat(pendiente - eproceso).toFixed(3);
         
          if(total<=0)
          {
          	 total=numberWithCommas(total*(-1));
          $("#penprog<?php echo $row['idImpresion'];?>").text(total);
          $("#penprog<?php echo $row['idImpresion'];?>").css("color","blue");
          }
          else
          {
          	 total=numberWithCommas(total);
          $("#penprog<?php echo $row['idImpresion'];?>").text(total);
          $("#penprog<?php echo $row['idImpresion'];?>").css("color","#961010");
          }

          });

 function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>
</div>
</div>
</div>
<?php
if($tipo!=1)
{
	if($unidades+$enProceso==0)
	{
		$MySQLiconn->query("UPDATE tbproduccion SET estado=0 WHERE nombreProducto='".$row['idImpresion']."'");
	}
}
}
?>
</div>
</div>


