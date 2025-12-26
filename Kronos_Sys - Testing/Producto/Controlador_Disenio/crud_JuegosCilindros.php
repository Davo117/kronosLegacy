<?php

include_once 'db_Producto.php';

/* Inicio Código Insertar */
//Si se dio clic en guardar:
if(isset($_POST['comboImpresiones']))
{
	unset($_GET['edit']);
}
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post

	$descripcionImpresion= $MySQLiconn->real_escape_string($_POST['comboImpresiones']);
	$identificadorCilindro = $MySQLiconn->real_escape_string($_POST['identificadorCilindro']);
	$SQL=$MySQLiconn->query("SELECT identificadorCilindro from juegoscilindros where identificadorCilindro='$identificadorCilindro'");
	$row=$SQL->fetch_array();
	if(empty($row['identificadorCilindro']))
	{
	$proveedor=$MySQLiconn->real_escape_string($_POST['proveedor']);
	$fechaRecepcion = $MySQLiconn->real_escape_string($_POST['fechaRecepcion']);
	$diametro= $MySQLiconn->real_escape_string($_POST['diametro']);
	//$tabla = $MySQLiconn->real_escape_string($_POST['tabla']);
	//$repAlPaso = $MySQLiconn->real_escape_string($_POST['repAlPaso']);
	$repAlGiro = $MySQLiconn->real_escape_string($_POST['repAlGiro']);
	$girosGarantizados = $MySQLiconn->real_escape_string($_POST['girosGarantizados']);
	$presionCilindro = $MySQLiconn->real_escape_string($_POST['presionCilindro']);
	$presionGoma = $MySQLiconn->real_escape_string($_POST['presionGoma']);
	//$altura=$MySQLiconn->real_escape_string($_POST['tabla']);
	$presionRasqueta = $MySQLiconn->real_escape_string($_POST['presionRasqueta']);
	$tolVelocidad = $MySQLiconn->real_escape_string($_POST['tolVelocidad']);
	$tolViscosidad=$MySQLiconn->real_escape_string($_POST['tolViscosidad']);
	$tolCilindro = $MySQLiconn->real_escape_string($_POST['tolCilindro']);
	$tolTemperatura = $MySQLiconn->real_escape_string($_POST['tolTemperatura']);
	$temperatura = $MySQLiconn->real_escape_string($_POST['temperatura']);
	$tolgoma = $MySQLiconn->real_escape_string($_POST['tolGoma']);
	$tolRasqueta = $MySQLiconn->real_escape_string($_POST['tolRasqueta']);
	$viscosidad=$MySQLiconn->real_escape_string($_POST['viscosidad']);
	$velocidad=$MySQLiconn->real_escape_string($_POST['velocidad']);
	if(is_numeric($diametro) && is_numeric($repAlGiro))
	{
	
	$cat=$MySQLiconn->query("SELECT s.anchura,i.anchoPelicula FROM sustrato s INNER JOIN impresiones i
	ON  s.idSustrato=i.sustrato WHERE i.id='".$descripcionImpresion."'");
	$dim=$cat->fetch_array();
	$tabla=$dim['anchura'];//Aquí estoy igualando la tabla y las repeticiones al paso alas del diseñi ligado
	$repAlPaso=$tabla/$dim['anchoPelicula'];
	$alturaReal=$diametro*pi()/$repAlGiro;
	$anchura=$tabla/$repAlPaso;
	if($dim['anchura']<($dim['anchoPelicula']*$repAlPaso)+1 || $dim['anchura']>($dim['anchoPelicula']*$repAlPaso)-1)
	{
		if($dim['anchura']==$tabla)
		{


	 $SQL =$MySQLiconn->query("INSERT INTO juegoscilindros (descripcionImpresion,identificadorCilindro,proveedor,fechaRecepcion,diametro, tabla,repAlPaso,repAlGiro,girosGarantizados,presionCilindro,presionGoma,presionRasqueta,tolVelocidad,tolViscosidad,tolCilindro,tolTemperatura,temperatura,tolgoma,tolRasqueta,alturaReal,anchuraReal,velocidad,viscosidad) VALUES('$descripcionImpresion','$identificadorCilindro','$proveedor','$fechaRecepcion','$diametro','$tabla','$repAlPaso','$repAlGiro','$girosGarantizados','$presionCilindro','$presionGoma','$presionRasqueta','$tolVelocidad','$tolViscosidad','$tolCilindro','$tolTemperatura','$temperatura','$tolgoma','$tolRasqueta','$alturaReal','$anchura','$velocidad','$viscosidad')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo "<div class='alert alert-success alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Operación exitosa</strong> , Agregado correctamente.
	</div>
	";
	 }
	 		 }
	 	
else
{
	 echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Ancho plano incorrecto.
	</div>
	";
}
	}

	else
	{
		  echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Las repeticiones al paso no coinciden con las dimensiones del sustrato y diseño.
	</div>
	";
	}
	}
	else
	{
		 echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Se ingresaron valores de medida incorrectos.
	</div>
	";
	}
	
	}
	else
	{
	 echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Ya existe un juego de cilindros con este identificador,no se pudo completar el registro.
	</div>
	";
	}
	
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE juegoscilindros SET baja=0 WHERE IDCilindro=".$_GET['del']);
	 header("Location: Producto_JuegosCilindros.php");
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
}



