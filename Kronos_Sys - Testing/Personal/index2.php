<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
ob_start();
include("../components/barra_lateral2.php");
?>
	<!DOCTYPE>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Página principal | Grupo Labro</title>
	<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
	<link rel="manifest" href="../pictures/manifest.json">
	<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#fff">
	  <script type="text/javascript" src="../css/teclasN.js"></script>
	  <script type="text/javascript">
	  	/*
 function limitar(e, contenido, caracteres)
        {
            // obtenemos la tecla pulsada
            var unicode=e.keyCode? e.keyCode : e.charCode;
 
            // Permitimos las siguientes teclas:
            // 8 backspace
            // 46 suprimir
            // 13 enter
            // 9 tabulador
            // 37 izquierda
            // 39 derecha
            // 38 subir
            // 40 bajar
            if(unicode==8 || unicode==46 || unicode==13 || unicode==9 || unicode==37 || unicode==39 || unicode==38 || unicode==40)
                return true;
 
            // Si ha superado el limite de caracteres devolvemos false
            if(contenido.length>=caracteres)
                return false;
 
            return true;
        }*/
	  </script>
	</head>
	<body> 
	<div id="page-wrapper">
	<div class="container-fluid">
	
	<div class="panel panel-default">

	<div class="panel-heading" style="text-align:center;"><b class="titulo"">Bienvenido <b style="color:#866565"><?php echo $_SESSION['nombre'];?></b></b>
	
 </div>
<div class="panel-body" style="text-align: center;">
	<?php
	/*
 <div class="col-xs-4 col-lg-2 col-md-4" style="float: right;">
                 <select required  name="cdgproveedor" autofocus="true" onchange="document.formulary.submit()" id="cdgproveedor"  class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="--Busca el módulo">
<option value="">--</option>
 <option data-tokens="Impresiones" value="Producto-Impresiones">Producto-Impresiones</option>
 <option data-tokens="Impresiones" value="Producto-Impresiones">Producto-Suaje</option>
 <option data-tokens="Impresiones" value="Producto-Impresiones">Producto-Banda de seguridad</option>
 <option data-tokens="Impresiones" value="Producto-Impresiones">Producto-Juego de cilindros</option>
</select>
</div>*/
$wall=rand(1,10);
$mes=date('F');?>

 
           
<div class="col-xs-12 col-lg-12 col-md-12">
<img src="<?php echo '../pictures/walls/'.$mes.'/'.$wall.'.jpg';?>" class="img-thumbnail" width="500" height="400">
</div>
</div>
</div>
	
	 
</div>

		
<div id="muestra" class="col-xs-12"></div>

</div>
</div>
</body>
<script src="../jquery-3.2.1.min.js"></script>
<script language="javascript">

</script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); 
}
else {
	include "../ingresar.php"; 
} ?>