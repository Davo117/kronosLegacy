	<?php
	//include('crud_Registro.php');

if(isset($_POST['save']))
{
$map=$_SESSION['codigo'];

$codigo=$MySQLiconn->real_escape_string($_POST[$map]);
$noopInter=parsearCodigo($codigo);
$arrayDatos=explode("|",$noopInter);
$noopInter=$arrayDatos[4];
$lote=$arrayDatos[2];
$tipo=$MySQLiconn->real_escape_string($_GET['tipo']);
if($tipo=="Termoencogible")
{
	$tipo="Termoencogible";
}
$procesoAnterior=substr($codigo,4,2);//Toma el numero de proceso del codigo para darlo de baja
	if($procesoAnterior==-1)
	{
		$procesoAnterior==0;//Si el proceso anterior es igual a -1,quiere decie que es programado
	}
$Type=$MySQLiconn->query("SELECT tipo from produccion where tipo='$tipo' and nombreProducto=(SELECT producto from codigosbarras where codigo='$codigo') group by tipo");
$rims=$Type->fetch_array();
 if (!empty($rims['tipo']))
 {

$SIK=$MySQLiconn->query("SELECT*,(SELECT descripcionProceso from juegoprocesos where numeroProceso=(SELECT numeroProceso-1 from juegoprocesos where descripcionProceso='".$_POST['comboProcesos']."' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where tipo='".$_GET['tipo']."')) and identificadorJuego=(SELECT juegoprocesos from tipoproducto where tipo='".$_GET['tipo']."') and baja=1 limit 1) as descripcionAnterior ,(SELECT nombreParametro from juegoparametros where identificadorJuego=(SELECT packParametros from procesos where descripcionProceso= (SELECT descripcionProceso from juegoprocesos where numeroProceso=(SELECT numeroProceso-1 from juegoprocesos where descripcionProceso='".$_POST['comboProcesos']."' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where tipo='".$_GET['tipo']."')) and identificadorJuego=(SELECT juegoprocesos from tipoproducto where tipo='".$_GET['tipo']."') limit 1)) and baja=1 and numParametro='C') as referencialAnterior from juegoparametros where identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='".$_POST['comboProcesos']."') and baja=1 and numParametro!='G'");//Se trae los parametros de información
$divs=$MySQLiconn->query("SELECT divisiones from procesos where descripcionProceso='".$_POST['comboProcesos']."'");
$div=$divs->fetch_object();
if($tipo!="BS")
{
	if($div->divisiones==1)
	{
		$SQL=$MySQLiconn->query("SELECT*,(select descripcion from producto where tipo='$tipo' and baja=1 and descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion=(SELECT producto from codigosbarras where codigo='$codigo'))) as comparador,(SELECT lote from codigosbarras where  codigo='$codigo') as lote,(SELECT divisiones from codigosbarras where  codigo='$codigo') as divisiones from `pro".$_POST['comboProcesos']."` where $map='$codigo'");//Trae los datos del codigo
	}
	else
	{
		$SQL=$MySQLiconn->query("SELECT*,(select descripcion from producto where tipo='$tipo' and baja=1 and descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion=(SELECT producto from codigosbarras where codigo='$codigo'))) as comparador,(SELECT lote from codigosbarras where  codigo='$codigo') as lote,(SELECT divisiones from codigosbarras where  codigo='$codigo') as divisiones from `pro".$_POST['comboProcesos']."` where $map='$codigo'");//Trae los datos del codigo
	}
}

else
{
	$SQL=$MySQLiconn->query("SELECT*,(select nombreBanda from bandaseguridad where baja=1 and nombreBanda=(SELECT identificadorBS from bandaspp where nombreBSPP=(SELECT producto from codigosbarras where codigo='$codigo'))) as comparador,(SELECT lote from codigosbarras where  codigo='$codigo') as lote,(SELECT divisiones from codigosbarras where  codigo='$codigo') as divisiones from `pro".$_POST['comboProcesos']."` where $map='$codigo'");//Trae los datos del codigo

}


$row=$SQL->fetch_object();
if($div->divisiones==1)
{
	$DIVA=$MySQLiconn->query("SELECT count(noop) as inputs from `pro".$_POST['comboProcesos']."` where noop like '".$row->noop."-%' and $map='$codigo'");
}
else
{
$DIVA=$MySQLiconn->query("SELECT count(noop) as inputs from `pro".$_POST['comboProcesos']."` where noop like '".$row->noop."' and $map='$codigo'");
}
$In=$DIVA->fetch_object();

$_SESSION['etiquetasInd']=$codigo."|".$In->inputs;
$_SESSION['noop']=$row->noop;
$producto=$row->producto;
//Esta consulta le envia al crud el proceso anterior para hacer la baja de estatus
if($tipo!="BS")
{
	$Mode=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where numeroProceso='".$procesoAnterior."' and  identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion='".$row->producto."')))");
}
else
{
	$Mode=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where numeroProceso='".$procesoAnterior."' and  identificadorJuego=(SELECT juegoprocesos from tipoproducto where alias='BS') and baja=1");
}
$Prem=$Mode->fetch_object();
$ant="";
$referencialAnterior="";
$_SESSION['procesoAnterior']=$Prem->descripcionProceso;

