<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE>
<html>
<head>
<title>Elementos | Materia Prima</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">
<script>
x = 0;
$(document).ready(function(){
    $(window).resize(function(){
        alert('');
    });
});
</script>
</head>
<body> 
<?php
include("../components/barra_lateral2.php");
include("Controlador_Bloques/crud_elementos.php");
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

				<div class="panel-heading"><b class="titulo">Elementos de consumo</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar todo
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
							</label>
							<?php
						}?> 
</div>
						<div class="panel-body">
<div class="col-xs-3">
<label for="identificadorElemento">Identificador:</label>
<input type="text" id="identificadorElemento" required class="form-control" name="identificadorElemento" value="<?php if(isset($_GET['edit'])) echo $getROW['identificadorElemento'].'" readonly="true';  ?>"
    size="20" placeholder="nombre corto">
</div>

<div class="col-xs-3">
<label for="nombreElemento">Nombre:</label>
<input type="text" name="nombreElemento" required id="nombreElemento" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreElemento'];?>"
    size="20" placeholder="nombre del elemento">
</div>
<input type="hidden" name="n_clasificaciones" value="<?php if(isset($_POST['n_clasificaciones'])){ echo $_POST['n_clasificaciones'];}else{ echo 1;}?>">
<?php if(isset($_GET["edit"]))
{
	?>
	<div class="col-xs-3">
<label for="foto_field">Fotografía</label>
<input type="file" name="foto_field" required id="foto_field" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreElemento'];?>"
    size="20" placeholder="nombre del elemento"><img src="data:image/gif;base64,<?php echo $getROW['foto']?>">
</div>
	<?php
}
else
{?>
<div class="col-xs-3">
<label for="foto_field">Fotografía</label>
<input type="file" name="foto_field" required id="foto_field" class="form-control" value="<?php if(isset($_GET['edit'])) echo $getROW['nombreElemento'];?>"
    size="20" placeholder="nombre del elemento">
</div>
	<?php

}
?>
<div class="col-xs-3"> 
<label for="comboUnidades">Unidad de medida:</label>
	<SELECT name="comboUnidades" required id="comboUnidades" class="form-control"> 
	<?php
$resultado = $MySQLiconn->query("SELECT * FROM unidades where baja=1");
?>
<option value="">--</option>
<?php
while($row=$resultado->fetch_array())
{

	if(isset($_GET['edit']) && $getROW['unidad']==$row['nombreUnidad'])
	{

?>
<option selected value="<?php echo $row ['nombreUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
else
{
	?>
<option value="<?php echo $row ['nombreUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
}
?>
 </SELECT>

</div>
<div class="col-xs-3" style="border-radius:5px;margin-top:4px">
<label for="clasificaciones">Clasificaciones</label>
<button style="float:right;" title="Agregar clasificaciones" class="btn btn-info" id="clasificaciones" value="+" type="submit" name="n_clasificaciones" onclick="document.formulary.submit()"><img src="../pictures/plus.png" width="20"></button>
<?php 
if(isset($_POST['n_clasificaciones']))
{
	for($i=0;$i<$_SESSION['lstclas'];$i++)
{?>
	<select id="clas" class="form-control" style="margin-bottom:2px" name="<?php echo 'cmbclasificaciones'.$i;?>">
		<?php
	$SQL=$MySQLiconn->query("SELECT nombre,identificador FROM clasificacion");
	while($row=$SQL->fetch_array())
	{?>
		
	<option value="<?php echo $row['identificador']?>"><?php echo $row['nombre']?></option>
		<?php
	}?>
	</select>
	<?php

}

}
?>

</div>
</div>
</div>
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
	<button style="float:right;" class="btn btn-default" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>
<button class="btn btn-info" style="" data-toggle="modal" data-target="#window_clas">Clasificaciones</button>

<hr>
<!-- Modal -->
<div id="window_clas" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2>Clasificaciones</h2>
      </div>
      <div class="modal-body">
      	<form method="post" name="formu" id="formu" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Agregar clasificación</b>
					</div>
						<div class="panel-body">
<div class="col-xs-3">
<label for="identificador_clas">Código</label>
<input type="text" placeholder="Abreviación" class="form-control" name="identificador_clas" id="identificador_clas">
</div>
<div class="col-xs-3">
<label for="nombre_clas">Valor de la clasificación</label>
<input type="text" placeholder="Descripción" class="form-control" name="nombre_clas" id="nombre_clas">
</div>
</div>
<button type="submit"  style="float:right;" name="save" class="btn btn-success">Guardar</button>
</div>
</form>
        <h4>Catálogo</h4>
        <div class="table-responsive">
        <table class="table table-hover">
        <th>Código</th>
        <th>Valor</th>
        <?php
        $SQl2=sqlsrv_query($SQLconn,"SELECT CCODIGOVALORCLASIFICACION as identificador,CVALORCLASIFICACION as nombre FROM admClasificacionesValores");
        while($row2=sqlsrv_fetch_array($SQl2, SQLSRV_FETCH_ASSOC))
        {
        	?>
        	<tr>
        		<td><?php echo $row2['identificador']?></td>
        		<td><?php echo $row2['nombre']?></td>
        	</tr>
        	<?php
        }?>
    </table>
      </div>
      <div class="modal-footer">
      
      </div>
    </div>

  </div>
</div>
</div>

<?php 
if(isset($_POST['checkTodos']))
{?>
	<h4 class="ordenMedio">Elementos Activos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showElementos.php");
?>
</table>
	<h4 class="ordenMedio">Elementos Inactivos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showElementos_bajas.php");
?>
</table>
<?php
}
else{?>
<h4 class="ordenMedio">Elementos Activos</h4>
	<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showElementos.php");
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