<?php
include 'db_materiaPrima.php';
include '../Database/SQLConnection.php';
//error_reporting(0);
if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	//$identificadorElemento=$MySQLiconn->real_escape_string($_POST['identificadorElemento']);
	$elemento= $MySQLiconn->real_escape_string($_POST['comboElementos']);  
	$unidad = $MySQLiconn->real_escape_string($_POST['comboUnidades']);
	$unidad2 = $MySQLiconn->real_escape_string($_POST['comboUnidades2']);
	$place=trim($_POST['place']);
	$precio=trim($_POST['hasprice']);
	$lote=trim($_POST['lote']);
	$criterio=trim($_POST['criterio']);
	//$imagen=$_POST['foto_field'];
	if(isset($_POST['lote']))
	{
		$lote=1;
	}
	else
	{
		$lote=0;
	}
	if(isset($_POST['precio']))
	{
		$hasprice=1;
	}
	else
	{
		$hasprice=0;
	}
	$criterio=$_POST['criterio'];
	if($criterio=="2")
	{
		$max=$_POST['max'];
		$min=$_POST['min'];
	}
	else
	{
		$max='0';
		$min='0';
	}
//VALIDACION LUGAR PARA NO REPETIR//
	if (isset($_POST['place'])) {
	


$result=$MySQLiconn->query("SELECT place FROM obelisco.productosCK WHERE place= $place");

if(mysqli_num_rows($result)>0)
{
echo "
<p class='avisos'> ya esta registrado</p>
<p class='avisos'></p>
";
}
else
{
	echo "
<p class='avisos'>Registro insertado con exito</p>
<p class='avisos'></p>
";
} 
}
	/*move_uploaded_file($_FILES['foto_field']['tmp_name'],'../imagenes/'.$FILES['foto_field']['name']);
	chmod('../imagenes/'.$FILES['foto_field']['name'],0644);*/
	
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO obelisco.productosCK(producto,unidad,unidad2,hasprice,criterio,max,min,class,place,lote) VALUES('".$elemento."','".$unidad."','".$unidad2."','".$hasprice."','".$criterio."','".$max."','".$min."','".$clase."','".$place."','".$lote."')");
	 sqlsrv_query($SQLconn, "UPDATE admProductos SET CTEXTOEXTRA1='1' WHERE CIDPRODUCTO='".$elemento."'");
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 	
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo elemento ')</script>";
	 header("Location: MateriaPrima_Elementos.php");
	 }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE obelisco.productosCK SET baja=0 WHERE id=".$_GET['del']);
	header("Location: MateriaPrima_Elementos.php");
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Dado de Baja Exitosamente')</script>";
	 header("Location: MateriaPrima_Elementos.php");
}
/* Fin Código Eliminación Logíca  */


 //Inicio Código Atualizar//
/*
if(isset($_GET['update']))
{

	//Realizamos la consulta


 $MySQLiconn->query("UPDATE obelisco.productosCK SET producto='".$_POST['comboElementos']."', unidad='".$_POST['comboUnidades']."', hasprice='".$_POST['precio']."', criterio='".$_POST['criterio']."', place='".$_POST['place']."', lote='".$_POST['lote'].", max='".$_POST['max'].", min='".$_POST['min']." WHERE id=".$_GET['edit'] );

sqlsrv_query($SQLconn, "UPDATE admProductos SET CTEXTOEXTRA1='' WHERE CIDPRODUCTO='".$_GET['idp']."'");

	header("Location: MateriaPrima_Elementos.php");

 

*/


if(isset($_GET['acti']))
{


	$MySQLiconn->query("UPDATE obelisco.productosCK set baja=1 where id=".$_GET['acti']."");
	header("Location: MateriaPrima_Elementos.php");
}

if(isset($_GET['delfin'])){
	$MySQLiconn->query("DELETE from obelisco.productosCK where id=".$_GET['delfin']."");
	sqlsrv_query($SQLconn, "UPDATE admProductos SET CTEXTOEXTRA1='' WHERE CIDPRODUCTO='".$_GET['idp']."'");
	header("Location: MateriaPrima_Elementos.php");
}

/*
if(isset($_GET['update'])){
	$MySQLiconn->query("UPDATE from obelisco.productosCK where id=".$_GET['edit']."");
	sqlsrv_query($SQLconn, "UPDATE admProductos SET CTEXTOEXTRA1='' WHERE CIDPRODUCTO='".$_GET['idp']."'");
	header("Location: MateriaPrima_Elementos.php");
}

*/
//consulta que trae los datos al presionar modificar//


if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT * FROM obelisco.productosCK WHERE producto='".$_GET['edit']."'");
	$getROW = $SQL->fetch_array();

}
if(isset($_POST['update']))
{
$unidad=$_POST['comboUnidades'];
$unidad2=$_POST['comboUnidades2'];
$hasprice=$_POST['precio'];
$criterio=$_POST['criterio'];
$place=$_POST['place'];
$lote=$_POST['lote'];
if(isset($_POST['lote']))
	{
		$lote=1;
	}
	else
	{
		$lote=0;
	}
	if(isset($_POST['precio']))
	{
		$hasprice=1;
	}
	else
	{
		$hasprice=0;
	}
	
$SQL = $MySQLiconn->query("UPDATE obelisco.productosCK SET  unidad='$unidad',unidad2='$unidad2', hasprice='$hasprice',  place='$place',lote='$lote' WHERE producto='".$_GET['edit']."'");


header("Location: MateriaPrima_Elementos.php");

}





