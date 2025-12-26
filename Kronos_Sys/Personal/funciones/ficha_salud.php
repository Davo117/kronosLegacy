<?php
  include("../../Database/conexionphp.php");
  include '../../fpdf/fpdf.php';
  $SQL=$mysqli->query("SELECT f.*,CONCAT(e.Nombre,' ',e.apellido) as nombre,e.puesto FROM ficha_salud f inner join empleado e on e.numemple=f.no_empleado WHERE e.numemple='".$_GET['empleado']."'");
  if(mysqli_num_rows($SQL)!=0)
  {
    $row=$SQL->fetch_array();
  $empleado=$row['nombre'];
  $bloodtype=$row['sangre'];
  $puesto=$row['puesto'];
  $edad=$row['edad'];
  $medicamento1=$row['medicamento'];
  $dosis1=$row['dosis'];
  $observaciones1=$row['observaciones'];
  $medicamento2='';
  $dosis2='';
  $observaciones2='';
  $diabetes=$row['diabetes'];
  $presion=$row['presion'];
  $gastitis=$row['gastritis'];
  $colitis=$row['colitis'];
  $asma=$row['asma'];
  $vertigo=$row['vertigo'];
  $gota=$row['gota'];
  $migrana=$row['migrana'];
  $epilepsia=$row['epilepsia'];
  $rinones=$row['rinones'];
  $corazon=$row['corazon'];
  $otro=$row['otro'];
  $padecimientos=$row['padecimientos'];
  $haxorus=$row['fracturas'];
  $situaciones=$row['operaciones'];
  $alergmedic=$row['alergias'];
  $alergmedicS=$row['cuales'];
  $medicAler=$row['medim_aler'];

  $alergalim=$row['alerg_alim'];
  $alergalimS=$row['cuales_alim_alerg'];
  $alimAler=$row['medim_alerg_dosis'];

  $alergotro=$row['otro_factor'];
  $alergotroS=$row['cual_factor'];
  $otroAler='';
  $comment=$row['comentarios'];
  $another=$row['infor_add'];
  if(file_exists('../../Documentos/fotos/'.$row['no_empleado'].'.jpg'))
  {
    $foto='../../Documentos/fotos/'.$row['no_empleado'].'.jpg';
  }
  else
  {
    $foto='../../pictures/lolo.jpg';
  }
class PDF extends FPDF
  { // Cabecera de página
    

  }
  $pdf = new PDF('P','mm','letter');
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFont('Arial','B',12);
  $pdf->SetFillColor(255,255,255);
  $pdf->Cell(false,8,'FICHA DE SALUD DEL TRABAJADOR',1,1,'C',true);
  $pdf->Cell(41,4,'',"TB",0,'L',true);
  $pdf->Cell(false,4,'',T,1,'L',true);
  $pdf->Cell(1,38,'',LT,0,'L',true);
  $pdf->Image($foto,11,23,40,36);
  $pdf->SetX('51');
  $pdf->Cell(3,38,'',L,0,'C',true);

  $pdf->Cell(false,6,'',0,1,'C',true);
  $pdf->SetX('54');
  $pdf->Cell(40,6,'',0,0,'C',true);
  $pdf->Cell(70,6,'',0,0,'C',true);
  $pdf->Cell(25,6,'',0,0,'C',true);
  $pdf->Cell(10,6,'',0,1,'C',true);
  $pdf->SetX('54');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(45,4,utf8_decode('NOMBRE DEL TRABAJADOR:'),0,0,'L',true);
  $pdf->Cell(65,4,utf8_decode($empleado),0,0,'C',true);
  $pdf->Cell(25,4,utf8_decode('TIPO DE SANGRE:'),0,0,'L',true);
  $pdf->Cell(10,4,utf8_decode($bloodtype),0,1,'C',true);
  $pdf->SetX('54');
   $pdf->Cell(42,1,'',0,0,'L',true);
  $pdf->Cell(68,1,'',B,0,'C',true);
  $pdf->Cell(25,1,'',0,0,'L',true);
  $pdf->Cell(10,1,'',B,1,'C',true);
  $pdf->Ln(-8);
  $pdf->Cell(169,4,'',0,0,'C');  
  $pdf->Ln(24); 
  $pdf->SetX('54');
  $pdf->Cell(14,4,utf8_decode('PUESTO:'),0,0,'L',true);
  $pdf->Cell(55,4,utf8_decode($puesto),0,0,'C',true);
  $pdf->Cell(10,4,utf8_decode('EDAD:'),0,0,'L',true);
  $pdf->Cell(10,4,utf8_decode($edad),0,1,'C',true);
  $pdf->SetY('60');
  $pdf->Cell(41,1,'',T,0,'L',true);
  $pdf->SetX('54');
  $pdf->Cell(14,1,'',0,0,'L',true);
  $pdf->Cell(55,1,'',T,0,'C',true);
  $pdf->Cell(10,1,'',0,0,'L',true);
  $pdf->Cell(10,1,'',T,1,'C',true);
  $pdf->Ln(4);
  $pdf->SetFont('Arial','BI',8);
  $pdf->SetFillColor(0,0,0);
  $pdf->SetTextColor(255,255,255);
  $pdf->Cell(false,6,utf8_decode("PADECIEMIENTOS, ENFERMEDADES CRÓNICAS DEGENERATIVAS Y/O LESIONES FÍSICAS"),1,1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,6,'*ENFERMEDAD (ES) QUE PADECE',1,0,'C',true);
  $pdf->Cell(false,6,'*TRATAMIENTO AL QUE ES SOMETIDO',1,1,'C',true);

  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->Cell(40,4,'DIABETES',LT,0,'C',true);
  if($diabetes == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',LTR,0,'C',true);
  $pdf->Cell(22,4,'Medicamento:',LT,0,'C',true);
  $pdf->Cell(false,4,utf8_decode($medicamento1),"TBR",1,'L',true);

  $pdf->Cell(40,4,utf8_decode('PRESIÓN ALTA/BAJA'),L,0,'C',true);
  if($presion == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',LR,0,'C',true);
  $pdf->Cell(22,4,'Dosis:',L,0,'C',true);
  $pdf->Cell(false,4,utf8_decode($dosis1),"TBR",1,'L',true);

  $pdf->Cell(40,4,utf8_decode('GASTRITIS'),"L",0,'C',true);
  if($gastitis == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(22,4,'Observaciones:',L,0,'C',true);
  $pdf->Cell(false,4,utf8_decode($observaciones1),"TBR",1,'L',true);

  $pdf->Cell(40,4,utf8_decode('COLITIS'),"L",0,'C',true);
  if($colitis == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(22,4,'Medicamento:',"LT",0,'C',true);
  $pdf->Cell(false,4,utf8_decode($medicamento2),"TBR",1,'L',true);


  
  $pdf->Cell(40,4,utf8_decode('ASMA'),"L",0,'C',true);
  if($asma == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(22,4,'Dosis:',"L",0,'C',true);
  $pdf->Cell(false,4,utf8_decode($dosis2),"TBR",1,'L',true);

  $pdf->Cell(40,4,utf8_decode('VÉRTIGO'),"L",0,'C',true);
  if($vertigo == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(22,4,'Observaciones:',"L",0,'C',true);
  $pdf->Cell(false,4,utf8_decode($observaciones2),"TBR",1,'L',true);

  $pdf->Cell(40,4,utf8_decode('GOTA'),"L",0,'C',true);
  if($gota == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(FALSE,4,utf8_decode('*PADECIMIENTOS O LESIONES FÍSICAS:'),"LTR",1,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(40,4,utf8_decode('MIGRAÑA'),"L",0,'C',true);
  if($migrana == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $contar=strlen($padecimientos);
  if($contar>90)
  {
    $c1=substr($padecimientos, 0,90);
    $c2=substr($padecimientos, 90);
  }
  else
  {
    $c1=$padecimientos;
    $c2="";
  }
  $pdf->Cell(FALSE,4,utf8_decode($c1),"LTR",1,'C',true);

   $pdf->Cell(40,4,utf8_decode('EPILEPSIA'),"L",0,'C',true);
  if($epilepsia == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(FALSE,4,utf8_decode($c2),"LR",1,'C',true);

  $pdf->Cell(40,4,utf8_decode('RIÑONES'),"L",0,'C',true);
  if($rinones == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(FALSE,4,utf8_decode('*FRACTURAS,LUXACIONES Y/O ESGUINCES:'),"LTR",1,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(40,4,utf8_decode('CORAZÓN'),"L",0,'C',true);
  if($corazon == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $contar=strlen($haxorus);
  if($contar>90)
  {
    $c1=substr($haxorus, 0,90);
    $c2=substr($haxorus, 90);
  }
  else
  {
    $c1=$haxorus;
    $c2="";
  }
  $pdf->Cell(FALSE,4,utf8_decode($c1),"LTR",1,'C',true);

  $pdf->Cell(40,4,utf8_decode('OTRO'),"L",0,'C',true);
  if($otro == 'X')
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  else
  {
    $pdf->Cell(5,4,'',1,0,'C',true);
  }
  $pdf->Cell(25,4,'',"LR",0,'C',true);
  $pdf->Cell(FALSE,4,utf8_decode($c2),"LR",1,'C',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(130,6,utf8_decode('*OPERACIONES O SITUACIONES DONDE HAYA PERMANECIDO HOSPITALIZADO:'),1,0,'L',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->Cell(FALSE,6,utf8_decode(''),"LTR",1,'C',true);
  $pdf->Cell(FALSE,8,utf8_decode($situaciones),"LR",1,'C',true);
  $pdf->SetFont('Arial','BI',8);
  $pdf->SetFillColor(0,0,0);
  $pdf->SetTextColor(255,255,255);
  $pdf->Cell(FALSE,6,"Alergias","LR",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('*ALERGIAS A MEDICAMENTOS:'),"RLT",0,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(15,4,'SI',"LTB",0,'C',true);
  if($alergmedic=='X')
  {
    
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',"TRB",0,'C',true);
    $pdf->SetFont('Arial','',8);
  }

  $pdf->Cell(20,4,'NO',1,0,'C',true);
  if($alergmedic==0)
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',1,0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  $pdf->Cell(false,4,'',"LTR",1,'C',true);
   $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('Cuál (es):'),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($alergmedicS),"BR",1,'C',true);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("MEDICAMENTO PARA ATENCIÓN"),"LT",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($medicAler),"LRT",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("DE ALERGIA (INDICAR DÓSIS):"),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('*ALERGIAS A ALIMENTOS:'),"RLT",0,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(15,4,'SI',"LTB",0,'C',true);
  if($alergalim=='X')
  {
    
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',"TRB",0,'C',true);
    $pdf->SetFont('Arial','',8);
  }

  $pdf->Cell(20,4,'NO',"TLB",0,'C',true);
  if($alergalim==0)
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',"TRB",0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  $pdf->Cell(false,4,'',"LTR",1,'C',true);
   $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('Cuál (es):'),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($alergalimS),"BR",1,'C',true);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("MEDICAMENTO PARA ATENCIÓN"),"LT",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($alimAler),"LRT",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("DE ALERGIA (INDICAR DÓSIS):"),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('*ALERGIAS A OTRO FACTOR:'),"RLT",0,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(15,4,'SI',"LTB",0,'C',true);
  if($alergotro=='X')
  {
    
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',"TRB",0,'C',true);
    $pdf->SetFont('Arial','',8);
  }

  $pdf->Cell(20,4,'NO',"TLB",0,'C',true);
  if($alergotro==0)
  {
    $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->Cell(5,4,'4',"TRB",0,'C',true);
    $pdf->SetFont('Arial','',8);
  }
  $pdf->Cell(false,4,'',"LTR",1,'C',true);
   $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode('Cuál (es):'),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($alergotroS),"BR",1,'C',true);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("MEDICAMENTO PARA ATENCIÓN"),"LT",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode($otroAler),"LRT",1,'C',true);
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(193,193,193);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(70,4,utf8_decode("DE ALERGIA (INDICAR DÓSIS):"),"L",0,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(false,4,utf8_decode(""),"LR",1,'C',true);
  $pdf->SetFont('Arial','BI',8);
  $pdf->SetFillColor(0,0,0);
  $pdf->SetTextColor(255,255,255);
  $pdf->Cell(FALSE,6,utf8_decode("INFORMACIÓN ADICIONAL"),"LR",1,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(FALSE,6,utf8_decode($another),1,1,'C',true);
  $pdf->Cell(FALSE,6,"",1,1,'C',true);
  $pdf->Cell(FALSE,6,"",1,1,'C',true);
  $pdf->Cell(FALSE,6,"",1,1,'C',true);
  $pdf->Cell(FALSE,6,"",1,1,'C',true);

  $pdf->SetFont('Arial','BI',8);
  $pdf->SetFillColor(0,0,0);
  $pdf->SetTextColor(255,255,255);
  $pdf->Cell(FALSE,6,utf8_decode("OBSERVACIONES Y/O COMENTARIOS"),"LR",1,'C',true);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(FALSE,6,utf8_decode($comment),1,1,'C',true);
  $pdf->Cell(FALSE,6,"",1,1,'C',true);
  ////////////////////////////////////////////////
  $pdf->Output('CO-FR07', 'I');
  }
  else
  {
    echo "<center><h1>No se encontró la ficha de salud</h1></center>";
  }
  
 ?>