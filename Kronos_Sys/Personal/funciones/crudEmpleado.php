<?php



/* Inicio Código Insertar */
//Si se dio clic en guardar:

// if(isset($_POST['suerte'])){
// 	$SQLi=$MySQLiconn->query("SELECT perfil FROM usuarios");

// 	while ($row=$SQLi->fetch_array()) {
// 		$SQLi1=$MySQLiconn->query("SELECT id FROM modulo");
// 		while ($row1=$SQLi1->fetch_array()) {
// 			$MySQLiconn->query("INSERT into accesos(perfil, cdgmodulo, permiso) values('".$row['perfil']."', '".$row1['id']."', 'rwx')");
// 		}
// 	}
// 	echo "alert('Finish')";
// }

if(isset($_POST['save'])){
	//Pasamos los parametros por medio de post
	$numemp = $MySQLiconn->real_escape_string($_POST['NumEmp']);
	$nombre =$MySQLiconn->real_escape_string($_POST['Nombre']);
	$ape = $MySQLiconn->real_escape_string($_POST['Apellido']);
	$telefono = $MySQLiconn->real_escape_string($_POST['Telefono']);
	$dep= $MySQLiconn->real_escape_string($_POST['Depa']);
	$puesto= $MySQLiconn->real_escape_string($_POST['Puesto']);
	if ($numemp=="" or $nombre=="" or $ape=="" or $telefono==""){
		echo "<script>alert('No se registró el Empleado')</script>";
	}
	else{
		$SQLi=$MySQLiconn->query("SELECT numemple FROM empleado where Baja='1'");
		while($row=$SQLi->fetch_array()){
			if ($row['numemple']==$numemp) {
				echo "<script>
				alertify.alert('Atención', 'No se puede repetir el N° de Empleado.',function(){ alertify.error('No se agrego el empleado'); });
				</script>";
				//alert('No se puede repetir el N° de Empleado.')
				return;
			}
		}
		//Realizamos la consulta
		$SQL =$MySQLiconn->query("INSERT INTO empleado(numemple, Nombre, apellido, Telefono, puesto, departamento) VALUES('$numemp','$nombre','$ape','$telefono','$puesto','$dep')");
		//$add= $MySQLiconn->query("INSERT INTO $reporte(nombre, accion, modulo, departamento, registro) VALUES('$usuarioF','Agrego al empleado Num:  $numemp','Personal','Recursos Humanos',NOW())");

		//En caso de ser diferente la consulta:
	 	if(!$SQL){
			//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	 	else{
	 		echo "<script>
				alertify.success('Empleado Agregado'); });
				</script>";
	 	}
	}
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Activar */
//Si se dio clic en Activar:
if(isset($_GET['activar'])){	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE empleado	SET baja=1 WHERE id=".$_GET['activar']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: index.php");
}
/* Fin Código Activar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del'])){
/*
	//Si tiene datos en sucursales o contactos, los desactiva tambien
	$sqlite = $MySQLiconn->query("SELECT * FROM $tablaem WHERE id=".$_GET['del']);
	$getROW = $sqlite->fetch_array();
	if (empty($getROW['Nombre'])) {
	}else{

	$MySQLiconn->query("UPDATE $user SET datodebaja=0 WHERE nombre='".$getROW['Nombre']."'");
}
*/
	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE $tablaem	SET baja=0 WHERE id=".$_GET['del']);
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: index.php");
}
/* Fin Código Eliminación Logíca  */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Eliminación Definitiva */
if(isset($_GET['eli'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $tablaem where ID=".$_GET['eli']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}else header("Location: index.php");
}
 /* Fin Código Eliminación Definitiva */
//////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////
/* Inicio Código Atualizar  */
if(isset($_GET['edit'])){
	$SQL = $MySQLiconn->query("SELECT * FROM $tablaem WHERE id=".$_GET['edit']);
	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update'])){
	if ($_POST['NumEmp']=="" or $_POST['Nombre']=="" or $_POST['Apellido']=="" ){
		echo "<script>alert('Necesita llenar el campo vacío')</script>";
	}
	else{
		//Precaucion por que puede pasar que los nombres sean iguales y tener conflicto a la hora del cambio de nombre
		if (!empty($_POST['Nombre'])) {
			$MySQLiconn->query("UPDATE $user SET nombre='".$_POST['Nombre']."' WHERE nombre='".$getROW['Nombre']."'");
		}
		if (!empty($_POST['NumEmp'])) {
			$SQLi=$MySQLiconn->query("SELECT numemple FROM $tablaem where Baja='1' && numemple!='".$getROW['numemple']."'");
			while($row=$SQLi->fetch_array()){
				if ($row['numemple']==$_POST['NumEmp']) {
					echo "<script>alert('No se puede repetir el N° de Empleado.')</script>";
					return;
				}
			}
		}
		$SQL = $MySQLiconn->query("UPDATE $tablaem SET numemple='".$_POST['NumEmp']."', Nombre='".$_POST['Nombre']."', apellido='".$_POST['Apellido']."', Telefono='".$_POST['Telefono']."', puesto='".$_POST['Puesto']."', departamento='".$_POST['Depa']."' WHERE id=".$_GET['edit']);
		if(!$SQL){
		 	//Mandar el mensaje de error
			echo $MySQLiconn->error;
		}
		else header("Location: index.php");
	}
}
/* Fin Código Atualizar */
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['guardar'])){
	//Pasamos los parametros por medio de post
	$depa = $MySQLiconn->real_escape_string($_POST['depa']);
	if ($depa==""){
		echo "<script>alert('No se registro el Departamento')</script>";
	}
	else{
		//Realizamos la consulta
		$SQL =$MySQLiconn->query("INSERT INTO departamento(nombre) VALUES('$depa')");
		//En caso de ser diferente la consulta:
		if(!$SQL){
	 		//Mandar el mensaje de error
			echo $MySQLiconn->error;
	 	}
	}
}
/* Fin Código Insertar */
if(isset($_GET['eliminarDepa'])){ //Cambiar el parametro "del" para que no haya conflictos:
	$SQL = $MySQLiconn->query("DELETE FROM  $depa where id=".$_GET['eliminarDepa']);
	//En caso de ser diferente la consulta:
	if(!$SQL){
	 	//Mandar el mensaje de error
		echo $MySQLiconn->error;
	}
	else header("Location: Departamento.php");
}
if(isset($_POST['seguro']))
{
	include '../../Database/db.php';
	$Squero=$MySQLiconn->query("SELECT*FROM empleado where ID='".$_POST['seguro']."'");

	$rows=$Squero->fetch_assoc();
	?>
	<div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title">Historial médico</h3>
                    <h4><?php echo $rows['Nombre'].' '.$rows['apellido'];?></h4>
                  </div>
                  <div class="modal-body" style="overflow:hidden;" id="formCantidad">
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnclose">Aceptar</button>
                  </div>
                </div>
              </div>
              <?php
}
/* Fin Código  */
?>