<?php
ob_start();
session_start();
include ("../Database/db.php");
include ("funciones/crud/crudCalendarios.php");
if (isset($_GET['finde'])) {$_SESSION['finde']=$_GET['finde'];}
if (isset($_GET['nulos'])) {$_SESSION['nulos']=$_GET['nulos'];}

if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	 
	} else {
	  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	}
	$now = time();
	if($now > $_SESSION['expire']) {
	session_destroy();
	echo '<script language="javascript">alert("Tu sesión caducó");</script>'; 
	echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
	exit;
	}
include("../css/barra_horizontal5.php");
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Entregas(Producción) | Grupo Labro</title>
 <link rel="stylesheet" href="../css/produccionCss.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="../css/stylees.css" type="text/css" media="screen"/>
<link rel="stylesheet" type="text/css" href="../css/Stylish.css" />
<link rel="stylesheet" type="text/css" href="../css/produccionCss.css" />

<style type="text/css">


* {margin:0; padding:0;}
	ul {-webkit-box-shadow: 0 0 0px ;
  -moz-box-shadow: 0 0 0px ;
  box-shadow: 0 0 0px ;}

	#contenedor {background-color: #CDCDCD; margin:10px auto; padding:10px 40px; width:700px; border-radius:15px;}
	
	#calendario {width:700px; height: 380px; font-style: Sansation;}
	
	#calendario div.tabla {position:relative; width:700px; height:400px;  padding-bottom:26px;}
	#calendario p {display:none;}
	#calendario ul {list-style:none;}
	#calendario ul span {display:none;}
	#calendario table {border:1px solid #bbb; border-collapse: separate; border-spacing: 0; border-width:1px 0 0 1px; height: 250px; width:700px;}
	#calendario table caption span {display:none;}
	#calendario table thead th {background-color:#eee; border:1px solid #bbb; border-width:0 1px 1px 0 ; color:#E09F81;}
	#calendario table tbody td {border:1px solid #bbb; border-width:0 1px 1px 0 ; text-align:center; font-size: 18px;}
	#calendario table tbody td .enlace {display:block;}
	#calendario table tbody td.diaNulo {color:#ccc; }
	
	#calNavMeses li:hover {background-color: transparent;}
	#calNavMeses li {position:absolute; top:0; background-color:transparent;}
	#calNavMeses li a {display:block; height:30px; width:30px;}
	#calNavMeses. li.anterior li.siguente a{background-color: none;  }
	#calNavMeses li.anterior a {background:url(funciones/img/go-previous.png) no-repeat 0 0;}
	#calNavMeses li.siguiente a {background:url(funciones/img/go-next.png) no-repeat 0 0;}
	
	#calNavYears  li:hover {font-size: 70px;}
	#calNavYears li {bottom:0; position:absolute; background-color: transparent; color:#610B0B; transform: translate(0%, 60%);}
	

	#calendario ul li.anterior {left:0;}
	#calendario ul li.siguiente {right:0;}
	
	#contenedor #calendario a {color:#C04000; text-decoration:none; padding: 10px;  }
	#contenedor #calendario td:hover {color:#4D4DFF; background-color:#848482; }
	#contenedor #calendario table tbody td.fechaHoy  {background-color:#848482; color:#585858;}
	#contenedor #calendario table tbody td.fechaSeleccionada  {background-color:#565051; color:#fff;}

	#opciones { margin:20px 0; padding:10px 0;}
	#opciones p {font-size:14px;}
	

	#opciones li.holi {  background-color: transparent; transform: translate(-50%, 150%);}
 	
 	#opciones li.holin {  background-color: transparent; transform: translate(-30%, 150%);}
 
 	#opciones caption.holo{font-style: Sansation Regular; font-size: 25px;}
	
	#commons {font-size:11px; margin-top:32px; padding-bottom:16px; text-align:center;}
	
	#contenedor #opciones a, #contenedor #commons a { text-decoration:none;}
	#contenedor #opciones a:hover, #contenedor #commons a:hover {color:#045FB4; font-size: 17px;}
  
	