?>
	<body style="overflow:hidden;">

	<div class="formulariosProducion">
		<p style="padding-left:10px;margin-right:40px; font:bold 20px Sansation">Información</p>
		<div style="background:#D7DDE0;border-radius:5px;padding:10px;margin-right:10px">
<p style="float:right;margin-right:40px; font:bold 30px Sansation Light">NoOP:&nbsp;<b style="font:bold 35px Sansation"><?php echo $row->noop;?></b></p>
<?php
while($ris=$SIK->fetch_object())
{
$pam=$ris->nombreparametro;
$ant=$ris->descripcionAnterior;
$referencialAnterior=$ris->referencialAnterior;

if($pam==$map)
{

	?>

		<p style="font:bold 15px Sansation Light"><?php echo $ris->leyenda;?>:&nbsp; <b style="font:bold 18px Sansation";><?php echo $row->lote;?></b></p>
		
		<?php
}
else if($pam!="disco")
{

	?>


		<p style="font:bold 15px Sansation Light"><?php echo $ris->leyenda;?>:&nbsp; <b style="font:bold 18px Sansation";><?php echo $row->$pam;?></b></p>
		
		<?php
	}
	else if($pam=="disco")
	{
		$Ban=$MySQLiconn->query("SELECT producto FROM codigosbarras WHERE codigo='".$row->$pam."' limit 1");
		$BS=$Ban->fetch_object();
		$nombreban=$BS->producto;
		?>
			<p style="font:bold 15px Sansation Light"><?php echo $ris->leyenda;?>:&nbsp; <b style="font:bold 18px Sansation";><?php echo $row->$pam."  -".$nombreban."";?></b></p>
		<?php
	}
	}
	?>
		<p style="font:bold 15px Sansation Light">Diseño:&nbsp;<b style="font:bold 18px Sansation"><?php echo $row->comparador;?></b></p>
		<p style="font:bold 15px Sansation Light">Impresión:&nbsp;<b style="font:bold 18px Sansation"><?php echo $row->producto;?></b></p>
		<?php
		if($procesoAnterior!=0)
		{


		$validacion=$MySQLiconn->query("SELECT nombreparametro,leyenda from juegoparametros WHERE identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='".$ant."')and numParametro='G' || nombreparametro='unidades' || nombreparametro='longitud'");
	
		$MySQLiconn->query("INSERT into  merma(codigo,producto,proceso)values('$codigo','".$producto."','".$_POST['comboProcesos']."')");
		$uni=1;
		$lon=1;

		while($va=$validacion->fetch_object())
		{

			$dest=$MySQLiconn->query("SELECT ".$va->nombreparametro." from `pro".$ant."` inner join codigosbarras on `pro".$ant."`.$referencialAnterior=codigosbarras.codigo where `pro".$ant."`.noop='".$row->noop."' and codigosbarras.lote='$lote'");
			while($rowDest=$dest->fetch_array()){
			$pamActual=$va->nombreparametro;
			$suma=$rowDest[$pamActual];
			
			if($pamActual=="unidades"){
				if($uni==1)
				{
				?>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light"><?php echo $pamActual;?>:&nbsp;<b style="font:bold 18px Sansation"><?php echo $suma;?></b></p>

			<?php
		}
			$uni=0;
			}	
			else if($pamActual=="longitud" || $pamActual=="Longitud"){
				if($lon==1)
				{
				?>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light"><?php echo $pamActual;?>:&nbsp;<b style="font:bold 18px Sansation"><?php echo $suma;?></b></p>

			<?php
		}
			$lon=0;
			}	
			else 
			{
				?>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light"><?php echo $pamActual;?>:&nbsp;<b style="font:bold 18px Sansation"><?php echo $suma;?></b></p>

			<?php
			}
			
			if($pamActual=="longitud")
			{
				$pamActual="longIn";
				$MySQLiconn->query("UPDATE  merma set $pamActual='".$suma."' where codigo='$codigo'");
			}
			if($pamActual=="unidades")
			{
				$pamActual="unidadesIn";
				$MySQLiconn->query("UPDATE  merma set $pamActual='".$suma."' where codigo='$codigo'");
			}
			/*if($pamActual=="bandera")//Aun no se sabe como se relacionan las banderas asi que esta parte esta inconclusa
			{
				$pamActual=="banderas";
				$MySQLiconn->query("UPDATE  merma set $pamActual='".$suma."'");
			}*/
			
		
	}
		
		}
	}

	else
	{
		$validacion=$MySQLiconn->query("SELECT longitud,anchuraBloque,peso,unidades from lotes where noop='".$row->noop."' and referenciaLote='$lote'");
		while($va=$validacion->fetch_array())
		{

			?>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light">Longitud:&nbsp;<b style="font:bold 18px Sansation"><?php echo $va['longitud'];?></b></p>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light">Anchura del bloque:&nbsp;<b style="font:bold 18px Sansation"><?php echo $va['anchuraBloque'];?></b></p>
			<p style="padding:3px;background:#7094CC;border-radius:3px;margin-right:10px;margin-bottom:5px;float:right;font:bold 15px Sansation Light">Peso:&nbsp;<b style="font:bold 18px Sansation"><?php echo $va['peso'];?></b></p>

			<?php
			$MySQLiconn->query("INSERT into  merma (longIn,unidadesIn,codigo,producto,proceso)
								values('".$va['longitud']."','".$va['unidades']."','$codigo','".$row->producto."','".$_POST['comboProcesos']."')");
		}
	}

		?>
</div>
	</div>

	<br>
	<?php
//Busqueda de los campos correspondientes al proceso
	$validacion=$MySQLiconn->query("SELECT nombreparametro from juegoparametros WHERE identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='".$_POST['comboProcesos']."')and numParametro='G'");
	$validar=0;
	$validarFormulario=$validacion->num_rows;
	$inputs=$In->inputs;//$inputs=$row->inputs-1;

if($validarFormulario==0)
{
	$validar=1;
}




		while($va=$validacion->fetch_object())
		{
if(empty($va->nombreparametro))
{
	$validar=1;
}
			$dest=$MySQLiconn->query("SELECT ".$va->nombreparametro." from `pro".$_POST['comboProcesos']."` where noop like '".$row->noop."%' and $map=".$codigo."");


			while($rowDest=$dest->fetch_array()){
			$pamActual=$va->nombreparametro;
			

			$suma=(double)$rowDest[$pamActual];
			$validar=$validar+$suma;//SE asegura de que los parámetros de este proceso hayan sido registrados
			
			$validar=(double)$validar;//Si no hay nada

		}
		}
	if($validar==0 || isset($_POST['codForanius'])){
	//Esta colcando la validacion,si esos datos ya se registraron o no,de lo contrario solo mostrará el codigo de barras
		?>
<div class="row">
	<div class="registros"">

<form   METHOD="POST" ACTION="Controlador_produccion/crud_Registro.php?inputs=<?php echo $inputs.'|'.$codigo."|".$_POST['comboProcesos']."|".$map."|".$Prem->descripcionProceso."|".$row->noop."|$tipo";?>">
	<?php
	$contador=0;
	while($contador<$inputs)
	{
 $bobina=$contador+1;
	?>

<div class="col-xs-6">
<h4 class="ordenMedio"><?php echo $map." ".$bobina;?></h4>

<?php
    $rest = $MySQLiconn->query("SELECT *from juegoparametros where identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='".$_POST['comboProcesos']."') and baja=1 and numParametro='G'");

?>

	<?php
while($rex = $rest->fetch_array()){
    $valiu=$rex['nombreparametro'];
    if($rex['requerido']==1)
    {
    	
        ?>

<div class="col-xs-auto" id="div_" style="clear:right; float:left;margin-right:5px">
<label for="<?php echo $rex['nombreparametro'].$contador; ?>"><?php echo $rex['leyenda'] ?></label>
<input onkeypress="return numeros(event)" class="form-control"   autofocus required type="text" name="<?php echo $rex['nombreparametro'].$contador; ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu].$contador;?>"
    size="20" placeholder="<?php echo $rex['placeholder'] ?>" >
</div>

<?php
    }
    else
    {
?>
<div class="col-xs-auto" id="div_" style="float:left;margin-right:5px">
<label for="<?php echo $rex['nombreparametro'].$contador; ?>"><?php echo $rex['leyenda'] ?></label>
<input onkeypress="return numeros(event)" class="form-control"  autofocus type="text" name="<?php echo $rex['nombreparametro'].$contador; ?>" value="<?php if(isset($_GET['edit'])) echo $getROW[$valiu].$contador;?>"
    size="20" placeholder="<?php echo $rex['placeholder'] ?>" >
</div>

<?php
}
}
?><br><?php
$contador++;
}
?>
</div>
</div>
<button class="btn btn-default" style="float:right;" type="submit" name="save2">Guardar</button>
</form>
</div>
</body>
	<?php
}
else
{
	?>
	<div class="registros"">

<form  target="_blank" METHOD="POST" ACTION="Controlador_produccion/crud_Registro.php?inputs=<?php echo $_POST['comboProcesos'].'|'." ";?>">

<br>
<button style="padding-bottom:10px;height:34px;" type="submit" name="codes"><img src="../pictures/barcodes.png" title="Generar codigo de barras"></button>
</form>
<a title="Registrar otro código" href="Produccion_Registro.php?tipo=<?php echo $tipo?>&proceso=<?php echo $proceso;?>"><img src="../pictures/sub_black_prev.png"></a>
</div>
</body>
	<?php
}
}
else
{
	?>
	<body style="overflow:hidden;">
	<div>
		<p>Este código no coincide con <?php echo $tipo;?> o aún no ha sido generado</p>
		
	</div>
</body>
	<?php
}
}
else
{
	?>
	<body style="overflow:hidden;">
	<div>
		<p>Esto sera un tablero algun día,o quizá no</p>
	</div>
</body>
	<?php
}

?>
<script language="JavaScript">
  function redireccionar() {

    setTimeout('history.back()',10000);
  }
  </script>