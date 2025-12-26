<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {

if($_SESSION['perfil']=="PF0"){ ?>

<head>
	<title>Diseño| Producto</title>
</head>
<?php

$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
$_SESSION['descripcion']=" ";
$_SESSION['descripcionCil']=" ";
$_SESSION['descripcionBanda']=" ";
include("../components/barra_lateral2.php");
include("Controlador_Cotizador/crud_cotizer.php");
include("../Database/conexionphp.php");
include("../Database/SQLConnection.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">

		<form method="GET" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading" style="height:10%"><b class="titulo">Cotizador de etiquetas</b>
					<div class="col-sm-3" style="float:right;"><label for="etiqueta"></label>
						<select name="cmbproducto" onchange="document.formulary.submit()" id="cmbproducto" class="selectpicker show-menu-arrow" 
						data-style="form-control" 
						data-live-search="true" 
						title="--Selecciona el producto--"
						>
						<?php
						$SQL=$mysqli->query("SELECT CONCAT('[',descripcionDisenio,']-',descripcionImpresion) as etiqueta,descripcionImpresion FROM impresiones WHERE baja=1");
						while($row=$SQL->fetch_array())
							{?>
								<option data-tokens="<?php echo $row['descripcionImpresion'];?>" <?php if(isset($_GET['cmbproducto'])){ if($_GET['cmbproducto']==$row['descripcionImpresion']){ echo "selected";}}?> value="<?php echo $row['descripcionImpresion'];?>" ><?php echo $row['etiqueta'];?></option>
								<?php
							}

							?>

						</select>
					</div>
				</div>
				
				
				
			</form>
			<div class="panel-body">
				<form name="cotiz" method="POST">
				<?php
				if(isset($_GET['cmbproducto']))
				{
					?>
					<div class="row" style="padding-left: 10px;padding-right:10px">
						<h3 style="font:bold 18px Sansation">Requerimiento de consumos</h3>
						<?php
						$SIC=$mysqli->query("SELECT descripcionproceso,numeroProceso from juegoprocesos where identificadorJuego=(SELECT juegoProcesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(
							SELECT descripcionDisenio from impresiones where descripcionImpresion='".$_GET['cmbproducto']."'))) and descripcionProceso!='programado'");
						while ($rowP=$SIC->fetch_array()) {
							?>
							<div class="col-sm-12" style="border-style: solid;border-width:2px; border-color:#DCDCDC;border-radius: 5px;margin-top:5px">
								<h4  style="border-radius:5px;padding:4px;background-color:#E3E3E3;font:bold 20px Sansation"><?php  echo ucwords(($rowP['descripcionproceso']));?><b style="float:right; font:bold 15px Sansation;color:black"><?php echo $rowP['numeroProceso'];?></b></h4>
								<hr>
								<?php
								$mysqli->next_result();
								$SELL=$mysqli->query("SELECT subProceso,elemento,consumo FROM consumos WHERE subProceso='".$rowP['descripcionproceso']."' and producto=(SELECT id FROM impresiones WHERE descripcionImpresion='".$_GET['cmbproducto']."' and baja=1 ) and baja=1");
								while($rowC=$SELL->fetch_array())
									{?>
										<div class="col-sm-4" style="float:left;border-style:solid;border-top: none;border-bottom:none;margin-left:5px;margin-right: 5px;border-width:3px;border-color: #E3E3E3;padding:5px;margin-bottom:5px">
											<?php 
											$SPR_1=sqlsrv_query($SQLconn,"SELECT p.CNOMBREPRODUCTO as elemento,u.CABREVIATURA as uni,u.CNOMBREUNIDAD, p.CIDVALORCLASIFICACION1,p.CIDVALORCLASIFICACION2,p.CIDVALORCLASIFICACION3,p.CIDUNIDADCOMPRA
FROM admproductos p
INNER JOIN admUnidadesMedidaPeso u on u.CIDUNIDAD=p.CIDUNIDADCOMPRA
WHERE p.CIDPRODUCTO ='".$rowC['elemento']."'");

											$rowprod= sqlsrv_fetch_array($SPR_1, SQLSRV_FETCH_ASSOC);

											?>
											<p style="font:bold 14px Sansation "><?php echo $rowprod['elemento']?></p>
											<div class="input-group"><span class="input-group-addon">Prov.</span>
											<select name="prov" class="form-control" >
											
											<option value="">--</option>
											<?php 
											$SPR=sqlsrv_query($SQLconn,"SELECT a.CIDPRODUCTO,a.CIDPROVEEDOR as id,a.CPRECIOCOMPRA,p.CNOMBREPRODUCTO,c.CRAZONSOCIAL as nombre,p.CIDVALORCLASIFICACION1 FROM admPreciosCompra a
												INNER JOIN admProductos p on p.CIDPRODUCTO=a.CIDPRODUCTO
												inner join admClientes c on c.CIDCLIENTEPROVEEDOR=a.CIDPROVEEDOR
												where p.CIDPRODUCTO='".$rowC['elemento']."' 
												and c.CIDCLIENTEPROVEEDOR!=0 ");

/*
Traer provedores y productos por CLASIFICACI0N
"SELECT a.CIDPRODUCTO,a.CIDPROVEEDOR,a.CPRECIOCOMPRA,p.CNOMBREPRODUCTO,c.CRAZONSOCIAL,p.CIDVALORCLASIFICACION1 FROM admPreciosCompra a
												INNER JOIN admProductos p on p.CIDPRODUCTO=a.CIDPRODUCTO
												inner join admClientes c on c.CIDCLIENTEPROVEEDOR=a.CIDPROVEEDOR
												where p.CIDVALORCLASIFICACION1=".$rowC['CIDVALORCLASIFICACION1']." or p.CIDVALORCLASIFICACION1=".$rowC['CIDVALORCLASIFICACION2']." or p.CIDVALORCLASIFICACION1=".$rowC['CIDVALORCLASIFICACION3']." or p.CIDVALORCLASIFICACION2=".$rowC['CIDVALORCLASIFICACION1']."
												or p.CIDVALORCLASIFICACION2=".$rowC['CIDVALORCLASIFICACION2']." or p.CIDVALORCLASIFICACION2=".$rowC['CIDVALORCLASIFICACION3']." or p.CIDVALORCLASIFICACION3=".$rowC['CIDVALORCLASIFICACION1']." or p.CIDVALORCLASIFICACION3=".$rowC['CIDVALORCLASIFICACION2']." or p.CIDVALORCLASIFICACION3=".$rowC['CIDVALORCLASIFICACION3']." 
												and c.CIDCLIENTEPROVEEDOR!=0 ");*/
												while ($rowProv= sqlsrv_fetch_array($SPR, SQLSRV_FETCH_ASSOC)) {?>
												<option value="<?php echo $rowProv['id']?>"><?php echo $rowProv['nombre']?></option>
												<?php
											}?>

										</select>
									</div>
										<hr>
										<b style="float:left;font-size:13px">Consumo:</b><b style="float:right;"><?php echo $rowC['consumo'].' '.$rowprod['uni'];?></b>
									</div>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
					<hr>
					<div class="row" style="padding-left: 10px;padding-right:10px">
						<h3 style="font:bold 18px Sansation">Requerimiento de tintas</h3>
						<?php 
						$Colors=$mysqli->query("SELECT*FROM pantonepcapa where codigoImpresion=(SELECT codigoImpresion from impresiones where descripcionImpresion='".$_GET['cmbproducto']."')");
						while($Com=$Colors->fetch_array()){
							$capa=$mysqli->query("SELECT codigoPantone from pantone where descripcionPantone='".$Com['descripcionPantone']."'");
							$sou=$capa->fetch_array();
							?>

							<div class="col-sm-3" style="float:left;margin:4px;border-radius:3px;padding:3px;background-color:<?php echo '#'.$sou['codigoPantone'];?>">
								<p style="text-shadow:
								-1px -1px 0 #000,
								1px -1px 0 #000,
								-1px 1px 0 #000,
								1px 1px 0 #000;color:white;font:bold 15px Sansation"><?php echo $Com['descripcionPantone']?></p>
								<div class="input-group"><span class="input-group-addon">Consumo</span><input disabled  class="form-control"  value="<?php echo number_format($Com['consumoPantone'],3).'kgs'?>" type="text" style="background-color:#FFF;border-radius:3px;padding:3px;clear:left;color:black;font:bold 12px Sansation Light;text-align:right;"></div><br>
								<div class="input-group"><span class="input-group-addon">Prov.</span><select name="cmbtintas" class="form-control"
									<?php 
									$SPR=sqlsrv_query($SQLconn,"SELECT distinct a.CIDPROVEEDOR as id,c.CRAZONSOCIAL as nombre FROM admPreciosCompra a
INNER JOIN admProductos p on p.CIDPRODUCTO=a.CIDPRODUCTO
inner join admClientes c on c.CIDCLIENTEPROVEEDOR=a.CIDPROVEEDOR
where p.CIDVALORCLASIFICACION1=12 or p.CIDVALORCLASIFICACION2=12 or p.CIDVALORCLASIFICACION3=12
and c.CIDCLIENTEPROVEEDOR!=0  ");
									while ($rowProv= sqlsrv_fetch_array($SPR, SQLSRV_FETCH_ASSOC)) {?>
									<option value="<?php echo $rowProv['id']?>"><?php echo $rowProv['nombre']?></option>
									<?php
								}?>

							</select></div></p>
						</div>
						<?php
					}?>
				</div>
				<hr>
				<div class="row" style="padding-left: 10px;padding-right:10px">
					<h3 style="font:bold 18px Sansation">Requerimiento de Sustrato</h3>
					<?php

					$SUS=$mysqli->query("select i.descripcionImpresion,i.anchoPelicula,i.alturaEtiqueta,s.rendimiento,i.sustrato from impresiones i
						inner join sustrato s on s.descripcionSustrato=i.sustrato
						where descripcionImpresion='".$_GET['cmbproducto']."'");
					$ren=$SUS->fetch_array();
					$consumo=(($ren['anchoPelicula']*$ren['alturaEtiqueta'])/1000)/$ren['rendimiento'];


					?>
					<div class="col-sm-3"  style="border-style: solid;border-width:2px; border-color:#DCDCDC;border-radius: 5px;">
						<p style="font:bold 15px Sansation;margin-right:10px;"><?php echo $ren['sustrato']?></p>
						<hr>
						<div class="input-group"><span class="input-group-addon">Consumo</span><input disabled class="form-control"  value="<?php echo number_format(($consumo/1000),5)." kgs";?>" type="text" style="background-color:#FFF;border-radius:3px;padding:3px;clear:left;color:black;font:bold 12px Sansation Light;text-align:right;"></div><br>
							<div class="input-group"><span class="input-group-addon">Prov.</span><select name="cmbtintas" class="form-control"
									<?php 
									$SPR=sqlsrv_query($SQLconn,"SELECT distinct a.CIDPROVEEDOR as id,c.CRAZONSOCIAL  as nombre FROM admPreciosCompra a
INNER JOIN admProductos p on p.CIDPRODUCTO=a.CIDPRODUCTO
inner join admClientes c on c.CIDCLIENTEPROVEEDOR=a.CIDPROVEEDOR
where p.CIDVALORCLASIFICACION1=11 or p.CIDVALORCLASIFICACION2=11 or p.CIDVALORCLASIFICACION3=11 or p.CIDVALORCLASIFICACION4=11
and c.CIDCLIENTEPROVEEDOR!=0 ");
									while ($rowProv= sqlsrv_fetch_array($SPR, SQLSRV_FETCH_ASSOC)) {?>
									<option value="<?php echo $rowProv['id']?>"><?php echo $rowProv['nombre']?></option>
									<?php
								}?>

							</select></div>


						<?php

					/*

					Consultas qu necesitaré
					SELECT*FROM admProductos
SELECT*FROM admDocumentosModelo where CIDDOCUMENTODE=32

SELECT a.CIDPRODUCTO,a.CPRECIO,doc.CRAZONSOCIAL,p.CNOMBREPRODUCTO,doc.CFECHAENTREGARECEPCION,doc.CIDCLIENTEPROVEEDOR FROM admMovimientos a
INNER JOIN admproductos p
ON p.CIDPRODUCTO=a.CIDPRODUCTO
INNER JOIN admDocumentos doc
ON  doc.CIDDOCUMENTO=a.CIDDOCUMENTO
WHERE doc.CIDDOCUMENTODE=32 AND p.CNOMBREPRODUCTO like '%sustrato%'

SELECT*FROM admMovimientos where CIDPRODUCTO=535
SELECT*FROM admdocumentos where CIDDOCUMENTO=2001
SELECT*FROM admClientes where CIDCLIENTEPROVEEDOR=0
SELECT*FROM admclasificacionesValores
*/?>

</div>
</div>
<button style="float:right;" class="btn btn-success btn-lg"><img src="../pictures/dollar.png" width="80">Obtener cotización</button>
</form>
</div>
<?php
}?>

</div>
	<?php /*
	$sqs=sqlsrv_query($SQLconn,"SELECT*FROM admMovimientos where CIDDOCUMENTO=1093");
	while ($sqc= sqlsrv_fetch_array($sqs, SQLSRV_FETCH_ASSOC)) 
	{
		echo "<p>".$sqc['CIDMOVIMIENTO']."</p>";
		echo "<h3>".$sqc['CIDPRODUCTO']."</h3>";
	} */?>
</div>
</div>
</div>

<?php
ob_end_flush();
}
else
{
echo '<html>
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="../css/global.css" /> 
</head>
<body>
<center>

<p class="message"><img src="../pictures/block.png" width="100"><br>No tienes los permisos para estar aquí</p>

<a style="background-color:#E27E7E;color:black;font:bold 15px Sansation;border-radius:5px;padding:5px" href="../Menu.php">Menu principal</a>
</center>
</body>
</html>';
}
}
 else {
	echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	include "../ingresar.php";
}

?>