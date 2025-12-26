<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<head>
	<title>Index | Almacén</title>
	<style type="text/css">
		.square
		{
			border-style:solid;
			border-color: #A4A4A4;
			border-radius: 5px;
			padding: 5px;
			margin:5px;
			border-width: 2px;
		}
	</style>
</head>
<?php

$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
$_SESSION['descripcion']=" ";
$_SESSION['descripcionCil']=" ";
$_SESSION['descripcionBanda']=" ";
//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
include("../components/barra_latera_almacen.php");
include("controlador_almacen/crud_almacen.php");
include("../Database/conexionphp.php");
//include("../Database/SQLConnection.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">

			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Inventario</b>
					</div>
						<div class="panel-body">
							<h4>Stock Crítico</h4>
							<?php
							$array=array();
							$SQL=$mysqli->query("SELECT p.producto,p.min,u.identificadorUnidad as unidad FROM obelisco.productosCK p inner join saturno.unidades u on u.idUnidad=p.unidad where p.criterio=2 and p.min>1");
							while($row=$SQL->fetch_array())
							{
								$Scuil=$mysqli->query("SELECT sum(cantidad) as entradas,(select sum(cantidad) from obelisco.movimientos where producto=".$row['producto']." and tipoDoc=2) as salidas FROM obelisco.movimientos where producto=".$row['producto']." and tipoDoc=1");
								$rem=$Scuil->fetch_array();
								if($rem['entradas']-$rem['salidas']<=$row['min'])
								{
									$arreglin=array();
									$arreglin[1]=$row['producto'];
									$arreglin[2]=$row['min'];
									$arreglin[3]=$rem['entradas']-$rem['salidas'];
									$arreglin[4]=$row['unidad'];
									array_push($array,$arreglin);
								}
							}
							for($i=1;$i<count($array);$i++)
							{
								$color="";
								$set=0;
								$clase="";
								//$resultado = sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO as nombre  FROM ADMPRODUCTOS WHERE CIDPRODUCTO='".$array[$i][1]."'");
								//$cont = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
								$cont['nombre'] = "pendiente";
								/*$length=strlen($cont['nombre']);
								if($length<16)
								{
									$col='col-md-2';
								}
								else if($length<32)
								{
									$col='col-md-3';
								}
								else
								{
									$col='col-md-4';
								}*/

								$set=($array[$i][3]/$array[$i][2])*100;
								if($set==0)
								{
									$color='#9E453E';
								}
								else if($set>0 && $set<=25)
								{
									$color='#9D534E';
								}
								else if($set>25 && $set<=50)
								{
									$color='#9C6965';
								}
								else if($set>50 && $set<=70)
								{
									$color='#9B7572';
								}
								else if($set>70 && $set<=85)
								{
									$color='#9C8482';
								}
								else if($set>85 && $set<=100)
								{
									$color='#9A8D8C';
								}
								?>
									<div class="col-md-3 square" style="background-color:<?php echo $color;?>">
									<label><?php echo $cont['nombre'];?></label>
									<div class="progress">
<?php
									

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
										aria-valuenow="<?php echo $array[$i][3];?>" aria-valuemin="0" aria-valuemax="<?php echo $array[$i][2] ?>" style="width: <?php echo number_format($set)."px"?>">
										<span class="sr-only"></span>
									</div>
									
								</div>
								<label>Actual:<?php echo $array[$i][3].' '.$array[$i][4];?></label><br>
								<label>Mínimo:<?php echo $array[$i][2].' '.$array[$i][4];?> </label>
									</div>
								<?php
							}
						
							?>
								</div>
						</div>
					</div>
				</div>
				<script type="text/javascript" src="../css/menuFunction.js"></script>
				</html>
				<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}?>
