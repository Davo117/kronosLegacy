<?php
session_start();
require_once('Excel/Classes/PHPExcel/IOFactory.php'); 
require_once('Excel/Classes/PHPExcel.php');
require_once('Excel/Classes/PHPExcel/Reader/Excel2007.php');

$hostname = 'localhost';
$database = 'saturno';
$username = 'kronos';
$password = 'gl123';
$tablaus ='lotes';

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
//cargamos el archivo excel(extension *.xls) 
$objPHPExcel = PHPExcel_IOFactory::load('Documentos/Rel_Upload.xls'); 
$bloque=$_SESSION['desBloque'];
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
 
$referenciaLote=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
//$arrayNumero = explode("-", $referenciaLote, 2);

$longitud=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$ancho=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$espesor=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$encogimiento=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$peso=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
$tarima=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
$loteGral=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
$valNum=0;
$Sq=$mysqli->query("SELECT numeroLote from tblotes where bloque='".$bloque."' and loteGral='".$loteGral."' order by numeroLote desc");
$rum=$Sq->fetch_array();
$numero=$rum['numeroLote']+1;
while($valNum<1)
{
$SK=$mysqli->query("SELECT numeroLote from tblotes where numeroLote='".$numero."' and loteGral='".$loteGral."'");
if($SK->num_rows>0)
{
	$numero++;
}
else
{
	$valNum=1;
}
}

if($valNum==1)
{
	$result=$mysqli->query("SELECT referenciaLote from tblotes where referenciaLote='$referenciaLote'");
 $rau = $result->fetch_array();
 $SQL="";
 if(empty($rau['referenciaLote']))
 {
 	$SQL =$mysqli->query("INSERT INTO tblotes (bloque, loteGral,referenciaLote, longitud, peso, tarima, estado, shower, noop, ancho, espesor, encogimiento,numeroLote,baja) VALUES ('$bloque','$loteGral','$referenciaLote', '$longitud', '$peso', '$tarima', 0, 1, 0, '$ancho', '$espesor', '$encogimiento','$numero', 1)");

 }
 else
 {
 	$msj=1;
 }
$i++;
}
 
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
echo "<script>window.location='Materia_prima/MateriaPrima_Bloques.php?msj=".$mensaje."';</script>";
?>