<?php
ob_start();
session_start();
$_SESSION['codigoPermiso']='774887464864';
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true ) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte de merma | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript" src="../css/teclasN.js"></script>
<script type="text/javascript" src="../FusionCharts/js/fusioncharts.js"></script>
<script type="text/javascript" src="../FusionCharts/js/themes/fusioncharts.theme.ocean.js"></script>
</head>

<body> 

<?php
error_reporting(0);
include("../FusionCharts/integrations/php/fusioncharts-wrapper/fusioncharts.php"); 	
include("../components/barra_lateral2.php");
 include("Controlador_Calidad/db_Producto.php"); ?>
<div id="page-wrapper">
<div class="container-fluid">

	<div class="panel panel-default" id="thep">

	<div class="panel-heading"><b class="titulo">Reporte de merma mensual</b>
		<form method="POST" name="formi" id="formi" role="form" class="form-control">
			<select name="anio" onchange="document.formi.submit()">
				<option value="">Seleccione el año</option>
				<option value="2019" <?php if($_POST['anio']=='2019'){ echo "selected";}?>>2019</option>
				<option value="2020" <?php if($_POST['anio']=='2020'){ echo "selected";}?>>2020</option>
			</select>
		</form>
					<button data-toggle="collapse" data-target="#chart-1" style="float:right;" class="btn btn-success btn-xs" ><img src="../pictures/ojo.png" class="img-responsive" width="25" height="25" title="Ocultar gráfico"></button></div>
						<div class="panel-body" id="panels">
							
							<div class="panel-heading collapse" id="chart-1" style=""></div>
							
			<?php
			if(isset($_POST['anio']))
			{
				$arrMonth=array(
				"chart"=>array(
				"caption"=>"Comportamiento de la merma",
				"yaxisname"=>"Porcentaje",
				"anchorradius"=>"7",
				"plottooltext"=>"Merma",
				"showhovereffect"=>"1",
				"showvalues"=>"1",
				"numbersuffix"=>"%",
				"theme"=>"candy",
				"anchorbgcolor"=>"#72D7B2",
				"palettecolors"=>"#72D7B2"
				)
			);
//$SQL=$MySQLiconn->query("");
			$arrayMeses=array();
			$arrayMeses[1]="Enero";
			$arrayMeses[2]="Febrero";
			$arrayMeses[3]="Marzo";
			$arrayMeses[4]="Abril";
			$arrayMeses[5]="Mayo";
			$arrayMeses[6]="Junio";
			$arrayMeses[7]="Julio";
			$arrayMeses[8]="Agosto";
			$arrayMeses[9]="Septiembre";
			$arrayMeses[10]="Octubre";
			$arrayMeses[11]="Noviembre";
			$arrayMeses[12]="Diciembre";
//SELECT*FROM tbmerma WHERE date(hora)>='2019-06-04' and date(hora)<'2019-06-05' and unidadesIn<unidadesOut
$Soul=$MySQLiconn->query("SELECT date(hora),month(hora) as mes  from tbmerma where year(hora)='".$_POST['anio']."' GROUP by month(hora) ");
$meses=$Soul->num_rows;
$arMesses=array();
$contador=1;
while($riw=$Soul->fetch_assoc())
{
	$arMesses[$contador][0]=$riw["mes"];
	$arMesses[$contador][1]=$_POST["anio"];
	$contador++;
}


$anio=date("Y");
$arrData=array();
for($i=1;$i<=$meses;$i++)
{
	//$fecha=date("Y").'-'.str_pad($i, 2, "0", STR_PAD_LEFT)."-".date("d");
	//Conteo de merma para caja
	$SQL=$MySQLiconn->query("SELECT (SELECT m.unidadesIn*1000 from tbcodigosbarras c inner join tbmerma m on m.codigo=c.codigo where c.lote=cod.lote and c.noProceso=0) as entradaC,sum(e.piezas) as salidaC,cod.lote FROM caja caj inner join ensambleempaques e on e.codEmpaque=caj.codigo inner join tbcodigosbarras cod on cod.codigo=e.codigo where year(caj.fechamov)='".$_POST['anio']."' and month(caj.fechamov)='".$arMesses[$i][0]."' group by cod.lote");
	$inBox=0;
	$outBox=0;
	while($rowCaja=$SQL->fetch_assoc())
	{
		$inBox=$rowCaja['entradaC']+$inBox;
		$outBox=$rowCaja['salidaC']+$outBox;
	}

	//Conteo de merma para rollo
	$SQLin=$MySQLiconn->query("SELECT (SELECT m.unidadesIn*1000 from tbcodigosbarras c inner join tbmerma m on m.codigo=c.codigo where c.lote=cod.lote and c.noProceso=0) as entradaR,sum(e.piezas) as salidaR,cod.lote FROM rollo caj inner join ensambleempaques e on e.codEmpaque=caj.codigo inner join tbcodigosbarras cod on cod.codigo=e.codigo where year(caj.fechamov)='".$arMesses[$contador][1]."' and month(caj.fechamov)='".$arMesses[$contador][0]."' group by cod.lote");
	$inRoll=0;
	$outRoll=0;
	while($rowRollo=$SQLin->fetch_assoc())
	{
		$inRoll=$rowRollo['entradaR']+$inRoll;
		$outRoll=$rowRollo['salidaR']+$outRoll;
	}

	$Sick=$MySQLiconn->query("SELECT sum(unidades) as bajas from codigos_baja where date(fecha_baja)>='".$arMesses[$i][1]."-".str_pad($arMesses[$i][0],  2, "0", STR_PAD_LEFT)."-01' and date(fecha_baja)<'".$arMesses[$i][1]."-".str_pad($arMesses[$i][0]+1,  2, "0", STR_PAD_LEFT)."-01'");
	$rum=$Sick->fetch_assoc();
	//Merma en millares
	
	$arrData[$i][0]=$arrayMeses[$arMesses[$i][0]];
	$arrData[$i][2]=($inRoll+$inBox)/1000;
	$arrData[$i][3]=($outBox+$outRoll)/1000;
	$arrData[$i][1]=(($arrData[$i][2]-($arrData[$i][3]+$rum['bajas']))*100)/$arrData[$i][2];
	$arrData[$i][4]=$arMesses[$i][1];
	//$arrData[$i][5]=$entradaL;
	//$arrData[$i][6]=$salidaL;
	//$arrData[$i][7]=$mermaL;
	
	
}


$labelV=array();
for($i=1;$i<=$meses;$i++)
{
array_push($labelV,array(
      "label"=>$arrData[$i][0],"value"=>$arrData[$i][1]));
}
/*$datasetChart=array();
array_push($datasetChart, array(
    "data"=>$labelV));*/

	/*$arrData=array(
			"label"=>"Enero",
			"value"=>"1"),
		array("label"=>"Febrero",
			"value"=>"2"),
		array("label"=>"Marzo",
			"value"=>"3")
	);*/
 $arrMonth["data"]=$labelV;


$jsonEncodeData=json_encode($arrMonth);
$columnChart = new FusionCharts("spline", "ex1", "100%", 400, "chart-1", "json",$jsonEncodeData);
$columnChart->render();
			}
			
