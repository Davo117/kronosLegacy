<?php
  include("../../Database/conexionphp.php");
  include("../../Database/SQLConnection.php");
  include '../../fpdf/fpdf.php';
  $salida=$_GET['salida'];
  $lstmov=array();
  $contador=1;
  $SQL=$mysqli->query("SELECT (SELECT concat(nombre,' ',apellido) from saturno.empleado where numemple=mv.datoextra1) as empleado,
                (SELECT d.nombre from saturno.departamento d where d.id=(select departamento from saturno.empleado where numemple=mv.datoextra1)) as area,time(en.fecha) as hora,date(en.fecha) as fecha,(SELECT identificadorUnidad from saturno.unidades where idUnidad=pr.unidad2) as unidad2, un.identificadorUnidad as nombreUnidad,pr.producto,mv.cantidad,mv.cantidad2,mv.costo FROM obelisco.salidas en 
                INNER JOIN obelisco.movimientos mv on mv.idDocumento = en.id
                INNER JOIN obelisco.productosCK pr on pr.producto = mv.producto 
                INNER JOIN saturno.unidades un on un.idUnidad = pr.unidad where mv.tipoDoc = 2 and en.id = '".$salida."'");
  $total=0;
while ($row=$SQL->fetch_array())
  {
    $lstSQL = sqlsrv_query($SQLconn,"SELECT p.cnombreproducto from admProductos p WHERE p.CIDPRODUCTO ='".$row['producto']."'");
    $runi=sqlsrv_fetch_array($lstSQL,SQLSRV_FETCH_ASSOC);
    $lstmov[$contador]['producto']=$runi['cnombreproducto'];
    $lstmov[$contador]['unidad']=$row['nombreUnidad'];
    $lstmov[$contador]['cantidad']=$row['cantidad2'];
    $lstmov[$contador]['unidad2']=$row['unidad2'];
    $lstmov[$contador]['cantidad2']=$row['cantidad'];
    $lstmov[$contador]['operador']=$row['empleado'];
    $lstmov[$contador]['area']=$row['area'];
    $lstmov[$contador]['liberacion']="N/A";
    $lstmov[$contador]['recibe']="";
    $lstmov[$contador]['hora']=$row['hora'];
    $_SESSION['fecha']=$row['fecha'];
    $total=$row['cantidad']+$total;
    $contador++;
  }
  class PDF extends FPDF
  { // Cabecera de página
    function Header()
    { //global $oplist_sustrato;

      $this->SetFont('Arial','B',8);

      $this->SetFillColor(224,7,8);

      if (file_exists('../../pictures/lolo.jpg')==true)
      { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); }
      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Bitácora de entrega de materiales'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('CO-FR07'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('5'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('14 de Febrero del 2018'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Almacenista'),0,1,'L'); 

      $this->SetFont('Arial','B',8);
      $this->Cell(135,4,utf8_decode('Disponibilidad'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Físico/Digital'),0,1,'L'); 

      $this->Ln(4); 
      $this->SetFont('Arial','B',8);    
      $this->Cell(9,4,'Fecha:',0,0,'R');
      $this->SetFont('Arial','',8);
      $this->Cell(0.5,4,$_SESSION['fecha'],0,0,'L');

        
      $this->SetFont('Arial','B',8);      
      $this->Cell(140,4,'Folio:',0,0,'R');
      $this->SetFont('Arial','I',8);// El 1 en la posición penúltima significa que se saltará a la siguiente linea
      $this->Cell(0.5,4,$_GET['salida'],0,1,'L');

      
      $this->Ln(29);
      $this->Ln(-25);

      $this->Ln(4); 
    }



    // Pie de página
    function Footer(){ 
      $this->SetY(-20);
      $this->SetFont('Arial','B',8);
      $this->Cell(195.9,4,'______________________________________________________',0,1,'C');
      $this->Cell(195.9,4,utf8_decode('Nombre y firma del operador'),0,1,'C');

      $this->SetY(-10);
      $this->SetFont('arial','B',8);
      $this->Cell(0,6,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s").'',0,1,'R'); 
      $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  $pdf = new PDF('P','mm','letter');
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(194,194,194);
  $pdf->Cell(55,8,'Producto',1,0,'C',true);
  $pdf->Cell(16,8,'Cant.',1,0,'C',true);
  $pdf->Cell(35,8,utf8_decode('Solicita'),1,0,'C',true);
  $pdf->Cell(30,8,utf8_decode('Área'),1,0,'C',true);
  $pdf->Cell(17,8,utf8_decode('Liberación'),1,0,'C',true);
  $pdf->Cell(35,8,utf8_decode('Recibe'),1,0,'C',true);
  $pdf->Cell(11,8,utf8_decode('Hora'),1,1,'C',true);
  $pdf->Ln(-8);
  $pdf->Cell(169,4,'',0,0,'C');  
  $pdf->Ln(8); 
  $pdf->Ln(-4);

  $pdf->SetFont('Arial','',7);
  $pdf->Cell(64,4,'',0,0,'C');  
  $pdf->Cell(25,4,'',0,0,'C');
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(34,4,'',0,1,'C');

  $pdf->SetFont('Arial','B',8);  

  for ($index = 1;$index<=count($lstmov); $index++)
  { $pdf->SetFont('Arial','B',12);

    $pdf->SetFont('Arial','',7);
    $pdf->Cell(55,8,$lstmov[$index]['producto'],1,0,'L');
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(16,4,number_format($lstmov[$index]['cantidad']).' '.$lstmov[$index]['unidad'],1,0,'C');
    
    $pdf->Cell(35,8,utf8_decode($lstmov[$index]['operador']),1,0,'C');
    $pdf->SetFont('Arial','',6.2);
    $pdf->Cell(30,8,utf8_decode($lstmov[$index]['area']),1,0,'C');
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(17,8,$lstmov[$index]['liberacion'],1,0,'C');
    $pdf->Cell(35,8,utf8_decode($lstmov[$index]['operador']),1,0,'C');
    $pdf->Cell(11,8,$lstmov[$index]['hora'],1,1,'C');
    $pdf->Ln(-4);
    $pdf->Cell(55,4,'',0,0,'C');
    $pdf->Cell(16,4,number_format($lstmov[$index]['cantidad2']).' '.$lstmov[$index]['unidad2'],1,0,'C');
    $pdf->Ln(-4);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(1,4,'',0,1,'L');}
    $pdf->ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(81,8,'Cantidad total:',1,0,'L');
    $pdf->SetFont('Arial','I',10);
    $pdf->SetX(10);
    $pdf->SetTextColor(224,7,8);
    $pdf->Cell(70,8,number_format($total,2),0,0,'R');
  ////////////////////////////////////////////////
  $pdf->Output('CO-FR07', 'I');
?>