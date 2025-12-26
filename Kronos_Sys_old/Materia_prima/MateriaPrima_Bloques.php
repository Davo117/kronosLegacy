<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../jquery-3.2.1.min.js" type="text/javascript"></script>
<title>Bloques | Materia Prima</title>
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
include("Controlador_Bloques/crud_bloques.php");
$_SESSION['tarima']=" ";
?>
<?php
if(isset($_GET['msj']))
{
	if($_GET['msj']=="success")
	{
		$mensaje="<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Lotes cargados exitosamente.
		</div>
		";
	}
	else if($_GET['msj']=="warning")
	{
		$mensaje="<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Advertencia</strong> , Algunos registros no se agregaron,ya existen en la base de datos.
		</div>
		";
	}
	else if($_GET['msj']=="danger")
	{
		$mensaje="<div class='alert alert-danger alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Error en la inserción,formato de documento incorrecto.
		</div>
		";
	}
	echo $mensaje;
}
?>
<div id="page-wrapper">
	<div class="container-fluid">

<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

				<div class="panel-heading"><b class="titulo">Bloques de materia prima</b>
					<?php 
					if(isset($_POST['checkTodos']))
					{
						?>

						<label class="checkbox-inline" style="float:right;">
							<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="document.formulary.submit()"> Mostrar detalles
						</label>
						<?php
					}
					else
						{?>

							<label class="checkbox-inline" style="float:right;">
								<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar detalles
							</label>
							<?php
						}?></div>
						<div class="panel-body">
</div>
</form>
</div>
<h4 class="ordenMedio">Bloques Activos</h4>
<div class="table-responsive">
<table border="0" cellpadding="15" class="table table-hover">
<?php
include ("Controlador_Bloques/showBloques.php");
?>
</table>
</div>
</div>
</body>
	 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript">
var my_delay =2000;
$('a#excel').click  ((function() {
      $.ajax({
            beforeSend:function(objeto){ 
            	$("div#carga").fadeIn(2000);
    		
            },
            success:function()
            {
				$("div#carga").css({display:'block'});
				setTimeout(5000);
            },
            complete:function()
            {
            	$("div#carga").fadeOut(8000);
            }
            
    })
  }));
</script>
</html>
<?php
				ob_end_flush();
			} else {
				echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
				include "../ingresar.php";
			}

			?>