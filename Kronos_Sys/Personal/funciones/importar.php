<?php
require_once('../../Excel/Classes/PHPExcel/IOFactory.php'); 
require_once('../../Excel/Classes/PHPExcel.php');
require_once('../../Excel/Classes/PHPExcel/Reader/Excel2007.php');

$hostname = 'localhost';
$database = 'saturno';
$username = 'kronos';
$password = 'gl123';

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
//cargamos el archivo excel(extension *.xls) 
$objPHPExcel = PHPExcel_IOFactory::load('../../Documentos/FICHA_SALUD.xls'); 

$objPHPExcel->setActiveSheetIndex(0); 
$i=2;
while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
'') 
{   
/*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS*/
/** Invocacion de Clases necesarias */

//DATOS DE CONEXION A LA BASE DE DATOS

//$db = mysqli_select_db ("escuela",$mysqli) or die ("ERROR AL CONECTAR A LA BD");
 
$no_empleado=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
//$arrayNumero = explode("-", $referenciaLote, 2);

$sangre=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$edad=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$diabetes=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$presion=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$gastritis=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
$colitis=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
$asma=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
$vertigo=$objPHPExcel->getActiveSheet()->getCell('I'.$i)->getValue();
$gota=$objPHPExcel->getActiveSheet()->getCell('J'.$i)->getValue();
$migrana=$objPHPExcel->getActiveSheet()->getCell('K'.$i)->getValue();
$epilepsia=$objPHPExcel->getActiveSheet()->getCell('L'.$i)->getValue();
$rinones=$objPHPExcel->getActiveSheet()->getCell('M'.$i)->getValue();
$corazon=$objPHPExcel->getActiveSheet()->getCell('N'.$i)->getValue();
$otro=$objPHPExcel->getActiveSheet()->getCell('O'.$i)->getValue();
$medicamento=$objPHPExcel->getActiveSheet()->getCell('P'.$i)->getValue();
$dosis=$objPHPExcel->getActiveSheet()->getCell('Q'.$i)->getValue();
$observaciones=$objPHPExcel->getActiveSheet()->getCell('R'.$i)->getValue();
$padecimientos=$objPHPExcel->getActiveSheet()->getCell('S'.$i)->getValue();
$fracturas=$objPHPExcel->getActiveSheet()->getCell('T'.$i)->getValue();
$operaciones=$objPHPExcel->getActiveSheet()->getCell('U'.$i)->getValue();
$alergias=$objPHPExcel->getActiveSheet()->getCell('V'.$i)->getValue();
$cuales=$objPHPExcel->getActiveSheet()->getCell('W'.$i)->getValue();
$medim_aler=$objPHPExcel->getActiveSheet()->getCell('X'.$i)->getValue();
$alerg_alim=$objPHPExcel->getActiveSheet()->getCell('Y'.$i)->getValue();
$cuales_alim_alerg=$objPHPExcel->getActiveSheet()->getCell('Z'.$i)->getValue();
$medim_alerg_dosis=$objPHPExcel->getActiveSheet()->getCell('AA'.$i)->getValue();
$otro_factor=$objPHPExcel->getActiveSheet()->getCell('AB'.$i)->getValue();
$cual_factor=$objPHPExcel->getActiveSheet()->getCell('AC'.$i)->getValue();
$info_add=$objPHPExcel->getActiveSheet()->getCell('AD'.$i)->getValue();
$comentarios=$objPHPExcel->getActiveSheet()->getCell('AE'.$i)->getValue();

$Sic=$mysqli->query("SELECT no_empleado FROM ficha_salud  where no_empleado='$no_empleado'");
if(mysqli_num_rows($Sic)==0)
{

 $SQL =$mysqli->query("INSERT INTO ficha_salud (no_empleado, sangre,edad,diabetes,presion,gastritis,colitis, asma, vertigo, gota, migrana, epilepsia,rinones,corazon,otro,medicamento,dosis,observaciones,padecimientos,fracturas,operaciones,alergias,cuales,medim_aler,alerg_alim,cuales_alim_alerg,medim_alerg_dosis,otro_factor,info_add,comentarios) VALUES ('$no_empleado', '$sangre','$edad','$diabetes','$presion','$gastritis','$colitis', '$asma', '$vertigo', '$gota', '$migrana', '$epilepsia','$rinones','$corazon','$otro','$medicamento','$dosis','$observaciones','$padecimientos','$fracturas','$operaciones','$alergias','$cuales','$medim_aler','$alerg_alim','$cuales_alim_alerg','$medim_alerg_dosis','$otro_factor','$info_add','$comentarios')");
}
else
{
	$mysqli->query("UPDATE ficha_salud SET
		 sangre='$sangre',
		 edad='$edad',
		 diabetes='$diabetes',
		 presion='$presion',
		 gastritis='$gastritis',
		 colitis='$colitis',
		 asma='$asma',
		 vertigo='$vertigo',
		 gota='$gota',
		 migrana='$migrana',
		 epilepsia='$epilepsia',
		 rinones='$rinones',
		 corazon='$corazon',
		 otro='$otro',
		 medicamento='$medicamento',
		 dosis='$dosis',
		 observaciones='$observaciones',
		 padecimientos='$padecimientos',
		 fracturas='$fracturas',
		 operaciones='$operaciones',
		 alergias='$alergias',
		 cuales='$cuales',
		 medim_aler='$medim_aler',
		 alerg_alim='$alerg_alim',
		 cuales_alim_alerg='$cuales_alim_alerg',
		 medim_alerg_dosis='$medim_alerg_dosis',
		 otro_factor='$otro_factor',
		 info_add='$info_add',
		 comentarios='$comentarios' where no_empleado='$no_empleado'");
}


$i++;
}
//echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Materia_prima/MateriaPrima_Bloques.php'>";
echo "<script>window.location='../Personal.php?msj=1';</script>";
?>