/* Inicio Código Atualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("
SELECT  jl.*,l.estado as prod FROM juegoscilindros jl 
LEFT JOIN produccion pr
ON pr.juegoCilindros=jl.identificadorCilindro
LEFT JOIN lotes l ON l.juegoLote=pr.juegoLotes
WHERE jl.IDCilindro='".$_GET['edit']."'");
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$cat=$MySQLiconn->query("SELECT s.anchura,i.anchoPelicula FROM sustrato s INNER JOIN impresiones i
	ON  s.idSustrato=i.sustrato WHERE i.id='".$_POST['comboImpresiones']."'");
	$dim=$cat->fetch_array();
	$tabla=$dim['anchura'];//Aquí estoy igualando la tabla y las repeticiones al paso alas del diseñi ligado
	$repAlPaso=$tabla/$dim['anchoPelicula'];
	$alturaReal=$diametro*pi()/$repAlGiro;
	$anchura=$tabla/$repAlPaso;
	if(($dim['anchura']<($dim['anchoPelicula']*$repAlPaso)+1 || $dim['anchura']>($dim['anchoPelicula']*$repAlPaso)-1))
	{
		if($dim['anchura']==$tabla)
		{


	$alturaReal=$_POST['diametro']*pi()/$_POST['repAlGiro'];
	$anchuraReal=($_POST['tabla']/$_POST['repAlPaso']);
	$SQL = $MySQLiconn->query("UPDATE juegoscilindros SET  identificadorCilindro='".$_POST['identificadorCilindro']."',proveedor='".$_POST['proveedor']."',fechaRecepcion='".$_POST['fechaRecepcion']."', diametro=".$_POST['diametro'].",tabla=".$_POST['tabla'].",repAlPaso=".$_POST['repAlPaso'].",repAlGiro=".$_POST['repAlGiro'].", girosGarantizados=".$_POST['girosGarantizados'].",viscosidad=".$_POST['viscosidad'].",velocidad=".$_POST['velocidad'].",presionCilindro=".$_POST['presionCilindro'].", presionGoma=".$_POST['presionGoma'].",presionRasqueta=".$_POST['presionRasqueta'].", tolVelocidad=".$_POST['tolVelocidad'].", tolCilindro=".$_POST['tolCilindro'].", tolTemperatura=".$_POST['tolTemperatura'].",temperatura=".$_POST['temperatura'].",tolViscosidad='".$_POST['tolViscosidad']."',tolGoma=".$_POST['tolGoma'].", tolRasqueta=".$_POST['tolRasqueta'].", alturaReal=".$alturaReal.", anchuraReal=".$anchuraReal." WHERE idCilindro=".$_REQUEST['edit']);
	
 if(!$SQL)
 {
 	 echo "<div class='alert alert-danger alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Error</strong> , Ocurrió un problema en la transacción,no se pudo completar el registro.
	</div>
	";
 }
 else
 {
 	 echo "<div class='alert alert-success alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Operación exitosa</strong> , Modificado correctamente.
	</div>
	";
 }
	//echo "<script>window.location='Producto_JuegosCilindros.php';</script>";
		 }
	 	
else
{
	  echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Ancho plano incorrecto.
	</div>
	";
}
}
	else
	{
		  echo "<div class='alert alert-warning alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Datos inválidos</strong> , Las repeticiones al paso no coinciden con las dimensiones del sustrato y diseño.
	</div>
	";
	}
	 
}

if(isset($_GET['imp']))
{
	$_SESSION['descripcion']= $_GET['imp'];
	echo "<script>window.location='Producto_Impresiones.php';</script>";
}

if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE juegoscilindros set baja=1 where IDCilindro=".$_GET['acti']."");
echo "<script>window.location='Producto_JuegosCilindros.php';</script>";
}
if(isset($_GET['delfin']))
{
	$SQL=$MySQLiconn->query("DELETE from juegoscilindros where idCilindro=".$_GET['delfin']."");
	if(!$SQL)
	{
		echo "<div class='alert alert-warning alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Hubo un problema al eliminar
		</div>
		";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	<strong>Operación exitosa</strong> ,eliminado correctamente
	</div>
	";	
	}
	
}
if(!empty($_GET['c']))
{
	$cam=$MySQLiconn->query("SELECT grupo from juegoscilindros where descripcionImpresion='".$_GET['c']."'");
	$rg=$cam->fetch_array();
	$grupo=$rg['grupo'];
		?>

			<select name="cmbImpresores" required id="cmbImpresores">
    <option value="">--</option>
    	<option value="cilindro" <?php  if($grupo=="Cilindro"){echo "selected";}?>>Juego de cilindros</option>
    	  <option value="placa"  <?php  if($grupo=="Placa"){echo "selected";}?>>Placa Flexográfica</option> 
</select>
				<?php
}
