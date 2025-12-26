<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seguimiento | Calidad</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">

</head>
<body> 
<?php
include("../components/barra_lateral2.php");
include_once ("Controlador_Calidad/crud_seguimiento.php");
include("Controlador_Calidad/db_Producto.php");
//include("../css/barra_horizontal9.php");
$_SESSION['etiquetasInd']="";
?>

<div id="page-wrapper">
	<div class="container-fluid">

		<form method="GET" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Seguimiento de producto</b>
					
	</div>
	<div class="panel-body">
	<?php
	if(isset($_GET['Buscar']) && !empty($getRow))
	{
		?>


<div class="col-xs-3">
<label>Código</label>
<input id="peso" autofocus required type="text" name="peso" placeholder="Código de barras">
</div>
<button class="btn btn-default" type="submit" name="update">Actualizar</button>
<?php

	}
	else
	{
?>
<div class="col-xs-3">
<label for="Buscar">Código</label>
<input id="code" autofocus required class="form-control" type="text" name="Buscar" id="Buscar" placeholder="inserte el código del producto">
</div>
</div>
<button class="btn btn-info" style="float:right;">Buscar</button>
<?php
	}

	
?>
</form>
</div><hr><?php
if(isset($_GET['Buscar'])){
	if(!empty($INFO))
	{
		echo "<div class='panel panel-default'>

				<div class='panel-heading'><b class='titulo'>Seguimiento de producto</b></div>
	<div class='panel-body'>";

		?>
		<a  style="float:right;" onclick="return confirm('¿Deseas eliminar definitivamente el código  <?php echo ' '.$_GET['Buscar'];?>?'); "  title="Eliminar código de barras" href="?delet=<?php echo $_GET['Buscar'];?>&proceso=<?php echo $tableIn;?>"><img src="../pictures/deletProducto.png"></a>
			<p style="font:bold 20px Sansation Light" >Proceso: <?php echo strtoupper($tableIn)."";?> | Código:<?php echo $_GET['Buscar'];?></p>
			<?php
		while($row=$INFO->fetch_array())
		{
			if($row['COLUMN_NAME']!="total" && $row['COLUMN_NAME']!="id" && $row['COLUMN_NAME']!="rollo_padre" && $row['COLUMN_NAME']!="baja" && $row['COLUMN_NAME']!="shower" && $row['COLUMN_NAME']!="estado" && $row['COLUMN_NAME']!="idLote" && $row['COLUMN_NAME']!="noLote")
			{
				if($tableIn!="programado")
				{
					$SQL=$MySQLiconn->query("SELECT ".$row['COLUMN_NAME']." as dato from `pro".$tableIn."` where noop='$noop'");//Puede fallar
			$op=$SQL->fetch_array();
				}
				else
				{
					$SQL=$MySQLiconn->query("SELECT ".$row['COLUMN_NAME']." as dato from `lotes` where noop='$noop'");//Puede fallar
			$op=$SQL->fetch_array();
				}
			
		$string = $row['COLUMN_NAME'];
$string = strtolower(preg_replace('/([A-Z])/', ' $1', $string));
if($string=="lote" || $string=="bobina" || $string=="rollo")
{
	$string="Código de barras";
}
else if($string!="disco")
{

		?>
		
			<div style=""><p style="font:bold 20px Sansation Light" ><?php echo ucwords($string);?></p></div>
				<div style=""><p style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $op['dato'];?></p></div><hr>
				<?php
}
else if($string=="disco")
{	
		$Ban=$MySQLiconn->query("SELECT producto FROM codigosbarras WHERE codigo='".$op['dato']."' limit 1");
		$BS=$Ban->fetch_object();
		$nombreban=$BS->producto;
	?>
			<div style=""><p style="font:bold 20px Sansation Light" ><?php echo ucwords($string);?></p></div>
			<div style=""><p style="color:black;font:bold 20px Sansation ;margin-left: 10px;"><?php echo $op['dato']."  [".$nombreban."]";?></p></div><hr>
				<?php
}

}
}
echo "</div>";
echo "</div>";
}

else
{
	?>
	<div id="empaques">
	<p style="text-align:center;">El código ingresado no existe</p>
	</div>
	<?php
}
}?>
</center>
</body>
<script type="text/javascript">
	
</script>
</html>
	<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>

