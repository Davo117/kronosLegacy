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
</head>
<body>



<?php
$codigoPermiso='14';
include("../components/barra_latera_almacen.php");
include ("Controlador_Bloques/db_materiaPrima.php");
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

				<div class="panel-heading"><b class="titulo">Importación de elementos de consumo</b>
				
					<?php /*
					
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
						*/?>
</div>
						<div class="panel-body">
<div class="col-xs-2">
	<?php//Boton criterio de explosion//?>
    <select <?php if (isset($_GET['edit'])) {
	echo "disabled"; }?> id="criterio" <?php if(isset($_POST['criterio']) && $_POST['criterio']=="2"){ echo "";}else{ echo "autofocus='true'";}?> onchange="document.formulary.submit()" name="criterio" class="form-control">
    	<option value="">-- Criterio de explosión --</option>
    	<option value="1" <?php if(isset($_POST['criterio']) && $_POST['criterio']=="1"){ echo "selected";}if(isset($_GET['edit']) && $getROW['criterio']=="1"){ echo "selected";}?>>Por orden de trabajo</option>
    	<option value="2" <?php if(isset($_POST['criterio']) && $_POST['criterio']=="2"){ echo "selected";}if(isset($_GET['edit']) && $getROW['criterio']=="2"){ echo "selected";}?>>Máximos y mínimos</option>|
    </select>
    <?php
    if(isset($_POST['criterio']) && $_POST['criterio']=="2")
    {
    	?>
    	<input type="number" autofocus="true" name="max" class="form-control" step="any" placeholder="Stock máximo">
    	<input type="number" name="min" class="form-control" step="any" placeholder="Stock mínimo">
    	<?php
    }
    ?>
</div>
<!-- SELECCION DE ELEMENTO   -->
<div class="col-xs-3" style="border-style:solid;border-color:black;border-radius:5px;border-width:2px;border-color:#DEDEDE;">
<select required  <?php if (isset($_GET['edit'])) {
	echo "disabled"; }?> name="comboElementos"  id="comboElementos" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="-- Selecciona el producto --">

    
 <?php
$resultado = sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id,CCODIGOPRODUCTO as codigo,CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO!=0 AND CSTATUSPRODUCTO=1 AND CTEXTOEXTRA1!=1 AND CIDVALORCLASIFICACION6!=2");
?>
<option value="">--</option>

<?php
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
?> 
	<?php
if( $getROW['producto']==$row['id'])
{
	?>
	<option data-tokens ="<?php echo $row['nombreElemento'].$row['codigo'];?>" value="<?php echo $getROW['id'];?>" selected><?php  echo $getROW['elemento'];?></option>
	<?php 
}
	else{
		?>
<option data-tokens ="<?php echo $row['nombreElemento'].$row['codigo'];?>" value="<?php echo $row['id'];?>"><?php echo $row['nombreElemento'];?></option>

<?php 
}
} 
?>


<?php

if(isset($_GET['edit'])) 
{ 
	$resultado2 = sqlsrv_query($SQLconn, "SELECT CIDPRODUCTO as id, CCODIGOPRODUCTO as codigo, CNOMBREPRODUCTO as nombreElemento FROM admproductos WHERE CIDPRODUCTO=".$getROW['producto']."");
    $row2 = sqlsrv_fetch_array($resultado2, SQLSRV_FETCH_ASSOC); 

if( $getROW['producto']==$row2['id'])
{
	?>

	<option selected data-tokens="<?php echo $row2['nombreElemento'].$row2['codigo'];?>" value="<?php echo $row2['id'];?>"  ><?php  echo $row2['nombreElemento'];?>  </option>
	<?php 
}
	else{
		?>
<option selected data-tokens="<?php echo $row2['nombreElemento'].$row2['codigo'];?>" value="<?php echo $row2['id'];?>"><?php echo $resultado2['nombreElemento'];?></option>


<?php 
}
} 
?>


</select>
</div>




<div class="col-xs-3" style="height:40px">
	<SELECT name="comboUnidades" required id="comboUnidades" class="form-control"> 
	<?php
$resultado = $MySQLiconn->query("SELECT * FROM unidades");
?>
<option value="">-- Selecciona la unidad de medida --</option>
<?php


