<?php
ob_start();
include("../../Database/SQLConnection.php");
include("../conexionobelisco.php");

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Reporte de inventarios del ".$_POST['cmbFchInicio']." al ".$_POST['cmbFchFinal'].".xls");

$fechaInicio = $_POST['cmbFchInicio'];
$fechaFin = $_POST['cmbFchFinal'];
$tipoMovimiento = $_POST['cmbMov'];
$familia = $_POST['cmbFamilia'];
$proveedor = $_POST['cdgproveedor'];
$producto = $_POST['cdgproducto'];
//$SQL = $SQLconn->query("");
//$MySQL = $mysqli->query("");
$consulta="where";
if($familia!=0)
{
	$consulta=$consulta." pz.familia=".$familia;
	if($proveedor!=0)
	{
		$consulta=$consulta." and pz.datop=".$proveedor;
	}
	if($producto!=0)
	{
		$consulta=$consulta." and pz.producto=".$producto;
	}
}
else if($proveedor!=0)
{
	$consulta=$consulta." pz.datop=".$proveedor;
	if($producto!=0)
	{
		$consulta=$consulta." and pz.producto=".$producto;
	}
}
else if($producto!=0)
{
	$consulta=$consulta." pz.producto=".$producto;
}
else
{
	$consulta="";
}

$namSQL = sqlsrv_query($SQLconn, "SELECT CRAZONSOCIAL FROM ADMCLIENTES WHERE CIDCLIENTEPROVEEDOR = ".$proveedor." and CESTATUS=1 and CTIPOCLIENTE=3");
$row = sqlsrv_fetch_array($namSQL, SQLSRV_FETCH_ASSOC);
?>

