<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { 
    $codigoPermiso='30';
include ("funciones/crudPrivilegios.php"); 
    include("../components/barra_lateral2.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Privilegios(Sistema) | Grupo Labro</title>
<link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
<link rel="manifest" href="../pictures/manifest.json">
<link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#fff">
<script type="text/javascript" src="funciones/js/jquery-1.7.2.min.js"></script>
<link href="css/accion.css" rel="stylesheet">

</head>
<body>
<div id="page-wrapper">
	<div class="container-fluid">
        <form method="post" name="formulary" id="formulary" role="formulary">
        <div class="panel panel-default">
            <div class="panel-heading"><b class="titulo">Permisos de usuario</b></div>
            <div class="panel-body">
                <div class="col-xs-3">
                    <label for="example">Usuario</label>
                    <select name="example" id="example" data-live-search="true"  class="selectpicker show-menu-arrow form-control" onChange="modulos()" required style="border: none;">
                    <option value="">[Seleccione un Usuario]</option>
                    <?php //Seleccionar todos los datos de la tabla 
                    $resultado = $MySQLiconn->query("SELECT perfil, usuario, nombre FROM usuarios ");
                    //mientras se tengan registros:
                    while ($row = $resultado->fetch_array()) { ?>
	                   <option value="<?php echo $row['perfil'];?>"><?php echo $row['usuario']." - ".$row['nombre'];?></option>	<?php
                    } ?>
                    </select>
                </div>

                <div class="col-xs-3 col-offset-6">
                <label for="example">Departamento</label>
                <select name="example1" id="example1" class="form-control" onChange="modulos();" required style="border: none;">
                    <option value="">[Selecciona un Departamento]</option>
                    <option value="1">Recursos Humanos</option>
                    <option value="2">Producto</option>
                    <option value="3">Materia Prima</option>
                    <option value="4">Logística</option>
                    <option value="5">Producción</option>
                    <option value="6">Buscador</option>
                    <option value="8">Sistema</option>
                    <option value="7">Reporteador</option>
                </select>
                </div>
                <div class="col-xs-3">
                <br>
                <input type="button" class="btn btn-primary btn-sm"  id="Ver"  onclick="verificar(example.value,example1.value);" value="Ver">
                </div>
                <button class="btn btn-success" type="submit" style="float:right;" name="save1">Guardar</button>
            </div>
        </div>

        <div class="card">
            <div id="pestanas">
                <article class="subbloque">
                <h2>Definición de permisos</h2>
                <br>
                <label><b>---</b> Bloqueado | <b>r--</b> Lectura | <b>rw-</b> Lectura y escritura | <b>rwx</b> Lectura, escritura y remoción </label>               
                </article>
            </div>
            <div id="access"></div>
            </form>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" language="JavaScript">
function modulos(){
    Ver.removeAttribute("disabled", "");
}

function verificar(str1, str2){
    $('#access').load('funciones/accesos.php?q='+str1+'&m='+str2);
    Ver.setAttribute("disabled", "");
}
//window.onload = function() {
//modulos(example.value);
//};
</script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html> <?php
ob_end_flush();
}
else{
    echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>';
    include "../ingresar.php";
} ?>