<?php
include '../../Database/db.php'; 
include '../../fpdf/fpdf.php';


/*Son necesarios los siguientes GET (por ahora)
  $_GET['cdgimpresion']
  $_GET['cdgmaquina']
  $_GET['fchprograma']
  $_GET['cdgjuego']
*/  

  $pdtoImpresionSelect = $MySQLiconn->query("
    SELECT descripcionDisenio, descripcionImpresion, anchoPelicula
      FROM impresiones WHERE codigoImpresion = '".$_GET['cdgimpresion']."'");


  if ($pdtoImpresionSelect->num_rows > 0){ 
    $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['oplist_diseno'] = $regPdtoImpresion->descripcionDisenio;
    $_SESSION['oplist_impresion'] = $regPdtoImpresion->descripcionImpresion;
    $oplist_ancho = $regPdtoImpresion->anchoPelicula;
  } 

  $prodMaquinaSelect = $MySQLiconn->query("
    SELECT descripcionMaq,codigo FROM maquinas 
     WHERE codigo = '".$_GET['cdgmaquina']."'");
  
  if ($prodMaquinaSelect->num_rows > 0)
  { $regProdMaquina = $prodMaquinaSelect->fetch_object();

    $_SESSION['oplist_idmaquina'] = $regProdMaquina->codigo;
    $_SESSION['oplist_maquina'] = $regProdMaquina->descripcionMaq; }



  // Buscar los lotes
  $prodLoteSelect = $MySQLiconn->query("SELECT lotes.noLote,lotes.noop,lotes.tipo,juegoscilindros.repAlPaso
      FROM lotes, juegoscilindros, produccion WHERE (produccion.maquina='".$_SESSION['oplist_maquina']."' AND
           produccion.juegoCilindros='".$_GET['cdgjuego']."' AND produccion.fechaProduccion='".$_GET['fchprograma']."') AND (produccion.juegoLotes=lotes.juegoLote AND juegoscilindros.identificadorCilindro='".$_GET['cdgjuego']."' AND juegoscilindros.identificadorCilindro=produccion.juegoCilindros) ORDER BY lotes.noLote");


  if ($prodLoteSelect->num_rows > 0){
    $item = 0;
    while ($regProdLote = $prodLoteSelect->fetch_object()){ 
      $item++;
      $oplist_noop[$item] = $regProdLote->noop;
      $oplist_tipo[$item] = $regProdLote->tipo;
      $oplist_alpaso[$item] = $regProdLote->repAlPaso; 
    }
    $nLotes = $item; 
  }

  // Reformar las clases
  class PDF extends FPDF{ // Cabecera de página
    function Header(){ 
      global $oplist_ancho;
    
      $this->SetFont('Arial','B',8);
         $this->SetFillColor(224,7,8);
  
  
      if (file_exists('../../pictures/lolo.jpg')==true)    { 
      $this->Image('../../pictures/lolo.jpg',10,10,0,20); 
      }   

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Medición y control de refilado'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('PRO-FR08'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('2.0'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('23 de Abril de 2015'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('refilador'),0,1,'L'); 

      $this->Ln(4);

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Fecha de la orden'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,4,$_GET['fchprograma'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_diseno'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$_SESSION['oplist_impresion'],0,1,'L');

      $this->SetFont('Arial','I',8);
      $this->Cell(40,4,utf8_decode('Ancho plano'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','B',8);
      $this->Cell(60,4,$oplist_ancho.' mm',0,1,'L');

      $this->Ln(4); 
    }

    // Pie de página
    function Footer(){
      $this->SetY(-10);
      $this->SetFont('arial','',8);
      $this->Cell(0,3,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','B',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); 
    }
  }


  // Generar el archivo
  $pdf = new PDF('P','mm','letter');
  //$pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');
  $pdf->AliasNbPages();

  $pdf->AddPage();

  for ($item = 1; $item <= $nLotes; $item++){ 
    $pdf->Cell(5,4,$item,0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,4,'noop',0,0,'R');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,4,$oplist_noop[$item].' ['.$oplist_tipo[$item].']',0,1,'L');

    $pdf->SetX(45);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(12,4,'Operador',0,0,'R');
    $pdf->Cell(40,4,'_______________________',0,0,'L');
    $pdf->Cell(12,4,utf8_decode('Máquina'),0,0,'R');
    $pdf->Cell(40,4,'_______________________',0,0,'L');
    $pdf->Cell(16,4,'Fecha y hora',0,0,'R');
    $pdf->Cell(40,4,'________________________',0,1,'L');
    
    $pdf->Ln(1);

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(15,4,'Bobinas',0,0,'R');
    $pdf->Cell(20,4,'Ancho plano',1,0,'C');
    $pdf->Cell(20,4,'Longitud (m)',1,0,'C');
    $pdf->Cell(20,4,'Peso (kg)',1,0,'C');    
    $pdf->Cell(20,4,'No. Banderas',1,0,'C');
    
    $pdf->Cell(18,4,'Velocidad',0,0,'R');
    $pdf->Cell(20,4,'__________',0,0,'L');
    $pdf->Cell(18,4,utf8_decode('Tensión inicial'),0,0,'R');
    $pdf->Cell(20,4,'__________',0,0,'L');
    $pdf->Cell(6,4,utf8_decode('final'),0,0,'R');
    $pdf->Cell(20,4,'__________',0,1,'L');

    for ($subItem = 1; $subItem <= $oplist_alpaso[$item]; $subItem++){
      $pdf->Cell(15,4,'',0,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,0,'C');
      $pdf->Cell(20,4,'',1,1,'C'); 
    }
    $pdf->Ln(2); 
  }
  $pdf->Ln(3); 
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(24,4,'Observaciones',0,1,'L');
  $pdf->Cell(0,12,'',1,1,'C');

  $pdf->SetY(-20);

  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(0,4,'______________________________________________________',0,1,'C');
  $pdf->Cell(0,4,utf8_decode('Validó Supervisor'),0,1,'C');
  ////////////////////////////////////////////////
  $pdf->Output('PRO-FR08.pdf', 'I');  ?>