<div id="RepTable" class="table-responsive">
  <table class="table table-hover col-xs-6" border="1" style="float:left;">
  	<thead>
		<tr>
			<th colspan="5">REPORTE DE INVENTARIOS</th>
		</tr>
	  	<tr>
		  <th colspan="5">Intervalo: <?php echo $fechaInicio?> al <?php echo $fechaFin?></th>
		</th>
		<tr>
			<th style="" colspan="5">Proveedor: <?php 
				if ($proveedor != 0){ 
					echo $row['CRAZONSOCIAL'];
				}else{ 
					echo "Todos";
				};
				?>
			</th>  
		</tr>
		<tr>
		  <?php
		  	switch ($familia) {
				case '0':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Todos</th>
				<?php 
					break;
				case '1':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Tintas</th>
				<?php 					  
					break;
				case '2':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Acabados</th>
				<?php 					  	  
					break;
				case '3':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Aditivos</th>
				<?php 					  	  
					break;
				case '4':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Solventes</th>
				<?php 					  
					break;
				case '5':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Adhesivos</th>
				<?php 					  
					break;
				case '6':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Sustratos</th>
				<?php 					  
					break;
				case '7':
				?>
					<th colspan="5">Familia: <?php echo $familia?> - Indirectos</th>
				<?php 					  
					break;
				default:
				?>
					<th colspan="5">Familia: No seleccionada</th>
				<?php
					break;
			  }	
		  ?>
		<tr>
		<?php 
		switch ($tipoMovimiento) {
			case '':
				?>
				<th colspan="5">Tipo de movimiento: <?php echo $tipoMovimiento?> - Todo</th>
				<?php
				break;
			case '1':		
				?>
				<th colspan="5">Tipo de movimiento: <?php echo $tipoMovimiento?> - Sólo Entradas</th>
				<?php
				break;
			case '2':		
				?>
				<th colspan="5">Tipo de movimiento: <?php echo $tipoMovimiento?> - Sólo Salidas</th>
				<?php
				break;
			default:
				# code...
				break;
		}
		?>			
		</tr>
		</tr>
		<tr>
			<th style='background-color:#90B4C8'> Codigo Producto</th>
			<th style='background-color:#90B4C8'>Nombre Producto</th>
			<?php
			if($tipoMovimiento==0)
			{?>
				<th style='background-color:#90B4C8'>Entradas</th>
				<th style='background-color:#90B4C8'>Salidas</th>
			<?php
			}
			else if($tipoMovimiento==1)
			{?>
				<th style='background-color:#90B4C8'>Entradas</th>
			<?php
			}
			else if($tipoMovimiento==2)
			{?>
				<th style='background-color:#90B4C8'>Salidas</th>
			<?php
			}
			?>
			<th style='background-color:#90B4C8'>Stock actual</th>
		</tr>  
	</thead>
	<tbody>

	<?php 
		//Filtra productos por familia
		$MySQL = $mysqli->query("SELECT p.unidad,p.producto FROM productosCK p INNER JOIN pzcodes pz ON p.producto = pz.producto $consulta GROUP by pz.producto ");
	while ($resMySQL = $MySQL->fetch_assoc()) {
		$entradas="0";
		$salidas="0";
		$unidad='';
		//Consulta las entradas realizadas del produto seleccionado, incluyendo rango de fechas
		$inSQL=$mysqli->query("SELECT sum(cantidad) as entradas FROM obelisco.movimientos m where m.fechaMov BETWEEN '".$fechaInicio."' and '".$fechaFin."' and m.producto='".$resMySQL['producto']."' and tipoDoc=1");
		
		$varIn=$inSQL->fetch_assoc();
		if($varIn['entradas']>0)
		{
			$entradas=$varIn['entradas'];
		}
		
		//Consulta las salidas realizadas del produto seleccionado, incluyendo rango de fechas
		$outSQL=$mysqli->query("SELECT sum(cantidad) as salidas FROM obelisco.movimientos m where m.fechaMov BETWEEN '".$fechaInicio."' and '".$fechaFin."' and m.producto='".$resMySQL['producto']."' and tipoDoc=2");
		
		$varOut=$outSQL->fetch_assoc();
		if($varOut['salidas']>0)
		{
			$salidas=$varOut['salidas'];
		}
		
		$allSQL=$mysqli->query("SELECT sum(cantidad) as total,tipoDoc,(SELECT identificadorUnidad from  saturno.unidades where idUnidad='".$resMySQL['unidad']."' ) as unidad FROM obelisco.movimientos m where  m.producto='".$resMySQL['producto']."' group by tipoDoc");
		$existencia=0;
		while($varAll=$allSQL->fetch_assoc())
		{
			$unidad=$varAll['unidad'];
			if($varAll['tipoDoc']==1)
			{
				$existencia=$varAll['total'];
			}
			else if($varAll['tipoDoc']==2)
			{
				$existencia=$existencia-$varAll['total'];
			}
			
		}

		$nameSQL = sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO as name,CCODIGOPRODUCTO as code  FROM ADMPRODUCTOS WHERE CIDPRODUCTO=".$resMySQL['producto']."");
		$rowSQL = sqlsrv_fetch_array($nameSQL, SQLSRV_FETCH_ASSOC);		 
		?>
		<tr>
			<td><?php echo $rowSQL['code'];?></td>
			<td><?php echo utf8_decode($rowSQL['name']);?></td>
			<?php
			if($tipoMovimiento==0)
			{
				echo "<td>".$entradas.' '.$unidad."</th>";
				echo "<td>".$salidas.' '.$unidad."</th>";
			
			}
			else if($tipoMovimiento==1)
			{
				echo "<td>".$entradas.' '.$unidad."</td>";
			
			}
			else if($tipoMovimiento==2)
			{
				echo "<td>".$salidas.' '.$unidad."</td>";
			
			}?>
			<td><?php echo $existencia.' '.$unidad;?></td>
		</tr>
		<?php
	}
	?>
	<tr><tr></tr></tr>
	<tr>
		<th colspan="5"><?php echo utf8_decode("Nota: Si no existen entradas y salidas en los periodos señalados únicamente se mostrán las existencias.")?></th>
		
	<tr>
	</tbody>
  </table>

</div>
<?php

ob_end_flush();
?>