while($row=$resultado->fetch_array())
{

	if(isset($_GET['edit']) && $getROW['unidad']==$row['idUnidad'])
	{

?>
<option selected value="<?php echo $row ['idUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
else
{
	?>
<option value="<?php echo $row ['idUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
}
?>
 </SELECT>
</div>
<div class="col-xs-3" style="height:40px">
  <SELECT name="comboUnidades2" required id="comboUnidades2" class="form-control"> 
  <?php
$resultado = $MySQLiconn->query("SELECT * FROM unidades");
?>
<option value="">-- Unidad de medida secundaria --</option>
<?php


while($row=$resultado->fetch_array())
{

  if(isset($_GET['edit']) && $getROW['unidad2']==$row['idUnidad'])
  {

?>
<option selected value="<?php echo $row ['idUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
else
{
  ?>
<option value="<?php echo $row ['idUnidad'];?>"><?php echo $row['nombreUnidad'];?></option>
<?php
}
}
?>
 </SELECT>
</div>
<div class="col-xs-1" style="">
   
   


<?php
 if(isset($_GET['edit']) AND $getROW["hasprice"]==1)

 {
 	?>
<input type="checkbox" name="precio" class="custom-control-input" id="precio" checked="true">
<?php
   
   }
else
{
	?>

<input type="checkbox" name="precio" class="custom-control-input" id="precio">
<?php
 }

?>
 <label class="custom-control-label" for="precio">Precio</label >
<!-- boton lote -->

    <br>
   
   <!--toma la variable de la base de datos que es igual a 1 y el boton si es verdadero lo marca, si no lo desmarca  -->
    <?php
  if(isset($_GET['edit']) AND $getROW["lote"]==1)

 {
 	?>
 <input type="checkbox" name="lote" class="custom-control-input" id="lote" checked="true">
<?php
   
   }
else
{
	?>

<input type="checkbox" name="lote" class="custom-control-input" id="lote">
<?php
 
 	
 }

?>
  <label class="custom-control-label" for="lote">Lote</label>

</div><hr style="clear:both;">

<!-- boton lugar -->
<div class="col-xs-1"> 
	<input type="text" name="place" title="Lugar en Almacén" id="place" class="form-control" enable="false" placeholder="Lugar en almacén" value="<?php if(isset($_GET['edit'])) echo $getROW['place']; ?>">
</div>

<div>
	<a type="submit" class="btn btn-primary btn-veralm">Ver almacén</a>
</div>


</div>


<!-- botones actulizar y guardar -->
<?php
if(isset($_GET['edit']))
{
	
	?>
	<button style="float:right;" class="btn btn-warning" type="submit" name="update">Actualizar</button>
	<?php
}
else
{
	?>
	<button style="float:right;" class="btn btn-success" type="submit" name="save">Guardar</button>
	<?php
}
?>
</form>



</div>
<div id="espaciosAlm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title" style="text-align: center">Elige un espacio</h2>
          </div>
          <div class="modal-body" id="detalleIn">
		  	<div>
			  <iframe height="100%" width="100%" src="../Almacen/layout_almacen - sin lateral.php"></iframe>
			  </div>	
          </div>
          <div class="modal-footer">  
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>  
          </div>  
        </div>
      </div> 
    </div> 
	<div id="window_in" class="modal fade" tabindex="-1" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title" style="text-align: center">¿Deseas seleccionar esta posición?</h2>
                  </div>
                  <div class="modal-body" id="detalleIn">
                  </div>
                  <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>  
                  </div>  
                </div>
              </div> 
            </div> 


<!--
<a class="btn btn-default" href="Controlador_Bloques/cargaProductos.php" title="Cargar productos"><img src="../pictures/excel.png"></a>-->
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

		
		<script type="text/javascript">
          $(document).ready(function(){
            $(function() {
              $(document).on('click', '#button.espacio', function(event){
                var id = this.id;

                $.ajax({
                  type: "POST",
                  url: "controlador_almacen/crud_almacen.php",
                  data: "show_prod="+id,
                  dataType: "html",
                  error: function(){
                    alert("Error cargando detalle")
                  },
                  success: function(data){
                    console.log(id);
                    
                    $("#window_in").modal("show");
                  }
                });
              }); 
            });
          });
        </script>


          
	 <script type="text/javascript" src="../css/menuFunction.js"></script>
	 <script type="text/javascript">
	 
	 $(document).ready(function(){
          $(function() {
            $(document).on('click', 'a.btn-veralm', function(event){
              var id = this.id;
              $.ajax({
                type: "POST",
                url: "",
                data: "show_mov=" + id,
                dataType: "html",
                error: function(){
                  alert("Error cargando detalle")
                },
                success: function(data){
                  //$("#detalleIn").html(data);
                  $("#espaciosAlm").modal("show");
                }
              });
            }); 
          });
        });
	 
	 
	 </script>
</html>
<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>

