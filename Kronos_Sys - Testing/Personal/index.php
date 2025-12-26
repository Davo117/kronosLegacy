<?php
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) {
ob_start();
	$codigoPermiso='1';

	include ("funciones/example.php");
	include("../components/barra_lateral2.php");
	$privilegios=getPermisos($_SESSION['perfil'],$codigoPermiso,$MySQLiconn);
$valorR="";
$valorW="";
$valorX="";
if(substr($privilegios,0,1)=='r'){
	$valorR="";
	if(substr($privilegios,0,2)=='rw'){
		$valorW="";
		if(substr($privilegios,0,3)=='rwx'){
			$valorX="";
		}
		else $valorX="1";
	}
	else $valorW="disabled";
}
	else {$valor="sin vista";
//si no tienes acceso a modulos no se muestra en la barra lateral
}
	include ("funciones/crudEmpleado.php"); ?>

	<!DOCTYPE>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Empleado(RH) | Grupo Labro</title>
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

	<form method="post" name="formulary" id="formulary" role="form">
	
	<div class="panel panel-default">

	<div class="panel-heading"><b class="titulo">Personal </b>
	<?php 
	if(isset($_POST['checkTodos'])){	?>

	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" checked value="checkTodos" name="checkTodo" onclick="window.location='index.php';"> Mostrar todo
		</label>
	 	<?php
	}
	else{ ?>
	 	<label class="checkbox-inline" style="float:right;">
  		<input type="checkbox" id="checkboxEnLinea1" value="checkTodo" name="checkTodos" onclick="document.formulary.submit()"> Mostrar todo
		</label>
		<?php
 	} ?>
 </div>
<div class="panel-body">

<div class="col-xs-3">
<label for="NumEmp">Código Empleado</label>
<?php $SQLi=$MySQLiconn->query("SELECT numemple FROM empleado where Baja='1' and numemple<976  order by numemple DESC  limit 1"); 
$rowS=$SQLi->fetch_array();

$valor=$rowS['numemple'] + 1; ?>
<input class="form-control" type="text" id="NumEmp" name="NumEmp" value="<?php if(isset($_GET['edit'])){ echo $getROW['numemple']; } else{ echo str_pad($valor,  3, "0", STR_PAD_LEFT);} ?>" size="20" placeholder="Número Empleado" required maxlength='3' onkeypress="return numeros(event)">
</div>

<div class="col-xs-3">
<label for="Nombre">Nombre(s)</label>
<input class="form-control" type="text" id="Nombre" name="Nombre" value="<?php if(isset($_GET['edit'])) echo $getROW['Nombre'];  ?>" size="20" placeholder="Nombre del Empleado" required></p>
</div>

<div class="col-xs-3">
<label for="Apellido">Apellidos</label>
<input type="text" id="Apellido" class="form-control" name="Apellido" value="<?php if(isset($_GET['edit'])) echo $getROW['apellido'];  ?>" size="20" placeholder="Apellidos" required></p>
</div>

<div class="col-xs-3">
<label for="Telefono">Teléfono</label>
<input type="text"  id="Telefono" class="form-control" name="Telefono" value="<?php if(isset($_GET['edit'])){ echo $getROW['Telefono'];}else{echo "000"; }  ?>" size="20" placeholder="Teléfono del Empleado" required onkeypress="return numeros(event)"></p>
</div>

<div class="col-xs-3">
<label for="Puesto">Puesto</label>
<input type="text" id="Puesto" class="form-control" name="Puesto" value="<?php if(isset($_GET['edit'])) echo $getROW['puesto'];  ?>" size="20" placeholder="Puesto" required></p>
</div>

<div class="col-xs-3">
<label for="Depa">Departamento</label>
<select name="Depa" data-live-search="true"  class="selectpicker show-menu-arrow form-control" list="exampleList" id="Depa" required>
<option value="">[Seleccione un Departamento]</option>

<?php //Seleccionar todos los datos de la tabla 
$resultado = $MySQLiconn->query("SELECT * FROM $depa where baja=1 order by nombre");
//mientras se tengan registros:
while ($row = $resultado->fetch_array()) {
	//Si hay uno seleccionado se establece por default el nombre del seleccionado del modulo anterior:

	if(isset($_GET['edit'])){ 
    	if($getROW['departamento'] == $row['id']){  ?>
			<option value="<?php echo $row['id'];?>" selected> <?php echo $row['nombre'];?></option>
			<?php  
		}
		else{  //Sino manda la lista normalmente:  ?>
			<option value="<?php echo $row['id'];?>"><?php echo $row['nombre'];?></option>
			<?php 
		}	
	}
	else{	//Sino manda la lista normalmente:  		?>
		<option value="<?php echo $row['id'];?>"><?php echo $row['nombre'];?></option>
		<?php 
	} 
}  ?> 
</select>
</div>
</div>
<?php
if(isset($_GET['edit'])){	?>
	<button class="btn btn-default" type="submit" style="float:right;" name="update" >Actualizar</button>
	<?php  
}else{	?>
	<button class="btn btn-default" type="submit"  style="float:right;" name="save">Guardar</button>
	
	<?php 
} ?>
</form>
</div>




<?php
if(isset($_POST['checkTodos'])){?>
<div class="table-responsive">
	<table border='0' cellpadding='0' class="table table-hover">
	<?php 	include ("funciones/mostrarTodoEmpleado.php");	?>
	</table></div>
	<?php
} ?>




<div class="table-responsive col-xs-10">
	<div class="col-xs-3"><a target="_blank" class="btn btn-info" href="codigosEmpleado.php">Generar códigos</a>
	</div>
	
	<div class="col-xs-4"><input type="text"  name="caja_busqueda" class="form-control" id="caja_busqueda" onkeyup="tratar(this.value)"	placeholder="Búsqueda General" style="border-style:border: 2px solid; border-color: #31B0D5;
  border-radius: 5px;">
	</div>
	
	<div class="col-xs-3"><img class="img-responsive" width="30" height="30" src="funciones/img/buscar.png">
	</div>
</div>

		
<div id="muestra" class="col-xs-12"></div>

</div>
</div>
</body>
<script src="../jquery-3.2.1.min.js"></script>
<script language="javascript">
$(tratar(''));


function tratar(valor){
 	if (valor != "") {
 		$('#muestra').load('funciones/mostradorEmpleado.php?em='+encodeURI(valor));
 	}else{
 		$('#muestra').load('funciones/mostradorEmpleado.php');
 	}
}
function buscar_datos(cons){
	$('#muestra').load('funciones/mostradorEmpleado.php?em='+cons);
}

function buscar_datos1(consulta){
	$.ajax({
		url: 'funciones/mostradorEmpleado.php',
		type: 'POST' ,
		dataType: 'html',
		data: {em: consulta},
	})
	.done(function(respuesta){
		$("#muestra").html(respuesta);
	})
	.fail(function(){
		console.log("error");
	});
}

// window.onload = function() {
//   busqueda();
// };

</script>
<script type="text/javascript" src="../css/menuFunction.js"></script>
</html>
<?php ob_end_flush(); 
}
else {
	include "../ingresar.php"; 
} ?>