</style>
</head>
<body>
<br>
<br>
<div id="contenedor">
<p style="font-size:30px; color:#610B0B; font-family: Sansation; text-align: center;"> Calendario de Entregas </p>
<?php


	
function calendario ($year,$mes,$finDeSemana=1,$mostrarDiasNulos=1,$nivelH=2) {
	
	include ("../Database/db.php");
	if (strlen($year)!=4) {$year=date('Y');}
	if (($mes<1 or $mes>12) or (strlen($mes)<1 or strlen($mes)>2)) {$year=date('n');}
	
	// Listados: días de la semana, letra inicial de los días de la semana, y meses
	$dias = array('Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado','Domingo');
	$diasAbbr = array('Lun','Mar','Mie','Jue','Vie','Sab','Dom');
	$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	// Se sacan valores que se utilizarán más adelante
	$diaInicial = mktime(0,0,0,$mes,1,$year);  // Primer día del mes dado
	$diasNulos = (date("N",$diaInicial))-1; // Con 'N' la semana empieza en Lunes. Con 'w', en domingo
		if($diasNulos<0){$diasNulos = 7-abs($diasNulos);}
	$diasEnMes = date("t",$diaInicial); // Número de días del mes dado
	
	// Se abre la capa contenedora y se genera el encabezado del bloque de calendario
	print( '<div id="calendario">');
	
	
	// Párrafos con la fecha actual y la fecha seleccionada

	print('<p>Fecha actual: '.date('j').' de '.$meses[(intval(date('n'))-1)].' de '.date('Y').'</p>');
	print('<p>Fecha seleccionada: ');
	if (isset($_GET['dia'])) {print( ''.$_GET['dia'].' de ');	} // El día solo sale si se ha definido previamente en el parámetro 'dia' de la URL

	print(''.$meses[($mes-1)].' de '.$year.'</p>');
	print( '<div class="tabla">');
	
	
	// Enlaces al mes anterior y al siguiente
	print( '<p>Navegación por meses:</p>');
	print( '<ul id="calNavMeses">');
	$enlaceAnterior1 = mktime(0,0,0,($mes-1),1,$year);
	$mesAnterior = date('n',$enlaceAnterior1);
	$yearMesAnterior = date('Y',$enlaceAnterior1);
	$enlaceSiguiente1 = mktime(0,0,0,($mes+1),1,$year);
	$mesSiguiente = date('n',$enlaceSiguiente1);
	$yearMesSiguiente = date('Y',$enlaceSiguiente1);
	print( '<li class="anterior"><a href="?dia=1&amp;mes='.$mesAnterior.'&amp;ano='.$yearMesAnterior.'"><span>Mes anterior ('.$meses[($mesAnterior-1)].')</span></a></li>');
	print( '<li class="siguiente"><a href="?dia=1&amp;mes='.$mesSiguiente.'&amp;ano='.$yearMesSiguiente.'"><span>Mes siguiente ('.$meses[($mesSiguiente-1)].')</span></a></li>');
	print( '</ul>');

	// Enlaces al año anterior y al siguiente
	print( '<p>Navegación por años:</p>');
	print( '<ul id="calNavYears">');
	$enlaceAnterior2 = mktime(0,0,0,$mes,1,($year-1));
	$yearAnterior = date('Y',$enlaceAnterior2);
	$enlaceSiguiente2 = mktime(0,0,0,$mes,1,($year+1));
	$yearSiguiente = date('Y',$enlaceSiguiente2);
	print( '<li class="anterior"><a href="?dia=1&amp;mes='.$mes.'&amp;ano='.$yearAnterior.'"><span>Año anterior (</span>'.$yearAnterior.'<span>)</span></a></li>');
	print( '<li class="siguiente"><a href="?dia=1&amp;mes='.$mes.'&amp;ano='.$yearSiguiente.'"><span>Año siguiente (</span>'.$yearSiguiente.'<span>)</span></a></li>');
	print( '</ul>');

	// Se abre la tabla que contiene el calendario
	print( '<table>');
	// Título mes-año (elemento CAPTION)
	$mesLista = $mes-1;
	print( '<caption class="holo">'.$meses[$mesLista].' del '.$year.'</caption>');
	
	// Se definen anchuras en elementos COL
	$cl=0; $anchoCol=100/7; while ($cl<7) {print( '<col width="'.$anchoCol.'%" />'); $cl++;}
	
	// Fila de los días de la semana (elemento THEAD)
	print( '<thead><tr>');$d=0;
	while ($d<7) {print( '<th scope="col" abbr="'.$dias[$d].'">'.$diasAbbr[$d].'</th>');$d++;}
	print( '</tr></thead>');
	
	// Se generan los días nulos (días del mes anterior o posterior) iniciales, el TBODY y su primer TR
	print( '<tbody>');
	if ($diasNulos>0) {print( '<tr>');} // Se abre el TR solo si hay días nulos
	if ($diasNulos>0 and $mostrarDiasNulos==0) {print( '<td class="nulo" colspan="'.$diasNulos.'"></td>');} // Se hace un TD en blanco con el ancho según los día nulos que haya
	if ($mostrarDiasNulos==1) { // Generación de los TD con días nulos si está activado que se muestren
		$dni=$diasNulos;$i=0;
		while ($i<$diasNulos) {
			$enSegundosNulo = mktime(0,0,0,$mes,(1-$dni),$year);
			$dmNulo = date('j',$enSegundosNulo);
			$idFechaNulo = 'cal-'.date('Y-m-d',$enSegundosNulo);
			print( '<td id="'.$idFechaNulo.'" class="diaNulo"><span class="dia"><span class="enlace">'.$dmNulo.'</span></span></td>');
			$dni--;
			$i++;
		}
	}
	
	// Se generan los TD con los días del mes
	$dm=1;$x=0;$ds=$diasNulos+1;

	while ($dm<=$diasEnMes) {

		if(($x+$diasNulos)%7==0 and $x!=0) {print( '</tr>');} // Se evita el cierre del TR si no hay días nulos iniciales
		if(($x+$diasNulos)%7==0) {print( '<tr>');$ds=1;}
		$enSegundosCalendario = mktime(0,0,0,$mes,$dm,$year); // Fecha del día generado en segundos
		$enSegundosActual = mktime(0,0,0,date('n'),date('j'),date('Y')); // Fecha actual en segundos
		$enSegundosSeleccionada = mktime(0, 0, 0, $_GET['mes'], $_GET['dia'], $_GET['ano']);
		
		 // Fecha seleccionada, en segundos
		$idFecha = 'cal-'.date('Y-m-d',$enSegundosCalendario);
		
		// Se generan los parámetros de la URL para el enlace del día
		$link_dia = date('j',$enSegundosCalendario);
		$link_mes = date('n',$enSegundosCalendario);
		$link_year = date('Y',$enSegundosCalendario);
		
		// Clases y etiquetado general para los días, para día actual y para día seleccionado
		$claseActual='';$tagDia='span';
		if ($enSegundosCalendario==$enSegundosActual) {$claseActual=' fechaHoy';$tagDia='strong';}
		if ($enSegundosCalendario==$enSegundosSeleccionada and isset($_GET['dia'])) {$claseActual=' fechaSeleccionada';$tagDia='em';}
		if ($enSegundosCalendario==$enSegundosActual and $enSegundosCalendario==$enSegundosSeleccionada and isset($_GET['dia'])) {$claseActual=' fechaHoy fechaSeleccionada';$tagDia='strong';}
		
		// Desactivación de los días del fin de semana
		if (($ds<6 and $finDeSemana==0) or $finDeSemana!=0) { // Si el fin de semana está activado, o el día es de lunes a viernes
			$tagEnlace='a';
			$fechEntrega=$link_year.'-'.$link_mes.'-'.$link_dia;
			$atribEnlace='?dia='.$link_dia.'&amp;mes='.$link_mes.'&amp;ano='.$link_year;
		} if ($ds>5 and $finDeSemana==0) { // Si el fin de semana está desactivado y el día es sábado o domingo
			$tagEnlace='span';
			$atribEnlace='';
			$paramFinde='0';
		}
		// Con las variables ya definidas, se crea el HTML del TD

		//**************CODIGO PARA IMAGENES DENTRO DEL TD DE LOS DIAS TRABAJADOS********************
		
		if ($link_dia<'10') {	$dialink='0'.$link_dia; } 	else { $dialink=$link_dia; }

		if ($link_mes<'10') {	$meslink='0'.$link_mes; }   else { $meslink=$link_mes;}

 		$fechaEmb=$link_year.'-'.$meslink.'-'.$dialink;
	 $SQL =$MySQLiconn->query("SELECT embarqueConfi FROM $confirProd where embarqueConfi='".$fechaEmb."'");
 		//la variable $dm contiene el numero del dia
	 	$filtro=$dm;
 		$rowers=$SQL->fetch_array();
 		if ($rowers['embarqueConfi']==$fechaEmb) {
 			$filtro='<a style=" background-color: transparent; color: transparent;" href="funciones/pdf/EntregasPDF.php?dsd='.$fechEntrega.'&hst='.$fechEntrega.'&suc=--&prod=--" onclick="return confirm("Gracias por hacer clic"); " ><IMG src="funciones/img/pdf.png" title="Descargar" /></a>';
 		}

 		//*************************** FIN CODIGO **********************************

		print( '<td id="'.$idFecha.'" class="'.calendarioClaseDia($ds).$claseActual.'"><'.$tagDia.' class="dia"><'.$tagEnlace.' class="enlace" href="'.$atribEnlace.'">'.$filtro.'</'.$tagEnlace.'></'.$tagDia.'></td>');
		$dm++;$x++;$ds++;
	}

	// Se generan los días nulos finales
	$diasNulosFinales = 0;
	while((($diasEnMes+$diasNulos)%7)!=0){$diasEnMes++;$diasNulosFinales++;}
	if ($diasNulosFinales>0 and $mostrarDiasNulos==0) {print( '<td class="nulo" colspan="'.$diasNulosFinales.'"></td>');} // Se hace un TD en blanco con el ancho según los día nulos que haya (si no se activa mostrar los días nulos)
	if ($mostrarDiasNulos==1) { // Generación de días nulos (si se activa mostrar los días nulos)
		$dnf=0;
		while ($dnf<$diasNulosFinales) {
			$enSegundosNulo = mktime(0,0,0,($mes+1),($dnf+1),$year);
			$dmNulo = date('j',$enSegundosNulo);
			$idFechaNulo = 'cal-'.date('Y-m-d',$enSegundosNulo);
			print( '<td id="'.$idFechaNulo.'" class="diaNulo"><span class="dia"><span class="enlace">'.$dmNulo.'</span></span></td>');
			$dnf++;
		} 	
	}
	// Se cierra el último TR y el TBODY
	print( '</tr></tbody>');
	// Se cierra la tabla
	print( '</table>');
	// Se cierran la capa de la tabla y la capa contenedora
	print( '</div>');
	print( '</div>');	
	// Se devuelve la variable que contiene el HTML del calendario	
}
function calendarioClaseDia ($dia) {
	switch ($dia) {
		case 1: $clase = 'lunes semana'; break;
		case 2: $clase = 'martes semana'; break;
		case 3: $clase = 'miercoles semana'; break;
		case 4: $clase = 'jueves semana'; break;
		case 5: $clase = 'viernes semana'; break;
		case 6: $clase = 'sabado finDeSemana'; break;
		case 7: $clase = 'domingo finDeSemana'; break;
	}
	return $clase; 
}