?>				
<div class="col-xs-4 col-md-8 col-lg-8">
<?php
$num=count($arrData);
for($i=$num;$i>0;$i--)
{?>
	<div style="<?php if($i==$num){ echo "background-color:#FFE6DC"; } ?>;float:left;border-radius:4px;border-style:solid;border-color:#E7E7E7;border-width:2px;padding:5px;margin:5px;"><b style="font:bold 15px Sansation;color:#75AEBB"><?php echo $arrData[$i][0]?></b>  <b style="font:italic 12px Sansation"><?php echo $arrData[$i][4]?></b><br><b>In: <strong><?php echo number_format($arrData[$i][2],3)?> mlls</strong></b><br><b>Out: <strong><?php echo number_format($arrData[$i][3],3)?> mlls</strong></b>
		<br><b>In: <strong><?php echo number_format($arrData[$i][5],3)?> mts</strong></b><br><b>Out: <strong><?php echo number_format($arrData[$i][6],3)?> mts</strong></b><br>Scrap mlls:<b style="color:#993131"><strong><?php echo number_format($arrData[$i][1],2)?>%</strong></b><br>Scrap mts:<b style="color:#993131"><strong><?php echo number_format($arrData[$i][7],2)?>%</strong></b></div>
	<?php
}
?>

</div>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
         	$("#chart-1").collapse('show');

          });

</script>
</html>
<?php
ob_end_flush();
} 
else {
	//echo "<center><img src='../pictures/fuera_s.png' class='img-responsive' width='360' height='360'></img></center>";
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
} ?>