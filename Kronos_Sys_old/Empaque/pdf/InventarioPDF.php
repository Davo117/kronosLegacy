<?php

include '../../Database/db.php';	
require('../../fpdf/fpdf.php');

if ($_GET['cdg']=='C') {    $presentacion='Pieza cortada'; $var='caja'; }
if ($_GET['cdg']=='R') {    $presentacion='Repetición continua';  $var='rollo';}

if ($_GET['prod']){
 $pdto = $MySQLiconn->query("SELECT * FROM impresiones WHERE id= '".$_GET['prod']."'");
 $pdtoI=$pdto->fetch_array();
$disenio=$pdtoI['descripcionDisenio'];
$impresion=$pdtoI['descripcionImpresion'];

    class PDF extends FPDF
    { // Cabecera de página
        function Header(){ 
            global $disenio;
            global $impresion;
            global $presentacion;

            $this->SetFont('Arial','B',8);
            $this->SetFillColor(224,7,8); 

            if (file_exists('../../pictures/lolo.jpg')==true)    { 
            $this->Image('../../pictures/lolo.jpg',10,10,0,20);     }

            $this->SetFont('Arial','B',8);
            $this->Cell(115,4,utf8_decode('Documento'),0,0,'R');
            $this->Cell(0.5,4,'',0,0,'R',true);
            $this->SetFont('Arial','I',8);
            $this->Cell(75,4,utf8_decode('Inventario de producto terminado'),0,1,'L');

            $this->SetFont('Arial','B',8);
            $this->Cell(115,4,utf8_decode('Diseño'),0,0,'R');
            $this->Cell(0.5,4,'',0,0,'R',true);
            $this->SetFont('Arial','I',8);
            $this->Cell(75,4,utf8_decode($disenio),0,1,'L');

            $this->SetFont('Arial','B',8);
            $this->Cell(115,4,utf8_decode('Impresión'),0,0,'R');
            $this->Cell(0.5,4,'',0,0,'R',true);
            $this->SetFont('Arial','I',8);
            $this->Cell(75,4,utf8_decode($impresion),0,1,'L');

            $this->SetFont('Arial','B',8);
            $this->Cell(115,4,utf8_decode('Presentación'),0,0,'R');
            $this->Cell(0.5,4,'',0,0,'R',true);  
            $this->SetFont('Arial','I',8);
            $this->Cell(75,4,utf8_decode($presentacion),0,1,'L');

            $this->Ln(15); 

            $this->SetFont('Arial','B',12);
            $this->SetX(27);
            $this->Cell(33,6,'Empaque',0,0,'L');
            $this->Cell(40,6,'Barcode',0,0,'C');
            $this->Cell(40,6,'Cantidad',0,1,'R');
        }
    }
    $pdf = new PDF('P','mm','letter');
    //$pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    $pdf->AliasNbPages();
    
    $pdf->AddPage();
    $pdtoDE = $MySQLiconn->query("SELECT * FROM impresiones WHERE id= '".$_GET['prod']."'");
    $total=0;
    while ($row=$pdtoDE->fetch_array()) {    
        $consulta=$MySQLiconn->query("SELECT * FROM $var where producto='".$row['descripcionImpresion']."' and baja='2'");    
        while ($row1=$consulta->fetch_array()) {
            $pdf->SetX(30);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(30,8,$row1['referencia'],0,0,'L');
            $pdf->SetFont('3of9','',24);
            $pdf->Cell(40,8,$row1['codigo'],0,0,'L');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(40,8,number_format($row1['piezas'],3,'.',''),0,0,'R');
            $pdf->Cell(5,5,'',0,0,'R'); 
            $pdf->Cell(5,5,'',1,0,'R'); 
            $pdf->Ln(8);
            $total+=$row1['piezas'];
        }  
    } 

    $pdf->SetFont('Arial','B',14);
    $pdf->SetX(115);
    $pdf->Cell(40,8,number_format($total,3,'.','').' mlls',0,1,'R');

    $pdf->Ln(9);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(195.9,4,'______________________________________________________',0,1,'C');
    $pdf->Cell(195.9,4,utf8_decode('Nombre y firma del responsable'),0,1,'C');
    $pdf->Output(); 
} 
else{ 
    echo '
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="UTF-8" />
      </head>
      <body>
        <div id="contenedor">
          <label class="modulo_nombre">Sin existencias...</label>
        </div>
      </body>
    </html>'; 
} ?>