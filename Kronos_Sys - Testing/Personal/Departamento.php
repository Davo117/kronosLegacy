<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {} 
else {
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
$codigoPermiso='2';
include ("funciones/example.php");
include ("funciones/crudEmpleado.php"); ?>

<title>Departamentos(RH) | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
</head>
<body> 
<?php include("../components/barra_lateral2.php"); ?>
<div id="page-wrapper">
<div class="container-fluid">

<form method="post" name="formulary" id="formulary" role="form">
<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Departamentos</b>
	</div>
<div class="panel-body">
<div class="col-xs-3">
<label for="depa">Área de Trabajo</label>
<input type="text" name="depa" value="" class="form-control" size="20" placeholder="Nombre Departamento" required>
</div> 
</div>
<button class="btn btn-default" style="float:right;" type="submit"  name="guardar">Guardar</button>
</form>
</div>
<div class="table-responsive">
<table  border="0" cellpadding="15" class="table table-hover">
<?php include ("funciones/mostradorDepa.php"); ?>
</table>
</div>
</div>
</body>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php b ob_end_flush(); ?>