<?php
session_start();
require_once('../../Excel/Classes/PHPExcel/IOFactory.php'); 
require_once('../../Excel/Classes/PHPExcel.php');
require_once('../../Excel/Classes/PHPExcel/Reader/Excel2007.php');

$hostname = 'localhost';
$database = 'saturno';
$username = 'kronos';
$password = 'gl123';
$tablaus ='elementosconsumo';

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
//cargamos el archivo excel(extension *.xls) 
$objPHPExcel = PHPExcel_IOFactory::load('../../Documentos/Productos_almacen.xls');
// Asignamos la hoja excel activa 
$objPHPExcel->setActiveSheetIndex(0); 
$i=2;
 $msj="";
 if(empty($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
''))
 {
 	$msj=2;
 }
 $numero=1;
while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
'') 
{   
/*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS*/
/** Invocacion de Clases necesarias */

//DATOS DE CONEXION A LA BASE DE DATOS

//$db = mysqli_select_db ("escuela",$mysqli) or die ("ERROR AL CONECTAR A LA BD");
 
$codigo_p=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
//$arrayNumero = explode("-", $referenciaLote, 2);

$nombre_p=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$impuesto=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$precio=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$clasificacion1=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$clasificacion2=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
$clasificacion3=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
$clasificacion4=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
$clave_sat=$objPHPExcel->getActiveSheet()->getCell('I'.$i)->getValue();


$result=$mysqli->query("SELECT identificadorElemento from elementosconsumo where identificadorElemento='$codigo_p'");
 $rau = $result->fetch_array();
 $SQL="";
 if(empty($rau['identificadorElemento']))
 {

 	$SQL =$mysqli->query("INSERT INTO elementosconsumo (identificadorElemento, nombreElemento, impuesto, precio, clasificacion1, clasificacion2, clasificacion3, clasificacion4, clave_sat) VALUES ('$codigo_p','$nombre_p', '$impuesto', '$precio', '$clasificacion1', '$clasificacion2','$clasificacion3','$clasificacion4', '$clave_sat')");

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
echo "<script>window.location='../../Materia_prima/MateriaPrima_Elementos.php?msj=".$mensaje."';</script>";
?>