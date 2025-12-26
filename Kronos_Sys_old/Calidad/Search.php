<?php 
if (isset($_POST['search']) or $_GET==true) { ?>
	<br>
	<style type="text/css">

</style>

<?php //background: #e8edff;">
include '../Database/db.php';



//irme a codigosbarras para eliminar el registro que pertenece al empaque 
/*Este codigo ignoralo por mientras xD
if(isset($_GET['destruir'])){
	$tipo=$_GET['tipo'];
	$code=$_GET['destruir'];
	$consulta=$MySQLiconn->query("SELECT referencia, producto from $tipo where codigo='$code' ");
	$rowC=$consulta->fetch_array();
	$consulta1=$MySQLiconn->query("SELECT codigo, producto from ensambleempaques where referencia='".$rowC['referencia']."' && producto='".$rowC['producto']."' ");

	while($row=$consulta1->fetch_array()){
		$consulta2=$MySQLiconn->query("SELECT * from codigosbarras where codigo='".$row['codigo']."' && producto='".$row['producto']."' ");
	}
}
*/

if(isset($_GET['desempacar'])){
	//tipo se refiere a caja o rollo 
	$tipo=$_GET['tipo'];
	//desempacar traera el codigo por medio del get
	$code=$_GET['desempacar'];
	//seleccionamos la referencia y el producto con los datos del get
	$consulta=$MySQLiconn->query("SELECT referencia, producto from $tipo where codigo='$code' ");
	$rowC=$consulta->fetch_array();
	//ahora seleccionamos el codigo y el producto de la tabla ensambleempaques donde los datos pertenecen a los datos traidos anteriormente.
	$consulta1=$MySQLiconn->query("SELECT codigo, producto from ensambleempaques where referencia='".$rowC['referencia']."' && producto='".$rowC['producto']."' ");
	//Puede tener varios paquetitos o rollos asi que aplicamos el while(mientras que) para recorrer hasta que no encuentre resultados.
	while($row=$consulta1->fetch_array()){
		//hacemos la consulta para poder traernos el proceso donde esta almacenado el codigo.
		$consulta2=$MySQLiconn->query("SELECT * from codigosbarras where codigo='".$row['codigo']."' && producto='".$row['producto']."' ");
		$rowC2=$consulta2->fetch_array();
		//este dato nos servira para poder identificar la tabla.
		$process=$rowC2['proceso'];

		//esta consulta nos servira para saber el nombre del campo donde se encuentra el codigo:
		$reconsulta=$MySQLiconn->query("SELECT nombreparametro FROM juegoparametros  where identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='$process') && numParametro='C'");
		$referencia=$reconsulta->fetch_array();
		$campo=$referencia['nombreparametro'];

		$MySQLiconn->query("UPDATE codigosbarras set baja='1' where codigo='".$row['codigo']."' && producto='".$row['producto']."' ");


		$MySQLiconn->query("UPDATE pro$process set total='1' where noop='".$rowC2['noop']."' && producto='".$row['producto']."' ");		
	}
	//ahora eliminamos todo lo relacionado al empaquetado.
	$MySQLiconn->query("DELETE from ensambleempaques where referencia='".$rowC['referencia']."' && producto='".$rowC['producto']."'");
	$MySQLiconn->query("DELETE from $tipo where codigo='$code'");
	echo "<script>alert('Se Desempacó el paquete ".$rowC['referencia']." con éxito.');</script>";
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=Buscador_Calidad.php'>";
}

else{








	if(isset($_POST['search'])){
		$lista=$_POST['lista'];
		$codigo=$_POST['codigo'];
	}
	elseif($_GET==true){ $lista=$_GET['lista']; $codigo=$_GET['codigo']; }

//Si la opcion fue noop
	if ($lista=='--'){ echo "<script>alert('Selecciona un codigo en especifico.')</script>"; return; }

	if ($lista=='1'){ 

		$consulta=$MySQLiconn->query("SELECT producto,lote, noop, noProceso from codigosbarras where codigo='".$codigo."'");
		$rowC=$consulta->fetch_array();
		if(mysqli_num_rows($consulta)>0)
		{
			echo "<h4>Código de Barras: ".$codigo."</h4>";
		}
		echo "<p style='font-size:100%'>Producto: ".$rowC['producto']."</h4><br>";
		$datos=explode("-", $rowC['noop']);
		$j=0;
		$agregar='';
		foreach ($datos as $numpp) {	$j++; 	}
		$concatenacion=array();
		for($h=1;$h<=$j;$h++){
			$concatenacion[$h-1]=$datos[$h-1];
			$var[$h-1]=implode('-', $concatenacion);	
		}
		for ($i=0; $i < $j; $i++) { 
			if ($i=='0') { 		$agregar=$agregar." noop='".$var[$i]."'";	}
			else { 		$agregar=$agregar." OR noop='".$var[$i]."'";	}
		}

		/* Esta consulta se tra solo los registros teniendo coo tope el código introducido.
		$procesosN=$MySQLiconn->query("SELECT noProceso from codigosbarras where noProceso<='".$rowC['noProceso']."' && lote='".$rowC['lote']."' && ($agregar) GROUP by noProceso order by noProceso ASC");
		*/
		$procesosN=$MySQLiconn->query("SELECT noProceso from codigosbarras where lote='".$rowC['lote']."' && ($agregar) GROUP by noProceso order by noProceso ASC");

		echo "<table class='table table-hover'><th class='sth'>Operador</th> <th class='sth'>Maquina</th>
		<th class='sth' style='width:15%;'>Fecha</th><th>Proceso</th> <th class='sth' style='width:10%;'> Mtrs In</th> <th class='sth' style='width:10%;'>Mtrs Out</th><th class='sth' style='width:10%;'>NoOp</th>";

		$break='';
		$codigoAnte='';
		if($procesosN->num_rows>0){
			$i=0;
			$banderinBS=0;
			while ($Count=$procesosN->fetch_array()) {

				$consultar=$MySQLiconn->query("SELECT codigo, proceso, noProceso, producto, noop from codigosbarras where noProceso='$i' && lote='".$rowC['lote']."' && ($agregar) order by noop DESC limit 1");
				$i++;
				if($consultar->num_rows<=1)
				{
					$break=$codigoAnte;
				}
				while($row=$consultar->fetch_array()){
					$contadorsin=$consultar->num_rows;
					$prod=$row['producto'];
					if ($row['noProceso']=='0') {	$codigoAnte=$row['codigo'];}
					else{
						$process=strtolower('pro'.$row['proceso']);
						$consulta1=$MySQLiconn->query("SELECT nombreparametro FROM juegoparametros  where identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='".$row['proceso']."') && numParametro='C'");
						$rowP=$consulta1->fetch_array();

						if ($process!='procorte') {


							$consulta3=$MySQLiconn->query("SELECT operador, maquina, fecha,total from `$process`  where ".$rowP['nombreparametro']."='".$codigoAnte."' Limit 1");
							$rowCons3=$consulta3->fetch_array();

							if($process=='prosliteo')
							{
								$SEL=$MySQLiconn->query("SELECT noop FROM codigosbarras WHERE codigo='".$codigo."'");
								$fetc=$SEL->fetch_array();
								$noop=$fetc['noop'];
								$consultin=$MySQLiconn->query("SELECT operador, maquina, fecha,total from `prosliteo`  where noop='".$noop."' Limit 1");
								$rowConss=$consultin->fetch_array();
								if($rowConss['total']=='0')
								{
									$SEL=$MySQLiconn->query("SELECT CONCAT(e.Nombre,' ',e.apellido) as nombre,b.longitud,b.fecha from baja_BS b inner join empleado e on e.numemple=b.empleado where b.codigo='".$codigo."'");
									$fetc=$SEL->fetch_array();
									$empleadoBS=$fetc['nombre'];
									$unidadesBS=$fetc['longitud'];
									$fechaBS=$fetc['fecha'];
									$banderinBS=1;
								}

							}
							$operador='Sin Datos';
							$maquina='Sin Datos';
							$fecha='Sin Datos';

							if($consulta3->num_rows>0){ 
								$operador=$rowCons3['operador'];
								$maquina=$rowCons3['maquina'];
								$fecha=$rowCons3['fecha'];
							}
						}
						else{
							if ($process=='procorte') {

								$consulta3=$MySQLiconn->query("SELECT operador, maquina, fecha from ".$process." where producto='".$row['producto']."' && rollo_padre='1' && ($agregar)");
								if($consulta3->num_rows>0){ 
									$rowCons3=$consulta3->fetch_array();
									$operador=$rowCons3['operador'];
									$maquina=$rowCons3['maquina'];
									$fecha=$rowCons3['fecha'];
								}
							}
						}

						$merma=$MySQLiconn->query("SELECT longin, longout from merma where codigo='".$codigoAnte."'");
						$rowM=$merma->fetch_array();

						$op=$MySQLiconn->query("SELECT noop from codigosbarras where codigo='".$codigoAnte."'");
						$rowNop=$op->fetch_array();
						if($noop==$codigo)
						{

						echo '<tr style="background-color:#C1E7B7">';
						}
						else
						{

						echo '<tr>';
						}
						echo '<td class="tab"><b style="font-size:100%">'; echo $operador; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $maquina; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $fecha; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $row['proceso']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowM['longin']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowM['longout']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowNop['noop']; echo '</b></td>';
						
						echo '
						</tr>';

						$codigoAnte=$row['codigo'];
					}
				}
			}
			echo "</table>";
			if($banderinBS)
			{
				
				echo '<table class="table table-hover"><th class="sth">Operador</th><th class="sth">Fecha</th><th class="sth">Metros</th>
				<tr><td class="tab"><b style="font-size:100%">'; echo $empleadoBS; echo '</b></td>
				<td class="tab"><b style="font-size:100%">'; echo $fechaBS; echo '</b></td>
				<td class="tab"><b style="font-size:100%">'; echo $unidadesBS; echo '</b></td>
				</tr></table';
			}
			$consultar=$MySQLiconn->query("SELECT referencia, producto, cdgEmbarque FROM ensambleempaques where codigo='".$codigoAnte."'");
			$ensamble=$consultar->fetch_array();
			echo "</div><div>";
			if($consultar->num_rows>0){
				$link='<a href="Buscador_Calidad.php?lista=3&codigo='.$ensamble['cdgEmbarque'].'"style="text-decoration: none;">'.$ensamble['cdgEmbarque'].'</a>';
				$paquete=$ensamble['referencia'];
				echo '</div><div><p style="font-size:120%;">Referencia: '; echo $paquete; echo '</h4>
				<p style="font-size:120%;">Embarque: '; echo $link; echo '</h4>';
			}
	//

			else{
				if($banderinBS==1)
				{

					echo "<div class='alert alert-info alert-dismissible fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Producto terminado</strong> , Este disco ha sido retirado de producción.
					</div>
					";
				}
				else
				{
					echo "<div class='alert alert-info alert-dismissible fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Información</strong> , Este producto se encuentra en producción.
					</div>
					";
				}
			}

	//

		}
		else{
			echo "<script>alert('No hay existencias'); history.back(1);</script>";
		}
	}


	//Si la opcion fue Empaque
	$cdgE='';
	if ($lista=='2'){
		echo "Empaque: ";
		$consulta1=$MySQLiconn->query("SELECT producto, referencia from caja where codigo='".$codigo."'");

		$consulta2=$MySQLiconn->query("SELECT producto, referencia from rollo where codigo='".$codigo."'");
		$total1=$consulta1->num_rows;
		$total2=$consulta2->num_rows;
		
		if ($total1>0 or $total2>0) {
			if ($total1>0) { $request=$consulta1->fetch_array(); $tipo='caja'; }
			elseif ($total2>0) { $request=$consulta2->fetch_array(); $tipo='rollo'; }
			echo $request['referencia']; echo '</h4>';
			$consulta11=$MySQLiconn->query("SELECT * from ensambleempaques  where producto='".$request['producto']."' && referencia='".$request['referencia']."'");
			echo "<table class='table table-hover'><tr>";
			$item=0;
			$cdgE='';
			$code='';
			while ($row=$consulta11->fetch_array()) {
				if ($item==4) { echo '</tr><tr><td style="background:#85929E;"></td></tr><tr>'; $item=0; }

				echo '<td class="tab"><p style="font-size:200%"><b>'; echo $row['referencia']; echo '</b>&nbsp; &nbsp; '; echo $row['piezas']; echo ' piezas</h4>
				
				<a  href="Buscador_Calidad.php?lista=1&codigo='.$row['codigo']; echo '" style="font-size:210%; margin:3%;" title="código: '.$row['codigo'].'">'; echo $row['codigo']; echo '</a></td><td style=" width:1%; background:#85929E;"></td>';
				$cdgE=$row['cdgEmbarque'];
				$item++;
			}
			echo '</div><div><p style="font-size:120%;">Producto: '; echo $request['producto']; echo '</h4>';

			if($cdgE=='') {
				echo '<a href="Buscador_Calidad.php?desempacar='.$codigo; echo '&tipo='.$tipo.'" style="font-size:210%; margin:3%;">Desempacar</a>';
			}
			else{
				echo '<p style="font-size:120%;">Embarque: <a href="Buscador_Calidad.php?lista=3&codigo='; echo $cdgE; echo '"style="text-decoration: none;">'; echo $cdgE; echo '</a></h4>';
			}
		}
		else{
			echo "<script>alert('No hay existencias');
			history.back(1);</script>";
		}
	}


//Si la opcion fue Embarque
	if ($lista=='3'){
		$consulta=$MySQLiconn->query("SELECT * from ensambleempaques where cdgEmbarque='".$codigo."' group by referencia");
		$consulta3=$MySQLiconn->query("SELECT * from embarque where numEmbarque='".$codigo."'");
		$row3=$consulta3->fetch_array();
		echo "Embarque: "; echo $row3['numEmbarque']; echo " </h4><div style='background:#AEB6BF; border-radius:10px;'>";
		
		if ($consulta3->num_rows>0) {
			$item=0;
			while ($row=$consulta->fetch_array()) {				
				$consulta2=$MySQLiconn->query("SELECT * from ".$row['tipoEmpaque']." where referencia='".$row['referencia']."' && producto='".$row['producto']."'");
				$row2=$consulta2->fetch_array();

				if ($item==4) { print('<br>'); $item=0; }
				echo '<a  href="Buscador_Calidad.php?lista=2&codigo='.$row2['codigo']; echo '" style="font-size:210%; margin:3%;" title="código: '.$row2['codigo'].'">'; echo $row2['referencia']; echo '</a>';
				$item++;
			}
			echo '</div><div><p style="font-size:120%;">Producto: '; echo $row3['prodEmbFK']; echo '</h4>
			<p style="font-size:120%;">Destino: '; echo $row3['sucEmbFK']; echo '</h4></div>';
		}
		else{
			echo "<script>alert('No hay existencias');
			history.back(1);</script>";
		}
	}
	if($lista=='4')
	{
	//$nopi=$_GET['cod']
		$tipo="";
		if(isset($_GET['tipo']))
		{
			$tipo=$_GET['tipo'];
		}
		else if(isset($_POST['tipo']))
		{
			$tipo=$_POST['tipo'];
		}	
		
		$consulta=$MySQLiconn->query("SELECT producto,lote, noop, noProceso from codigosbarras where noop='".$codigo."' and tipo='".$tipo."' order by noProceso desc limit 1");
		
		//echo $_POST['tipo'];
		$rowC=$consulta->fetch_array();
		echo "<p style='font-size:100%'>Producto: ".$rowC['producto']."</h4><br>";
		$datos=explode("-", $rowC['noop']);
		$j=0;
		$agregar='';
		foreach ($datos as $numpp) {	$j++; 	}
		$concatenacion=array();
		for($h=1;$h<=$j;$h++){
			$concatenacion[$h-1]=$datos[$h-1];
			$var[$h-1]=implode('-', $concatenacion);	
		}
		for ($i=0; $i < $j; $i++) { 
			if ($i=='0') { 		$agregar=$agregar." noop='".$var[$i]."'";	}
			else { 		$agregar=$agregar." OR noop='".$var[$i]."'";	}
		}

		$procesosN=$MySQLiconn->query("SELECT noProceso from codigosbarras where lote='".$rowC['lote']."' && ($agregar) GROUP by noProceso order by noProceso ASC");

		echo "<table class='table table-hover' <th class='sth'>Operador</th> <th class='sth'>Máquina</th>
		<th class='sth' style='width:15%;'>Fecha</th><th>Proceso</th><th class='sth' style='width:10%;'> Mtrs In</th> <th class='sth' style='width:10%;'>Mtrs Out</th><th class='sth' style='width:10%;'>NoOp</th>";

		$break='';
		if($procesosN->num_rows>0){
			$i=0;
			$banderinBS=0;
			while ($Count=$procesosN->fetch_array()) {
				$consultar=$MySQLiconn->query("SELECT codigo, proceso, noProceso, producto, noop from codigosbarras where noProceso='$i' && lote='".$rowC['lote']."' && ($agregar) order by noop DESC limit 1");
				$i++;
					if($consultar->num_rows==0)
				{
					$break=$codigoAnte;
				}
				while($row=$consultar->fetch_array()){
					$prod=$row['producto'];
					if ($row['noProceso']=='0') {	$codigoAnte=$row['codigo'];}
					else{
						$process=strtolower('pro'.$row['proceso']);
						$consulta1=$MySQLiconn->query("SELECT nombreparametro FROM juegoparametros  where identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='".$row['proceso']."') && numParametro='C'");
						$rowP=$consulta1->fetch_array();

						if ($process!='procorte') {

							if($process=='prosliteo')
							{
								$SEL=$MySQLiconn->query("SELECT codigo FROM codigosbarras WHERE noop='".$codigo."' and tipo='BS'");
								$fetc=$SEL->fetch_array();
								//$codiguin=$fetc['codigo'];
								$consultin=$MySQLiconn->query("SELECT operador, maquina, fecha,total from `prosliteo`  where noop='".$codigo."' Limit 1");
								$rowConss=$consultin->fetch_array();
								if($rowConss['total']=='0')
								{
									$SEL=$MySQLiconn->query("SELECT CONCAT(e.Nombre,' ',e.apellido) as nombre,b.longitud,b.fecha from baja_BS b inner join empleado e on e.numemple=b.empleado where b.codigo='".$codiguin."'");
									$fetc=$SEL->fetch_array();
									$empleadoBS=$fetc['nombre'];
									$unidadesBS=$fetc['longitud'];
									$fechaBS=$fetc['fecha'];
									$banderinBS=1;
								}

							}

							$consulta3=$MySQLiconn->query("SELECT operador, maquina, fecha from `$process`  where ".$rowP['nombreparametro']."='".$codigoAnte."' Limit 1");
							$rowCons3=$consulta3->fetch_array();

							$operador='Sin Datos';
							$maquina='Sin Datos';
							$fecha='Sin Datos';

							if($consulta3->num_rows>0){ 
								$operador=$rowCons3['operador'];
								$maquina=$rowCons3['maquina'];
								$fecha=$rowCons3['fecha'];
							}
						}
						else{
							if ($process=='procorte') {

								$consulta3=$MySQLiconn->query("SELECT operador, maquina, fecha from ".$process." where producto='".$row['producto']."' && rollo_padre='1' && ($agregar)");
								if($consulta3->num_rows>0){ 
									$rowCons3=$consulta3->fetch_array();
									$operador=$rowCons3['operador'];
									$maquina=$rowCons3['maquina'];
									$fecha=$rowCons3['fecha'];
								}
							}
						}

						$merma=$MySQLiconn->query("SELECT longin, longout from merma where codigo='".$codigoAnte."'");
						$rowM=$merma->fetch_array();

						$op=$MySQLiconn->query("SELECT noop,proceso from codigosbarras where codigo='".$codigoAnte."'");
						$rowNop=$op->fetch_array();
						

						echo '<tr>';
						
						echo '<td class="tab"><b style="font-size:100%">'; echo $operador; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $maquina; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $fecha; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $row['proceso']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowM['longin']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowM['longout']; echo '</b></td>
						<td class="tab"><b style="font-size:100%">'; echo $rowNop['noop']; echo '</b></td>

						</tr>';
						$codigoAnte=$row['codigo'];
					}
				}
			}
			
			echo "</table>";
			if($banderinBS)
			{
				
				echo '<table class="table table-hover"><th class="sth">Operador</th><th class="sth">Fecha</th><th class="sth">Metros</th>
				<tr><td class="tab"><b style="font-size:100%">'; echo $empleadoBS; echo '</b></td>
				<td class="tab"><b style="font-size:100%">'; echo $fechaBS; echo '</b></td>
				<td class="tab"><b style="font-size:100%">'; echo $unidadesBS; echo '</b></td>
				</tr></table';
			}
			$consultar=$MySQLiconn->query("SELECT referencia, producto, cdgEmbarque FROM ensambleempaques where codigo='".$codigoAnte."'");
			$ensamble=$consultar->fetch_array();
			echo "</div><div>";
			if($consultar->num_rows>0){
				$link='<a href="Buscador_Calidad.php?lista=3&codigo='.$ensamble['cdgEmbarque'].'"style="text-decoration: none;">'.$ensamble['cdgEmbarque'].'</a>';
				$paquete=$ensamble['referencia'];
				echo '</div><div><p style="font-size:120%;">Referencia: '; echo $paquete; echo '</h4>
				<p style="font-size:120%;">Embarque: '; echo $link; echo '</h4>';
			}
			else{
				if($banderinBS==1)
				{

					echo "<div class='alert alert-info alert-dismissible fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Producto terminado</strong> , Este disco ha sido retirado de producción.
					</div>
					";
				}
				else
				{
					echo "<div class='alert alert-info alert-dismissible fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Información</strong> , Este producto se encuentra en producción.
					</div>
					";
				}
			}
		}
		else{
		//echo "<script>alert('No hay existencias'); history.back(1);</script>";
		}
	}


}
?>
</div>
</center>
</div>
<?php } ?>