<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<head>
	<title>Diseño| Producto</title>
</head>
<?php

$_SESSION['codigoPermiso']='20001';
include ("funciones/permisos.php");
$_SESSION['descripcion']=" ";
$_SESSION['descripcionCil']=" ";
$_SESSION['descripcionBanda']=" ";
include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
include("../components/barra_lateral2.php");
include("Controlador_Disenio/crud_product.php");
include("Controlador_Disenio/db_Producto.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">

		<form method="POST" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Diseño</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline"  style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='Producto_Disenio.php';"> Mostrar todo
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
							</label>
							<?php
						}?></div>
						<div class="panel-body">
							
							
							<div class="col-xs-3">
								<label for="descripcion">Descripción</label>
								<input id="descripcion" type="text" class="form-control" name="descripcion" autocomplete="off" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcion'].'" readonly="true';  ?>" size="20" placeholder="Descripción o nombre" required>
								<div id="resultado">
								</div>
							</div>
							<div class="col-xs-3">
								<label for="codigo">Código</label>
								<input id="codigo" type="text" class="form-control" name="codigo" onkeypress="return numeros(event)" value="<?php if(isset($_GET['edit'])) echo $getROW['codigo'];?>"
								size="20" placeholder="Código del diseño" required> 
							</div>
							
							<div class="col-xs-3">
								<label for="tipocombo">Tipo de producto</label>
								<SELECT required name="tipocombo" class="form-control" id="tipocombo" title="Seleccione el tipo de producto según las carácteristicas" method="post" > 
									<?php
									$resultado=$MySQLiconn->query("SELECT alias as tipo,presentacion from tipoproducto where baja=1");
									?>
									<option value="">--</option>
									<?php
									while($row=$resultado->fetch_array())
									{
										if($row['presentacion']=='EtiqAbierta')
										{
											$presentacion="Et.abierta";
										}
										else if($row['presentacion']=="Predeterminados")
										{
											$presentacion="Et.cerrada";
										}
										else
										{
											$presentacion="Holograma";
										}
										if($row['tipo']!="BS")
										{	

											if($getROW['tipo']==$row['tipo'])
											{
												
												
												?>
												<OPTION selected VALUE="<?php echo $row['tipo']; ?>"  ><?php echo $row['tipo'].' ['.$presentacion.']'; ?></OPTION>
												<?php
											}
											else
											{
												?>
												<OPTION VALUE="<?php echo $row['tipo']; ?>" ><?php echo $row['tipo'].' ['.$presentacion.']'; ?></OPTION>
												<?php
											}
										}
									}
									?>
								</SELECT></div>
								<?php
								if(!isset($_GET['edit']))
									{?>

										<div class="col-xs-3">
											<label class="checkbox-inline">
												<input title="Al marcar esta casilla,este tipo de producto tendrá los consumos genéricos" type="checkbox" id="checkbox"  name="predeterminados"> Consumos predeterminados
											</label>
										</div>
										<?php
									}
									?>
									<div class="col-xs-3">
										<label class="checkbox-inline">
											<input type="checkbox"  name="holog" id="holog"> Holograma
										</label>
									</div>
								</div>
								<?php
								if(isset($_GET['edit']))
								{
									if(empty($getROW['prod']))
									{
										?>
										<button class="btn btn-default" style="float:right;" type="submit" name="update">Actualizar</button>
										<?php
									}
									else
									{
										?>
										<button class="btn btn-default" style="float:right;" type="button" name="update" disabled>Actualizar</button>
										<?php
									}
								}
								else
								{
									?>
									<button class="btn btn-default" style="float:right;" type="submit" name="save">Guardar</button>
									<?php
								}?>
							</form>
							
						</div>
						<h4 class="ordenMedio">Diseños Activos</h4>
						<div class="table-responsive">
							<table  border='0' cellpadding='15' class="table table-hover">
								<?php
								include ("Controlador_Disenio/show.php");
								?>
							</table>
						</div>
						<?php 
						if(isset($_POST['checkTodos']))
						{
							?>
							<h4 class="ordenMedio">Diseños Inactivos</h4>
							<div class="table-responsive">
								<table border='0' cellpadding='15'  class="table table-hover">
									<?php
									include ("Controlador_Disenio/show_bajas.php");
									?>
								</table>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<script type="text/javascript" src="../css/menuFunction.js"></script>
				</html>
				<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>
			<script type="text/javascript">
				$(document).ready(function(){
					
					var consulta;
					
      //hacemos focus
      $("#descripcion").focus();
      
      //comprobamos si se pulsa una tecla
      $("#descripcion").keyup(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#descripcion").val();
             
             //hace la búsqueda
             $("#resultado").delay(100).queue(function(n) {      
             	
             	$("#resultado").html('<img src="cargando.gif" />');
             	
             	$.ajax({
             		type: "POST",
             		url: "Controlador_Disenio/crud_product.php",
             		data: "b="+consulta,
             		dataType: "html",
             		error: function(){
             			alert("error petición ajax");
             		},
             		success: function(data){                                                      
             			$("#resultado").html(data);
             			n();
             		}
             	});
             	
             });
             
         });
      
  });
</script>
