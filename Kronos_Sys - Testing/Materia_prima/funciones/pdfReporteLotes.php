<?php
  include '../../Database/db.php';
  include '../../fpdf/fpdf.php';
  session_start();
  $user=$_SESSION['rol'];
  
  $progBloque_cdgbloque = $_GET['cdgbloque'];
  
$pesoE0=0;
$pesoE1=0;
$pesoE2=0;
$pesoE3=0;
$pesoE4=0;
$pesoE5=0;

 // $querySelect = $MySQLiconn->query("SELECT descripcionSustrato,codigo FROM $bloqueM WHERE sustrato='".$progBloque_cdgbloque."'");

  
  $progbloque_idbloque = $_GET['nameSus'];
  $progbloque_sustrato = $_GET['nameSus'];
  
  $querySelect = $MySQLiconn->query("SELECT tarima FROM tblotes WHERE bloque = '".$progBloque_cdgbloque."'
  GROUP BY tarima ORDER BY tarima");

  if ($querySelect->num_rows > 0){
    $item = 0;
    $nombreB=$_GET['nameSus'];

    while ($regQuery = $querySelect->fetch_object()){
      $item++;

      $progLote_tarima[$item] = $regQuery->tarima;

      $subQuerySelect = $MySQLiconn->query("SELECT estado,peso,idLote,referenciaLote,longitud,estado FROM lotes WHERE bloque='$nombreB' AND tarima='".$progLote_tarima[$item]."' ORDER BY bloque");

      
      if ($subQuerySelect->num_rows > 0){
        $subItem = 0;
      
        while ($regSubQuery = $subQuerySelect->fetch_object()){ 
          $subItem++;

          $progLote_idlote[$item][$subItem] = $regSubQuery->idLote;
          $progLote_lote[$item][$subItem] = $regSubQuery->referenciaLote;
          $progLote_longitud[$item][$subItem] = $regSubQuery->longitud;
          $progLote_peso[$item][$subItem] = $regSubQuery->peso;
          $progLote_sttlote[$item][$subItem] = $regSubQuery->estado;
          
          if ($regSubQuery->estado=='0') { $pesoE0+=$regSubQuery->peso; }
          if ($regSubQuery->estado=='1') { $pesoE1+=$regSubQuery->peso; }
          if ($regSubQuery->estado=='2') { $pesoE2+=$regSubQuery->peso; }
          if ($regSubQuery->estado=='3') { $pesoE3+=$regSubQuery->peso; }
          if ($regSubQuery->estado=='4') { $pesoE4+=$regSubQuery->peso; }
          if ($regSubQuery->estado=='5') { $pesoE5+=$regSubQuery->peso; }
        }
        $nLotes[$item]  = $subItem;
      }
    }
    $nTarimas = $item;
  }
  
  class PDF extends FPDF
  { // Cabeza de página
    function Header()
    { 
      
      global $progbloque_sustrato;
      global $progbloque_idbloque;
      
      if (file_exists('../../pictures/lolo.jpg')==true){ $this->Image('../../pictures/lolo.jpg',10,10,0,20); }
      
      $this->SetFillColor(224,7,8);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Listado de lotes por bloque'),0,1,'L');
  

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Sustrato'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($progbloque_sustrato),0,1,'L');
      
      $this->Ln(10);

      $this->SetFillColor(237,125,49);
      $this->SetFont('arial','B',8);

      $this->Cell(5,4,'',0,0,'L');
      $this->Cell(60,4,'Lote',1,0,'L',true);
      $this->Cell(20,4,'Metros',1,0,'L',true);
      $this->Cell(20,4,'Kilos',1,1,'L',true); 

    }
      
    // Pie de página
    function Footer(){ 
      global $user;
      if ($user=='') { $user='Invitado'; }
      $this->SetFont('arial','',9);
      $this->SetY(-8);
      $this->Cell(0,3,'Usuario: '.utf8_decode($user).' | '.utf8_decode('página '.$this->PageNo().' de {nb}'),0,1,'R');
      $this->SetFont('arial','I',8);
      $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }
  }

  $pdf=new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  //$pdf->SetDisplayMode(real, continuous);
  $pdf->AddPage();

  $pdf->SetFillColor(210,210,210);
  $colors=1;
  for ($item = 1; $item <= $nTarimas; $item++){ 
    $progLote_longitudes = 0;
    $progLote_pesos = 0;
    $progLote_rendimientos = 0;

    $pdf->SetFont('arial','',8);
    $progLote_pesototal=0;
  
    for ($subItem = 1; $subItem <= $nLotes[$item]; $subItem++){ 
      if ($colors%2 > 0) { $pdf->SetFillColor(248,215,205); } 
          else { $pdf->SetFillColor(252,236,232); }
    
      
      $pdf->Cell(5,4,'',0,0,'L');
      $pdf->Cell(10,4,$colors.' ',1,0,'R',true);
      $pdf->Cell(50,4,$progLote_lote[$item][$subItem].' ',1,0,'L',true);
      $pdf->Cell(20,4,number_format($progLote_longitud[$item][$subItem],3).' ',1,0,'R',true);
      $pdf->Cell(20,4,number_format($progLote_peso[$item][$subItem],3).' ',1,0,'R',true);
      
      if ($progLote_sttlote[$item][$subItem] == 0){ $pdf->Cell(5,4,' ',1,1,'R'); }
      else{ $pdf->Cell(5,4,' ',0,1,'R'); }

      $progLote_longitudes += $progLote_longitud[$item][$subItem];
      $progLote_pesos += $progLote_peso[$item][$subItem];
      $progLote_pesototal += $progLote_peso[$item][$subItem];
        $colors++;
    }
    $pdf->SetFillColor(237,125,49);
          
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(5,4,'',0,0,'L');
    $pdf->Cell(60,4,'Tarima '.$progLote_tarima[$item],1,0,'L',true);
    $pdf->Cell(20,4,number_format($progLote_longitudes,3).' ',1,0,'R',true);
    $pdf->Cell(20,4,number_format($progLote_pesos,3).' ',1,0,'R',true);
    $pdf->Cell(5,4,' ',0,1,'R');
  }

  $pdf->Ln(4.15);
  $pdf->SetFillColor(237,125,49);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(40,4,'Totales',1,1,'L',true);

$pdf->SetFillColor(248,215,205); 


  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($pesoE3,3),1,0,'R',true);   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Devuelto',1,1,'L',true);

$pdf->SetFillColor(252,236,232);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($pesoE0,3),1,0,'R',true);   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Activo',1,1,'L',true);

$pdf->SetFillColor(248,215,205); 

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($pesoE2,3),1,0,'R',true);   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Liberado',1,1,'L',true);  

$pdf->SetFillColor(252,236,232);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($pesoE1,3),1,0,'R',true);   
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Programados',1,1,'L',true);
  
$pdf->SetFillColor(248,215,205); 

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($pesoE5,3),1,0,'R',true);
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Procesados',1,1,'L',true);
  
$pdf->SetFillColor(237,125,49);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,number_format($progLote_pesototal,3),1,0,'R',true);
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Netos',1,1,'L',true); 

$pdf->SetFillColor(248,215,205);

  $pdf->Cell(85,4,'',0,0,'R');
  $pdf->SetFont('arial','B',8);
  $pdf->Cell(20,4,$nTarimas,1,0,'R',true);
  $pdf->SetFont('arial','I',8);
  $pdf->Cell(20,4,'Tarima(s)',1,1,'L',true);

  $pdf->Output('Listado de lotes por bloque '.$progbloque_idbloque.'.pdf', 'I');
?>