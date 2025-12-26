<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tablero de control | Producción</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#000">


</head>

<body> 
<?php
include ("Controlador_produccion/showSeguimiento.php"); 
include("../css/barra_horizontal5.php");
if(empty($_GET['tipo']))
{
    $tipo="Manga Termoencogible";
}
else
{
    $tipo=$_GET['tipo'];
}

?>
<div id="page-wrapper">
    <div class="container-fluid">
<?php
include('Controlador_produccion/showTablero.php');
?>
</div>
</div>
</body>
 <script type="text/javascript" src="../css/menuFunction.js"></script>
<script type="text/javascript">
</script>
</html>