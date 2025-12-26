<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE>
<html>
<head>
<title>Inventario | Almacen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
</head>
<body>



<?php
$codigoPermiso='14';

include("../components/barra_latera_almacen.php");
include ("controlador_almacen/db_materiaPrima.php");
include("controlador_almacen/crud_elementos.php");
include("conexionobelisco.php");


if(isset($_POST['n_clasificaciones']))
{
	if($_POST['n_clasificaciones']>0)
	{
		$_SESSION['lstclas']++;
	}
	else
	{
		//$lstclas= array();
		$_SESSION['lstclas']=1;
	}
}
else
{
	$_SESSION['lstclas']=0;
}


?>
<div id="page-wrapper">
	<div class="container-fluid">

<form  method="post" enctype="multipart/form-data" name="formulary" id="formulary" role="form">
<div class="panel panel-default">
	<div class="panel-heading"><b class="titulo">Inventario de materia prima</b></div>
		<div class="panel-body">
			<div class="col-xs-2">
				<label for="">Inicio</label>
				<input class="form-control" type="date">
			</div>	
			<div class="col-xs-2">
				<label>Hasta</label>
				<input class="form-control" type="date">
			</div>
			<div class="col-xs-2">
				<label for="">Movimiento</label>
				<select class="form-control" name="" id="">
					<option value="">--</option>
					<option value="">Entradas</option>
					<option value="">Salidas</option>
				</select>
			</div>
			<div class="col-xs-2">
				<label for="">Familia</label>
				<select class="form-control" name="" id="">
					<option value="">--</option>
					<option value="">Tintas</option>
					<option value="">Acabados</option>
					<option value="">Aditivos</option>
					<option value="">Solventes</option>
					<option value="">Adhesivos</option>
					<option value="">Sustratos</option>
					<option value="">Indirectos</option>
				</select>
			</div>
			<div class="col-xs-2">
				<label for="">Proveedor</label>
				<select required="true"  name="cdgproveedor" autofocus="true"  id="cdgproveedor"  class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="--Selecciona el proveedor">
					<?php $resultado = sqlsrv_query($SQLconn, "SELECT CRAZONSOCIAL as proveedor,CIDCLIENTEPROVEEDOR as codigo FROM ADMCLIENTES WHERE CESTATUS=1 and CTIPOCLIENTE=3");?>
					<option value="">--</option>
					<?php 
					while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){
						if($_POST['cdgproveedor']==$row['codigo']){
						?>
							<option selected data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
						<?php
						}	
						else{
						?> 
							<option data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
						<?php 
						}
						} 
						?>
				</select>
			</div>
			<div class="col-xs-2">
				<label for="">Producto</label>
				<select required="true"  name="cdgproducto" id="cdgproducto" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Selecciona un producto" <?php if (isset($_GET['edit'])) {
  echo "disabled"; }?>>
    <?php
  
  $obel=$mysqli->query("SELECT  producto as id, id as codigo from obelisco.productosCK");
?>
<option value="" >--</option>


<?php
while ($mySQL=$obel->fetch_array())  
{
  $resultado = sqlsrv_query($SQLconn, "SELECT CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO='".$mySQL['id']."'");

  $row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

  $myS=$mysqli->query("SELECT hascode FROM obelisco.productosCK WHERE producto='".$row['id']."'");
  $rum=$myS->fetch_assoc();
  if($rum['hascode']==0)
  {

?>

<option data-tokens="<?php echo $row['nombreElemento'].$mySQL['codigo'];?>" value="<?php echo $mySQL['id'];?>"><?php echo $row['nombreElemento']; ?></option >

<?php 
  }
} 
?> 
				</select>
			</div>
		</div> 


<!-- botones actulizar y guardar -->
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button style="float:right;" class="btn btn-default" type="submit" name="save">Generar Reporte EXCEL</button>
	<?php
}
?>
</form>



</div>
<!--
<a class="btn btn-default" href="controlador_almacen/cargaProductos.php" title="Cargar productos"><img src="../pictures/excel.png"></a>-->
<?php 
if(isset($_POST['checkTodos']))
{?>
	<h4 class="ordenMedio">Elementos Activos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("controlador_almacen/showElementos.php");
?>
</table>
	<h4 class="ordenMedio">Elementos Inactivos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("controlador_almacen/showElementos_bajas.php");
?>
</table>
<?php
}


else{?>
<h4 class="ordenMedio">Elementos Activos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("controlador_almacen/showElementos.php");
?>
</table>
<?php
}
?>
</form>
</div>
</center>
</body>
	 <script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>

