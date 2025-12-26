<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<head>
	<title>Diseño| Producto</title>
</head>
<?php

$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
$_SESSION['descripcion']=" ";
$_SESSION['descripcionCil']=" ";
$_SESSION['descripcionBanda']=" ";
//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
include("../components/barra_latera_almacen.php");
include("controlador_almacen/crud_almacen.php");
include("../Database/conexionphp.php");
?>
<div id="page-wrapper">
	<div class="container-fluid">

		<form method="POST" name="formulary" id="formulary" role="form">
			<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Inventario</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
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
							
							
							<?php /*<div class="col-xs-3">
								<label for="descripcion">Descripción</label>
								<input id="descripcion" type="text" class="form-control" name="descripcion" autocomplete="off" value="<?php if(isset($_GET['edit'])) echo $getROW['descripcion'].'" readonly="true';  ?>" size="20" placeholder="Descripción o nombre" required>
								<div id="resultado">
								</div>

							</div>*/?>
							
								</div>
								<?php
								/*if(isset($_GET['edit']))
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
								}?>*/?>
							</form>
							
						</div>
						<?php /*<h4 class="ordenMedio">Diseños Activos</h4>
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
						}*/
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
			}?>
