<?php
session_start();
require_once('../../Excel/Classes/PHPExcel/IOFactory.php'); 
require_once('../../Excel/Classes/PHPExcel.php');
require_once('../../Excel/Classes/PHPExcel/Reader/Excel2007.php');
include('../../Database/SQLConnection.php');
include("../../Database/conexionphp.php");

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
//cargamos el archivo excel(extension *.xls) 
$objPHPExcel = PHPExcel_IOFactory::load('../../Documentos/exportarProductosU.xls'); 
//$bloque=$_SESSION['desBloque'];
// Asignamos la hoja excel activa 
$objPHPExcel->setActiveSheetIndex(0); 
$i=2;
 $msj="";
 if(empty($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
''))
 {
 	$msj=2;
 }
 
while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
'') 
{   
/*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS*/
/** Invocacion de Clases necesarias */

//DATOS DE CONEXION A LA BASE DE DATOS

//$db = mysqli_select_db ("escuela",$mysqli) or die ("ERROR AL CONECTAR A LA BD");
 
$criterio=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
$producto=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$unidad=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$hascode=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$hasprice=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$maximo=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
$minimo=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
$class=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
$place=$objPHPExcel->getActiveSheet()->getCell('J'.$i)->getValue();
$lote=$objPHPExcel->getActiveSheet()->getCell('K'.$i)->getValue();
if(!empty($hascode))
{
	$hascode=1;
}
else
{
	$hascode=0;
}
if(!empty($hasprice))
{
	$hasprice=1;
}
else
{
	$hasprice=0;
}
if($criterio=="Explosión")
{
	$criterio=1;
}
else if($criterio=="Max y min")
{
	$criterio=2;
}
if(!empty($lote))
{
	$lote=1;
}
else
{
	$lote=0;
}
$SQLUn=$mysqli->query("SELECT idUnidad from unidades where nombreUnidad='".$unidad."'");
$run=$SQLUn->fetch_assoc();
$unidad=$run['idUnidad'];
$consult=sqlsrv_query($SQLconn,"SELECT CIDPRODUCTO,CTEXTOEXTRA1 from admproductos where CCODIGOPRODUCTO='$producto'");
$rau = sqlsrv_fetch_array($consult, SQLSRV_FETCH_ASSOC);
$SQL="";
if(empty($rau['CTEXTOEXTRA1']) and !empty($unidad) and !empty($criterio))
 {
 	 $SQL =$mysqli->query("INSERT INTO obelisco.productosCK(producto,unidad,hascode,hasprice,criterio,max,min,class,place,lote) VALUES('".$rau['CIDPRODUCTO']."','".$unidad."','".$hascode."','".$hasprice."','".$criterio."','".$maximo."','".$minimo."','".$class."','".$place."','".$lote."')");
	 sqlsrv_query($SQLconn, "UPDATE admProductos SET CTEXTOEXTRA1='1' WHERE CIDPRODUCTO='".$rau['CIDPRODUCTO']."'");

 }
 else
 {
 	$msj=1;
 }
$i++;
}

//echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Materia_prima/MateriaPrima_Bloques.php'>";
if($msj==1)
{
	$mensaje="warning";
}
else if($msj==2)
{
	$mensaje="danger";
}
else
{
	$mensaje="success";
}
echo "<script>window.location='../MateriaPrima_Elementos.php?msj=".$mensaje."';</script>";
?>