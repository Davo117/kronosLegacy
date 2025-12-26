<?php
ob_start();
//error_reporting(0);
include_once 'db_produccion.php';
//print("<script>alert('Esta enviando el parametro');</script>");
if(!empty($_GET['d']))
{
	$result=$MySQLiconn->query("UPDATE cache set dato='".$_GET['d']."' where id=7");
}
if(isset($_GET['generar']))
{
	$_SESSION['etiquetas']=$_GET['generar'];
	$proceso=$_SESSION['etiquetas'];
	$proceso=explode("|", $proceso,3);
	$producto=$proceso[1];
	$proceso=$proceso[0];
	$documento="etiq".$proceso;
	$name_document = 'etiquetas/'.$documento.'.php';

	if (file_exists($name_document)) {
		header("Location:$name_document");
	} else {
		header("Location:generarEtiquetasDinamicas.php");
	}

}

if(!empty($_POST['comboProcesos']))
{
	if(isset($_POST['comboProcesos']))
{
	$SQL=$MySQLiconn->query("SELECT nombreParametro as referencial from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='".$_POST['comboProcesos']."')");
}
else
{

}

$row=$SQL->fetch_object();
$procesoActual=$_POST['comboProcesos'];
$dato=$row->referencial;
$mostradorTotales="";
$mostradorReg="";
if(!empty($_POST['cmbLapso']) and !empty($_POST['cmbfchFinal']))
{
	
		$consul="DATE(fecha) between '".$_POST['cmbLapso']."' and '".$_POST['cmbfchFinal']."'";
		$consulTime="DATE(merma.hora) between '".$_POST['cmbLapso']."' and '".$_POST['cmbfchFinal']."'";
}
else
{
	$consul="DATE(fecha)=curdate()";
	$consulTime="DATE(merma.hora)=curdate()";
}
if(!empty($_POST['cmbProductos']))
{
	$consultProd="and producto='".$_POST['cmbProductos']."'";
	$consultProdTime="and merma.producto='".$_POST['cmbProductos']."'";
	$pr=$MySQLiconn->query("SELECT millaresPorPaquete as mpp from impresiones where descripcionImpresion='".$_POST['cmbProductos']."'");
	$gu=$pr->fetch_array();
	$mpp=$gu['mpp']*1000;
}
else
{
	
	$consultProd="";
	$consultProdTime="";
}
if(!empty($_POST['cmbEmpleados']))
{
	$consultEmp="and operador='".$_POST['cmbEmpleados']."'";
	$consultEmpTime="";
}
else
{
	$consultEmp="";
	$consultEmpTime="";
}
//$consul="DATE(fecha) between'2017-12-11' and curdate() ";
$S=$MySQLiconn->query("SELECT operador FROM `pro".$procesoActual."` where $consul $consultProd $consultEmp and `pro".$procesoActual."`.tipo='".$tipo."' group by operador");
while($rem=$S->fetch_object())
{
	$nombre=explode('[',$rem->operador);
	$codigo=$nombre[1];
	$nombre=$nombre[0];
	?>
	<div id="RepTable" class="table-responsive">
	<table class="table table-hover col-xs-3" border="0" style="">
	<tr style="font:bold 2vh Sansation;">
		<h4 class="ordenMedio"><?php echo $nombre ;?></h4>
	
			<?php 
			print("<th style='background-color:#90B4C8' >Hora</th>");
			print("<th style='' >Mts In</th>");
			print("<th style='' >Mts out</th>");
			print("<th style='' >Millares In</th>");
			print("<th style='' >Millares Out</th>");
			 if($procesoActual=="corte"){
				?><th style="">Paquetes</th><?php
			}
			else
			{
				?><th style="">Banderas</th><?php
			}
			print("</tr>");
			$e=date('H');
 $hor=date('H', strtotime( '-5 hour',strtotime($e)));
			for($i=1;$i<=$hor;$i++)
			{
					${"longIn".$i}=0;
					${"longOut".$i}=0;
					${"unidadesIn".$i}=0;
					${"unidadesOut".$i}=0;
					${"banderas".$i}=0;
					${"paquetes".$i}=0;
			}	
					$longInTotal=0;
					$longOutTotal=0;
					$unidadesInTotal=0;
					$unidadesOutTotal=0;
					$banderasTotal=0;
					$paquetesTotal=0;
					$tipos=$tipo;
					if($procesoActual=="corte")
					{
							$Sim=$MySQLiconn->query("SELECT merma.banderas,merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoActual."`.fecha) as hora,`pro".$procesoActual."`.producto from `pro".$procesoActual."` inner join merma on merma.codigo=`pro".$procesoActual."`.$dato where `pro".$procesoActual."`.operador='".$rem->operador."' and $consul $consultProdTime $consultEmpTime and `pro".$procesoActual."`.tipo='".$tipos."' and rollo_padre=1 group by TIME(`pro".$procesoActual."`.fecha)");
					}
					else
					{
							$Sim=$MySQLiconn->query("SELECT merma.codigo,merma.banderas,merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoActual."`.fecha) as hora,`pro".$procesoActual."`.producto from `pro".$procesoActual."` inner join merma on merma.codigo=`pro".$procesoActual."`.$dato where `pro".$procesoActual."`.operador='".$rem->operador."' and $consul $consultProdTime $consultEmpTime and `pro".$procesoActual."`.tipo='".$tipos."' and rollo_padre=0 group by merma.codigo
								");

					}
		
			while($ho=$Sim->fetch_object())
			{
				$SKISH=$MySQLiconn->query("SELECT millaresPorPaquete FROM impresiones WHERE descripcionImpresion='".$ho->producto."'");
				$rems=$SKISH->fetch_assoc();
				$mpp=$rems["millaresPorPaquete"]*1000;
				//error_reporting(0);
				if(strtotime($ho->hora)>=strtotime("8:00:00") && strtotime($ho->hora)<strtotime("9:00:00")){
					$longIn1=$longIn1+$ho->longIn;
					$longOut1=$longOut1+$ho->longOut;
					$unidadesIn1=$unidadesIn1+$ho->unidadesIn;
					$unidadesOut1=$unidadesOut1+$ho->unidadesOut;
					$banderas1=$banderas1+$ho->banderas;
					$paquetes1=$paquetes1+number_format(($ho->unidadesIn*1000)/$mpp);
				
			
			}
			if(strtotime($ho->hora)>=strtotime("9:00:00") && strtotime($ho->hora)<strtotime("10:00:00")){
				$longIn2=$longIn2+$ho->longIn;
					$longOut2=$longOut2+$ho->longOut;
					$unidadesIn2=$unidadesIn2+$ho->unidadesIn;
					$unidadesOut2=$unidadesOut2+$ho->unidadesOut;
					$banderas2=$banderas2+$ho->banderas;
					$paquetes2=$paquetes2+number_format(($ho->unidadesOut*1000)/$mpp);
			}
			if(strtotime($ho->hora)>=strtotime("10:00:00") && strtotime($ho->hora)<strtotime("11:00:00")){
				$longIn3=$longIn3+$ho->longIn;
					$longOut3=$longOut3+$ho->longOut;
					$unidadesIn3=$unidadesIn3+$ho->unidadesIn;
					$unidadesOut3=$unidadesOut3+$ho->unidadesOut;
					$banderas3=$banderas3+$ho->banderas;
					$paquetes3=$paquetes3+number_format(($ho->unidadesOut*1000)/$mpp);
					
			}
			if(strtotime($ho->hora)>=strtotime("11:00:00") && strtotime($ho->hora)<strtotime("12:00:00")){
				$longIn4=$longIn4+$ho->longIn;
					$longOut4=$longOut4+$ho->longOut;
					$unidadesIn4=$unidadesIn4+$ho->unidadesIn;
					$unidadesOut4=$unidadesOut4+$ho->unidadesOut;
					$banderas4=$banderas4+$ho->banderas;
					$paquetes4=number_format($paquetes4+($ho->unidadesIn*1000)/$mpp);
						
			}
			if(strtotime($ho->hora)>=strtotime("12:00:00") && strtotime($ho->hora)<strtotime("13:00:00")){
				$longIn5=$longIn5+$ho->longIn;
					$longOut5=$longOut5+$ho->longOut;
					$unidadesIn5=$unidadesIn5+$ho->unidadesIn;
					$unidadesOut5=$unidadesOut5+$ho->unidadesOut;
					$banderas5=$banderas5+$ho->banderas;
					$paquetes5=$paquetes5+number_format(($ho->unidadesIn*1000)/$mpp);
				
			}
			if(strtotime($ho->hora)>=strtotime("13:00:00") && strtotime($ho->hora)<strtotime("14:00:00")){
				$longIn6=$longIn6+$ho->longIn;
					$longOut6=$longOut6+$ho->longOut;
					$unidadesIn6=$unidadesIn6+$ho->unidadesIn;
					$unidadesOut6=$unidadesOut6+$ho->unidadesOut;
					$banderas6=$banderas6+$ho->banderas;
					$paquetes6=$paquetes6+number_format(($ho->unidadesIn*1000)/$mpp);
				
				
			}
			if(strtotime($ho->hora)>=strtotime("14:00:00") && strtotime($ho->hora)<strtotime("15:00:00")){
				$longIn7=$longIn7+$ho->longIn;
					$longOut7=$longOut7+$ho->longOut;
					$unidadesIn7=$unidadesIn7+$ho->unidadesIn;
					$unidadesOut7=$unidadesOut7+$ho->unidadesOut;
					$banderas7=$banderas7+$ho->banderas;
					$paquetes7=$paquetes7+number_format(($ho->unidadesIn*1000)/$mpp);
					
			}
			if(strtotime($ho->hora)>=strtotime("15:00:00") && strtotime($ho->hora)<strtotime("16:00:00")){
				$longIn8=$longIn8+$ho->longIn;
					$longOut8=$longOut8+$ho->longOut;
					$unidadesIn8=$unidadesIn8+$ho->unidadesIn;
					$unidadesOut8=$unidadesOut8+$ho->unidadesOut;
					$banderas8=$banderas8+$ho->banderas;
					$paquetes8=$paquetes8+number_format(($ho->unidadesIn*1000)/$mpp);
					
			}
			if(strtotime($ho->hora)>=strtotime("16:00:00") && strtotime($ho->hora)<strtotime("17:00:00")){
				$longIn9=$longIn9+$ho->longIn;
					$longOut9=$longOut9+$ho->longOut;
					$unidadesIn9=$unidadesIn9+$ho->unidadesIn;
					$unidadesOut9=$unidadesOut9+$ho->unidadesOut;
					$banderas9=$banderas9+$ho->banderas;
					$paquetes9=$paquetes9+number_format(($ho->unidadesIn*1000)/$mpp);
				
			}
			if(strtotime($ho->hora)>=strtotime("17:00:00") && strtotime($ho->hora)<strtotime("18:00:00")){
				$longIn10=$longIn10+$ho->longIn;
					$longOut10=$longOut10+$ho->longOut;
					$unidadesIn10=$unidadesIn10+$ho->unidadesIn;
					$unidadesOut10=$unidadesOut10+$ho->unidadesOut;
					$banderas10=$banderas10+$ho->banderas;
					$paquetes10=$paquetes10+number_format(($ho->unidadesIn*1000)/$mpp);
					

			}

			}
			
			for($i=1;$i<$hor;$i++)
			{
				//error_reporting(0);
					?>
				<tr>
					<td  title="Horas" style="background-color:#AAC4D2"><p class="infoRows"><?php echo ($i+7).":00";?></p></td>
					<td title="Metros de entrada" style=""><p class="infoReporte"><?php if(${"longIn".$i}!=0)echo number_format(${"longIn".$i},2);?></p></td>
					<td title="Metros de salida"  style=""><p class="infoReporte"><?php  if(${"longOut".$i}!=0)echo number_format(${"longOut".$i},2);?></p></td>
					<td title="Millares de entrada"  style=""><p class="infoReporte"><?php  if(${"unidadesOut".$i}!=0)echo number_format(${"unidadesIn".$i},3);?></p></td>
					<td title="Millares de salida" style=""><p class="infoReporte"><?php  if(${"unidadesOut".$i}!=0)echo number_format(${"unidadesOut".$i},3);?></p></td>
					<?php 
					if($procesoActual!="corte")
					{ 
							echo "<td title='Cantidad de banderas'  style='width:15%'><p class='infoReporte'>".${"banderas".$i}."</p></td>";//le puse $mpp,porque en promedio un paquete tiene $mpp piezas,sin embargo,hay que modificarlo para que ajuste a lo que requiera el producto
					}
						else if($procesoActual=="corte")
						{
							echo "<td title='cantidad de paquetes'  style='width:15%'><p class='infoReporte'>". ${"paquetes".$i}."</p></td>";							
						}
						?>

				<tr><?php

			}
			
			for($i=1;$i<=$hor;$i++)
			{
					$longInTotal=$longInTotal+${"longIn".$i};
					
					$longOutTotal=$longOutTotal+${"longOut".$i};
					$unidadesInTotal=$unidadesInTotal+${"unidadesIn".$i};
					$unidadesOutTotal=$unidadesOutTotal+${"unidadesOut".$i};
					$banderasTotal=	$banderasTotal+${"banderas".$i};
					$paquetesTotal=$paquetesTotal+${"paquetes".$i};


			}

?><td class="cabeceras">Totales</td>
<?php echo "<td class='cabeceras'>".number_format($longInTotal,2)."</td>
	<td class='cabeceras'>".number_format($longOutTotal,2)."</td>
	<td class='cabeceras'>".number_format($unidadesInTotal,2)."</td>
	<td class='cabeceras'>".number_format($unidadesOutTotal,2)."</td>
	";
	if($procesoActual!="corte")
	{
		echo "<td class='cabeceras'>$banderasTotal</td>";
		
	}
	else if($procesoActual=="corte")
	{
		echo "<td class='cabeceras'>$paquetesTotal</td>";
	}
	?>

</table></div><?php }
}


ob_end_flush();
?>