$yearCalendario = date('Y');if (isset($_GET['ano'])) {$yearCalendario = $_GET['ano'];} // Si no se define año en el parámetro de URL, el año actual
$mesCalendario = date('n');if (isset($_GET['mes'])) {$mesCalendario = $_GET['mes'];} // Si no se define mes en el parámetro de URL, el mes actual
$finDeSemana = 1;if (isset($_SESSION['finde'])) {$finDeSemana = $_SESSION['finde'];} // Días de fin de semana activados: '1' para activado, '0' para desactivado (se predetermina a 1)
$diasNulos = 1;if (isset($_SESSION['nulos'])) {$diasNulos = $_SESSION['nulos'];} // Los días que son de otros meses pero que coinciden con las semanas de inicio y final del mes actual: '1' para mostrarlos, '0' para dejarlos ocultos (el TD lleva un atributo class="diaNulo" para poder darle otro color y van sin enlace)
$nivelH = 2; // Nivel para el encabezado del bloque de calendario, con valor entre '1' y '6'. Se predetermina en '2'.
echo calendario($yearCalendario,$mesCalendario,$finDeSemana,$diasNulos,$nivelH);
?>

<div id="opciones">
	<ul>
<?php
if (isset($_GET['mes']) and isset($_GET['ano'])) {
	$paramFecha = '&amp;dia='.$_GET['dia'].'&amp;mes='.$_GET['mes'].'&amp;ano='.$_GET['ano'].'';
} ?>
</ul>
</div></div>
<center>
<div  id="formularius" style="width:80%;overflow:hidden;">
<form >

