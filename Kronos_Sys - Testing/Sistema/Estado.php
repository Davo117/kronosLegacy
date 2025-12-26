<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
	ob_start();
	$codigoPermiso='32';
	include ("funciones/crudMapa.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	 <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {height: 100%;}
      /* Optional: Makes the sample page fill the window. */
      html, body {height: 100%; margin: 0; padding: 0; }
    </style>
     
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mapa | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<link rel="stylesheet" href="style.css" type="text/css" />
   <!-- ________________________________________________________________________________________________________________-->
        <!-- Llamada al API Javascript de Google Maps -->
        <script  src="https://maps.googleapis.com/maps/api/js"></script>
        <!-- ________________________________________________________________________________________________________________-->
         
        <script type="text/javascript" src="http://mapas.inegi.org.mx/espacioyd_map/query?request=Json&var=geeServerDefs"></script>
        <script type="text/javascript" src="servicios/scripts/capas_inegi.js"></script>
        <script type="text/javascript" src="servicios/scripts/fusion_map_obj_v3.js"></script>
</head>
<body> 
<?php include("../components/barra_lateral2.php");?>
<div id="page-wrapper">
	<div class="container-fluid">
<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

        <div class="panel-heading"><b class="titulo">Estados</b>
        </div>
<div class="panel-body">
              
              
<div class="col-xs-3">
<label for="estado">Estado</label>
<input type="text" class="form-control" id="estado" name="estado" value="" size="20" placeholder="Ejemplo: Guanajuato" required>
</div> 


<div class="col-xs-3">
<label for="alias">Abreviatura</label>
<input type="text" class="form-control" id="alias" name="alias" value="" size="20" placeholder="Ejemplo: GTO." required>
</div> 

<div class="col-xs-3">
<label for="pais">País</label>
<input type="text" name="pais" id="pais" class="form-control" value="" size="20" placeholder="Ejemplo: México" required>
</div> 
</div>
<button class="btn btn-default" style="float:right;" type="submit"  name="guardar">Guardar</button>

</form>
</div>
<br>
<h4 class="ordenMedio">Estados activos</h4>
<div class="table-responsive">
<table  border="0" class="table table-hover">
<?php include ("funciones/mostradorEstado.php"); ?>
</div>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); 
} 
else {
  echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>';
  echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=../ingresar.php'>";
  exit;
} ?>