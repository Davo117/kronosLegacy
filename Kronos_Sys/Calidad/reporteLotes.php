<?php
if(isset($_GET['codigo'])){
	if(!empty($_GET['codigo'])){
		$codigo=$_GET['codigo'];
		include("../Database/db.php");
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	  	header("Expires: 0");
  		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  		header("content-disposition: attachment;filename=Reporte de control de bobinas.xls"); ?>
	<style type="text/css">
		#heads
		{
			background:#4A4E5A;
			color:white;
		}
		#sub
		{
			background:#C3C3C3;
		}
	</style>
		<?php 
		echo "<div class='table table-responsive'><table class='table table-hover' border='1'>";
		echo "<tr ><th rowspan='2' width='50' colspan='9'><p style='font:bold 20px Sansation'>Rastreabilidad del lote <strong>".$codigo."</strong></p></th><tr>";
		echo "<tr id='heads'><th>".utf8_decode("Código")."</th><th>Proceso</th><th>Noop</th><th>Fecha y Hora</th><th>Millares In</th><th>Millares Out</th><th>Long In</th><th>Long Out</th><th>Merma</th></tr>";
		$lSQL=$MySQLiconn->query("SELECT referenciaLote,noop,fecha_alta,unidades,longitud FROM tblotes where referenciaLote='".$codigo."'");
		$rowL=$lSQL->fetch_object();
		echo "<tr id='sub'><td colspan='8' align='center'><strong>Ingreso al sistema</strong><td></tr>";
		echo "<td>".$rowL->referenciaLote."</td><td>"."Liberado"."</td><td>".$rowL->noop."</td><td>".$rowL->fecha_alta."</td><td>".number_format($rowL->unidades,3)."</td><td>N/A</td><td>".$rowL->longitud."</td><td>N/A</td><td>N/A</td>";
		echo "</tr>";
		$mSQL=$MySQLiconn->query("SELECT c.codigo,(select descripcionProceso from procesos where id=m.proceso) as proceso,(((m.longIn-m.longOut)*100)/m.longIn) as merma,c.noop,m.hora,m.unidadesIn,m.unidadesOut,m.longIn,m.longOut FROM  codigosbarras c inner join tbmerma m on m.codigo=c.codigo WHERE lote='".$codigo."' order by m.hora");
		echo "<tr id='sub'><td colspan='8' align='center'><strong>Proceso productivo</strong><td></tr>";
		while($lstCodes=$mSQL->fetch_object()){
			echo "<tr>";
			echo "<td>".$lstCodes->codigo."</td><td>".$lstCodes->proceso."</td><td>".$lstCodes->noop."</td><td>".$lstCodes->hora."</td><td>".$lstCodes->unidadesIn."</td><td>".$lstCodes->unidadesOut."</td><td>".$lstCodes->longIn."</td><td>".$lstCodes->longOut."</td><td>".number_format($lstCodes->merma,3)."%</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<div class='table table-responsive'><table class='table table-hover' border='1'>";
		echo "<tr id='heads'><th>".utf8_decode("Código")."</th><th>Noop</th><th>Referencia empaque</th><th>Fecha y Hora</th><th>Piezas</th><th>Longitud</th><th>Ref. Ensamble</th><th colspan='2'>Embarque</th></heads>";
		echo "<tr id='sub'><td colspan='8' align='center'><strong>Empacado en ROLLO</strong><td></tr>";
		$eSQL=$MySQLiconn->query("SELECT s.codigo,e.fechamov,e.referencia,c.noop,s.codEmpaque,s.longitud,s.piezas,s.refEnsamble from ensambleempaques s inner join rollo e on e.codigo=s.codEmpaque inner join tbcodigosbarras c on c.codigo=s.codigo where c.lote='".$codigo."' order by s.refEnsamble");
		if(mysqli_num_rows($eSQL)==0){
			echo "<tr>";
			echo "<td colspan='9' align='center'>Sin existencias</td>";
			echo "</tr>";
		}
		else{
			while($rowE=$eSQL->fetch_object()){
				echo "<tr>";
				echo "<td>".$rowE->codigo."</td><td>".$rowE->noop."</td><td>".$rowE->referencia."</td><td>".$rowE->fechamov."</td><td>".$rowE->piezas."</td><td>".$rowE->longitud."</td><td>".$rowE->refEnsamble."</td><td></td>";
				echo "</tr>";
			}
		}
		echo "<tr id='sub'><td colspan='8' align='center'><strong>Empacado en CAJA</strong><td></tr>";
		$eSQL=$MySQLiconn->query("SELECT s.codigo,e.fechamov,e.referencia,c.noop,s.codEmpaque,s.longitud,s.piezas,s.refEnsamble from ensambleempaques s inner join caja e on e.codigo=s.codEmpaque inner join tbcodigosbarras c on c.codigo=s.codigo where c.lote='".$codigo."' order by s.refEnsamble");
					
		if(mysqli_num_rows($eSQL)==0){
			echo "<tr>";
			echo "<td colspan='9' align='center'>Sin existencias</td>";
			echo "</tr>";
		}
		else{
			while($rowE=$eSQL->fetch_object()){
				echo "<tr>";
				echo "<td>".$rowE->codigo."</td><td>".$rowE->noop."</td><td>".$rowE->referencia."</td><td>".$rowE->fechamov."</td><td>".$rowE->piezas."</td><td>".$rowE->longitud."</td><td>".$rowE->refEnsamble."</td><td></td>";
				echo "</tr>";
			}
		}
	}
	else{
		echo "error";
	}
}
else{
	echo "error";
} ?>