<div class="fields">
<p>Sucursal:</p>
<select class="combosMenores" name="sucursal" >
<option value="--">[TODAS]</option>
<?php
//esta variable es una bandera para la ordenacion de campos en el combo box
$empresa="r";
//Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $tablasucursal where bajasuc=1 order by idsuc");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	// Comparas tu variable con la empresa
	if($empresa != $row['idcliFKS']) {
    	// Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        $empresa = $row['idcliFKS'];
		//se agrega dentro del if la etiqueta de optgroup para inicializar el orden ?>
		<optgroup label="<?php print($empresa); ?>">
		<?php 
	}?>
	<option value="<?php echo $row ['idsuc'];?>"><?php echo $row ['nombresuc'];?></option>
	<?php 
} ?> 
</optgroup>
</datalist>
</select>
</div>

<div class="fields">
<p>Producto:</p>
<select  class="combosMenores" name="productos" id="productos">
  
<option value="--">[TODOS]</option>
  <?php  
$primera = $MySQLiconn->query("SELECT prodcliReqFK FROM $reqProd group by prodcliReqFK");
while($row1 = $primera->fetch_array()){
	$segunda = $MySQLiconn->query("SELECT id, descripcionImpresion, descripcionDisenio FROM $impresion where codigoCliente='".$row1['prodcliReqFK']."' && baja=1");

	$empresa2='ssss';
	while($row2 = $segunda->fetch_array()){ //mientras se tengan registros:
		$agrupar = $MySQLiconn->query("SELECT IdentificadorCliente from productoscliente where nombre='".$row1['prodcliReqFK']."' and baja=1");
    	while ($rowAgrupar = $agrupar->fetch_array()){
    		// Comparas tu variable con la empresa
    		if($empresa2 != $rowAgrupar['IdentificadorCliente']) { 
    			// Ahora habra que comparar con esta empresa cada que de vuelta los datos 
        		$empresa2 = $rowAgrupar['IdentificadorCliente'];
				//se agrega dentro del if la etiqueta de optgroup para inicializar el orden
        		//no te olvides de la etiqueta de cierre ?>
        		<optgroup label="<?php print($empresa2); ?>">
        		<?php //cerramos el if anterior:
      		}
    	}?>
    	<option value="<?php echo $row2 ['id'];?>"><?php echo $row2 ['descripcionDisenio']." | " .$row2['descripcionImpresion'];?>  </option>
    	<?php  
	}
} ?> 
</optgroup>
</datalist>
</select> 
</div>

<?php 
$fecha = $_SERVER["QUERY_STRING"];

$arrayFecha = explode("=", $fecha, 3);
$array1=$arrayFecha[1];
$array2=$arrayFecha[2];

$array11= explode("&mes", $array1, 2);
$diacombo=$array11[0]; //trae el dia seleccionado
if ($diacombo<'10') { 	$diacombo= '0'.$diacombo; }

$array21= explode("&ano", $array2, 2);
$mescombo=$array21[0]; //trae el mes seleccionado
if ($mescombo<'10') { 	$mescombo= '0'.$mescombo; }

$arrayFecha1 = explode("dia=".$array11[0]."&mes=".$array21[0]."&ano=", $fecha, 2);
$aniocombo=$arrayFecha1[1]; //trae el año seleccionado

$concat= $aniocombo.'-'.$mescombo.'-'.$diacombo; 
 ?>

<div class="fields">
<p>Desde:</p>
<input type="date" class="fecha" value="<?php  echo $concat; ?>" name="fechaDesde">
</div>

<div class="fields">
<p>Hasta:</p>
<input type="date" class="fecha" value="<?php  echo $concat; ?>" name="fechaHasta">
</div>

<button type="submit" name="download">Descargar</button>
<br>

</form>
</div>
</center>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); ?>